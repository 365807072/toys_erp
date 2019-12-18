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
<p class='tablestyle_title'> 玩具退款押金列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<div class="check_list"><?php echo ($page); ?></div>
<div>
    <form action="http://checkpic.meimei.yihaoss.top/Others/refunddepositlist.html">
        &nbsp;&nbsp;&nbsp;&nbsp;<select name="status" >
            <option value="3" <?php if($status == 3 ): ?>selected<?php endif; ?> >已申请</option>
            <option value="7" <?php if($status == 7 ): ?>selected<?php endif; ?>>已退款</option>
        </select>
        <input type="submit" value="筛选">
    </form>
</div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "10%" align = "center">ID</th>
        <th width = "5%" align = "center">用户信息</th>
        <th width = "5%" align = "center">申请时间</th>
        <th width = "5%" align = "center">到账时间</th>
		<th width = "10%" align = "center">账单状态</th>
		<th width = "5%" align = "center">价格</th>
        <th width = "5%" align = "center">备注</th>
        <th width = "5%" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='10%' align = "center"><?php echo ($vo["id"]); ?></td>
            <td width ='5%'  align = "center"><?php echo ($vo["user_id"]); ?></td>
            <td width ='5%'  align = "center"><?php echo ($vo["create_time"]); ?></td>
            <td width ='5%'  align = "center"><?php echo ($vo["post_create_time"]); ?></td>
            <td width ='10%'  align = "center"><?php echo ($vo["tmp_status_name"]); ?></td>
			<td width ='5%' align = "center"><?php echo ($vo["price"]); ?></td>

            <td width ='10%'  align = "center">
                <input id="remark<?php echo ($vo["id"]); ?>" type="text" name="remark" value="<?php echo ($vo["remark"]); ?>" ><br>
                <input type="button" class="btn" value="编辑"  idindex="<?php echo ($vo["id"]); ?>">
            </td>

            <td width ='5%' align = "center">
                <a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV7/refundDepositDoing&id=<?php echo ($vo["id"]); ?>&user_id=<?php echo ($vo["user_id_no"]); ?>&price=<?php echo ($vo["price"]); ?>" onclick="return confirm('确认已经退款吗？')" >确认退款</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>

<script type="text/javascript">


    $(".btn").on("click",function(){
        var idindex = $(this).attr("idindex");
        var valindex = $("#remark"+idindex).val();
        $.ajax({
            url:'https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/ToysUpdepositInfo',
            type:'POST',
            data:{
                id: idindex,
                remark:valindex
            },
            success:function(res){
                var data = JSON.parse(res);
                alert(data.reMsg);
            }
        })
    })

</script>

</html>