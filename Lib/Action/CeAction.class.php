<?php
class CeAction extends Action{
    //在类初始化方法中，引入相关类库    
    /*public function _initialize() {
        vendor('Video.Videourlparser');
    }*/
    /**
     * 验证是否已设置session
     */
    //玩具赔付页面
    public function toyspayment(){
        $this->display();
    }
    //卡
    public function getCardInfo() {
        $str_card="418,420,422,424,548,649,652,655,1040,1041,1137,1139,1253,1255,1336,1338,1340,1342,1416,1625,1627,1629,1631,2253,2255,2633,2635,2853,2902,2907,2914,2916";
        return $str_card;
    }
    //玩具编号数量
    public function getBusinessTotalNum($business_id) {
        $this->checkSession();
        $model = new Model();
        //总库存
        $total_count=$model->table('baby_toys_business_listing')
            ->where(" new_old='0' and state='0' and business_id='$business_id' ")
            ->count();
        //可用编号
        $use1_count=$model->table('baby_toys_business_listing')
            ->where("  state='0' and business_id='$business_id' and is_use in ('0','5') and out_state='0' ")   //new_old='0' and
            ->count();
        //没选编号订单
        $use2_count=$model->table('baby_toys_order')
            ->where("state='0' and business_id=$business_id and (toys_number='' or toys_number is null ) and status in (1,2) and is_prize in (0,3) ")
            ->count();
        //可用库存
        $use_count = $use1_count - $use2_count;

        $new_old_count=$model->table('baby_toys_business_listing')
            ->where(" new_old='2' and state='0' and business_id='$business_id'  ") //and is_use in ('0','5') and out_state='0'
            ->count();
        $use_count = $use_count - $new_old_count;

        $num = array(
            'total_count' => $total_count,
            'use_count' => $use_count
        );
        return $num;
    }
    //玩具电池信息
    public function returnBatteryInfo($combined_order_id,$symbol,$battery_brand,$user_id) {
        $MyRes="";
        $this->checkSession();
        $battery_title="";
        $model = new Model();
        $getBrandRes=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where("o.combined_order_id='$combined_order_id' and o.user_id=$user_id and o.state='0' and o.is_prize='0' and ((b.battery_number5>0) or (b.battery_number7>0)) and ( (b.battery_number5>0) or (b.battery_number7>0) ) and ( (b.business_brand like '%汇乐%') or (b.business_brand like '%谷雨%') ) ")
                    ->field("SUM(b.battery_number5) as battery_number5,SUM(b.battery_number7) as battery_number7")
                    ->find();
        $special5=$getBrandRes['battery_number5'];
        $special7=$getBrandRes['battery_number7'];
        if($battery_brand==1) {
            $getBrandRes2=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where("o.combined_order_id='$combined_order_id' and o.user_id=$user_id and o.state='0' and o.is_prize='0' and ((b.battery_number5>0) or (b.battery_number7>0)) and ( (b.battery_number5>0) or (b.battery_number7>0) ) ")
                    ->field("SUM(b.battery_number5) as battery_number5,SUM(b.battery_number7) as battery_number7")
                    ->find();
            $special5_2=$getBrandRes2['battery_number5'];
            $special7_2=$getBrandRes2['battery_number7'];
            if($special5_2 && $special5) {
                $battery_title5=" 5号电池".$special5_2."节";
                $getBatteryPriceRes5=$this->GetBatteryPriceTotal(5,$battery_brand,$special5_2);
                $battery_price5=$getBatteryPriceRes5['price'];
            } else {
                $battery_title5="";
                $battery_price5=0;
            }
            if($special7_2 && $special7) {
                $battery_title7=" 7号电池".$special7_2."节";
                $getBatteryPriceRes7=$this->GetBatteryPriceTotal(7,$battery_brand,$special7_2);
                $battery_price7=$getBatteryPriceRes7['price'];
            } else {
                $battery_title7="";
                $battery_price7=0;
            }
            $battery_title.="【华太电池】：";
            $tmp_battery_price=$battery_price5+$battery_price7;
            if($special5_2 && $special5) {
                $battery_title.=$battery_title5.",";
            }
            if($special7_2 && $special7 ) {
                $battery_title.=$battery_title7.",";
            }
            $battery_title.=" 共".$tmp_battery_price."元";
            $MyRes.=$symbol.$battery_title; 
        } else {
            $getBatRes=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where("o.combined_order_id='$combined_order_id' and o.user_id=$user_id and o.state='0' and o.is_prize='0' and ((b.battery_number1>0) or (b.battery_number2>0) or (b.battery_number3>0) or (b.battery_number4>0) or (b.battery_number5>0) or (b.battery_number6>0) or (b.battery_number7>0) or (b.battery_number8>0) )")
                    ->field("SUM(b.battery_number1) as battery_number1,SUM(b.battery_number2) as battery_number2,SUM(b.battery_number3) as battery_number3,SUM(b.battery_number4) as battery_number4,SUM(b.battery_number5) as battery_number5,SUM(b.battery_number6) as battery_number6,SUM(b.battery_number7) as battery_number7,SUM(b.battery_number8) as battery_number8,o.create_time")
                    ->find();
            if($getBatRes) {
                $battery_number1=$getBatRes['battery_number1'];
                $battery_number2=$getBatRes['battery_number2'];
                $battery_number3=$getBatRes['battery_number3'];
                $battery_number4=$getBatRes['battery_number4'];
                $battery_number5=$getBatRes['battery_number5'];
                $battery_number6=$getBatRes['battery_number6'];
                $battery_number7=$getBatRes['battery_number7'];
                $battery_number8=$getBatRes['battery_number8'];
                $show_create_time=$getBatRes['create_time'];
                if($battery_number1>0) {
                    $battery_title1=" 1号电池".$battery_number1."节";
                    $getBatteryPriceRes1=$this->GetBatteryPriceTotal(1,$battery_brand,$battery_number1);
                    $battery_price1=$getBatteryPriceRes1['price'];
                } else {
                    $battery_title1="";
                    $battery_price1=0; 
                }
                if($battery_number2>0) {
                    $battery_title2=" 2号电池".$battery_number2."节";
                    $getBatteryPriceRes2=$this->GetBatteryPriceTotal(2,$battery_brand,$battery_number2);
                    $battery_price2=$getBatteryPriceRes2['price'];
                } else {
                    $battery_title2="";
                    $battery_price2=0;
                }
                if($battery_number3>0) {
                    $battery_title3=" 3号电池".$battery_number3."节";
                    $getBatteryPriceRes3=$this->GetBatteryPriceTotal(3,$battery_brand,$battery_number3);
                    $battery_price3=$getBatteryPriceRes3['price'];
                } else {
                    $battery_title3="";
                    $battery_price3=0;
                }
                if($battery_number4>0) {
                    $battery_title4=" 4号电池".$battery_number4."节";
                    $getBatteryPriceRes4=$this->GetBatteryPriceTotal(4,$battery_brand,$battery_number4);
                    $battery_price4=$getBatteryPriceRes4['price'];
                } else {
                    $battery_title4="";
                    $battery_price4=0; 
                }
                if($battery_number5>0 && ( $show_create_time<'2017-10-27 18:20:00' || (empty($special5) && $show_create_time>'2017-10-27 18:20:00' ) ) ) {
                    $battery_title5=" 5号电池".$battery_number5."节";
                    $getBatteryPriceRes5=$this->GetBatteryPriceTotal(5,$battery_brand,$battery_number5);
                    $battery_price5=$getBatteryPriceRes5['price'];
                } else {
                    $battery_title5="";
                    $battery_price5=0;
                }
                if($battery_number6>0) {
                    $battery_title6=" 6号电池".$battery_number6."节";
                    $getBatteryPriceRes6=$this->GetBatteryPriceTotal(6,$battery_brand,$battery_number6);
                    $battery_price6=$getBatteryPriceRes6['price'];
                } else {
                    $battery_title6="";
                    $battery_price6=0;
                }
                if($battery_number7>0 && ( $show_create_time<'2017-10-27 18:20:00' || (empty($special7) && $show_create_time>'2017-10-27 18:20:00' ) ) ) {
                    $battery_title7=" 7号电池".$battery_number7."节";
                    $getBatteryPriceRes7=$this->GetBatteryPriceTotal(7,$battery_brand,$battery_number7);
                    $battery_price7=$getBatteryPriceRes7['price'];
                } else {
                    $battery_title7="";
                    $battery_price7=0;
                }
                if($battery_number8>0) {
                    $battery_title8=" 纽扣电池".$battery_number8."节";
                    $getBatteryPriceRes8=$this->GetBatteryPriceTotal(8,$battery_brand,$battery_number8);
                    $battery_price8=$getBatteryPriceRes8['price'];
                } else {
                    $battery_title8="";
                    $battery_price8=0;
                }
                $battery_title.="【南孚电池】：";
                $tmp_battery_price=$battery_price1+$battery_price2+$battery_price3+$battery_price4+$battery_price5+$battery_price6+$battery_price7+$battery_price8;
                if($battery_title1) {
                    $battery_title.=$battery_title1.",";
                }
                if($battery_title2) {
                    $battery_title.=$battery_title2.",";
                }
                if($battery_title3) {
                    $battery_title.=$battery_title3.",";
                }
                if($battery_title4) {
                    $battery_title.=$battery_title4.",";
                }
                if($battery_title5 && ( $show_create_time<'2017-10-27 18:20:00' || (empty($special5) && $show_create_time>'2017-10-27 18:20:00' ) ) ) {
                    $battery_title.=$battery_title5.",";
                }
                if($battery_title6) {
                    $battery_title.=$battery_title6.",";
                }
                if($battery_title7 && ( $show_create_time<'2017-10-27 18:20:00' || (empty($special7) && $show_create_time>'2017-10-27 18:20:00' ) ) ) {
                    $battery_title.=$battery_title7.",";
                }
                if($battery_title8) {
                    $battery_title.=$battery_title8.",";
                }
                $battery_title.=" 共".$tmp_battery_price."元";
                $MyRes.=$symbol.$battery_title; 
            }
        }
        return $MyRes;
    }


    //电池单价
    static public function GetBatteryPriceTotal($type,$brand,$number){
        $imgRes=array();
        $getorderRes= M("toys_battery")
                    ->where("state='0' and type='$type' and brand='$brand'")
                    ->field("price")
                    ->find();
        $getorderPrice=$getorderRes['price'];
        if($getorderPrice) {
            $imgRes['price']=$getorderPrice*$number;
        } else {
            $imgRes['price']=2*$number;
        }
        return $imgRes;
    }
    //导出方法
    public function exportExcel($expTitle,$expCellName,$expTableData){
        ob_clean();
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $xlsTitle.date('_mdHis');
        //$fileName = $_SESSION['loginAccount'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
       /*$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
       $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->getActiveSheet()->getStyle('A'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('C'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('D'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('F'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('G'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

           /*$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
           $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
           $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
           $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
           $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);*/
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            //水平居中
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setWrapText(true);  
            //垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getFont()->setName('宋体')->setSize(14)->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        }   
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('B'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('C'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('D'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('E'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('F'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('G'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
               /*$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
               $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
               $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);*/
               $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
               $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                if($j==2 || $j==3 || $j==4 || $j==5 || $j==6) {
                    $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i+3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
                } else {
                    //水平居中
                    $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    //垂直居中
                    $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                }                
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i+3))->getAlignment()->setWrapText(true);  
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }             
        }  
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }
    //配送玩具信息
    public function returnsendInfo($where_conditon,$symbol) {
        $MyRes=array();
        $this->checkSession();
        $model = new Model();
        $getSendRes=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where($where_conditon)
                    ->field('o.business_id,b.way,b.business_title,o.remark,o.card_id,o.toys_number,o.order_time as new_create_time,o.is_size,o.is_prize')
                    ->select();
        $remark="";
        if($getSendRes) {
            foreach ($getSendRes as $k => $val) {
                $is_size=$val['is_size'];
                $tmp_bus_id=$val['business_id'];
                $tmp_bus_title=$val['business_title'];
                $tmp_remark=$val['remark'];
                $tmp_card_id=$val['card_id'];
                $tmp_toys_number=$val['toys_number'];
                $tmp_way=$val['way'];
                $is_prize = $val['is_prize'];
                $tmp_new_create_time = $val['new_create_time'];
                if($k==0) {
                    if($tmp_card_id>0) {
                        $get_card_info=M("toys_card")->where("id=$tmp_card_id ")->find();
                        $get_card_name=$get_card_info['card_name'];
                        if($get_card_name==1) {
                            $remark.="月卡用户";
                        } elseif($get_card_name==2) {
                            $remark.="季卡用户";
                        } elseif($get_card_name==3) {
                            $remark.="半年卡用户";
                        } elseif($get_card_name==4) {
                            $remark.="年卡用户";
                        } else {
                            $remark.="体验卡用户";
                        }
                    }
                    $remark.="  ".$tmp_remark;
                    $br="";
                } else {
                    $br=$symbol.$symbol;
                }
                $send_toys.=$br.$tmp_bus_id;
                if($tmp_toys_number) {
                    $send_toys.="【".$tmp_toys_number."】";
                }
                if($is_prize==3){
                    $is_size = 2;
                }
                if($tmp_way==0){
                    $is_size = 3;
                }
                if($is_size==1) {
                    $toys_size_name="【小】";
                }elseif($is_size==2){
                    $toys_size_name="【彩虹】";
                }elseif($is_size==3){
                    $toys_size_name="【售卖】";
                } else {
                    $toys_size_name="【大】";
                }
                $send_toys.=$symbol.$toys_size_name.$tmp_bus_title;
            }
            $MyRes=array('send_toys'=>$send_toys,'remark'=>$remark,'tmp_new_create_time'=>$tmp_new_create_time);
        }
        return $MyRes;
    }
    
    //取回玩具信息
    public function returnpickInfo($where_conditon,$symbol) {
        $MyRes=array();
        $model = new Model();
        $this->checkSession();
        $getPickRes=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where($where_conditon)
                    ->field('o.business_id,b.business_title,o.toys_number,o.is_size,o.is_prize,b.business_parts')
                    ->select();
        $business_parts_list = "";
        if($getPickRes) {
            foreach ($getPickRes as $k => $val) {
                $is_size=$val['is_size'];
                $tmp_bus_id=$val['business_id'];
                $tmp_bus_title=$val['business_title'];
                $tmp_bus_parts = $val['business_parts'];//玩具主要检查事项

                //检查事项
                if($tmp_bus_parts){
                    $business_parts_list .= "【".$tmp_bus_title."】：".$tmp_bus_parts.$symbol;
                }

                $tmp_toys_number=$val['toys_number'];
                $tmp_way=$val['way'];
                $is_prize=$val['is_prize'];
                if($is_prize==3){
                    $is_size = 2;
                }
                if($is_size==1) {
                    $toys_size_name="【小】";
                }elseif($is_size==2){
                    $toys_size_name="【彩虹】";
                } else {
                    $toys_size_name="【大】";
                }
                if($k==0) {
                    $br="";
                } else {
                    $br=$symbol.$symbol;
                }
                $pick_toys.=$br.$tmp_bus_id;
                if($tmp_toys_number) {
                    $pick_toys.="【".$tmp_toys_number."】";
                }
                $pick_toys.=$symbol.$toys_size_name.$tmp_bus_title;
            }
            $MyRes=array('pick_toys'=>$pick_toys,'business_parts_list'=>$business_parts_list);
        }
        return $MyRes;
    }
    
    private function checkSession(){
        import("ORG.Util.Session");
    
        //未设置session['username']值或者session过期

        if(!Session::get('username')  ) {
            $url = U('Login/login');
            //$url  = str_replace('/'.MODULE_NAME.'/'.ACTION_NAME.'.html','',$url);
            echo "<script>top.location.href=\"$url\";</script>";
            exit();
        }
        
    }
    public function getsendlist() {
        $this->checkSession(); 
        $user_id=I("user_id");
        $day_ago=date("Y-m-d 00:00:00", strtotime ("-2 day"));
        $search_condition_id=I("search_condition_id");
        $search_address=I("search_address");
        $excel_state=I("excel_state");
        $arr_user_id=I("arr_user_id");
        $str_card_info=$this->getCardInfo(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5)  ";
        if($user_id>0) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
            $order_condion="update_time desc ";
        } elseif($arr_user_id) {
            $tmp_user_id=explode("、", $arr_user_id);
            $real_arr_user_id=array();
            foreach ($tmp_user_id as $value) {
                if($value>0) {
                    $real_arr_user_id[]=$value;
                }
            }
            $str_user_id=implode(",", $real_arr_user_id);
            $where2.=" and o.user_id in ($str_user_id) ";
            $this->assign('arr_user_id',$arr_user_id);
            $order_condion="FIELD(o.user_id,$str_user_id) ";
        } else {
            $order_condion="update_time desc ";
        }
        if($search_condition_id==1) {//已超过2天
            $where2.=" and o.status in (2,17) and o.create_time<'$day_ago'  ";
            $this->assign('search_condition_id',$search_condition_id);
        } elseif($search_condition_id==2) {//取消过派单
            $where2.=" and o.status in (2,17) and o.cancel_reason in (1,2,3)  ";
            $this->assign('search_condition_id',$search_condition_id);
        } elseif($search_condition_id==3) {//已超2天-取消过
            $where2.=" and o.status in (2,17) and o.create_time<'$day_ago' and o.cancel_reason in (1,2,3)  ";
            $this->assign('search_condition_id',$search_condition_id);
        } else {
            $where2.=" and ((o.status in (2,17)) or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 ) )  ";
        }
        if($search_address) {
            $where2.=" and a.address like '%$search_address%' ";
            $this->assign('search_address',$search_address);
        }
        $model = new Model();
        if($excel_state==1) {//导出
            $getexcelUserRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                    ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                    ->where($where2)
                    ->field("o.user_id,max(o.create_time) update_time,a.user_name,a.mobile,a.address ")
                    ->group("o.user_id")
                    ->order($order_condion)
                    ->select();
            $xlsData=array();
            if($getexcelUserRes) {
                $xlsName="派单";
                $xlsCell  = array(
                    array('user_id','用户id/下单时间'),
                    array('user_info','配送信息'),
                    array('send_toys','待配送'),
                    array('pick_toys','待取回'),
                    array('remark','备注'),
                    array('business_parts_list','注意事项'),
                    array('user_act','用户操作')
                );
                foreach ($getexcelUserRes as $key => $value) {
                    $tmp_user_id=$value['user_id'];
                    $order_user_name=$value['user_name'];
                    $order_mobile=$value['mobile'];
                    $order_address=$value['address'];
                    $user_info=$send_toys=$pick_toys="";
                    if($order_user_name) {
                        $user_info.=$order_user_name;
                    }
                    if($order_mobile) {
                        $user_info.="\n".$order_mobile;
                    }
                    if($order_address) {
                        $user_info.="\n".$order_address;
                    }
                    $send_toys=$pick_toys="";

                    //配送
                    $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3) ","\n");
                    if(empty($returnsendInfo) ) {
                        $tmp_remark="";
                    } else {
                        $send_toys=$returnsendInfo['send_toys'];
                        $tmp_remark=$returnsendInfo['remark'];
                        $tmp_new_create_time=$returnsendInfo['tmp_new_create_time'];
                    }
                    $checkBatRes=M("toys_order")
                            ->where("user_id=$tmp_user_id and is_prize='2' and is_battery='1' and state='0' and status in (2,17) ")
                            ->field("combined_order_id,battery_brand ")
                            ->select();
                    if($checkBatRes) {
                        foreach ($checkBatRes as $k=> $va) {
                            $bat_combined_order_id=$va['combined_order_id'];
                            $bat_battery_brand=$va['battery_brand'];
                            $getreturnBatteryInfo=$this->returnBatteryInfo($bat_combined_order_id,"\n",$bat_battery_brand,$tmp_user_id);
                            if($k==0) {
                                $symbol="\n\n";
                            } else {
                                $symbol="\n";
                            }
                            $send_toys.=$symbol.$getreturnBatteryInfo;
                        }
                    }
                    $get_user_remark=M("toys_user_remark")
                                ->field("remark")
                                ->where("user_id=$tmp_user_id and state='0' ")
                                ->find();
                    $remark=$tmp_remark.$get_user_remark['remark'];
                    $getPrizeRes=$model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_prize as b on o.business_id=b.id")
                        ->where("o.status in (2,17) and o.user_id=$tmp_user_id and o.is_prize='1' and o.state='0' ")
                        ->field('b.prize_title2')
                        ->select();
                    if($getPrizeRes) {
                        foreach ($getPrizeRes as $k => $val) {
                            $tmp_bus_title=$val['prize_title2'];
                            if($k==0) {
                                $symbol="\n\n";
                            } else {
                                $symbol="\n";
                            }
                            $send_toys.=$symbol."【礼品】".$tmp_bus_title;
                        }
                    }
                    $business_parts_lists = "";
                    $returnpickInfo=$this->returnpickInfo("o.status in (10) and o.state='0' and o.user_id=$tmp_user_id ","\n");
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                        $business_parts_lists = $returnpickInfo['business_parts_list'];
                    }
                    $user_act = " □ 玩具实物和所租是否相符\n □ 需安装玩具是否安装\n □ 玩具是否干净\n 签收人：____________";
                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id."\n".$tmp_new_create_time,
                        'user_info'=>$user_info,
                        'send_toys'=>$send_toys,
                        'pick_toys'=>$pick_toys,
                        'remark'=>$remark,
                        'business_parts_list' => $business_parts_lists,
                        'user_act' => $user_act
                    );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }


        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                        ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                        ->where($where2)
                        ->field('o.user_id')
                        ->group("o.user_id")
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                    ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                    ->where($where2)
                    ->field("o.user_id,max(o.create_time ) update_time,a.user_name,a.mobile,a.address ")
                    ->group("o.user_id")
                    ->order($order_condion)
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_user_id=$value['user_id'];
                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];
                $order_address=$value['address'];
                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3) ","<br/>");
                if(empty($returnsendInfo)) {
                    $tmp_remark="";
                } else {
                    $send_toys=$returnsendInfo['send_toys'];
                    $tmp_remark=$returnsendInfo['remark'];
                    $tmp_new_create_time=$returnsendInfo['tmp_new_create_time'];
                }
                $checkBatRes=M("toys_order")
                            ->where("user_id=$tmp_user_id and is_prize='2' and is_battery='1' and state='0' and status in (2,17) ")
                            ->field("combined_order_id,battery_brand ")
                            ->select();
                if($checkBatRes) {
                    foreach ($checkBatRes as $k => $va) {
                        $bat_combined_order_id=$va['combined_order_id'];
                        $bat_battery_brand=$va['battery_brand'];
                        $getreturnBatteryInfo=$this->returnBatteryInfo($bat_combined_order_id,"<br/>",$bat_battery_brand,$tmp_user_id);
                        if($k==0) {
                            $symbol="<br/><br/>";
                        } else {
                            $symbol="<br/>";
                        }
                        $send_toys.=$symbol.$getreturnBatteryInfo;
                    }
                }
                $get_user_remark=M("toys_user_remark")
                            ->field("remark")
                            ->where("user_id=$tmp_user_id and state='0' ")
                            ->find();
                $remark=$tmp_remark.$get_user_remark['remark'];

                $getPrizeRes=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_prize as b on o.business_id=b.id")
                    ->where("o.status in (2,17) and o.user_id=$tmp_user_id and o.is_prize='1' and o.state='0' ")
                    ->field('b.prize_title2')
                    ->select();
                if($getPrizeRes) {
                    foreach ($getPrizeRes as $k => $val) {
                        $tmp_bus_title=$val['prize_title2'];
                        if($k==0) {
                            $symbol="<br/><br/>";
                        } else {
                            $symbol="<br/>";
                        }
                        $send_toys.=$symbol."【礼品】".$tmp_bus_title;
                    }
                }
                $returnpickInfo=$this->returnpickInfo("o.status in (10) and o.state='0' and o.user_id=$tmp_user_id ","<br/>");
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                $res[]=array(
                    'user_id'=>$tmp_user_id,
                    'user_info'=>$user_info,
                    'send_toys'=>$send_toys,
                    'pick_toys'=>$pick_toys,
                    'remark'=>$remark,
                    'new_create_time'=>$tmp_new_create_time
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //地图
    public function map() {
        $this->display();
    }
    //玩具出库状态
    public function getoutbusinessinfo(){
        $start_time=I('start_time');
        $end_time=I('end_time');
        $str_card_info=$this->getCardInfo();
        if($start_time) {
            $today=$start_time;
        } else {
            $today=date("Y-m-d 00:00:00",strtotime("-6 day"));
        }
        $this->assign("start_time",$today);
        if($end_time) {
            $moring=$end_time;
        } else {
            $moring=date("Y-m-d 00:00:00",strtotime("+1 day"));
        }
        $this->assign("end_time",$moring);
        $show_time=$today."到".$moring;
        $model = new Model();
        $begintime = strtotime($today);
        $endtime = strtotime($moring);
        for($start = $begintime; $start <$endtime; $start += 24 * 3600) {
            $show_start=date("Y-m-d H:i:s", $start);
            $show_start2=date("Y-m-d H:i:s", strtotime("+1 day",$start) );
            //玩具种类
            $toys_type_list = M('toys_business_listing')
                ->field('business_id')
                ->group("business_id")
                ->where("state='0' and create_time>'$show_start' and create_time<'$show_start2' and new_old='0' ")
                ->select();
            $toys_type_count=count($toys_type_list);

            //玩具个数
            $toys_number_list = M('toys_business_listing')
                ->field('id')
                ->where("state='0' and create_time>'$show_start' and create_time<'$show_start2' and new_old='0' ")
                ->select();
            $toys_number_count=count($toys_number_list);
            $toys_count=$toys_type_count."/".$toys_number_count;

            //入库
            $in_list = M('toys_order')
                ->field('id')
                ->where("state='0' and status=11 and post_create_time>'$show_start' and post_create_time<'$show_start2' and is_prize in (0,3) ")
                ->select();
            $in_count=count($in_list);
            $in_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and status=11 and post_create_time>'$show_start' and post_create_time<'$show_start2' and is_prize in (0,3) ")
                ->select();
            $in_user_count=count($in_user_list);
            $in_count_info=$in_count."/".$in_user_count;

            //取消送货
            $cancel_list = M('toys_order')
                ->field('id')
                ->where("state='0' and post_create_time>'$show_start' and post_create_time<'$show_start2' and is_prize in (0,3) and cancel_reason in (1,2,3) and status in (2,17,10) ")
                ->select();
            $cancel_count=count($cancel_list);
            $cancel_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and post_create_time>'$show_start' and post_create_time<'$show_start2' and is_prize in (0,3) and cancel_reason in (1,2,3) and status in (2,17,10) ")
                ->select();
            $cancel_user_count=count($cancel_user_list);
            $cancel_count_info=$cancel_count."/".$cancel_user_count;


            //送货中[送]
            $ordering_list =$model-> table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where("o.state='0' and o.status=5 and o.is_prize in (0,3) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='5' and i.state='0' ")
                ->select();
            $ordering_count=count($ordering_list);
            $ordering_user_list =$model->table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where("o.state='0' and o.status=5 and o.is_prize in (0,3) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='5' and i.state='0' ")
                ->select();
            $ordering_user_list_count=count($ordering_user_list);

            //送货中[取]
            $back_postid_list =$model->table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where("o.state='0' and o.status=10 and o.is_prize in (0,3) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='7' and i.state='0' and i.postman_id>0 ")
                ->select();
            $back_postid_count=count($back_postid_list);
            $back_postid_user_list = $model->table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where("o.state='0' and o.status=10 and o.is_prize in (0,3) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='7' and i.state='0' and i.postman_id>0 ")
                ->select();
            $back_postid_user_count=count($back_postid_user_list);

            $ordering_count_info=$ordering_count."—".$ordering_user_list_count."/".$back_postid_count."—".$back_postid_user_count;

            //玩乐中
            $fun_list = $model->table('baby_toys_order as o')
                ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where("o.state='0' and o.status=6 and o.is_prize in (0,3) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='8' and i.state='0' ")
                ->select();
            $fun_count=count($fun_list);
            $fun_user_list = $model->table('baby_toys_order as o')
                ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where("o.state='0' and o.status=6 and o.is_prize in (0,3) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='8' and i.state='0' ")
                ->select();
            $fun_user_list_count=count($fun_user_list);
            $fun_count_info=$fun_count."/".$fun_user_list_count;

            //待派单[送]
            $send_list = M('toys_order')
                ->field('id')
                ->where("state='0' and status in (2,17) and business_id not in ($str_card_info) and toys_number<>'' and is_prize in (0,3) ")
                ->select();
            $send_count=count($send_list);
            $send_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and status in (2,17) and business_id not in ($str_card_info) and toys_number<>'' and is_prize in (0,3) ")
                ->select();
            $send_user_list_count=count($send_user_list);
            $send_count_info=$send_count."/".$send_user_list_count;


            //备货中
            $prepare_list = M('toys_order')
                ->field('id')
                ->where("state='0' and status=2 and business_id not in ($str_card_info) and ( (toys_number is null) or (toys_number='') ) and is_prize in (0,3) and create_time>'$show_start' and create_time<'$show_start2' ")
                ->select();
            $prepare_count=count($prepare_list);
            $prepare_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and status=2 and business_id not in ($str_card_info) and ( (toys_number is null) or (toys_number='') ) and is_prize in (0,3) and create_time>'$show_start' and create_time<'$show_start2' ")
                ->select();
            $prepare_user_list_count=count($prepare_user_list);
            $prepare_count_info=$prepare_count."/".$prepare_user_list_count;


            $res[]=array(
                'toys_count'=>$toys_count,//新玩具上架量（玩具种类/玩具个数）
                'in_count_info'=>$in_count_info,//入库量/用户量
                'cancel_count_info'=>$cancel_count_info,//取消送货量/用户量
                'fun_count_info'=>$fun_count_info,//玩乐中/用户量
                'ordering_count_info'=>$ordering_count_info,//送货中[送/取]
                'prepare_count_info'=>$prepare_count_info,//准备中数量/用户量
                'send_count_info'=>$send_count_info,//待出库数量/用户量
                'start_time'=>$show_start,
                'end_time'=>$show_start2,
                'times'=>$show_start."<br/>".$show_start2
            );
        }
        $this->assign("show_time",$show_time);
        $this->assign("res",$res);
        $this->display();
    }
    //玩具数量
    public function getpostmanlist(){
        $today=I('start_time');
        $moring=I('end_time');
        $order_status=I('order_status');//1:送货中数量/待取回数量（配送员接单）、2取消送货量、3入库量、4新玩具上架量
        $str_card_info=$this->getCardInfo();
        $show_time=$today."到".$moring;
        $model = new Model();
        if($order_status==1) {//送货中[送和取]
            $where_condition="o.state='0' and o.is_prize in (0,3) and i.post_create_time>'$today' and i.post_create_time<'$moring' and i.state='0' and i.postman_id>0 and ((i.status='5' and o.status=5) or (i.status='7' and o.status=10 ) )";
            $resultRes =$model-> table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                    ->field('count(o.id) as count,i.postman_id')
                    ->group("i.postman_id")
                    ->where($where_condition)
                    ->select();
            $status_name="配送员送和取";
        } elseif($order_status==2) {//取消送货量
            $where_condition="o.state='0' and o.post_create_time>'$today' and o.post_create_time<'$moring' and o.is_prize in (0,3) and o.cancel_reason in (1,2,3) and ((i.status='2' and o.status in (2,17) ) or (i.status='7' and o.status=10 ) ) ";
            $resultRes =$model-> table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                    ->field('count(o.id) as count,i.postman_id')
                    ->group("i.postman_id")
                    ->where($where_condition)
                    ->select();
            $status_name="取消送货量";
        } elseif($order_status==3) {//入库量
            $where_condition="o.state='0' and o.post_create_time>'$today' and o.post_create_time<'$moring' and o.is_prize in (0,3) and i.status='11' and o.status=11 ";
            $resultRes =$model-> table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                    ->field('count(o.id) as count,i.postman_id')
                    ->group("i.postman_id")
                    ->where($where_condition)
                    ->select();
            $status_name="入库量";
        } elseif($order_status==4) {//新玩具上架量
            $status_name="新玩具上架量";
            $toys_condition="state='0' and create_time>'$today' and create_time<'$moring' and new_old='0' ";
            //玩具种类
            $toys_type_list = M('toys_business_listing')
                    ->field('business_id')
                    ->group("business_id")
                    ->where($toys_condition)
                    ->select();

        }
        $res=array();
        if($resultRes) {//订单相关
            foreach ($resultRes as $key => $value) {
                $order_count=$value['count'];
                $postman_id=$value['postman_id'];
                if($postman_id>0) {
                    $getuserinfo=M("user")->field('user_name')->where("id=$postman_id")->find();
                    $real_name=$getuserinfo['user_name']?$getuserinfo['user_name']:"";
                } else {
                    $real_name="";
                }
                $userRes =$model-> table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                    ->field('o.user_id')
                    ->group("o.user_id")
                    ->where($where_condition." and i.postman_id=$postman_id ")
                    ->select();
                $user_count=count($userRes);
                $res[]=array(
                    'postman_id'=>$postman_id,
                    'order_count'=>$order_count,
                    'real_name'=>$real_name,
                    'user_count'=>$user_count,
                    'show_time'=>$show_time
                );
            }
        }
        if($toys_type_list) {//玩具相关
            foreach ($toys_type_list as $key => $value) {
                $business_id=$value['business_id'];
                $toys_count = M('toys_business_listing')
                    ->where($toys_condition." and business_id=$business_id ")
                    ->count();
                $toys_title_res = M('toys_business')
                    ->field('business_title')
                    ->where(" id=$business_id ")
                    ->find();
                $business_title=$toys_title_res['business_title'];
                $toys_res[]=array(
                    'business_id'=>$business_id,
                    'business_title'=>$business_title,
                    'toys_count'=>$toys_count,
                    'show_time'=>$show_time
                );
            }
        }
        $this->assign("start_time",$today);
        $this->assign("end_time",$moring);
        $this->assign("order_status",$order_status);
        $this->assign("status_name",$status_name);
        $this->assign("res",$res);
        $this->assign("toys_res",$toys_res);
        $this->display();
    }

}
?>
