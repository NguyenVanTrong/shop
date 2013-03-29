<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SHOP管理中心 - 商品列表 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />

</head>
<body>

<h1>
<span class="action-span"><a href="goods.php?act=add">添加商品</a></span>
<span class="action-span1"><a href="index.php?act=main">SHOP管理中心</a> </span><span id="search_id" class="action-span1"> - 商品列表 </span>
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
    <th>上架</th>
    <th>精品</th>
    <th>新品</th>
    <th>热销</th>
    <th>排序</th>
    <th>库存</th>
    <th>操作</th>
  </tr>
  <?php foreach($list as $row):?>
  <tr align="center" class="0" id="0_6">
    <td align="left" class="first-cell" ><?php echo $row['goods_id'];?></td>
    <td><?php echo $row['goods_name'];?></td>
    <td><?php echo $row['goods_sn'];?></td>
    <td><?php echo $row['goods_price'];?></td>
    <td><img src="images/<?php echo ($row['is_on_sale']==1)?'yes.gif':'no.gif';?>" /></td>
    <td><img src="images/<?php echo ($row['is_best']==1)?'yes.gif':'no.gif';?>" /></td>
    <td><img src="images/<?php echo ($row['is_new']==1)?'yes.gif':'no.gif';?>" /></td>
    <td><img src="images/<?php echo ($row['is_hot']==1)?'yes.gif':'no.gif';?>" /></td>
    <td><?php echo $row['sort_order'];?></td>
    <td align="right"><?php echo $row['goods_number'];?></td>
    <td width="24%" align="center">
      <a href="" title="编辑">编辑</a> |
      <a href="goods.php?act=del&id=<?php echo $row['goods_id'];?>" title="移除" onclick="return confirm('你确定要将商品：<?php echo $row['goods_name'];?> 放入回收站么？');">移除</a>
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