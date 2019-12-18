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
<p class='tablestyle_title'> 玩具编号列表 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="publictoysnumber?business_id=<?php echo ($business_id); ?>">添加批次玩具编号</a></p>
<div>
    <form action="toysnumberlist" method="GET">
        玩具编号：<input type="text" name="id_search" value="<?php echo ($id_search); ?>">
        <input type="hidden" name="id" value="<?php echo ($business_id_now); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="查询">
    </form>
</div>
<div><a href="http://checkpic.meimei.yihaoss.top/Others/toysnumberlist?id=<?php echo ($business_id_now); ?>&act_ing=1">查看可放库存</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://checkpic.meimei.yihaoss.top/Others/toysnumberlist?id=<?php echo ($business_id_now); ?>">查看全部</a></div>
<br>
<?php if($act_ing == 1): ?><p style="color:red;">可放库存数量：<?php echo ($count); ?></p>
    <form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV7/BusinessStockPut" method="POST" onsubmit="return confirm('确定批量放库存吗？')">
        <input type="hidden" name="business_id" value="<?php echo ($business_id_now); ?>">
        放库存数量：<input type="text" name="business_id_num" value="" placeholder="最多为<?php echo ($count); ?>个" >
        <input type="submit" value="提交">
    </form><?php endif; ?>

<!--<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV7/ExchangeBusinessListing" method="POST" onsubmit="return confirm('确定更换编号吗？')">-->
    <!--预约编号：<select name="new_id" >-->
                    <!--<option value="0">请选择</option>-->
                    <!--<?php if(is_array($new_ids)): foreach($new_ids as $key=>$new_ids): ?>-->
                        <!--<option value="<?php echo ($new_ids["id"]); ?>"><?php echo ($new_ids["id"]); ?></option>-->
                    <!--<?php endforeach; endif; ?>-->
              <!--</select>-->

    <!--可换编号：<select name="old_id" >-->
                    <!--<option value="0">请选择</option>-->
                    <!--<?php if(is_array($old_ids)): foreach($old_ids as $key=>$old_ids): ?>-->
                        <!--<option value="<?php echo ($old_ids["id"]); ?>"><?php echo ($old_ids["id"]); ?></option>-->
                    <!--<?php endforeach; endif; ?>-->
              <!--</select>-->
    <!--<input type="hidden" name="business_id_ex" value="<?php echo ($business_id_now); ?>">-->
    <!--<input type="submit" value="提交">-->
<!--</form>-->

    <div>总数量：<?php echo ($total_number); ?></div>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%" cellpadding = '3' cellspacing = '3'>
	<tr height = "10"  >
		<th width = "5%" align = "center">ID</th>
		<th width = "5%" align = "center">玩具ID</th>
        <th width = "5%" align = "center">编号ID</th>
        <th width = "5%" align = "center">玩具编号</th>
        <th width = "5%" align = "center">是否可用</th>
        <th width = "5%" align = "center">是否出库</th>
        <th width = "5%" align = "center">详细状态</th>
        <th width = "10%" align = "center">采购日期</th>
        <th width = "10%" align = "center">修改时间</th>
        <th width = "8%" align = "center">操作人</th>
        <th width = "10%" align = "center">操作</th>
        <!-- <th width = "5%" align = "center">编辑</th>
		<th width = "5%" align = "center">删除</th> -->
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>	
        <td width ='5%' align = "center"><?php echo ($vo["id"]); ?></td>
		<td width ='5%' align = "center"><?php echo ($vo["business_id"]); ?></td>
        <td width ='5%' align = "center"><?php echo ($vo["tag_id"]); ?></td>
        <td width ='5%' align = "center"><?php echo ($vo["bus_number"]); ?></td>
        <td width ='5%' align = "center"><?php echo ($vo["use_name"]); if($vo["new_old"] == 1): ?>(入库新增加)<?php endif; if($vo["new_old"] == 2): ?>(出库新增加)<?php endif; ?></td>
        <td width ='5%' align = "center">
            <?php if($vo["out_state"] == 0): ?>未出库<?php else: ?>出库<?php endif; ?>
        </td>
        <td width ='5%' align = "center"><?php echo ($vo["status_name"]); ?></td>
        <td width ='10%' align = "center">
            <?php if($vo["purchase_id"] > 0): ?><a href="http://checkpic.meimei.yihaoss.top/Toys/getbuspurlist?search_business_id=<?php echo ($vo["business_id"]); ?>&search_id=<?php echo ($vo["purchase_id"]); ?>" target="_black" ><?php echo ($vo["purchase_time"]); ?></a>
            <?php else: echo ($vo["purchase_time"]); endif; ?>
        </td>
        <td width ='10%' align = "center">
            <?php echo ($vo["post_create_time"]); ?>
        </td>
        <td width ='5%' align = "center"><?php echo ($vo["player_name"]); ?></td>
		<td width ='10%' align = "center">
            <a href="edittoysnumber?id=<?php echo ($vo["id"]); ?>" target="_black" >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="operator_toysnumberlist?id=<?php echo ($vo["id"]); ?>">删除</a>
        </td>
    </tr><?php endforeach; endif; ?>
</table>
<br/>
<div class="check_list"><?php echo ($page); ?></div>

</body>
</html>