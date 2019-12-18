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
<p class='tablestyle_title'>玩乐中</p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Others/toysorderfun" >
        订单ID：<input type="text" name="order_id" value="<?php echo ($order_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;用户ID：<input type="text" name="user_id" value="<?php echo ($user_id); ?>">
         玩具ID：<input type="text" name="business_id" value="<?php echo ($business_id); ?>">
         &nbsp;&nbsp;&nbsp;&nbsp;玩具编号：<input type="text" name="toys_number" value="<?php echo ($toys_number); ?>" > <br>
         订单号：<input type="text" name="order_num" value="<?php echo ($order_num); ?>">
         开始时间:<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
         结束时间:<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
</div>



<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "10%" align = "center">订单Id/订单号/订单批号</th>
		<th width = "10%" align = "center">用户信息</th>
        <th width = "10%" align = "center">收货信息</th>
		<th width = "10%" align = "center">玩具ID/玩具标题/玩具编号</th>
        <th width = "10%" align = "center">修改时间</th>	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='10%' align = "center"><?php echo ($vo["id"]); ?><br/><?php echo ($vo["order_num"]); ?><br/><?php echo ($vo["combined_order_id"]); ?></td>
            <td width ='10%'  align = "center">
            <?php echo ($vo["user_id"]); ?><br/><?php echo ($vo["nick_name"]); ?><br/><?php echo ($vo["mobile"]); ?>
            </td>
            <td  width ='10%'  align = "center"><?php echo ($vo["ord_user_name"]); ?><br/> <?php echo ($vo["ord_mobile"]); ?> <br><?php echo ($vo["address"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["business_id"]); ?><br/><?php echo ($vo["business_title"]); ?><br/><?php echo ($vo["toys_number"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["post_create_time"]); ?></td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>