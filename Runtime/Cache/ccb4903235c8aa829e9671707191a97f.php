<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo C('TITLE');?></title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<!-- 审核日志列表 -->
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/Tpl/Public/js/jquery-ui-timepicker-zh-CN.js"></script>
<script language = "JavaScript" type = "text/javascript">
function modify(obj,imgid){
			
			var divs = document.getElementById("imageDiv_" + imgid);
			var inputs = divs.getElementsByTagName('input');
			var is_recommend = obj.value;
			//alert(oper);false;
			var arrayObj = new Array();
			for (var i = 0; i < inputs.length; i++) {
			
				if (inputs[i].checked == true) { //是否被选中
					//记录id
					var imageIdStr = inputs[i].id;
					var id = imageIdStr.substring(6);//取 image_后面的字符
					arrayObj.push(id);
				}
				
			}
			if (arrayObj.length == 0) {
				alert("您未选中任何图片!");
				return;
			}

			$.ajax({
				type: "POST",
				url: "http://checkpic.meimei.yihaoss.top/Others/operator_post",
				//data:"imgid="+arrayObj,
				data:{"imgid":arrayObj,"is_recommend":is_recommend}, 
				//data:"{imgid:'" + arrayObj + "',oprator:'" + oprator + "'}",
				dataType: "json",
				success:function(data){
				
					var  jsonData= eval(data);
					if(jsonData.flag){
		
					}else{
						alert(jsonData.reMsg);
					
					}
				}
			});
		}
	
	 
	 function hot_state(obj,imgid){
			
			var divs = document.getElementById("imageDiv_" + imgid);
			var inputs = divs.getElementsByTagName('input');
			//alert(oper);false;
			var arrayObj = new Array();
			for (var i = 0; i < inputs.length; i++) {
			
				if (inputs[i].checked == true) { //是否被选中
					//记录id
					var imageIdStr = inputs[i].id;
					var id = imageIdStr.substring(6);//取 image_后面的字符
					arrayObj.push(id);
				}
				
			}
			if (arrayObj.length == 0) {
				alert("您未选中任何图片!");
				return;
			}
			$.ajax({
				type: "POST",
				url: "http://checkpic.meimei.yihaoss.top/Others/operator_hot",
				//data:{"imgid":arrayObj}, 
				data:{"imgid":imgid},
				dataType: "json",
				success:function(data){
				
					var  jsonData= eval(data);
					if(jsonData.flag){
		
					}else{
						alert(jsonData.reMsg);
					
					}
				}
			});
		}
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


<style>
th{
	font-size:15px;
}


</style>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 热点【和秀秀合并后】列表   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="publicpostimg">发布热点【和秀秀合并后】</a></p>
<form name = "orderForm" action ="http://checkpic.meimei.yihaoss.top/Others/getListinglist" method = "get">
	搜索帖子ID<input type="text" name="img_id" value="<?php echo ($img_id); ?>">
	搜索热点标题<input type="text" name="img_title" value="<?php echo ($img_title); ?>">
	搜索热点内容<input type="text" name="img_description" value="<?php echo ($img_description); ?>"><br/><br/>
	开始时间:<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
         结束时间:<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker">
	<input type ='submit' class ="right-button01" value = "确定"  />
</form> 
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10"  >
		<th width = "5%" align = "center">楼主</th>
		<th width = "30%" align = "center">话题图片</th>
		<th width = "10%" align = "center">话题标题</th>
		<th width = "10%" align = "center">话题描述</th>
		<th width = "5%" align = "center">发布时间</th>
		<th width = "5%" align = "center">设置精品</th>
		<th width = "5%" align = "center">操作</th>
		<th width = "5%" align = "center">群ID</th>
		<th width = "5%" align = "center">标签</th>
		<th width = "5%" align = "center">设置热门</th>
		<th width = "5%" align = "center">评论</th>
		<th width = "5%" align = "center">删除</th>
		<th width = "5%" align = "center">标签状态</th>
		<th width = "5%" align = "center">查看</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
	
        <td width ='10%' align = "cenSter"><?php echo ($vo["nick_name"]); ?></td>
		
        	<td width ='30%'  align = "center">       	
        		<table width = "100%">
        			<tr>
        				<td align = "left">
        				  
        				<div id = "imageDiv_<?php echo ($vo["id"]); ?>">
        				   <input  id ="input_<?php echo ($vo["id"]); ?>" name ="input_<?php echo ($vo["id"]); ?>" type = "checkbox" /><?php echo ($vo["id"]); ?>&nbsp;&nbsp;
        				 
        				    <?php if(is_array($vo["imgUrl"])): foreach($vo["imgUrl"] as $k=>$fff): if($k%8 == 0): ?><br /><?php endif; ?>
							
    							 <img id ="image_<?php echo ($vo["id"]); ?>" name="image_<?php echo ($vo["id"]); ?>" src ="<?php echo ($fff); ?>" style="width:120px;height:120px;"/><?php endforeach; endif; ?> 
								
        				 <div id="text" style="color:red;font-size:24px;"></div>
        			</div>
        				</td>
        			</tr>
        		</table>
        		
        	</td>	
        	<!-- <img id ="image_<?php echo ($vo["id"]); ?>" name="image_<?php echo ($vo["id"]); ?>" src ="<?php echo ($fff); ?>_thumb_200.jpg" style="width:120px;height:120px;"/> -->
        	<td width ='10%' align = "center"><?php echo (msubstr($vo["img_title"],0,40,'utf-8')); ?></td>
			 <td width ='10%' align = "center"><?php echo (msubstr($vo["img_description"],0,40,'utf-8')); ?></td>
			 <td width ='5%' align = "center"><?php echo ($vo["create_time"]); ?></td>
		<td width ='5%' height='200' align = "center">
	        <select id = "oper_selected" name = "oper_selected" onchange = "modify(this,<?php echo ($vo["id"]); ?>)" >
				<option value="0" <?php if(($vo["is_recommend"]) == "0"): ?>selected<?php endif; ?>>非精品</option>
				<option value="1" <?php if(($vo["is_recommend"]) == "1"): ?>selected<?php endif; ?>>精品</option>
			 </select>
         </td>			
			<td width ='5%' align = "center"><a href="replaypostimg?id=<?php echo ($vo["id"]); ?>" target="_blank">回复话题</a></td>
			<td width ='5%' align = "center"><?php echo ($vo["group_id"]); ?></td>
			<td width ='5%' align = "center">
			
			<?php if($vo["jump_id"] > '0' ): ?>去打标签<?php else: ?>
				<a href="listingLabel?id=<?php echo ($vo["id"]); ?>" target="_blank">去打标签</a><?php endif; ?>
			</td>
			<td width ='5%' align = "center">
				<select  name = "is_hot" onchange = "hot_state(this,<?php echo ($vo["id"]); ?>)" >
					<option value="0" >未设热门</option>
					<option value="1" >热门</option>
				 </select>
			</td>
			<td width ='5%' align = "center"><a href="postreplylist?id=<?php echo ($vo["id"]); ?>" target="_blank">去评论</a></td>
			<td width ='5%' height='200' align = "center">
				<a href="http://checkpic.meimei.yihaoss.top/Others/operator_listingstate?imgid=<?php echo ($vo["id"]); ?>">删除</a>
			</td>
			<td width ='5%' align = "center">
				<?php if(($vo["label_state"] == '1') OR ($vo["jump_id"] > '0') ): ?>已打过标签<?php else: ?>未打过标签<?php endif; ?>
			</td>	
			<td width ='5%' align = "center">
				<?php if($vo["is_video"] == '1' ): ?><a href="http://www.yihaoss.top/fenxiang/video.html?user_id=<?php echo ($vo["user_id"]); ?>&img_id=<?php echo ($vo["id"]); ?>" target="_blank">视频</a>
				<?php else: ?>
				<a href="http://www.yihaoss.top/fenxiang/postbardetial.html?img_id=<?php echo ($vo["id"]); ?>&user_id=<?php echo ($vo["user_id"]); ?>" target="_blank">帖子</a><?php endif; ?>
			</td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<div class="check_list"><?php echo ($page); ?></div>

</body>
</html>