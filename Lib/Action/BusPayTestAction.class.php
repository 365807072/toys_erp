<?php
	class BusPayTestAction extends Action{
		//在类初始化方法中，引入相关类库    
	    public function _initialize() {
			vendor('BusPay.Corefunction');
			vendor('BusPay.Notify');
			vendor('BusPay.Rsafunction');   
	    }
	    /******************************
        服务器异步通知页面方法
        其实这里就是将notify_url.php文件中的代码复制过来进行处理
        *******************************/
        function notifyurlorder(){
			//这里还是通过C函数来读取配置项，赋值给$alipay_config
	        $alipay_config=array(
				'partner' =>'2088721930969145',   //这里是你在成功申请支付宝接口后获取到的PID；
				//'key'=>'ugsac4wyd4ymr1ud4ihthzv0befbb6la',//这里是你在成功申请支付宝接口后获取到的Key
				'sign_type'=>strtoupper('RSA'),//strtoupper('MD5')
				'input_charset'=> strtolower('utf-8'),
				'cacert'=> dirname(__file__).'/cacert.pem',
				'transport'=> 'http',
				'private_key_path'=>VENDOR_PATH.'BusPay/key/rsa_private_key.pem',
				'ali_public_key_path'=>VENDOR_PATH.'BusPay/key/alipay_public_key.pem'
	        );
	        //计算得出通知验证结果
	        $alipayNotify = new AlipayNotify($alipay_config);
	        $verify_result = $alipayNotify->verifyNotify();
	        //file_put_contents("payLog1.txt", var_export($_POST, true), FILE_APPEND);
	        if($verify_result) {
	            //验证成功——获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	            $temp_order_id   = $_POST['out_trade_no'];      //商户订单号
	            $trade_no       = $_POST['trade_no'];          //支付宝交易号
	            $trade_status   = $_POST['trade_status'];      //交易状态
	            $total_fee      = $_POST['total_fee'];         //交易金额
	            $notify_id      = $_POST['notify_id'];         //通知校验ID。
	            $notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
	            $buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号;
	            if(strstr($temp_order_id,"c")){//c3 为玩具订单
	                $array_order_c=explode("c",$temp_order_id);
	                $out_trade_no=(int)$array_order_c[0];
	                $order_type=(int)$array_order_c[1];
	            } elseif(strstr($temp_order_id,"d")){//d4 为玩具组合订单
	                $array_order_d=explode("d",$temp_order_id);
	                $out_trade_no=(int)$array_order_d[0];
	                $order_type=(int)$array_order_d[1];
	            } else {
	            	$array_order_id=explode("a",$temp_order_id);
	            	$out_trade_no=(int)$array_order_id[0];
	                $order_type=1;
	            }

	            if( ($trade_status == 'TRADE_FINISHED') || ($trade_status == 'TRADE_SUCCESS') ) {
	            	if($order_type==4) {
		            	$model_order=M('toys_order');
		            	$model_deposit=M('toys_deposit');
		            	$model_card=M('toys_card');
		            	$model_listing=M('toys_listing');
		            	$model_account=M('account');
		            	$model_cart=M('toys_cart');
		            	$model_business=M('toys_business');
		            	$model_business_listing=M('toys_business_listing');
    					$model_toys_appointment=M("toys_appointment");
		            	$same_day=date("Y-m-d H:i:s");
						if($out_trade_no) {
							$checkOrderUserRes=$model_order
                                ->where("combined_order_id=".$out_trade_no)
                                ->order('id desc')
                                ->find();
            				$checkOrder_user_id=$checkOrderUserRes['user_id'];
						    $model = new Model();
						    $check_order = $model->table('baby_toys_order as o')
						                 ->join(" left join baby_toys_business as b on o.business_id=b.id")
						                 ->field('o.id as order_id,o.status,o.business_id,b.way,o.rent_day,o.balance_price,o.need_price,o.sell_price,o.service_price,o.user_id,b.is_card,b.number,o.card_id,o.deposit_price,o.change_order_id,b.service_number,o.battery_price,o.is_prize')
						                ->where("o.combined_order_id=$out_trade_no and o.user_id=$checkOrder_user_id and (o.is_prize in (0,3,4,5,6,7) or (o.is_prize=2 and o.is_battery=1) )")
						                ->select();
						    if($check_order) {
						    	foreach ($check_order as $key => $value) {
						    		$add_des_state=0;
						    		$check_change_order_id= $value["change_order_id"];
							    	$check_business_id=$value['business_id'];
								    $check_status=$value['status'];
								    $check_way=$value['way'];
								    $check_is_card=$value['is_card'];
								    $check_number=$value['number'];
								    $check_card_id=$value['card_id'];
								    $check_order_id=$value['order_id'];
								    $check_service_number=$value['service_number'];
								    // 账户金额 开始
								    $check_user_id=$value['user_id'];
								    $check_balance_price=$value['balance_price'];//消费账户余额
								    $check_deposit_price=$value['deposit_price'];//押金抵扣金额
								    $check_need_price=$value['need_price'];//押金
								    $check_sell_price=$value['sell_price'];//租价价格总价格 或 售卖价格
								    $check_battery_price=$value['battery_price'];//电池
								    $check_service_price=$value['service_price'];//服务费
									$check_is_prize=$value['is_prize'];//4,5赔付订单
								    $tmp_total_price=$check_need_price+$check_sell_price+$check_service_price+$check_battery_price;//总金额
								    if($tmp_total_price>$total_fee) {
								    	$tmp_total_price=$total_fee;
								    }

								    //检查押金对应商家id

							    	$add_account_data=array();
									$ins_price1=$check_need_price-$check_balance_price;//押金-余额

									// 账户金额 结束

									if(($check_status=='1') ) {
									//待支付时才支付
										
										$update_data['post_create_time'] =$same_day;
										$update_data['order_time'] =$same_day;
										if($tmp_total_price>0) {
											$update_data['trade_no'] = $trade_no;
											$update_data['buyer_email']=$buyer_email;
										}
										$update_data['total_price']=$tmp_total_price;
//										$update_data['state'] = '0';
										if(($check_is_prize==4)||($check_is_prize==5)){
											$update_data['status'] = "7";
										}else{
											$update_data['status'] = "2";
										}

										$update_data['payment']='4';
										$update_data['wx_source']='4';

										$model_order->where('id='.$check_order_id." and state !='2' ")->save($update_data);

										//预约开始
										$gettoysappRes=$model_toys_appointment
								                    ->field('id')
								                    ->where("user_id=$check_user_id and state='0' and business_id=$check_business_id and is_rent='6' ")
								                    ->find();//预约
								        $gettoysappID=$gettoysappRes['id'];
								        if($gettoysappID>0) {
								            $app_data['is_rent'] = '1' ;//已租
								            $model_toys_appointment->where("id=".$gettoysappID)->data($app_data)->save();

								            $gettoysbuslisRes=$model_business_listing
								                    ->field('id')
								                    ->where("state='0' and business_id=$check_business_id and is_use='4' ")
								                    ->order("id desc ")
								                    ->find();
								            $gettoysbuslisID=$gettoysbuslisRes['id'];
								            if($gettoysbuslisID>0) {
								                $bus_lis_data['is_use'] = '0' ;//可用
								                $model_business_listing->where("id=".$gettoysbuslisID)->data($bus_lis_data)->save();
								            }
								        }
										//预约结束
										//检查玩具在购物车是否存在 开始
									    $getCartRes=$model_cart
										                    ->field('id,status')
										                    ->where("user_id=$check_user_id and state='0' and business_id=$check_business_id ")
										                    ->find();
										$getCartID=$getCartRes['id'];
										$getCartStatus=$getCartRes['status'];
										if($getCartID>0) {
									        $cart_data['state'] = '1' ;
									        $model_cart->where("id=".$getCartID)->data($cart_data)->save();
										}
										//检查玩具在购物车是否存在 结束
										if($add_account_data) {//账户余额
											$model_account->addAll($add_account_data);
										}

										
								    	//检查用户有会员 开始
										if($check_card_id>0) {
								        	$check_card = $model_card
								        			->field('card_name,order_id,card_day,start_time')
								                    ->where("id=$check_card_id ")
								                    ->find();
								        	$check_card_day=$check_card['card_day'];
								        	$card_check_order_id=$check_card['order_id'];
								        	$card_check_start_time=$check_card['start_time'];
								        	if($card_check_start_time=="0000-00-00 00:00:00") {
                                                $up_end_time=date("Y-m-d H:i:s",strtotime("+$check_card_day day"));
                                                $upcard_data['post_create_time']=date("Y-m-d H:i:s");
                                                $upcard_data['start_time']=date("Y-m-d H:i:s");
                                                $upcard_data['end_time']=$up_end_time;
                                                $upcard_data['status']='1';
                                                $model_card->where('id='.$check_card_id)->save($upcard_data);
                                            }
                                            if($card_check_order_id>0) {
                                                $check_card_order = $model_order->field('status')
                                                ->where("id=$card_check_order_id ")
                                                ->find();
                                                $check_card_status=$check_card_order['status'];
                                                if($check_card_status==2) {
                                                    $upcardorder_data['status']='7';
                                                    $upcardorder_data['post_create_time']=date("Y-m-d H:i:s");
                                                    $model_order->where('id='.$card_check_order_id)->save($upcardorder_data);
                                                }
                                            }
								        	
										}
								      	//检查用户有会员 结束

										if(($check_is_card==0) || ($check_is_card==9) ) {
											//改帖子状态 开始
											$check_listing = $model_listing->field('id')
											->where("state='0' and order_id=$check_order_id and status='1' ")
											->find();
											$checkListingId=$check_listing['id'];
											$update_listing['post_create_time']=date("Y-m-d H:i:s");
											$update_listing['status_name']='已下单';
											if($checkListingId>0) {
												$model_listing->where('id='.$checkListingId)->save($update_listing);
											}
											//改帖子状态 结束
											//添加一条帖子 开始
											if(($check_is_prize==4)||($check_is_prize==5)){
												$add_listing_data=array(
													'business_id'=>$check_business_id,
													'order_id'=>$check_order_id,
													'create_time'=>date("Y-m-d H:i:s"),
													'post_create_time'=>date("Y-m-d H:i:s"),
													'status'=>'9',
													'status_name'=>'已完成'
												);
											}else{
												$add_listing_data=array(
													'business_id'=>$check_business_id,
													'order_id'=>$check_order_id,
													'create_time'=>date("Y-m-d H:i:s"),
													'post_create_time'=>date("Y-m-d H:i:s"),
													'status'=>'2',
													'status_name'=>'准备中'
												);
											}

											if($add_listing_data) {
												$model_listing->add($add_listing_data);
											}
											//添加一条帖子 结束
										} else {
											if(($check_business_id==1625) || ($check_business_id==1627) || ($check_business_id==2253) || ($check_business_id==3028) || ($check_business_id==2255) )  {
												$card_day="0";
												$card_service_num=$check_service_number;
											} elseif($check_business_id==1416) {
												$card_day="0";
												$card_service_num="0";
											} elseif($check_is_card==1) {
												$card_day="30";
												$card_service_num="2";
											} elseif($check_is_card==2) {
												$card_day="90";
												$card_service_num="6";
											} elseif($check_is_card==3) {
												$card_day="183";
												$card_service_num="8";
											} elseif($check_is_card==4) {
												$card_day="366";
												$card_service_num="18";
											}
								        	//添加一条vip 开始
											$add_card_data=array(
												'user_id'=>$check_user_id,
												'order_id'=>$check_order_id,
												'create_time'=>date("Y-m-d H:i:s"),
												'post_create_time'=>date("Y-m-d H:i:s"),
												'card_name'=>$check_is_card,
												'card_day'=>$card_day,
												'card_service_num'=>$card_service_num,
												'card_service_final_num'=>$card_service_num,
												'end_num'=>$card_service_num
												);
											if($add_card_data) {
												$model_card->add($add_card_data);
											}
											//添加一条vip 结束
										}
								    }
								}
								if(($check_status=='1') ){
									//----------------------------------------------------------------------------new start-----------------------------------------------------------------------------------------
									$each_order_res = $model->table('baby_toys_order as o')
										->join(" left join baby_toys_business as b on o.business_id=b.id")
										->field('o.user_id,o.id,o.card_id,o.is_prize,o.rent_price,o.rent_day,o.service_price,o.business_id,o.change_order_id_new,o.change_business_id_new,o.sell_price,b.need_price,b.member_price,b.service_number,b.is_card,o.combined_order_id')
										->where("o.combined_order_id=$out_trade_no and o.user_id=$checkOrder_user_id ")
										->select();
									$new_sell_price_total = 0;
									$new_service_price_total = 0;
									$new_deposit_price_total = 0;
									$new_battery_price_total = 0;
									$new_card_id_arr = array();
									$no_card_order_id_arr = array();
									$each_change_order_id_arr = '';
									$each_change_business_id_arr = '';
									$now_time=date("Y-m-d H:i:s");
									foreach($each_order_res as $value){
										$each_order_id = $value['id'];
										$each_combined_order_id = $value['combined_order_id'];
										$each_user_id = $value['user_id'];
										$each_is_prize = $value['is_prize'];
										$each_card_id = $value['card_id'];
										$each_rent_price = $value['rent_price'];//租赁价格
										$each_rent_day = $value['rent_day'];//租赁天数
										$each_service_price = $value['service_price'];//服务费
										$each_sell_price = $value['sell_price'];//租赁费
										$each_business_id = $value['business_id'];
										$each_need_price = $value['need_price'];//押金
										$each_service_number = $value['service_number'];//押金
										$each_is_card = $value['is_card'];//卡类型
										$each_member_price = $value['member_price'];//高端玩具会员价
										$each_change_order_id_new = $value['change_order_id_new'];//购物车退还 订单
										if(!empty($each_change_order_id_new)){
											$each_change_order_id_arr = explode(',',$each_change_order_id_new);
										}
										$each_change_business_id_new = $value['change_business_id_new'];//购物车退还 玩具

										if(!empty($each_change_business_id_new)){
											$each_change_business_id_arr = $each_change_business_id_new;
										}

										if($each_card_id>0){
											
										}else{
											if(($each_is_prize==0)||($each_is_prize==3)){
												$no_card_order_id_arr[] = array(
													'is_prize' => $each_is_prize,
													'business_id' => $each_business_id,
													'need_price' => $each_need_price,
												);
											}

										}
										//读写账单表start
										if($each_sell_price>0){
											$arr_each_sell_price=array(
												'user_id'=>$each_user_id,
												'order_id'=>$each_order_id,
												'create_time'=>$now_time,
												'post_create_time'=>$now_time,
												'price'=>$each_sell_price,
												'account_type'=>'7',
												'status'=>0,
												'wx_source'=>4,
												'trade_no'=>$trade_no,
												'buyer_email'=>$buyer_email,
												'source'=>0
											);
											$model_account->add($arr_each_sell_price);
										}
										if($each_service_price>0){
											$arr_each_service_price=array(
												'user_id'=>$each_user_id,
												'order_id'=>$each_order_id,
												'create_time'=>$now_time,
												'post_create_time'=>$now_time,
												'price'=>$each_service_price,
												'account_type'=>'7',
												'status'=>0,
												'wx_source'=>4,
												'trade_no'=>$trade_no,
												'buyer_email'=>$buyer_email,
												'source'=>0
											);
											$model_account->add($arr_each_service_price);
										}
										//读写账单表end

									}

									//处理退还玩具的订单状态
									if($each_change_order_id_arr){
										foreach($each_change_order_id_arr as $val){
											$check_change_order_id_new = $val;
											if($check_change_order_id_new>0)  {
												//换玩具
												$getChangeOrderRes =$model->table('baby_toys_order')
													->field('status,card_id,business_id,user_id,sell_price,service_price,toys_number')
													->where("id=$check_change_order_id_new and state='0' and status in (2,5,6,14)")
													->find();

												$getChangeOrderStatus=$getChangeOrderRes['status'];
												$getChangebus_id=$getChangeOrderRes['business_id'];
												$getChange_user_id=$getChangeOrderRes['user_id'];
												$getChange_toys_number=$getChangeOrderRes['toys_number'];
												$getChange_sell_price=$getChangeOrderRes['sell_price'];//租价价格总价格 或 售卖价格
												$getChange_service_price=$getChangeOrderRes['service_price'];//服务费
												$end_change_price=$getChange_sell_price+$getChange_service_price;
												if($getChangeOrderRes) {
													if(($getChangeOrderStatus==6) || ($getChangeOrderStatus==14) ) {
														$up_change_status="10";
														$refund_status=$getChangeOrderStatus;
														$checkListingRes=$model_listing
															->field('id')
															->where("order_id=$check_change_order_id_new and status='7' and state='0' ")->find();
														$checkComListingID=$checkListingRes['id'];

														if($checkComListingID<=0 ) {
															$inComListing_data=array(
																'business_id'=>$getChangebus_id,
																'order_id'=>$check_change_order_id_new,
																'create_time'=>$now_time,
																'post_create_time'=>$now_time,
																'status'=>'7',
																'status_name'=>'已完成'
															);
															if($inComListing_data) {
																$model_listing->add($inComListing_data);
															}
														}
														$update_change_data['refund_status']=$getChangeOrderStatus;
														$update_change_data['post_create_time'] =$now_time;
														$update_change_data['status'] = $up_change_status;
													} else {
														if($end_change_price>0) {
															$up_change_status="8";
														} else {
															$up_change_status="9";
														}

														if($getChange_toys_number) {//改出库状态
															$upBusLisdata['out_state']=0;
															$model_business_listing->where("id=$getChange_toys_number")->save($upBusLisdata);
														}
														$update_change_data['refund_status']=$getChangeOrderStatus;
														$update_change_data['post_create_time'] =$now_time;
														$update_change_data['status'] = $up_change_status;


														//玩具是否待租 结束
													}
													$model_order->where('id='.$check_change_order_id_new)->save($update_change_data);
												}
											}
										}
									}

									//需要交付的押金
									$need_deposit_price_total = 0;
									//存入押金账单信息

									//查询订单表需要支付的押金记录
									$new_deposit_order_id_res =  $model->table('baby_toys_order')
										->where("combined_order_id='$each_combined_order_id' and state='2' and business_id='99999999'")
										->field("id,sell_price")
										->find();
									$new_deposit_order_id = $new_deposit_order_id_res['id'];
									$need_deposit_price_total = $new_deposit_order_id_res['sell_price'];
									if($need_deposit_price_total>0){
										$arr_need_deposit_price_total=array(
											'user_id'=>$each_user_id,
											'order_id'=>$new_deposit_order_id,
											'create_time'=>$now_time,
											'post_create_time'=>$now_time,
											'price'=>$need_deposit_price_total,
											'account_type'=>'3',
											'status'=>2,
											'wx_source'=>4,
											'trade_no'=>$trade_no,
											'buyer_email'=>$buyer_email,
											'source'=>1
										);
										$model_account->add($arr_need_deposit_price_total);
									}

									//读写押金表
									//1.解冻本次使用卡订单涉及的押金

									$update_change_data1['status'] =0;
									$update_change_data1['post_create_time'] = $now_time;
									$model_deposit->where("card_id >0 and user_id='$each_user_id' and account_type='3' and status='2'")
										->save($update_change_data1);

									//2.解冻本次 未使用卡&&退还玩具 的 订单涉及的押金

									if(!empty($each_change_business_id_arr)){
										$update_change_data2['status'] =0;
										$update_change_data2['post_create_time'] = $now_time;
										$model_deposit->where("business_id1 in ($each_change_business_id_arr) and user_id='$each_user_id' and account_type='3' and status='2' and card_id='0'")
											->save($update_change_data2);
									}
									//3.本次支付的押金写进押金表  可提现状态
									if($need_deposit_price_total>0){
										$arr_need_deposit_price_total=array(
											'user_id'=>$each_user_id,
											'create_time'=>$now_time,
											'post_create_time'=>$now_time,
											'price'=>$need_deposit_price_total,
											'account_type'=>'3',
											'status'=>'0',
										);
										$model_deposit->add($arr_need_deposit_price_total);
									}
									//4.当前批号下面 卡 对应的押金写进押金表  冻结状态
									$can_no_use_deposit_total = 0;
									$new_total_card_price_res = $model_order
										->where("state='0' and status in (2,5,6,14,17)  and card_id>0 and user_id='$each_user_id'")
										->field('card_id')
										->group("card_id")
										->select();
									$new_card_id_arr = array();
									if($new_total_card_price_res){
										foreach($new_total_card_price_res as $val){
											$new_card_id_arr[] = $val['card_id'];
										}
									}
									if(count($new_card_id_arr)>0){
										foreach($new_card_id_arr as $val){
											$tmp_use_card_id = $val;
											//查询该卡需要支付的押金
											$card_use_deposit2_res = $model->table('baby_toys_card as c')
												->join(" left join baby_toys_order as o on c.order_id=o.id")
												->join(" left join baby_toys_business as b on o.business_id=b.id")
												->field('b.highest_price')
												->where("c.id=$tmp_use_card_id")
												->find();
											$card_use_deposit2 = $card_use_deposit2_res['highest_price'];
											$can_no_use_deposit_total += $card_use_deposit2;
											//查询该卡对应的玩具
											$business_id_arr_now = array();
											$use_card_business_id_res = $model_order->field('business_id')
												->where("card_id='$tmp_use_card_id' and state='0' and status in (2,5,6,14,17) and user_id='$each_user_id'")
												->select();
											if($use_card_business_id_res){
												foreach($use_card_business_id_res as $val){
													$business_id_arr_now[] = $val['business_id'];
												}
											}
//                    var_dump($business_id_arr_now);die;
											if(count($business_id_arr_now)==0){
												//不操作
											}elseif(count($business_id_arr_now)==1){
												$business_id1_now = $business_id_arr_now[0];
												$business_id2_now = 0;

												$arr_need_deposit_price_total3=array(
													'user_id'=>$each_user_id,
													'create_time'=>$now_time,
													'post_create_time'=>$now_time,
													'price'=>$card_use_deposit2,
													'account_type'=>'3',
													'status'=>'2',
													'card_id'=>$tmp_use_card_id,
													'business_id1'=>$business_id1_now,
													'business_id2'=>$business_id2_now,
												);
												$model_deposit->add($arr_need_deposit_price_total3);
											}elseif(count($business_id_arr_now)==2){
												$business_id1_now = $business_id_arr_now[0];
												$business_id2_now = $business_id_arr_now[1];
												$arr_need_deposit_price_total3=array(
													'user_id'=>$each_user_id,
													'create_time'=>$now_time,
													'post_create_time'=>$now_time,
													'price'=>$card_use_deposit2,
													'account_type'=>'3',
													'status'=>'2',
													'card_id'=>$tmp_use_card_id,
													'business_id1'=>$business_id1_now,
													'business_id2'=>$business_id2_now,
												);
												$model_deposit->add($arr_need_deposit_price_total3);
											}
										}
									}

									//5.当前批号下面 非卡 对应的押金写进押金表  冻结状态
									if(count($no_card_order_id_arr)>0){
										foreach($no_card_order_id_arr as $val){
											$no_card_need_price_now = 0;
											$no_card_business_id_now = $val['business_id'];
											$no_card_is_prize_now = $val['is_prize'];
											$no_card_need_price_now = $val['need_price'];
											//普通玩具
											if($no_card_is_prize_now==0){
												$can_no_use_deposit_total += $no_card_need_price_now;
												$arr_need_deposit_price_total4=array(
													'user_id'=>$each_user_id,
													'create_time'=>$now_time,
													'post_create_time'=>$now_time,
													'price'=>$no_card_need_price_now,
													'account_type'=>'3',
													'status'=>'2',
													'card_id'=>'0',
													'business_id1'=>$no_card_business_id_now,
													'business_id2'=>0,
												);
												$model_deposit->add($arr_need_deposit_price_total4);
											}
											//高端玩具
											if($no_card_is_prize_now==3){
												$card_time=date("Y-m-d 00:00:00",strtotime("+1 day"));
												$getCard_Count  = $model->table('baby_toys_card')
													->where("user_id=$each_user_id and state='0' and (( (case when final_end_time > end_time then final_end_time else end_time end) >'$card_time') or (end_time='0000-00-00 00:00:00') )")
													->count();
												if($getCard_Count>0){
													//会员
												}else{
													//非会员
													$no_card_need_price_now = $no_card_need_price_now*1.3 ;
												}
												$can_no_use_deposit_total += $no_card_need_price_now;
												$arr_need_deposit_price_total5=array(
													'user_id'=>$each_user_id,
													'create_time'=>$now_time,
													'post_create_time'=>$now_time,
													'price'=>$no_card_need_price_now,
													'account_type'=>'3',
													'status'=>'2',
													'card_id'=>'0',
													'business_id1'=>$no_card_business_id_now,
													'business_id2'=>0,
												);
												$model_deposit->add($arr_need_deposit_price_total5);
											}

										}
									}
									//6。计算全部最终可提现的押金
									$can_use_deposit_total_res = $model->table('baby_toys_deposit')
										->where(" user_id='$each_user_id' and state='0' and account_type='3' and status='0'")
										->field("SUM(price) as use_price")
										->find();
									$can_use_deposit_total = $can_use_deposit_total_res['use_price'];

									$live_deposit_end = $can_use_deposit_total - $can_no_use_deposit_total;//最终可提现的押金

									//所有解冻的押金全部废弃
									$update_change_data3['status'] =7;
									$update_change_data3['post_create_time'] = $now_time;
									$model_deposit->where("user_id='$each_user_id' and state='0' and account_type='3' and status='0'")
										->save($update_change_data3);
									//7.生成最终可提现的押金记录
									if($live_deposit_end>0){
										$arr_live_deposit_end=array(
											'user_id'=>$each_user_id,
											'create_time'=>$now_time,
											'post_create_time'=>$now_time,
											'price'=>$live_deposit_end,
											'account_type'=>'3',
											'status'=>'0',
											'card_id'=>'0',
											'business_id1'=>0,
											'business_id2'=>0,
										);
										$model_deposit->add($arr_live_deposit_end);
									}
									//处理同一批号下的历史遗留待支付订单
									$update_change_data4['status'] ='2';
									$update_change_data4['post_create_time'] = $now_time;
									$model_order->where("combined_order_id='$each_combined_order_id' and state='2'")
										->save($update_change_data4);
									//----------------------------------------------------------------------------new end-------------------------------------------------------------------------------------------
								}

						    }      
						}
						//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
					} else {
	                    $update_data['post_create_time'] = date("Y-m-d H:i:s");
	                    $update_data['trade_no'] = $trade_no;
	                    $update_data['buyer_email'] = $buyer_email;
	                    $update_data['pay_price'] = $total_fee;
	                    $update_data['state'] = '0';
	                    $verification=mt_rand(100000000,999999999);
	                    $update_data['verification'] = $verification;
	                    $update_data['verification1'] = $verification;
	                    if($out_trade_no) {
							$check_order = M("order")
							          ->field('user_id,status,verification,order_role,babys_idol_id,packet_id,price,business_id')
							          ->where('id='.$out_trade_no)
							          ->find();
							$check_status=$check_order['status'];
							$check_verification=$check_order['verification'];
							$order_role=$check_order['order_role'];
							$business_id=$check_order['business_id'];
							$user_id=$check_order['user_id'];
							//成长记录
							$babys_idol_id=$check_order['babys_idol_id'];
							if($order_role==1) {
								$check_idol = M("babys_idol")
										->field('is_each_others')
										->where('id='.$babys_idol_id)
										->find();
								$is_each_others=$check_idol['is_each_others'];
								if($is_each_others!=0) {
									$status= '3';                  
								} else {
									$status= '1';
								}
								$idol_update_data['pay_time']=date("Y-m-d");
								$idol_update_data['post_create_time'] = date("Y-m-d H:i:s");
								if($is_each_others!=0) {//30天计算
									$str_next_month=time()+30*24*3600;
									$next_month=date('Y-m-d',$str_next_month);
									$idol_update_data['over_time']=$next_month;
									$idol_update_data['is_each_others']='2';
								}
								$temp_business_id=$babys_idol_id;
							} else {
								$status= '1';
								$temp_business_id=$business_id;
							}
							$update_data['status'] = $status; 
							//红包
							$packet_id=$check_order['packet_id'];
							$price=$check_order['price'];
							$pay_price=$price-$total_fee;
							$packet_update_data['is_grab_use']='2';
							$packet_update_data['post_create_time']=date("Y-m-d H:i:s");
							if(($pay_price>0) && ($packet_id>0) ) {
								$payment='7';
							} else {
								$payment='1';
							}
							$update_data['payment']=$payment;
							$update_data['wx_source']='4';

							//添加红包
							$now_time=date("Y-m-d H:i:s");
							$now_time_later=date("Y-m-d", time()+30*24*3600);
							$add_packet_data=array();
							for($i=0;$i<9;$i++) {
								$packet_price=rand(3,5);
								$add_packet_data[$i]=array(
									'user_id'=>$user_id,
									'packet_price'=>$packet_price,
									'packet_type'=>'0',
									'packet_msg'=>'秀秀内商家使用',
									'create_time'=>$now_time,
									'post_create_time'=>$now_time,
									'packet_description'=>'红包的金额和你的颜值一样高哦',
									'order_id'=>$out_trade_no,
									'business_id'=>$temp_business_id,
									'order_role'=>$order_role,
									'expiry'=>$now_time_later
									);
							}
							$hign_packet_price=rand(6,8);
							$add_packet_data[9]=array(
								'user_id'=>$user_id,
								'packet_price'=>$hign_packet_price,
								'packet_type'=>'0',
								'packet_msg'=>'秀秀内商家使用',
								'create_time'=>$now_time,
								'post_create_time'=>$now_time,
								'packet_description'=>'红包的金额和你的颜值一样高哦',
								'order_id'=>$out_trade_no,
								'business_id'=>$temp_business_id,
								'order_role'=>$order_role,
								'expiry'=>$now_time_later
								);
	                      
							if((empty($check_verification) && (($check_status=='2') || ($check_status=='1') ) ) ) {
								M("order")->where('id='.$out_trade_no)->save($update_data);
								if($add_packet_data) {
									M('packet')->addAll($add_packet_data);
								}
							}

							if($order_role==1) {                
								M("babys_idol")->where('id='.$babys_idol_id)->save($idol_update_data);
							}
							if(($pay_price>0) && ($packet_id>0) ) {
								M("packet")->where('id='.$packet_id)->save($packet_update_data);
							}    
	                    }
	                }         
	            }
	            return ture;
	        }else {
	            return false;//验证失败
	        }    
	    }
        
	}
?>