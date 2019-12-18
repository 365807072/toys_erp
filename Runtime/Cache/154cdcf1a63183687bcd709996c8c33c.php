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
<p class='tablestyle_title'> 配送管理列表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Toys/getpostmanorderlist" >

        订单状态：<select name="search_status">
                    <option value="0">请选择</option>
                    <option value="1"<?php if($search_status == 1 ): ?>selected<?php endif; ?>>待支付</option>
                    <option value="2"<?php if($search_status == 2 ): ?>selected<?php endif; ?>>准备中</option>
                    <option value="17"<?php if($search_status == 17 ): ?>selected<?php endif; ?>>重新配送</option>
                    <option value="5"<?php if($search_status == 5 ): ?>selected<?php endif; ?>>送货中</option>
                    <option value="6"<?php if($search_status == 6 ): ?>selected<?php endif; ?>>玩乐中</option>
                    <option value="14"<?php if($search_status == 14 ): ?>selected<?php endif; ?>>恢复玩乐</option>
                    <option value="7"<?php if($search_status == 7 ): ?>selected<?php endif; ?>>待入库</option>
                    <option value="10"<?php if($search_status == 10 ): ?>selected<?php endif; ?>>待取回</option>
                    <option value="8"<?php if($search_status == 8 ): ?>selected<?php endif; ?>>退款中</option>
                    <option value="9"<?php if($search_status == 9 ): ?>selected<?php endif; ?>>已退款</option>
                    <option value="11"<?php if($search_status == 11 ): ?>selected<?php endif; ?>>已入库</option>
                  </select>
    &nbsp;&nbsp;&nbsp;&nbsp;订单id：<input type="text" name="serach_order_id" value="<?php echo ($serach_order_id); ?>">
    &nbsp;&nbsp;&nbsp;&nbsp;用户id：<input type="text" name="serach_user_id" value="<?php echo ($serach_user_id); ?>">
    &nbsp;&nbsp;&nbsp;&nbsp;赔付状态：<select name="search_compensation_status">
                                                <option value="0">请选择</option>
                                                <option value="1"<?php if($search_compensation_status == 1 ): ?>selected<?php endif; ?>>需要赔付</option>
                                                <option value="2"<?php if($search_compensation_status == 2 ): ?>selected<?php endif; ?>>已生成赔付</option>
                                        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='搜索'>
   </form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">订单ID</th>
        <th width = "10%" align = "center">订单状态</th>
		<th width = "10%" align = "center">更新时间/玩具名称</th>
        <th width = "10%" align = "center">配送图片</th>
		<th width = "10%" align = "center">取回图片</th>
        <th width = "10%" align = "center">配送员</th>
        <th width = "10%" align = "center">赔付备注</th>
        <th width = "10%" align = "center">赔付信息</th>
        <th width = "5" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center"><?php echo ($vo["order_id"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["status_name"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["post_create_time"]); ?><br><br><?php echo ($vo["business_title"]); ?></td>
            <td width ='300px'>
                <div style="width: 300px;">
                    <?php if(is_array($vo["admin_load_img"])): foreach($vo["admin_load_img"] as $key=>$vv): ?><img src="https://api.meimei.yihaoss.top/<?php echo ($vv); ?>" style="width:150px;height:150px;" alt=""><?php endforeach; endif; ?>
                </div>
            </td>
            <td width ='300px'>
                <div style="width: 300px;">
                    <?php if(is_array($vo["user_load_img"])): foreach($vo["user_load_img"] as $key=>$vv): ?><img src="https://api.meimei.yihaoss.top/<?php echo ($vv); ?>" style="width:150px;height:150px;" alt=""><?php endforeach; endif; ?>
                </div>
            </td>
            <td width ='10%' align = "center"><?php echo ($vo["postman_name"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["toys_payment_title"]); ?></td>
            <td width ='10%' align = "center"><?php echo ($vo["compensation_status_name"]); ?></td>
            <td width ='10%' align = "center"><a href="toyspayment/order_id/<?php echo ($vo["order_id"]); ?>">生成赔付订单</a></td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>