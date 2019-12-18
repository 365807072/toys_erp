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
<p class='tablestyle_title'> 仓库记录列表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="pubwarehousere">添加仓库记录</a></p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getwarehouserelist" >
        用户ID：<input type="text" name="search_user_id" value="<?php echo ($search_user_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "10%" align = "center">用户信息</th>
		<th width = "10%" align = "center">问题</th>
		<th width = "10%" align = "center">处理方法</th>
        <th width = "10" align = "center"> 处理记录</th>
        <th width = "10" align = "center"> 创建时间</th>
        <th width = "5" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='10%' align = "center"><?php echo ($vo["user_info"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["problem"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["need_problem"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["handle_record"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["create_time"]); ?></td>
            <td width ='5%' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/Toys/pubwarehousere?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a>
                <br/>
                <a href="http://checkpic.meimei.yihaoss.top/Toys/operator_warehouserestate?imgid=<?php echo ($vo["id"]); ?>">删除</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>