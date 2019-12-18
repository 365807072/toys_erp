<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>编辑玩具编号信息</title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
<style>
th{font-size:15px;}
.div_box{margin-top:10px;width:1000px;min-height:300px;max-height: 400px;border-bottom:1px dashed #111;}
</style>

</head>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>

<body  class = "mainBody">
<p class='tablestyle_title'> 编辑玩具编号信息</p>

<table cellpadding='5' cellspacing='10' width = "100%">
	<form enctype="multipart/form-data" method ='POST'  action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV20/editToysNumber">
    玩具ID：<input type="text" name="business_id" value="<?php echo ($res["business_id"]); ?>" readonly="readonly" ><br/>
    编号ID：<input type="text" name="tag_id" value="<?php echo ($res["tag_id"]); ?>" readonly="readonly" ><br/>
    当前是否可用：<input type="radio" name="is_use" value="0" <?php if($res["is_use"] == 0): ?>checked<?php endif; ?> >可用 &nbsp;&nbsp;&nbsp;
    	<input type="radio" name="is_use" value="1" <?php if($res["is_use"] == 1): ?>checked<?php endif; ?>>不可用 &nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="2" <?php if($res["is_use"] == 2): ?>checked<?php endif; ?>>维修 &nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="3" <?php if($res["is_use"] == 3): ?>checked<?php endif; ?>>未找到&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="4" <?php if($res["is_use"] == 4): ?>checked<?php endif; ?>>预约&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="5" <?php if($res["is_use"] == 5): ?>checked<?php endif; ?>>部分损坏不影响使用&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="6" <?php if($res["is_use"] == 6): ?>checked<?php endif; ?>>完全报废&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="7" <?php if($res["is_use"] == 7): ?>checked<?php endif; ?>>不可用-new&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="8" <?php if($res["is_use"] == 8): ?>checked<?php endif; ?>>活动预留&nbsp;&nbsp;&nbsp;
        <input type="radio" name="is_use" value="9" <?php if($res["is_use"] == 9): ?>checked<?php endif; ?>>售卖
        <input type="hidden" name="is_use_now" value="<?php echo ($res["is_use"]); ?>">
        <br>
    采购日期：
        <select name ="purchase_id">
            <option value ="0" <?php if($res["warehouse"] == 0 ): ?>selected<?php endif; ?> >无</option>
            <?php if(!empty($purRes)): if(is_array($purRes)): foreach($purRes as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if($res["purchase_id"] == $vo["id"] ): ?>selected<?php endif; ?>><?php echo ($vo["purchase_time"]); ?></option><?php endforeach; endif; endif; ?>
        </select>
    <br/>
    仓库名称：
    	<select name ="warehouse">
    		<option value ="0" <?php if($res["warehouse"] == 0 ): ?>selected<?php endif; ?> >无</option>
    		<option value ="1" <?php if($res["warehouse"] == 1 ): ?>selected<?php endif; ?> >大仓库</option>
    		<option value ="2" <?php if($res["warehouse"] == 2 ): ?>selected<?php endif; ?> >小仓库</option>
    	</select>
    <br/>
    房间：<input type="text" name="room" value="<?php echo ($res["room"]); ?>" >填数字（0、1、2...）<br/>
    货架：<input type="text" name="storage_rack" value="<?php echo ($res["storage_rack"]); ?>" >填数字（0、1、2...）<br/>
    层数：<input type="text" name="number" value="<?php echo ($res["number"]); ?>" >填数字（0、1、2...）<br/>
    <!-- 市：<input type="text" name="city_name" value="<?php echo ($res["city_name"]); ?>" ><br>
    县：<input type="text" name="county_name" value="<?php echo ($res["county_name"]); ?>" ><br>
    区：<textarea name="area_name" cols="100" rows="2"><?php echo ($res["area_name"]); ?></textarea><br> -->
    备注：<textarea name="remark" cols="100" rows="2"><?php echo ($res["remark"]); ?></textarea><br>
    <input type="hidden" name="id" value="<?php echo ($res["id"]); ?>" >
        <!--<font color="red">测试，勿填此项：</font><input type="text" name="seven" value=""><br>-->
   	&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='发布'>
	&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置'>
   </form>

</table>
<br/>
</body>
</html>