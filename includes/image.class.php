<?php

class image {
    private $upload_max_size;//允许上传的最大文件（字节单位）
    private $upload_allow_types;//允许上传的类型
    private $upload_dir;//上传文件的保存目录
    
         //创建资源时 使用的函数
    private $creators = array(
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/gif'  => 'imagecreatefromgif',
            'image/png'  => 'imagecreatefrompng'
        );
    //保存 或者 输出 图片时 所使用的函数
    private $makers = array(
                'image/jpeg' => 'imagejpeg',
                'image/gif'  => 'imagegif',
                'image/png'  => 'imagepng'
        );

    private $error_info = '';//错误信息

    public function __construct() {
        $this->upload_max_size = isset($GLOBALS['config']['ADMIN']['upload_max_size'])?$GLOBALS['config']['ADMIN']['upload_max_size']:2000000;//使用配置 如果没有配置 则使用2M
        $this->upload_allow_types = isset($GLOBALS['config']['ADMIN']['upload_allow_types'])?$GLOBALS['config']['ADMIN']['upload_allow_types']:'image/jpeg,image/png,image/gif';
        $this->upload_dir = defined('UPLOAD_DIR')?UPLOAD_DIR:'./';//如果定义了常量就是用这个常量，否则使用当前目录
    }


    /**
     * 完成文件上传
     *
     * @param $file array 保存的是当前上传的文件信息
     * @param $prefix string 文件标志的前缀字符串 默认为空
     *
     * @return mixed 如果上传成功，返回新建的文件名 否则返回 false。
     */

    public function upload($file, $prefix='') {

        //检查数组结构是否合法：
        //先检查是否是一个数组，然后检查是否存在 必要的五个元素。
        if(!(is_array($file) && array_key_exists('name', $file) && array_key_exists('tmp_name', $file) && array_key_exists('type', $file) && array_key_exists('size', $file) && array_key_exists('error', $file))) {
            $this->error_info = '数据格式不正确';
            return false;
        }


        //完整性的上传：
        //1，先判断 上传文件是否有错误 利用每个文件的 error元素来判断
        //error 表示错类型 其中 如果为零 表示 上传文件没有错误：
        if($file['error'] == 0) {
            //没有错误
            //先判断大小
            if($file['size'] <= $this->upload_max_size) {
                //大小合适
                //判断类型
                //允许的文件类型 利用strpos来判断
                if(strpos($this->upload_allow_types, $file['type']) !== false) {
                    //类型合适
                    //将文件 由 临时目录 移动到 项目规定的目录（当前目录）
                    //在移动时 需要求得 移动后的文件名
                    //原则 ：1，不重复；2，保持原来的后缀名；3，容易识别
                    //我们应该在name内将后缀名截取，可以利用字符串操作函数
                    //strrchr()可以帮助我们 ，函数可以找到 某个子字符串 在 完整字符串内 最后出现的位置。并且 返回 该位置后到字符串结尾的所有字符。
                    //加上前缀
                    //$new_name = $prefix . time().mt_rand(1000, 9999). strrchr($file['name'], '.');
                    $new_name = $prefix . $file['name'];
                    //有时 需要先判读目录是否存在，可能还需要自己创建。
                    if(move_uploaded_file($file['tmp_name'], $this->upload_dir . $new_name)) {
                        return $new_name;;
                    } else {
                        //记录错误信息
                        $this->error_info = '移动失败';
                        return false;
                    }
                } else {
                    $this->error_info = '类型不符合';
                    return false;
                }

            } else {
                //文件太大
                $this->error_info = '文件过大';
            }
        } else {
            //上传有错误
            //根据不同的错误类型 输出不同的错误信息。
            switch($file['error']) {
                case '1'://文件大小超过了 php 配置文件内 upload_max_filesize的限制：
                    $this->error_info = '文件大小超过允许上传的文件最大值';
                    break;
                case '2'://MAX_FILE_SIZE
                    $this->error_info = '文件大小超过PHP可以接受的,通过POST方法上传的最大值';
                    break;
                case '3'://
                    $this->error_info = '文件未上传完';
                    break;
                case '4':
                    $this->error_info = '文件没有上传';
                    break;
                case '6':
                    $this->error_info = '没有找到临时目录';
                    break;
                case '7':
                    $this->error_info = '向临时目录写文件时失败';
                    break;
            }

            return false;
        }
    }


    /**
     * 获得上传失败时的错误信息
     */
    public function getErrorInfo() {
        return $this->error_info;
    }


    /**
     *制作缩略图
     * @param $src_file string 文件的路径
     * @param $max_width int 缩略图的宽（范围的宽）
     * @param $max_height int 缩略图的高 (范围的高)
     * @param $prefix string 文件名前缀 默认为空字符串
     * @return mixed 成功返回图片名， 失败返回false
     */
    public function makeThumb($src_file, $max_width, $max_height, $prefix='') {
        //判断文件是否合理：
        if(!file_exists($src_file)) {
            return false;
        }

//        //创建原始图片资源
//        $src_img = imagecreatefromjpeg($src_file);
//
//        //先求出 原始图片 的大小：
//        $src_info = getimagesize($src_file);

        
                //获得文件的信息
        $src_info = getimagesize($src_file);
        //获得类型：
        $type = $src_info['mime'];

        //求得操作函数：
        $create_function = $this->creators[$type];
        //利用可变函数 完成操作
        //创建原始图片资源
        $src_img = $create_function($src_file);
//        $src_img = imagecreatefromjpeg($src_file);

        
        $src_width = $src_info[0];
        $src_height = $src_info[1];

        //利用原始图片的宽高 求出 缩略图的宽高。
        //$dst_width = $src_width * 0.1;
        //$dst_height = $src_height * 0.1;
        //我们在制作缩略图时 通常是 给出一个缩略图的 最大尺寸，
        //让缩略图，在保证比例的情况下，尽量大

        //给出最大宽高

        //求出比例
        $src_scale = $src_width / $src_height;
        $max_scale = $max_width / $max_height;

        //判断
        if($src_scale > $max_scale) {
            $dst_width = $max_width;
            $dst_height = $dst_width / $src_scale;
        } else {
            $dst_height = $max_height;
            $dst_width = $dst_height * $src_scale;
        }

        //创建缩略图资源
        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $back_color = imagecolorallocate($dst_img, 0xff, 0xff, 255);//背景白色
        imagefill($dst_img, 0, 0, $back_color);

        //复制 重置大小 采样
        $dst_x = round(($max_width-$dst_width)/2);
        $dst_y = round(($max_height-$dst_height)/2);

        if(imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $dst_width, $dst_height, $src_width, $src_height)) {
            //创建成功
            //保存
            //保存时 需要考虑 路径问题
            $thumb_file = $prefix . basename($src_file);
            imagejpeg($dst_img, UPLOAD_DIR . $thumb_file);
            $result = $thumb_file;
        } else {
            //失败
            //记录错误信息
            $result = false;
        }

        imagedestroy($dst_img);
        imagedestroy($src_img);

        return $result;

    }
    
 	/**
     * 为图片增加水印
     * @param $dst_file string  目标图片（需要增加水印）的图片名称
     * @param $src_file string  水印图片名 默认为空以为这使用系统配置的水印图片
     * @param $position int 水印图片的位置 假设 1，表示左上角，2表示右上角；3表示左下角，4表示右下角，5表示中间  默认为4
     * @param $pct 透明度 0-100 默认 60
     */
    public function addStamp($dst_file, $src_file='', $position=4, $pct=60) {
        //$dst_img = imagecreatefromjpeg($dst_file);
        //获得大小
        $dst_info = getimagesize($dst_file);
        if($src_file == '') {
            //没 使用默认
            $src_file = $GLOBALS['config']['ADMIN']['water_src_filename'];
        }
        //$src_img = imagecreatefromjpeg($src_file);
		$src_info = getimagesize($src_file);
//        //获得大小
//        $dst_info = getimagesize($dst_file);
//        $src_info = getimagesize($src_file);

        //
        $dst_create_function = $this->creators[$dst_info['mime']];
        $dst_img = $dst_create_function($dst_file);

        $src_create_function = $this->creators[$src_info['mime']];
        $src_img = $src_create_function($src_file);

        //处理位置
        switch($position) {
            case '1':
                $dst_x = 0;
                $dst_y = 0;
                break;
            case '4'://右下
                $dst_x = $dst_info[0] - $src_info[0];
                $dst_y = $dst_info[1] - $src_info[1];
                break;
            case '5'://中间
                $dst_x = ($dst_info[0] - $src_info[0])/2;
                $dst_y = ($dst_info[1] - $src_info[1])/2;
                break;
        }

        //合并
        if(imagecopymerge($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $src_info[0], $src_info[1], $pct)) {
//            //成功
//            imagejpeg($dst_img, $dst_file);
//            $return = basename($dst_file);//指向返回文件名 不带路径
            
            //成功
            $make_function = $this->makers[$dst_info['mime']];
            $make_function($dst_img, $dst_file);
            $return = basename($dst_file);//指向返回文件名 不带路径
        } else {
            $return = false;
        }

        imagedestroy($dst_img);
        imagedestroy($src_img);
        return $return;

    }

}