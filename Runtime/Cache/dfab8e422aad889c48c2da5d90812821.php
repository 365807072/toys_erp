<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>发布玩具</title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
<style>
th{font-size:15px;}
.div_box{margin-top:10px;width:1000px;min-height:300px;max-height: 400px;border-bottom:1px dashed #111;}
</style>

</head>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script language = "JavaScript" type = "text/javascript">
</script>
<body  class = "mainBody">
<p class='tablestyle_title'> 发布玩具</p>

<table cellpadding='5' cellspacing='10' width = "100%">
	<form enctype="multipart/form-data" method ='POST'  action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV20/PublicToysBusNew">
		<input type="hidden" name="business_id" value="<?php echo ($res["id"]); ?>" >
	封面图：<input type="file" name="cover" value=""><?php if($is_new == 0): if($res["root_img_id"] == 0): ?><img src="<?php echo ($res["pic1"]); ?>" alt="" style="width:100px;height:100px;">&nbsp;&nbsp;&nbsp;&nbsp;
		<!-- <a href="publictoysnewnumber.html?business_id=<?php echo ($res["id"]); ?>" >出/入库增加新编号</a> --><?php endif; endif; ?><br>
	玩具租赁方式：<input type="radio" name="way" value="1" <?php if($res["way"] == 1 ): ?>checked<?php endif; ?> >租赁&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="radio" name="way" value="0" <?php if($res["way"] == 0 ): ?>checked<?php endif; ?> >售卖<br>
	<!-- <?php if($res["way"] == 0 ): ?>checked<?php endif; ?> -->
	玩具类型：
        <input type="radio" name="is_card" value="0" <?php if($res["is_card"] == 0 ): ?>checked<?php endif; ?> >普通&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="9" <?php if($res["is_card"] == 9 ): ?>checked<?php endif; ?> >高端&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <br>
	标题：<textarea name="business_title" value="" cols="100" rows="2"><?php echo ($res["business_title"]); ?></textarea><br>
	玩具品牌：<textarea name="business_brand" value="" cols="100" rows="2"><?php echo ($res["business_brand"]); ?></textarea><br>
	售卖价格：<input type="text" name="sell_price1" value="<?php echo ($res["sell_price1"]); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;市场价格：<input type="text" name="market_price1" value="<?php echo ($res["market_price1"]); ?>" ><br><br>
    押金价格：<input type="text" name="need_price" value="<?php echo ($res["need_price"]); ?>" ><br/>
    租赁价格：<input type="text" name="sell_price2" value="<?php echo ($res["sell_price2"]); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    最高赔付价格：<input type="text" name="highest_price" value="<?php echo ($res["highest_price"]); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    租赁市场价格：<input type="text" name="market_price2" value="<?php echo ($res["market_price2"]); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;附件价格：<input type="text" name="parts_price" value="<?php echo ($res["parts_price"]); ?>" >
    <br/>
    服务费【租赁必填】：<input type="text" name="service_price" value="<?php echo ($res["service_price"]); ?>" >（如果不填默认15元）<br/>
    会员价格【高端】：<input type="text" name="member_price" value="<?php echo ($res["member_price"]); ?>" ><br/>
    总共玩具数量：<input type="text" name="total_number" value="<?php echo ($res["total_number"]); ?>" ><br/>
    适龄儿童：<textarea name="age" value="" cols="100" rows="2"><?php echo ($res["age"]); ?></textarea><br>
	关键词：<textarea name="key_words" value="" cols="100" rows="2"><?php echo ($res["key_words"]); ?></textarea><font style="color:red;">（多个关键词之间用“、”隔开，例：秋千、三合一；注：只需封面图上传关键词）</font><br>
		玩具重量：<input type="text" name="weight" value="<?php echo ($res["weight"]); ?>" ><br/>
		玩具尺寸：<input type="text" name="size" value="<?php echo ($res["size"]); ?>" ><br/>
	1号电池：<input type="text" name="battery_number1" value="<?php echo ($res["battery_number1"]); ?>" ><br/>
	2号电池：<input type="text" name="battery_number2" value="<?php echo ($res["battery_number2"]); ?>" ><br/>
	3号电池：<input type="text" name="battery_number3" value="<?php echo ($res["battery_number3"]); ?>" ><br/>
	4号电池：<input type="text" name="battery_number4" value="<?php echo ($res["battery_number4"]); ?>" ><br/>
	5号电池：<input type="text" name="battery_number5" value="<?php echo ($res["battery_number5"]); ?>" ><br/>
	6号电池：<input type="text" name="battery_number6" value="<?php echo ($res["battery_number6"]); ?>" ><br/>
	7号电池：<input type="text" name="battery_number7" value="<?php echo ($res["battery_number7"]); ?>" ><br/>
	纽扣电池：<input type="text" name="battery_number8" value="<?php echo ($res["battery_number8"]); ?>" ><br/>
    分类： <?php if(is_array($cate_info)): $i = 0; $__LIST__ = $cate_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="checkbox" name="category[]"
		<?php if(!empty($categoryInfo)): if(is_array($categoryInfo)): $i = 0; $__LIST__ = $categoryInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i; if($vo["category_id"] == $data ): ?>checked<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>
		value="<?php echo ($vo["category_id"]); ?>"  ><?php echo ($vo["title"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?><br/>


		<?php if($is_new == 1): ?><div class="div_box">
				话题一描述:<textarea name="img_desc" value="" cols="100" rows="5"></textarea><br>
				话题一图片1：<input type="file" name="img1" value=""><br>
				话题一图片2：<input type="file" name="img2" value=""><br>
				话题一图片3：<input type="file" name="img3" value=""><br>
				话题一图片4：<input type="file" name="img4" value=""><br>
			</div>
			<div class="div_box">
				话题二描述:<textarea name="img_desc2" value="" cols="100" rows="5"></textarea><br>
				话题二图片1：<input type="file" name="img5" value=""><br>
				话题二图片2：<input type="file" name="img6" value=""><br>
				话题二图片3：<input type="file" name="img7" value=""><br>
				话题二图片4：<input type="file" name="img8" value=""><br>
			</div>
			<div class="div_box">
				话题三描述:<textarea name="img_desc3" value="" cols="100" rows="5"></textarea><br>
				话题三图片1：<input type="file" name="img9" value=""><br>
				话题三图片2：<input type="file" name="img10" value=""><br>
				话题三图片3：<input type="file" name="img11" value=""><br>
				话题三图片4：<input type="file" name="img12" value=""><br>
			</div>
			<div class="div_box">
				话题四描述:<textarea name="img_desc4" value="" cols="100" rows="5"></textarea><br>
				话题四图片1：<input type="file" name="img13" value=""><br>
				话题四图片2：<input type="file" name="img14" value=""><br>
				话题四图片3：<input type="file" name="img15" value=""><br>
				话题四图片4：<input type="file" name="img16" value=""><br>
			</div>
			<div class="div_box">
				话题五描述:<textarea name="img_desc5" value="" cols="100" rows="5"></textarea><br>
				话题五图片1：<input type="file" name="img17" value=""><br>
				话题五图片2：<input type="file" name="img18" value=""><br>
				话题五图片3：<input type="file" name="img19" value=""><br>
				话题五图片4：<input type="file" name="img20" value=""><br>
			</div><?php endif; ?>

<?php if($is_new == 0): if($res["root_img_id"] != 0): ?>描述:<textarea name="img_desc" value="" cols="100" rows="5"><?php echo ($res["business_des"]); ?></textarea><br>
	<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
		图片一：<input type="file" name="img1" value="">
		<?php if(!empty($res["pic1"])): ?><img  src ="<?php echo ($res["pic1"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
	</p>
	<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
		图片二：<input type="file" name="img2" value="">
		<?php if(!empty($res["pic2"])): ?><img  src ="<?php echo ($res["pic2"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
	</p>
	<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
		图片三：<input type="file" name="img3" value="">
		<?php if(!empty($res["pic3"])): ?><img  src ="<?php echo ($res["pic3"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
	</p>
	<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
		图片四：<input type="file" name="img4" value="">
		<?php if(!empty($res["pic4"])): ?><img  src ="<?php echo ($res["pic4"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
	</p>
		<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
			图片五：<input type="file" name="img5" value="">
			<?php if(!empty($res["pic5"])): ?><img  src ="<?php echo ($res["pic5"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
		</p>
		<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
			图片六：<input type="file" name="img6" value="">
			<?php if(!empty($res["pic6"])): ?><img  src ="<?php echo ($res["pic6"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
		</p>
		<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
			图片七：<input type="file" name="img7" value="">
			<?php if(!empty($res["pic7"])): ?><img  src ="<?php echo ($res["pic7"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
		</p>
		<p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
			图片八：<input type="file" name="img8" value="">
			<?php if(!empty($res["pic8"])): ?><img  src ="<?php echo ($res["pic8"]); ?>" style="width:120px;height:120px;"/><?php endif; ?>
		</p><?php endif; endif; ?>
   	&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='发布'>
	&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置'>


   </form>

</table>
<br/>
</body>
</html>