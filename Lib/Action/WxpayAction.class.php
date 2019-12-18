<?php
class WxpayAction extends Action{
    //require_once VENDOR_PATH."Wxpay/lib/log.php";
       //在类初始化方法中，引入相关类库    
    public function _initialize() {
        vendor('Wxpay.lib.Api');
        vendor('Wxpay.lib.Config');
        vendor('Wxpay.lib.Data');
        vendor('Wxpay.lib.Exception'); 
        vendor('Wxpay.lib.log');
        vendor('Wxpay.lib.Notify');
        vendor('Wxpay.lib.NativePay');        
    }
    /******************************
    服务器异步通知页面方法
    其实这里就是将notify_url.php文件中的代码复制过来进行处理
    
    *******************************/
    function notifyurl(){
        header('Access-Control-Allow-Origin:*');//允许跨域
	$file_contents=file_get_contents('php://input');
 	$data = array();
	$xml = simplexml_load_string($file_contents, 'SimpleXMLElement', LIBXML_NOCDATA);
	foreach ($xml as $k => $v) {
	    $data[(string) $k] = (string) $v;
	}
	file_put_contents('wxpay.log',$file_contents);
	$notify = new WxPayNotify();
        $notify->Handle(false);
        file_put_contents('wxpay.log',$data);
	$notify->NotifyProcess($data); 
    }
    function notifyurlorder2(){
        header('Access-Control-Allow-Origin:*');//允许跨域
        $notify = new WxPayNotify();
        $notify->Handle(false);
        $data['out_trade_no']=$_POST['out_trade_no'];
        $data['total_fee']=$_POST['total_fee'];
        $data['transaction_id']=$_POST['transaction_id'];
        //$notify->NotifyProcessOrder2($data); 
        $notify->NotifyProcess($data);
    }
    function notifyurltest(){
        header('Access-Control-Allow-Origin:*');//允许跨域
        $notify = new WxPayNotifytest();
                echo 11;die;
        $notify->Handle(false);
        $out_trade_no=$_POST['out_trade_no'];
        $data['out_trade_no']=$out_trade_no;
        $data['total_fee']=$_POST['total_fee'];
        $data['transaction_id']=$_POST['transaction_id'];
        $notify->NotifyProcess($data);
    }
   
}
?>
