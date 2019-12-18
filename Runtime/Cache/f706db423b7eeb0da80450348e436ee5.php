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

    </script>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 路线列表&nbsp;&nbsp;&nbsp;&nbsp;<a href="gaodesendoutday.html">查看物流超过一天未操作</a></p>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
    <tr height = "10" >
        <!--<th width = "5%" align = "center">Id</th>-->
        <th width = "10%" align = "center">线路名称</th>
        <!--<th width = "10%" align = "center">创建时间</th>-->
        <!--<th width = "10%" align = "center">修改时间</th>-->
        <th width = "5%" align = "center">配送员</th>
        <th width = "5%" align = "center">线路状态</th>
        <!--<th width = "5%" align = "center">完成量</th>-->
        <th width = "5%" align = "center">完成状况</th>
        <th width = "10%" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr align = "center">
            <!--<td width ='5%' ><?php echo ($vo["id"]); ?></td>-->
            <td width ='10%' align = "left" ><?php echo ($vo["title"]); ?></td>
            <!--<td width ='10%' ><?php echo ($vo["create_time"]); ?></td>-->
            <!--<td width ='10%' ><?php echo ($vo["post_create_time"]); ?></td>-->
            <td width ='5%' ><?php echo ($vo["postman_name"]); ?></td>
            <td width ='5%' ><?php echo ($vo["status_name"]); ?></td>
            <!--<td width ='5%' ><?php echo ($vo["process"]); ?></td>-->
            <th width = "5%" align = "center"><?php echo ($vo["process_name"]); ?></th>
            <td width ='10%' >
                <a href="gaodepointlineinfo?search_root_img_id=<?php echo ($vo["id"]); ?>" target="_self">路线调整</a>&nbsp;&nbsp;&nbsp;
                <!--<a href="gaodepointlineinfoadddel?search_root_img_id=<?php echo ($vo["id"]); ?>" target="_self">测试删减</a>&nbsp;&nbsp;&nbsp;-->
                <a href="gaodepointlineinfoend?search_root_img_id=<?php echo ($vo["id"]); ?>" target="_self">查看详情</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>

<br/>
<div class="check_list"><?php echo ($page); ?></div>
<br><br>
搜索用户所在线路：
<form action="gaodepointline.html">
    用户ID：<input type="text" name="search_user_id" value="<?php echo ($search_user_id); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="搜索">
</form>
<p><?php echo ($search_title); ?></p>

<br/>
<?php if(is_array($res_search_remark)): foreach($res_search_remark as $key=>$voo): ?><p>用户ID：<?php echo ($voo["user_id"]); ?>--手机号：<?php echo ($voo["mobile"]); ?>--路线：<?php echo ($voo["title"]); ?>--玩具名称：<?php echo ($voo["business_title"]); ?>【<?php echo ($voo["business_id"]); ?>/<?php echo ($voo["id"]); ?>】<br>入库备注：<font color="red"><?php echo ($voo["remark"]); ?></font>  小编备注：<font color="green"><?php echo ($voo["order_remark"]); ?></font></p><?php endforeach; endif; ?>
<br/>
</body>
</html>