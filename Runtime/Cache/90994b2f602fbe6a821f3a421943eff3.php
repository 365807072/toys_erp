<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 新版玩具banner列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="toysheadnew">添加玩具banner</a></p>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
		<th width = "30%" align = "center">玩具头部图片</th>
        <th width = "10%" align = "center">来源</th>
		<th width = "15%" align = "center">删除</th>	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
        	<td width ='30%'  align = "center">
        	
        		<table width = "100%">
        			<tr>
        				<td align = "left">
        				  
        				<div id = "imageDiv_<?php echo ($vo["id"]); ?>">
        				  
							
    					 <img id ="image_<?php echo ($vo["id"]); ?>" name="image_<?php echo ($vo["id"]); ?>" src ="<?php echo ($vo["img"]); ?>" />
        				      
        				  
								
        				 <div id="text" style="color:red;font-size:24px;"></div>
        			</div>
        				</td>
        			</tr>
        		</table>
        		
        	</td>	
		<td width ='5%' height='200' align = "center">
	       <?php echo ($vo["name"]); ?>
         </td>
   
		 <td width ='5%' height='200' align = "center">
	       <a href="http://checkpic.meimei.yihaoss.top/Others/operator_toys_headnew?id=<?php echo ($vo["id"]); ?>">删除</a>
         </td>

    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>