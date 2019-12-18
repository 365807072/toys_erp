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
        $('#excel_info').click(function(){ 
            $(".excel_state").val("1"); 
            $("#search_form").submit();
        });
    })
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
<p class='tablestyle_title'> 备货列表</p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getstocklist" >
        玩具ID：<input type="text" name="business_id" value="<?php echo ($search_business_id); ?>">
        <input type="hidden" name="excel_state" value="" class="excel_state">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
<P><a id="excel_info" style="cursor:pointer;" >导出数据并生成excel</a></P>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">玩具Id</th>
		<th width = "10%" align = "center">玩具名</th>
        <!-- <th width = "10%" align = "center">备货总数量</th> -->
		<th width = "10%" align = "center">待备货数量</th>
        <!-- <th width = "10" align = "center">已备货数量/编号</th> -->
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["business_id"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["business_title"]); ?></td>
			<!-- <td width ='10%' align = "center"><?php echo ($vo["total_number"]); ?></td> -->
            <td width ='10%' align = "center"><?php echo ($vo["ready_number"]); ?></td>
            <!-- <td width ='10%' align = "center"><?php echo ($vo["over_number"]); ?></td> -->
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>