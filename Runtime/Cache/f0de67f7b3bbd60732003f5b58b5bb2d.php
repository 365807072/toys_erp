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
<p class='tablestyle_title'> 物流管理&nbsp;&nbsp;&nbsp;&nbsp;<!-- <a target="_self" href="/Others/toysstorehouse">仓库管理</a>&nbsp;&nbsp;&nbsp;&nbsp; --><a target="_self" href="http://www.meimei.yihaoss.top/H5/check/check.html">订单管理</a></p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getlogisticslist" >
        订单ID：<input type="text" name="order_id" value="<?php echo ($order_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;订单号：<input type="text" name="order_num" value="<?php echo ($order_num); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;配送状态：
        <select name="order_status">
            <option value="0" >全部</option>
            <option value="1" <?php if($order_status == 1 ): ?>selected<?php endif; ?>>备货中</option>
            <option value="2" <?php if($order_status == 2 ): ?>selected<?php endif; ?>>待派单</option>
            <option value="5" <?php if($order_status == 5 ): ?>selected<?php endif; ?>>送货中</option>
            <option value="6" <?php if($order_status == 6 ): ?>selected<?php endif; ?>>玩乐中</option>
            <option value="10" <?php if($order_status == 10 ): ?>selected<?php endif; ?>>待取回</option>
            <option value="7" <?php if($order_status == 7 ): ?>selected<?php endif; ?>>待入库</option>
            <option value="11" <?php if($order_status == 11 ): ?>selected<?php endif; ?>>已入库</option>
        </select>
         <br>玩具ID：<input type="text" name="business_id" value="<?php echo ($business_id); ?>">
    &nbsp;&nbsp;&nbsp;&nbsp;玩具编号：<input type="text" name="toys_number" value="<?php echo ($toys_number); ?>"><br>
         用户ID：<input type="text" name="user_id" value="<?php echo ($user_id); ?>">
         开始时间:<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
         结束时间:<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
</div>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">订单Id/订单号/订单批号</th>
		<th width = "10%" align = "center">用户信息</th>
		<th width = "10%" align = "center">玩具ID/玩具标题/玩具编号</th>
        <th width = "5" align = "center">收货信息</th>
        <!-- <th width = "5%" align = "center">预约时间</th> -->
        <th width = "5%" align = "center">状态</th> 
        <th width = "5%" align = "center">配送人</th>     
        <!-- <th width = "5%" align = "center">操作</th> -->
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["order_info"]); ?></td>
            <td width ='10%'  align = "center"><?php echo ($vo["user_info"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["business_info"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["address"]); ?></td>
            <!-- <td width ='5%' align = "center">
                <form id="search_form" method ='post' action="http://checkpic.meimei.yihaoss.top/Toys/editdelivery" >
                    <input type="text" name="delivery_time" value="<?php echo ($vo["delivery_time"]); ?>">
                    <input type="hidden" name="edit_order_id" value="<?php echo ($vo["id"]); ?>" class="ui_timepicker">
                    <input type='submit' value='修改'>
                </form>
            </td> -->
			<td width ='5%' align = "center"><?php echo ($vo["status_name"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["postman_name"]); ?></td>
            <!-- <td width ='5%' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/Toys/updatelogistics?id=<?php echo ($vo["id"]); ?>" target="_blank" >编辑</a>
            </td> -->
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>