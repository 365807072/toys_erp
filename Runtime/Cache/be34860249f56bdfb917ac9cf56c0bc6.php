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
<div style="position:fixed; left:0px; top:0px; z-index:1; width:120%;" id="head">
<p class='tablestyle_title'> 玩具首页分类&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toysindexclassedit">添加玩具首页分类</a>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toysindexcard">会员卡首页展示</a>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toysheadlistnew">新版首页banner</a>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toysheadmobile">非会员前端展示客服手机号</a></p>

	<ul style="text-align:center;background-color:#6795B4;color:#fff;width:100%;">
        <li>分类标题</li>
		<!-- <li>更多分类</li> -->
		<li>分类</li>
		<li>展示形式</li>
		<!--<li>跳转目标</li>-->
		<li>排序</li>
		<li>修改时间</li>
		<!--<li>玩具1</li>-->
		<!--<li>图片1</li>-->
		<!--<li>玩具2</li>-->
		<!--<li>图片2</li>-->
		<!--<li>玩具3</li>-->
		<!--<li>玩具3</li>-->
		<!--<li>玩具4</li>-->
		<!--<li>图片4</li>-->
		<!--<li>玩具5</li>-->
		<!--<li>图片5</li>-->
		<li>操作</li>
		<!--<li style="width:300px;">外链地址</li>-->
    </ul>
</div>
	<ul style="height:78px;">
		<li style="width:100%;"></li>
	</ul>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><ul style="text-align:center;">

		<li>
	       <?php echo ($vo["class_title"]); ?>
         </li>

		<!-- <li>
			<?php echo ($vo["class_more_title"]); ?>
		</li> -->

		<li>
			<?php echo ($vo["category_name"]); ?>
		</li>

		<li>
			<?php echo ($vo["show_type"]); ?>
		</li>

		<!--<li>-->
			<!--<?php echo ($vo["source"]); ?>-->
		<!--</li>-->

		<li>
			<?php echo ($vo["rank"]); ?>
		</li>

		<li>
			<?php echo ($vo["post_create_time"]); ?>
		</li>

		<!--<li>-->
			<!--<?php echo ($vo["business_title1"]); echo ($vo["star1"]); ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php if(!empty($vo["img_1"])): ?>-->
				<!--<img src="<?php echo ($vo["img_1"]); ?>" alt="" style="width:140px;height:100px;">-->
			<!--<?php endif; ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php echo ($vo["business_title2"]); echo ($vo["star2"]); ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php if(!empty($vo["img_2"])): ?>-->
				<!--<img src="<?php echo ($vo["img_2"]); ?>" alt="" style="width:140px;height:100px;">-->
			<!--<?php endif; ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php echo ($vo["business_title3"]); echo ($vo["star3"]); ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php if(!empty($vo["img_3"])): ?>-->
				<!--<img src="<?php echo ($vo["img_3"]); ?>" alt="" style="width:140px;height:100px;">-->
			<!--<?php endif; ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php echo ($vo["business_title4"]); echo ($vo["star4"]); ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php if(!empty($vo["img_4"])): ?>-->
				<!--<img src="<?php echo ($vo["img_4"]); ?>" alt="" style="width:140px;height:100px;">-->
			<!--<?php endif; ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php echo ($vo["business_title5"]); echo ($vo["star5"]); ?>-->
		<!--</li>-->

		<!--<li>-->
			<!--<?php if(!empty($vo["img_5"])): ?>-->
				<!--<img src="<?php echo ($vo["img_5"]); ?>" alt="" style="width:140px;height:100px;">-->
			<!--<?php endif; ?>-->
		<!--</li>-->

		 <li>
			 <a href="http://checkpic.meimei.yihaoss.top/Others/toysindexclassedit?id=<?php echo ($vo["id"]); ?>">编辑</a><br>
	       <a href="http://checkpic.meimei.yihaoss.top/Others/toysindexclassdel?id=<?php echo ($vo["id"]); ?>" onclick="return confirm('确定要删除吗？')">删除</a>
         </li>

			<!--<li style="width:300px;">-->
				<!--<?php echo ($vo["web_link"]); ?>-->
			<!--</li>-->

    </ul><?php endforeach; endif; ?>


<br/>
<br/>


</body>
</html>