<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
  <link type="text/css" href="/Tpl/Public/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
     <link type="text/css" href="/Tpl/Public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />

<style>
	*{
		margin: 0;
		padding: 0;
	}
ul{
	width:100%;
	display: -webkit-box;
}
	ul li{
		width:120px;
		list-style: none;
		border-right:1px solid #8c8c8c;
		border-bottom:1px solid #8c8c8c;
	}


</style>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script language = "JavaScript" type = "text/javascript">
 $(document).ready(function(){
	 window.onscroll=function(){
		 var sl=-Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
		 document.getElementById('head').style.left=sl+'px';

	 };
}) 
</script>


</head>

<body  class = "mainBody" id="mainBody">


		<form action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysremarkup" method="post" onsubmit="return confirm('确定增加备注?')">
			&nbsp;&nbsp;&nbsp;增加备注（永久性备注）： <textarea name="remark" cols="30" rows="5"><?php echo ($remark); ?></textarea>
			<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
			<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">
			<input type="submit" value="确认增加">
		</form>


		<br><br>
		<?php if($is_prize == 1): ?><p>删除礼品操作：<a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysdelprize&order_id=<?php echo ($order_id); ?>&is_prize=<?php echo ($is_prize); ?>" onclick="return confirm('确定删除该礼品订单吗？')">删除</a></p><?php endif; ?>

		<br><br>
		<?php if($toys_number != '0'): ?><hr>
			<br>
			<form action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysToysnumberUp" method="post" onsubmit="return confirm('确定增加入库备注?')">
				&nbsp;&nbsp;&nbsp;增加入库备注： <textarea name="order_remark" cols="30" rows="5"><?php echo ($order_remark); ?></textarea>
				<input type="hidden" name="toys_number" value="<?php echo ($toys_number); ?>">
				<input type="submit" value="确认增加">
			</form><?php endif; ?>

		<br><br>
		<hr><br>
		<form action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysUserNicknameUp" method="post" onsubmit="return confirm('确定修改昵称?')">
			&nbsp;&nbsp;&nbsp;用户昵称： <input type="text" name="nick_name" value="<?php echo ($nick_name); ?>">
			<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">
			<input type="submit" value="确认修改">
		</form>
		<br>
		&nbsp;&nbsp;&nbsp;<a href="https://api.meimei.yihaoss.top/index.php?r=BabyShowV20/UserToysList&login_user_id=<?php echo ($user_id); ?>">查看租赁记录</a>
		<br>
		<br>
		<hr>

		<?php if($add_status == 1): ?><br>
			<form action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV20/NoMemberDayAdd" method="post" onsubmit="return confirm('确定操作延期？')">
				&nbsp;&nbsp;&nbsp;延期天数： <input type="text" name="day" value="">
				<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
				<input type="submit" value="确认操作">
			</form>
			<br><?php endif; ?>

		&nbsp;&nbsp;&nbsp;配送信息：
		<table>
		<?php if(is_array($listing_res)): foreach($listing_res as $key=>$vo): ?><tr>
					<td width ='200px' align = "center"><?php echo ($vo["create_time"]); ?></td>
					<td width ='200px' align = "center"><?php echo ($vo["status_name"]); ?>
						<?php if($vo["state"] == 1): ?><font color="red">（已取消）</font><?php endif; ?></td>
					<td width ='200px' align = "center"><?php echo ($vo["nick_name"]); ?></td>
				</tr><?php endforeach; endif; ?>
		</table>

		<!--<hr>-->
		<!--<br><br>-->
		<!--<form action="https://api.meimei.yihaoss.top/index.php?r=BabyShowV77/toysremarkupsendorder" method="post" onsubmit="return confirm('确定限制派单?')">-->
			<!--&nbsp;&nbsp;&nbsp;<font color="red">限制派单备注：</font> <textarea name="remark" cols="30" rows="5"><?php echo ($remark); ?></textarea>-->
			<!--&lt;!&ndash;<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">&ndash;&gt;-->
			<!--<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">-->
			<!--<input type="submit" value="提交">-->
		<!--</form>-->


<script>

</script>
</body>
</html>