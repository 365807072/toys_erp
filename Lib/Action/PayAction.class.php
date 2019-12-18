<?php
class PayAction extends Action{
       //在类初始化方法中，引入相关类库    
    public function _initialize() {
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');  
    }
    public function refund_order(){
        $order_id=I("id");
        if($order_id) {
            //$price ='0.01';
            //$trade_no="2015101600001000260062637360";
            $order_info = M("order")
                    ->where('id='.$order_id)
                    ->field('pay_price,trade_no')
                    ->find();
            $price =$order_info['pay_price'];
            $trade_no=$order_info['trade_no'];
        }

        $alipay_config=C('alipay_config');
        $AlipaySubmit = new AlipaySubmit();


        $notify_url   =C('alipay.notify_url'); //服务器异步通知页面路径
        //$return_url   =C('alipay.return_url'); //页面跳转同步通知页面路径
        $seller_email =C('alipay.seller_email');//卖家支付宝帐户必填
        $refund_date=date("Y-m-d H:i:s"); 
        $batch_no=date("Ymd"). mt_rand(1000000000,9999999999);
        $batch_num="1"; 
        $detail_data=$trade_no."^".$price."^协商退款";  

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "refund_fastpay_by_platform_pwd",
            "partner" => trim($alipay_config['partner']),
            "notify_url"    => $notify_url,
            "seller_email"  => $seller_email,//卖家支付宝帐户
            "refund_date"   => $refund_date,//退款当天日期//必填，格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
            "batch_no"  => $batch_no,//批次号//必填，格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
            "batch_num" => $batch_num,//退款笔数  //必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
            "detail_data"   => $detail_data,//退款详细数据
            "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
        //print_r($parameter);die;
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;

    }    
        /******************************
        服务器异步通知页面方法
        其实这里就是将notify_url.php文件中的代码复制过来进行处理
        
        *******************************/
    function notifyurl(){

                /*
                同理去掉以下两句代码；
                */ 
                //require_once("alipay.config.php");
                //require_once("lib/alipay_notify.class.php");
                
                //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=C('alipay_config');
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        //file_put_contents("payLog1.txt", var_export($_POST, true), FILE_APPEND);
        if($verify_result) {
               //验证成功
                   //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
          //批次号
           $batch_no = $_POST['batch_no'];

  //批量退款数据中转账成功的笔数

  $success_num = $_POST['success_num'];

  //批量退款数据中的详细信息
  $result_details = $_POST['result_details'];
            $explode_result_details=explode("^", $result_details);
            if($explode_result_details[0]) {
                $order_list=M("order")->where("trade_no='".$explode_result_details[0]."'")->find();
            }

            $update_data['status'] = '5';
            $update_data['post_create_time'] = date("Y-m-d H:i:s");
            $update_data['result_details'] = $result_details;
            $update_data['verification'] = '';
            //$update_data['trade_no'] = $trade_no;
            //$update_data['buyer_email'] = $buyer_email;
            if($order_list && ($order_list['status']!='5') && ($explode_result_details[2]=='SUCCESS')) {
                M("order")->where("trade_no='".$explode_result_details[0]."'")->save($update_data);  
                $packet_id=$order_list['packet_id'];
                if($packet_id>0) {
                    $packet_data['is_grab_use'] = '0';
                    M('packet')->where("id=".$packet_id)->data($packet_data)->save();
                } 
            }
            //return ture;
            
                echo "success";        //请不要修改或删除
         }else {
                //验证失败
                echo "fail";
            //return false;
        }    
    }
    
    /*
        页面跳转处理方法；
        这里其实就是将return_url.php这个文件中的代码复制过来，进行处理； 
        */
    function returnurl(){
                //头部的处理跟上面两个方法一样，这里不罗嗦了！
        $alipay_config=C('alipay_config');
        $alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
        $trade_no       = $_GET['trade_no'];          //支付宝交易号
        $trade_status   = $_GET['trade_status'];      //交易状态
        $total_fee      = $_GET['total_fee'];         //交易金额
        $notify_id      = $_GET['notify_id'];         //通知校验ID。
        $notify_time    = $_GET['notify_time'];       //通知的发送时间。
        $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；
            
        $parameter = array(
            "out_trade_no"     => $out_trade_no,      //商户订单编号；
            "trade_no"     => $trade_no,          //支付宝交易号；
            "total_fee"      => $total_fee,         //交易金额；
            "trade_status"     => $trade_status,      //交易状态
            "notify_id"      => $notify_id,         //通知校验ID。
            "notify_time"    => $notify_time,       //通知的发送时间。
            "buyer_email"    => $buyer_email,       //买家支付宝帐号
        );
        
        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                if(!checkorderstatus($out_trade_no)){
                     orderhandle($parameter);  //进行订单处理，并传送从支付宝返回的参数；
            }
                $this->redirect(C('alipay.successpage'));//跳转到配置项中配置的支付成功页面；
            }else {
                echo "trade_status=".$_GET['trade_status'];
                $this->redirect(C('alipay.errorpage'));//跳转到配置项中配置的支付失败页面；
            }
        }else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "支付失败！";
        }
    }
}
?>