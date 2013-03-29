<?php
class captcha {
    //
    private $captcha_key = 'captcha_code';//session内的元素的下标
    private $width;//图片的宽
    private $height;//图片的高

    /**
     *构造方法
     * @param $width integer 宽
     * @param $height integer 高
     */
    public function __construct() {

    }

    public function generate($width, $height) {
    	//初始化属性
        $this->width = $width;
        $this->height = $height;
        //制作验证码
        $chars = array_merge(range('a', 'z'), range('A', 'Z'), range('1','9'));
        $rand_keys = array_rand($chars, 4);
        shuffle($rand_keys);
        foreach($rand_keys as $key) {
            $char[] = $chars[$key];
        }

        //放入到session
        @session_start();
        //将字符串连接起来，保存到session内
        //implode(), 如果不写第一个参数，则表示空字符连接。
        $_SESSION[$this->captcha_key] = implode($char);


        //处理图片
        //step1
        $img = imagecreatetruecolor($this->width, $this->height);
        //step2
        //填充背景
        // 先设计颜色，分配到图片上
        $backgroud_color = imagecolorallocate($img, mt_rand(180, 255), mt_rand(180, 255), mt_rand(180, 255));
        //填充到图片上
        imagefill($img, 0, 0, $backgroud_color);


        //增加一些干扰线段
        //先分配线段颜色
        for($i=0; $i<5; $i++) {
            $line_color = imagecolorallocate($img, mt_rand(150, 200), mt_rand(150, 200), mt_rand(150, 200));
            //画线
            imageline($img, mt_rand(0, $this->width-1), mt_rand(0, $this->height-1), mt_rand(0, $this->width-1), mt_rand(0, $this->height-1), $line_color);
        }

        //增加干扰像素
        for($i = 0; $i<100; $i++) {
            $pixel_color = imagecolorallocate($img, mt_rand(150, 200), mt_rand(150, 200), mt_rand(150, 200));
            imagesetpixel($img, mt_rand(0, $this->width-1), mt_rand(0, $this->height-1), $pixel_color);
        }

        //先获得可以在资源上使用的颜色
        $string_color = imagecolorallocate($img, mt_rand(80, 150), mt_rand(80, 150), mt_rand(80, 150));//
        //将获得的字符串 写到图片上
        imagestring($img, 5, 55, 2, $_SESSION[$this->captcha_key], $string_color);
        //step3
        //输出到浏览器
        //设置回应头，我们要输出到浏览器的内容是 png格式的图片
        header('Content-Type:image/png');
        imagepng($img);//如果没有第二个参数，就表示是输出的意思。
        //step 4
        imagedestroy($img);

    }
    
	/**
     * 判断用户提交的验证码是否正确
     * @param $code string 用户提交的数据
     * @return bool 一致则返回true 不一致返回false
     */
    public function checkCaptcha($code) {
        return strtolower($code) == strtolower($_SESSION[$this->captcha_key]);
    }
}

//$captcha = new captcha();
//$captcha->generate(145, 20);

?>