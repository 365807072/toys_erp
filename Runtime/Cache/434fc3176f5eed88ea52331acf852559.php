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
<p class='tablestyle_title'> 小区列表&nbsp;&nbsp;&nbsp;<a target="_blank" href="parklistsuggest.html">建议开通</a></p>
<!---->

<form  method ='POST' action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV7/parkSrarchName" >
    用户ID：<input type="text" name="user_id" value="">
    &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
</form>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/parklist" >
        小区名：<input type="text" name="search_park_name" value="<?php echo ($search_park_name); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">小区ID</th>
        <th width = "10%" align = "center">所属地区</th>
        <th width = "10%" align = "center">小区名称</th>
		<th width = "10%" align = "center">搜索关键词</th>
        <th width = "5%" align = "center">参与人数</th>
        <th width = "5%" align = "center">购卡人数</th>
        <th width = "5%" align = "center">会员人数</th>
        <th width = "10%" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["id"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["region_name"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["name"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["nick_name"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["join_count"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["buy_count"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["user_count"]); ?></td>
            <td width ='10%' align = "center"><a href="parklistinfo/search_park_id/<?php echo ($vo["id"]); ?>">查看详情</a></td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>