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
<p class='tablestyle_title'> 编辑玩具会员卡展示</p>

<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexCardEdit" method="post" enctype='multipart/form-data'>
	<input type="hidden" name="id" value="<?php echo ($res["id"]); ?>"><br>
	会员卡ID：<input type="text" name="card_id" value="<?php echo ($res["card_id"]); ?>"><br>
	会员卡图片：<input type="file" name="img1" value="">
	<?php if(!empty($res["img"])): ?><img src="<?php echo ($res["img"]); ?>" alt="" style="width:140px;height:100px;"><?php endif; ?>
	<br>
	排序：<input type="text" name="rank" value="<?php echo ($res["rank"]); ?>">（越大越靠上）
	<br>
	<br>
	<input type="submit" value="确认提交">
</form>

</body>
</html>