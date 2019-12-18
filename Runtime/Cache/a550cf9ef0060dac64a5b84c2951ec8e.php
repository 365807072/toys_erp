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
<p class='tablestyle_title'> 运营商家列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/Index/npublicbusiness?business_type=2">添加运营商家</a></p>

<div style="margin:10px;margin-top:0px;">
<form  method ='POST' action="http://checkpic.meimei.yihaoss.top/Others/virtualbusinesslist" >
        &nbsp;&nbsp;&nbsp;&nbsp;商家ID：<input type="text" name="business_id" value="<?php echo ($business_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;商家标题：<input type="text" name="search_business_title" value="<?php echo ($search_business_title); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
</div>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
		<th width = "30%" align = "center">商家标题</th>
		<th width = "25%" align = "center">联系方式</th>
		<th width = "25%" align = "center">发布时间</th>
		<th width = "25%" align = "center">修改</th>
		<th width = "15%" align = "center">删除</th>
	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
        <td width ='10%' align = "center"><input  id ="input_<?php echo ($vo["id"]); ?>" name ="input_<?php echo ($vo["id"]); ?>" type = "checkbox" /><?php echo ($vo["id"]); ?>&nbsp;&nbsp;<?php echo ($vo["business_title"]); ?></td>
        <td width ='10%' align = "center"><?php echo ($vo["business_contact"]); ?></td>
        <td width ='10%' align = "center"><?php echo ($vo["create_time"]); ?></td>
   		<td width ='5%' height='200' align = "center">
	       <a href="http://checkpic.meimei.yihaoss.top/index.php/Index/update_business?id=<?php echo ($vo["id"]); ?>&business_type=2" target="_blank">修改</a>
         </td>

		 <td width ='5%' height='200' align = "center">
	       <a href="http://checkpic.meimei.yihaoss.top/index.php/Index/del_business?id=<?php echo ($vo["id"]); ?>&business_type=2">删除</a>
         </td>

    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>