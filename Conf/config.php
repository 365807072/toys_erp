<?php

defined('DEFAULT_AVATAR') or define('DEFAULT_AVATAR', 'static/album/defaultAvatar.png');


defined('BREAKER_AVATAR') or define('BREAKER_AVATAR', 'static/album/violateAlbum.png');

defined('BREAKER_ALBUM') or define('BREAKER_ALBUM', 'static/album/violateAvatar.png');


return array(
	//'配置项'=>'配置值'
	
		
'DB_TYPE'   =>'mysql',
		
'DB_HOST'   =>'localhost',
		
'DB_NAME'   => 'quanquan_baby_show',
		
'DB_USER'   => 'root',
		
'DB_PWD'	=> 'qwertQWERT12345',
		
'DB_PREFIX' =>'baby_',

		
'USER_AUTH_KEY'  => 'authId',
	//	
'URL_MODEL'   =>2,
		
'TITLE' =>'宝贝有约运维平台', //管理后台名称

		
'WEB_PATH'  =>'http://test.show.yuanyuanquanquan.com/', 
'VAR_PAGE'=>'p',
//图片路径
 		
		
//'TMPL_PARSE_STRING'  =>array(
   
  		
//	'__APP__' => '/index.php',
			
//'__PUBLIC__' => '../Tpl/Public',
		



//),
	//支付宝配置参数
 /*'api_alipay_config'=>array(
    'partner' =>'2088612306229230',   //这里是你在成功申请支付宝接口后获取到的PID；
    //'key'=>'ugsac4wyd4ymr1ud4ihthzv0befbb6la',//这里是你在成功申请支付宝接口后获取到的Key
    'sign_type'=>strtoupper('RSA'),//strtoupper('MD5')
    'input_charset'=> strtolower('utf-8'),
    'cacert'=> dirname(__file__).'/cacert.pem',
    'transport'=> 'http',
    'private_key_path'=>VENDOR_PATH.'Pay/key/rsa_private_key.pem',
    'ali_public_key_path'=>VENDOR_PATH.'Pay/key/alipay_public_key.pem',
 ),*/


	//支付宝配置参数
 'alipay_config'=>array(
    'partner' =>'2088612306229230',   //这里是你在成功申请支付宝接口后获取到的PID；
    'key'=>'ugsac4wyd4ymr1ud4ihthzv0befbb6la',//这里是你在成功申请支付宝接口后获取到的Key
    'sign_type'=>strtoupper('MD5'),//
    'input_charset'=> strtolower('utf-8'),
    'cacert'=> dirname(__file__).'/cacert.pem',
    'transport'=> 'http',
    //'private_key_path'=>VENDOR_PATH.'Alipay/key/rsa_private_key.pem',
    //'ali_public_key_path'=>VENDOR_PATH.'Alipay/key/alipay_public_key.pem',
 ),
 /*//商户的私钥（后缀是.pen）文件相对路径
$alipay_config['private_key_path']	= 'key/rsa_private_key.pem';

//支付宝公钥（后缀是.pen）文件相对路径
$alipay_config['ali_public_key_path']= 'key/alipay_public_key.pem';*/
     //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
    
 'alipay'   =>array(
	 //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
	 'seller_email'=>'service@yihaoss.top',
	 //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
	 'notify_url'=>'http://checkpic.meimei.yihaoss.top/Pay/notifyurl', 
	 //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
	 'return_url'=>'http://checkpic.meimei.yihaoss.top/Pay/returnurl',
	 //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
	 //'successpage'=>'User/myorder?ordtype=payed',   
	 //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
	 //'errorpage'=>'User/myorder?ordtype=unpay'
 ),
 'alipay17'   =>array(
	 //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
	 'seller_email'=>'service@yihaoss.top',
	 //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
	 'notify_url'=>'http://checkpic.meimei.yihaoss.top/Pay17/notifyurl', 
	 //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
	 'return_url'=>'http://checkpic.meimei.yihaoss.top/Pay17/returnurl',
	 //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
	 //'successpage'=>'User/myorder?ordtype=payed',   
	 //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
	 //'errorpage'=>'User/myorder?ordtype=unpay'
 ),
'alipay2'   =>array(
	 //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
	 'seller_email'=>'service@yihaoss.top',
	 //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
	 'notify_url'=>'http://checkpic.meimei.yihaoss.top/Pay2/notifyurl', 
	 //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
	 'return_url'=>'http://checkpic.meimei.yihaoss.top/Pay2/returnurl',
	 //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
	 //'successpage'=>'User/myorder?ordtype=payed',   
	 //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
	 //'errorpage'=>'User/myorder?ordtype=unpay'
 ),
	
);





?>
