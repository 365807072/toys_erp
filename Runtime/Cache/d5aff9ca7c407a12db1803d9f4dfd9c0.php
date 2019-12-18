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
<script src="/Tpl/Public/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script >
$(document).ready(function(){
    $('.check_list a').click(function(){ 
        $("#search_form").submit();
    });
})
</script>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 微信订单退款列表</p>
<div style="margin:10px;margin-top:0px;">
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/index.php/Index/wxrefundorderlist" >    
        &nbsp;&nbsp;&nbsp;&nbsp;订单ID：<input type="text" name="order_id" value="<?php echo ($order_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;订单号：<input type="text" name="order_num" value="<?php echo ($order_num); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;消费状态：
        <select name="order_status">
            <option value="0" >选择消费</option>
            <option value="1"  <?php if($order_status == 1 ): ?>selected<?php endif; ?>>未消费</option>
            <option value="2" <?php if($order_status == 2 ): ?>selected<?php endif; ?>>未支付</option>
            <option value="3" <?php if($order_status == 3 ): ?>selected<?php endif; ?>>已完成</option>
            <option value="4" <?php if($order_status == 4 ): ?>selected<?php endif; ?>>退款中</option>
            <option value="5" <?php if($order_status == 5 ): ?>selected<?php endif; ?>>已退款</option>
            <option value="6" <?php if($order_status == 6 ): ?>selected<?php endif; ?>>待上门付款</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
</div>



<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
        <th width = "5%" align = "center">订单Id</th>
        <th width = "5%" align = "center">微信支付来源</th>
        <th width = "5%" align = "center">微信支付单号</th>
        <th width = "10%" align = "center">订单号</th>
		<th width = "10%" align = "center">用户/用户邮箱</th>
		<th width = "15%" align = "center">商家</th>
		<th width = "10%" align = "center">支付方式</th>
        <th width = "10%" align = "center">消费状态</th>
        <th width = "5" align = "center">支付金额</th>
        <th width = "10%" align = "center">修改时间</th>
        <th width = "10%" align = "center">修改状态</th>
	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["id"]); ?></td>
            <td width ='5%' align = "center">
                <?php if($vo['wx_source'] == 2): ?>H5
                <?php else: ?>APP<?php endif; ?>
            </td>
            <td width ='5%' align = "center"><?php echo ($vo["trade_no"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["order_num"]); ?></td>
        	<td width ='10%'  align = "center">
            <?php echo ($vo["user_id"]); ?>—— <?php echo ($vo["nick_name"]); ?>—— <?php echo ($vo["email"]); ?>
        	</td>
			<td width ='15%' align = "center"><?php echo ($vo["business_title"]); ?></td>
            <td width ='10%' align = "center">
                <?php if($vo['payment'] == 1): ?>支付宝支付
                <?php elseif($vo['payment'] == 2): ?>微信支付
                <?php elseif($vo['payment'] == 3): ?>银联支付
                <?php elseif($vo['payment'] == 4): ?>上门支付
                <?php elseif($vo['payment'] == 5): ?>免费
                <?php elseif($vo['payment'] == 6): ?>红包
                <?php elseif($vo['payment'] == 7): ?>支付宝+红包
                <?php elseif($vo['payment'] == 8): ?>微信+红包
                <?php else: ?>其他<?php endif; ?>
            </td>
            <td width ='10%' align = "center">
                <?php if($vo['status'] == 1): ?>未消费
                <?php elseif($vo['status'] == 2): ?>未支付
                <?php elseif($vo['status'] == 3): ?>已完成
                <?php elseif($vo['status'] == 4): ?>退款中
                <?php elseif($vo['status'] == 5): ?>已退款<?php endif; ?>
            </td>
			<td width ='5%' align = "center"><?php echo ($vo["pay_price"]); ?></td>
   <td width ='15%' align = "center"><?php echo ($vo["post_create_time"]); ?></td>
            <td width ='10%' height='200' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/index.php/Index/wxupdate_order?id=<?php echo ($vo["id"]); ?>" target="_blank" >修改状态</a>
            </td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>