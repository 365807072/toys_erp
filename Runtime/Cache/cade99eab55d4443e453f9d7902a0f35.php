<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo C('TITLE');?></title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
<!-- 审核日志列表 -->

<style>
th{
	font-size:15px;
}

</style>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 玩具帖子列表 </p>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10"  >
		<th width = "5%" align = "center">ID</th>
		<th width = "30%" align = "center">描述</th>
		<th width = "20%" align = "center">话题图片</th>
        <th width = "5%" align = "center">编辑</th>
		<th width = "5%" align = "center">删除</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
	
        <td width ='10%' align = "center"><?php echo ($vo["id"]); ?></td>
		 <td width ='30%' align = "center"><?php echo (msubstr($vo["business_des"],0,20,'utf-8')); ?></td>
        	<td width ='20%'  align = "center">
        	
        		<table width = "100%">
        			<tr>
        				<td align = "left">
        				  
        				<div id = "imageDiv_<?php echo ($vo["id"]); ?>">
        				  
        				    <?php if(is_array($vo["imgUrl"])): foreach($vo["imgUrl"] as $k=>$fff): ?><img id ="image_<?php echo ($vo["id"]); ?>" name="image_<?php echo ($vo["id"]); ?>" src ="<?php echo ($fff); ?>" style="width:120px;height:120px;"/><?php endforeach; endif; ?> 
        				 <div id="text" style="color:red;font-size:24px;"></div>
        			</div>
        				</td>
        			</tr>
        		</table>
        		
        	</td>	
            <td width ='5%' align = "center">
				<?php if($is_card == "1"): ?><a href="publiccard?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a>
					<?php else: ?>
					<a href="publictoysnew?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a><?php endif; ?>


			</td>
			<td width ='10%' align = "center"><a href="operator_replytoyslist?id=<?php echo ($vo["id"]); ?>">删除</a></td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<div class="check_list"><?php echo ($page); ?></div>

</body>
</html>