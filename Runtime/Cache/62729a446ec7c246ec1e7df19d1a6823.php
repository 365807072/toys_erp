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
<p class='tablestyle_title'> 玩具一级分类列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toyscategorytop">添加一级玩具分类</a></p>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
        <th width = "5%" align = "center">分类ID</th> 
        <th width = "10%" align = "center">标题</th>
        <th width = "10%" align = "center">一级分类标题</th>
        <th width = "10%" align = "center">二级分类标题</th> 
		<th width = "5%" align = "center">操作</th>	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
        <th width = "5%" align = "center"><?php echo ($vo["id"]); ?></th>
        <th width = "10%" align = "center"><?php echo ($vo["title"]); ?></th>
        <th width = "10%" align = "center"><?php echo ($vo["title1"]); ?></th>
        <th width = "10%" align = "center"><?php echo ($vo["title2"]); ?></th>
		<td width ='5%' height='200' align = "center">
            <a href="http://checkpic.meimei.yihaoss.top/Others/toyscategorytop?id=<?php echo ($vo["id"]); ?>" target="_blank">编辑</a>
            <br/>
            <a href="http://checkpic.meimei.yihaoss.top/Others/operator_toys_category_top?id=<?php echo ($vo["id"]); ?>">删除</a>
        </td>

    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>