<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>卡操作</title>

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
<p class='tablestyle_title'> 卡操作</p>

<table cellpadding='5' cellspacing='10' width = "100%">
	<form enctype="multipart/form-data" method ='POST'  action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV20/PublicCard">
    【7-21前使用】封面图：<input type="file" name="old_cover" value="">
    <?php if(!empty($res["old_cover"])): ?><p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
           <img  src ="<?php echo ($res["old_cover"]); ?>" style="width:120px;height:120px;"/>
        </p><?php endif; ?>
    <br>
	【7-21后使用】封面图：<input type="file" name="cover" value="">
    <?php if(!empty($res["cover"])): ?><p style="width:1000px;height:150px;border-bottom:1px dashed #111;">
            <img  src ="<?php echo ($res["cover"]); ?>" style="width:120px;height:120px;"/>
        </p><?php endif; ?>
    <br>
	标题：<textarea name="business_title" value="" cols="100" rows="2"><?php echo ($res["business_title"]); ?></textarea><br>
    卡类型：
        <input type="radio" name="is_card" value="1" <?php if($res["is_card"] == 1 ): ?>checked<?php endif; ?> >月卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="2" <?php if($res["is_card"] == 2 ): ?>checked<?php endif; ?> >季卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="3" <?php if($res["is_card"] == 3 ): ?>checked<?php endif; ?> >半年卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="4" <?php if($res["is_card"] == 4 ): ?>checked<?php endif; ?>  >一年卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="5" <?php if($res["is_card"] == 5 ): ?>checked<?php endif; ?> >周卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="6" <?php if($res["is_card"] == 6 ): ?>checked<?php endif; ?> >升级卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="7" <?php if($res["is_card"] == 7 ): ?>checked<?php endif; ?> >服务卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_card" value="8" <?php if($res["is_card"] == 8 ): ?>checked<?php endif; ?> >次卡
        <br>
    服务卡服务次数：<input type="text" name="service_number" value="<?php echo ($res["service_number"]); ?>" ><br/>
    售卖价格：<input type="text" name="sell_price1" value="<?php echo ($res["sell_price"]); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    售卖市场价格：<input type="text" name="market_price1" value="<?php echo ($res["market_price"]); ?>" ><br/>
    总共卡数量：<input type="text" name="total_number" value="<?php echo ($res["total_number"]); ?>" ><br/>
    <?php if($res["is_card"] > 1 ): ?>描述:<textarea name="img_desc" value="" cols="100" rows="5"><?php echo ($res["business_des"]); ?></textarea><br>
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
    	<?php else: ?>
    	<div class="div_box">
		 	话题一描述:<textarea name="img_desc" value="" cols="100" rows="5"><?php echo ($res["business_des"]); ?></textarea><br>
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
		</div><?php endif; ?>
    
	<input type="hidden" name="id" value="<?php echo ($res["id"]); ?>">
   	&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='发布'>
	&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置'>
   </form>

</table>
<br/>
</body>
</html>