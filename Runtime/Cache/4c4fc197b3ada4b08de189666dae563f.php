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
<p class='tablestyle_title'> 支付宝退款订单列表</p>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
        <th width = "5%" align = "center">订单Id</th>
        <th width = "10%" align = "center">订单号</th>
		<th width = "15%" align = "center">用户</th>
		<th width = "15%" align = "center">商家</th>
		<th width = "10%" align = "center">支付方式</th>
        <th width = "10%" align = "center">消费状态</th>
        <th width = "5%" align = "center">支付金额</th>
        <th width = "15%" align = "center">修改时间</th>
		<th width = "15%" align = "center">操作</th><!-- 去退款 -->
	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["id"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["order_num"]); ?></td>
        	<td width ='15%'  align = "center">
            <?php echo ($vo["user_id"]); ?>—— <?php echo ($vo["nick_name"]); ?>      		
        	</td>	
			<td width ='15%' align = "center"><?php echo ($vo["business_title"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["payment_name"]); ?></td>
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
		<!-- <td width ='5%' height='200' align = "center">
	       <a href="http://checkpic.meimei.yihaoss.top/index.php/Pay/refund_order?id=<?php echo ($vo["id"]); ?>" target="_blank" >去退款</a>
        </td> -->

    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>