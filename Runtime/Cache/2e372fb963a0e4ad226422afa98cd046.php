<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>宝宝秀秀--发布值得买</title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
  <link type="text/css" href="/Tpl/Public/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
     <link type="text/css" href="/Tpl/Public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />

<style>
th{
	font-size:15px;
}

</style>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'>注册新用户</p>

<table cellpadding='5' cellspacing='10' width = "100%">
	<form enctype="multipart/form-data" method ='POST'  action="http://api.meimei.yihaoss.top/index.php?r=BabyShow/RegistWeb">
 	用户名：<input type="text" name="user_name" value=""><br>
	密 码：<input type="text" name="password" value=""><br>
	昵 称：<input type="text" name="nick_name" value=""><br>
	email：<input type="text" name="email" value=""><br>
	网页注册:<input type="text" name="is_web" value="1" readonly><br>
	头 像：<input type="file" name="avatar" value=""><br>
	
	选择归属用户：&nbsp;&nbsp;&nbsp;&nbsp;
	<select name="cid" id="cid">
		
        <option value="1">管理级用户</option>
		<option value="2">樊颖</option>
		<!-- <option value="4">老刘</option> -->
		<option value="5">杜志改</option> <!-- 蚊子 -->
		<option value="6">老邹</option>
		<option value="8">李静</option>
		<option value="20">话题专用</option>
		<option value="21">海燕</option>
		<!-- <option value="22">杨芳</option> -->
		<option value="25">美艳</option>
		<option value="26">杨芳</option>
		<option value="27">李玉清</option> <!-- 张明 -->
		<option value="28">朱晓娟</option>
		<option value="29">李永利</option>
		<option value="30">钟文东</option>
		<option value="31">韦杰</option>
		<!-- <option value="32">左旭东</option> -->
		<option value="33">荆哲</option>
		<!-- <option value="34">李晨</option> -->
		<!-- <option value="35">李永利</option> -->
       <!--  <option value="36">单晓彤</option> -->
        <option value="38">王春艳</option>
        <option value="39">项晖</option>
        <option value="40">王贫</option>
        <option value="41">张桐</option>
        <option value="42">谭硕</option>
        <option value="43">刘国华</option>
        <option value="44">刘瑞</option>
        <option value="45">李嘉欣</option>
        <option value="46">马思敏</option>
        <option value="37">刘同艳</option>
    </select><br>
	
   	<input type='submit' value='注册'>
	<input type='reset' value='取消'>
   </form>

</table>
<br/>
</body>
</html>