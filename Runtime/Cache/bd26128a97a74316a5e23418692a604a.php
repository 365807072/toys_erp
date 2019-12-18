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
        /*$('.check_list a').click(function(){ 
            $("#search_form").submit();
        });*/
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
<p class='tablestyle_title'> 采购建议列表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="pubpuradvice">添加采购建议</a></p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getpuradvice" >
        用户ID：<input type="text" name="search_user_id" value="<?php echo ($search_user_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type='submit' value='搜索'>
   </form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">发布人</th>
        <th width = "10%" align = "center">玩具信息</th>
		<th width = "10%" align = "center">用户信息</th>
		<th width = "10%" align = "center">建议时间</th>
        <th width = "10" align = "center"> 采购审核/审核时间</th>
        <th width = "10" align = "center">是否回复/回复时间</th>
        <th width = "10" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='10%' align = "center"><?php echo ($vo["public_name"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["toys_info"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["user_info"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["create_time"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["purchase_reamrk"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["reply_reamrk"]); ?></td>
            <td width ='5%' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/Toys/pubpuradvice?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a>
                <br/>
                <a href="http://checkpic.meimei.yihaoss.top/Toys/operator_advicestate?imgid=<?php echo ($vo["id"]); ?>">删除</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>