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
<p class='tablestyle_title'> 采购列表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="pubbuspur">添加采购</a></p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getbuspurlist" >
        玩具ID：<input type="text" name="search_business_id" value="<?php echo ($search_business_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        开始时间:<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
             结束时间:<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker">&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='submit' value='搜索'>
   </form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "10%" align = "center">玩具信息</th>
        <th width = "5" align = "center"> 联系方式</th>
        <th width = "5%" align = "center">价格</th>
        <th width = "5%" align = "center">数量</th>
		<th width = "5%" align = "center">采购渠道</th>
        <th width = "10" align = "center"> 链接</th>
        <th width = "5%" align = "center">采购日期</th>
        <th width = "5" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <th width = "10%" align = "center">
                <?php if($vo["business_id"] > 0): ?><a href="http://checkpic.meimei.yihaoss.top/Others/toysnumberlist?id=<?php echo ($vo["business_id"]); ?>" target="_black" ><?php echo ($vo["toys_info"]); ?></a>
                <?php else: echo ($vo["toys_info"]); endif; ?>
            </th>
            <td width ='5%' align = "center"><?php echo ($vo["mobile"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["price"]); ?></td>
			<td width ='5%' align = "center"><?php echo ($vo["total_number"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["source"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["post_url"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["purchase_time"]); ?></td>
            <td width ='5%' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/Toys/pubbuspur?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a>
                <br/>
                <a href="http://checkpic.meimei.yihaoss.top/Toys/operator_buspurstate?imgid=<?php echo ($vo["id"]); ?>">删除</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>