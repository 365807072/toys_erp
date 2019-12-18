<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
<link type="text/css" href="/Tpl/Public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<style>
th{
	font-size:15px;
}
</style>
<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
</head>
<body  class = "mainBody">
<p class='tablestyle_title'> 团购列表&nbsp;&nbsp;&nbsp;&nbsp;<a href="toysinvitecode.html">邀请码</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="toysactivitypartner.html">超级伙伴</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="toysactivitygrouplist.html">一元畅玩活动列表</a></p>
<form id="search_form" method ='GET' action="http://checkpic.meimei.yihaoss.top/Others/toyscardactivity.html" >
        用户ID：<input type="text" name="user_id" value="<?php echo ($user_id); ?>">&nbsp;&nbsp;&nbsp;&nbsp;团长ID：<input type="text" name="parent_id" value="<?php echo ($parent_id); ?>">&nbsp;&nbsp;&nbsp;&nbsp;phone：<input type="text" name="phone" value="<?php echo ($phone); ?>">
        &nbsp;&nbsp;&nbsp;&nbsp;
        身份：<select name="search_parent_id">
                        <option value="0">请选择</option>
                        <option value="1" <?php if($search_parent_id == 1 ): ?>selected<?php endif; ?> >团长</option>
                        <option value="2" <?php if($search_parent_id == 2 ): ?>selected<?php endif; ?> >团员</option>
                        <option value="3" <?php if($search_parent_id == 3 ): ?>selected<?php endif; ?> >自主下单</option>
                        <!--<option value="4" <?php if($search_parent_id == 4 ): ?>selected<?php endif; ?> >注册</option>-->
                        <option value="5" <?php if($search_parent_id == 5 ): ?>selected<?php endif; ?> >注册绑定</option>
                    </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
    类型筛选：<select name="search_invite_type">
    <!--1：跟进；2：本期活动；3：暂不跟进；4：会员-->
    <option value="0">请选择</option>
    <option value="1" <?php if($search_invite_type == 1 ): ?>selected<?php endif; ?> >跟进</option>
    <option value="2" <?php if($search_invite_type == 2 ): ?>selected<?php endif; ?> >本期活动</option>
    <option value="3" <?php if($search_invite_type == 3 ): ?>selected<?php endif; ?> >暂不跟进</option>
    <option value="4" <?php if($search_invite_type == 4 ): ?>selected<?php endif; ?> >会员</option>
    <option value="10" <?php if($search_invite_type == 10 ): ?>selected<?php endif; ?> >超级伙伴</option>
</select>
    &nbsp;&nbsp;&nbsp;&nbsp;
        备注：<input type="text" name="search_remark" value="<?php echo ($search_remark); ?>">&nbsp;&nbsp;&nbsp;&nbsp;
    AB：<select name="ab">
    <!--A:奇数  B:偶数-->
    <option value="0">请选择</option>
    <option value="1" <?php if($ab == 1 ): ?>selected<?php endif; ?> >A</option>
    <option value="2" <?php if($ab == 2 ): ?>selected<?php endif; ?> >B</option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='submit' value='搜索'>
   </form>

<!--<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV77/ToysTry" method ='POST'>-->
    <!--试玩用户id：<input type="text" name="user_id" value="">-->
    <!--<input type="submit" value="提交">-->
<!--</form>-->
<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV7/ToysHelpCommonuser" method ='POST'>
    <!--被助力用户id：<input type="text" name="login_user_id" value="">&nbsp;&nbsp;&nbsp;&nbsp;-->
    运营账号 用户id：<input type="text" name="new_login_user_id" value="">&nbsp;&nbsp;&nbsp;
    是否跟进：<select name="invite_type">
        <!--1：跟进；2：本期活动；3：暂不跟进；4：会员-->
        <option value="3" >暂不跟进</option>
        <option value="1" >跟进</option>
    </select>&nbsp;&nbsp;&nbsp;
    <input type="submit" value="提交">
</form>

<form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV20/OwnOrderUser" method ='POST'>
    自主下单用户id：<input type="text" name="user_id" value="">
    <input type="submit" value="提交">
</form>
<div class="check_list"><?php echo ($page); ?></div>
<table width = "100%"  border="1">
	<tr height = "10" >
        <th width = "5%" align = "center">ID</th>
        <th width = "10%" align = "center">用户信息</th>
        <th width = "5%" align = "center">手机号</th>
        <th width = "10%" align = "center">身份</th>
        <th width = "5%" align = "center">是否购卡</th>
        <th width = "10%" align = "center">备注</th>
        <th width = "5%" align = "center">类型</th>
        <th width = "10%" align = "center">奖品</th>
        <!--<th width = "10%" align = "center">拼福利奖品</th>-->
        <th width = "10%" align = "center">奖品记录</th>
        <th width = "5%" align = "center">创建时间</th>
		<th width = "5%" align = "center">操作</th>	
    </tr>
    <?php if(is_array($res)): foreach($res as $key=>$vo): ?><tr>
            <td width ='5%' align = "center">
                <?php echo ($vo["id"]); ?>
                <?php if($vo["is_true"] == 1): ?><br>运营账号<?php endif; ?>
            </td>
            <td width ='10%'  align = "center">
            <?php echo ($vo["user_id"]); echo ($vo["a_b"]); ?><br/><?php echo ($vo["user_name"]); ?><br/><?php echo ($vo["nick_name"]); ?>
            </td>
            <td width ='5%'  align = "center">
                <?php echo ($vo["mobile"]); ?>
            </td>
            <td width ='10%' align = "center" <?php if($vo["parent_id"] < 1): ?>style="color:red"<?php endif; ?> ><?php echo ($vo["user_info"]); ?> <?php echo ($vo["business_info"]); ?>
                <?php if($vo["parent_id"] > 0): ?><br>团长ID：<?php echo ($vo["parent_id"]); endif; ?>
            </td>
            <td width ='5%' align = "center"><?php echo ($vo["is_pay"]); ?></td>
            <td width ='10%' align = "center">
                <form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV77/ToysUpRemark" method="post">
                    <textarea name="remark" id="" cols="20" rows="3"><?php echo ($vo["remark"]); ?></textarea>
                    <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>">
                    <input type="submit" value="修改">
                </form>
            </td>
            <td width ='10%' align = "center">
                <form action="http://api.meimei.yihaoss.top/index.php?r=BabyShowV77/ToysUpinvitetype" method="post">
                    <select name="invite_type">
                        <!--1：跟进；2：本期活动；3：暂不跟进；4：会员-->
                        <option value="0">请选择</option>
                        <option value="1" <?php if($vo["invite_type"] == 1 ): ?>selected<?php endif; ?> >跟进</option>
                        <!--<option value="2" <?php if($search_invite_type == 2 ): ?>selected<?php endif; ?> >本期活动</option>-->
                        <option value="3" <?php if($vo["invite_type"] == 3 ): ?>selected<?php endif; ?> >暂不跟进</option>
                        <option value="4" <?php if($vo["invite_type"] == 4 ): ?>selected<?php endif; ?> >会员</option>
                        <option value="10" <?php if($vo["invite_type"] == 10 ): ?>selected<?php endif; ?> >超级伙伴</option>
                    </select>
                    <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>">
                    <input type="submit" value="修改">
                </form>
            </td>
            <!--<td width ='5%' align = "center"><?php echo ($vo["click_num"]); ?></td>-->
            <td width ='10%' align = "center"><?php echo ($vo["prize"]); ?></td>
            <!--<td width ='10%' align = "center"><?php echo ($vo["prize_name"]); ?></td>-->
            <td width ='10%' align = "center"><?php echo ($vo["prize_name_3_str"]); ?></td>
            <td width ='5%' align = "center"><?php echo ($vo["create_time"]); ?></td>
            <td width ='5%' align = "center">
                <a href="http://checkpic.meimei.yihaoss.top/Others/toysactivitydel?id=<?php echo ($vo["id"]); ?>">删除</a>
            </td>

        </tr><?php endforeach; endif; ?>
</table>
<br/>
<br/>
<div class="check_list"><?php echo ($page); ?></div>
</body>
</html>