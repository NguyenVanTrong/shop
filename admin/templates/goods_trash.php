<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SHOP管理中心 - 商品回收站 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />

</head>
<body>

<h1>
<span class="action-span"><a href="goods.php?act=list">商品列表</a></span>
<span class="action-span1"><a href="index.php?act=main">SHOP管理中心</a> </span><span id="search_id" class="action-span1"> - 商品回收站 </span>
<div style="clear:both"></div>
</h1>

<form method="post" action="" name="listForm">

<div class="list-div" id="listDiv">

<table width="100%" cellspacing="1" cellpadding="2" id="list-table">
  <tr>
    <th>编号</th>
    <th>商品名称</th>
    <th>货号</th>
    <th>价格</th>
    <th>操作</th>
  </tr>
  <?php foreach($list as $row):?>
  <tr align="center" class="0" id="0_6">
    <td align="left" class="first-cell" ><?php echo $row['goods_id'];?></td>
    <td><?php echo $row['goods_name'];?></td>
    <td><?php echo $row['goods_sn'];?></td>
    <td><?php echo $row['shop_price'];?></td>
    <td width="24%" align="center">
      <a href="goods.php?act=restore&id=<?php echo $row['goods_id'];?>" title="编辑">还原</a> |
      <a href="goods.php?act=delele&id=<?php echo $row['goods_id'];?>" title="彻底删除">彻底删除</a>
    </td>
  </tr>
  <?php endforeach;?>
  </table>

  <table cellspacing="0" id="page-table">
  <tr>
    <td nowrap="true" align="right" style="background-color: rgb(255, 255, 255);">
      <?php echo $page_html;?>
    </td>
  </tr>
</table>

</div>
</form>


<div id="footer">

版权所有 &copy; 2013 青岛大学浩园4号楼411室</div>

</div>

</body>
</html>