<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />
<style>
div{
	display:inline;
}

th{
	font-size:15px;
}
</style>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script language = "JavaScript" type = "text/javascript">

</script>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 合作列表</p>



<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">合作ID</th> 
        <th width = "10%" align = "center">合作名称</th>
		<th width = "5%" align = "center">合作商家状态</th>
        <th width = "5%" align = "center">用户状态</th>
        <th width = "5%" align = "center">审核状态</th>
        <th width = "10%" align = "center">电话</th>
        <th width = "10%" align = "center">邮箱</th>
        <th width = "5%" align = "center">修改时间</th>	
        <th width = "5%" align = "center">操作</th> 
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
        <td width ='5%'  align = "center"> <?php echo ($vo["id"]); ?> </td>
        <td width ='10%' align = "center"><?php echo ($vo["cooperation_name"]); ?></td>
    	<td width ='5%'  align = "center">
            <?php if($vo["cooperation_state"] == 2 ): ?>区域合作
            <?php elseif($vo["cooperation_state"] == 1 ): ?> 线上商家 
            <?php else: ?> 
            线下商家<?php endif; ?>
        </td>
        <td width ='5%'  align = "center"><?php if($vo["user_state"] == 1 ): ?>用户,推荐此商家 <?php else: ?> 商家<?php endif; ?></td>
        <td width ='5%'  align = "center"><?php if($vo["auditing_state"] == 1 ): ?>已审核 <?php else: ?> 未审核<?php endif; ?></td>
        <th width = "10%" align = "center"><?php echo ($vo["mobile"]); ?></th>
        <th width = "10%" align = "center"><?php echo ($vo["email"]); ?></th>
        <td width ='5%' align = "center"> <?php echo ($vo["post_create_time"]); ?></td>
        <td width ='5%' height='200' align = "center">
            <a href="http://checkpic.meimei.yihaoss.top/Others/update_cooperation_info?id=<?php echo ($vo["id"]); ?>" target="_blank" >编辑</a>
           <a href="http://checkpic.meimei.yihaoss.top/Others/operator_cooperation?id=<?php echo ($vo["id"]); ?>">删除</a>
         </td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>