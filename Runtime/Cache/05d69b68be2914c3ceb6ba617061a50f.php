<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo C('TITLE');?></title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />

<style>
th{
	font-size:15px;
}


</style>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 活动红包列表   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="publicactivitypacket">发布活动红包</a></p>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10"  >
		<th width = "5%" align = "center">红包ID</th>
		<th width = "5%" align = "center">抢红包用户</th>
		<th width = "10%" align = "center">商家信息</th>
		<th width = "5%" align = "center">套餐</th>
		<th width = "5%" align = "center">金额</th>
		<th width = "10%" align = "center">过期时间</th>
		<th width = "5%" align = "center">删除</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
    	<td width ='5%' align = "center"><?php echo ($vo["id"]); ?></td>
        <td width ='5%' align = "center"><?php echo ($vo["grab_user_id"]); ?><br/><?php echo ($vo["grab_nick_name"]); ?></td>
		<td width ='10%' align = "center"><?php echo ($vo["activity_business_id"]); ?><br/><?php echo ($vo["business_title"]); ?></td>
		<td width ='5%' align = "center">
			<?php if($vo["activity_business_package"] == 1): ?>套餐一
 <?php elseif($vo["activity_business_package"] == 2): ?>套餐二
 <?php elseif($vo["activity_business_package"] == 3): ?>套餐三
 <?php elseif($vo["activity_business_package"] == 4): ?>套餐四
 <?php else: ?> 请选择<?php endif; ?>
		</td>
		<td width ='5%' align = "center"><?php echo ($vo["packet_price"]); ?></td>
		<td width ='10%' align = "center"><?php echo ($vo["expiry"]); ?></td>
		<td width ='5%' align = "center"><a href="http://checkpic.meimei.yihaoss.top/Others/operator_activitypacket?id=<?php echo ($vo["id"]); ?>">删除</a>
		</td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<div class="check_list"><?php echo ($page); ?></div>

</body>
</html>