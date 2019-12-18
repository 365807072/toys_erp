<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
<title><?php echo C('TITLE');?></title>
<style type="text/css">

body {
	margin:0px auto;
	
}

</style>
<link href="/Tpl/Public/css/css.css" rel="stylesheet" type="text/css" />

<script src="/Tpl/Public/js/jquery-1.10.2.min.js"></script>
<script language = "JavaScript" type = "text/javascript">
	
	function logout(){
		var result =	confirm("确定退出?");
		if(result){
			$.ajax({
				type:"GET",
				url:"logout",
				data:{},
				dataType:'json',
				success:function(data){
					var jsonData  = eval(data);
					if(jsonData.result){
						top.location.href="/Login/login.html";
					}					

				}
			});
		}else{
			return;
		}
	}

    function fun_play(){
        if($('.fun_hidden').is(':visible')){
            $(".fun_hidden").css("display","none");
        }else{
            $(".fun_hidden").css("display","inline-table");
        }
    }
    function warehouse(){
        if($('.warehouse_hidden').is(':visible')){
            $(".warehouse_hidden").css("display","none");
        }else{
            $(".warehouse_hidden").css("display","inline-table");
        }
    }
    function customeract(){
        if($('.customeract_hidden').is(':visible')){
            $(".customeract_hidden").css("display","none");
        }else{
            $(".customeract_hidden").css("display","inline-table");
        }
    }
    function customer_play(){
        if($('.customer_hidden').is(':visible')){
            $(".customer_hidden").css("display","none");
        }else{
            $(".customer_hidden").css("display","inline-table");
        }
    }
</script>

</head>

<body class = "mainBody">
<table width="198" border="0" cellpadding="0" cellspacing="0" class="left-table01">
  <tr>
    <td>
   		<!-- 用户信息开始 -->
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td width="207" height="55" background="/Tpl/Public/images/nav01.gif">
				<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
					
				    <td width="75%" height="22" class="left-font01">欢迎您：<span class="left-font02"><?php echo $_SESSION['username']; ?></span></td>
				  </tr>
				  <tr>
					<td height="22" class="left-font01">
						 <!-- [&nbsp;<a href="/Login/login.html"  target="_top" class="left-font01">退出</a>&nbsp;] -->
 					    <input type="button" name="Submit" value="退出" class="right-button01" onclick="logout()"/>
				</td> 
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
			
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/getListinglist.html" target="mainFrame" class="left-font03" >帖子列表【合表后】</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/getHotlist.html" target="mainFrame" class="left-font03" >热门列表【合表后】</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/listinghomelist.html" target="mainFrame" class="left-font03" >最新大图列表【合表后】</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/publicmanage.html" target="mainFrame" class="left-font03" >管理</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03" style="display:inline-table;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <!--<a href="/Others/getToyslist.html" target="mainFrame" class="left-font03" >玩具列表</a>-->
                                <a href="javascript:void(0);" onclick="fun_play()"  class="left-font03" >玩具</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!--display:none start -->


        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="https://api.meimei.yihaoss.top/H5/check/check.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;订单管理</a>
                            </td>
                        </tr>
                    </table>
                </td><!-- http://www.yihaoss.top/H5/check/check.html -->
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;" >
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toyscardactivity.html" target="mainFrame" class="fun_hidden" >&nbsp;&nbsp;团购列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;" >-->
            <!--<tr>-->
                <!--<td height="29">-->
                    <!--<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">-->
                        <!--<tr>-->
                            <!--<td width="92%">-->
                                <!--<a href="/Others/toyshelpmelist.html" target="mainFrame" class="fun_hidden" >&nbsp;&nbsp;3次卡加速</a>-->
                            <!--</td>-->
                        <!--</tr>-->
                    <!--</table>-->
                <!--</td>-->
            <!--</tr>-->
        <!--</table>-->

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/parklist" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;小区团购</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysindexclass.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;首页</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getpuradvice.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;采购建议列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getappointmentlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;预约玩具列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">-->
            <!--<tr>-->
                <!--<td height="29">-->
                    <!--<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">-->
                        <!--<tr>-->
                            <!--<td width="92%">-->
                                <!--<a href="/Toys/getcusplolist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;客服交接列表</a>-->
                            <!--</td>-->
                        <!--</tr>-->
                    <!--</table>-->
                <!--</td>-->
            <!--</tr>-->
        <!--</table>-->

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getpaymentlist" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;赔付订单列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getshareprize" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;分享抽奖列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysalluserinfo" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;运营账号链接</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getshareprizethree" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;三天抽奖列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>



        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getstopcardlist" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;停卡列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/toyssendmessage" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;发送短信</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/refundaccountlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;退款列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/refunddepositlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;押金退款列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getlogisticslist" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;物流管理</a><!-- http://www.yihaoss.top/H5/check/order.html -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getbuspurlist" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;采购列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getlogisticslist2" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;物流管理2</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->

        <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">-->
            <!--<tr>-->
                <!--<td height="29">-->
                    <!--<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">-->
                        <!--<tr>-->
                            <!--<td width="92%">-->
                                <!--<a href="http://www.yihaoss.top/H5/check/memberlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;会员</a>-->
                            <!--</td>-->
                        <!--</tr>-->
                    <!--</table>-->
                <!--</td>-->
            <!--</tr>-->
        <!--</table>-->

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/getToyslist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;玩具列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="http://www.yihaoss.top/H5/check/inventory.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;玩具库存</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysparams.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;上传玩具参数</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getbaterypricelist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;电池价格列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/gethotsearchlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;热门搜索列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>




        <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">-->
            <!--<tr>-->
                <!--<td height="29">-->
                    <!--<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">-->
                        <!--<tr>-->
                            <!--<td width="92%">-->
                                <!--<a href="/Others/toysexchange" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;玩具兑换</a>-->
                            <!--</td>-->
                        <!--</tr>-->
                    <!--</table>-->
                <!--</td>-->
            <!--</tr>-->
        <!--</table>-->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysheadlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;首页banner</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toyscategorytoplist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;一级分类列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toyscategorylist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;二级分类列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>



        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/accountlist.html" target="mainFrame"  class="fun_hidden" >&nbsp;&nbsp;账户列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
       <!--  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;" >
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/getinvprizelist.html" target="mainFrame" class="fun_hidden" >&nbsp;&nbsp;邀请列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fun_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/getPrizelist.html" target="mainFrame" class="fun_hidden" >&nbsp;&nbsp;奖品列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--display:none  end -->
        
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
                <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="92%">
                            <a href="https://api.meimei.yihaoss.top/H5/custom/custom.html" target="mainFrame" class="left-font03" >交接系统</a>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>       
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03" style="display:inline-table;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="javascript:void(0);" onclick="warehouse()"  class="left-font03" >仓库管理系统</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getoutbusinessinfo.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;玩具出库状况</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getwarehouserelist.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;仓库记录列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getpostmanorderlist.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;配送管理列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getstocklist.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;玩具备货列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getblacklist.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;黑名单列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/newbusinesslisting.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;新增编号申请</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysorderprepare.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;备货中</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysordersend.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;待派单</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysordering.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;送货中</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysorderfun.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;玩乐中</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/everytoysorderfun.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;每天玩乐中</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysorderput.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;待入库</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/toysorderputrepair.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;维修-待入库</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getsendlist.html" target="mainFrame"  class="warehouse_hidden" >&nbsp;&nbsp;派单列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/gaodepointselectshow.html" target="_blank"  class="warehouse_hidden" >&nbsp;&nbsp;地图筛选</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="warehouse_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/gaodepointline.html" target="_blank"  class="warehouse_hidden" >&nbsp;&nbsp;线路规划</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <!--客服------------------------------------------------------------------start-->


        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03" style="display:inline-table;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="javascript:void(0);" onclick="customeract()"  class="left-font03" >test</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/webPuToysOrder" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;添加卡订单
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getstopcardlist" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;停卡开卡
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/customerold" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;用户租赁记录
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/customermobile" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;查询用户id
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/getpaymentlist" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;赔付订单列表
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/customercart" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;清空购物车
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/customerprize" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;礼品兑换
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/customerinvitation" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;绑定邀请关系
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="customeract_hidden" class="left-table03" style="display:none;">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Toys/customeraddress" target="mainFrame"  class="customeract_hidden" style="font-family: '宋体';font-size: 12px;color: #FF0000;text-decoration: none;">
                                    &nbsp;&nbsp;地址搜索
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--客服--------------------------------------------------------------------end-->


        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="orderlist.html" target="mainFrame" class="left-font03" >精选商家订单列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
		<!--  相册图片审核开始   -->
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="checkpicture.html" target="mainFrame" class="left-font03" >相册图片审核</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		<!--  相册图片审核结束    -->
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Index/publicpostlist.html" target="mainFrame" class="left-font03" >热点列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/video.html" target="mainFrame" class="left-font03" >抓取视频URL</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
		
	
		
		<!--  审核列表开始 -->   
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="postlist.html" target="mainFrame" class="left-font03" >话题列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
       
	<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="otherpostlist.html" target="mainFrame" class="left-font03" >用户发布的话题</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
	
		
	   	<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="buylist.html" target="mainFrame" class="left-font03" >妈妈值得买列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="buy_recommendlist.html" target="mainFrame" class="left-font03" >值得买推荐列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
 -->

		<!--  审核列表结束    -->

		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="businesslist.html" target="mainFrame" class="left-font03" >商家管理列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		
		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="postalbumlist.html" target="mainFrame" class="left-font03" >今日头条列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
		 -->
		
		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="searchAlbumList.html" target="mainFrame" class="left-font03" >查询用户相册</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="register.html" target="mainFrame" class="left-font03" >添加新用户</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>

		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="specaillist.html" target="mainFrame" class="left-font03" >专题分类列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
		
		
			<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="specailheadlist.html" target="mainFrame" class="left-font03" >专题头部列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		
		
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="specailshailist.html" target="mainFrame" class="left-font03" >专题筛选</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="onweruser.html" target="mainFrame" class="left-font03" >添加归属用户</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
		
		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="buynewlist.html" target="mainFrame" class="left-font03" >添加新版值得买</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="postnewlist.html" target="mainFrame" class="left-font03" >添加新版话题</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="postgrouplist.html" target="mainFrame" class="left-font03" >群列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
		
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="sharepicture.html" target="mainFrame" class="left-font03" >来自共享人图片</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
		
		
		<!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="headlist.html" target="mainFrame" class="left-font03" >首页banner</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="newheadlist.html" target="mainFrame" class="left-font03" >新首页banner</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="/Others/newheadlist.html" target="mainFrame" class="left-font03" >首页banner【合表后】</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="mainheadlist.html" target="mainFrame" class="left-font03" >主推首页banner</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
          <tr>
            <td height="29">
				<table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
					<tr>
						<td width="92%">
							<a href="specialtitlelist.html" target="mainFrame" class="left-font03" >专题标题列表</a>
						</td>
					</tr>
				</table>
			</td>
          </tr>		  
        </table> -->
        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="postcoverlist.html" target="mainFrame" class="left-font03" >新版今日头条列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
       <!--  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="newbusinesslist.html" target="mainFrame" class="left-font03" >新版本商家列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/newbusinesslist.html" target="mainFrame" class="left-font03" >商家列表【无套餐】</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/virtualbusinesslist.html" target="mainFrame" class="left-font03" >运营商家列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/virtualorderlist.html" target="mainFrame" class="left-font03" >运营订单列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="refundorderlist.html" target="mainFrame" class="left-font03" >支付宝退款订单列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="wxrefundorderlist.html" target="mainFrame" class="left-font03" >微信订单退款列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="orderCount.html" target="mainFrame" class="left-font03" >订单统计</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/teacherlist.html" target="mainFrame" class="left-font03" >老师列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/cooperationlist.html" target="mainFrame" class="left-font03" >合作商家列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/radiushomelist.html" target="mainFrame" class="left-font03" >半径首页列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/activitypacketlist.html" target="mainFrame" class="left-font03" >活动红包列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left-table03">
            <tr>
                <td height="29">
                    <table width="75%" border="0" align="right" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="92%">
                                <a href="/Others/modulelist.html" target="mainFrame" class="left-font03" >首页模块列表</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
	  </td>
  </tr>
   <tr>
  </tr>
</table>
</body>
</html>