<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />

<style>
th{font-size:15px;}
.div_box{margin-top:10px;width:1000px;min-height:300px;max-height: 400px;border-bottom:1px dashed #111;}
</style>

</head>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script language = "JavaScript" type = "text/javascript">

function province(obj){
		
		var province=obj.value;
		if(province==0){
			return;
		}
		var select = document.getElementById('login_user_id');
		
		var successProcess=function(data){
			var selectOptions = select.options;  
			var optionLength = selectOptions.length;  
			for(var i=0;i <optionLength;i++)  
			{  
				select.removeChild(selectOptions[0]);  
			}  
			var dataArray = eval(data);
			 for(var i = 0; i<dataArray.length;i++){
				var login_user_id = dataArray[i]['id'];
				var nick_name = dataArray[i]['nick_name'];
				select.options.add(new Option(nick_name,login_user_id));
			}
		}
		$.ajax({
			type: 'GET',
			url: "http://checkpic.meimei.yihaoss.top/Index/getUser1/cid/"+province,
			success: successProcess,
		});
		
 }

</script>
<body  class = "mainBody">
<p class='tablestyle_title'> 管理 </p>

<table cellpadding='5' cellspacing='10' width = "100%">
	<form enctype="multipart/form-data" method ='POST'  action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV20/PublicManage">
	来源：
        <input type="radio" name="source" value="1" checked  >帖子&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="source" value="2" >关注群
        <br>
    帖子(或群)ID：<input type="text" name="img_id" value=""><br>
	帖子赞人数：<input type="text" name="admire_count" value=""><br>
	关注群人数：<input type="text" name="idol_count" value=""><br>

   	&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='发布'>
	&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置'>
   </form>

</table>
<br/>
</body>
</html>