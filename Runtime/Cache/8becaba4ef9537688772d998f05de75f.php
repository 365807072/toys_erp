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
<p class='tablestyle_title'>非会员前端展示客服手机号</p>
<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexmobileEdit" method="post" onsubmit="return confirm('确定修改手机号？')">
	A：15652106280
	<br>
	B：13373920077
	<br><br>
	手机号：<input type="text" name="mobile" value="<?php echo ($mobile); ?>">
	<input type="submit" value="确认提交">
</form>

</body>
</html>