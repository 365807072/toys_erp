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
<p class='tablestyle_title'> 首页列表【合并后】&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="listinghome">添加首页【合并后】</a></p>

<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10" >
        <th width = "5%" align = "center">ID</th>
		<th width = "10%" align = "center">首页标题</th>
        <th width = "10%" align = "center">图片</th>
        <th width = "5%" align = "center">图展示</th>
        <th width = "5%" align = "center">来源</th>
        <th width = "5%" align = "center">跳转分类</th>
        <th width = "5%" align = "center">排序</th>
		<th width = "5%" align = "center">操作</th>
	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
        <td width ='5%'  align = "center"><?php echo ($vo["id"]); ?> </td>
        <td width ='10%'  align = "center"><?php echo ($vo["title"]); ?></td>
        <td width ='10%'  align = "center">
            <img src="<?php echo ($vo["img1"]); ?>" style="width:100px;height:100px;" >
            <img src="<?php echo ($vo["img2"]); ?>" style="width:100px;height:100px;" >
            <img src="<?php echo ($vo["img3"]); ?>" style="width:100px;height:100px;" >
        </td>
        <td width ='5%'  align = "center">
            <?php if($vo['index_show'] == 1): ?>单图
                <?php elseif($vo['index_show'] == 2): ?>1-3 图
                <?php elseif($vo['index_show'] == 3): ?>1+2 图
                <?php elseif($vo['index_show'] == 4): ?>视频<?php endif; ?>
        </td>
        <td width ='5%'  align = "center">
            <?php if($vo['index_data'] == 1): ?>话题
                <?php elseif($vo['index_data'] == 2): ?>商家<?php endif; ?>
        </td>
        
		<td width ='5%'  align = "center">
            <?php if($vo['index_show_detail'] == 1): ?>详情
                <?php elseif($vo['index_show_detail'] == 2): ?>列表
                <?php elseif($vo['index_show_detail'] == 3): ?>群列表<?php endif; ?>

         </td>
         <td width ='5%'  align = "center"><?php echo ($vo["rank"]); ?></td>
		 <td width ='5%' height='200' align = "center">
            <!-- <?php if($vo['is_ad'] == 1): endif; ?>  -->     
            <a href="http://checkpic.meimei.yihaoss.top/Others/listinghome?id=<?php echo ($vo["id"]); ?>" target="_blank">编辑</a>      
	       <a href="http://checkpic.meimei.yihaoss.top/Others/operator_listing_home?id=<?php echo ($vo["id"]); ?>">删除</a>
         </td>

    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>