<?php
class OthersAction extends Action{

    /**
     * 验证是否已设置session
     */
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
    //卡
    public function getCardInfo() {
        $str_card="3281,3283,3299,3301,418,420,422,424,548,649,652,655,1040,1041,1137,1139,1253,1255,1336,1338,1340,1342,1416,1625,1627,1629,1631,2253,2255,2633,2635,2853,2902,2907,2914,2916,3026,3028,3074,3088,3140,3164,3303,3305,3346,3307,3166,3168,3189,3201,3223,3231,3233,3242";
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
            ->where("  state='0' and business_id='$business_id' and is_use in ('0','5') and out_state='0' ") //new_old='0' and
            ->count();
        //出库新增编号
        $use1_count_new=$model->table('baby_toys_business_listing')
            ->where(" new_old='2' and state='0' and business_id='$business_id'  ") //and is_use in ('0','5') and out_state='0'
            ->count();
        //没选编号订单
        $use2_count=$model->table('baby_toys_order')
            ->where("state='0' and business_id=$business_id and (toys_number='' or toys_number is null ) and status in (1,2) and is_prize in (0,3,6) ")
            ->count();
        //可用库存
        $use_count = $use1_count - $use2_count - $use1_count_new;
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
                    ->where("o.combined_order_id='$combined_order_id' and o.is_prize='0' and o.user_id=$user_id and o.state='0' and o.is_prize='0' and ((b.battery_number5>0) or (b.battery_number7>0)) and ( (b.battery_number5>0) or (b.battery_number7>0) ) and ( (b.business_brand like '%汇乐%') or (b.business_brand like '%谷雨%') ) ")
                    ->field("SUM(b.battery_number5) as battery_number5,SUM(b.battery_number7) as battery_number7")
                    ->find();
        $special5=$getBrandRes['battery_number5'];
        $special7=$getBrandRes['battery_number7'];
        if($battery_brand==1) {
            $getBrandRes2=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where("o.combined_order_id='$combined_order_id' and o.is_prize='0' and o.user_id=$user_id and o.state='0' and o.is_prize='0' and ((b.battery_number5>0) or (b.battery_number7>0)) and ( (b.battery_number5>0) or (b.battery_number7>0) ) ")
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
                    ->where("o.combined_order_id='$combined_order_id' and o.is_prize='0' and o.user_id=$user_id and o.state='0' and o.is_prize='0' and ((b.battery_number1>0) or (b.battery_number2>0) or (b.battery_number3>0) or (b.battery_number4>0) or (b.battery_number5>0) or (b.battery_number6>0) or (b.battery_number7>0) or (b.battery_number8>0) )")
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
                if($battery_number5>0 && ( $show_create_time<'2017-10-27 18:20:00' || (empty($special5) && $show_create_time>'2017-10-27 18:20:00' ) ) ) {//&& empty($special5)
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
                if($battery_number7>0 && ( $show_create_time<'2017-10-27 18:20:00' || (empty($special7) && $show_create_time>'2017-10-27 18:20:00' ) ) ) {//&& empty($special7)
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->getActiveSheet()->getStyle('A'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('C'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('D'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN); 
            $objPHPExcel->getActiveSheet()->getStyle('F'.'2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
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
                
                              
                if($j==2 || $j==3) {
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
                    ->field('o.business_id,b.business_title,b.way,o.remark,o.card_id,o.toys_number,b.category_ids,o.is_battery,o.battery_price,o.battery_brand,b.battery_number1,b.battery_number2,b.battery_number3,b.battery_number4,b.battery_number5,b.battery_number6,b.battery_number7,b.battery_number8,o.is_prize,o.is_size')
                    ->select();
        $remark="";
        if($getSendRes) {
            foreach ($getSendRes as $k => $val) {
                $is_size=$val['is_size'];
                $is_prize=$val['is_prize'];
                $tmp_bus_id=$val['business_id'];
                $tmp_bus_title=$val['business_title'];
                $tmp_remark=$val['remark'];
                $tmp_card_id=$val['card_id'];
                $tmp_toys_number=$val['toys_number'];
                $tmp_way=$val['way'];
                /*$tmp_category_ids=$val['category_ids'];
                $tmp_is_battery=$val['is_battery'];
                $battery_price = $val['battery_price'];//电池价格
                if($battery_price=='0.00'){
                    $battery_price=0;
                }
                $battery_brand = $val['battery_brand'];//电池品品牌：1华泰、2南孚
                $battery_number1 = $val['battery_number1'];//  1号电池
                $battery_number2 = $val['battery_number2'];//  2号电池
                $battery_number3 = $val['battery_number3'];//  3号电池
                $battery_number4 = $val['battery_number4'];//  4号电池
                $battery_number5 = $val['battery_number5'];//  5号电池
                $battery_number6 = $val['battery_number6'];//  6号电池
                $battery_number7 = $val['battery_number7'];//  7号电池
                $battery_number8 = $val['battery_number8'];//  纽扣电池
                $battery_number = "";
                if($battery_number1){
                    $battery_number .= $battery_number1.";";
                }
                if($battery_number2){
                    $battery_number .= $battery_number2.";";
                }
                if($battery_number3){
                    $battery_number .= $battery_number3.";";
                }
                if($battery_number4){
                    $battery_number .= $battery_number4.";";
                }
                if($battery_number5){
                    $battery_number .= $battery_number5.";";
                }
                if($battery_number6){
                    $battery_number .= $battery_number6.";";
                }
                if($battery_number7){
                    $battery_number .= $battery_number7.";";
                }
                if($battery_number8){
                    $battery_number .= $battery_number8.";";
                }
                if($battery_brand==1){
                    $battery_brand="华太电池";
                }elseif($battery_brand==2){
                    $battery_brand = "南孚电池";
                }elseif($battery_brand==3){
                    $battery_brand = "双鹿电池";
                }else{
                    $battery_brand = "无品牌";
                }

                $battery_info = "";

                if(!empty($battery_price) && $battery_number){
                    $battery_info = "电池总计：￥".$battery_price.";".$battery_brand.";详情：".$battery_number ;
                }
                if($tmp_is_battery==1) {
                    $is_battery_title="[带电池]";
                } else {
                    $is_battery_title="";
                }
                $toys_size="0";
                if($tmp_category_ids) {
                    $arr_category_ids=explode(",", $tmp_category_ids);
                    if(in_array("106",$arr_category_ids) ) {
                        $toys_size="1";
                    }
                }*/
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
                if(($is_prize==0)||($is_prize==3)) {
                    $send_toys.=$symbol.$toys_size_name.$tmp_bus_title.$symbol.$battery_info;
                } else {
                    $send_toys.=$symbol.$battery_info;
                }
                
            }
            $MyRes=array('send_toys'=>$send_toys,'remark'=>$remark);
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
                    ->field('o.business_id,b.business_title,o.toys_number,b.category_ids,o.is_prize,o.id')
                    ->select();
        if($getPickRes) {
            foreach ($getPickRes as $k => $val) {
                $order_id = $val['id'];
                $tmp_bus_id=$val['business_id'];
                $tmp_bus_title=$val['business_title'];
                $tmp_toys_number=$val['toys_number'];
                $tmp_category_ids=$val['category_ids'];
                $is_prize = $val['is_prize'];
                $toys_size="0";
                if($tmp_category_ids) {
                    $arr_category_ids=explode(",", $tmp_category_ids);
                    if(in_array("106",$arr_category_ids) ) {
                        $toys_size="1";
                    }
                }
                if($is_prize==3){
                    $toys_size = 2;
                }
                if($toys_size==1) {
                    $toys_size_name="【小】";
                }elseif($toys_size==2){
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
                if($is_prize==5){
                    $getListpayRes= $model->table('baby_toys_compensation')
                        ->where(" state='0' and order_id='$order_id' and root_img_id=0  ")
                        ->field('business_title,business_des')
                        ->find();
                    $pick_toys_pay = $getListpayRes['business_title']."【零件丢失】".$symbol.$getListpayRes['business_des']."【赔付原因】";
                    $pick_toys.=$symbol.$pick_toys_pay;
                }else{
                    $pick_toys.=$symbol.$toys_size_name.$tmp_bus_title;
                }
            }
            $MyRes=array('pick_toys'=>$pick_toys);
        }
        return $MyRes;
    }
    
    public function index(){
        $this->checkSession();
        $this->display();
    }
    //老师列表
	public function teacherlist(){
		$this->checkSession();    
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['state'] ='0';
        $where2['user_role'] ='2';
        $model = new Model();


        //满足条件的总记录数
        $count  = $model->table('baby_user')
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_user')
                    ->where($where2)
                    ->field('id,nick_name,email,last_modify_time')
                    ->order('last_modify_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
	}
    //修改老师角色
    public function update_user_role(){
        $user_id=I("id");
        if($user_id) {
            $userList = M('user')->where('id='.$user_id)->find();
        }
        
        $this->assign('userList',$userList);

        
        $this->display('publicuserrole');
    }
    
    //添加老师角色
    public function publicuserrole(){           
        $this->display('searchuser');
    }
    //搜索用户信息
    public function searchuser(){   
        $email=trim(I("serach_email")); 
        $user_id=trim(I("user_id"));
        
        if($email) {
            $where_condition['email'] =$email;
        } 
        if($user_id) {
            $where_condition['id'] =$user_id;
        }
        $userList = M('user')->where($where_condition)->find();
        $this->assign('userList',$userList);         
        $this->display('publicuserrole');
    }

    //入驻合作列表
    public function cooperationlist(){
        $this->checkSession();    
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['state'] ='0';
        $model = new Model();


        //满足条件的总记录数
        $count  = $model->table('baby_cooperation')
                        ->where($where2)
                        ->order('post_create_time desc')
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_cooperation')
                    ->where($where2)
                    ->order('post_create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    public function operator_cooperation(){
        
        $coopid = I("id");
        $album = M('cooperation');
        //$where['id'] = $imgid;
        $where['id'] = array('in',$coopid);
        $data['state'] = '1' ;
        $data['post_create_time'] = date("Y-m-d H:i:s");
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/cooperationlist.html");
    }
    public function update_cooperation_info() {
        $coopid = I("id");
        $model = new Model();
        if($coopid>0) {
            $businessList=$model->table('baby_cooperation as c')
                ->field('c.*,u.nick_name,u.email,u.mobile as user_mobile')
                ->where("c.id=".$coopid)
                ->join(" left join baby_user as u on c.user_id=u.id")
                ->select();
            $this->assign("businessList",$businessList[0]);
        }
        $this->display("updatecooperation");
    }
    //运营商家列表
    public function virtualbusinesslist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state']='0';
        $business = M('business2');
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2['id'] =$business_id;
            $this->assign('business_id',$business_id);
        }
        $search_business_title=trim(I("search_business_title"));
        if($search_business_title) {
            $where2['business_title'] =array('like','%'.$search_business_title.'%');;
            $this->assign('search_business_title',$search_business_title);
        }

        //满足条件的总记录数
        $count  = $business->where($where2)->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $business->where($where2)
                            ->field('id,create_time,business_title,business_contact')
                            ->order('create_time desc')
                            ->limit($page->firstRow,$page->listRows)
                            ->select();        
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $business_title = $value['business_title'];
            $business_contact = $value['business_contact'];
            $create_time = $value['create_time'];
            $res[]=array(
                'id'=>$id,
                'business_title'=>$business_title,
                'business_contact'=>$business_contact,
                'create_time'=>$create_time
            );
        }
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();    
    }
    //下订单前寻找虚拟商家
    public function searchvirtualbusiness(){
        $this->display();
    }
    //搜索虚拟商家信息
    public function searchbusinessinfo(){   
        $business_id=trim(I("business_id"));
        $where_condition['state']='2';
        if($business_id) {
            $where_condition['id'] =$business_id;
        }
        $businessList = M('business')->where($where_condition)->find();
        $this->assign('businessList',$businessList);         
        $this->display('publicorder');
    }
    //运营订单列表
    public function virtualorderlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['o.state'] ='0';
        $model = new Model();
        $order_id=trim(I("order_id"));
        if($order_id) {
            $where2['o.id'] =$order_id;
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2['o.order_num'] =array('like','%'.$order_num.'%');
            $this->assign('order_num',$order_num);
        }
        $order_status=trim(I("order_status"));
        if($order_status) {
            $where2['o.status'] =$order_status;
            $this->assign('order_status',$order_status);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2['o.business_id'] =$business_id;
            $this->assign('business_id',$business_id);
        }
        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2['o.post_create_time'] =array(array('EGT',$start_time),array('ELT',$end_time),'AND');
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2['o.post_create_time'] =array('EGT',$start_time);
            $this->assign('start_time',$start_time);
        }
        //满足条件的总记录数
        $count  = $model->table('baby_order2 as o')
                        ->join(" left join baby_business2 as b on o.business_id=b.id")
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->join(" left join baby_user as s on o.seller_id=s.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_order2 as o')
                    ->join(" left join baby_business2 as b on o.business_id=b.id")
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->join(" left join baby_user as s on o.seller_id=s.id")
                    ->where($where2)
                    ->field('o.id,o.order_role,o.baby_name,o.babys_idol_id,o.user_id,u.nick_name,u.email,o.seller_id,s.nick_name as seller_name,s.email as seller_email,b.business_contact,o.payment,o.status,o.price,o.pay_price,o.business_id,b.business_title,o.order_num,o.post_create_time,o.is_communication,b.business_location,o.trade_no')
                    ->order('o.post_create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        foreach($res as $key=>$value) {
            $order_role=$value['order_role'];
            if($order_role==1) {
                $res[$key]['business_id']=$value['babys_idol_id'];
                $res[$key]['business_title']=$value['baby_name'];
            }

        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    //半径首页
    public function radiushomelist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        //满足条件的总记录数
        $count  = M('radius_home')
                ->where($where2)
                ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $list= M('radius_home')
                ->where($where2)
                ->limit($page->firstRow,$page->listRows)
                ->order("id desc")
                ->select();
        $this->assign("res",$list);
        $this->assign('page',$show);//赋值分页输出
        $this->display('radiushomelist');    
    }
    //添加/编辑半径首页页面
    public function radiushome(){
        $special_id=I("id");
        if($special_id) {
            $specialList = M('radius_home')->where('id='.$special_id)->find();
            $img1=$specialList['img1'];
            if($img1) {
                $specialList['img1']="http://api.meimei.yihaoss.top/".$img1;
            }
            $img2=$specialList['img2'];
            if($img2) {
                $specialList['img2']="http://api.meimei.yihaoss.top/".$img2;
            }
            $img3=$specialList['img3'];
            if($img3) {
                $specialList['img3']="http://api.meimei.yihaoss.top/".$img3;
            }
            $this->assign('specialList',$specialList);
        }
        //赋值数据集*/
        $this->display('radiushome');
    }
    //删除半径首页某一个
    public function operator_radius_home(){        
        $id = $_GET['id'];
        $album = M('radius_home');
        $where['id'] = array('in',$id);
        $result = $album->where($where)->delete();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/radiushomelist.html");
    }
    public function getType(){
        $cid= $_GET['cid'];
        if($cid==1) {
            $typelist=array(
                array('id'=>'2','title'=>'某一个专题'),
                array('id'=>'5','title'=>'某一个专题详情'),
                array('id'=>'3','title'=>'某一个秀秀详情'),
                array('id'=>'1','title'=>'专题列表'),
                array('id'=>'4','title'=>'秀秀列表'),
             );
        } elseif($cid==2) {
            $typelist=array(
                array('id'=>'22','title'=>'某一个帖子详情'),
                array('id'=>'21','title'=>'话题列表')
             );
        } elseif($cid==3) {
            $typelist=array(
                array('id'=>'42','title'=>'某一个商家详情'),
                /*array('id'=>'43','title'=>'群中某一个商家'),*/
                array('id'=>'41','title'=>'商家列表')
             );
        } elseif($cid==4) {
            $typelist=array(
                array('id'=>'52','title'=>'某一个值得买详情'),
                array('id'=>'51','title'=>'值得买列表')
             );
        } elseif($cid==5) {
            $typelist=array(
                array('id'=>'62','title'=>'某一个群详情'),
                array('id'=>'61','title'=>'群列表')
             );
        }
        if($typelist) {
            echo json_encode($typelist);
        }        
    }
    //活动分配红包列表
    public function activitypacketlist(){
        import("ORG.Util.Page");
        //先查相册的数据库，
        $user_album = M('packet');
        $now_time=date("Y-m-d");

        //删除低于此时间的红包
        $update_data['state']='1';
        $update_data['is_expiry']='1';
        $user_album->where("state='0' and is_grab_use='0' and activity_business_id>0 and expiry<'$now_time' ")
                    ->data($update_data)
                    ->save();
        
        $where2['state'] ='0';//图片状态正常
        $where2['is_grab_use'] ='0';
        $where2['activity_business_id']=array('GT',0);
        $count  =$user_album ->where($where2)->count();
        $page = new Page($count,50);
        $show = $page->show();
        
        $res=array();   
        $datas = $user_album
                      ->where($where2)
                      ->order('id desc')
                      ->limit($page->firstRow,$page->listRows)
                      ->select();       
        foreach ($datas as $key => $data){            
            $img_id = $data['id'];
            $grab_user_id = intval($data['grab_user_id']);
            if($grab_user_id>0) {
                $condition['id'] = $grab_user_id;
                $grab_users = M('user')->where($condition)->find();
                $grab_nick_name = $grab_users['nick_name'];
            } else {
                $grab_nick_name="";
            }
            $activity_business_id = intval($data['activity_business_id']);
            if($activity_business_id>0) {
                $act_condition['id'] = $activity_business_id;
                $business_info = M('business_new')->where($act_condition)->find();
                $business_title = $business_info['business_title'];
            } else {
                $business_title="";
            }
                 
            $res[]=array(
                'id' =>$img_id,
                'packet_price'=>$data['packet_price'],
                'expiry'=>$data['expiry'],
                'grab_user_id'=>$grab_user_id,
                'grab_nick_name'=>$grab_nick_name,
                'activity_business_id'=>$activity_business_id,
                'business_title'=>$business_title,
                'activity_business_package'=>$data['activity_business_package']
            );
        }
        $this->assign("res",$res);
        $this->assign('page',$show);
        $this->display();    
    }
    //活动红包删除
    public function operator_activitypacket(){        
        $imgid = $_GET['id'];
        $album = M('packet');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/activitypacketlist.html");
    }
    //添加活动红包页面
    public function publicactivitypacket(){
        $this->display();    
    }
    //添加视频页面
    public function video(){
        $this->display();
    }
    //新版商家列表
    public function newbusinesslist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        //$where2='state=0';
        $where2['state']='0';
        $business = M('business_new');

        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2['id'] =$business_id;
            $this->assign('business_id',$business_id);
        }
        $search_business_title=trim(I("search_business_title"));
        if($search_business_title) {
            $where2['business_title'] =array('like','%'.$search_business_title.'%');;
            $this->assign('search_business_title',$search_business_title);
        }

        //满足条件的总记录数
        $count  = $business->where($where2)->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $business->where($where2)
                            ->field('id,create_time,business_title,business_contact')
                            ->order('create_time desc')
                            ->limit($page->firstRow,$page->listRows)
                            ->select();     
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $business_title = $value['business_title'];
            $business_contact = $value['business_contact'];
            $create_time = $value['create_time'];
            $res[]=array(
                'id'=>$id,
                'business_title'=>$business_title,
                'business_contact'=>$business_contact,
                'create_time'=>$create_time
            );
        }
        $this->assign("res",$res);
        $this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display('newbusinesslist');  
    }
    /*删除商家*/
    public function  del_business(){
        $id = $_GET['id'];        
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $album= M('business_new');
        $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/newbusinesslist.html");        
    }
    public function update_business(){
        $business_id=I("id");
        if($business_id) {
            $businessList = M('business_new')->where('id='.$business_id)->find();           
        }
        $cover=$businessList['cover'];
        if($cover) {
            $businessList['cover']="http://api.meimei.yihaoss.top/".$cover;
        }
        $business_pics=$businessList['business_pics'];
        $array_pics = explode(";",$business_pics);
        if($array_pics[0]) {
            $businessList['business_pic1']="http://api.meimei.yihaoss.top/".$array_pics[0];
        }
        if($array_pics[1]) {
            $businessList['business_pic2']="http://api.meimei.yihaoss.top/".$array_pics[1];
        }
        if($array_pics[2]) {
            $businessList['business_pic3']="http://api.meimei.yihaoss.top/".$array_pics[2];
        }
        if($array_pics[3]) {
            $businessList['business_pic4']="http://api.meimei.yihaoss.top/".$array_pics[3];
        }
        if($array_pics[4]) {
            $businessList['business_pic5']="http://api.meimei.yihaoss.top/".$array_pics[4];
        }
        if($array_pics[5]) {
            $businessList['business_pic6']="http://api.meimei.yihaoss.top/".$array_pics[5];
        }
        if($array_pics[6]) {
            $businessList['business_pic7']="http://api.meimei.yihaoss.top/".$array_pics[6];
        }
        if($array_pics[7]) {
            $businessList['business_pic8']="http://api.meimei.yihaoss.top/".$array_pics[7];
        }

        $province=$businessList['province'];
        $city=$businessList['city'];
        $county=$businessList['county'];
        $street=$businessList['street'];
        $street=$businessList['street'];
        $business_location=$businessList['business_location'];
        if(preg_match("/——/",$business_location)) {
            $temp_location=explode('——', $business_location);
            $businessList['business_location']=$temp_location[1];
        }
        if($province>0) {
            /*市*/
            $where_condition['province_id'] = $province;
            $where_condition['city_id'] = array('gt','0');
            $where_condition['county_id'] = '0';
            $where_condition['street_id'] = '0';
            $cityInfo = M('citys') ->field('city_id,name')
                ->where($where_condition)
                ->select();
            $this->assign('cityInfo',$cityInfo);
        }
        if($city>0) {
            $city_temp=$city-$province*100;
            $businessList['city']=$city_temp;
            /*县*/
            $county_where['province_id'] = $province;
            $county_where['city_id'] = $city_temp;
            $county_where['county_id'] = array('gt','0');
            $county_where['street_id'] = '0';
            $countyList = M('citys') ->field('county_id,name')
                ->where($county_where)
                ->select();
            $this->assign('countyList',$countyList);
        }
        if($county>0) {
            $temp_county=$county-$province*10000-$city_temp*100;
            $businessList['county']=$temp_county;
            /*乡镇*/
            $street_where['province_id'] = $province;
            $street_where['city_id'] = $city-$province*100;
            $street_where['county_id'] = $temp_county;
            $street_where['street_id'] = array('gt','0');
            $streetList = M('citys') ->field('street_id,name')
                ->where($street_where)
                ->select();
            $this->assign('streetList',$streetList);
        }
        if($street>0) {
            $temp_street=$street-$province*10000000-$city_temp*100000-$temp_county*1000;
            $businessList['street']=$temp_street;
        }
        $this->assign('businessList',$businessList);

        /*省*/
        $city_where['city_id'] = '0';
        $city_where['county_id'] = '0';
        $cityList = M('citys') ->field('province_id,name')
            ->where($city_where)
            ->select();
        $this->assign('cityList',$cityList);

        $userList = M('user')->where('user_role="1"')->select();
        $this->assign('userList',$userList);


        $regionA = M('citys_regions') 
            ->field('county as city_name,county_id as id')
            ->group("county")
            ->select();

        $this->assign('regionA',$regionA);

        if($businessList['region_county']>0) {
            $regionB = M('citys_regions') 
            ->field('region as city_name,id')
            ->where("county_id=".$businessList['region_county'])
            ->select();
        }
        $this->assign('regionB',$regionB);
        $this->display('npublicbusiness');
    }
    public function npublicbusiness(){
        $city_where['city_id'] = '0';
        $city_where['county_id'] = '0';
        $cityList = M('citys') ->field('province_id,name')
            ->where($city_where)
            ->select();
        $this->assign('cityList',$cityList);
        $userList = M('user')->where('user_role="1"')->select();
        $this->assign('userList',$userList);
        $regionA = M('citys_regions') 
            ->field('county as city_name,county_id as id')
            ->group("county")
            ->select();

        $this->assign('regionA',$regionA);
        
        $this->display();
    }
    public function businessPackageList(){
        $business_id=I("id");        
        $businessInfo = M('business_new')->where("id=".$business_id)->find();
        $package_where['business_id'] = $business_id;
        $package_where['state'] = '0';
        $packageList = M('business_package')->where($package_where)
            ->select();
        foreach ($packageList as $key => $data){
            $packageList[$key]['business_title']=$businessInfo['business_title'];
        }
        $this->assign('business_id',$business_id);
        $this->assign('packageList',$packageList);        
        $this->display();
    }
    //删除商家套餐
    public function  del_business_package(){
        $business_id=I("business_id");
        $id = $_GET['id'];        
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $album= M('business_package');
        $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/businessPackageList?id=$business_id");        
    }
    public function publicbusinessPackage(){ 
        $business_id=I("business_id");
        if($business_id) {
            $businessList = M('business_new')->where("id=".$business_id)->find();
            $this->assign("businessList",$businessList);
        }         
        $this->display();
    }
    public function update_business_package() {
        $package_id=I("id");
        if($package_id) {
            $businessList = M('business_package')->where("id=".$package_id)->find();
            $this->assign("businessList",$businessList);
        }
        $this->display();
    }
    public function getListinglist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $img_title = I('img_title'); 
        $img_description = I('img_description');  
        $img_id = intval(I('img_id'));
        $where_condition="";      
        if(!empty($img_description)) {
            $where_condition.=" and img_description like '%$img_description%' ";
            $this->assign('img_description',$img_description);
        }
        if(!empty($img_title)) {
            $where_condition.=" and img_title like '%$img_title%' ";
            $this->assign('img_title',$img_title);
        }
        if(!empty($img_id)) {
            $where_condition.=" and id=$img_id ";
            $this->assign('img_id',$img_id);
        }
        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            //$where2['create_time'] =array(array('EGT',$start_time),array('ELT',$end_time),'AND');
            $where_condition.=" and create_time>='$start_time' and create_time<='$end_time' ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where_condition.=" and create_time>='$start_time'  ";
            //$where2['create_time'] =array('EGT',$start_time);
            $this->assign('start_time',$start_time);
        }

        //先查相册的数据库，
        $post_img = M('listing');
        $model = new Model();
        //满足条件的总记录数
        $count  = $post_img->where("state='0' and root_img_id is null ".$where_condition)->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $post_img
                ->where("state='0' and root_img_id is null ".$where_condition)
                ->field('id,user_id,img_description,is_recommend,img,post_class,post_cate_id,link_state,link_nick_name,label,create_time,group_id,is_video,img_title,jump_id')
                ->order('create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select(); 
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $tmpImg = $value['img']; 
            $label = $value['label'];
            $is_video = $value['is_video'];
            if($label) {
                $label_state=1;
            } else {
                $label_state=0;
            } 
            $create_time=$value['create_time']; 
            $img_title=$value['img_title'];        
            $desc = $value['img_description'];
            $user_id = $value['user_id'];
            $link_state = $value['link_state'];
            if($link_state==1) {
                $nick_name = $value['link_nick_name'];
            } else {
                $userInfo=M("user")->where("id=$user_id")->field('nick_name')->find();
                $nick_name = $userInfo['nick_name'];
            }
            $is_recommend = $value['is_recommend'];
            $jump_id = $value['jump_id'];
            $group_id = intval($value['group_id']);
            $post_class = $value['post_class'];
            $post_cate_id = $value['post_cate_id'];
            $Imgs=explode(';',$tmpImg);
            if(count($Imgs)>1){
                $is_theme = 1;
            }else{
                $is_theme =0;
            }
            $imgUrl=array();    
            if(is_array($Imgs)){
            foreach ($Imgs as $key=>$tmpImg){
                    //$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
                    $imgUrl[]="http://api.meimei.yihaoss.top/".$tmpImg;
                }
            }
                        
            $res[]=array(
                'id'=>$id,
                'nick_name'=>$nick_name,
                'img_description'=>$desc,
                'imgUrl'=>$imgUrl,
                'is_theme'=>$is_theme,
                'user_id'=>$user_id,
                'is_recommend'=>$is_recommend,
                'post_class'=>$post_class,
                'post_cate_id'=>$post_cate_id,
                'label_state'=>$label_state,
                'create_time'=>$create_time,
                'group_id'=>$group_id,
                'is_video'=>$is_video,
                'img_title'=>$img_title,
                'jump_id'=>$jump_id       
            );
        }
        
        $this->assign("res",$res);
        $this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    
    
    //删除话题列表
    public function operator_listingstate(){        
        $imgid = $_GET['imgid'];
        $album = M('listing');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getListinglist.html");
    }
    //帖子【和秀秀合并后】列表
    public function postreplylist(){
        $this->checkSession();
        $id = $_GET['id'];
        import("ORG.Util.Page");
        //先查相册的数据库，
        $post_img = M('listing');
        $where2['state'] ='0';
        $model = new Model();

        //满足条件的总记录数
        $count  = $post_img
                        ->where("state='0' and root_img_id={$id}  ")
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出



        $list = $post_img
                ->where("state='0' and (root_img_id={$id} or id={$id})  ")
                ->field('id,user_id,img_description,img,create_time,post_create_time,link_state,link_nick_name')
                ->order('post_create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $tmpImg = $value['img'];
            $link_state = $value['link_state'];
            $user_id = $value['user_id'];
            if($link_state==1) {
                $nick_name = $value['link_nick_name'];
            } else {
                $userInfo=M("user")->where("id=$user_id")->field('nick_name')->find();
                $nick_name = $userInfo['nick_name'];
            }
            $desc = $value['img_description'];          
            $Imgs=explode(';',$tmpImg);
            if(count($Imgs)>1){
                $is_theme = 1;
            }else{
                $is_theme =0;
            }
            $imgUrl=array();    
            if(!empty($Imgs)){
                foreach ($Imgs as $key=>$tmpImg){
                    $imgUrl[]="http://api.meimei.yihaoss.top/".$tmpImg;
                }
            } else {
                $imgUrl=array();    
            }
            $admireCount = M('listing_admire')->where("img_id={$id} and is_cancel='0' ")->count();
            $reviewCount = M('listing_review')->where("img_id={$id} and is_del='0' ")->count();
            $res[]=array(
                'id'=>$id,
                'nick_name'=>$nick_name,
                'img_description'=>$desc,
                'imgUrl'=>$imgUrl,
                'is_theme'=>$is_theme,
                'user_id'=>$user_id,
                'admire_count'=>$admireCount,
                'review_count'=>$reviewCount
            );
        }
        $this->assign("res",$res);
        //$this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    public function operator_replylistinglist(){        
        $imgid = $_GET['id'];
        $album = M('listing');
        $find_where['id']=$imgid;
        $findRes=$album->where($find_where)->find();
        $root_img_id=$findRes['root_img_id'];
        if($root_img_id>0) {
           $find_img_id=$root_img_id;
        } else {
            $find_img_id=$imgid;
        }
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/postreplylist?id=$find_img_id");
    }
    public function reviewreplaypost(){
        $this->display();
    }
    //热点赞
    public function admirepost(){
        $this->display();
    }
    public function publicpostimg() {
        $businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    public function replaypostimg(){
        $imgid=I('id');
        $find_where['id']=$imgid;
        $findRes=M("listing")->where($find_where)->find();
        $user_id=$findRes['user_id'];
        $this->assign('user_id',$user_id);
        $this->display();
    }
    //标签页面
    public function listingLabel(){
        $imgid=I('id');
        $listing_label=M("listing")
                ->field('img_description,label')
                ->where("state=0 and id=$imgid")
                ->find();
        $get_label=$listing_label['label'];
        if($get_label) {
            $array_get_label=explode(",",$get_label);
        }
        $img_description=$listing_label['img_description'];
        $main_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        $min_label=M("listing_label")
                ->field('label2 as label_name,label2_s as label_id')
                ->where("state=0")
                ->select();
        if($min_label) {
            foreach ($min_label as $key => $value) {
                $main_label[]=$value;
            }
        }
        $this->assign('img_description',$img_description);
        $this->assign('labelInfo',$array_get_label);
        $this->assign('labelListing',$main_label);
        $top_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        if($top_label) {
            foreach ($top_label as $key => $value) {
                $label_id=intval($value['label_id']);
                if($label_id>0) {
                    $second_label=M("listing_label")
                            ->field('label2 as label_name,label2_s as label_id')
                            ->where("state=0 and label1_s=$label_id")
                            ->select();
                    if($second_label) {
                        $top_label[$key]['category']=$second_label;
                    }
                }
                
            }
        }
        $this->assign('top_label',$top_label);
        $this->assign('id',$imgid);
        $this->display();
    }
    public function editLabel(){
        $resultList=I('label');        
        $img_id=I('id');
        $now_time=date("Y-m-d H:i:s");
        $where['id']=$img_id;
        $temp_id=array();
        if($resultList) {
            foreach ($resultList as $key => $value) {
                $temp_id[$key]=$value;
            }
            $temp_img_id=implode(",", $temp_id);
            $data['label'] = $temp_img_id;
        } else {
            $data['label'] = "";
        }
        $data['post_create_time'] = $now_time;
        M("listing")->where($where)->save($data);
        header("Location:http://checkpic.meimei.yihaoss.top/Others/listingLabel?id=$img_id");
    }
    public function listingHot(){
        $hotid=I('id');
        $index_data=I('index_data');
        $where_condition['state']='0';
        $where_condition['id']=$hotid;
        $listing_label=M("listing_hot")
                ->where($where_condition)
                ->find();
        $imgid=$listing_label['img_id1'];
        $temp_img1=$listing_label['img1'];
        if($temp_img1) {
            $listing_label['img1']="http://api.meimei.yihaoss.top/".$temp_img1;
        }        
        $main_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        $min_label=M("listing_label")
                ->field('label2 as label_name,label2_s as label_id')
                ->where("state=0")
                ->select();
        if($min_label) {
            foreach ($min_label as $key => $value) {
                $main_label[]=$value;
            }
        }
        $this->assign('businessList',$listing_label);
        $this->assign('labelListing',$main_label);
        $top_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        if($top_label) {
            foreach ($top_label as $key => $value) {
                $label_id=intval($value['label_id']);
                if($label_id>0) {
                    $second_label=M("listing_label")
                            ->field('label2 as label_name,label2_s as label_id')
                            ->where("state=0 and label1_s=$label_id")
                            ->select();
                    if($second_label) {
                        $top_label[$key]['category']=$second_label;
                    }
                }
                
            }
        }
        $this->assign('top_label',$top_label);
        $this->assign('img_id',$imgid);
        $this->assign('index_data',$index_data);
        $this->display();
    }
    public function getHotlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        //先查相册的数据库，
        $post_img = M('listing_hot');
        $model = new Model();
        //满足条件的总记录数
        $count  = $post_img->where("state='0' ")->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $post_img
                ->where("state='0'")
                ->order('id desc')
                ->limit($page->firstRow,$page->listRows)
                ->select(); 
        $res=array();
        foreach($list as $key=>$value){         
            $id = $value['id'];
            $title = $value['title'];            
            $index_show = $value['index_show'];
            $index_data = $value['index_data'];
            $index_show_detail = $value['index_show_detail'];
            $img1 = $value['img1'];
            $img_id1 = $value['img_id1'];            
            if($img1){
                $img_thumb="http://api.meimei.yihaoss.top/".$img1;
            } else {
                $img_thumb="";
            }
            if($index_show_detail>0) {
                $mainlabel=M("listing_label")->field('label1')
                        ->where("label1_s=$index_show_detail ")->find();
                $main_name=$mainlabel['label1'];
                if($main_name) {
                    $label_name=$main_name;
                } else {
                    $minlabel=M("listing_label")->field('label2')
                        ->where("label2_s=$index_show_detail ")->find();
                    $min_name=$minlabel['label1'];
                    $label_name=$min_name;
                }
            } else {
                $label_name="";
            }         
            $res[]=array(
                'id'=>$id,
                'title'=>$title,
                'index_show'=>$index_show,
                'index_data'=>$index_data,
                'index_show_detail'=>$index_show_detail,
                'label_name'=>$label_name,
                'img_thumb'=>$img_thumb,
                'img_id'=>$img_id1      
            );
        }        
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    public function operator_hotstate(){        
        $imgid = $_GET['hotid'];
        $album = M('listing_hot');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getHotlist.html");
    }
    //热点设置为精品
     public function operator_post(){
        
        $imgid = $_POST['imgid'];
        $oprator = $_POST['is_recommend'];
        $album = M('listing');
        $where['id'] = array('in',$imgid);
        $data['is_recommend'] = $oprator;
        $result = $album->where($where)->data($data)->save();   
         if ($result) {
            $array = array(
                'flag'  => true,
                'reMsg'    => "操作成功!",
            );
        }else{
            $array = array(
                'flag'  => false,
                'reMsg'    => "操作失败!",
            );
        }
        echo json_encode($array);
    }
    public function operator_hot(){       
        $imgid = intval(I('imgid'));
        //查找信息
        $where_condition['id'] =$imgid;
        $where_condition['id'] =$imgid;//id|root_img_id 
        $where_condition['img'] = array("like", "static%");   
        $listingList = M('listing')
                    ->field("img_title,img_description,substring_index(img,';',1) as img")
                    ->where($where_condition)
                    //->order("id desc")
                    ->find();

        $now_time=date("Y-m-d H:i:s");
        $arr=$_POST['imgid'];
        $data['create_time']=$now_time;
        $data['img_id1']=$imgid;
        $data['index_show']=2;
        $data['title']=$listingList['img_title'];
        $data['description']=$listingList['img_description'];
        $data['img1']=$listingList['img'];
        $data['index_data']=1;
        $data['index_show_detail']=1;
        $result = M('listing_hot')->add($data);   
         if ($result) {
            $array = array(
                'flag'  => true,
                'reMsg'    => "操作成功!",
            );
        }else{
            $array = array(
                'flag'  => false,
                'reMsg'    => "操作失败!",
            );
        }
        echo json_encode($array);
    }

    public function addcover(){
        $id = $_GET['id'];
        $user_id = $_GET['user_id'];
        $this->assign('id',$id);//赋值数据集
        $this->assign('user_id',$user_id);
        $groupList = M('post_group')->field('id,group_name')->where("state='0'")->order('id desc')->select();
        $this->assign('groupList',$groupList);//赋值数据集
        $businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集

        $this->display('addcover');
    
    }
    public function newheadlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        //$where2['online'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table(array('baby_listing_head' => 'business'))
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $list = $model->table(array('baby_listing_head' => 'business'))
                            ->where($where2)
                            ->field('business.id,business.img,business.type')
                            ->limit($page->firstRow,$page->listRows)
                            ->select();
        
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $album_img = "http://api.meimei.yihaoss.top/".$value['img'];
            $business_type = intval($value['type']);
            if($business_type==1){
                $name = "来自专题";
            }elseif($business_type==2){
                $name = "来自群";
            }elseif($business_type==3){
                $name = "来自帖子";
            }elseif($business_type==4){
                $name = "来自个人相册";
            }elseif($business_type==5){
                $name = "来自值得买";
            }elseif($business_type==0){
                $name = "来自静态图片";
            }elseif($business_type==8){
                $name = "来自视频";
            }                    
            $res[]=array(
            'id'=>$id,
            'img'=>$album_img,
            'name'=>$name
            );
        }
    
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();  
    }
    public function newhead(){
        $businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
        
        $this->display();
    }
    public function operator_new_head(){        
        $id = $_GET['id'];
        $album = M('listing_head');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/newheadlist.html");
    }
    public function listinghomelist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        //满足条件的总记录数
        $count  = M('listing_pagg')
                ->where($where2)
                ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list= M('listing_pagg')
                ->field("id,title,index_show,index_data,index_show_detail,index_list_key,rank,img_id1,img_id2,img_id3,substring_index(img1,';',1) as img1,substring_index(img2,';',1) as img2,substring_index(img3,';',1) as img3")
                ->where($where2)
                ->limit($page->firstRow,$page->listRows)
                ->order("rank asc, id desc")
                ->select();
        if($list) {
            foreach ($list as $key => $value) {
                $list[$key]['img1']="http://api.meimei.yihaoss.top/".$value['img1'];
                $list[$key]['img2']="http://api.meimei.yihaoss.top/".$value['img2'];
                $list[$key]['img3']="http://api.meimei.yihaoss.top/".$value['img3'];
            }
        }
        $this->assign("res",$list);
        $this->assign('page',$show);//赋值分页输出
        $this->display();    
    }
    public function operator_listing_home(){        
        $id = $_GET['id'];
        $album = M('listing_pagg');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();;
        header("Location:http://checkpic.meimei.yihaoss.top/Others/listinghomelist.html");
    }
    public function listinghome(){
        $special_id=I("id");
        if($special_id) {
            $specialList = M('listing_pagg')
                    ->field("id,title,index_show,index_data,index_show_detail,index_list_key,rank,img_id1,img_id2,img_id3,substring_index(img1,';',1) as img1,substring_index(img2,';',1) as img2,substring_index(img3,';',1) as img3,create_time,region,is_ad")
                    ->where('id='.$special_id)->find();
            $img1=$specialList['img1'];
            if($img1) {
                $specialList['img1']="http://api.meimei.yihaoss.top/".$img1;
            }
            $img2=$specialList['img2'];
            if($img2) {
                $specialList['img2']="http://api.meimei.yihaoss.top/".$img2;
            }
            $img3=$specialList['img3'];
            if($img3) {
                $specialList['img3']="http://api.meimei.yihaoss.top/".$img3;
            }
            $this->assign('specialList',$specialList);
        }
        $main_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        $min_label=M("listing_label")
                ->field('label2 as label_name,label2_s as label_id')
                ->where("state=0")
                ->select();
        if($min_label) {
            foreach ($min_label as $key => $value) {
                $main_label[]=$value;
            }
        }
        //赋值数据集*/
        $this->assign('labelListing',$main_label);
        $top_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        if($top_label) {
            foreach ($top_label as $key => $value) {
                $label_id=intval($value['label_id']);
                if($label_id>0) {
                    $second_label=M("listing_label")
                            ->field('label2 as label_name,label2_s as label_id')
                            ->where("state=0 and label1_s=$label_id")
                            ->select();
                    if($second_label) {
                        $top_label[$key]['category']=$second_label;
                    }
                }
                
            }
        }
        $this->assign('top_label',$top_label);
        $this->display();
    }
    //编辑帖子
    public function editListingInfo(){
        $this->checkSession();
        $id = $_GET['id'];
        import("ORG.Util.Page");
        //先查相册的数据库，
        $post_img = M('listing');
        $where2['state'] ='0';
        $where2['id'] =$id;

        $list = M('listing')
                ->where($where2)
                ->field('id,img_title,img_description,img,cover,jump_id,create_time,index_show,index_cover')
                ->find();
        $res=array();
        $res=array(
            'id'=>$list['id'],
            'img_title'=>$list['img_title'],
            'img_description'=>$list['img_description'],
            'jump_id'=>$list['jump_id'],
            'create_time'=>$list['create_time'],
            'index_show'=>$list['index_show']
        );
        $tmp_cover=$list['cover'];
        if($tmp_cover) {
            $res['cover']="http://api.meimei.yihaoss.top/".$tmp_cover;
        }
        $tmp_index_cover=$list['index_cover'];
        if($tmp_index_cover) {
            $res['index_cover']="http://api.meimei.yihaoss.top/".$tmp_index_cover;
        }
        $Imgs=$list['img'];
        if($Imgs) {
            $array_pics = explode(";",$Imgs);
        }        
        if($array_pics[0]) {
            $res['pic1']="http://api.meimei.yihaoss.top/".$array_pics[0];
        }
        if($array_pics[1]) {
            $res['pic2']="http://api.meimei.yihaoss.top/".$array_pics[1];
        }
        if($array_pics[2]) {
            $res['pic3']="http://api.meimei.yihaoss.top/".$array_pics[2];
        }
        if($array_pics[3]) {
            $res['pic4']="http://api.meimei.yihaoss.top/".$array_pics[3];
        }
        
        $this->assign("res",$res);
        //$this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //群中帖子列表
    public function groupListing(){
        $this->checkSession();
        $group_id = $_GET['id'];
        import("ORG.Util.Page");
        //先查相册的数据库，
        $post_img = M('listing');
        $where2['state'] ='0';
        $model = new Model();

        //满足条件的总记录数
        $count  = $post_img
                        ->where("state='0' and group_id={$group_id} and root_img_id is null  ")
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出



        $list = $post_img
                ->where("state='0' and group_id={$group_id} and root_img_id is null  ")
                ->field('id,user_id,img_description,img,create_time,post_create_time,link_state,link_nick_name')
                ->order('post_create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $tmpImg = $value['img'];
            $link_state = $value['link_state'];
            $user_id = $value['user_id'];
            if($link_state==1) {
                $nick_name = $value['link_nick_name'];
            } else {
                $userInfo=M("user")->where("id=$user_id")->field('nick_name')->find();
                $nick_name = $userInfo['nick_name'];
            }
            $desc = $value['img_description'];          
            $Imgs=explode(';',$tmpImg);
            if(count($Imgs)>1){
                $is_theme = 1;
            }else{
                $is_theme =0;
            }
            $imgUrl=array();    
            if(!empty($Imgs)){
                foreach ($Imgs as $key=>$tmpImg){
                    $imgUrl[]="http://api.meimei.yihaoss.top/".$tmpImg;
                }
            } else {
                $imgUrl=array();    
            }
            $res[]=array(
                'id'=>$id,
                'nick_name'=>$nick_name,
                'img_description'=>$desc,
                'imgUrl'=>$imgUrl,
                'is_theme'=>$is_theme,
                'user_id'=>$user_id
            );
        }
        $this->assign("res",$res);
        //$this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    public function publicmanage(){
        $this->display();
    }
    public function publictoys(){
        $this->checkSession();
        $this->display();
    }
    //发布玩具
    public function publictoysdet(){
        $cate_info=M("toys_category")
                ->field('title,category_id')
                ->where("state=0")
                ->select();
        $this->assign('cate_info',$cate_info);         
        $this->display();
    }
    //玩具列表
    public function getToyslist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $img_title = I('img_title');  
        $img_id = intval(I('img_id'));
        $listing_id = intval(trim(I('listing_id')));
        $search_state = intval(I('search_state'));
        $category_id = I('category_id');
        $where_condition=" b.state='0' and b.root_img_id=0 ";
        if($search_state==0){
            //
        }elseif($search_state==1){
            $where_condition.=" and b.is_show='0' ";
        }elseif($search_state==2){
            $where_condition.=" and b.is_show='2' ";
        }elseif($search_state==3){
            $where_condition.=" and b.is_break='1' ";
        }


        $this->assign('search_state',$search_state);      
        if(!empty($img_title)) {
            $where_condition.=" and ( (b.business_title like '%$img_title%') or (b.key_words like '%$img_title%') or (b.business_brand like '%$img_title%') ) ";
            $this->assign('img_title',$img_title);
        }
        if(!empty($category_id)) {
            $where_condition.=" and b.category_ids like '%$category_id%' ";
            $this->assign('category_id',$category_id);
        }
        if($img_id>0) {
            $where_condition.=" and b.id='$img_id'";
            $this->assign('img_id',$img_id);
        }
        if($listing_id>0) {
            $where_condition.=" and l.id='$listing_id'";
            $this->assign('listing_id',$listing_id);
        }
        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where_condition.=" and b.create_time>='$start_time' and b.create_time<='$end_time' ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where_condition.=" and b.create_time>='$start_time'  ";
            $this->assign('start_time',$start_time);
        }
        //先查相册的数据库，
        $model = new Model();
        //满足条件的总记录数
        $tmpcount  = $model->table('baby_toys_business as b')
                ->join(" left join baby_toys_business_listing as l on l.business_id=b.id")
                ->field('distinct b.id')
                ->where($where_condition)->select();
        $count=count($tmpcount);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_business as b')
                ->join(" left join baby_toys_business_listing as l on l.business_id=b.id")
                ->field('distinct b.id,b.business_title,b.sell_price,b.business_pics,b.is_card,b.category_ids,b.is_show as state,b.is_break')
                ->where($where_condition)
                ->order('b.create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select(); 
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $business_title = $value['business_title']; 
            $tmp_category_ids = $value['category_ids'];
            $tmp_state = $value['state'];
            $tmp_break = $value['is_break'];
            if($tmp_break==1){
                $tmp_state = 3;
            }
            if($tmp_state==2){
                $business_title = "【已下架】".$business_title;
            }elseif($tmp_state==3){
                $business_title = "【断货】".$business_title;
            }
            $toys_size=0;
            if($tmp_category_ids) {
                $arr_category_ids=explode(",", $tmp_category_ids);
                if(in_array("106",$arr_category_ids) ) {
                    $toys_size="1";
                }
            } 
            if($toys_size==1) {
                $size_img_thumb="http://api.meimei.yihaoss.top/static3/album/2017/0517/0d96a3aec4cb0d6d.jpg";
            } else {
                $size_img_thumb="";
            }         
            $sell_price = $value['sell_price'];
            $tmpImg = $value['business_pics'];
            $is_card=$value['is_card'];
            $Imgs=explode(';',$tmpImg);
            $imgUrl=array();    
            if(is_array($Imgs)){
                foreach ($Imgs as $key=>$tmpImg){
                    $imgUrl[]="http://api.meimei.yihaoss.top/".$tmpImg;
                }
            } else {
                $imgUrl=array(); 
            } 
            $total_numberC = $this->getBusinessTotalNum($id);
            $total_number = $total_numberC['total_count'];
            $toys_number = $total_numberC['use_count'];
            if($toys_number<=0) {
                $toys_number=0;
            } 
            if($is_card==9) {
                $is_card=0;
            }       
            $res[]=array(
                'id'=>$id,
                'business_title'=>$business_title,
                'imgUrl'=>$imgUrl,
                'total_number'=>$total_number,
                'toys_number'=>$toys_number,
                'sell_price'=>$sell_price,
                'is_card'=>$is_card,
                'size_img_thumb'=>$size_img_thumb,
                'state'=>$tmp_state      
            );
        }
        $cate_info=M("toys_category")
                ->field('title,category_id')
                ->where("state=0")
                ->select();
        $this->assign('cate_info',$cate_info);
        $this->assign("res",$res);
        $this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除
    public function operator_toysstate(){        
        $imgid = $_GET['img_id'];
        $album = M('toys_business');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getToyslist.html");
    }
    //玩具帖子列表
    public function toysreplylist(){
        $this->checkSession();
        $id = $_GET['id'];
        import("ORG.Util.Page");
        //先查相册的数据库，
        $post_img = M('toys_business');
        $where2['state'] ='0';
        $model = new Model();
        $list = $post_img
                ->where("state='0' and (root_img_id={$id} or id={$id})  ")
                ->field('id,business_des,business_pics')
                ->order('root_img_id asc,id asc')
                ->limit($page->firstRow,$page->listRows)
                ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $tmpImg = $value['business_pics'];
            $business_des = $value['business_des'];         
            $Imgs=explode(';',$tmpImg);
            $imgUrl=array();    
            if(!empty($Imgs)){
                foreach ($Imgs as $key=>$tmpImg){
                    $imgUrl[]="http://api.meimei.yihaoss.top/".$tmpImg;
                }
            } else {
                $imgUrl=array();    
            }
            $res[]=array(
                'id'=>$id,
                'business_des'=>$business_des,
                'imgUrl'=>$imgUrl
            );
        }
        $this->assign("res",$res);
        $this->display();
    }
    public function operator_replytoyslist(){        
        $imgid = $_GET['id'];
        $album = M('toys_business'); 
        $find_where['id']=$imgid;
        $findRes=$album->where($find_where)->find();
        $root_img_id=$findRes['root_img_id'];
        if($root_img_id>0) {
           $find_img_id=$root_img_id;
        } else {
            $find_img_id=$imgid;
        }      
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysreplylist?id=$find_img_id");
    }

    //编辑帖子
    public function editToysInfo(){
        $this->checkSession();
        $id = $_GET['id'];
        $where2['id'] = array('in',$id);
        $list = M('toys_business')
                ->where($where2)
                ->find();
        $res=array();
        $way=$list['way'];
        $tmp_market_price=$list['market_price'];
        $tmp_sell_price=$list['sell_price'];
        if($way==1) {
            $market_price2=$tmp_market_price;
            $sell_price2=$tmp_sell_price;
            $market_price1=$sell_price1="";
        } else {
            $market_price2=$sell_price2="";
            $market_price1=$tmp_market_price;
            $sell_price1=$tmp_sell_price;
        }

        $res=array(
            'id'=>$list['id'],
            'business_title'=>$list['business_title'],
            'business_contact'=>$list['business_contact'],
            'qq'=>$list['qq'],
            'business_des'=>$list['business_des'],
            'main_part'=>$list['main_part'],
            'parts'=>$list['parts'],
            'weight'=>$list['weight'],
            'size'=>$list['size'],
            'color'=>$list['color'],
            'business_location'=>$list['business_location'],
            'way'=>$list['way'],
            'is_support'=>$list['is_support'],
            'market_price1'=>$market_price1,
            'sell_price1'=>$sell_price1,
            'market_price2'=>$market_price2,
            'sell_price2'=>$sell_price2,
            'need_price'=>$list['need_price'],
            'service_price'=>$list['service_price'],
            'highest_price'=>$list['highest_price'],
            'age'=>$list['age'],
            'root_img_id'=>$list['root_img_id'],
            'is_card'=>$list['is_card'],
            'number'=>$list['number'],
            'is_active'=>$list['is_active'],
            'contract_number'=>$list['contract_number'],
            'total_number'=>$list['total_number'],
            'number_title'=>$list['number_title'],
            'service_number'=>$list['service_number'],
            'battery_number8'=>$list['battery_number8'],
            'battery_number1'=>$list['battery_number1'],
            'battery_number2'=>$list['battery_number2'],
            'battery_number3'=>$list['battery_number3'],
            'battery_number4'=>$list['battery_number4'],
            'battery_number5'=>$list['battery_number5'],
            'battery_number6'=>$list['battery_number6'],
            'battery_number7'=>$list['battery_number7']
        );
        $Imgs=$list['business_pics'];
        if($Imgs) {
            $array_pics = explode(";",$Imgs);
        } else {
            $array_pics=array();
        }   
        if($array_pics) {
            if($array_pics[0]) {
                $res['pic1']="http://api.meimei.yihaoss.top/".$array_pics[0];
            }
            if($array_pics[1]) {
                $res['pic2']="http://api.meimei.yihaoss.top/".$array_pics[1];
            }
            if($array_pics[2]) {
                $res['pic3']="http://api.meimei.yihaoss.top/".$array_pics[2];
            }
            if($array_pics[3]) {
                $res['pic4']="http://api.meimei.yihaoss.top/".$array_pics[3];
            }
        }
        $this->assign("res",$res);
        $tmp_category_ids=$list['category_ids'];
        if($tmp_category_ids) {
            $array_category_id=explode(",",$tmp_category_ids);
        }
        $this->assign('categoryInfo',$array_category_id);           
        $cate_info=M("toys_category")
                ->field('title,category_id')
                ->where("state=0")
                ->select();
        $this->assign('cate_info',$cate_info);
        //$this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //玩具订单列表
    public function toysorderlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="1 ";
        $model = new Model();
        $order_id=trim(I("order_id"));
        if($order_id) {
            $where2.=" and o.id=$order_id and o.state='0' ";
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%' and o.state='0' ";
            $this->assign('order_num',$order_num);
        }
        $order_status=trim(I("order_status"));
        if($order_status) {
            /*if($order_status==7) {
                $where2.=" and o.status in ('7','11') and o.state='0' ";
            } else {
                 
            }*/
            $where2.=" and o.status=$order_status and o.state='0'  ";
            $this->assign('order_status',$order_status);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }
        $user_id=trim(I("user_id"));
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2.=" and o.post_create_time>='$start_time' and o.post_create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.post_create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }
        if(empty($order_id) && empty($order_num) && empty($order_status) ) {
            //$where2.=" and (o.state='0' and o.status not in ('7','11')  or o.status in ('7','11')) ";
            $where2.=" and o.state='0' ";
        }
        
        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_business as b on o.business_id=b.id")
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->join(" left join baby_user as s on o.seller_id=s.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->join(" left join baby_user as s on o.seller_id=s.id")
                    ->where($where2)
                    ->field('o.id,o.order_num,o.post_create_time,o.user_id,u.nick_name,u.email,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,s.nick_name as seller_name,b.business_contact as bus_mobile,s.mobile as ob_mobile,o.status,o.payment,b.way,o.total_price,o.trade_no,o.address,o.combined_order_id,o.toys_number')
                    ->order('o.post_create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        foreach ($res as $key => $value) {
            $tmp_nick_name=$value['nick_name']?$value['nick_name']."[平]":"";
            $res[$key]['nick_name']=$tmp_nick_name;
            
            $tmp_mobile=$value['mobile']?$value['mobile']."[平]":"";
            $res[$key]['mobile']=$tmp_mobile;

            $tmp_ord_user_name=$value['ord_user_name']?$value['ord_user_name']."[订]":"";
            $res[$key]['ord_user_name']=$tmp_ord_user_name;

            $tmp_ord_mobile=$value['ord_mobile']?$value['ord_mobile']."[订]":"";
            $res[$key]['ord_mobile']=$tmp_ord_mobile;

            $tmp_seller_name=$value['seller_name']?$value['seller_name']."[平]":"";
            $res[$key]['seller_name']=$tmp_seller_name;

            $tmp_ob_mobile=$value['ob_mobile']?$value['ob_mobile']."[平]":"";
            $res[$key]['ob_mobile']=$tmp_ob_mobile;

            $tmp_bus_mobile=$value['bus_mobile']?$value['bus_mobile']."[订]":"";
            $res[$key]['bus_mobile']=$tmp_bus_mobile;

            $tmp_status=$value['status'];
            if(($tmp_status==3) || ($tmp_status==4) ) {
                $res[$key]['status']=2;
            } else {
                $res[$key]['status']=$tmp_status;  
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }

    //玩具订单状态编辑
    public function edittoysorderstatus(){
        $this->checkSession();
        $model = new Model();
        $order_id = trim(I('order_id'));//订单id
        $status = trim(I('status'));//订单状态
        $user_id = trim(I('user_id'));//用户id
        $business_id = trim(I('business_id'));//玩具id
        //判断是不是卡
        $str_card_info=$this->getCardInfo();
        $arr_card_info = explode(',',$str_card_info);
        if(in_array($business_id,$arr_card_info)){
            $is_card_status = 1;
        }else{
            $is_card_status = 0;
        }
        $this->assign('is_card_status',$is_card_status);

        //order   消费状态（1待支付、2准备中【办卡未消费】、5送货中、6玩乐中、7已完成【我们】、8退款中、9已退款、10已完成【用户】、11入库、12卡过期、13周卡失效）
        $status_name = " ";
        switch($status){
            case 1:
                $status_name = "待支付";
                break;
            case 2:
                $status_name = "准备中";
                break;
            case 5:
                $status_name = "送货中";
                break;
            case 6:
                $status_name = "玩乐中";
                break;
            case 7:
                $status_name = "待入库";
                break;
            case 8:
                $status_name = "退款中";
                break;
            case 9:
                $status_name = "已退款";
                break;
            case 10:
                $status_name = "待取回";
                break;
            case 11:
                $status_name = "已入库";
                break;
            case 12:
                $status_name = "卡过期";
                break;
            case 13:
                $status_name = "周卡失效";
                break;
            case 14:
                $status_name = "恢复玩乐";
                break;
            case 17:
                $status_name = "重新配送";
                break;
            default:
                $status_name = "未知";
        }
        $this->assign('status_name',$status_name);
        $this->assign('order_id',$order_id);
        $this->assign('status',$status);
        $this->assign('user_id',$user_id);

        //是否停卡取回玩具标记
//        if( ($status==10) && $order_id>0 ){
            $is_stop_card_res = $model->table('baby_toys_order')
                ->where(" id=$order_id ")
                ->field('is_size')
                ->find();
            $is_size = $is_stop_card_res['is_size'];
            $this->assign('is_size',$is_size);
//            $this->assign('stop_card',1);
//        }

        $business_info = $model->table('baby_toys_business')
            ->where(" id=$business_id ")
            ->field('business_title,category_ids')
            ->find();
        $category_ids = $business_info['category_ids'];
        $category_ids = explode(',',$category_ids);
        if(in_array('106',$category_ids)){
            $category_ids = '（小）';
        }else{
            $category_ids = '（大）';
        }

        $business_title = $business_info['business_title'].$category_ids;
        $this->assign('business_title',$business_title);

        $res_old = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->where(" o.user_id=$user_id AND o.status=10 AND o.state='0' ")
            ->field('o.id,o.business_id,b.business_title,b.category_ids')
            ->select();

        foreach($res_old as &$value){
            $category_ids_old = $value['category_ids'];
            $category_ids_old = explode(',',$category_ids_old);
            if(in_array('106',$category_ids_old)){
                $value['business_title'] .= '（小）';
            }else{
                $value['business_title'] .= '（大）';
            }
        }


        $this->assign('res_old',$res_old);//待取回 用户手里的玩具
        $this->assign('res_old_pre',$res_old);//待取回 用户手里的玩具

        $res_new = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->where(" o.user_id=$user_id AND o.status=5 AND o.state='0' AND o.cancel_reason=2 ")
            ->field('o.id,o.business_id,b.business_title,b.category_ids')
            ->select();

        foreach($res_new as &$value){
            $category_ids_old = $value['category_ids'];
            $category_ids_old = explode(',',$category_ids_old);
            if(in_array('106',$category_ids_old)){
                $value['business_title'] .= '（小）';
            }else{
                $value['business_title'] .= '（大）';
            }
        }

        $this->assign('res_new',$res_new);//送货中  新玩具

        $res_pre = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->where(" o.user_id=$user_id AND o.status=2 AND o.state='0' ")
            ->field('o.id,o.business_id,b.business_title,b.category_ids')
            ->select();

        foreach($res_pre as &$value){
            $category_ids_old = $value['category_ids'];
            $category_ids_old = explode(',',$category_ids_old);
            if(in_array('106',$category_ids_old)){
                $value['business_title'] .= '（小）';
            }else{
                $value['business_title'] .= '（大）';
            }
        }

        $this->assign('res_pre',$res_pre); //准备中 新玩具


        //添加备注信息 开始
        $remark_res=M("toys_order")
                    ->where(" id=$order_id ")
                    ->field('id,remark')
                    ->find();
        $this->assign('remark_res',$remark_res);
        //添加备注信息 结束

        $this->display();
    }

    //玩具仓库管理
    public function toysstorehouse(){
        $str_card_info=$this->getCardInfo();
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" case when status=7 then (o.state!='77' and toys_number is not null) else  o.state='0' end  and o.business_id not in ($str_card_info) and o.status not in (1,5,6,8,9,11)  ";
        $ascsql = "o.create_time asc";
        $model = new Model();
        $order_id=trim(I("order_id"));

        $excel_state=I("excel_state");
        if($order_id) {
            $where2.=" and o.id=$order_id and case when status=7 then o.state!='77' else  o.state='0' end ";
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%' and case when status=7 then o.state!='77' else  o.state='0' end ";
            $this->assign('order_num',$order_num);
        }

        $user_id=trim(I("user_id"));
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }

        $order_status=trim(I("order_status"));
        if(!$order_status && !$user_id){
            $order_status = 1;
        }
        if($order_status) {
            if($order_status == 1){
                $where2.= " and o.status=2 and o.is_ready =0 and o.toys_number is null  ";
            }elseif($order_status == 2){
                $where2.= " and o.is_ready=0 and ((o.status=2 and o.toys_number is not null ) or (o.status=10)) ";
                $ascsql = "o.user_id ASC";
            }elseif($order_status == 3){
                $where2.= " and o.is_ready=1 and ((o.status=2 and o.toys_number is not null ) or (o.status=10)) ";
                $ascsql = "o.user_id ASC";
            }else{
                $where2.=" and o.status=$order_status and case when status=7 then o.state!='77' else  o.state='0' end   ";
            }

            $this->assign('order_status',$order_status);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }

        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2.=" and o.post_create_time>='$start_time' and o.post_create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.post_create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }
        if(empty($order_id) && empty($order_num) && empty($order_status) ) {
            //$where2.=" and (o.state='0' and o.status not in ('7','11')  or o.status in ('7','11')) ";
            $where2.=" and o.state='0' ";
        }

        $toys_number = I("toys_number");
        if($toys_number){
            $where2.=" and o.toys_number='$toys_number' ";
            $this->assign('toys_number',$toys_number);
        }
        if($excel_state==1) {//导出
            $xlsName="仓库";
            $xlsCell  = array(
                array('user_info','用户信息'),
                array('address_info','收货信息'),
                array('business_info','玩具ID/玩具标题/玩具编号')   
            );
            $xlsData=array();
            $excelRes= $model->table('baby_toys_order as o')
                ->join(" left join baby_toys_business as b on o.business_id=b.id")
                ->join(" left join baby_user as u on o.user_id=u.id")
                ->where($where2)
                ->field('o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.address,o.business_id,b.business_title,o.toys_number')
                ->order("$ascsql")
                ->select();
            if($excelRes) {
                foreach ($excelRes as $key => $value) {
                    $tmp_user_id=$value['user_id'];
                    $user_info="";
                    $user_info.=$tmp_user_id;
                    $tmp_nick_name=$value['nick_name'];
                    if($tmp_nick_name) {
                        $user_info.="\n".$tmp_nick_name;
                    }
                    $tmp_mobile=$value['mobile'];
                    if($tmp_mobile) {
                        $user_info.="\n".$tmp_mobile;
                    }
                    $business_id=$value['business_id'];
                    $business_title=$value['business_title'];
                    $toys_number=$value['toys_number'];
                    //$business_info=$business_id."\r\n".$business_title."\r\n".$toys_number;
                    $business_info="$business_id\n$business_title\n$toys_number";
                    
                    $address_info="";
                    $address=$value['address'];
                    $tmp_ord_user_name=$value['ord_user_name'];
                    if($tmp_ord_user_name) {
                        $address_info.=$tmp_ord_user_name;
                    }
                    $tmp_ord_mobile=$value['ord_mobile'];
                    if($tmp_ord_mobile) {
                        $address_info.="\n".$tmp_ord_mobile;
                    }
                    if($address) {
                        $address_info.="\n".$address;
                    }
                    $xlsData[]=array(
                        'user_info'=>$user_info,
                        'address_info'=>$address_info,
                        'business_info'=>$business_info
                        );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }

        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_user as s on o.seller_id=s.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_user as s on o.seller_id=s.id")
            ->where($where2)
            ->field('o.id,o.order_num,o.post_create_time,o.delivery_time,o.user_id,u.nick_name,u.email,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.payment,b.way,o.total_price,o.trade_no,o.address,o.combined_order_id,o.toys_number,o.is_ready,o.remark')
            ->order("$ascsql")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        foreach ($res as $key => $value) {
            $tmp_nick_name=$value['nick_name']?$value['nick_name']:"";
            $res[$key]['nick_name']=$tmp_nick_name;

            $tmp_mobile=$value['mobile']?$value['mobile']:"";
            $res[$key]['mobile']=$tmp_mobile;

            $tmp_ord_user_name=$value['ord_user_name']?$value['ord_user_name']:"";
            $res[$key]['ord_user_name']=$tmp_ord_user_name;

            $tmp_ord_mobile=$value['ord_mobile']?$value['ord_mobile']:"";
            $res[$key]['ord_mobile']=$tmp_ord_mobile;

            $tmp_status=$value['status'];
            if(($tmp_status==3) || ($tmp_status==4) ) {
                $res[$key]['status']=2;
            } else {
                $res[$key]['status']=$tmp_status;
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //仓库备货中
    public function toysorderprepare(){
        $str_card_info=$this->getCardInfo();
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.state='0' and o.status in(2,17) and ((o.toys_number is null) or (o.toys_number='') ) and o.business_id not in ($str_card_info) and is_prize in (0,3,6)   ";
        $ascsql = "o.create_time asc";
        $day_ago=date("Y-m-d 00:00:00", strtotime ("-2 day"));
        $search_condition_id=I("search_condition_id");
        $model = new Model();
        $order_id=trim(I("order_id"));
        $search_address=I("search_address");
        $excel_state=I("excel_state");
        if($order_id) {
            $where2.=" and o.id=$order_id ";
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%' ";
            $this->assign('order_num',$order_num);
        }
        if($search_condition_id==1) {//已超过2天
            $where2.=" and o.create_time<'$day_ago'  ";
            $this->assign('search_condition_id',$search_condition_id);
        }
        $user_id=trim(I("user_id"));
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }
        if($search_address) {
            $where2.=" and o.address like '%$search_address%' ";
            $this->assign('search_address',$search_address);
        }

        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2.=" and o.create_time>='$start_time' and o.create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        } elseif($end_time) {
            $where2.=" and o.create_time<='$end_time'  ";
            $this->assign('end_time',$end_time);
        }

        //查询运营账号
        $getCommonUserRes= $model->table('baby_common_user')
            ->where("user_id>0")
            ->field('user_id')
            ->select();
        foreach($getCommonUserRes as $val){
            $common_user_id_arr[] = $val['user_id'];
        }
        $common_user_id_str = implode(',',$common_user_id_arr);
        $where2.=" and o.user_id not in ($common_user_id_str) ";
        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_user_remark as r on o.user_id=r.user_id and r.state='0' ")
            ->where($where2)
            ->field('o.id,o.order_num,o.create_time,o.post_create_time,o.delivery_time,o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.payment,b.way,o.total_price,o.trade_no,o.address,o.combined_order_id,o.toys_number,o.is_ready,o.remark,r.remark as remark_r,o.battery_price,o.battery_brand,b.battery_number1,b.battery_number2,b.battery_number3,b.battery_number4,b.battery_number5,b.battery_number6,b.battery_number7,b.battery_number8')
            ->order("$ascsql")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        foreach ($res as $key => $value) {
            $tmp_nick_name=$value['nick_name']?$value['nick_name']:"";
            $res[$key]['nick_name']=$tmp_nick_name;

            $res[$key]['remark_end'] = $value['remark']."<br>".$value['remark_r'];

            $tmp_mobile=$value['mobile']?$value['mobile']:"";
            $res[$key]['mobile']=$tmp_mobile;

            $tmp_ord_user_name=$value['ord_user_name']?$value['ord_user_name']:"";
            $res[$key]['ord_user_name']=$tmp_ord_user_name;

            $tmp_ord_mobile=$value['ord_mobile']?$value['ord_mobile']:"";
            $res[$key]['ord_mobile']=$tmp_ord_mobile;
            
            $tmp_status=$value['status'];
            if(($tmp_status==3) || ($tmp_status==4) ) {
                $res[$key]['status']=2;
            } else {
                $res[$key]['status']=$tmp_status;
            }

            $battery_price = $value['battery_price'];//电池价格
            if($battery_price=='0.00'){
                $battery_price=0;
            }
            $battery_brand = $value['battery_brand'];//电池品品牌：1华泰、2南孚
            $battery_number1 = $value['battery_number1']?"一号电池X".$value['battery_number1']:"";//  1号电池
            $battery_number2 = $value['battery_number2']?"二号电池X".$value['battery_number2']:"";//  2号电池
            $battery_number3 = $value['battery_number3']?"三号电池X".$value['battery_number3']:"";//  3号电池
            $battery_number4 = $value['battery_number4']?"四号电池X".$value['battery_number4']:"";//  4号电池
            $battery_number5 = $value['battery_number5']?"五号电池X".$value['battery_number5']:"";//  5号电池
            $battery_number6 = $value['battery_number6']?"六号电池X".$value['battery_number6']:"";//  6号电池
            $battery_number7 = $value['battery_number7']?"七号电池X".$value['battery_number7']:"";//  7号电池
            $battery_number8 = $value['battery_number8']?"纽扣电池X".$value['battery_number8']:"";//  纽扣电池
            $battery_number = "";
            if($battery_number1){
                $battery_number .= $battery_number1.";";
            }
            if($battery_number2){
                $battery_number .= $battery_number2.";";
            }
            if($battery_number3){
                $battery_number .= $battery_number3.";";
            }
            if($battery_number4){
                $battery_number .= $battery_number4.";";
            }
            if($battery_number5){
                $battery_number .= $battery_number5.";";
            }
            if($battery_number6){
                $battery_number .= $battery_number6.";";
            }
            if($battery_number7){
                $battery_number .= $battery_number7.";";
            }
            if($battery_number8){
                $battery_number .= $battery_number8.";";
            }


            if($battery_brand==1){
                $battery_brand="华太电池";
            }elseif($battery_brand==2){
                $battery_brand = "南孚电池";
            }elseif($battery_brand==3){
                $battery_brand = "双鹿电池";
            }else{
                $battery_brand = "无品牌";
            }

            $battery_info = "";

            if(!empty($battery_price) && $battery_number){
                $battery_info = "电池总计：￥".$battery_price.";".$battery_brand.";详情：".$battery_number ;
            }
            $res[$key]['battery_info']=$battery_info;


        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //仓库待派单
    public function toysordersend() {
        $this->checkSession(); 
        $user_id=I("user_id");
        $day_ago=date("Y-m-d 00:00:00", strtotime ("-2 day"));
        $search_condition_id=I("search_condition_id");
        $search_address=I("search_address");
        $excel_state=I("excel_state");
        $str_card_info=$this->getCardInfo(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        //备货中订单用户
        $where2=" o.state='0' and o.business_id not in ($str_card_info) and l.postman_id=0 and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6) and o.toys_number<>'' ";

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
            $where2.=" and ((o.status in (2,17)  and l.status=2 and l.state='0') or (o.status=10 and l.status=7 and l.state='0') )  ";
        }
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
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
                    ->order("update_time desc ")
                    ->select();
            $xlsData=array();
            if($getexcelUserRes) {
                $xlsName="待派单";
                $xlsCell  = array(
                    array('user_id','用户id'),
                    array('user_info','配送信息'),
                    array('send_toys','待配送'),
                    array('pick_toys','待取回'),
                    array('remark','备注')   
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
                    $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) and o.toys_number<>'' ","\n");
                    if(empty($returnsendInfo) ) {
                        $tmp_remark="";
                    } else {
                        $send_toys=$returnsendInfo['send_toys'];
                        $tmp_remark=$returnsendInfo['remark'];
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
                    $returnpickInfo=$this->returnpickInfo("o.status in (10) and o.state='0' and o.user_id=$tmp_user_id ","\n");
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                    }

                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id,
                        'user_info'=>$user_info,
                        'send_toys'=>$send_toys,
                        'pick_toys'=>$pick_toys,
                        'remark'=>$remark
                    );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                        ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                        ->where($where2)
                        ->field('o.user_id')
                        ->group("o.user_id")
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                    ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                    ->where($where2)
                    ->field("o.user_id,max(o.create_time) update_time,a.user_name,a.mobile,a.address ")
                    ->group("o.user_id")
                    ->order("update_time desc ")
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
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) and o.toys_number<>'' ","<br/>");
                if(empty($returnsendInfo) ) {//
                    $tmp_remark="";
                } else {
                    $send_toys=$returnsendInfo['send_toys'];
                    $tmp_remark=$returnsendInfo['remark'];
                }
                $checkBatRes=M("toys_order")
                            ->where("user_id=$tmp_user_id and is_prize='2' and is_battery='1' and state='0' and status in (2,17) ")
                            ->field("combined_order_id,battery_brand ")
                            ->select();
                if($checkBatRes) {
                    foreach ($checkBatRes as $k=>$va) {
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
                    ->where("o.status in (2,5,17) and o.user_id=$tmp_user_id and o.is_prize='1' and o.state='0' ")
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
                //查询下单时间
                $res_time_status = M('toys_order')
                    ->where(" state='0' and user_id='$tmp_user_id' and status in(2,10,17) and business_id not in ($str_card_info) ")
                    ->field('create_time,post_create_time,status')
                    ->select();
                foreach($res_time_status as $value){
                    $send_time = $value['post_create_time'];
                    $send_status = $value['status'];
                    if(($send_status==2)||($send_status==17)){
                        $send_time = $value['create_time'];
                        break;
                    }
                }

                $res[]=array(
                    'user_id'=>$tmp_user_id,
                    'user_info'=>$user_info,
                    'send_toys'=>$send_toys,
                    'pick_toys'=>$pick_toys,
                    'remark'=>$remark,
                    'send_time'=>$send_time
                );
            }
        }
        //查询配送员
        $res_postman = M('user')
            ->where(" state='0' and user_role in ('4','6') and id not in (1,182,296113,307167,95371,674,426894,309806,421524,427215,427592) ")
            ->field('id,user_name')
            ->select();
        $this->assign('res_postman',$res_postman);
        
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }


    //仓库送货中
    public function toysordering() {
        $this->checkSession(); 
        $user_id=I("user_id");
        $search_address=I("search_address");
        $search_postman_id=I("postman_id");
        $excel_state=I("excel_state");
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" ((o.status=5  and l.status=5 ) or (o.status=10 and l.status=7  ) ) and o.state='0' and a.state='0' and a.is_defalut='1' and l.state='0' and l.postman_id>0 and o.toys_number<>''  ";
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        if($search_postman_id) {
            $where2.=" and l.postman_id=$search_postman_id ";
            $this->assign('search_postman_id',$search_postman_id);
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
                    ->field("o.user_id,max(o.create_time) update_time,l.postman_id,a.user_name,a.mobile,a.address ")
                    ->group("o.user_id")
                    ->order("update_time desc ")
                    ->select();
            $xlsData=array();
            if($getexcelUserRes) {
                $xlsName="送货中";
                $xlsCell  = array(
                    array('user_id','用户id'),
                    array('user_info','配送信息'),
                    array('send_toys','待配送'),
                    array('pick_toys','待取回'),
                    array('remark','备注'),
                    array('postman','配送员')   
                );
                foreach ($getexcelUserRes as $key => $value) {
                    $tmp_user_id=$value['user_id'];
                    $postman_id=$value['postman_id'];
                    $order_user_name=$value['user_name'];
                    $order_mobile=$value['mobile'];
                    $order_address=$value['address'];
                    $user_info=$send_toys=$pick_toys="";
                    if($order_user_name) {
                        $user_info.=$order_user_name;
                    }
                    if($postman_id>0) {
                        $getpostmanRes=M('user')
                            ->where("id=$postman_id ")
                            ->field('nick_name')
                            ->find();
                        $getpostman=$getpostmanRes['nick_name']?$getpostmanRes['nick_name']:"";
                    }
                    if($getpostman) {
                        $postman=$getpostman;
                    } else {
                        $postman="";
                    }
                    if($order_mobile) {
                        $user_info.="\n".$order_mobile;
                    }
                    if($order_address) {
                        $user_info.="\n".$order_address;
                    }
                    $send_toys=$pick_toys="";
                    $returnsendInfo=$this->returnsendInfo("o.status in (5) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) and o.toys_number<>'' ","\n");
                    if(empty($returnsendInfo) ) {
                        $tmp_remark="";
                    } else {
                        $send_toys=$returnsendInfo['send_toys'];
                        $tmp_remark=$returnsendInfo['remark'];
                    }
                    $checkBatRes=M("toys_order")
                            ->where("user_id=$tmp_user_id and is_prize='2' and is_battery='1' and state='0' and status in (5) ")
                            ->field("combined_order_id,battery_brand ")
                            ->select();
                    if($checkBatRes) {
                        foreach ($checkBatRes as $k=>$va) {
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
                        ->where("o.status in (5) and o.user_id=$tmp_user_id and o.is_prize='1' and o.state='0' ")
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
                    $returnpickInfo=$this->returnpickInfo("o.status in (10) and o.state='0' and o.user_id=$tmp_user_id ","\n");
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                    }
                    
                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id,
                        'user_info'=>$user_info,
                        'send_toys'=>$send_toys,
                        'pick_toys'=>$pick_toys,
                        'remark'=>$remark,
                        'postman'=>$postman
                    );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                        ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                        ->where($where2)
                        ->field('o.user_id')
                        ->group("o.user_id")
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as l on l.order_id=o.id")
                    ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
                    ->where($where2)
                    ->field("o.user_id,max(o.create_time) update_time,l.postman_id,a.user_name,a.mobile,a.address ")
                    ->group("o.user_id")
                    ->order("update_time desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_user_id=$value['user_id'];
                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];
                $order_address=$value['address'];
                $postman_id=$value['postman_id'];
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
                if($postman_id>0) {
                    $getpostmanRes=M('user')
                        ->where("id=$postman_id ")
                        ->field('nick_name')
                        ->find();
                    $getpostman=$getpostmanRes['nick_name']?$getpostmanRes['nick_name']:"";
                }
                if($getpostman) {
                    $postman=$getpostman;
                } else {
                    $postman="";
                }
                $send_toys=$pick_toys="";
                //配送开始
                $returnsendInfo=$this->returnsendInfo("o.status in (5) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) and o.toys_number<>'' ","<br/>");
                if(empty($returnsendInfo) ) {
                    $tmp_remark="";
                } else {
                    $send_toys=$returnsendInfo['send_toys'];
                    $tmp_remark=$returnsendInfo['remark'];
                }
                $checkBatRes=M("toys_order")
                            ->where("user_id=$tmp_user_id and is_prize='2' and is_battery='1' and state='0' and status in (5) ")
                            ->field("combined_order_id,battery_brand ")
                            ->select();
                if($checkBatRes) {
                    foreach ($checkBatRes as $k=>$va) {
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
                    ->where("o.status in (5) and o.user_id=$tmp_user_id and o.is_prize='1' and o.state='0' ")
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
                    'postman'=>$postman,
                    'postman_id'=>$postman_id
                );
            }
        }
        //查询配送员
        $res_postman = M('user')
            ->where(" state='0' and user_role in ('4','6') and id not in (1,182,296113,306187) ")
            ->field('id,user_name')
            ->select();
        $this->assign('res_postman',$res_postman);
        
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //仓库玩乐中
    public function toysorderfun(){
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.state='0' and o.status in (6)  ";
        $ascsql = "o.create_time asc";
        $model = new Model();
        $order_id=trim(I("order_id"));

        $excel_state=I("excel_state");
        if($order_id) {
            $where2.=" and o.id=$order_id ";
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%' ";
            $this->assign('order_num',$order_num);
        }

        $user_id=trim(I("user_id"));
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }

        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2.=" and o.post_create_time>='$start_time' and o.post_create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.post_create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }
        $toys_number = I("toys_number");
        if($toys_number){
            $where2.=" and o.toys_number='$toys_number' ";
            $this->assign('toys_number',$toys_number);
        }

        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->where($where2)
            ->field('o.id,o.order_num,o.post_create_time,o.delivery_time,o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.payment,b.way,o.total_price,o.trade_no,o.address,o.combined_order_id,o.toys_number,o.is_ready,o.remark')
            ->order("$ascsql")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        foreach ($res as $key => $value) {
            $tmp_nick_name=$value['nick_name']?$value['nick_name']:"";
            $res[$key]['nick_name']=$tmp_nick_name;

            $tmp_mobile=$value['mobile']?$value['mobile']:"";
            $res[$key]['mobile']=$tmp_mobile;

            $tmp_ord_user_name=$value['ord_user_name']?$value['ord_user_name']:"";
            $res[$key]['ord_user_name']=$tmp_ord_user_name;

            $tmp_ord_mobile=$value['ord_mobile']?$value['ord_mobile']:"";
            $res[$key]['ord_mobile']=$tmp_ord_mobile;
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //仓库待入库
    public function toysorderput(){
        $str_card_info=$this->getCardInfo();
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="  o.business_id not in ($str_card_info) and o.status=7 and o.toys_number<>'' and i.is_use not in (2) and o.is_prize in (0,3,6)  ";
        $model = new Model();
        $order_id=trim(I("order_id"));
        if($order_id) {
            $where2.=" and o.id=$order_id  ";
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%'  ";
            $this->assign('order_num',$order_num);
        }

        $user_id=trim(I("user_id"));
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }

        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2.=" and o.post_create_time>='$start_time' and o.post_create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.post_create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }
        $toys_number = I("toys_number");
        if($toys_number){
            $where2.=" and o.toys_number='$toys_number' ";
            $this->assign('toys_number',$toys_number);
        }


        //满足条件的总记录数

        $count  = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_business_listing as i on o.toys_number=i.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_business_listing as i on o.toys_number=i.id")
            ->where($where2)
            ->field('o.id,o.order_num,o.post_create_time,o.delivery_time,o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.payment,b.way,o.total_price,o.trade_no,o.address,o.combined_order_id,o.toys_number,o.is_ready,o.remark')
            ->order("o.create_time asc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        foreach ($res as $key => $value) {
            $tmp_nick_name=$value['nick_name']?$value['nick_name']:"";
            $res[$key]['nick_name']=$tmp_nick_name;

            $tmp_mobile=$value['mobile']?$value['mobile']:"";
            $res[$key]['mobile']=$tmp_mobile;

            $tmp_ord_user_name=$value['ord_user_name']?$value['ord_user_name']:"";
            $res[$key]['ord_user_name']=$tmp_ord_user_name;

            $tmp_ord_mobile=$value['ord_mobile']?$value['ord_mobile']:"";
            $res[$key]['ord_mobile']=$tmp_ord_mobile;
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //维修-待入库
    public function toysorderputrepair(){
        $str_card_info=$this->getCardInfo();
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="  o.business_id not in ($str_card_info) and o.status=7 and o.toys_number<>'' and i.is_use in (2)  ";
        $model = new Model();
        $order_id=trim(I("order_id"));
        if($order_id) {
            $where2.=" and o.id=$order_id  ";
            $this->assign('order_id',$order_id);
        }
        $order_num=trim(I("order_num"));
        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%'  ";
            $this->assign('order_num',$order_num);
        }

        $user_id=trim(I("user_id"));
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        $business_id=trim(I("business_id"));
        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }

        $start_time=I("start_time");
        $end_time=I("end_time");
        if($start_time && $end_time) {
            $where2.=" and o.post_create_time>='$start_time' and o.post_create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.post_create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }
        $toys_number = I("toys_number");
        if($toys_number){
            $where2.=" and o.toys_number='$toys_number' ";
            $this->assign('toys_number',$toys_number);
        }


        //满足条件的总记录数

        $count  = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_business_listing as i on o.toys_number=i.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_business_listing as i on o.toys_number=i.id")
            ->where($where2)
            ->field('o.id,o.order_num,o.post_create_time,o.delivery_time,o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.payment,b.way,o.total_price,o.trade_no,o.address,o.combined_order_id,o.toys_number,o.is_ready,o.remark')
            ->order("o.create_time asc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        foreach ($res as $key => $value) {
            $tmp_nick_name=$value['nick_name']?$value['nick_name']:"";
            $res[$key]['nick_name']=$tmp_nick_name;

            $tmp_mobile=$value['mobile']?$value['mobile']:"";
            $res[$key]['mobile']=$tmp_mobile;

            $tmp_ord_user_name=$value['ord_user_name']?$value['ord_user_name']:"";
            $res[$key]['ord_user_name']=$tmp_ord_user_name;

            $tmp_ord_mobile=$value['ord_mobile']?$value['ord_mobile']:"";
            $res[$key]['ord_mobile']=$tmp_ord_mobile;
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    // 查看玩具图片和玩具组成
    public function toyspicture(){
        $this->checkSession();

        $business_id = I("business_id");

        $res = M("toys_business")
            ->where("id=$business_id")
            ->field("business_img")
            ->find();
        $img = $res['business_img'];
        $img = explode(";",$img);
//        var_dump($img);
        if($img[0]){
            $this->assign('yes',1);
            $this->assign('img',$img);
        }


        $this->display();
    }

    //玩具首页分类
    public function toysindexclass(){
        $this->checkSession();
        $res_class = M("toys_index")
            ->where("state='0' ")
            ->order("rank asc")
            ->select();

        $myRes = array();

        foreach($res_class as $key=> $value){
            $id = $value['id']?$value['id']:"";//id
            $class_title = $value['class_title']?$value['class_title']:"";//分类标题
            $class_more_title = $value['class_more_title']?$value['class_more_title']:"";//更多分类标题
            $category_id = $value['category_id']?$value['category_id']:"";//分类id
            $show_type = $value['show_type']?$value['show_type']:"";//显示样式（1：一张；  2：三张并列； 3：一拖四；）
            switch($show_type){
                case 1:
                    $show_type = "一张图";
                    break;
                case 2:
                    $show_type = "三张并列";
                    break;
                case 3:
                    $show_type = "一拖三";
                    break;
                case 4:
                    $show_type = "四张两行";
                    break;
            }
//            $source = $value['source']?$value['source']:"";//跳转目的（1：玩具详情； 2：列表； 3：外链；）
//            switch($source){
//                case 1:
//                    $source = "玩具详情";
//                    break;
//                case 2:
//                    $source = "玩具列表";
//                    break;
//                case 3:
//                    $source = "外部链接";
//            }
//            $web_link = $value['web_link']?$value['web_link']:"";//外链地址
            $rank = $value['rank']?$value['rank']:"";//排序
            $post_create_time = $value['post_create_time']?$value['post_create_time']:"";//修改时间
            /*$business_id1 = $value['business_id1']?$value['business_id1']:"";//玩具ID1
            $business_id2 = $value['business_id2']?$value['business_id2']:"";//玩具ID2
            $business_id3 = $value['business_id3']?$value['business_id3']:"";//玩具ID3
            $business_id4 = $value['business_id4']?$value['business_id4']:"";//玩具ID4
            $business_id5 = $value['business_id5']?$value['business_id5']:"";//玩具ID5
            $img_1 = $value['img_1']?'http://api.meimei.yihaoss.top/'.$value['img_1']:"";//玩具图片1
            $img_2 = $value['img_2']?'http://api.meimei.yihaoss.top/'.$value['img_2']:"";//玩具图片2
            $img_3 = $value['img_3']?'http://api.meimei.yihaoss.top/'.$value['img_3']:"";//玩具图片3
            $img_4 = $value['img_4']?'http://api.meimei.yihaoss.top/'.$value['img_4']:"";//玩具图片4
            $img_5 = $value['img_5']?'http://api.meimei.yihaoss.top/'.$value['img_5']:"";//玩具图片5
            */
            $myRes["$key"] = array(
                'id' => $id,
                'class_title' => $class_title,
                'class_more_title' => $class_more_title,
                'show_type' => $show_type,
//                'source' => $source,
//                'web_link' => $web_link,
                'rank' => $rank,
                'post_create_time' => $post_create_time,
                'category_id' => $category_id/*,
                'business_id1' => $business_id1,
                'business_id2' => $business_id2,
                'business_id3' => $business_id3,
                'business_id4' => $business_id4,
                'business_id5' => $business_id5,
                'img_1' => $img_1,
                'img_2' => $img_2,
                'img_3' => $img_3,
                'img_4' => $img_4,
                'img_5' => $img_5*/
            );

            if($category_id){
                //$category_name_res = M('toys_category')->where('category_id='.$category_id)->field("title")->find();
                $category_name_res = M('toys_category_top')
                                ->where('category_id2='.$category_id)
                                ->field("title")->find();
                $myRes["$key"]['category_name'] = $category_name_res['title'];
            }else{
                $myRes["$key"]['category_name'] = "跳转全部";
            }

            /*if($business_id1){
                $res_one = M('toys_business')->where('id='.$business_id1)->field("business_title,category_ids")->find();
                $business_title = $res_one['business_title'];//玩具标题
                $myRes["$key"]['business_title1'] = $business_title;
                $category_ids = $res_one['category_ids'];
                $category_ids_arr = explode(',',$category_ids);
                if(in_array(106,$category_ids_arr)){//星标玩具
                    $myRes["$key"]['star1'] = "（小）";
                }else{
                    $myRes["$key"]['star1'] = "（大） ";
                }
            }

            if($business_id2){
                $res_one = M('toys_business')->where('id='.$business_id2)->field("business_title,category_ids")->find();
                $business_title = $res_one['business_title'];//玩具标题
                $myRes["$key"]['business_title2'] = $business_title;
                $category_ids = $res_one['category_ids'];
                $category_ids_arr = explode(',',$category_ids);
                if(in_array(106,$category_ids_arr)){//星标玩具
                    $myRes["$key"]['star2'] = "（小）";
                }else{
                    $myRes["$key"]['star2'] = "（大） ";
                }
            }

            if($business_id3){
                $res_one = M('toys_business')->where('id='.$business_id3)->field("business_title,category_ids")->find();
                $business_title = $res_one['business_title'];//玩具标题
                $myRes["$key"]['business_title3'] = $business_title;
                $category_ids = $res_one['category_ids'];
                $category_ids_arr = explode(',',$category_ids);
                if(in_array(106,$category_ids_arr)){//星标玩具
                    $myRes["$key"]['star3'] = "（小）";
                }else{
                    $myRes["$key"]['star3'] = "（大） ";
                }
            }

            if($business_id4){
                $res_one = M('toys_business')->where('id='.$business_id4)->field("business_title,category_ids")->find();
                $business_title = $res_one['business_title'];//玩具标题
                $myRes["$key"]['business_title4'] = $business_title;
                $category_ids = $res_one['category_ids'];
                $category_ids_arr = explode(',',$category_ids);
                if(in_array(106,$category_ids_arr)){//星标玩具
                    $myRes["$key"]['star4'] = "（小）";
                }else{
                    $myRes["$key"]['star4'] = "（大） ";
                }
            }
            if($business_id5){
                $res_one = M('toys_business')->where('id='.$business_id5)->field("business_title,category_ids")->find();
                $business_title = $res_one['business_title'];//玩具标题
                $myRes["$key"]['business_title5'] = $business_title;
                $category_ids = $res_one['category_ids'];
                $category_ids_arr = explode(',',$category_ids);
                if(in_array(106,$category_ids_arr)){//星标玩具
                    $myRes["$key"]['star5'] = "（小）";
                }else{
                    $myRes["$key"]['star5'] = "（大） ";
                }
            }*/
        }

        $this->assign('res',$myRes);
        $this->display();
    }

    //编辑玩具首页分类
    public function toysindexclassedit(){
        $this->checkSession();
        $id = $_GET['id'];
        $res_class = M("toys_index")
            ->where("state='0' and id='$id' ")
            ->find();

        $myRes = array();

            $id = $res_class['id']?$res_class['id']:"";//id
            $class_title = $res_class['class_title']?$res_class['class_title']:"";//分类标题
            $class_more_title = $res_class['class_more_title']?$res_class['class_more_title']:"";//更多分类标题
            $category_id = $res_class['category_id']?$res_class['category_id']:"0";//分类id
            $is_active = $res_class['is_active']?$res_class['is_active']:"0";//是否是一元卡指定玩具
            $show_type = $res_class['show_type']?$res_class['show_type']:"";//显示样式（1：一张；  2：三张并列； 3：一拖三；四张换行）
            $source1 = $res_class['source1']?$res_class['source1']:"";//跳转目的（1：玩具详情； 2：列表； 3：外链；）
            $source2 = $res_class['source2']?$res_class['source2']:"";//跳转目的（1：玩具详情； 2：列表； 3：外链；）
            $source3 = $res_class['source3']?$res_class['source3']:"";//跳转目的（1：玩具详情； 2：列表； 3：外链；）
            $source4 = $res_class['source4']?$res_class['source4']:"";//跳转目的（1：玩具详情； 2：列表； 3：外链；）
            $source5 = $res_class['source5']?$res_class['source5']:"";//跳转目的（1：玩具详情； 2：列表； 3：外链；）
            $web_link1 = $res_class['web_link1']?$res_class['web_link1']:"";//外链地址
            $web_link2 = $res_class['web_link2']?$res_class['web_link2']:"";//外链地址
            $web_link3 = $res_class['web_link3']?$res_class['web_link3']:"";//外链地址
            $web_link4 = $res_class['web_link4']?$res_class['web_link4']:"";//外链地址
            $web_link5 = $res_class['web_link5']?$res_class['web_link5']:"";//外链地址
            $rank = $res_class['rank']?$res_class['rank']:"";//排序
            $business_id1 = $res_class['business_id1']?$res_class['business_id1']:"";//玩具ID1
            $business_id2 = $res_class['business_id2']?$res_class['business_id2']:"";//玩具ID2
            $business_id3 = $res_class['business_id3']?$res_class['business_id3']:"";//玩具ID3
            $business_id4 = $res_class['business_id4']?$res_class['business_id4']:"";//玩具ID4
            $business_id5 = $res_class['business_id5']?$res_class['business_id5']:"";//玩具ID5
            $img_1 = $res_class['img_1']?'http://api.meimei.yihaoss.top/'.$res_class['img_1']:"";//玩具图片1
            $img_2 = $res_class['img_2']?'http://api.meimei.yihaoss.top/'.$res_class['img_2']:"";//玩具图片2
            $img_3 = $res_class['img_3']?'http://api.meimei.yihaoss.top/'.$res_class['img_3']:"";//玩具图片3
            $img_4 = $res_class['img_4']?'http://api.meimei.yihaoss.top/'.$res_class['img_4']:"";//玩具图片4
            $img_5 = $res_class['img_5']?'http://api.meimei.yihaoss.top/'.$res_class['img_5']:"";//玩具图片5

        $business_title1 = "";
        $business_title2 = "";
        $business_title3 = "";
        $business_title4 = "";
        $business_title5 = "";

        if($business_id1){
            $res_one = M('toys_business')->where('id='.$business_id1)->field("business_title,category_ids")->find();
            $business_title1 = $res_one['business_title'];//玩具标题
        }

        if($business_id2){
            $res_one = M('toys_business')->where('id='.$business_id2)->field("business_title,category_ids")->find();
            $business_title2 = $res_one['business_title'];//玩具标题
        }

        if($business_id3){
            $res_one = M('toys_business')->where('id='.$business_id3)->field("business_title,category_ids")->find();
            $business_title3 = $res_one['business_title'];//玩具标题
        }

        if($business_id4){
            $res_one = M('toys_business')->where('id='.$business_id4)->field("business_title,category_ids")->find();
            $business_title4 = $res_one['business_title'];//玩具标题
        }

        if($business_id5){
            $res_one = M('toys_business')->where('id='.$business_id5)->field("business_title,category_ids")->find();
            $business_title5 = $res_one['business_title'];//玩具标题
        }

            $myRes= array(
                'id' => $id,
                'class_title' => $class_title,
                'class_more_title' => $class_more_title,
                'show_type' => $show_type,
                'rank' => $rank,
                'source1' => $source1,
                'source2' => $source2,
                'source3' => $source3,
                'source4' => $source4,
                'source5' => $source5,
                'web_link1' => $web_link1,
                'web_link2' => $web_link2,
                'web_link3' => $web_link3,
                'web_link4' => $web_link4,
                'web_link5' => $web_link5,
                'category_id' => $category_id,
                'business_id1' => $business_id1,
                'business_id2' => $business_id2,
                'business_id3' => $business_id3,
                'business_id4' => $business_id4,
                'business_id5' => $business_id5,
                'img_1' => $img_1,
                'img_2' => $img_2,
                'img_3' => $img_3,
                'img_4' => $img_4,
                'img_5' => $img_5,
                'business_title1' =>$business_title1,
                'business_title2' =>$business_title2,
                'business_title3' =>$business_title3,
                'business_title4' =>$business_title4,
                'business_title5' =>$business_title5,
                'is_active'=>$is_active
            );

//var_dump($myRes);

        $this->assign('res',$myRes);

//        $cate_info=M("toys_category_top")
//            ->field('title,category_id2 as category_id')
//            ->where("state=0")
//            ->select();
        $cate_info=M("toys_category")
            ->field('title,category_id')
            ->where("state=0")
            ->select();
        $this->assign('cate_info',$cate_info);

        $this->display();
    }

    //删除玩具
    public function toysindexclassdel(){
        $id = $_GET['id'];
        $album = M('toys_index');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysindexclass.html");
    }

    //玩具会员卡首页展示
    public function toysindexcard(){
        $this->checkSession();
        $res_class = M("toys_index_card")
            ->where("state='0' ")
            ->order("rank asc")
            ->select();

        $myRes = array();

        foreach($res_class as $key=> $value){
            $id = $value['id']?$value['id']:"";//id
            $rank = $value['rank']?$value['rank']:"";//排序
            $card_id = $value['card_id']?$value['card_id']:"";//会员卡ID
            $img = $value['img']?'http://api.meimei.yihaoss.top/'.$value['img']:"";//会员卡图片
            $myRes["$key"] = array(
                'id' => $id,
                'rank' => $rank,
                'card_id' => $card_id,
                'img' => $img
            );



        }

        $this->assign('res',$myRes);
        $this->display();
    }

    //编辑玩具会员卡首页展示
    public function toysindexcardedit(){
        $this->checkSession();
        $id = $_GET['id'];
        $res_class = M("toys_index_card")
            ->where("state='0' and id='$id' ")
            ->find();

        $myRes = array();

        $id = $res_class['id']?$res_class['id']:"";//id
        $rank = $res_class['rank']?$res_class['rank']:"";//排序
        $card_id = $res_class['card_id']?$res_class['card_id']:"";//会员卡id
        $img = $res_class['img']?'http://api.meimei.yihaoss.top/'.$res_class['img']:"";//会员卡图片

        $myRes= array(
            'id' => $id,
            'rank' => $rank,
            'card_id' => $card_id,
            'img' => $img
        );

        $this->assign('res',$myRes);

        $this->display();
    }

    //编辑非会员前端展示客服手机号
    public function toysheadmobile(){
        $this->checkSession();
        $res_class = M("toys_prize_activity")
            ->where(" id='2' ")
            ->find();
        $mobile = $res_class['remark'];

        $this->assign('mobile',$mobile);

        $this->display();
    }

    //删除玩具会员卡首页展示
    public function toysindexcarddel(){
        $id = $_GET['id'];
        $album = M('toys_index_card');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysindexcard.html");
    }

    //首页banner展示
    public function toysindexbanner(){
        $this->checkSession();
        $res_class = M("toys_index_banner")
            ->where("state='0' ")
            ->order("rank asc")
            ->select();

        $myRes = array();

        foreach($res_class as $key=> $value){
            $id = $value['id']?$value['id']:"";//id
            $rank = $value['rank']?$value['rank']:"";//排序
            $img_id = $value['img_id']?$value['img_id']:"";//帖子id
            $img = $value['img']?'http://api.meimei.yihaoss.top/'.$value['img']:"";//会员卡图片
            $source = $value['source']?$value['source']:"";//跳转目的
            switch($source){
                case 1:
                    $source = "帖子";
                    break;
                case 2:
                    $source = "外链地址";
                    break;
                case 3:
                    $source = "玩具列表";
                    break;
                case 4:
                    $source = "玩具详情";
                    break;
            }
            $web_link = $value['web_link']?$value['web_link']:"";//外链地址
            $myRes["$key"] = array(
                'id' => $id,
                'rank' => $rank,
                'img_id' => $img_id,
                'img' => $img,
                'source' => $source,
                'web_link' => $web_link
            );



        }

        $this->assign('res',$myRes);
        $this->display();
    }

    //编辑首页banner展示
    public function toysindexbanneredit(){
        $this->checkSession();
        $id = $_GET['id'];
        $res_class = M("toys_index_banner")
            ->where("state='0' and id='$id' ")
            ->find();

        $myRes = array();

        $id = $res_class['id']?$res_class['id']:"";//id
        $rank = $res_class['rank']?$res_class['rank']:"";//分类标题
        $img_id = $res_class['img_id']?$res_class['img_id']:"";//会员卡ID
        $img = $res_class['img']?'http://api.meimei.yihaoss.top/'.$res_class['img']:"";//会员卡图片
        $source = $res_class['source']?$res_class['source']:"";//跳转目的
        $web_link = $res_class['web_link']?$res_class['web_link']:"";//外链地址
        $myRes = array(
            'id' => $id,
            'rank' => $rank,
            'img_id' => $img_id,
            'img' => $img,
            'source' => $source,
            'web_link' => $web_link
        );

        $this->assign('res',$myRes);

        $cate_info=M("toys_category")
            ->field('title,category_id')
            ->where("state=0")
            ->select();
        $this->assign('cate_info',$cate_info);

        $this->display();
    }

    //删除首页banner展示
    public function toysindexbannerdel(){
        $id = $_GET['id'];
        $album = M('toys_index_banner');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysindexbanner.html");
    }

    //延期配送次数增加详情
    public function getonecardinfo() {
        $id=I("id");
        if(empty($id)){
            die("非法操作");
        }
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_card_listing')
            ->where(" card_id=$id and state='0' ")
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_card_listing')
            ->where(" card_id=$id and state='0' ")
            ->order("id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $card_id=$value['card_id'];
                $type = $value['type'];
                $card_num = $value['card_num'];
                $card_day = $value['card_day'];
                $remark = $value['remark'];

                if($type==1){
                    $type_name = "延期";
                    $type_val = $card_day;
                }elseif($type==2){
                    $type_name = "配送";
                    $type_val = $card_num;
                }else{
                    $type_name = "";
                    $type_val = "";
                }

                $res[]=array(
                    'id'=>$id,
                    'create_time'=>$create_time,
                    'type_name'=>$type_name,
                    'type_val'=>$type_val,
                    'remark'=>$remark
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //选择玩具编号
    public function toysimgnum(){
        $this->checkSession();

        $order_id = I("order_id");
        $this->assign('order_id' , $order_id);

        $user_id = I("user_id");
        $this->assign('user_id',$user_id);

        $business_id = I("business_id");

        $delivery_time = I("delivery_time");
        if(!$delivery_time){
            $delivery_time = " ";
        }
        $this->assign('delivery_time',$delivery_time);
//        $checkres = M("toys_business_listing")
//            ->where("business_id=$business_id and state='0' and (is_use='0' or is_use='5') and out_state='0' ")
//            ->field('id,business_id')
//            ->order("id asc")
//            ->select();
//        if($checkres) {
//            foreach ($checkres as $value) {
//                $toys_number = $value['id'];//玩具编号ID
//                $business_id = $value['business_id'];
//                $orderCount = M("toys_order")
//                        ->where("business_id=$business_id and state='0' and toys_number=$toys_number and status in (2,5,6,7,10,14) ")
//                        ->count();
//                if($orderCount>0) {
//                    $up_where['id']=$toys_number;
//                    $updata['post_create_time'] = date("Y-m-d H:i:s") ;
//                    $updata['out_state'] = '1' ;
//                    M("toys_business_listing")->where($up_where)->data($updata)->save();
//                }
//            }
//        }
        $status = I("status");

        $res = array();

        $res = M("toys_business_listing")
            ->where("business_id=$business_id and state='0' and (is_use='0' or is_use='5' or is_use='4' or is_use='8') and out_state='0' ")
            ->order("id asc")
            ->select();
        $remark_list = array();
        $remark_state = 0;

        if($res) {
            foreach($res as  $key=>$value){
                $id = $value['id'];//玩具编号ID
                $is_use = $value['is_use'];//4预约
                if($is_use==4){
                    $name_name = "预约";
                }elseif($is_use==8){
                    $name_name = "活动占用";
                }else{
                    $name_name = "仓库";
                }

                $new_old = $value['new_old'];
                if($new_old==1){
                    $new_name = "-入库新增";
                }elseif($new_old==2){
                    $new_name = "-出库新增";
                }else{
                    $new_name = "";
                }
                $business_id = $value['business_id']?$value['business_id']:'';//玩具ID
                $tag_id = $value['tag_id']?$value['tag_id']:'';//玩具第几件
                $toys_number = $id."-".$business_id."-".$tag_id;//玩具完整编号
                $remark = $value['remark']?$value['remark']:'';//备注
                if($remark){
                    $remark_state = 1;
                    $remark_list[] = "编号：".$id."--".$remark;
                }
                $warehouse = $value['warehouse']?$value['warehouse']:$name_name;//仓库名称
                $room = $value['room']?$value['room']:''; //房间
                $storage_rack = $value['storage_rack']?$value['storage_rack']:'';//货架
                $number = $value['number']?$value['number']:'';//层数
                $city_name = $value['city_name']?$value['city_name']:'';//市
                $county_name = $value['county_name']?$value['county_name']:'';//县
                $area_name = $value['area_name']?$value['area_name']:'';//区
                $address = $city_name.$county_name.$area_name.$warehouse.$room.$storage_rack.$number;
                $toys_number_address = $toys_number."-".$address.$new_name;
                $res["$key"]['toys_number_address'] = $toys_number_address;
            }
        }

        $res1 = M("toys_business")
            ->where("id=$business_id")
            ->field("business_pics")
            ->find();
        $img = $res1['business_pics'];
        $img = explode(";",$img);
        $this->assign('img',$img);

//var_dump($res);

        if($res && ($status==2) ){
            $is_have=1;
            $this->assign("is_have",$is_have);
        }else{
            $is_have=0;
            $this->assign("is_have",$is_have);
        }
//        print_r($remark_list);die;
        $this->assign("remark_state",$remark_state);
        $this->assign("remark_list",$remark_list);
        $this->assign("business_id",$business_id);
        $this->assign("res",$res);
        $this->display();
    }

    //编辑玩具订单列表页面
    public function toysremarkup(){
        $order_id=I("order_id");
        $user_id = I("user_id");
        if(empty($user_id)){
            $remark_o = M('toys_order')->where('id='.$order_id)->field('user_id,is_prize,toys_number,status,card_id')->find();
            $user_id = $remark_o['user_id'];
            $is_prize = $remark_o['is_prize'];
            $toys_number = $remark_o['toys_number']?$remark_o['toys_number']:0;

            $card_id = $remark_o['card_id'];
            $status = $remark_o['status'];

            if( in_array($is_prize,array(0,3)) && ($card_id==0) && in_array($status,array(2,5,17,6,14)) ){
                $add_status = 1;
            }else{
                $add_status = 0;
            }

            $this->assign('add_status',$add_status);
        }


            $remark_oo = M('user')->where('id='.$user_id)->field('nick_name')->find();
            $nick_name = $remark_oo['nick_name'];

//var_dump($remark_oo);
        if(!empty($toys_number)){
            $number_res = M('toys_business_listing')->where('id='.$toys_number)->field('order_remark')->find();
            $order_remark = $number_res['order_remark']?$number_res['order_remark']:"";
            $this->assign('order_remark',$order_remark);
        }

        $model = new Model();
        $listing_res = $model->table('baby_toys_listing as i')
            ->join(" left join baby_user as u on i.postman_id=u.id")
            ->where('i.order_id='.$order_id)//." and i.state='0' "
            ->field('i.*,u.nick_name')
            ->select();
        $this->assign('listing_res',$listing_res);

//var_dump($toys_number);
        $this->assign('is_prize',$is_prize);
        $this->assign('user_id',$user_id);
        $this->assign('toys_number',$toys_number);
        $remark_r = M('toys_user_remark')->where("user_id= '$user_id' and state='0' ")->field('remark')->find();
        $remark = $remark_r['remark'];
        $this->assign('remark',$remark);//原先的备注
        $this->assign('order_id',$order_id);
        $this->assign('nick_name',$nick_name);
        $this->display('toysremarkup');
    }

    //编辑玩具订单列表页面
    public function updateToysorder(){
        $order_id=I("id");
        if($order_id) {
            $orderList = M('toys_order')->where('id='.$order_id)->find();            
        }
        $this->assign('orderList',$orderList);      
        $this->display('updateToysorder');
    }
    //账单列表
    public function accountlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['state'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_account')
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_account')
                    ->where($where2)
                    ->field('id,user_id,order_id,account_type,status,price')
                    ->order('post_create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
                    /*`account_type` int(11) DEFAULT '1' COMMENT '类型（1租金{或卖价格}【0未消费、1已消费、3已退】、2服务费【0未消费、1已消费、3已退】、3押金【0未消费、1已消费{转余额}、2冻结、3已退】、4余额/提现【0未消费{余额}、1已消费、2申请提现、3已完成 】、5超时押金、6退款【0未消费、1已消费、4退至余额】）',
  `status` int(11) DEFAULT '1' COMMENT '状态（0未消费、1已消费、2冻结、3已退、4退款至余额）',*/
        foreach ($list as $key => $value) {
            $tmp_id=$value['id'];
            $tmp_user_id=$value['user_id'];
            $tmp_order_id=$value['order_id'];
            $tmp_account_type=$value['account_type'];
            $tmp_status=$value['status'];
            $tmp_price=$value['price'];
            if($tmp_account_type==1) {
                if($tmp_status==0) {
                    $type_name="价格-未消费";
                } elseif($tmp_status==1) {
                    $type_name="价格-已消费";
                } elseif($tmp_status==3) {
                    $type_name="价格-已退款";
                }
            } elseif($tmp_account_type==2) {
                if($tmp_status==0) {
                    $type_name="服务费-未消费";
                } elseif($tmp_status==1) {
                    $type_name="服务费-已消费";
                } elseif($tmp_status==3) {
                    $type_name="服务费-已退款";
                }
            } elseif($tmp_account_type==3) {
                if($tmp_status==0) {
                    $type_name="押金-未消费";
                } elseif($tmp_status==1) {
                    $type_name="押金-已消费{转余额}";
                } elseif($tmp_status==2) {
                    $type_name="押金-冻结";
                } elseif($tmp_status==3) {
                    $type_name="押金-已退款";
                }
            } elseif($tmp_account_type==4) {
                if($tmp_status==0) {
                    $type_name="余额-未消费{余额}";
                } elseif($tmp_status==1) {
                    $type_name="余额-已消费";
                } elseif($tmp_status==2) {
                    $type_name="余额-申请提现";
                } elseif($tmp_status==3) {
                    $type_name="余额-已完成";
                }
            } elseif($tmp_account_type==5) {
                if($tmp_status==1) {
                    $type_name="超时-已消费";
                }
            } elseif($tmp_account_type==6) {
                if($tmp_status==0) {
                    $type_name="退款-未消费";
                } elseif($tmp_status==1) {
                    $type_name="退款-已消费";
                } elseif($tmp_status==4) {
                    $type_name="退款-退款至余额";
                } elseif($tmp_status==5) {
                    $type_name="退款-转押金";
                }
            }

            $res[]=array(
                'id'=>$tmp_id,
                'user_id'=>$tmp_user_id,
                'order_id'=>$tmp_order_id,
                'type_name'=>$type_name,
                'price'=>$tmp_price
            );
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    //邀请用户列表
    public function invitationlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['i.state'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_invitation as i')
                        ->join(" left join baby_user as u on i.user_id=u.id")
                        ->join(" left join baby_user as s on i.invite_user_id=s.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_invitation as i')
                    ->join(" left join baby_user as u on i.user_id=u.id")
                    ->join(" left join baby_user as s on i.invite_user_id=s.id")
                    ->where($where2)
                    ->field('i.id,i.user_id,i.invite_user_id,u.nick_name as user_name,s.nick_name as invite_user_name')
                    ->order('i.id desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    //删除
    public function operator_invitationstate(){        
        $imgid = $_GET['imgid'];
        $album = M('invitation');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/invitationlist.html");
    }
    //退款押金列表
    public function refunddepositlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $model = new Model();
        $status = I("status");
        if(empty($status)){
            $status = 3;
        }
        $this->assign('status',$status);
        //满足条件的总记录数
        $count  = $model->table('baby_toys_deposit')
                        ->where("state='0' and pay_type='2' and status='$status' ")
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        // //wx_source 0不是微信支付，1微信，3余额、4商业支付宝
        $list = $model->table('baby_toys_deposit as a')
                    ->join(" left join baby_user as u on a.user_id=u.id")
//                    ->join(" left join baby_user as u on a.user_id=u.id")
                    ->where("a.state='0' and pay_type='2' and status='$status' ")
                    ->field('a.id,a.user_id,a.price,a.status,a.create_time,a.post_create_time,u.nick_name,a.remark')
                    ->order('a.create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        foreach ($list as $key => $value) {
            $tmp_id=$value['id'];
            $tmp_user_id=$value['user_id'];
            $tmp_nick_name=$value['nick_name'];
            $user_info = $tmp_user_id."--".$tmp_nick_name;
            $tmp_create_time = $value['create_time'];
            $tmp_post_create_time = $value['post_create_time'];
            $tmp_price=$value['price'];
            $tmp_status=$value['status'];
            if($tmp_status==3){
                $tmp_status_name="押金提现-已申请";
                $tmp_post_create_time = "未到账";
            }elseif($tmp_status==7){
                $tmp_status_name="押金提现-已退款";
            }

            $remark = $value['remark'];
            $res[]=array(
                'id'=>$tmp_id,
                'user_id'=>$user_info,
                'user_id_no'=>$tmp_user_id,
                'price'=>$tmp_price,
                'create_time'=>$tmp_create_time,
                'post_create_time'=>$tmp_post_create_time,
                'tmp_status_name'=>$tmp_status_name,
                'remark'=>$remark
            );
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }

    //退款账单列表
    public function refundaccountlist(){
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $model = new Model();
        //满足条件的总记录数
//        $count  = $model->table('baby_account')
//            ->where("state='0' and ((account_type='6' and status='0') or (account_type='4' and status='2') ) ")
//            ->count();
        $count  = $model->table('baby_toys_order')
            ->where("state='0' and status=8 and is_prize not in (4,5) and ((service_price>0) or (sell_price>0) ) ")
            ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        // //wx_source 0不是微信支付，1微信，3余额、4商业支付宝
//        $list = $model->table('baby_account as a')
//            ->join(" left join baby_user as u on a.user_id=u.id")
////                    ->join(" left join baby_user as u on a.user_id=u.id")
//            ->where("a.state='0' and ((a.account_type='6' and a.status='0') or (a.account_type='4' and a.status='2') ) ")
//            ->field('a.id,a.user_id,a.order_id,a.account_type,a.status,a.price,a.create_time,u.nick_name,a.wx_source')
//            ->order('a.create_time desc')
//            ->limit($page->firstRow,$page->listRows)
//            ->select();

        $list = $model->table('baby_toys_order as a')
            ->join(" left join baby_user as u on a.user_id=u.id")
            ->where("a.state='0' and a.status=8 and a.is_prize not in (4,5) and ((a.service_price>0) or (a.sell_price>0) ) ")
            ->field('a.id,a.user_id,a.status,a.sell_price,a.service_price,a.post_create_time,u.nick_name,a.payment,a.trade_no')
            ->order('a.post_create_time desc')
            ->limit($page->firstRow,$page->listRows)
            ->select();

        foreach ($list as $key => $value) {
            $tmp_user_id=$value['user_id'];
            $tmp_nick_name=$value['nick_name'];
            $user_info = $tmp_user_id."--".$tmp_nick_name;
            $tmp_order_id=$value['id'];
            $tmp_payment=$value['payment'];
            $tmp_post_create_time = $value['post_create_time'];
            $tmp_trade_no = $value['trade_no'];

            $tmp_wx_source_name = "";
            if($tmp_payment==1){
                $tmp_wx_source_name = "退款中(个人支付宝)";
            }elseif($tmp_payment==2){
                $tmp_wx_source_name = "退款中(微信)";
            }elseif($tmp_payment==3){
                $tmp_wx_source_name = "退款中(余额)";
            }elseif($tmp_payment==4){
                $tmp_wx_source_name = "退款中(商业支付宝)";
            }

            $tmp_status=$value['status'];
            $tmp_service_price = $value['service_price'];
            $tmp_sell_price = $value['sell_price'];
            $tmp_price=$tmp_service_price + $tmp_sell_price;//租赁费+服务费

            $res[]=array(
                'user_id'=>$user_info,
                'order_id'=>$tmp_order_id,
                'type_name'=>"退款中",
                'price'=>$tmp_price,
                'post_create_time'=>$tmp_post_create_time,
                'trade_no'=>$tmp_trade_no
            );
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //编辑账单
    public function editAccount(){
        $account_id=I('id');
        $model = new Model();
        if($account_id>0) {
            $accountRes=M('account')
                        ->where("id=$account_id ")
                        ->field('id,user_id,order_id,account_type,status,price,trade_no,wx_source,source')
                        ->find();
            $account_user_id=$accountRes['user_id'];
            $account_order_id=$accountRes['order_id'];
            $account_account_type=$accountRes['account_type'];
            $account_status=$accountRes['status'];
            $account_price=$accountRes['price'];
            $account_source=$accountRes['source'];
            if($account_source==0) {//来自玩具
                $account_trade_no=$accountRes['trade_no'];
                $account_wx_source=$accountRes['wx_source'];
            } else {//来自押金
                $account_trade_no=$account_wx_source="";
            }
            if(($account_account_type==4) && ($account_status==2) ) {
                $account_type_name="余额-申请提现";
            } elseif( ($account_account_type==6) && ($account_status==0) ) {
                $account_type_name="退款-未消费";
            }
            if($account_wx_source==3) {
                $source_name="余额";
            } elseif($account_wx_source==1) {
                $source_name="微信";
            } elseif(($account_wx_source==0) && ($account_trade_no) ) {
                $source_name="支付宝";
            } elseif($account_wx_source==4) {
                $source_name="企业-支付宝";
            }
            if($account_source==0) {
                if($account_order_id>0) {
                    //商家标题
                    $busRes = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_business as b on b.id=o.business_id ")
                        ->where("o.id=$account_order_id ")
                        ->field('b.business_title')
                        ->find();
                    $business_title=$busRes['business_title'];
                } else {
                    $business_title="";
                }
            } else {
                $business_title="押金";
            }
            $res=array(
                'id'=>$account_id,
                'account_type'=>$account_account_type,
                'type_name'=>$account_type_name,
                'price'=>$account_price,
                'trade_no'=>$account_trade_no,
                'source_name'=>$source_name,
                'business_title'=>$business_title
            );

            $this->assign('res',$res);//赋值数据集
        }        
        $this->display();   
    }
    
    //玩具banner列表
    public function toysheadlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        //$where2['online'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_head")                    
                ->where($where2)
                ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $list = M("toys_head")
            ->where($where2)
            ->field('id,img,type')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $album_img = "http://api.meimei.yihaoss.top/".$value['img'];
            $business_type = intval($value['type']);
            //3话题帖子,9玩具列表,10玩具详情
            if($business_type==3){
                $name = "来自帖子";
            }elseif($business_type==5){
                $name = "外链";
            }elseif($business_type==9){
                $name = "玩具列表";
            }elseif($business_type==10){
                $name = "玩具详情";
            }                   
            $res[]=array(
            'id'=>$id,
            'img'=>$album_img,
            'name'=>$name
            );
        }
    
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();  
    }
    //新版玩具banner列表
    public function toysheadlistnew(){
        $this->checkSession();
        import("ORG.Util.Page");
        $where2['state'] ='0';
        //$where2['online'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_head_new")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $list = M("toys_head_new")
            ->where($where2)
            ->field('id,img,type')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $album_img = "https://api.meimei.yihaoss.top/".$value['img'];
            $business_type = intval($value['type']);
            //3话题帖子,9玩具列表,10玩具详情
            if($business_type==3){
                $name = "来自帖子";
            }elseif($business_type==5){
                $name = "外链";
            }elseif($business_type==9){
                $name = "玩具列表";
            }elseif($business_type==10){
                $name = "玩具详情";
            }
            $res[]=array(
                'id'=>$id,
                'img'=>$album_img,
                'name'=>$name
            );
        }

        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除玩具banner信息
    public function operator_toys_head(){        
        $id = $_GET['id'];
        $album = M('toys_head');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysheadlist.html");
    }
    //删除新版玩具banner信息
    public function operator_toys_headnew(){
        $id = $_GET['id'];
        $album = M('toys_head_new');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysheadlistnew.html");
    }

    public function toyshead(){
        $businessList = M('toys_category')
                    ->field('category_id as id,title')
                    ->where("state=0")
                    ->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    public function toysheadnew(){
        $businessList = M('toys_category')
            ->field('category_id as id,title')
            ->where("state=0")
            ->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }

    //新版玩具参数编辑
    public function toysselectinfo(){
        $this->checkSession();
        $id=I("id");
        $this->assign('id',$id);

        $res = M('toys_business')
            ->field("toys_brand,toys_age,toys_ability,toys_type,business_title")
            ->where("id=$id")
            ->find();
        $this->assign('res',$res);
        $this->display();
    }

    //玩具一级分类列表
    public function toyscategorytoplist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_category_top")                    
                ->where($where2)
                ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $list = M("toys_category_top")
            ->where($where2)
            ->field('id,title,class_id,category_id2')
            ->limit($page->firstRow,$page->listRows)
            ->order("id desc ")
            ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $title = $value['title'];
            $class_id = $value['class_id'];
            $category_id2 = $value['category_id2'];
            if($class_id==1) {//1年龄、2热门、3功能
                $title1="年龄";
            } elseif($class_id==2) {
                $title1="热门";
            } elseif($class_id==3) {
                $title1="功能";
            } else {
                $title1="";
            }
            if($category_id2==999) {
                $title2="童车（电动车、摩托车、自行车、三轮车、扭扭车）";
            } elseif($category_id2==998) {
                $title2="学习益智（积木、学习桌、婴儿感知、绘本）";
            } elseif($category_id2==997) {
                $title2="家庭乐园（滑梯、围栏、学习桌、百变乐园）";
            } elseif($category_id2==996) {
                $title2="场景模拟（轨道车、场景模拟、模型）";
            } elseif($category_id2==995) {
                $title2="户外和运动（户外、手推车、学步车）";
            } else {
                $catelist2 = M("toys_category")
                        ->where("category_id=$category_id2 ")
                        ->field('title')
                        ->find();
                $title2=$catelist2['title']?$catelist2['title']:"";
            }
            $res[]=array(
            'id'=>$id,
            'title'=>$title,
            'title1'=>$title1,
            'title2'=>$title2
            );
        }
    
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();  
    }
    
    //删除玩具分类信息
    public function operator_toys_category_top(){        
        $id = $_GET['id'];
        $album = M('toys_category_top');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toyscategorytoplist.html");
    }
    //添加/编辑玩具分类
    public function toyscategorytop(){
        $this->checkSession();
        $id = $_GET['id'];
        $res=array();
        if($id>0) {
            $where2['id'] = array('in',$id);
            $list = M('toys_category_top')
                ->field('id,title,class_id,category_id2')
                ->where($where2)
                ->find();
            $tmp_category_id2=$list['category_id2'];
            if($tmp_category_id2>=1000) {
                $category_id3=$tmp_category_id2;
                $category_id2=0;
            } else {
                $category_id2=$tmp_category_id2;
                $category_id3=0;
            }
            $res=array(
                'id'=>$list['id'],
                'title'=>$list['title'],
                'class_id'=>$list['class_id'],
                'category_id2'=>$category_id2,
                'category_id3'=>$category_id3
            );
        }
        $category_info = M("toys_category")
                ->field('category_id as id,title')
                ->where("state='0' ")
                ->select();
        $this->assign("category_info",$category_info);
        $this->assign("res",$res);
        $this->display();
    }
    
    //玩具二级分类列表
    public function toyscategorylist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_category")                    
                ->where($where2)
                ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $list = M("toys_category")
            ->where($where2)
            ->field('id,title,category_id,rank,img')
            ->limit($page->firstRow,$page->listRows)
            ->order("id desc ")
            ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $title = $value['title'];
            $category_id=$value['category_id'];
            $rank = $value['rank'];
            $img = "http://api.meimei.yihaoss.top/".$value['img'];
            $res[]=array(
                'id'=>$id,
                'title'=>$title,
                'category_id'=>$category_id,
                'rank' => $rank,
                'img' => $img
            );
        }
    
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();  
    }
    
    //删除玩具二级分类信息
    public function operator_toys_category(){        
        $id = $_GET['id'];
        $album = M('toys_category');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toyscategorylist.html");
    }
    //添加/编辑玩具二级分类
    public function toyscategory(){
        $this->checkSession();
        $id = $_GET['id'];
        $res=array();
        if($id>0) {
            $where2['id'] = array('in',$id);
            $list = M('toys_category')
                ->where($where2)
                ->find();

            $res=array(
                'id'=>$list['id'],
                'title'=>$list['title'],
                'rank'=>$list['rank'],
                'category_id'=>$list['category_id'],
                'img' => "http://api.meimei.yihaoss.top/".$list['img'],
                'is_show' => $list['is_show'],
                'parent_id'=>$list['parent_id']
            );
        }   
        $this->assign("res",$res);

        //该分类下面的所有玩具
        $toys_info = array();
        if($id){
            $toys_info = M("toys_business")
                ->field('id,business_title,business_pics')
                ->where("state='0' and root_img_id='0' and is_show='0' and category_ids like '%".$list['category_id']."%' ")
                ->select();
            if($toys_info) {
                foreach ($toys_info as $key => $value) {
                    $bu_id=$value['id'];

                    $getBusinessRes=$this->getBusinessTotalNum($bu_id);
                    $total_number=$getBusinessRes['total_count'];
                    $toys_number=$getBusinessRes['use_count'];
                    if($toys_number<=0) {
                        $toys_number=0;
                    }
                    $toys_info[$key]['total_number']=$total_number;
                    $toys_info[$key]['toys_number']=$toys_number;
                }
            }
        }
        $this->assign("toys_info",$toys_info);
        $this->display();
    }

    //置换玩具
    public function toysexchange(){
        $this->checkSession();


        $toys_info = array();

            $toys_info = M("toys_exchange")
                ->where("state<>2")
                ->order("state asc , create_time asc ")
                ->select();
        foreach($toys_info as &$toys){

//玩具状态
            switch($toys['status']){

                case 1:
                    $toys['status'] = "全新-未使用";
                    break;
                case 2:
                    $toys['status'] = "9成新-功能良好，配件齐全";
                    break;
                case 3:
                    $toys['status'] = "7-8成新 有损伤，不影响使用";
                    break;
                case 4:
                    $toys['status'] = "3-6成新 零件丢失，可配";
                    break;
                case 5:
                    $toys['status'] = "零件完好，可维修";
                    break;
                case 6:
                    $toys['status'] = "其它";
                    break;
                default:
                    $toys['status'] = "未知";
            }
//购买年限
            switch($toys['buy_time_now']){

                case 1:
                    $toys['buy_time_now'] = "3个月以内";
                    break;
                case 2:
                    $toys['buy_time_now'] = "3-6个月";
                    break;
                case 3:
                    $toys['buy_time_now'] = "6-12个月";
                    break;
                case 4:
                    $toys['buy_time_now'] = "12-18个月";
                    break;
                case 5:
                    $toys['buy_time_now'] = "18-24个月";
                    break;
                case 6:
                    $toys['buy_time_now'] = "两年以上";
                    break;
                default:
                    $toys['buy_time_now'] = "未知";
            }

            $toys['toys_pics'] = explode(';',$toys['toys_pics']);

        }

        $this->assign("toys_info",$toys_info);
        $this->display();
    }
    //玩具参数
    public function toysparams(){
        $this->checkSession();
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        $where2 = " state='0' and root_img_id='0' and id not in($str_card_info) ";
        $business_id = I('business_id');
        $business_title = I('business_title');
        $search_state = intval(I('search_state'));
        $where2.=" and is_show='$search_state' ";
        $this->assign('search_state',$search_state);
        if(!empty($business_id)){
            $this->assign('business_id',$business_id);
            $where2.= " and id='$business_id' ";
        }
        if(!empty($business_title)){
            $this->assign('business_title',$business_title);
            $where2.= " and ( (business_title like '%$business_title%') or (key_words like '%$business_title%') or (business_brand like '%$business_title%') ) ";
        }

        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_business")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $list = M("toys_business")
            ->where($where2)
            ->field('id,business_title,age,business_pics,business_brand,weight,size,key_words,battery_number1,battery_number2,battery_number3,battery_number4,battery_number5,battery_number6,battery_number7,battery_number8,category_ids,toys_age,toys_type,toys_ability,toys_brand')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();
        foreach($list as $key=>$value){
            $id= $value['id'];
            $business_title = $value['business_title'];
            $business_pics = $value['business_pics'];
            $business_brand = $value['business_brand'];
            $weight = $value['weight'];
            $size = $value['size'];
            $key_words = $value['key_words'];
            $toys_brand = $value['toys_brand'];
            $toys_age = $value['toys_age'];
            $toys_type = $value['toys_type'];
            $toys_ability = $value['toys_ability'];
            $age = $value['age'];
            $battery_number1 = $value['battery_number1'];
            $battery_number2 = $value['battery_number2'];
            $battery_number3 = $value['battery_number3'];
            $battery_number4 = $value['battery_number4'];
            $battery_number5 = $value['battery_number5'];
            $battery_number6 = $value['battery_number6'];
            $battery_number7 = $value['battery_number7'];
            $battery_number8 = $value['battery_number8'];
            if($battery_number1 || $battery_number2 || $battery_number3 || $battery_number4 || $battery_number5 || $battery_number6 || $battery_number7 || $battery_number8){
                $battery_number = 1;
            }else{
                $battery_number = 0;
            }

            $tmp_category_ids = $value['category_ids'];
            $toys_size=0;
            if($tmp_category_ids) {
                $arr_category_ids=explode(",", $tmp_category_ids);
                if(in_array("106",$arr_category_ids) ) {
                    $toys_size="1";
                }
            } 
            if($toys_size==1) {
                $size_img_thumb="http://api.meimei.yihaoss.top/static3/album/2017/0517/0d96a3aec4cb0d6d.jpg";
            } else {
                $size_img_thumb="";
            }
            $res["$key"]['id'] = $id;
            $res["$key"]['business_title'] = $business_title;
            $res["$key"]['business_brand'] = $business_brand;
            $res["$key"]['weight'] = $weight;
            $res["$key"]['size'] = $size;
            $business_pics_arr = explode(";",$business_pics);
            $res["$key"]['business_pic'] = "http://api.meimei.yihaoss.top/".$business_pics_arr[0];
            $res["$key"]['battery_number'] = $battery_number;
            $res["$key"]['key_words'] = $key_words;
            $res["$key"]['size_img_thumb'] = $size_img_thumb;
            $res["$key"]['toys_type'] = $toys_type;
            $res["$key"]['toys_ability'] = $toys_ability;
            $res["$key"]['toys_age'] = $toys_age;
            $res["$key"]['toys_brand'] = $toys_brand;
            $res["$key"]['age'] = $age;
        }

        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    } 

    //玩具电池参数
    public function toysbattery(){
        $this->checkSession();
        $model = new Model();
        $id = $_GET['id'];
        $where2 = " id='$id' ";

        $list = M("toys_business")
            ->where($where2)
            ->field('id,battery_number1,battery_number2,battery_number3,battery_number4,battery_number5,battery_number6,battery_number7,battery_number8')
            ->find();

        $res=array();


            $id= $list['id'];
            $battery_number1 = $list['battery_number1'];
            $battery_number2 = $list['battery_number2'];
            $battery_number3 = $list['battery_number3'];
            $battery_number4 = $list['battery_number4'];
            $battery_number5 = $list['battery_number5'];
            $battery_number6 = $list['battery_number6'];
            $battery_number7 = $list['battery_number7'];
            $battery_number8 = $list['battery_number8'];

            $res['id'] = $id;
            $res['battery_number1'] = $battery_number1;
            $res['battery_number2'] = $battery_number2;
            $res['battery_number3'] = $battery_number3;
            $res['battery_number4'] = $battery_number4;
            $res['battery_number5'] = $battery_number5;
            $res['battery_number6'] = $battery_number6;
            $res['battery_number7'] = $battery_number7;
            $res['battery_number8'] = $battery_number8;


        $this->assign("res",$res);

        $this->display();
    }
    //会员卡信息编辑
    public function cardeditinfo(){
        $this->checkSession();

        $user_id = I('user_id');
        $this->assign('user_id',$user_id);
        //---------------------------------------------------------------------------------------
        $cardinfo1 = array();

        $now_time = date("Y-m-d 00:00:00");
        $card = M();
        $sql = " SELECT * FROM baby_toys_card WHERE user_id='$user_id' AND state = '0' AND status!='5'  ORDER BY id ";//AND (case when final_end_time>end_time then final_end_time else end_time end > '$now_time' OR start_time='0000-00-00 00:00:00')
        $cardinfo1 = $card->query($sql);


        //卡名（1月卡、2季卡、3半年卡、4一年卡、5周卡）card_name

        foreach($cardinfo1 as &$value) {

            //卡名
            switch ($value['card_name']) {
                case 1:
                    $value['card_name'] = "月卡";
                    break;
                case 2:
                    $value['card_name'] = "季卡";
                    break;
                case 3:
                    $value['card_name'] = "半年卡";
                    break;
                case 4:
                    $value['card_name'] = "年卡";
                    break;
                case 5:
                    $value['card_name'] = "周卡";
                    break;
                default:
                    $value['card_name'] = "未知";

            }
        }


        foreach($cardinfo1 as &$value){
            //      卡状态（0未启用、1已启用、2已过期、3周卡失效、4停卡、5合并）
//var_dump($value['status']);
            switch($value['status']){

                case 0:
                    $value['status'] = "未启用";
                    break;
                case 1:
                    $value['status'] = "已启用";
                    break;
                case 2:
                    $value['status'] = "已过期";
                    break;
                case 3:
                    $value['status'] = "周卡失效";
                    break;
                case 4:
                    $value['status'] = "停卡";
                    break;
                case 5:
                    $value['status'] = "合并";
                    break;
                default:
                    $value['status'] = "未知";

            }
        }
        foreach($cardinfo1 as &$value){
            //卡剩余天数
            $start_time = strtotime($value['start_time']);
            $end_time = strtotime($value['end_time']);
            $final_end_time = strtotime($value['final_end_time']);
            $create_time = strtotime($value['create_time']);
            $six_first = strtotime("2017-06-01 00:00:00");
            $card_day = $value['card_day'];
            if(($create_time<$six_first) && ($card_day<700) ){
                $value['service_money'] = "5元/每次";
            }else{
                $value['service_money'] = "8元/每次";
            }
            if($value['id'] == 1729){
                $value['service_money'] = "5元/每次";
            }
            if($value['id'] == 2313){
                $value['service_money'] = "5元/每次";
            }

            if($value['id']>3385){
                $value['service_money'] = "15元/每次";
            }
            if($value['id']==3391){//429119    599卡9折，服务费超出8元   刚买的539的会员卡，转给13910508144手机号
                $value['service_money'] = "8元/每次";
            }
            if($value['id']==3405){//432841  这个用户分配499的卡，服务费超出是8元一次
                $value['service_money'] = "8元/每次";
            }
            if($value['id']>4169){// test
                $value['service_money'] = "20元/每次";
            }


            //未启用
            if($value['start_time'] == '0000-00-00 00:00:00'){
                $value['card_have'] = $value['card_day'];
                $value['end_time_now'] = '0000-00-00 00:00:00';
            }
            if(($value['final_end_time']=='0000-00-00 00:00:00') && ($value['start_time'] != '0000-00-00 00:00:00')){
                $value['card_have'] = ceil(($end_time - time())/86400);
                $value['end_time_now'] = $value['end_time'];

                $end_time_s = $value['end_time'];
                //过期截至期限
                //天
                $timestamp_diff = strtotime($end_time_s)-strtotime(date('Y-m-d h:i:s'));
                $end_day = intval($timestamp_diff/86400);
                //时
                $time_temp = $timestamp_diff%86400;
                $end_hour = intval($time_temp/3600);
                //分
                $time_temp = $time_temp%3600;
                $end_min = intval($time_temp/60);
                //秒
                $end_sec = $time_temp%60;
                if($timestamp_diff>0){
                    $end_time_diff = $end_day."天".$end_hour."时".$end_hour."分".$end_sec."秒";
                }else{
                    $end_time_diff = '已过期';
                }
                $value['card_have'] = $end_time_diff;

            }
            if(($value['final_end_time']!='0000-00-00 00:00:00') && ($value['start_time'] != '0000-00-00 00:00:00')){
                $value['card_have'] = ceil(($final_end_time - time())/86400);
                $value['end_time_now'] = $value['final_end_time'];

                $end_time_s = $value['final_end_time'];
                //过期截至期限
                //天
                $timestamp_diff = strtotime($end_time_s)-strtotime(date('Y-m-d h:i:s'));
                $end_day = intval($timestamp_diff/86400);
                //时
                $time_temp = $timestamp_diff%86400;
                $end_hour = intval($time_temp/3600);
                //分
                $time_temp = $time_temp%3600;
                $end_min = intval($time_temp/60);
                //秒
                $end_sec = $time_temp%60;
                if($timestamp_diff>0){
                    $end_time_diff = $end_day."天".$end_hour."时".$end_hour."分".$end_sec."秒";
                }else{
                    $end_time_diff = '已过期';
                }
                $value['card_have'] = $end_time_diff;

            }
        }
        //---------------------------------------------------------------------------------------


        $cardinfo = array();

        $now_time = date("Y-m-d 00:00:00");
        $card = M();
        $sql = " SELECT * FROM baby_toys_card WHERE user_id='$user_id' AND state = '0' AND status!='5' AND (case when final_end_time>end_time then final_end_time else end_time end > '$now_time' OR start_time='0000-00-00 00:00:00') ORDER BY id ";
        $cardinfo = $card->query($sql);


        //卡名（1月卡、2季卡、3半年卡、4一年卡、5周卡）card_name

        foreach($cardinfo as &$value) {

            //卡名
            switch ($value['card_name']) {
                case 1:
                    $value['card_name'] = "月卡";
                    break;
                case 2:
                    $value['card_name'] = "季卡";
                    break;
                case 3:
                    $value['card_name'] = "半年卡";
                    break;
                case 4:
                    $value['card_name'] = "年卡";
                    break;
                case 5:
                    $value['card_name'] = "周卡";
                    break;
                default:
                    $value['card_name'] = "未知";

            }
        }


            foreach($cardinfo as &$value){
                //      卡状态（0未启用、1已启用、2已过期、3周卡失效、4停卡、5合并）
//var_dump($value['status']);
                switch($value['status']){

                    case 0:
                        $value['status'] = "未启用";
                        break;
                    case 1:
                        $value['status'] = "已启用";
                        break;
                    case 2:
                        $value['status'] = "已过期";
                        break;
                    case 3:
                        $value['status'] = "周卡失效";
                        break;
                    case 4:
                        $value['status'] = "停卡";
                        break;
                    case 5:
                        $value['status'] = "合并";
                        break;
                    default:
                        $value['status'] = "未知";

                }
            }
        foreach($cardinfo as &$value){
            //卡剩余天数
            $start_time = strtotime($value['start_time']);
            $end_time = strtotime($value['end_time']);
            $final_end_time = strtotime($value['final_end_time']);
            $create_time = strtotime($value['create_time']);
            $six_first = strtotime("2017-06-01 00:00:00");
            $card_day = $value['card_day'];
            if(($create_time<$six_first) && ($card_day<700) ){
                $value['service_money'] = "5元/每次";
            }else{
                $value['service_money'] = "8元/每次";
            }
            //未启用
            if($value['start_time'] == '0000-00-00 00:00:00'){
                $value['card_have'] = $value['card_day'];
                $value['end_time_now'] = '0000-00-00 00:00:00';
            }
            if(($value['final_end_time']=='0000-00-00 00:00:00') && ($value['start_time'] != '0000-00-00 00:00:00')){
                $value['card_have'] = ceil(($end_time - time())/86400);
                $value['end_time_now'] = $value['end_time'];
            }
            if(($value['final_end_time']!='0000-00-00 00:00:00') && ($value['start_time'] != '0000-00-00 00:00:00')){
                $value['card_have'] = ceil(($final_end_time - time())/86400);
                $value['end_time_now'] = $value['final_end_time'];
            }
        }



        $this->assign("cardinfo1",$cardinfo1);
        $this->assign("cardinfo",$cardinfo);
        $this->display();
    }

    //编辑审核备注
    public function update_remark(){

        $id=I("id");
        $toys_info = M("toys_exchange")
            ->where("id=$id")
            ->find();

        $this->assign('toys_info',$toys_info);


        $this->display();
    }

    //客服退款编辑 （逾期、玩具损坏）
    public function editservice(){
        
        $this->display();
    }



    //首页模块列表
    public function modulelist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where2['state'] ='0';
        //满足条件的总记录数
        $count  = M("toys_module")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $list = M("toys_module")
                ->where($where2)
                ->field('id,img,type,is_default,sort')
                ->limit($page->firstRow,$page->listRows)
                ->select();
        
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $sort = $value['sort'];
            $is_default = $value['is_default'];
            $album_img = "http://api.meimei.yihaoss.top/".$value['img'];
            
            $business_type = intval($value['type']);
            //1帖子列表、2帖子、3视频、21商家列表、22商家详情、31群列表、32群详情,41外链、42热门、43萌宝、44成长记录、51玩具分类、52玩具详情、53玩具首页
            if($business_type==1){
                $name = "帖子列表";
            }elseif($business_type==2){
                $name = "帖子";
            }elseif($business_type==3){
                $name = "视频";
            }elseif($business_type==21){
                $name = "商家列表";
            }elseif($business_type==22){
                $name = "商家详情";
            }elseif($business_type==31){
                $name = "群列表";
            }elseif($business_type==32){
                $name = "群详情";
            }elseif($business_type==41){
                $name = "外链";
            }elseif($business_type==42){
                $name = "热门";
            }elseif($business_type==43){
                $name = "萌宝";
            }elseif($business_type==44){
                $name = "成长记录";
            }elseif($business_type==45){
                $name = "个人群详情";
            }elseif($business_type==51){
                $name = "玩具首页";
            }elseif($business_type==52){
                $name = "玩具详情";
            }elseif($business_type==53){
                $name = "玩具分类";
            }                   
            $res[]=array(
            'id'=>$id,
            'img'=>$album_img,
            'name'=>$name,
            'sort'=>$sort,
            'type'=>$business_type
            );
        }
    
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();  
    }
    //删除首页模块信息
    public function operator_module(){        
        $id = $_GET['id'];
        $album = M('toys_module');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/modulelist.html");
    }
    public function module(){
        $special_id=I("id");
        if($special_id) {
            $res = M('toys_module')->where('id='.$special_id)->find();
            $type=$res['type'];
            $tmp_img_id=$res['img_id'];
            $show_img_id1= 0;
            if(($type=='1') || ($type=='31')) {
                $show_img_id1= 1;
                $img_id1= $tmp_img_id;
                $img_id= 0;
                $is_img_id=0;
            } elseif(($type=='2') || ($type=='3') || ($type=='22') || ($type=='32') || ($type=='53') || ($type=='52') ) {
                $img_id = $tmp_img_id;
                $img_id1= 0;
                $is_img_id=1;
            } else {
                $img_id1= 0;
                $img_id = 0;
                $is_img_id=0;
            }
            $res['is_img_id']=$is_img_id;
            $res['img_id']=$img_id;
            $res['img_id1']=$img_id1;
            $res['show_img_id1']=$show_img_id1;
            $img=$res['img'];
            if($img) {
                $res['img']="http://api.meimei.yihaoss.top/".$img;
            }
        }
        $this->assign('res',$res);//编辑信息
        $top_label=M("listing_label")
                ->field('label1 as label_name,label1_s as label_id')
                ->where("state=0")
                ->group("label1_s")
                ->select();
        if($top_label) {
            foreach ($top_label as $key => $value) {
                $label_id=intval($value['label_id']);
                if($label_id>0) {
                    $second_label=M("listing_label")
                            ->field('label2 as label_name,label2_s as label_id')
                            ->where("state=0 and label1_s=$label_id")
                            ->select();
                    if($second_label) {
                        $top_label[$key]['category']=$second_label;
                    }
                }
                
            }
        }
        $this->assign('top_label',$top_label);//帖子分类
        $this->display();
    }
    //玩具添加卡订单
    public function webPuToysOrder(){
        $this->display();
    }
    //玩具编号列表
    public function toysnumberlist(){
        $this->checkSession();    
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $id = $_GET['id'];
        $this->assign('business_id_now',$id);

        $act_ing=0;
        $act_ing = $_GET['act_ing']; //批量放库存操作
        if($act_ing==1){
            $where2['is_use'] ='7';
        }
        $this->assign('act_ing',$act_ing);
        $num = $this->getBusinessTotalNum($id);
//        $busList= M('toys_business')
//            ->field('total_number,number')
//            ->where('id='.$id)
//            ->find();
//        $total_number=$busList['total_number'];
        $total_number = $num['total_count'];

        $where2['state'] ='0';
        $where2['business_id'] =$id;
        $id_search = $_GET["id_search"];
        if($id_search){
            $where2['id'] =$id_search;
        }
        $this->assign('id_search',$id_search);
        $model = M("toys_business_listing");
        //满足条件的总记录数
        $count=$model
            ->where($where2)
            ->count();
        $this->assign('count',$count);
        $page = new Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $res =$model
            ->where($where2)
            ->order('is_use asc,id desc')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        if($res) {
            foreach ($res as $key => $value) {
                $business_listing_id = $value['id'];
                $player_id = $value['player_id'];
                if($player_id){
                    $user_player_res= M('user')
                        ->field('user_name')
                        ->where('id='.$player_id)
                        ->find();
                    $player_name = $user_player_res['user_name'];
                }else{
                    $player_name = "---";
                }
                $res[$key]['player_name']=$player_name;
                $business_listing_res= M('toys_order')
                    ->field('status')
                    ->where('toys_number='.$business_listing_id)
                    ->order('post_create_time desc')
                    ->find();
                $status = $business_listing_res['status'];
                if($status==2) {
                    $status_name="待出库";
                } elseif($status==5) {
                    $status_name="送货中";
                } elseif($status==6) {
                    $status_name="玩乐中";
                } elseif($status==7) {
                    $status_name="待入库";
                } elseif($status==8) {
                    $status_name="退款中";
                } elseif($status==9) {
                    $status_name="已退款";
                } elseif($status==10) {
                    $status_name="待取回";
                } elseif($status==11) {
                    $status_name="已入库";
                } elseif($status==12) {
                    $status_name="已过期";
                } elseif($status==13) {
                    $status_name="已失效";
                } elseif($status==14) {
                    $status_name="恢复玩乐";
                } elseif($status==16) {
                    $status_name="问题订单";
                }elseif($status==17) {
                    $status_name="重新配送";
                }else {
                    $status_name="未出库";
                }

                $tmp_is_use=$value['is_use'];//0可用，1不可用【待上新】、2维修
                $res[$key]['bus_number']=$value['id']."-".$value['business_id']."-".$value['tag_id'];
                if($tmp_is_use==4) {
                    $tmp_use_name="预约";
                } elseif($tmp_is_use==3) {
                    $tmp_use_name="未找到";
                } elseif($tmp_is_use==2) {
                    $tmp_use_name="维修";
                } elseif($tmp_is_use==1) {
                    $tmp_use_name="不可用";
                }elseif($tmp_is_use==5) {
                    $tmp_use_name="部分损坏不影响使用";
                }elseif($tmp_is_use==6) {
                    $tmp_use_name="完全报废";
                }elseif($tmp_is_use==7) {
                    $tmp_use_name="不可用-new";
                }elseif($tmp_is_use==8) {
                    $tmp_use_name="活动预留";
                }elseif($tmp_is_use==9) {
                    $tmp_use_name="售卖";
                } else {
                    $tmp_use_name="可用";
                }
                $res[$key]['use_name']=$tmp_use_name;
                $res[$key]['status_name']=$status_name;
                $purchase_id=$value['purchase_id'];
                if($purchase_id>0) {
                    $busPurList= M('toys_business_purchasing')
                            ->field('purchase_time')
                            ->where('id='.$purchase_id)
                            ->find();
                    $purchase_time=$busPurList['purchase_time'];
                } else {
                    $purchase_time="";
                }
                $res[$key]['purchase_time']=$purchase_time;
            }
        }
        $this->assign("business_id",$id);
        $this->assign("res",$res);
        $this->assign("total_number",$total_number);
        $this->assign('page',$show);//赋值分页输出
        //--------------
        $res_new =$model->where("state='0' and business_id='$id' and is_use='4' ")->field('id')->select();
        $this->assign("new_ids",$res_new);

        $res_old =$model->where("state='0' and business_id='$id' and is_use='0' and out_state='0' ")->field('id')->select();
        $this->assign("old_ids",$res_old);
        //--------------

        $this->display();
    }

    public function operator_toysnumberlist(){        
        $imgid = $_GET['id'];
        $album = M('toys_business_listing'); 
        $find_where['id']=$imgid;
        $findRes=$album->where($find_where)->find();
        $find_img_id=$findRes['business_id'];      
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toysnumberlist?id=$find_img_id");
    }
    public function publictoysnumber(){        
        $business_id = $_GET['business_id'];
        $this->assign("business_id",$business_id);
        $this->display();
    }
    public function edittoysnumber(){        
        $imgid = $_GET['id'];
        $find_where['id']= $imgid;
        $findRes=M('toys_business_listing')->where($find_where)->find();
        $this->assign('res',$findRes);//赋值分页输出
        $business_id=$findRes['business_id'];
        if($business_id>0) {
            $purRes=M('toys_business_purchasing')
                ->field('id,purchase_time')
                ->where("business_id=$business_id and state='0' ")
                ->select();
            $this->assign('purRes',$purRes);
        }
        $this->display();
    }
    
    public function addtoysprize() {
        $businessList = M('toys_prize')
                    ->field('prize_id1,prize_title1')
                    ->where("state='0' ")
                    ->group("prize_id1")
                    ->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    //获取奖品列表
    public function getPrizelist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where_condition="state='0'"; 
        $post_img = M('toys_prize');
        //满足条件的总记录数
        $count  = $post_img->where($where_condition)->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $post_img
                ->where($where_condition)
                ->order('create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select(); 
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $prize_title1 = $value['prize_title1']; 
            $prize_title2 = $value['prize_title2'];
            $tmpImg= $value['img'];
            if($tmpImg) {
                $imgUrl="http://api.meimei.yihaoss.top/".$tmpImg;
            } else {
                $imgUrl="";
            }
            $is_card = $value['is_card'];
            $card_status = $value['card_status'];
            $card_day = $value['card_day'];
            $card_service = $value['card_service'];
            if($is_card==1) {
                $belong="卡";
                if($card_status==3) {
                    $card_info="延长".$card_day."天，加".$card_service."次配送";
                } elseif($card_status==2) {
                    $card_info="加".$card_service."次配送";
                } elseif($card_status==1) {
                    $card_info="延长".$card_day."天";
                } else {
                    $card_info="加一张周卡";
                }
            } else {
                $belong="奖品";
                $card_info="";
            }
            $res[]=array(
                'id'=>$id,
                'prize_title1'=>$prize_title1,
                'prize_title2'=>$prize_title2,
                'imgUrl'=>$imgUrl,
                'belong'=>$belong,
                'card_info'=>$card_info    
            );
        }
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //删除奖品
    public function operator_prizestate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_prize');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getPrizelist.html");
    }
    //编辑奖品页面
    public function editprize(){
        $this->checkSession();
        $id = $_GET['id'];
        $res = M("toys_prize")
                ->where("state='0' and id=$id ")
                ->find();
        $tmpImg=$res['img'];
        if($tmpImg) {
            $imgUrl="http://api.meimei.yihaoss.top/".$tmpImg;
        } else {
            $imgUrl="";
        }
        $res['img']=$imgUrl;
        $this->assign("res",$res);
        $this->display();
    }
    //邀请列表[获礼品或卡]
    public function getinvprizelist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $invite_user_id=I("invite_user_id");
        $where2['i.state'] ='0';
        if($invite_user_id>0) {
            $where2['i.invite_user_id'] =$invite_user_id;
            $this->assign('invite_user_id',$invite_user_id);
        }
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_invitation as i')
                        ->join(" left join baby_user as s on i.invite_user_id=s.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_invitation as i')
                    ->join(" left join baby_user as s on i.invite_user_id=s.id")
                    ->where($where2)
                    ->field('i.id,i.user_id,i.invite_user_id,s.nick_name as invite_user_name,i.status,i.prize_id,i.is_card,i.mobile')
                    ->order('i.id desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($list) {
            foreach ($list as $key => $value) {
                $id=$value['id'];
                $user_id=$value['user_id'];
                $invite_user_id=$value['invite_user_id'];
                $invite_user_name=$value['invite_user_name'];
                $status=$value['status'];
                $prize_id=$value['prize_id'];
                $is_card=$value['is_card'];
                $mobile=$value['mobile'];
                if($user_id>0) {
                    $userInfo=M("user")->field('nick_name')->where("id=$user_id")->find();
                    $user_name=$userInfo['nick_name'];
                } else {
                    $user_name="";
                }
                if($prize_id>0) {
                    $prizeInfo=M("toys_prize")->where("prize_id2=$prize_id")->find();
                    $card_status = $prizeInfo['card_status'];
                    $card_day = $prizeInfo['card_day'];
                    $card_service = $prizeInfo['card_service'];
                    $prize_title2 = $prizeInfo['prize_title2'];
                    if($status==1) {//卡
                        if($is_card==1) {
                           if($card_status==3) {
                                $prize_info="延长".$card_day."天，加".$card_service."次配送";
                            } elseif($card_status==2) {
                                $prize_info="加".$card_service."次配送";
                            } elseif($card_status==1) {
                                $prize_info="延长".$card_day."天";
                            } else {
                                $prize_info="加一张周卡";
                            } 
                        } else {
                            $prize_info="领了".$prize_title2;
                        }                        
                    }
                } else {
                    $prize_info="";
                }
                if($status==2) {
                    $status_name="已失效";
                } elseif($status==1) {
                    $status_name="已接受";
                } else {
                    $status_name="邀请中";
                }
                if($is_card==2) {
                    $card_name="玩具";
                } elseif($is_card==1) {
                    $card_name="卡";
                } else {
                    $card_name="无";
                }
                $res[]=array(
                    'id'=>$id,
                    'user_id'=>$user_id,
                    'user_name'=>$user_name,
                    'mobile'=>$mobile,
                    'invite_user_id'=>$invite_user_id,
                    'invite_user_name'=>$invite_user_name,
                    'status_name'=>$status_name,
                    'card_name'=>$card_name,
                    'prize_info'=>$prize_info,
                    );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }


    //团购列表
    public function toyscardactivity(){
        $show_time="2017-09-11 12:00:01";
        $start_time="2018-04-16 23:59:59";
        $end_time="2019-04-16 23:59:59";

        $today_start_time = date('Y-m-d 00:00:01');
        $today_end_time = date('Y-m-d 23:59:59');

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
//        $str_card_info=$this->getCardInfo();//,3028,2255,2253
        $str_card_info = "418,420,422,424,548,649,652,655,1040,1041,1137,1139,1253,1255,1336,1338,1340,1342,1416,1625,1627,1629,1631,2633,2635,2853,2902,2907,2914,2916,3026,3074,3088,3140,3164,3303,3305,3346,3307,3166,3168,3189,3201,3223,3231,3233,3242";
        $user_id=I("user_id");//用户id
        $parent_id=I("parent_id");//团长id
        $phone=I("phone");//手机号
        $search_parent_id=I("search_parent_id");//身份
        $search_invite_type=I("search_invite_type");//1：跟进；2：本期活动；3：暂不跟进；4：会员
        if($search_parent_id==3) {//自主下单
            $where2= " 1 ";
        } else {
            $where2= " ((a.create_time > '$show_time' and a.state='0' ) or a.state=2 )";
        }
        $search_remark=I("search_remark");//备注
        $ab = I('ab');//奇数偶数
        $order_AD = "a.create_time DESC";

        //除去超级伙伴购卡数据
        $where2 .= " and a.parent_id<100000000 ";

        //团购分类
        if($search_invite_type>0) {
            if($search_invite_type==2){
                $where2 .= " and a.create_time> '$start_time' and a.create_time<'$end_time' ";
            }else{
                $where2 .= " and a.invite_type= '$search_invite_type' ";
            }
            $this->assign('search_invite_type',$search_invite_type);
        }

        if($search_parent_id==1) {//团长
            $where2 .= " and a.state='0' and a.parent_id=0 ";
            $this->assign('search_parent_id',$search_parent_id);
        } elseif($search_parent_id==2) {//团员
            $where2 .= " and a.state='0' and a.parent_id>0 ";
            $this->assign('search_parent_id',$search_parent_id);
        } elseif($search_parent_id==3) {//自主下单
            $where2 .= " and (a.state='2' and a.parent_id='-1') ";
            $this->assign('search_parent_id',$search_parent_id);
        }elseif($search_parent_id==4) {//当天注册未提交过任何订单
            $where2 .= " and (a.create_time>'$today_start_time' and a.create_time<'$today_end_time' and a.parent_id='-2') ";
            $this->assign('search_parent_id',$search_parent_id);
        }elseif($search_parent_id==5) {//注册绑定
            $where2 .= " and (a.state='0' and a.parent_id='-2') ";
            $this->assign('search_parent_id',$search_parent_id);
        } else {
             $where2.= " and a.state!='1' ";
        }
        if($search_remark) {
            $where2 .= " and a.remark like '%$search_remark%' ";
            $this->assign('search_remark',$search_remark);
        }
        if($ab>0) {
            if($ab==1){
                $where2 .= " and (a.user_id%2 = 1)  ";
            }
            if($ab==2){
                $where2 .= " and (a.user_id%2 = 0)  ";
            }

            $this->assign('ab',$ab);
        }
        if($user_id>0) {
            $where2 .= " and (a.user_id='$user_id' ) ";
            $this->assign('user_id',$user_id);
        }

        if($parent_id>0) {
            $where2 .= " and (a.parent_id='$parent_id' or user_id = $parent_id ) ";
            $order_AD = "a.parent_id ASC";
            $this->assign('parent_id',$parent_id);
        }

        if($phone) {
            $where2 .= " and a.mobile='$phone'  ";
            $order_AD = "a.parent_id ASC";
            $this->assign('phone',$phone);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_activity_invitation as a')
            ->join(" left join baby_user as s on a.user_id=s.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_activity_invitation as a')
            ->join(" left join baby_user as s on a.user_id=s.id")
            ->where($where2)
            ->field('a.id,a.user_id,a.parent_id,s.nick_name,s.user_name,a.mobile,s.mobile as u_mobile,a.click_num,a.prize,a.remark,a.create_time,a.state,a.pin_prize,a.pin_prize2,a.pin_prize3,a.pin_ids,a.invite_type')
            ->order("$order_AD")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        $common_user_id_arr = array();//运营账号
        $common_user_res = $model->table('baby_common_user')
            ->where("user_id>0")
            ->field('user_id')
            ->select();
        foreach($common_user_res as $value){
            $common_user_id_arr[] = $value['user_id'];
        }
        if($list) {
            foreach ($list as $key => $value) {
                $id=$value['id'];
                $user_id=$value['user_id'];

                //运营账号
                if(in_array("$user_id",$common_user_id_arr)){
                    $is_true = 1;//运营账号
                }else{
                    $is_true = 0;//用户
                }
                $show_create_time=$value['create_time'];
                $parent_id=$value['parent_id'];
                $user_name=$value['user_name'];
                $nick_name=$value['nick_name'];
                $invite_type = $value['invite_type'];
                $mobile=$value['mobile'];
                if(($mobile==0) && ($value['u_mobile'])){
                    $mobile = $value['u_mobile'];
                }
                $click_num=$value['click_num'];
                $remark = $value['remark'];
                $prize = $value['prize'];
                $state=$value['state'];
                $pin_prize = $value['pin_prize'];
                $pin_prize2 = $value['pin_prize2'];
                $pin_prize3 = $value['pin_prize3'];
                $prize_name_3_arr = array();
                $prize_name_3_str = "";
                if($pin_prize){
                    $pin_prize3_arr = explode(',',$pin_prize);
                    foreach($pin_prize3_arr as $val){
                        $pin_prize_3 = $val;
                        switch($pin_prize_3){
                            case 1:
                                $prize_name_3 = "芭比娃娃";
                                break;
                            case 2:
                                $prize_name_3 = "变形金刚";
                                break;
                            case 3:
                                $prize_name_3 = "配送一次";
                                break;
                            case 4:
                                $prize_name_3 = "特权一次";
                                break;
                            case 5:
                                $prize_name_3 = "延期15天";
                                break;
                            case 6:
                                $prize_name_3 = "延期28天";
                                break;
                            case 7:
                                $prize_name_3 = "立减￥30";
                                break;
                            default:
                                $prize_name_3 = "";
                        }
                        $prize_name_3_arr[] = $prize_name_3;
                    }

                    $prize_name_3_str = implode('<br>',$prize_name_3_arr);
                }
                $pin_ids = $value['pin_ids'];
                $prize_name="";
                switch($prize){
                    case 1:
                        $prize_name = "芭比娃娃";
                        break;
                    case 2:
                        $prize_name = "变形金刚";
                        break;
                    case 3:
                        $prize_name = "配送一次";
                        break;
                    case 4:
                        $prize_name = "特权一次";
                        break;
                    case 5:
                        $prize_name = "延期15天";
                        break;
                    case 6:
                        $prize_name = "延期28天";
                        break;
                    case 7:
                        $prize_name = "立减￥30";
                        break;
                    default:
                        $prize_name = "";
                }
//                if($pin_prize2==1){
//                    $prize_name2 .= "+延期7天";
//                }

                $arr_card_info = explode(',',$str_card_info);
                $business_info = "";
                if($parent_id==-1) {
                    $user_info="自主下单";
                } elseif($parent_id==0) {
                    $user_info="团长";
                } else {
                    $user_info="团员";
                }
                //是否购卡
                $where_conditon=" user_id='$user_id' and state='0' and status in (2,7) and business_id in ($str_card_info)  ";
                $where_conditon=" user_id='$user_id' and state='0' and status in (2,7) and business_id in ($str_card_info)  ";
                if($show_create_time>'2017-10-13 00:00:01') {
                    $where_conditon.= " and create_time>'2017-10-13 00:00:01' ";
                } else {
                    $where_conditon.= " and create_time>'$show_time' ";
                }
//                $is_have_res=M("toys_order")->field('id')->where($where_conditon)->find();
                $now_time = date('Y-m-d H:i:s');
                $where_conditon = " user_id='$user_id' and state='0' and card_name in (2,3,4) and ( case when final_end_time>end_time then final_end_time else end_time end > '$now_time'  or status=4  OR start_time='0000-00-00 00:00:00' ) ";
                $is_have_res=M("toys_card")->field('id')->where($where_conditon)->find();


                $a_b = "";
                if($is_have_res){
                    $is_pay = "已购卡";
                    $is_super_res=M("toys_activity_invitation")->field('user_id')->where("user_id='$user_id' and state='0' and parent_id>100000000 and  create_time>'$start_time' ")->find();
                    if(!empty($is_super_res)){
                        $is_pay = "已购卡<font color='red'>（超级伙伴购卡）</font>";
                    }
                }else{
                    $is_pay = "未支付";
                    if($state==2){//自主下单
                        $business_info_res = $model->table('baby_toys_order as o ')
                            ->join(" left join baby_toys_business as b on o.business_id=b.id")
                            ->where("o.user_id='$user_id'  and b.state='0' and o.status=1  ")
                            ->field('b.business_title,o.business_id')
                            ->select();
                        foreach($business_info_res as $v){
                            $business_title = $v['business_title'];
                            $business_info.= "<br>".$business_title;
                        }
                    }
                    $jo_status = $user_id%2;
                    if($jo_status==0){
                        $a_b = "<font color='red' >--B</font> ";
                    }else{
                        $a_b = "<font color='#9013FE'>--A</font> ";
                    }

                }

//                if($prize) {
//                    $prizeInfo=M("toys_prize")
//                            ->field('prize_title2,card_day,card_service,card_status')
//                            ->where("prize_id2 in ($prize) ")
//                            ->select();
//                    if($prizeInfo) {
//                        foreach ($prizeInfo as $k=> $val) {
//                            $prize_title2=$val['prize_title2'];
//                            $card_status=$val['card_status'];
//                            $card_day=$val['card_day'];
//                            $card_service=$val['card_service'];
//                            if($card_status==2) {
//                                $prize_info="加".$card_service."次配送";
//                            } elseif($card_status==1) {
//                                $prize_info="延长".$card_day."天";
//                            } else {
//                                $prize_info=$prize_title2;
//                            }
//                            if($k==0) {
//                                $prize_name.= $prize_info;
//                            } else {
//                                $prize_name.=",".$prize_info;
//                            }
//                        }
//                    }
//                }
                $prize_name2 = "";
                $res[]=array(
                    'id'=>$id,
                    'user_id'=>$user_id,
                    'parent_id'=>$parent_id,
                    'user_name'=>$user_name,
                    'nick_name'=>$nick_name,
                    'mobile'=>$mobile,
                    'user_id'=>$user_id,
                    'a_b'=>$a_b,
                    'user_info'=>$user_info,
                    'is_pay'=>$is_pay,
                    'click_num'=>$click_num,
                    'is_true'=>$is_true,
                    'prize' => $prize_name,
                    'remark'=>$remark,
                    'create_time'=>$show_create_time,
                    'business_info'=>$business_info,
                    'prize_name'=>$prize_name2,
                    'prize_name_3_str'=>$prize_name_3_str,
                    'invite_type' => $invite_type
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //超级伙伴列表
    public function toyscardactivitypartnerlist(){
        $show_time="2018-04-18 23:59:59";
        $start_time="2018-04-10 13:00:01";
        $end_time="2018-04-16 23:59:59";

        $today_start_time = date('Y-m-d 00:00:01');
        $today_end_time = date('Y-m-d 23:59:59');

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
//        $str_card_info=$this->getCardInfo();//,3028,2255,2253
        $str_card_info = "418,420,422,424,548,649,652,655,1040,1041,1137,1139,1253,1255,1336,1338,1340,1342,1416,1625,1627,1629,1631,2633,2635,2853,2902,2907,2914,2916,3026,3074,3088,3140,3164,3303,3305,3346,3307,3166,3168,3189,3201,3223,3231,3233,3242";
        $user_id=I("user_id");//用户id
        $parent_id=I("parent_id");//团长id
        $phone=I("phone");//手机号
        $search_parent_id=I("search_parent_id");//身份
        $search_invite_type=I("search_invite_type");//1：跟进；2：本期活动；3：暂不跟进；4：会员
        if($search_parent_id==3) {//自主下单
            $where2= " 1 ";
        } else {
            $where2= " ((a.create_time > '$show_time' and a.state='0' ) or a.state=2 )";
        }
        $search_remark=I("search_remark");//备注
        $order_AD = "a.create_time DESC";

        //除去超级伙伴购卡数据
        $where2 .= " and a.parent_id<100000000 ";

        //团购分类
        if($search_invite_type>0) {
            if($search_invite_type==2){
                $where2 .= " and a.create_time> '$start_time' and a.create_time<'$end_time' ";
            }else{
                $where2 .= " and a.invite_type= '$search_invite_type' ";
            }
            $this->assign('search_invite_type',$search_invite_type);
        }

        if($search_parent_id==1) {//团长
            $where2 .= " and a.state='0' and a.parent_id=0 ";
            $this->assign('search_parent_id',$search_parent_id);
        } elseif($search_parent_id==2) {//团员
            $where2 .= " and a.state='0' and a.parent_id>0 ";
            $this->assign('search_parent_id',$search_parent_id);
        } elseif($search_parent_id==3) {//自主下单
            $where2 .= " and (a.state='2' and parent_id='-1') ";
            $this->assign('search_parent_id',$search_parent_id);
        }elseif($search_parent_id==4) {//当天注册未提交过任何订单
            $where2 .= " and (a.create_time>'$today_start_time' and a.create_time<'$today_end_time' and a.parent_id='-2') ";
            $this->assign('search_parent_id',$search_parent_id);
        } else {
            $where2.= " and a.state!='1' ";
        }
        if($search_remark) {
            $where2 .= " and a.remark like '%$search_remark%' ";
            $this->assign('search_remark',$search_remark);
        }
        if($user_id>0) {
            $where2 .= " and (a.user_id='$user_id' ) ";
            $this->assign('user_id',$user_id);
        }

        if($parent_id>0) {
            $where2 .= " and (a.parent_id='$parent_id' or user_id = $parent_id ) ";
            $order_AD = "a.parent_id ASC";
            $this->assign('parent_id',$parent_id);
        }

        if($phone) {
            $where2 .= " and a.mobile='$phone'  ";
            $order_AD = "a.parent_id ASC";
            $this->assign('phone',$phone);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_activity_invitation as a')
            ->join(" left join baby_user as s on a.user_id=s.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_activity_invitation as a')
            ->join(" left join baby_user as s on a.user_id=s.id")
            ->where($where2)
            ->field('a.id,a.user_id,a.parent_id,s.nick_name,s.user_name,a.mobile,s.mobile as u_mobile,a.click_num,a.prize,a.remark,a.create_time,a.state,a.pin_prize,a.pin_prize2,a.pin_prize3,a.pin_ids,a.invite_type')
            ->order("$order_AD")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        $common_user_id_arr = array();//运营账号
        $common_user_res = $model->table('baby_common_user')
            ->where("user_id>0")
            ->field('user_id')
            ->select();
        foreach($common_user_res as $value){
            $common_user_id_arr[] = $value['user_id'];
        }
        if($list) {
            foreach ($list as $key => $value) {
                $id=$value['id'];
                $user_id=$value['user_id'];

                //运营账号
                if(in_array("$user_id",$common_user_id_arr)){
                    $is_true = 1;//运营账号
                }else{
                    $is_true = 0;//用户
                }
                $show_create_time=$value['create_time'];
                $parent_id=$value['parent_id'];
                $user_name=$value['user_name'];
                $nick_name=$value['nick_name'];
                $invite_type = $value['invite_type'];
                $mobile=$value['mobile'];
                if(($mobile==0) && ($value['u_mobile'])){
                    $mobile = $value['u_mobile'];
                }
                $click_num=$value['click_num'];
                $remark = $value['remark'];
                $prize = $value['prize'];
                $state=$value['state'];
                $pin_prize = $value['pin_prize'];
                $pin_prize2 = $value['pin_prize2'];
                $pin_prize3 = $value['pin_prize3'];
                $prize_name_3_arr = array();
                $prize_name_3_str = "";
                if($pin_prize){
                    $pin_prize3_arr = explode(',',$pin_prize);
                    foreach($pin_prize3_arr as $val){
                        $pin_prize_3 = $val;
                        switch($pin_prize_3){
                            case 1:
                                $prize_name_3 = "芭比娃娃";
                                break;
                            case 2:
                                $prize_name_3 = "变形金刚";
                                break;
                            case 3:
                                $prize_name_3 = "配送一次";
                                break;
                            case 4:
                                $prize_name_3 = "特权一次";
                                break;
                            case 5:
                                $prize_name_3 = "延期15天";
                                break;
                            case 6:
                                $prize_name_3 = "延期28天";
                                break;
                            case 7:
                                $prize_name_3 = "立减￥30";
                                break;
                            default:
                                $prize_name_3 = "";
                        }
                        $prize_name_3_arr[] = $prize_name_3;
                    }

                    $prize_name_3_str = implode('<br>',$prize_name_3_arr);
                }
                $pin_ids = $value['pin_ids'];
                $prize_name="";
                switch($prize){
                    case 1:
                        $prize_name = "芭比娃娃";
                        break;
                    case 2:
                        $prize_name = "变形金刚";
                        break;
                    case 3:
                        $prize_name = "配送一次";
                        break;
                    case 4:
                        $prize_name = "特权一次";
                        break;
                    case 5:
                        $prize_name = "延期15天";
                        break;
                    case 6:
                        $prize_name = "延期28天";
                        break;
                    case 7:
                        $prize_name = "立减￥30";
                        break;
                    default:
                        $prize_name = "";
                }
//                if($pin_prize2==1){
//                    $prize_name2 .= "+延期7天";
//                }

                $arr_card_info = explode(',',$str_card_info);
                $business_info = "";
                if($parent_id==-1) {
                    $user_info="自主下单";
                } elseif($parent_id==0) {
                    $user_info="团长";
                } else {
                    $user_info="团员";
                }
                //是否购卡
                $where_conditon=" user_id='$user_id' and state='0' and status in (2,7) and business_id in ($str_card_info)  ";
                $where_conditon=" user_id='$user_id' and state='0' and status in (2,7) and business_id in ($str_card_info)  ";
                if($show_create_time>'2017-10-13 00:00:01') {
                    $where_conditon.= " and create_time>'2017-10-13 00:00:01' ";
                } else {
                    $where_conditon.= " and create_time>'$show_time' ";
                }
                $is_have_res=M("toys_order")->field('id')->where($where_conditon)->find();

                if($is_have_res){
                    $is_pay = "已购卡";
                }else{
                    $is_pay = "未支付";
                    if($state==2){//自主下单
                        $business_info_res = $model->table('baby_toys_order as o ')
                            ->join(" left join baby_toys_business as b on o.business_id=b.id")
                            ->where("o.user_id='$user_id'  and b.state='0' and o.status=1  ")
                            ->field('b.business_title,o.business_id')
                            ->select();
                        foreach($business_info_res as $v){
                            $business_title = $v['business_title'];
                            $business_info.= "<br>".$business_title;
                        }
                    }
                }

//                if($prize) {
//                    $prizeInfo=M("toys_prize")
//                            ->field('prize_title2,card_day,card_service,card_status')
//                            ->where("prize_id2 in ($prize) ")
//                            ->select();
//                    if($prizeInfo) {
//                        foreach ($prizeInfo as $k=> $val) {
//                            $prize_title2=$val['prize_title2'];
//                            $card_status=$val['card_status'];
//                            $card_day=$val['card_day'];
//                            $card_service=$val['card_service'];
//                            if($card_status==2) {
//                                $prize_info="加".$card_service."次配送";
//                            } elseif($card_status==1) {
//                                $prize_info="延长".$card_day."天";
//                            } else {
//                                $prize_info=$prize_title2;
//                            }
//                            if($k==0) {
//                                $prize_name.= $prize_info;
//                            } else {
//                                $prize_name.=",".$prize_info;
//                            }
//                        }
//                    }
//                }
                $prize_name2 = "";
                $res[]=array(
                    'id'=>$id,
                    'user_id'=>$user_id,
                    'parent_id'=>$parent_id,
                    'user_name'=>$user_name,
                    'nick_name'=>$nick_name,
                    'mobile'=>$mobile,
                    'user_id'=>$user_id,
                    'user_info'=>$user_info,
                    'is_pay'=>$is_pay,
                    'click_num'=>$click_num,
                    'is_true'=>$is_true,
                    'prize' => $prize_name,
                    'remark'=>$remark,
                    'create_time'=>$show_create_time,
                    'business_info'=>$business_info,
                    'prize_name'=>$prize_name2,
                    'prize_name_3_str'=>$prize_name_3_str,
                    'invite_type' => $invite_type
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    
    //团购列表
    public function toyshelpmelist(){
        $show_time="2017-09-07 10:00:01";
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $str_card_info=$this->getCardInfo();
        $user_id=I("user_id");//用户id
        $parent_id=I("parent_id");//邀请人id
        $phone=I("phone");//手机号
        $search_parent_id=I("search_parent_id");//身份
        $help_number_order = I("help_number_order");
        $where2= " a.create_time > '$show_time' and a.user_id!='312065' ";
        $search_remark=I("search_remark");//备注
        $order_AD = "a.id DESC";
        if($search_parent_id==1) {//团长
            $where2 .= " and a.state='0' and a.parent_id=0 ";
            $this->assign('search_parent_id',$search_parent_id);
        } elseif($search_parent_id==2) {//团员
            $where2 .= " and a.state='0' and a.parent_id>0 ";
            $this->assign('search_parent_id',$search_parent_id);
        } elseif($search_parent_id==3) {//自主下单
            $where2 .= " and a.state='2' and parent_id='-1' ";
            $this->assign('search_parent_id',$search_parent_id);
        } else {
            $where2.= " and a.state!='1' ";
        }
        if($search_remark) {
            $where2 .= " and a.remark like '%$search_remark%' ";
            $this->assign('search_remark',$search_remark);
        }
        if($user_id>0) {
            $where2 .= " and (a.user_id='$user_id' ) ";
            $this->assign('user_id',$user_id);
        }

        if($parent_id>0) {
            $where2 .= " and (a.parent_id='$parent_id' or user_id = $parent_id ) ";
            $order_AD = "a.parent_id ASC";
            $this->assign('parent_id',$parent_id);
        }

        if($phone) {
            $where2 .= " and a.mobile='$phone'  ";
            $order_AD = "a.parent_id ASC";
            $this->assign('phone',$phone);
        }
//
        if($help_number_order==1){
            $where2 .= " and a.parent_id='0'  ";
            $order_AD = " a.help_number DESC ";
            $this->assign('help_number_order',$help_number_order);
        }

        if($help_number_order==2){
            $where2 .= " and a.parent_id='0' and a.help_number='100'  ";
            $order_AD = " a.help_number DESC ";
            $this->assign('help_number_order',$help_number_order);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_help_me as a')
            ->join(" left join baby_user as s on a.user_id=s.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_help_me as a')
            ->join(" left join baby_user as s on a.user_id=s.id")
            ->where($where2)
            ->field('a.id,a.user_id,a.parent_id,s.nick_name,s.user_name,a.mobile,a.remark,a.create_time,a.state,a.help_number')
            ->order("$order_AD")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        $common_user_id_arr = array();//运营账号
        $common_user_res = $model->table('baby_common_user')
            ->where("user_id>0")
            ->field('user_id')
            ->select();
        foreach($common_user_res as $value){
            $common_user_id_arr[] = $value['user_id'];
        }
        if($list) {
            foreach ($list as $key => $value) {
                $id=$value['id'];
                $user_id=$value['user_id'];

                //运营账号
                if(in_array("$user_id",$common_user_id_arr)){
                    $is_true = 1;//运营账号
                }else{
                    $is_true = 0;//用户
                }
                $show_create_time=$value['create_time'];
                $parent_id=$value['parent_id'];
                $user_name=$value['user_name'];
                $nick_name=$value['nick_name'];
                $mobile=$value['mobile'];

                $help_number = $value['help_number'];

                $remark = $value['remark'];

                $state=$value['state'];

                $arr_card_info = explode(',',$str_card_info);


                //是否购卡
                $is_have_res=M("toys_order")->field('id')->where(" user_id='$user_id' and state='0' and status in (2,7) and business_id in ($str_card_info) and create_time>'$show_time' ")->find();

                if($is_have_res){
                    $is_pay = "已购卡";
                }else{
                    $is_pay = "未支付";

                }

                $is_have_res_1=M("toys_order")->field('id')->where(" user_id='$user_id' and state='0' and status in (2,7) and business_id in ($str_card_info) ")->find();
                if($is_have_res_1){
                    $user_info_end = "会员";
                }else{
                    $user_info_end = "非会员";
                }
                if($parent_id==0) {
                    $user_info="邀请人(".$user_info_end.")";
                } else {
                    $user_info="被邀请人(".$user_info_end.")";
                }

                $prize_name="";


                $res[]=array(
                    'id'=>$id,
                    'user_id'=>$user_id,
                    'parent_id'=>$parent_id,
                    'user_name'=>$user_name,
                    'nick_name'=>$nick_name,
                    'mobile'=>$mobile,
                    'user_id'=>$user_id,
                    'user_info'=>$user_info,
                    'is_pay'=>$is_pay,
                    'help_number'=>$help_number,
                    'is_true'=>$is_true,

                    'remark'=>$remark,
                    'create_time'=>$show_create_time,

                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除团员
    public function toysactivitydel(){
        $id = $_GET['id'];
        $album = M('toys_activity_invitation');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toyscardactivity.html");
    }

    //删除团员
    public function toyshelpmedel(){
        $id = $_GET['id'];
        $album = M('toys_help_me');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/toyshelpmelist.html");
    }
    //删除
    public function operator_invPrizestate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_invitation');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getinvprizelist.html");
    }
    //获取奖品规则
    public function getPrizeRule(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $where_condition="state='0'"; 
        $post_img = M('toys_prize_rule');
        //满足条件的总记录数
        $count  = $post_img->where($where_condition)->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $post_img
                ->where($where_condition)
                ->order('create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select(); 
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $description = $value['description']; 
            $res[]=array(
                'id'=>$id,
                'description'=>$description    
            );
        }
        $this->assign("res",$res);
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //删除奖品规则
    public function operator_prizerulestate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_prize_rule');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getPrizeRule.html");
    }
    //编辑奖品规则页面
    public function editprizerule(){
        $id = $_GET['id'];
        if($id>0) {
            $res = M("toys_prize_rule")
                ->where("state='0' and id=$id ")
                ->find();
            $this->assign("res",$res);
        }
        $this->display("addprizerule");
    }
    public function edittoysorder(){
        $this->checkSession();
        $this->display();
    }
    //取消派单页面
    public function cacelorder(){
        $user_id = I('user_id');
        $postman_id = I('postman_id');
        $this->assign("postman_id",$postman_id);
        $this->assign("user_id",$user_id);
        $this->display();
    }
    //玩具有问题订单特殊处理
    public function puproblemtoysorder(){
        $this->display();
    }

    //每天玩乐中列表
    public function everytoysorderfun() {
        $user_id=I("user_id");
        $start_time=I('start_time');
        $end_time=I('end_time');
        $search_address=I("search_address");
        $this->checkSession(); 
        if($start_time) {
            $today=$start_time;
            $this->assign("start_time",$start_time);
        } else {
            $today=date("Y-m-d 00:00:00");
        }
        if($end_time) {
            $moring=$end_time;
            $this->assign("end_time",$end_time);
        } else {
            $moring=date("Y-m-d 00:00:00",strtotime("+1 day"));
        }
        
        $show_time=$today."到".$moring;
        $this->assign("show_time",$show_time);
        //$today=date("Y-m-d 00:00:00");
        //$moring=date("Y-m-d 00:00:00",strtotime("+1 day"));
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.status=6 and o.state='0' and o.post_create_time>'$today' and o.post_create_time<'$moring'   ";//and a.state='0' and a.is_defalut='1'
        if($user_id) {
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        if($search_address) {
            $where2.=" and o.address like '%$search_address%' ";
            $this->assign('search_address',$search_address);
        }
        $model = new Model();
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_order as o')
                        ->where($where2)
                        ->field('o.user_id')
                        ->group("o.user_id")
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_order as o')
                    ->where($where2)
                    ->field("o.user_id ")
                    ->group("o.user_id")
                    ->order("post_create_time desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_user_id=$value['user_id'];
                $getAddressRes=M("user_address")
                    ->where("state='0' and user_id=$tmp_user_id and is_defalut='1' ")
                    ->find();
                if($getAddressRes) {
                    $order_user_name=$getAddressRes['user_name'];
                    $order_mobile=$getAddressRes['mobile'];
                    $order_address=$getAddressRes['address'];
                }
                $user_info=$send_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address) {
                    $user_info.="<br/>".$order_address;
                }
                $getSendRes=$model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->join(" left join baby_toys_user_remark as r on o.user_id=r.user_id and r.state='0' ")//
                    ->where("o.status in (6) and o.state='0' and o.user_id=$tmp_user_id and o.post_create_time>'$today' and o.post_create_time<'$moring' ")
                    ->field('o.business_id,b.business_title,b.category_ids,r.remark')
                    ->select();
                $remark="";
                if($getSendRes) {
                    foreach ($getSendRes as $k => $val) {
                        $tmp_bus_id=$val['business_id'];
                        $tmp_bus_title=$val['business_title'];
                        $tmp_category_ids=$val['category_ids'];
                        $tmp_remark=$val['remark'];
                        $toys_size="0";
                        if($tmp_category_ids) {
                            $arr_category_ids=explode(",", $tmp_category_ids);
                            if(in_array("106",$arr_category_ids) ) {
                                $toys_size="1";
                            }
                        }
                        if($toys_size==1) {
                            $toys_size_name="【小】";
                        } else {
                            $toys_size_name="【大】";
                        }
                        if($k==0) {
                            $remark.=$tmp_remark;
                            $br="";
                        } else {
                            $br="<br/><br/>";
                        }
                        $send_toys.=$br.$tmp_bus_id;
                        if($tmp_toys_number) {
                            $send_toys.="【".$tmp_toys_number."】";
                        }
                        $send_toys.="<br/>".$toys_size_name.$tmp_bus_title;
                    }
                }
                $res[]=array(
                    'user_id'=>$tmp_user_id,
                    'user_info'=>$user_info,
                    'send_toys'=>$send_toys,
                    'remark'=>$remark
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //一键清空备注
    public function operator_userremarkstate(){       
        $imgid = I('user_id');
        $album = M('toys_user_remark');
        $where['user_id'] = $imgid;
        $where['state'] = '0';

        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/everytoysorderfun.html");
    }
    //添加加速页面
    public function pubhelpme(){
        $this->display();
    }
    //玩具修改状态
    public function edittoystate(){        
        $imgid = I('id');
        $state=I('state');
        $where['id'] = array('in',$imgid);
        if($state==1){
            $data['is_show'] = 0 ;
            $data['is_break'] = 0 ;
        }elseif($state==2){
            $data['is_show'] = 2 ;
            $data['is_break'] = 0 ;
        }elseif($state==3){
            $data['is_break'] = 1 ;
            $data['is_show'] = 0 ;
        }
        $data['post_create_time'] = date("Y-m-d H:i:s");
        $result = M('toys_business')->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Others/getToyslist?img_id=$imgid&search_state=$state ");
    }

    //推广链接列表
    public function toysalluserlink(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");


        $model = new Model();
        $where2 = " state='0' ";
        //满足条件的总记录数
        $count  = $model->table('baby_toys_info_link')
            ->where($where2)
            ->count();
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_info_link')
            ->where($where2)
            ->order("id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $this->assign('res',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //推广链接列表编辑
    public function toysalluserlinkedit(){

        $this->checkSession();

        $model = new Model();
        $id = I('id');

        $list = array();

        if($id>0){
            $where2 = " state='0' and id =$id ";
            $list = $model->table('baby_toys_info_link')
                ->where($where2)
                ->find();
        }

        $this->assign('res',$list);//赋值数据集
        $this->display();
    }

    //推广小号列表
    public function toysalluserinfo(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");


        $model = new Model();
        $where2 = " state='0' ";
        //满足条件的总记录数
        $count  = $model->table('baby_toys_info_user')
            ->where($where2)
            ->count();
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_info_user')
            ->where($where2)
            ->order("id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $this->assign('res',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //推广小号编辑
    public function toysalluserinfoedit(){

        $this->checkSession();

        $model = new Model();
        $id = I('id');

        $list = array();

        if($id>0){
            $where2 = " state='0' and id =$id ";
            $list = $model->table('baby_toys_info_user')
                ->where($where2)
                ->find();
        }

        $this->assign('res',$list);//赋值数据集
        $this->display();
    }

    //邀请码列表
    public function toysinvitecode(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");


        $model = new Model();
        $where2 = " state='0' and id !=2 ";
        //满足条件的总记录数
        $count  = $model->table('baby_toys_invite_code')
            ->where($where2)
            ->count();
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_invite_code')
            ->where($where2)
            ->order("id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        if($list) {
            foreach ($list as $key => $value) {
                $id = $value['id'];
                $number = $value['number'];
                $create_time = $value['create_time'];
                $start_time = $value['start_time'];
                $end_time = $value['end_time'];
                $money = $value['money'];
                $invite_type = $value['invite_type'];
                $title = $value['title'];
                $remark = $value['remark'];

                $type_name = "";
                if($invite_type==2){
                    $type_name = "内部推荐";
                }elseif($invite_type==3){
                    $type_name = "线下活动";
                }elseif($invite_type==4){
                    $type_name = "团购专场";
                }

                $res[]=array(
                    'id' => $id,
                    'number' => $number,
                    'create_time' => $create_time,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'money' => $money,
                    'title' => $title,
                    'type_name' => $type_name,
                    'remark' => $remark
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //邀请码列表
    public function toysinvitecodeedit(){

        $this->checkSession();

        $model = new Model();
        $id = I('id');
        $where2 = "";
        $now_time=date ( 'Y-m-d H:i:s' );
        $list = array(
            'start_time'=>$now_time,
            'end_time'=>$now_time
        );
        if($id>0){
            $where2 = " state='0' and id =$id ";
            $list = $model->table('baby_toys_invite_code')
                ->where($where2)
                ->find();
        }




        $this->assign('res',$list);//赋值数据集
        $this->display();
    }

    //一元卡活动列表
    public function toysactivitygrouplist(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");

        $where2 = " p.state='0' ";
        $user_id_search = intval(I("user_id"));
        $search_status = intval(I("search_status"));

        if($user_id_search>0){
            $where2 .= " and p.user_id='$user_id_search' ";
            $this->assign('user_id_search',$user_id_search);
        }

        //筛选支付状况
        $this->assign('search_status',$search_status);
        if($search_status==1){
            $where2 .= " and p.status=0 ";
        }elseif($search_status==2){
            $where2 .= " and p.status=1 ";
        }elseif($search_status==3){
            $where2 .= " and p.success_status=1 ";
        }elseif($search_status==4){
            $where2 .= " and p.status=1 and p.success_status=0 ";
        }

        //起始时间搜索
        $now_time = date("Y-m-d 00:00:00");
        $this->assign('now_time',$now_time);
        $search_start_time = I('search_start_time');
        $search_end_time = I('search_end_time');

        if($search_start_time){
            $where2.=" and p.post_create_time>'$search_start_time' ";
            $this->assign('search_start_time',$search_start_time);
        }
        if($search_end_time){
            $where2.=" and p.post_create_time<'$search_end_time' ";
            $this->assign('search_end_time',$search_end_time);
        }

        $model = new Model();

        //查询运营账号
        $getCommonUserRes= $model->table('baby_common_user')
            ->where("user_id>0")
            ->field('user_id')
            ->select();
        foreach($getCommonUserRes as $val){
            $common_user_id_arr[] = $val['user_id'];
        }
        //满足条件的总记录数
        $count  = $model->table('baby_toys_activity_groups as p')
            ->join("left join baby_user as u on p.user_id=u.id")
            ->where($where2)
            ->count();
        $this->assign('false_user_num',$count);

        $true_user_num=$model->table('baby_toys_activity_groups as p')
            ->join("left join baby_user as u on p.user_id=u.id")
            ->join("left join baby_common_user as c on p.user_id=c.user_id")
            ->where($where2." and c.user_id is null")
            ->count();

        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_activity_groups as p')
            ->join("left join baby_user as u on p.user_id=u.id")
            ->where($where2)
            ->field('p.*,u.nick_name,u.mobile')
            ->order("id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        if($list) {
            foreach ($list as $key => $value) {
                $id = $value['id'];
                $user_id = $value['user_id'];
                $nick_name = $value['nick_name'];
                $mobile = $value['mobile'];
                $create_time = $value['create_time'];
                $post_create_time = $value['post_create_time'];
                $marks_id = $value['marks_id'];
                $marks_id_arr = explode('-',$marks_id);
                $parent_id = $marks_id_arr[0];
                $business_id = $marks_id_arr[1];
                $status=$value['status'];
                $success_status = $value['success_status'];
                $remark = $value['remark'];

                $now_time = date('Y-m-d H:i:s');
                $where_conditon = " user_id='$user_id' and state='0' and card_name in (2,3,4) and ( case when final_end_time>end_time then final_end_time else end_time end > '$now_time'  or status=4  OR start_time='0000-00-00 00:00:00' ) ";
                $is_have_res=M("toys_card")->field('id')->where($where_conditon)->find();

                if($is_have_res){
                    $member_info = "<font color='red'>会员</font>";
                }else{
                    $member_info = "非会员";
                }

                $groups_name = "";
                if($success_status==1){
                    $groups_name = "已成团";
                }else{
                    if($status==1){
                        $num_sql = "state='0' and status=1 and marks_id='".$marks_id."'";
                        $numRes = $model->table('baby_toys_activity_groups')
                            ->where($num_sql)
                            ->select();
                        $sel_marks_num = count($numRes);
                        $other_num = 5-$sel_marks_num;
                        $groups_name = "<font color='blue'>差".$other_num."人成团</font>";
                    }

                }

                if($status==0){
                    $status_name = "未支付";
                }else{
                    $status_name = "<font color='red'>已支付</font>";
                }

                if(in_array($user_id,$common_user_id_arr)){
                    $status_name .= "<font color='green'>（运营）</font>";
                }

                $res[]=array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'nick_name' => $nick_name,
                    'mobile' => $mobile,
                    'create_time'=>$create_time,
                    'post_create_time'=>$post_create_time,
                    'parent_name'=>$parent_id,
                    'business_id'=>$business_id,
                    'status_name'=>$status_name,
                    'groups_name'=>$groups_name,
                    'member_info'=>$member_info,
                    'remark'=>$remark
                );
            }
        }
        $this->assign('true_user_num',$true_user_num);
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //超级合伙人列表
    public function toysactivitypartner(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");

        $where2 = " p.state='0' ";
        $user_id_search = intval(I("user_id"));
        $nick_name_search = trim(I("nick_name"));

        if($user_id_search>0){
            $where2 .= " and p.user_id='$user_id_search' ";
            $this->assign('user_id_search',$user_id_search);
        }
        if(!empty($nick_name_search)){
            $where2 .= " and u.nick_name like '%".$nick_name_search."%' ";
            $this->assign('nick_name_search',$nick_name_search);
        }


        $model = new Model();

        //满足条件的总记录数
        $count  = $model->table('baby_toys_activity_partner as p')
            ->join("left join baby_user as u on p.user_id=u.id")
            ->where($where2)
            ->count();
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_activity_partner as p')
            ->join("left join baby_user as u on p.user_id=u.id")
            ->where($where2)
            ->field('p.*,u.nick_name')
            ->order("id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        if($list) {
            foreach ($list as $key => $value) {
                $id = $value['id'];
                $user_id = $value['user_id'];
                $user_name = $value['user_name'];
                $nick_name = $value['nick_name'];
                $mobile = $value['mobile'];
                $invite_num = $value['invite_num'];
                $invite_bonus = $value['invite_bonus'];
                $create_time = $value['create_time'];
                $start_time = $value['start_time'];
                $end_time = $value['end_time'];
                $remark = $value['remark']?$value['remark']:"暂无备注";
                $level = $value['level'];
                if($level==1){
                    $level_name = "一阶";
                }elseif($level==2){
                    $level_name = "二阶";
                }elseif($level==3){
                    $level_name = "三阶";
                }elseif($level==4){
                    $level_name = "成长伙伴";
                }



                $res[]=array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'nick_name' => $nick_name,
                    'mobile' => $mobile,
                    'invite_num' => $invite_num,
                    'invite_bonus' => $invite_bonus,
                    'create_time' => $create_time,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'remark' => $remark,
                    'level_name'=>$level_name
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //超级合伙人列表
    public function toysactivitypartnerall(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");

        $start_time = I('start_time')?I('start_time'):"";
        $end_time = I('end_time')?I('end_time'):"";

        $time_area = $start_time.'<br>'.$end_time;
        $time_area_excel = $start_time.'--'.$end_time;
        $this->assign('time_area',$time_area);

        $excel_state=I("excel_state");

        $time_where = "";
        if($start_time){
            $time_where = " and a.create_time>'$start_time' ";
            $this->assign('start_time',$start_time);
        }

        if($end_time){
            $time_where .= " and a.create_time<'$end_time' ";
            $this->assign('end_time',$end_time);
        }

        //分配默认时间
        $default_time = date('Y-m-d 00:00:00');
        $this->assign('default_time',$default_time);

        $model = new Model();
        $where2 = " p.state='0' ";
        //满足条件的总记录数

        //excel----------------------------out------start
        if($excel_state==1) {//导出
            $getexcelUserRes = $model->table('baby_toys_activity_partner as p')
                ->join('left join baby_user as u on p.user_id=u.id')
                ->where($where2)
                ->field('p.user_id,p.invite_num,u.nick_name')
                ->order("p.id desc")
                ->select();

            $xlsData=array();
            if($getexcelUserRes) {
                $xlsName="超级伙伴统计";
                $xlsCell  = array(
                    array('user_id','用户id'),
                    array('user_name','用户名'),
                    array('time_area','阶段'),
                    array('total_num','总邀请人数'),
                    array('balance_ing_num','待结算人数'),
                    array('balance_ing_price','本次结算金额')
                );
                foreach ($getexcelUserRes as $key => $value) {
                    $user_id = $value['user_id'];
                    $user_name = $value['nick_name'];
                    $invite_num = $value['invite_num'];

                    //结算信息开始-----------------------
                    $search_user_id = $user_id;
                    $search_user_id = 100000000+$search_user_id;
                    $search_new_user_id = $invite_num;
                    if($search_user_id==$search_new_user_id){
                        $search_user_id_str = "$search_user_id";
                    }else{
                        $search_user_id_str = $search_user_id.",".$search_new_user_id;
                    }


                    $list3 = $model->table('baby_toys_activity_invitation as a')
                        ->join("baby_toys_order as o on a.pin_prize3=o.combined_order_id")
                        ->where("a.state='0' and a.parent_id in($search_user_id_str) $time_where ")
                        ->field("a.*,o.status as card_status")
                        ->group("a.user_id")
                        ->select();

                    $balance_have_num = 0;//已结算人数
                    $balance_have_price = 0;//已结算金额

                    $balance_ing_num = 0;//已开卡待结算人数
                    $balance_ing_price = 0;//已开卡待结算金额

                    $balance_ed_num = 0;//未开卡待结算人数
                    $balance_ed_price = 0;//未开卡待结算金额

                    $total_num = 0;

//var_dump($list3);die;
                    if($list3) {
                        foreach ($list3 as $key => $value) {
                            $total_num++;
                            $create_time = $value['create_time'];
                            $card_status = $value['card_status'];
                            $balance_price = $value['balance_price'];
                            $is_balance = $value['is_balance'];


                            if($is_balance==1){
                                //已结算
                                $balance_have_num++;
                                $balance_have_price+=$balance_price;
                            }

                            if($is_balance==0){

                                if($card_status==7){
                                    //已开卡，待结算
                                    $balance_ing_num++;
                                    $balance_ing_price+=$balance_price;
                                }

                                if($card_status==2){
                                    //未开卡，待结算
                                    $balance_ed_num++;
                                    $balance_ed_price+=$balance_price;
                                }

                            }


                        }
                    }
                    //结算信息结束-----------------------

                    $xlsData[]=array(
                        'user_id' => $user_id,
                        'user_name' => $user_name,
                        'time_area' => $time_area_excel,
                        'total_num' => $total_num,
                        'balance_ing_num' => $balance_ing_num,
                        'balance_ing_price' => $balance_ing_price
                    );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
        //excel----------------------------out------end

        $count  = $model->table('baby_toys_activity_partner as p')
            ->where($where2)
            ->count();
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_activity_partner as p')
            ->join('left join baby_user as u on p.user_id=u.id')
            ->where($where2)
            ->field('p.user_id,p.invite_num,u.nick_name')
            ->order("p.id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();

        if($list) {
            foreach ($list as $key => $value) {
                $user_id = $value['user_id'];
                $user_name = $value['nick_name'];
                $invite_num = $value['invite_num'];

                //结算信息开始-----------------------
                $search_user_id = $user_id;
                $search_user_id = 100000000+$search_user_id;
                $search_new_user_id = $invite_num;
                if($search_user_id==$search_new_user_id){
                    $search_user_id_str = "$search_user_id";
                }else{
                    $search_user_id_str = $search_user_id.",".$search_new_user_id;
                }


                $list3 = $model->table('baby_toys_activity_invitation as a')
                    ->join("baby_toys_order as o on a.pin_prize3=o.combined_order_id")
                    ->where("a.state='0' and a.parent_id in($search_user_id_str) $time_where ")
                    ->field("a.*,o.status as card_status")
                    ->group("a.user_id")
                    ->select();

                $balance_have_num = 0;//已结算人数
                $balance_have_price = 0;//已结算金额

                $balance_ing_num = 0;//已开卡待结算人数
                $balance_ing_price = 0;//已开卡待结算金额

                $balance_ed_num = 0;//未开卡待结算人数
                $balance_ed_price = 0;//未开卡待结算金额

                $total_num = 0;

//var_dump($list3);die;
                if($list3) {
                    foreach ($list3 as $key => $value) {
                        $total_num++;
                        $create_time = $value['create_time'];
                        $card_status = $value['card_status'];
                        $balance_price = $value['balance_price'];
                        $is_balance = $value['is_balance'];


                        if($is_balance==1){
                            //已结算
                            $balance_have_num++;
                            $balance_have_price+=$balance_price;
                        }

                        if($is_balance==0){

                            if($card_status==7){
                                //已开卡，待结算
                                $balance_ing_num++;
                                $balance_ing_price+=$balance_price;
                            }

                            if($card_status==2){
                                //未开卡，待结算
                                $balance_ed_num++;
                                $balance_ed_price+=$balance_price;
                            }

                        }


                    }
                }
                //结算信息结束-----------------------

                $res[]=array(
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'total_num' => $total_num,
                    'balance_ing_num' => $balance_ing_num,
                    'balance_ing_price' => $balance_ing_price
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //超级合伙人详情
    public function toysactivitypartnerlist(){

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");


        $model = new Model();
        $search_user_id = I('user_id');
        $search_user_id = 100000000+$search_user_id;
        $search_new_user_id = I('new_user_id');
        if($search_user_id==$search_new_user_id){
            $search_user_id_str = "$search_user_id";
        }else{
            $search_user_id_str = $search_user_id.",".$search_new_user_id;
        }

        $where2 = " state='0' and parent_id in($search_user_id_str) ";

        //满足条件的总记录数
        $count  = $model->table('baby_toys_activity_invitation')
            ->where($where2)
            ->count();
        $this->assign('total_num',$count);

        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $model->table('baby_toys_activity_invitation as a')
            ->join("baby_user as u on a.user_id=u.id")
            ->join("baby_toys_order as o on a.pin_prize3=o.combined_order_id")
            ->where("a.state='0' and a.parent_id in($search_user_id_str) ")
            ->field("a.*,u.nick_name,o.status as card_status,o.sell_price")
            ->group("a.user_id")
            ->order("a.id desc")
//            ->limit($page->firstRow,$page->listRows)
            ->select();
//        var_dump($list);die;
        $res=array();
//        $str_card_info=$this->getCardInfo();
//        $arr_card_info = explode(',',$str_card_info);

        $balance_have_num = 0;//已结算人数
        $balance_have_price = 0;//已结算金额

        $balance_ing_num = 0;//已开卡待结算人数
        $balance_ing_price = 0;//已开卡待结算金额

        $balance_ed_num = 0;//未开卡待结算人数
        $balance_ed_price = 0;//未开卡待结算金额


        if($list) {
            foreach ($list as $key => $value) {
                $id = $value['id'];
                $user_id = $value['user_id'];
                $user_name = $value['nick_name'];;
                $create_time = $value['create_time'];
                $card_status = $value['card_status'];
                $sell_price = $value['sell_price'];
                $balance_price = $value['balance_price'];
                $is_balance = $value['is_balance'];
                $remark = $value['remark'];

                if($card_status==2){
                    $card_name = "待启用";
                }elseif($card_status==7){
                    $card_name = "已启用";
                }elseif($card_status==8){
                    $card_name = "退款中";
                }elseif($card_status==9){
                    $card_name = "已退款";
                }

                if($is_balance==1){
                    //已结算
                    $balance_have_num++;
                    $balance_have_price+=$balance_price;
                }

                if($is_balance==0){

                    if($card_status==7){
                        //已开卡，待结算
                        $balance_ing_num++;
                        $balance_ing_price+=$balance_price;
                    }

                    if($card_status==2){
                        //未开卡，待结算
                        $balance_ed_num++;
                        $balance_ed_price+=$balance_price;
                    }

                }


                $res[]=array(
                    'id' => $id,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'create_time' => $create_time,
                    'card_name' => $card_name,
                    'sell_price' => $sell_price,
                    'balance_price' => $balance_price,
                    'is_balance' => $is_balance,
                    'remark' => $remark
                );
            }
        }

        $balance_info = array(
            'balance_have_num'=>$balance_have_num,
            'balance_have_price'=>$balance_have_price,
            'balance_ing_num'=>$balance_ing_num,
            'balance_ing_price'=>$balance_ing_price,
            'balance_ed_num'=>$balance_ed_num,
            'balance_ed_price'=>$balance_ed_price,
        );
        $this->assign('balance_info',$balance_info);//赋值数据集

        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //ajax test
    public function toysajaxtest(){
        $attr = array(
            array(
                'code'=> 11,
                'name'=> 'one'
            ),
            array(
                'code'=> 22,
                'name'=> 'two'
            ),
            array(
                'code'=> 33,
                'name'=> 'three'
            ),
        );

        $this->ajaxReturn($attr);//ajax返回数据的方式，用ajaxReturn。

    }

    //超级合伙人编辑
    public function toysactivitypartneredit(){

        $this->checkSession();

        $model = new Model();
        $id = I('id');
        $where2 = "";
        $now_time=date ( 'Y-m-d H:i:s' );
        $list = array(
            'start_time'=>$now_time,
            'end_time'=>$now_time
        );
        if($id>0){
            $where2 = " state='0' and id =$id ";
            $list = $model->table('baby_toys_activity_partner')
                ->where($where2)
                ->find();
        }




        $this->assign('res',$list);//赋值数据集
        $this->display();
    }

    //超级合伙人绑定关系
    public function toysactivitypartnersuper(){

        $this->checkSession();

        $model = new Model();

        $this->display();
    }

}
?>
