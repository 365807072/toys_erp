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
<p class='tablestyle_title'> 玩具退款账单列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <!--<th width = "10%" align = "center">账单ID</th>-->
        <th width = "5%" align = "center">订单ID</th>
        <th width = "5%" align = "center">用户ID</th>
        <th width = "5%" align = "center">申请时间</th>
        <th width = "10%" align = "center">账单状态</th>
		<th width = "10%" align = "center">交易号</th>
		<th width = "5%" align = "center">价格</th>	
        <th width = "5%" align = "center">编辑</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <!--<td width ='10%' align = "center"><?php echo ($vo["id"]); ?></td>-->
            <td width ='5%'  align = "center"><?php echo ($vo["order_id"]); ?></td>
            <td width ='5%'  align = "center"><?php echo ($vo["user_id"]); ?></td>
            <td width ='5%'  align = "center"><?php echo ($vo["post_create_time"]); ?></td>
            <td width ='10%'  align = "center"><?php echo ($vo["type_name"]); ?></td>
            <td width ='10%'  align = "center"><?php echo ($vo["trade_no"]); ?></td>
			<td width ='5%' align = "center"><?php echo ($vo["price"]); ?></td>
            <td width ='5%' align = "center">
                <a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV20/editAccountnew&order_id=<?php echo ($vo["order_id"]); ?>" onclick="return confirm('确定编辑为已退款吗？')">编辑</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>