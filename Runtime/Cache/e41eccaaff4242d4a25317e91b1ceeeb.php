<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 


<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />

<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script language = "JavaScript" type = "text/javascript">
function modify(obj,imgid){
			
			var divs = document.getElementById("imageDiv_" + imgid);
			var inputs = divs.getElementsByTagName('input');
			var oper = obj.value;
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
			//alert($("#oper_selected").val());
			$.ajax({
				type: "POST",
				url: "http://checkpic.meimei.yihaoss.top/index.php/Index/operator_album",
				//data:"imgid="+arrayObj,
				data:{"imgid":arrayObj,"oper":oper}, 
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
			
		
			var cate_name = obj.value;
		
			$.ajax({
				type: "POST",
				url: "http://checkpic.meimei.yihaoss.top/index.php/Index/shailist",
				data:{"cate_name":cate_name}, 
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
div{
	display:inline;
}

th{
	font-size:15px;
}
</style>

</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 专题筛选</p>
<form name = "orderForm" action ="http://checkpic.meimei.yihaoss.top/index.php/Index/searchSpecialPic" method = "POST">
	筛选：
		     <select id = "oper_selected" name = "oper_selected"  >
			
				 <?php if(is_array($show_cates)): $i = 0; $__LIST__ = $show_cates;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$show): $mod = ($i % 2 );++$i;?><option value="<?php echo ($show["id"]); ?>" <?php if(($show["id"]) == $vo["show_cate"]): ?>selected<?php endif; ?> ><?php echo ($show["cate_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?> 
			
			
			
			
				
			 </select>
		
	<input type ='submit' class ="right-button01" value = "确定"  />
</form> 
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10"  >
		<th width = "5%" align = "center">用户ID</th>
		<th width = "40%" align = "center">图片显示</th>
		<th width = "30%" align = "center">图片描述</th>
		<th width = "5%" align = "center">赞</th>
		<th width = "5%" align = "center">赞数</th>
		
		<th width = "5%" align = "center">评论</th>
		<th width = "5%" align = "center">评论数</th>
		<th width = "5%" align = "center">设为主题</th>
		<th width = "5%" align = "center">删除</th>
		
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
	
        <td width ='5%' align = "center"><?php echo ($vo["user_id"]); ?></td>
        	<td width ='60%'  align = "center">
        	
        		<table width = "60%">
        			<tr>
        				<td align = "left">
        				  
        				<div id = "imageDiv_<?php echo ($vo["id"]); ?>">
        				   <input  id ="input_<?php echo ($vo["id"]); ?>" name ="input_<?php echo ($vo["id"]); ?>" type = "checkbox" /><?php echo ($vo["id"]); ?>&nbsp;&nbsp;
        				 
        				    <?php if(is_array($vo["imgUrl"])): foreach($vo["imgUrl"] as $k=>$fff): if($k%6 == 0): ?><br /><?php endif; ?>
    							 <img id ="image_<?php echo ($vo["id"]); ?>" name="image_<?php echo ($vo["id"]); ?>" src ="<?php echo ($fff); ?>_thumb_200.jpg" style="width:120px;height:120px;"/><?php endforeach; endif; ?> 
        				 <div id="text" style="color:red;font-size:24px;"></div>
        			</div>
        				</td>
        			</tr>
        		</table>
        		
        	</td>	
			
			<?php if($vo["type"] == 2): ?><td width ='20%' height='200' align = "center" style="color:red;">热点<?php echo ($vo["img_description"]); ?></td>
			<?php elseif($vo["type"] == 3): ?>
			<td width ='20%' height='200' align = "center" style="color:blue;">值得买<?php echo ($vo["img_description"]); ?></td>
			<?php else: ?>
			<td width ='20%' height='200' align = "center"><?php echo ($vo["img_description"]); ?></td><?php endif; ?>
			 
		
		
		 <?php if($vo["img_cate"] == '0' ): ?><td width ='10%' align = "center"><a href="admireimg?id=<?php echo ($vo["id"]); ?>&admire_id=<?php echo ($vo["user_id"]); ?>" target="_blank">赞</a></td>
		 <td width ='5%' height='200' align = "center"><?php echo ($vo["admirecount"]); ?></td>
		 <td width ='10%' align = "center"><a href="replayimg?id=<?php echo ($vo["id"]); ?>&user_id=<?php echo ($vo["user_id"]); ?>" target="_blank">评论</a></td>
		 <td width ='5%' height='200' align = "center"><?php echo ($vo["reviewcount"]); ?></td>
		
		 <td width ='5%' height='200' align = "center">
	       <a href="http://checkpic.meimei.yihaoss.top/index.php/Index/operator_specialstate?id=<?php echo ($vo["id"]); ?>">删除</a>
         </td><?php endif; ?>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>