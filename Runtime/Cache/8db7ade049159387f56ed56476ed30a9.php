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
.move_class{
	margin-left:50px;
}
</style>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-timepicker-zh-CN.js"></script>
<script language = "JavaScript" type = "text/javascript">
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
<p class='tablestyle_title'> 玩具出库状况</p>
	<!-- <div class="move_class" > -->
	<div>
		<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getoutbusinessinfo" >
	         开始时间:<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
	         结束时间:<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker">
	        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
	   </form>
   <div>

	下面<?php echo ($res["show_time"]); ?>操作<br/>
	<table width = "100%"  border="1">
		<tr height = "10" >
	        <th width = "10%" align = "center">准备中订单量/用户量</th>
			<th width = "10%" align = "center">待出库数量/用户量</th>
	        <th width = "10%" align = "center">送货中数量-用户量/待取回数量（配送员接单）-用户量</th>
	         <th width = "10%" align = "center">玩乐中数量/用户量</th>
			<th width = "10%" align = "center">取消送货量/用户量</th>
	        <th width = "10" align = "center">入库量/用户量</th>
	        <th width = "10" align = "center">新玩具上架量（玩具种类/玩具个数）</th>
	        <th width = "10" align = "center">时间</th>
	    </tr>
	    <?php if(is_array($res)): foreach($res as $key=>$res): ?><tr>
		        <td width ='10%' align = "center"><?php echo ($res["prepare_count_info"]); ?></td>
		        <td width ='10%' align = "center"><?php echo ($res["send_count_info"]); ?></td>
		        <td width ='10%' align = "center"><a href="http://checkpic.meimei.yihaoss.top/Toys/getpostmanlist?order_status=1&start_time=<?php echo ($res["start_time"]); ?>&end_time=<?php echo ($res["end_time"]); ?> "><?php echo ($res["ordering_count_info"]); ?></a></td>
		        
		        <td width ='10%' align = "center"><?php echo ($res["fun_count_info"]); ?></td>
				<td width ='10%' align = "center"><a href="http://checkpic.meimei.yihaoss.top/Toys/getpostmanlist?order_status=2&start_time=<?php echo ($res["start_time"]); ?>&end_time=<?php echo ($res["end_time"]); ?> "><?php echo ($res["cancel_count_info"]); ?></a></td>

		        <td width ='10%' align = "center"><a href="http://checkpic.meimei.yihaoss.top/Toys/getpostmanlist?order_status=3&start_time=<?php echo ($res["start_time"]); ?>&end_time=<?php echo ($res["end_time"]); ?> "><?php echo ($res["in_count_info"]); ?></a></td>

		        <td width ='10%' align = "center"><a href="http://checkpic.meimei.yihaoss.top/Toys/getpostmanlist?order_status=4&start_time=<?php echo ($res["start_time"]); ?>&end_time=<?php echo ($res["end_time"]); ?> "><?php echo ($res["toys_count"]); ?></a></td>
		        
		        <td width ='10%' align = "center"><?php echo ($res["times"]); ?></td>
		    </tr><?php endforeach; endif; ?> 
	</table>
	<!-- </div> -->
</body>
</html>