<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<style>
.show_user {
    color: #000000;
    text-decoration: none;
}
a.show_user:hover {
    color: #000000;
    text-decoration: underline;
}

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
</script>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 预约玩具列表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="pubappointment">添加预约玩具</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV20/AppointmentInfo">查看预约总计</a></p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getappointmentlist" >
        用户ID：<input type="text" name="search_user_id" value="<?php echo ($search_user_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        玩具ID：<input type="text" name="search_business_id" value="<?php echo ($search_business_id); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        玩具名：<input type="text" name="search_toys_title" value="<?php echo ($search_toys_title); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        租赁状态：<select name='search_is_rent'>
            <!-- <option value='0'>请选择</option> -->
            <option value='2' <?php if($search_is_rent == 2 ): ?>selected<?php endif; ?> >未租</option>
            <option value='1' <?php if($search_is_rent == 1 ): ?>selected<?php endif; ?> >已租</option>
            <option value='3' <?php if($search_is_rent == 3 ): ?>selected<?php endif; ?> >放弃</option>
            <!-- <option value='4' <?php if($search_is_rent == 4 ): ?>selected<?php endif; ?> >重新排队</option>
            <option value='5' <?php if($search_is_rent == 5 ): ?>selected<?php endif; ?> >已通知</option> -->
            <option value='6' <?php if($search_is_rent == 6 ): ?>selected<?php endif; ?> >处理中</option>
            <option value='7' <?php if($search_is_rent == 7 ): ?>selected<?php endif; ?> >系统插队</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
        可租数量：<select name='search_number'>
            <option value='0'>请选择</option>
            <option value='1' <?php if($search_number == 1 ): ?>selected<?php endif; ?> >大于0</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;<br/>
        玩具种类：<select name='search_type'>
            <option value='0'>请选择</option>
            <option value='1' <?php if($search_type == 1 ): ?>selected<?php endif; ?> >是</option>
        </select>
    &nbsp;&nbsp;&nbsp;&nbsp;
    预约时长：<select name='search_datediff'>
    <option value='0'>请选择</option>
    <option value='1' <?php if($search_datediff == 1 ): ?>selected<?php endif; ?> >超过10天</option>
</select>
        &nbsp;&nbsp;&nbsp;&nbsp;
         <input type="hidden" name="excel_state" value="" class="excel_state">
        <input type='submit' value='搜索'>
   </form>
   <P><a id="excel_info" style="cursor:pointer;" >导出数据并生成excel</a></P>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">发布人</th>
        <th width = "10%" align = "center">预约用户</th>
		<th width = "10%" align = "center">预约玩具/预约数量</th>
		<th width = "10%" align = "center">预约时间/排队时间/更新时间</th>
        <th width = "5" align = "center"> 可租数量</th>
        <th width = "5" align = "center"> 是否租赁</th>
        <th width = "5" align = "center"> 排序</th>
        <th width = "10" align = "center"> 备注</th>
        <th width = "5" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <th width = "5%" align = "center"><?php echo ($vo["public_name"]); ?></th>
            <td width ='10%' align = "center">
            <a href="http://checkpic.meimei.yihaoss.top/Toys/getappointmentlist?search_user_id=<?php echo ($vo["user_id"]); ?>" class="show_user" ><?php echo ($vo["user_info"]); ?></a>
            </td>
            <td width ='10%' align = "center"><?php echo ($vo["toys_info"]); ?></td>
			<td width ='10%' align = "center"><?php echo ($vo["create_time"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["toys_number"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["rent_title"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["show_rank"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["remark"]); ?></td>
            <td width ='5%' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/Toys/pubappointment?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a>
                <br/>
                <a href="http://checkpic.meimei.yihaoss.top/Toys/operator_appointmentstate?imgid=<?php echo ($vo["id"]); ?>">删除</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>