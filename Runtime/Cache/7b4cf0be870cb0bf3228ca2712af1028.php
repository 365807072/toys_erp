<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />
<style>
div{
	display:inline;
}

th{
	font-size:15px;
}
</style>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 玩具二级分类列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toyscategory">添加二级玩具分类</a></p>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
        <th width = "5%" align = "center">分类ID</th> 
        <th width = "10%" align = "center">分类标题</th>
        <th width = "10%" align = "center">分类图片</th>
        <th width = "10%" align = "center">排序</th>
        <th width = "5%" align = "center">编辑</th>   
		<th width = "5%" align = "center">删除</th>	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
        <th width = "5%" align = "center"><?php echo ($vo["category_id"]); ?></th>
        <th width = "10%" align = "center"><?php echo ($vo["title"]); ?></th>
        <th width = "10%" align = "center"><img src="<?php echo ($vo["img"]); ?>" alt="" style="width:100px;height:100px;"></th>
        <th width = "10%" align = "center"><?php echo ($vo["rank"]); ?></th>
        <td width ='5%' height='200' align = "center">
           <a href="http://checkpic.meimei.yihaoss.top/Others/toyscategory?id=<?php echo ($vo["id"]); ?>" target="_blank">编辑</a>
         </td>	   
		 <td width ='5%' height='200' align = "center">
	       <a href="http://checkpic.meimei.yihaoss.top/Others/operator_toys_category?id=<?php echo ($vo["id"]); ?>">删除</a>
         </td>

    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>