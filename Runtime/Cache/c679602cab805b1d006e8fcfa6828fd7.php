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
<p class='tablestyle_title'> 玩具列表   <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="publictoysdet">发布玩具（即将弃用）</a> --> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/Toys/publictoysnew">发布玩具</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="/Toys/publiccard">发布卡</a></p>
<form name = "orderForm" action ="http://checkpic.meimei.yihaoss.top/Others/getToyslist" method = "get">
	玩具ID：<input type="text" name="img_id" value="<?php echo ($img_id); ?>">
	玩具标题：<input type="text" name="img_title" value="<?php echo ($img_title); ?>">
	开始时间：<input type="text" name="start_time" value="<?php echo ($start_time); ?>" class="ui_timepicker">--
         结束时间：<input type="text" name="end_time" value="<?php echo ($end_time); ?>" class="ui_timepicker"><br/><br/>
   <!--  活动状态：<select name='is_active'>
    	<option value='0'>不是活动</option>
    	<option value='1' <?php if($is_active == 1 ): ?>selected<?php endif; ?> >是活动</option>
    </select> -->
    分类：<select name='category_id'>
    	<option value='0'>无</option>
    	<?php if(is_array($cate_info)): $i = 0; $__LIST__ = $cate_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["category_id"]); ?>' <?php if($category_id == $vo["category_id"] ): ?>selected<?php endif; ?> ><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
    玩具编号ID：<input type="text" name="listing_id" value="<?php echo ($listing_id); ?>">
    玩具状态：<select name='search_state'>
		<option value='0' <?php if($search_state == 0): ?>selected<?php endif; ?> >所有</option>
    	<option value='1' <?php if($search_state == 1): ?>selected<?php endif; ?> >正常</option>
    	<option value='2' <?php if($search_state == 2): ?>selected<?php endif; ?> >隐藏</option>
		<option value='3' <?php if($search_state == 3): ?>selected<?php endif; ?> >断货</option>
    </select>
	<input type ='submit' class ="right-button01" value = "确定"  />
</form> 
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" border='1'>
	<tr height = "10"  >
		<th width = "5%" align = "center">玩具ID</th>
		<th width = "10%" align = "center">话题图片</th>
		<th width = "10%" align = "center">标题</th>
		<th width = "5%" align = "center">总数量</th>
		<th width = "5%" align = "center">可用数量</th>
		<th width = "5%" align = "center">价格</th>
		<th width = "5%" align = "center">状态</th>
		<th width = "5%" align = "center">编号</th>
		<!-- <th width = "5%" align = "center">帖子</th>	 -->
		<th width = "5%" align = "center">操作</th>
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
	
        <td width ='5%' align = "cenSter"><?php echo ($vo["id"]); ?></td>		
    	<td width ='10%'  align = "center">       	
    		<table width = "100%">
    			<tr>
    				<td align = "left">    				  
	    				<div id = "imageDiv_<?php echo ($vo["id"]); ?>">  				 
	    				    <?php if(is_array($vo["imgUrl"])): foreach($vo["imgUrl"] as $k=>$fff): if($k%8 == 0): ?><br /><?php endif; ?>
								 <img id ="image_<?php echo ($vo["id"]); ?>" name="image_<?php echo ($vo["id"]); ?>" src ="<?php echo ($fff); ?>" style="width:120px;height:120px;"/><?php endforeach; endif; ?> 	
							<!-- <br/> -->
					        <?php if($vo["size_img_thumb"] != '' ): ?><img src='<?php echo ($vo["size_img_thumb"]); ?>' style="width:20px;height:20px;" /><?php endif; ?>						
	    				 <div id="text" style="color:red;font-size:24px;"></div>
	    				</div>
    				</td>
    			</tr>
    		</table>
    		
    	</td>	
    	
		<td width ='10%' align = "center">
		<?php echo ($vo["business_title"]); ?> 
		</td>
		<td width ='5%' align = "center"><?php echo ($vo["total_number"]); ?></td>
		<td width ='5%' align = "center"><?php echo ($vo["toys_number"]); ?></td>			
		<td width ='5%' align = "center">
			<?php if($vo["way"] == 1 ): echo ($vo["sell_price"]); ?>/天<?php else: echo ($vo["sell_price"]); endif; ?>
		</td>
		<td width ='5%' align = "center">
			<form action="http://checkpic.meimei.yihaoss.top/Others/edittoystate" method="post" onsubmit="return confirm('确定编辑?')">
				<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>">
				<select name="state" >
					<option value="1" <?php if($vo["state"] == 0): ?>selected<?php endif; ?> >正常</option>
					<option value="2" <?php if($vo["state"] == 2): ?>selected<?php endif; ?> >隐藏</option>
					<option value="3" <?php if($vo["state"] == 3): ?>selected<?php endif; ?> >断货</option>
				</select><br><br>
				<!--<input type="hidden" name="state" value="0">-->
				<input type="submit" value="确定">
			</form>
        </td>
		<td width ='5%' align = "center">
			<?php if($vo["is_card"] > 0 ): ?>无<?php else: ?>
				<a href="toysnumberlist?id=<?php echo ($vo["id"]); ?>" target="_black" >编号</a><?php endif; ?>
		</td>
		<!-- <td width ='5%' align = "center">
            <a href="toysreplylist?id=<?php echo ($vo["id"]); ?>" target="_black" >帖子（即将弃用）</a>
        </td> -->
		<td width ='5%' align = "center">
			<a href="../Toys/toysreplylist?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a><br><br>
			<a href="http://checkpic.meimei.yihaoss.top/Others/operator_toysstate?img_id=<?php echo ($vo["id"]); ?>" onclick="return confirm('确定删除吗')" >删除</a>

			<br><br>
			<!-- http://api.meimei.yihaoss.top/index.php?r=BabyShowV20/editToyState -->
			<!--<?php if($vo["state"] == 2 ): ?>-->
				<!--<form action="http://checkpic.meimei.yihaoss.top/Others/edittoystate" method="post" onsubmit="return confirm('确定释放?')">-->
	                <!--<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>">-->
	                <!--<input type="hidden" name="state" value="0">-->
	                <!--<input type="submit" value="释放">-->
	            <!--</form>-->
			<!--<?php else: ?>-->
				<!--<form action="http://checkpic.meimei.yihaoss.top/Others/edittoystate" method="post" onsubmit="return confirm('确定隐藏?')">-->
	                <!--<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>">-->
	                <!--<input type="hidden" name="state" value="2">-->
	                <!--<input type="submit" value="隐藏">-->
	            <!--</form>-->
			<!--<?php endif; ?>-->
			

		</td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<div class="check_list"><?php echo ($page); ?></div>

</body>
</html>