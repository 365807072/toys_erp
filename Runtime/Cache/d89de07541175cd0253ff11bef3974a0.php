<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />
<style>
	*{
		margin: 0;
		padding: 0;
	}
	ul{
		width:100%;
		display: -webkit-box;
	}
	ul li{
		width:150px;
		list-style: none;
		border-right:1px solid #8c8c8c;
		border-bottom:1px solid #8c8c8c;
	}
</style>

	<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
	<script language = "JavaScript" type = "text/javascript">
		$(document).ready(function(){
			window.onscroll=function(){
				var sl=-Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
				document.getElementById('head').style.left=sl+'px';

			};
		})
	</script>

</head>
<body  class = "mainBody" id="mainBody">
<div style="position:fixed; left:0px; top:0px; z-index:1; width:100%;" id="head">
<p class='tablestyle_title'> 玩具会员卡首页展示&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toysindexcardedit">添加会员卡首页展示</a></p>

	<ul style="text-align:center;background-color:#6795B4;color:#fff;width:100%;">
		<li>会员卡ID</li>
		<li>会员卡图片</li>
		<li>排序</li>
		<li>操作</li>
    </ul>
</div>
	<ul style="height:78px;">
		<li style="width:100%;"></li>
	</ul>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><ul style="text-align:center;">

		<li>
			<?php echo ($vo["card_id"]); ?>
		</li>

		<li>
			<?php if(!empty($vo["img"])): ?><img src="<?php echo ($vo["img"]); ?>" alt="" style="width:140px;height:100px;"><?php endif; ?>
		</li>

		<li>
			<?php echo ($vo["rank"]); ?>
		</li>

		 <li>
			 <a href="http://checkpic.meimei.yihaoss.top/Others/toysindexcardedit?id=<?php echo ($vo["id"]); ?>">编辑</a><br>
	         <a href="http://checkpic.meimei.yihaoss.top/Others/toysindexcarddel?id=<?php echo ($vo["id"]); ?>" onclick="return confirm('确定要删除吗？')">删除</a>
         </li>

    </ul><?php endforeach; endif; ?>


<br/>
<br/>


</body>
</html>