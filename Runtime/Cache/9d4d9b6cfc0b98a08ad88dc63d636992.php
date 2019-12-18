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
</script>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 赔付订单列表 &nbsp;&nbsp;&nbsp;&nbsp;<a href="getpaymentlistorder">配送员上传需要赔付的订单列表</a> &nbsp;&nbsp;&nbsp;&nbsp;<a href="getcancellistorder">配送员取消订单（跟进）</a></p>
<!--<a target="_blank" href="pubstopcard">添加停卡</a>-->
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getpaymentlist" >
        用户ID：<input type="text" name="search_user_id" value="<?php echo ($search_user_id); ?>">
        订单状态：<select name="search_status">
                    <option value="0">请选择</option>
                    <option value="1"<?php if($search_status == 1 ): ?>selected<?php endif; ?>>待支付</option>
                    <option value="7"<?php if($search_status == 7 ): ?>selected<?php endif; ?>>已完成</option>
                    <option value="10"<?php if($search_status == 10 ): ?>selected<?php endif; ?>>待取回</option>
                    <option value="8"<?php if($search_status == 8 ): ?>selected<?php endif; ?>>退款中</option>
                    <option value="9"<?php if($search_status == 9 ): ?>selected<?php endif; ?>>已退款</option>
                  </select>
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">订单ID</th>
        <th width = "10%" align = "center">用户信息</th>
		<th width = "10%" align = "center">付款信息</th>
		<th width = "10%" align = "center">订单信息</th>
        <th width = "10%" align = "center">玩具信息</th>
        <th width = "10%" align = "center">订单状态</th>
        <th width = "10%" align = "center">更新时间/创建时间</th>
        <th width = "5" align = "center">展示状态</th>
        <th width = "5" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["order_id"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["user_info"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["total_price"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["is_prize_name"]); ?><br><?php echo ($vo["toys_payment_title"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["toys_info"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["status_name"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["time_info"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["is_show_name"]); ?></td>
            <td width ='10%' align = "center">
                <a href="getpaymentlistinfo/search_order_id/<?php echo ($vo["order_id"]); ?>">编辑</a><br>
                <form method ='POST' action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV7/EditPaymentStatus" onsubmit="return confirm('确定更改订单状态吗？')">
                    <input type="hidden" name="order_id" value="<?php echo ($vo["order_id"]); ?>">
                    <select name="status">
                    <option value="1"<?php if($vo["status"] == 1 ): ?>selected<?php endif; ?>>待支付</option>
                    <option value="7"<?php if($vo["status"] == 7 ): ?>selected<?php endif; ?>>已完成</option>
                    <option value="10"<?php if($vo["status"] == 10 ): ?>selected<?php endif; ?>>待取回</option>
                    <option value="8"<?php if($vo["status"] == 8 ): ?>selected<?php endif; ?>>退款中</option>
                    <option value="9"<?php if($vo["status"] == 9 ): ?>selected<?php endif; ?>>已退款</option>
                </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='更改'><br>
                </form>
                <a href="getpaymentlistinfo/search_order_id/<?php echo ($vo["order_id"]); ?>">
                    <?php if($vo["is_show"] == '0' ): ?><a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV7/EditPaymentShow&state=1&order_id=<?php echo ($vo["order_id"]); ?>" onclick="return confirm('确定隐藏吗？')">隐藏此订单</a><?php endif; ?>
                    <?php if($vo["is_show"] == '1' ): ?><a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV7/EditPaymentShow&state=0&order_id=<?php echo ($vo["order_id"]); ?>" onclick="return confirm('确定展示吗？')">展示此订单</a><?php endif; ?>
                </a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>