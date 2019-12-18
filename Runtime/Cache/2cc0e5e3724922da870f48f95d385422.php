<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo C('TITLE');?></title>

<link href = '/Tpl/Public/css/css.css' rel = 'stylesheet' type = 'text/css'  />
<!-- 审核日志列表 -->
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
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
				url: "http://checkpic.meimei.yihaoss.top/index.php/Index/operator_post",
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
	
	 
	 function change_class(obj,imgid){
			
			var divs = document.getElementById("imageDiv_" + imgid);
			var inputs = divs.getElementsByTagName('input');
			var post_cate_id = obj.value;
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
				url: "http://checkpic.meimei.yihaoss.top/index.php/Index/operator_postnew_class",
				data:{"imgid":arrayObj,"post_cate_id":post_cate_id}, 
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
		
</script>


<style>
th{
	font-size:15px;
}


</style>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 热门列表--【和秀秀合并后】</p>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10"  >
		<th width = "10%" align = "center">图片</th>
		<th width = "10%" align = "center">标题</th>
		<th width = "10%" align = "center">描述</th>
		<th width = "5%" align = "center">来源</th>
		<th width = "5%" align = "center">图展示</th>
		<th width = "5%" align = "center">跳转</th>
		<th width = "5%" align = "center">标签</th>
		<th width = "5%" align = "center">编辑</th>
		<th width = "5%" align = "center">删除</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>		
    	<td width ='10%'  align = "center">       	
    		<?php echo ($vo["id"]); ?>
			<img src ="<?php echo ($vo["img_thumb"]); ?>" style="width:120px;height:120px;"/>  		
    	</td>	
		<td width ='10%' align = "center"><?php echo (msubstr($vo["title"],0,40,'utf-8')); ?></td>
		<td width ='10%' align = "center"><?php echo (msubstr($vo["description"],0,40,'utf-8')); ?></td>
		<td width ='5%' height='200' align = "center">
	        <?php if($vo["index_data"] == 1 ): ?>话题
	        <?php else: ?>商家<?php endif; ?>
         </td>			
		<td width ='5%' align = "center"> 
			<?php if($vo["index_show"] == 1 ): ?>大图
	        <?php else: ?>小图<?php endif; ?>
	    </td>
		<td width ='5%' align = "center">
			<?php if($vo["index_show_detail"] == 1 ): ?>详情
			 <?php elseif($vo["index_show_detail"] == 2 ): ?>列表
			 <?php elseif($vo["index_show_detail"] == 3 ): ?>群列表<?php endif; ?>
		</td>
		<td width ='5%' align = "center"><?php echo ($vo["label_name"]); ?></td>
		<td width ='5%' align = "center"><a href="listingHot?id=<?php echo ($vo["id"]); ?>&index_data=<?php echo ($vo["index_data"]); ?>" target="_blank">编辑</a></td>
		<td width ='5%' align = "center">
			<a href="http://checkpic.meimei.yihaoss.top/Others/operator_hotstate?hotid=<?php echo ($vo["id"]); ?>">删除</a>
		</td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<div class="check_list"><?php echo ($page); ?></div>

</body>
</html>