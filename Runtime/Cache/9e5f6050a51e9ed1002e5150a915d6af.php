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
<p class='tablestyle_title'> 编辑玩具分类</p>

<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexClassEdit" method="post" enctype='multipart/form-data'>
	<input type="hidden" name="id" value="<?php echo ($res["id"]); ?>"><br>
	标题：<input type="text" name="class_title" value="<?php echo ($res["class_title"]); ?>"><br>
	更多分类标题：<input type="text" name="class_more_title" value="<?php echo ($res["class_more_title"]); ?>"><br><br>
	一级分类：
	<input type="radio" name="category_id" value="0" <?php if( '0' == $res["category_id"] ): ?>checked<?php endif; ?> >全部分类
	<?php if(is_array($cate_info)): $i = 0; $__LIST__ = $cate_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="radio" name="category_id"
				<?php if($vo["category_id"] == $res["category_id"] ): ?>checked<?php endif; ?>
		value="<?php echo ($vo["category_id"]); ?>"  ><?php echo ($vo["title"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
	<br><br>
	展示样式：
	<select name="show_type">
		<option value="0">请选择</option>
		<option value="1" <?php if($res["show_type"] == '1'): ?>selected<?php endif; ?> >一张图</option>
		<option value="2" <?php if($res["show_type"] == '2'): ?>selected<?php endif; ?> >三张并列</option>
		<option value="3" <?php if($res["show_type"] == '3'): ?>selected<?php endif; ?> >一拖三</option>
		<option value="4" <?php if($res["show_type"] == '4'): ?>selected<?php endif; ?> >四张换行</option>
	</select>
	<br>
	排序：<input type="text" name="rank" value="<?php echo ($res["rank"]); ?>">（越大越靠上）<br><br>
	玩具1：<input type="text" name="business_id1" value="<?php echo ($res["business_id1"]); ?>"><input type="file" name="img1" value="">
	<br>玩具1标题：<?php echo ($res["business_title1"]); ?><br>
	跳转目的：
	<select name="source1">
		<option value="1" <?php if($res["source1"] == '1'): ?>selected<?php endif; ?> >玩具详情</option>
		<option value="3" <?php if($res["source1"] == '3'): ?>selected<?php endif; ?> >外部链接</option>
		<option value="2" <?php if($res["source1"] == '2'): ?>selected<?php endif; ?> >玩具列表</option>
	</select>
	<br>
	外链地址：<input type="text" name="web_link1" value="<?php echo ($res["web_link1"]); ?>">（只需跳转目的为外部链接的填写）<br>
	<?php if(!empty($res["img_1"])): ?><img src="<?php echo ($res["img_1"]); ?>" alt="" style="width:140px;height:100px;"><a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexpicDel&id=<?php echo ($res["id"]); ?>&img=1" onclick="return confirm('确定删除？')">删除</a><?php endif; ?><br>

	玩具2：<input type="text" name="business_id2" value="<?php echo ($res["business_id2"]); ?>"><input type="file" name="img2" value="">
	<br>玩具2标题：<?php echo ($res["business_title2"]); ?><br>
	跳转目的：
	<select name="source2">
		<option value="1" <?php if($res["source2"] == '1'): ?>selected<?php endif; ?> >玩具详情</option>
		<option value="3" <?php if($res["source2"] == '3'): ?>selected<?php endif; ?> >外部链接</option>
		<option value="2" <?php if($res["source1"] == '2'): ?>selected<?php endif; ?> >玩具列表</option>
	</select>
	<br>
	外链地址：<input type="text" name="web_link2" value="<?php echo ($res["web_link2"]); ?>">（只需跳转目的为外部链接的填写）<br>
	<?php if(!empty($res["img_2"])): ?><img src="<?php echo ($res["img_2"]); ?>" alt="" style="width:140px;height:100px;"><a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexpicDel&id=<?php echo ($res["id"]); ?>&img=2" onclick="return confirm('确定删除？')">删除</a><?php endif; ?><br>

	玩具3：<input type="text" name="business_id3" value="<?php echo ($res["business_id3"]); ?>"><input type="file" name="img3" value="">
	<br>玩具3标题：<?php echo ($res["business_title3"]); ?><br>
	跳转目的：
	<select name="source3">
		<option value="1" <?php if($res["source3"] == '1'): ?>selected<?php endif; ?> >玩具详情</option>
		<option value="3" <?php if($res["source3"] == '3'): ?>selected<?php endif; ?> >外部链接</option>
		<option value="2" <?php if($res["source1"] == '2'): ?>selected<?php endif; ?> >玩具列表</option>
	</select>
	<br>
	外链地址：<input type="text" name="web_link3" value="<?php echo ($res["web_link3"]); ?>">（只需跳转目的为外部链接的填写）<br>
	<?php if(!empty($res["img_3"])): ?><img src="<?php echo ($res["img_3"]); ?>" alt="" style="width:140px;height:100px;"><a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexpicDel&id=<?php echo ($res["id"]); ?>&img=3" onclick="return confirm('确定删除？')">删除</a><?php endif; ?><br>

	玩具4：<input type="text" name="business_id4" value="<?php echo ($res["business_id4"]); ?>"><input type="file" name="img4" value="">
	<br>玩具4标题：<?php echo ($res["business_title4"]); ?><br>
	跳转目的：
	<select name="source4">
		<option value="1" <?php if($res["source4"] == '1'): ?>selected<?php endif; ?> >玩具详情</option>
		<option value="3" <?php if($res["source4"] == '3'): ?>selected<?php endif; ?> >外部链接</option>
		<option value="2" <?php if($res["source1"] == '2'): ?>selected<?php endif; ?> >玩具列表</option>
	</select>
	<br>
	外链地址：<input type="text" name="web_link4" value="<?php echo ($res["web_link4"]); ?>">（只需跳转目的为外部链接的填写）<br>
	<?php if(!empty($res["img_4"])): ?><img src="<?php echo ($res["img_4"]); ?>" alt="" style="width:140px;height:100px;"><a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysIndexpicDel&id=<?php echo ($res["id"]); ?>&img=4" onclick="return confirm('确定删除？')">删除</a><?php endif; ?><br>

	<!--玩具5：<input type="text" name="business_id5" value="<?php echo ($res["business_id5"]); ?>">-->
	<!--<p>玩具5标题：<?php echo ($res["business_title5"]); ?></p>-->
	<!--<?php if(!empty($res["img_5"])): ?>-->
		<!--<img src="<?php echo ($res["img_5"]); ?>" alt="" style="width:140px;height:100px;"><?php echo ($res["business_title5"]); ?>-->
	<!--<?php endif; ?>-->
	<!--<br>-->
<font color="red">是否是一元卡指定玩具：</font>
	<select name="is_active">
		<option value="0" <?php if($res["is_active"] == '0'): ?>selected<?php endif; ?> >否</option>
		<option value="1" <?php if($res["is_active"] == '1'): ?>selected<?php endif; ?> >是</option>
	</select>
	<br><br>
	<input type="submit" value="确认提交">
</form>

</body>
</html>