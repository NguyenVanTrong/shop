<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SHOP管理中心 - 编辑分类 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>
<span class="action-span"><a href="category.php?act=list">商品分类</a></span>
<span class="action-span1"><a href="index.php?act=main">SHOP 管理中心</a> </span><span id="search_id" class="action-span1"> - 编辑分类 </span>
<div style="clear:both"></div>
</h1>
<!-- start add new category form -->
<div class="main-div">

  <form action="category.php" method="post" name="theForm" >
  <table width="100%" id="general-table">
      <tr>
        <td class="label">分类名称:</td>
        <td>
          <input type='text' name='category_name' maxlength="20" value='<?php echo $row['category_name'];?>' size='27' />
        </td>
      </tr>
      <tr>
        <td class="label">上级分类:</td>
        <td>
          <select name="parent_id">
          <option value="0">顶级分类</option>
          <?php foreach($category_list as $item):?>
          <!-- 判断 当前的分类 是否是 正在编辑的分类的 父类 -->
            <option value="<?php echo $item['category_id'];?>" <?php echo ($item['category_id']==$row['parent_id'])?'selected':'';?> >
            <?php echo str_repeat('&nbsp;&nbsp;', $item['level']),  $item['category_name'];?>
            </option>
          <?php endforeach;?>
          </select>
        </td>
      </tr>

      <tr>
        <td class="label">排序:</td>
        <td>
          <input type='text' name='sort_order' maxlength="20" value='<?php echo $row['sort_order'];?>' size='27' />
        </td>
      </tr>

      </table>

      <div class="button-div">
        <input type="submit" value=" 确定 " />
        <input type="reset" value=" 重置 " />
      </div>
   <input type="hidden" name="act" value="update" />
   <input type="hidden" name="category_id" value="<?php echo $row['category_id'];?>" />
  </form>
</div>



<div id="footer">

版权所有 &copy; 2013 青岛大学浩园4号楼411室</div>

</div>

</body>
</html>