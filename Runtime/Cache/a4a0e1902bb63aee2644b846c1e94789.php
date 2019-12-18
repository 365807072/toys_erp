<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<style>
th{
	font-size:15px;
}
</style>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-timepicker-zh-CN.js"></script>
<script language = "JavaScript" type = "text/javascript">
    $(document).ready(function(){
        $('.check_list a').click(function(){ 
            $("#search_form").submit();
        });
    })
    $(function () {
        $(".ui_timepicker").datetimepicker({
            
            showSecond: true,
            timeFormat: 'hh:mm:ss',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1
        })
    })
</script>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 订单列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/Index/webPublicOrder">添加特定订单</a></p>
<div style="margin:10px;margin-top:0px;">
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/index.php/Index/orderlist" >    
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
         &nbsp;&nbsp;&nbsp;&nbsp;商家ID：<input type="text" name="business_id" value="<?php echo ($business_id); ?>"><br>
         用户ID：<input type="text" name="user_id" value="<?php echo ($user_id); ?>">
         开始时间:<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
         结束时间:<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
</div>



<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "10%" align = "center">订单Id/订单号</th>
		<th width = "10%" align = "center">用户</th>
		<th width = "10%" align = "center">商家ID/商家(或成长记录ID/被关注人)</th>
        <th width = "10%" align = "center">商家(或成长记录)用户</th>
        <th width = "10%" align = "center">商家(或成长记录)电话/邮箱</th>
        <!--<th width = "10%" align = "center">商家地址</th>-->
		<th width = "5%" align = "center">支付方式</th>
        <th width = "5%" align = "center">消费状态</th>
        <th width = "5" align = "center">支付金额(红包前)</th>
        <th width = "5" align = "center">支付金额(红包后)</th>
        <th width = "5%" align = "center">是否与商家沟通</th>
        <th width = "5%" align = "center">修改时间</th>
        <th width = "10%" align = "center">操作</th>
	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='10%' align = "center"><?php echo ($vo["id"]); ?><br/><?php echo ($vo["order_num"]); ?></td>
        	<td width ='10%'  align = "center">
            <?php echo ($vo["user_id"]); ?><br/> <?php echo ($vo["nick_name"]); ?><br/> <?php echo ($vo["mobile"]); ?>      		
        	</td>
			<td width ='10%' align = "center"><?php echo ($vo["business_id"]); ?><br/><?php echo ($vo["business_title"]); ?></td>
            <td width ='10%'  align = "center">
            <?php echo ($vo["seller_id"]); ?><br/><?php echo ($vo["seller_name"]); ?>             
            </td>
            <td width ='10%' align = "center">
            <?php echo ($vo["business_contact"]); ?><br/> <?php echo ($vo["seller_email"]); ?>
            </td>
            <!--<td width ='10%' align = "center"><?php echo ($vo["business_location"]); ?></td>-->
            <td width ='5%' align = "center"><?php echo ($vo["payment_name"]); ?></td>
            <td width ='5%' align = "center">
                <?php if($vo['status'] == 1): ?>未消费
                <?php elseif($vo['status'] == 2): ?>未支付
                <?php elseif($vo['status'] == 3): ?>已完成
                <?php elseif($vo['status'] == 4): ?>退款中
                <?php elseif($vo['status'] == 5): ?>已退款
                <?php elseif($vo['status'] == 6): ?>待上门付款<?php endif; ?>
            </td>
            <td width ='5%' align = "center"><?php echo ($vo["price"]); ?></td>
			<td width ='5%' align = "center"><?php echo ($vo["pay_price"]); ?></td>
            <td width ='5%' align = "center">
                <?php if($vo['is_communication'] == 1): ?>是
                    <?php else: ?>否<?php endif; ?>
            </td>
   <td width ='5%' align = "center"><?php echo ($vo["post_create_time"]); ?></td>
            <td width ='10%' height='200' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/index.php/Index/update_order?id=<?php echo ($vo["id"]); ?>&business_type=1" target="_blank" >编辑</a>
            </td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>