<?php

class page {

    //属性
    //方法

    /**
     * @param $total int 总的记录数
     * @param $page int 页数
     * @param $pagesize int 每页的记录数
     * @param $url string 请求的脚本 例如 goods.php
     * @param $arguments array 请求时携带的参数array('act' => 'list')
     *
     * @return string 处理好的 用户接口 html代码
     * @example
     * $page_object = new page;
     * echo $page_object->show(20, 3, 5, 'goods.php', array('act'=>'list'));
     */
    public function show($total, $page, $pagesize, $url, $arguments) {

        //定义一个变量 用于保存最终的字符串
        //分页开启部分
        $page_html_start = <<<HTML
            <div id="turn-page">
HTML;
        //分页信息部分
        $total_page = ceil($total/$pagesize);
        $page_html_info = <<<HTML
总计 <span id="totalRecords">{$total}</span> 个记录，
分为 <span id="totalPages">{$total_page}</span> 页，
当前第 <span id="pageCurrent">{$page}</span> 页，
每页 <span id="pagesize">{$pagesize}</span> 条
HTML;
        //上一页 下一页 翻页部分
        //需要求出 请求的地址 和 携带的参数
        $request_url = $url . '?';
        //array('act'=>'list')  act=list&
        foreach($arguments as $key => $value) {
            $request_url .= $key . '=' . $value . '&';
        }
        //拼凑page参数
        $request_url .= 'page=';
        //goods.php?act=list&page=
        //求出页数
        $prev = ($page==1)?'1':$page-1;
        $next = ($page==$total_page)?$total_page:$page+1;
        $page_html_link = <<<HTML
<span id="page-link">
<a href="{$request_url}1">第一页</a>
<a href="{$request_url}{$prev}">上一页</a>
<a href="{$request_url}{$next}">下一页</a>
<a href="{$request_url}{$total_page}">最末页</a>
HTML;
        //拼凑下拉列表翻页部分：
        $page_html_select = <<<HTML

<select id="gotoPage" onchange="window.location.href='{$request_url}'+this.value;">
HTML;
        //循环生成 所有可能的页数
        for($i=1;$i<=$total_page; $i++) {
            $is_selected = ($page == $i)?'selected':'';
            $page_html_select .= <<<HTML
<option value="{$i}" {$is_selected}>{$i}</option>
HTML;
        }
        //select结束标签
        $page_html_select .= '</select>';

        //拼凑结束部分
        $page_html_end = '</span></div>';

        //返回
        return $page_html_start . $page_html_info . $page_html_link . $page_html_select . $page_html_end;
    }
}