<?php
class ToysAction extends Action{
    //在类初始化方法中，引入相关类库    
    /*public function _initialize() {
        vendor('Video.Videourlparser');
    }*/
    /**
     * 验证是否已设置session
     */
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
            ->where("  state='0' and business_id='$business_id' and is_use in ('0','5') and out_state='0' ")   //new_old='0' and
            ->count();
        //没选编号订单
        $use2_count=$model->table('baby_toys_order')
            ->where("state='0' and business_id=$business_id and (toys_number='' or toys_number is null ) and status in (1,2) and is_prize in (0,3,6) ")
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
                    ->field('o.id as order_id,o.user_id,o.business_id,b.way,b.business_title,o.remark,o.card_id,o.toys_number,o.order_time as new_create_time,o.is_size,o.is_prize,o.cancel_reason,o.cancel_remark')
                    ->select();
        $remark="";
        if($getSendRes) {
            $cancel_remark_end = "";
            foreach ($getSendRes as $k => $val) {
                $is_size=$val['is_size'];
                $tmp_bus_id=$val['business_id'];
                $tmp_user_id=$val['user_id'];
                $tmp_order_id=$val['order_id'];
                $tmp_bus_title=$val['business_title'];

                $cancel_remark=$val['cancel_remark']?$val['cancel_remark']:"";
                $cancel_reason=$val['cancel_reason']?$val['cancel_reason']:"";//取消类型  1：配送员原因取消  2:电话无人接听    3:暂时无法接收玩具
                if($cancel_reason==1){
                    $cancel_remark_end .= $symbol."配送员取消".$cancel_remark;
                }elseif($cancel_reason==2){
                    $cancel_remark_end .= $symbol."电话无人接听".$cancel_remark;
                }elseif($cancel_reason==3){
                    $cancel_remark_end .= $symbol."暂时无法接收玩具".$cancel_remark;
                }else{
                    $cancel_remark = "";
                }

                $tmp_remark=$val['remark'];
                $tmp_card_id=$val['card_id'];
                $tmp_toys_number=$val['toys_number'];
                $tmp_way=$val['way'];
                $is_prize = $val['is_prize'];
                $tmp_new_create_time = $val['new_create_time'];
                //备货链接
                if(empty($tmp_toys_number) && ($symbol=="<br/>")){
                    $tmp_bus_title = "<a href='http://checkpic.meimei.yihaoss.top/Others/toysimgnum?business_id=".$tmp_bus_id."&order_id=".$tmp_order_id."&status=2&user_id=".$tmp_user_id."'>$tmp_bus_title</a>";
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

            $attachment_res = $model->table('baby_toys_order as o')
                ->join(" left join baby_toys_battery as b on o.business_id=b.type")
                ->where(" o.user_id='$tmp_user_id' and o.is_prize=8 and o.state='0' and o.status in (2,17) ")
                ->field('b.title')
                ->select();
            if($attachment_res){
                foreach($attachment_res as $key=>$value){
                    $attachment_name = "【".$value['title']."】（附件）";
                    if($key==0){
                        $send_toys.=$symbol.$symbol.$attachment_name;
                    }else{
                        $send_toys.=$symbol.$attachment_name;
                    }

                }
            }

            $MyRes=array('send_toys'=>$send_toys,'remark'=>$remark.$cancel_remark_end,'tmp_new_create_time'=>$tmp_new_create_time);
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
                    ->join(" left join baby_toys_business_listing as t on o.toys_number=t.id")
                    ->where($where_conditon)
                    ->field('o.business_id,b.business_title,o.toys_number,o.is_size,o.is_prize,b.business_parts,o.id,o.remark,o.cancel_remark,t.remark as toys_remark')
                    ->select();
        $business_parts_list = "";
        $toys_back_remark = array();
        $toys_back_cancel_reason = array();
        if($getPickRes) {
            foreach ($getPickRes as $k => $val) {
                $order_id=$val['id'];
                $is_size=$val['is_size'];
                $tmp_bus_id=$val['business_id'];
                $tmp_bus_title=$val['business_title'];
                $tmp_bus_parts = $val['business_parts'];//玩具主要检查事项
                $temp_back_remark = $val['remark'];
                $temp_back_cancel_reason = $val['cancel_remark'];
                $temp_toys_remark = $val['toys_remark'];
                $tmp_toys_number=$val['toys_number'];
                if(empty($temp_toys_remark)){
                    $temp_toys_remark = "";
                }else{
                    $temp_toys_remark = "编号【".$tmp_toys_number."】入库备注：".$temp_toys_remark.$symbol;
                }


                //检查事项
                if($tmp_bus_parts){
                    $business_parts_list .= "【".$tmp_bus_title."】：".$tmp_bus_parts.$symbol.$temp_toys_remark;
                }else{
                    $business_parts_list .= $temp_toys_remark;
                }


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
                    $br2="";
                } else {
                    $br=$symbol.$symbol;
                    $br2=$symbol;
                }
                $pick_toys.=$br.$tmp_bus_id;
                if($tmp_toys_number) {
                    $pick_toys.="【".$tmp_toys_number."】";
                }

                if($temp_back_remark){
                    $toys_back_remark[]=$temp_back_remark;
                }
                if($toys_back_remark){
                    $toys_back_remark_end = array_unique($toys_back_remark);
                    $toys_back_remark_str = implode($symbol,$toys_back_remark_end);
                }else{
                    $toys_back_remark_str = "";
                }

                if($temp_back_cancel_reason){
                    $toys_back_cancel_reason[]=$temp_back_cancel_reason;
                }
                if($toys_back_cancel_reason){
                    $toys_back_cancel_reason_end = array_unique($toys_back_cancel_reason);
                    $toys_back_cancel_reason_str = implode($symbol,$toys_back_cancel_reason_end);
                    $toys_back_remark_str .= $toys_back_cancel_reason_str;
                }else{
                    $toys_back_cancel_reason_str = "";
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

            $MyRes=array('pick_toys'=>$pick_toys,'business_parts_list'=>$business_parts_list,'remark'=>$toys_back_remark_str);
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
    //玩具物流列表
    public function getlogisticslist(){
        $str_card_info=$this->getCardInfo();
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="case when status=7 then o.state!='77' else  o.state='0' end   and o.business_id not in ($str_card_info) ";
        $model = new Model();

        $order_id=trim(I("order_id"));
        $order_num=trim(I("order_num"));
        $order_status=trim(I("order_status"));
        $business_id=trim(I("business_id"));
        $start_time=I("start_time");
        $end_time=I("end_time");
        $user_id=trim(I("user_id"));
        $toys_number = trim(I("toys_number"));

        if($user_id) {
            if(!$order_id && !$order_num && !$order_status && !$business_id && !$start_time && !$end_time && !$toys_number){
                $order_status = "77" ;
            }
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }

        if($toys_number) {
            if(!$order_id && !$order_num && !$order_status && !$business_id && !$start_time && !$end_time && !$user_id){
                $order_status = "88" ;
            }
            $where2.=" and o.toys_number=$toys_number  ";
            $this->assign('toys_number',$toys_number);
        }

        if($order_id) {
            $where2.=" and o.id=$order_id ";
            $this->assign('order_id',$order_id);
        }

        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%' ";
            $this->assign('order_num',$order_num);
        }

        if($order_status) {
            if($order_status == 1){
                $where2.= " and o.status=2 and o.is_ready =0 and o.toys_number is null  ";
            } elseif ($order_status == 2){
                $where2.= " and ((o.status=2 and o.toys_number is not null ) or (o.status=10)) ";
                $ascsql = "o.user_id ASC";
            } elseif($order_status == 77){
                $where2.="  ";
                $order_condition=" (case when o.status in (10) then o.post_create_time else o.create_time end) desc ";
            }  elseif($order_status == 88){
                $where2.="  ";
                $order_condition=" (case when o.status in (10) then o.post_create_time else o.create_time end) desc ";
            }else {
                $where2.=" and o.status=$order_status  ";
            }
            $this->assign('order_status',$order_status);
            $order_condition=" o.create_time desc ";
        } else {
            $where2.=" and o.status not in (1,6,8,9,11)  ";
            $order_condition=" (case when o.status in (10) then o.post_create_time else o.create_time end) desc ";
        }

        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }





        if($start_time && $end_time) {
            $where2.=" and o.create_time>='$start_time' and o.create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }

        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_business as b on o.business_id=b.id and o.is_prize in (0,3,6) ")
                        ->join(" left join baby_toys_prize as p on o.business_id=p.id and o.is_prize='1' ")
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $orderres = $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id and o.is_prize in (0,3,6) ")
                    ->join(" left join baby_toys_prize as p on o.business_id=p.id and o.is_prize='1' ")
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->where($where2)
                    ->field('o.id,o.order_num,o.create_time,o.post_create_time,o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.address,o.combined_order_id,o.toys_number,o.delivery_time,p.prize_title2,o.is_prize')
                    ->order($order_condition)
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        foreach ($orderres as $key => $value) {
            $order_id=$value['id'];
            $order_num=$value['order_num'];
            $combined_order_id=$value['combined_order_id'];
            $prize_title2=$value['prize_title2'];
            $is_prize=$value['is_prize'];
            $order_info=$order_id."<br/>".$order_num."<br/>".$combined_order_id;
            $tmp_user_id=$value['user_id'];
            $user_info="";
            $user_info.=$tmp_user_id;
            $tmp_nick_name=$value['nick_name'];
            if($tmp_nick_name) {
                $user_info.="<br/>".$tmp_nick_name;
            }
            $tmp_mobile=$value['mobile'];
            if($tmp_mobile) {
                $user_info.="<br/>".$tmp_mobile;
            }
            $post_create_time=$value['post_create_time'];
            $create_time=$value['create_time'];
            $business_id=$value['business_id'];
            $business_title=$value['business_title'];
            $toys_number=$value['toys_number'];
            if($is_prize==1) {
                $business_info=$prize_title2."【礼】";
            } else {
                $business_info=$business_id."<br/>".$business_title."<br/>".$toys_number;
            }
            $tmp_status=$value['status'];
            if($tmp_status==2) {
                if($toys_number) {
                    $status_name="待派单";
                } else {
                    $status_name="备货中";
                }
                $listing_status=2;
            }elseif($tmp_status == 1){
                $status_name="待支付";
                $listing_status=1;
            } elseif($tmp_status==5) {
                $status_name="送货中";
                $listing_status=5;
            } elseif($tmp_status==6) {
                $status_name="玩乐中";
                $listing_status=8;
            } elseif($tmp_status==7) {
                $status_name="待入库";
                $listing_status=9;
            } elseif($tmp_status==8){
                $status_name="退款中";
            } elseif($tmp_status==9){
                $status_name="已退款";
            } elseif($tmp_status==10) {
                $status_name="待取回";
                $listing_status=7;
            } elseif($tmp_status==11) {
                $status_name="已入库";
                $listing_status=11;
            }elseif($tmp_status==14) {
                $status_name="恢复玩乐";
                $listing_status=14;
            }
            $lisres =M("toys_listing")
                    ->where("state='0' and order_id=$order_id and status=$listing_status ")
                    ->field('postman_id')
                    ->find();
            if($lisres) {
                $postman_id=$lisres['postman_id'];
            } else {
                $postman_id=0;
            }
            if($postman_id>0) {
                $postuser_id=M("user")->where("id=$postman_id ")->field('nick_name')->find();
                $postman_name=$postuser_id['nick_name'];
            } else {
                $postman_name="";
            }
            
            $address_info="";
            $address=$value['address'];
            $tmp_ord_user_name=$value['ord_user_name'];
            if($tmp_ord_user_name) {
                $address_info.=$tmp_ord_user_name;
            }
            $tmp_ord_mobile=$value['ord_mobile'];
            if($tmp_ord_mobile) {
                $address_info.="<br/>".$tmp_ord_mobile;
            }
            if($address) {
                $address_info.="<br/>".$address;
            }
            $delivery_time=$value['delivery_time'];

            $res[]=array(
                'id'=>$order_id,
                'order_info'=>$order_info,
                'user_info'=>$user_info,
                'address'=>$address_info,
                'post_create_time'=>$post_create_time,
                'create_time'=>$create_time,
                'business_info'=>$business_info,
                'status_name'=>$status_name,
                'delivery_time'=>$delivery_time,
                'postman_name'=>$postman_name
                );
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    
    public function editdelivery(){
        $edit_order_id = I("edit_order_id");
        $delivery_time = I("delivery_time");
        $where['id'] = array('in',$edit_order_id);
        $data['delivery_time'] = $delivery_time ;
        $result = M('toys_order')->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getlogisticslist?order_id=$edit_order_id");
    }
    //编辑物流页面
    public function updatelogistics(){
        //配送人
        $getUserRes =M('toys_order')
                    ->where("state='0' and user_role='4' ")
                    ->field('id,nick_name')
                    ->select();

        $edit_order_id= I("id");
        $orderres =M('toys_order')
                    ->where("id=$edit_order_id")
                    ->field('id,status,business_id,toys_number')
                    ->find();
        $order_id=$orderres['id'];
        $order_status=$orderres['status'];
        $order_toys_number=$orderres['toys_number'];
        $business_id=$orderres['business_id'];
        if($business_id>0) {
            $busres=M('toys_business')
                    ->where("id=$business_id")
                    ->field('business_title,business_pics')
                    ->find();
            $business_title=$busres['business_title'];
            $business_pics=$busres['business_pics'];

            $busLisCon="(business_id=$business_id and state='0' and out_state='0' and is_use='0' ) ";
            if($order_toys_number) {
                $busLisCon.=" or (id=$order_toys_number) ";
            }
            $busLisres=M('toys_business_listing')
                    ->where($busLisCon)
                    ->field('id,tag_id')
                    ->select();
        }
        if(empty($business_title)) {
            $business_title="";
        }
        if($business_pics) {
            $img="http://api.meimei.yihaoss.top/".$business_pics;
        } else {
            $img="";
        }
        $getBusListing=array();
        if($busLisres) {//玩具编号
            foreach ($busLisres as $key => $value) {
                $tag_id=$value['tag_id'];
                $lis_id=$value['id'];
                $getBusListing[]=array(
                    'id'=>$lis_id,
                    'listing_name'=>$lis_id."-".$business_id."-".$tag_id."仓库"
                    );
            }
        }
        $res=array(
            'id'=>$order_id,
            'status'=>$order_status,
            'business_title'=>$business_title,
            'img'=>$img,
            'toys_number'=>$toys_number
            );
        $this->assign('getBusListing',$getBusListing);
        $this->assign('res',$res);
        $this->assign('userInfo',$getUserRes);
        $this->display();   
    }
    //玩具物流列表
    public function getlogisticslist2(){
        $str_card_info=$this->getCardInfo();
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="case when status=7 then o.state!='77' else  o.state='0' end   and o.business_id not in ($str_card_info) ";
        $model = new Model();

        $order_id=trim(I("order_id"));
        $order_num=trim(I("order_num"));
        $order_status=trim(I("order_status"));
        $business_id=trim(I("business_id"));
        $start_time=I("start_time");
        $end_time=I("end_time");
        $user_id=trim(I("user_id"));
        $toys_number = trim(I("toys_number"));

        if($user_id) {
            if(!$order_id && !$order_num && !$order_status && !$business_id && !$start_time && !$end_time && !$toys_number){
                $order_status = "77" ;
            }
            $where2.=" and o.user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }

        if($toys_number) {
            if(!$order_id && !$order_num && !$order_status && !$business_id && !$start_time && !$end_time && !$user_id){
                $order_status = "88" ;
            }
            $where2.=" and o.toys_number=$toys_number  ";
            $this->assign('toys_number',$toys_number);
        }

        if($order_id) {
            $where2.=" and o.id=$order_id ";
            $this->assign('order_id',$order_id);
        }

        if($order_num) {
            $where2.=" and o.order_num like '%$order_num%' ";
            $this->assign('order_num',$order_num);
        }

        if($order_status) {
            if($order_status == 1){
                $where2.= " and o.status=2 and o.is_ready =0 and o.toys_number is null  ";
            } elseif ($order_status == 2){
                $where2.= " and ((o.status=2 and o.toys_number is not null ) or (o.status=10)) ";
                $ascsql = "o.user_id ASC";
            } elseif($order_status == 77){
                $where2.="  ";
                $order_condition=" (case when o.status in (10) then o.post_create_time else o.create_time end) desc ";
            }  elseif($order_status == 88){
                $where2.="  ";
                $order_condition=" (case when o.status in (10) then o.post_create_time else o.create_time end) desc ";
            }else {
                $where2.=" and o.status=$order_status  ";
            }
            $this->assign('order_status',$order_status);
            $order_condition=" o.create_time desc ";
        } else {
            $where2.=" and o.status not in (1,6,8,9,11)  ";
            $order_condition=" (case when o.status in (10) then o.post_create_time else o.create_time end) desc ";
        }

        if($business_id) {
            $where2.=" and o.business_id=$business_id  ";
            $this->assign('business_id',$business_id);
        }





        if($start_time && $end_time) {
            $where2.=" and o.create_time>='$start_time' and o.create_time<='$end_time'  ";
            $this->assign('start_time',$start_time);
            $this->assign('end_time',$end_time);
        } elseif($start_time) {
            $where2.=" and o.create_time>='$start_time' ";
            $this->assign('start_time',$start_time);
        }

        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_business as b on o.business_id=b.id")
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $orderres = $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->where($where2)
                    ->field('o.id,o.order_num,o.create_time,o.post_create_time,o.user_id,u.nick_name,u.mobile,o.user_name as ord_user_name,o.mobile as ord_mobile,o.business_id,b.business_title,o.status,o.address,o.combined_order_id,o.toys_number,o.delivery_time')
                    ->order($order_condition)
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        foreach ($orderres as $key => $value) {
            $order_id=$value['id'];
            $order_num=$value['order_num'];
            $combined_order_id=$value['combined_order_id'];
            $order_info=$order_id."<br/>".$order_num."<br/>".$combined_order_id;
            $tmp_user_id=$value['user_id'];
            $user_info="";
            $user_info.=$tmp_user_id;
            $tmp_nick_name=$value['nick_name'];
            if($tmp_nick_name) {
                $user_info.="/".$tmp_nick_name;
            }
            $tmp_mobile=$value['mobile'];
            if($tmp_mobile) {
                $user_info.="/".$tmp_mobile;
            }
            $post_create_time=$value['post_create_time'];
            $create_time=$value['create_time'];
            $business_id=$value['business_id'];
            $business_title=$value['business_title'];
            $toys_number=$value['toys_number'];
            $business_info=$business_id."/".$business_title."/".$toys_number;
            $tmp_status=$value['status'];
            if($tmp_status==2) {
                if($toys_number) {
                    $status_name="待派单";
                } else {
                    $status_name="备货中";
                }
                $listing_status=2;
            }elseif($tmp_status == 1){
                $status_name="待支付";
                $listing_status=1;
            } elseif($tmp_status==5) {
                $status_name="送货中";
                $listing_status=5;
            } elseif($tmp_status==6) {
                $status_name="玩乐中";
                $listing_status=8;
            } elseif($tmp_status==7) {
                $status_name="待入库";
                $listing_status=9;
            } elseif($tmp_status==8){
                $status_name="退款中";
            } elseif($tmp_status==9){
                $status_name="已退款";
            } elseif($tmp_status==10) {
                $status_name="待取回";
                $listing_status=7;
            } elseif($tmp_status==11) {
                $status_name="已入库";
                $listing_status=11;
            }elseif($tmp_status==14) {
                $status_name="恢复玩乐";
                $listing_status=14;
            }
            $lisres =M("toys_listing")
                    ->where("state='0' and order_id=$order_id and status=$listing_status ")
                    ->field('postman_id')
                    ->find();
            if($lisres) {
                $postman_id=$lisres['postman_id'];
            }
            if($postman_id) {
                $postuser_id=M("user")->where("id=$postman_id ")->field('nick_name')->find();
                $postman_name=$postuser_id['nick_name'];
            }
            if(empty($postman_name)) {
                $postman_name="";
            }
            $address_info="";
            $address=$value['address'];
            $tmp_ord_user_name=$value['ord_user_name'];
            if($tmp_ord_user_name) {
                $address_info.=$tmp_ord_user_name;
            }
            $tmp_ord_mobile=$value['ord_mobile'];
            if($tmp_ord_mobile) {
                $address_info.="/".$tmp_ord_mobile;
            }
            if($address) {
                $address_info.="/".$address;
            }
            $delivery_time=$value['delivery_time'];

            $res[]=array(
                'id'=>$order_id,
                'order_num'=>$order_num,
                'combined_order_id'=>$combined_order_id,
                //'order_info'=>$order_info,
                'user_info'=>$user_info,
                'address'=>$address_info,
                'post_create_time'=>$post_create_time,
                'create_time'=>$create_time,
                'business_info'=>$business_info,
                'status_name'=>$status_name,
                'delivery_time'=>$delivery_time,
                'postman_name'=>$postman_name
                );
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    //派单列表
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
        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6)  ";
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
//                    array('new_create_time','下单时间'),
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
                    //查询最新的订单表地址start
                    $new_order_address_arr = $model->table('baby_toys_order')
                        ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                        ->field('address')
                        ->order('create_time asc') //status asc,post_
                        ->select();
                    $order_address_arr = array();
                    $address_only = 0;
                    if($new_order_address_arr){
                        foreach($new_order_address_arr as $val){
                            $order_address_arr[] = $val['address'];
                        }
                        $order_address = $order_address_arr[0];
                        $order_address_arr = array_unique($order_address_arr);
                        $address_num = count($order_address_arr);
                        if($address_num>1){
                            $address_only = 1;
                            $address_chose = "";
                            foreach($order_address_arr as $vv){
                                $address_chose .= "\n□".$vv;
                            }
                        }
                    }
                    //查询最新的订单表地址end
                    $user_info=$send_toys=$pick_toys="";
                    if($order_user_name) {
                        $user_info.=$order_user_name;
                    }
                    if($order_mobile) {
                        $user_info.="\n".$order_mobile;
                    }
                    if($order_address && ($address_only==0)) {
                        $user_info.="\n".$order_address;
                    }
                    if($address_only==1){
                        $user_info.=$address_chose;
                    }
                    $send_toys=$pick_toys="";

                    //配送
                    $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","\n");
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
                    //取回备注
                    $toys_back_remark = $returnpickInfo['remark'];
                    $remark.=$toys_back_remark;
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                        $business_parts_lists = $returnpickInfo['business_parts_list'];
                    }
                    $user_act = " □ 玩具实物和所租是否相符\n □ 需安装玩具是否安装\n □ 玩具是否干净\n 签收人：____________";
                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id."\n".$tmp_new_create_time,
                        'user_info'=>$user_info,
//                        'new_create_time'=>$tmp_new_create_time,
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
                $tmp_user_id_number=$value['user_id'];
                $order_address=$value['address'];

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
                }
                $res[]=array(
                    'user_id_number'=>$tmp_user_id_number,
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
    
    //派单加备注页面
    public function editsendremark() {
        $user_id=I("user_id");
        $str_card_info=$this->getCardInfo();
        $getSendRes=M("toys_order")
                    ->where("status in (2,5,10,17) and business_id not in ($str_card_info) and state='0' and user_id=$user_id ")
                    ->field('stock_remark')
                    ->order("id desc")
                    ->find();
        $remark=$getSendRes['stock_remark'];
        $this->assign('user_id',$user_id);
        $this->assign('remark',$remark);
        $this->display();
    }
    //备货列表
    public function getstocklist() {
        $this->checkSession(); 
        $search_business_id=I("business_id");
        $excel_state=I("excel_state");
        $str_card_info=$this->getCardInfo(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="o.status in (2) and o.business_id not in ($str_card_info) and o.state='0' and ((o.toys_number is null) or (o.toys_number='') ) ";
        if($search_business_id) {
            $where2.=" and o.business_id=$search_business_id ";
            $this->assign('search_business_id',$search_business_id);
        }
        $model = new Model();
        if($excel_state==1) {
            $getexcelBusRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where($where2)
                    ->field("o.business_id,b.business_title ")
                    ->group("o.business_id")
                    ->order("o.business_id desc")
                    ->select();
            $xlsData=array();
            if($getexcelBusRes) {
                $xlsName="备货";
                $xlsCell  = array(
                    array('business_id','玩具id'),
                    array('business_title','玩具名'),
                    array('ready_number','待备货数量')  
                );
                foreach ($getexcelBusRes as $key => $value) {
                    $tmp_business_id=$value['business_id'];
                    $tmp_business_title=$value['business_title'];
                    $ready_number=M('toys_order')
                        ->where("status in (2) and business_id in ($tmp_business_id) and state='0' and ((toys_number is null) or (toys_number='') ) ")
                        ->count();//待备货数量
                    
                    $xlsData[]=array(
                        'business_id'=>$tmp_business_id,
                        'business_title'=>$tmp_business_title,
                        'ready_number'=>$ready_number
                    );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_order as o')
                        ->where($where2)
                        ->field('o.business_id')
                        ->group("o.business_id")
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where($where2)
                    ->field("o.business_id,b.business_title ")
                    ->group("o.business_id")
                    ->order("o.business_id desc")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_business_id=$value['business_id'];
                $tmp_business_title=$value['business_title'];
                $ready_number=M('toys_order')
                    ->where("status in (2) and business_id in ($tmp_business_id) and state='0' and ((toys_number is null) or (toys_number='') ) ")
                    ->count();//待备货数量
                $res[]=array(
                    'business_id'=>$tmp_business_id,
                    'business_title'=>$tmp_business_title,
                    'ready_number'=>$ready_number
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    public function getstocklist0814() {
        $this->checkSession(); 
        $search_business_id=I("business_id");
        $excel_state=I("excel_state");
        $str_card_info=$this->getCardInfo(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="o.status in (2) and o.business_id not in ($str_card_info) and o.state='0' ";
        if($search_business_id) {
            $where2.=" and o.business_id=$search_business_id ";
            $this->assign('search_business_id',$search_business_id);
        }
        $model = new Model();
        if($excel_state==1) {
            $getexcelBusRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where($where2)
                    ->field("o.business_id,b.business_title ")
                    ->group("o.business_id")
                    ->order("o.business_id desc")
                    ->select();
            $xlsData=array();
            if($getexcelBusRes) {
                $xlsName="备货";
                $xlsCell  = array(
                    array('business_id','玩具id'),
                    array('business_title','玩具名'),
                    array('total_number','备货总数量'),
                    array('ready_number','待备货数量'),
                    array('over_number','已备货数量/编号')   
                );
                foreach ($getexcelBusRes as $key => $value) {
                    $tmp_business_id=$value['business_id'];
                    $tmp_business_title=$value['business_title'];
                    
                    $total_number=M('toys_order')
                        ->where("status in (2) and business_id in ($tmp_business_id) and state='0' ")
                        ->count();//总数量

                    $ready_number=M('toys_order')
                        ->where("status in (2) and business_id in ($tmp_business_id) and state='0' and ((toys_number is null) or (toys_number='') ) ")
                        ->count();//待备货数量
                    $getHaveToysNumberRes=M('toys_order')
                        ->where("status in (2) and business_id in ($tmp_business_id) and state='0' and toys_number is not null ")
                        ->field('toys_number')
                        ->select();
                    $arr_toys_number=array();
                    $str_toys_number=$over_number="";
                    $getHaveToysNumber=count($getHaveToysNumberRes);
                    $over_number.=$getHaveToysNumber;
                    if($getHaveToysNumberRes) {
                        foreach ($getHaveToysNumberRes as $k => $val) {
                            $arr_toys_number[]=$val['toys_number'];
                        }
                        $str_toys_number=implode(",", $arr_toys_number);
                        $over_number.="【".$str_toys_number."】";
                    } else {
                        $getHaveToysNumberCount=0;
                    }
                    
                    $xlsData[]=array(
                        'business_id'=>$tmp_business_id,
                        'business_title'=>$tmp_business_title,
                        'total_number'=>$total_number,
                        'ready_number'=>$ready_number,
                        'over_number'=>$over_number
                    );
                }
                $this->exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_order as o')
                        ->where($where2)
                        ->field('o.business_id')
                        ->group("o.business_id")
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_business as b on o.business_id=b.id")
                    ->where($where2)
                    ->field("o.business_id,b.business_title ")
                    ->group("o.business_id")
                    ->order("o.business_id desc")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_business_id=$value['business_id'];
                $tmp_business_title=$value['business_title'];
                
                $total_number=M('toys_order')
                    ->where("status in (2) and business_id in ($tmp_business_id) and state='0' ")
                    ->count();//总数量

                $ready_number=M('toys_order')
                    ->where("status in (2) and business_id in ($tmp_business_id) and state='0' and ((toys_number is null) or (toys_number='') ) ")
                    ->count();//待备货数量
                $getHaveToysNumberRes=M('toys_order')
                    ->where("status in (2) and business_id in ($tmp_business_id) and state='0' and toys_number is not null ")
                    ->field('toys_number')
                    ->select();
                $arr_toys_number=array();
                $str_toys_number=$over_number="";
                $getHaveToysNumber=count($getHaveToysNumberRes);
                $over_number.=$getHaveToysNumber;
                if($getHaveToysNumberRes) {
                    foreach ($getHaveToysNumberRes as $k => $val) {
                        $arr_toys_number[]=$val['toys_number'];
                    }
                    $str_toys_number=implode(",", $arr_toys_number);
                    $over_number.="【".$str_toys_number."】";
                } else {
                    $getHaveToysNumberCount=0;
                }
                
                $res[]=array(
                    'business_id'=>$tmp_business_id,
                    'business_title'=>$tmp_business_title,
                    'total_number'=>$total_number,
                    'ready_number'=>$ready_number,
                    'over_number'=>$over_number
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //黑名单列表
    public function getblacklist() {
        $this->checkSession(); 
        $user_id=I("user_id"); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="state='0' ";
        if($user_id) {
            $where2.=" and user_id=$user_id ";
            $this->assign('user_id',$user_id);
        }
        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_black")->where($where2)->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getBlackRes= M("toys_black")
                    ->where($where2)
                    ->order("id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getBlackRes) {
            foreach ($getBlackRes as $key => $value) {
                $tmp_id=$value['id'];
                $tmp_user_id=$value['user_id'];
                $business_id=$value['business_id'];
                $remark=$value['remark'];
                $status=$value['status'];
                if($status==2) {
                    $status_name="更重";
                } elseif($status==1) {
                    $status_name="重";
                } else {
                    $status_name="轻微";
                }
                $business_info="";
                if($business_id>0) {
                    $getBusRes=M("toys_business")->where("id=$business_id ")
                            ->field('business_title')
                            ->find();
                    $business_title=$getBusRes['business_title'];
                    $business_info.=$business_id."<br/>".$business_title;
                }
                $res[]=array(
                    'id'=>$tmp_id,
                    'user_id'=>$tmp_user_id,
                    'business_info'=>$business_info,
                    'remark'=>$remark,
                    'status_name'=>$status_name
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //黑名单列表
    public function newbusinesslisting() {
        $this->checkSession();
        $business_id=I("business_id");
        $business_id = trim($business_id);
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="state='0' and status=1 ";
        if($business_id) {
            $where2.=" and business_id=$business_id ";
            $this->assign('business_id',$business_id);
        }
        $model = new Model();
        //满足条件的总记录数
        $count= M("toys_new_business_listing")->where($where2)->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getBlackRes= M("toys_new_business_listing")
            ->where($where2)
            ->order("id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();
        if($getBlackRes) {
            foreach ($getBlackRes as $key => $value) {
                $id=$value['id'];
                $business_id=$value['business_id'];
                $create_time=$value['create_time'];
                $status=$value['status'];
                $business_title = $value['business_title'];
                $business_pic = "https://api.meimei.yihaoss.top/".$value['business_pic'];


                $res[]=array(
                    'id'=>$id,
                    'business_id'=>$business_id,
                    'create_time'=>$create_time,
                    'business_title'=>$business_title,
                    'business_pic'=>$business_pic,
                    'status'=>$status
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除新增申请列表
    public function delnewbusinesslisting(){
        $id = $_GET['id'];
        $album = M('toys_new_business_listing');
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/newbusinesslisting.html");
    }
    //删除黑名单列表
    public function operator_blackstate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_black');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getblacklist.html");
    }
    //添加/编辑黑名单信息页面
    public function editblack() {
        $id=I("id"); 
        if($id>0) {
            $getBlackRes=M("toys_black")->where("id=$id")->find();
        }
        $this->assign('res',$getBlackRes);//赋值数据集
        $this->display();
    }
    //添加玩具07-11
    public function publictoysnew() {
        $this->checkSession();
        $id = $_GET['id'];
        if($id){
            $is_new = 0;
        }else{
            $is_new = 1;
        }
        $this->assign('is_new',$is_new);//1发布新玩具，0编辑玩具

        $where2['id'] = array('in',$id);
        $list = M('toys_business')
            ->where($where2)
            ->find();
        $res=array();
        $way=$list['way'];
        $tmp_market_price=$list['market_price'];
        $tmp_sell_price=$list['sell_price'];
        $tmp_member_price=$list['member_price'];
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
            'parts_price'=>$list['parts_price'],
            'member_price'=>$tmp_member_price,
            'age'=>$list['age'],
            'root_img_id'=>$list['root_img_id'],
            'is_card'=>$list['is_card'],
            'number'=>$list['number'],
            'is_active'=>$list['is_active'],
            'contract_number'=>$list['contract_number'],
            'total_number'=>$list['total_number'],
            'number_title'=>$list['number_title'],
            'service_number'=>$list['service_number'],
            'battery_price'=>$list['battery_price'],
            'battery_number1'=>$list['battery_number1'],
            'battery_number2'=>$list['battery_number2'],
            'battery_number3'=>$list['battery_number3'],
            'battery_number4'=>$list['battery_number4'],
            'battery_number5'=>$list['battery_number5'],
            'battery_number6'=>$list['battery_number6'],
            'battery_number7'=>$list['battery_number7'],
            'battery_number8'=>$list['battery_number8'],
            'key_words'=>$list['key_words'],
            'weight'=>$list['weight'],
            'size'=>$list['size'],
            'business_brand'=>$list['business_brand']
        );
        if(empty($id)) {
            $res=array(
                'way'=>"1"
            );
        }
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
            if($array_pics[4]) {
                $res['pic5']="http://api.meimei.yihaoss.top/".$array_pics[4];
            }
            if($array_pics[5]) {
                $res['pic6']="http://api.meimei.yihaoss.top/".$array_pics[5];
            }
            if($array_pics[6]) {
                $res['pic7']="http://api.meimei.yihaoss.top/".$array_pics[6];
            }
            if($array_pics[7]) {
                $res['pic8']="http://api.meimei.yihaoss.top/".$array_pics[7];
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
        //$this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //添加新编号入库
    public function publictoysnewnumberin(){
        $this->checkSession();
        $business_id = $_GET['business_id'];
        $this->assign("business_id",$business_id);
        $source = I("source");
        $new_id = I("id");
        $this->assign("source",$source);
        $this->assign("new_id",$new_id);

        //查询操作人
        $post_img = M('user');
        $model = new Model();
        $list = $post_img
            ->where("state='0' and user_role in ('4','6') and id not in('1','182','674','305416','312287','423859','424069','425412','307167','95371','305060','426894','309806','421524','427215','427592')  ")
            ->field('id,user_name')
            ->select();
        $res=array();
        foreach($list as $v){
            $user_id = $v['id'];
            $user_name = $v['user_name'];
            $res[] = array(
                'user_id'=>$user_id,
                'user_name'=>$user_name
            );
        }
        $this->assign("res",$res);
        $this->display();
    }

    //添加新编号出库
    public function publictoysnewnumberout(){
        $this->checkSession();
        $business_id = $_GET['business_id'];
        $this->assign("business_id",$business_id);
        $source = I("source");
        $new_id = I("id");
        $this->assign("source",$source);
        $this->assign("new_id",$new_id);

        //查询操作人
        $post_img = M('user');
        $model = new Model();
        $list = $post_img
            ->where("state='0' and user_role in ('4','6') and id not in('1','182','674','305416','312287','423859','424069','425412','307167','95371','305060','426894','309806','421524','427215','427592')  ")
            ->field('id,user_name')
            ->select();
        $res=array();
        foreach($list as $v){
            $user_id = $v['id'];
            $user_name = $v['user_name'];
            $res[] = array(
                'user_id'=>$user_id,
                'user_name'=>$user_name
            );
        }
        $this->assign("res",$res);
        $this->display();
    }

    //卡操作页面
    public function publiccard() {
        $id=I("id"); 
        if($id>0) {
            $getBusRes=M("toys_business")->where("id=$id")->find();
            $root_img_id=$getBusRes['root_img_id'];
            $old_Imgs=$getBusRes['old_business_pics'];
            $Imgs=$getBusRes['business_pics'];
            if($Imgs) {
                $array_pics = explode(";",$Imgs);
            } else {
                $array_pics=array();
            }   
            if($array_pics) {
                if($root_img_id>0) {
                    if($array_pics[0]) {
                        $getBusRes['pic1']="http://api.meimei.yihaoss.top/".$array_pics[0];
                    }
                    if($array_pics[1]) {
                        $getBusRes['pic2']="http://api.meimei.yihaoss.top/".$array_pics[1];
                    }
                    if($array_pics[2]) {
                        $getBusRes['pic3']="http://api.meimei.yihaoss.top/".$array_pics[2];
                    }
                    if($array_pics[3]) {
                        $getBusRes['pic4']="http://api.meimei.yihaoss.top/".$array_pics[3];
                    }
                } else {
                    if($array_pics[0]) {
                        $getBusRes['cover']="http://api.meimei.yihaoss.top/".$array_pics[0];
                    }
                    if($old_Imgs) {
                        $getBusRes['old_cover']="http://api.meimei.yihaoss.top/".$old_Imgs;
                    }
                }
            }    
        }
        $this->assign('res',$getBusRes);//赋值数据集
        $this->display();
    }
    //玩具帖子列表
    public function toysreplylist(){
        $this->checkSession();
        $id = $_GET['id'];
        $str_card_info=$this->getCardInfo();
        $arr_card_info = explode(',',$str_card_info);
        $is_card = 0;
        if(in_array($id,$arr_card_info)){
            $is_card = 1;  //判断是不是卡
        }
        $this->assign('is_card',$is_card);

        import("ORG.Util.Page");
        //先查相册的数据库，
        $post_img = M('toys_business');
        $where2['state'] ='0';
        $model = new Model();
        $list = $post_img
            ->where("state='0' and (root_img_id={$id} or id={$id})  ")
            ->field('id,business_des,business_pics')
            ->order('root_img_id asc,id asc')
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
    //采购建议操作页面
    public function pubpuradvice() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_purchasing_advice")
                        ->where("id=$id ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }
    //采购建议列表
    public function getpuradvice() {
        $search_user_id=I("search_user_id");
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="state='0' ";
        if($search_user_id>0) {
            $where2.=" and user_info like '%$search_user_id%' ";
            $this->assign('search_user_id',$search_user_id);
        }
        $model = new Model();
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_purchasing_advice')
                        ->where($where2)
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_purchasing_advice')
                    ->where($where2)
                    ->order("id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $create_time=$value['create_time'];
                $user_info=$value['user_info'];
                $toys_info=$value['toys_info'];
                $public_name=$value['public_name'];
                $id=$value['id'];
                $tmp_purchase_reamrk=$value['purchase_reamrk'];
                $tmp_reply_reamrk=$value['reply_reamrk'];
                $purchase_time=$value['purchase_time'];
                $reply_time=$value['reply_time'];
                $purchase_reamrk=$reply_reamrk="";
                if($tmp_purchase_reamrk) {
                    $purchase_reamrk=$tmp_purchase_reamrk."<br/>".$purchase_time;
                }
                if($tmp_reply_reamrk) {
                    $reply_reamrk=$tmp_reply_reamrk."<br/>".$reply_time ;
                }
                $res[]=array(
                    'user_info'=>$user_info,
                    'toys_info'=>$toys_info,
                    'purchase_reamrk'=>$purchase_reamrk,
                    'id'=>$id,
                    'reply_reamrk'=>$reply_reamrk,
                    'create_time'=>$create_time,
                    'public_name'=>$public_name
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除采购建议
    public function operator_advicestate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_purchasing_advice');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getpuradvice.html");
    }
    //删除电池价格
    public function operator_baterrypricestate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_battery');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getbaterypricelist.html");
    }
     //电池价格列表
    public function getbaterypricelist() {
        $this->checkSession(); 
        $search_type=I("search_type");
        $search_brand=I("search_brand");
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="state='0' ";
        if($search_type) {
            $where2.=" and type=$search_type ";
            $this->assign('search_type',$search_type);
        }
        if($search_brand) {
            $where2.=" and brand=$search_brand ";
            $this->assign('search_brand',$search_brand);
        }
        $model = new Model();
        //满足条件的总记录数
        $count= $model->table('baby_toys_battery')
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_battery')
                    ->where($where2)
                    ->order("id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_id=$value['id'];
                $type=$value['type'];
                $brand=$value['brand'];
                $price=$value['price'];
                $market_price=$value['market_price'];
                if($price<=0) {
                    $price="";
                }
                if($market_price<=0) {
                    $market_price="";
                }
                if($brand==1) {
                    $battery_brand="华太";
                } elseif($brand==2) {
                    $battery_brand="南孚";
                } elseif($brand==3) {
                    $battery_brand="双鹿";
                } else {
                    $battery_brand="";
                }
                if($type==1) {
                    $battery_type="1号电池";
                } elseif($type==2) {
                    $battery_type="2号电池";
                } elseif($type==3) {
                    $battery_type="3号电池";
                } elseif($type==4) {
                    $battery_type="4号电池";
                } elseif($type==5) {
                    $battery_type="5号电池";
                } elseif($type==6) {
                    $battery_type="6号电池";
                } elseif($type==7) {
                    $battery_type="7号电池";
                } elseif($type==8) {
                    $battery_type="纽扣电池";
                }
                $res[]=array(
                    'id'=>$tmp_id,
                    'battery_type'=>$battery_type,
                    'battery_brand'=>$battery_brand,
                    'price'=>$price,
                    'market_price'=>$market_price
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    //电池操作页面
    public function pubbateryprice() {
        $id=I("id"); 
        if($id>0) {
            $getBusRes=M("toys_battery")->where("id=$id")->find();
        }
        $this->assign('res',$getBusRes);//赋值数据集
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
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/toysreplylist?id=$find_img_id");
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
        for($start =$endtime ; $start >$begintime; $start -= 24 * 3600) {
            $show_start=date("Y-m-d H:i:s",strtotime("-1 day",$start) );
            $show_start2=date("Y-m-d H:i:s", $start );
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
            $in_list = $model-> table('baby_toys_order as o')
                ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where(" o.status=11 and o.is_prize in (0,3,6) and i.create_time>'$show_start' and i.create_time<'$show_start2' and i.status='11' and i.state='0' ")//
                ->select();
            $in_count=count($in_list);
            $in_user_list = $model-> table('baby_toys_order as o')
                ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where(" o.status=11 and o.is_prize in (0,3,6) and i.create_time>'$show_start' and i.create_time<'$show_start2' and i.status='11' and i.state='0' ")//
                ->select();
            $in_user_count=count($in_user_list);
            $in_count_info=$in_count."/".$in_user_count;

            //取消送货
            $cancel_list = M('toys_order')
                ->field('id')
                ->where("state='0' and post_create_time>'$show_start' and post_create_time<'$show_start2' and is_prize in (0,3,6) and cancel_reason in (1,2,3) and status in (2,17,10) ")
                ->select();
            $cancel_count=count($cancel_list);
            $cancel_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and post_create_time>'$show_start' and post_create_time<'$show_start2' and is_prize in (0,3,6) and cancel_reason in (1,2,3) and status in (2,17,10) ")
                ->select();
            $cancel_user_count=count($cancel_user_list);
            $cancel_count_info=$cancel_count."/".$cancel_user_count;


            //送货中[送]
            $ordering_list =$model-> table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where("o.state='0' and o.status=5 and o.is_prize in (0,3,6) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='5' and i.state='0' ")
                ->select();
            $ordering_count=count($ordering_list);
            $ordering_user_list =$model->table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where("o.state='0' and o.status=5 and o.is_prize in (0,3,6) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='5' and i.state='0' ")
                ->select();
            $ordering_user_list_count=count($ordering_user_list);

            //送货中[取]
            $back_postid_list =$model->table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where("o.state='0' and o.status=10 and o.is_prize in (0,3,6) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='7' and i.state='0' and i.postman_id>0 ")
                ->select();
            $back_postid_count=count($back_postid_list);
            $back_postid_user_list = $model->table('baby_toys_order as o')
                       ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where("o.state='0' and o.status=10 and o.is_prize in (0,3,6) and i.post_create_time>'$show_start' and i.post_create_time<'$show_start2' and i.status='7' and i.state='0' and i.postman_id>0 ")
                ->select();
            $back_postid_user_count=count($back_postid_user_list);

            $ordering_count_info=$ordering_count."—".$ordering_user_list_count."/".$back_postid_count."—".$back_postid_user_count;

            //玩乐中
            $fun_list = $model->table('baby_toys_order as o')
                ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.id')
                ->where(" o.is_prize in (0,3,6) and i.create_time>'$show_start' and i.create_time<'$show_start2' and i.status='8' and i.state='0' ")//o.state='0' and o.status=6 and
                ->select();
            $fun_count=count($fun_list);
            $fun_user_list = $model->table('baby_toys_order as o')
                ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                ->field('o.user_id')
                ->group("o.user_id")
                ->where(" o.is_prize in (0,3,6) and i.create_time>'$show_start' and i.create_time<'$show_start2' and i.status='8' and i.state='0' ")//o.state='0' and o.status=6 and
                ->select();
            $fun_user_list_count=count($fun_user_list);
            $fun_count_info=$fun_count."/".$fun_user_list_count;

            //待派单[送]
            $send_list = M('toys_order')
                ->field('id')
                ->where("state='0' and status in (2,17) and business_id not in ($str_card_info) and toys_number<>'' and is_prize in (0,3,6) ")
                ->select();
            $send_count=count($send_list);
            $send_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and status in (2,17) and business_id not in ($str_card_info) and toys_number<>'' and is_prize in (0,3,6) ")
                ->select();
            $send_user_list_count=count($send_user_list);
            $send_count_info=$send_count."/".$send_user_list_count;


            //备货中
            $prepare_list = M('toys_order')
                ->field('id')
                ->where("state='0' and status=2 and business_id not in ($str_card_info) and ( (toys_number is null) or (toys_number='') ) and is_prize in (0,3,6) and create_time>'$show_start' and create_time<'$show_start2' ")
                ->select();
            $prepare_count=count($prepare_list);
            $prepare_user_list = M('toys_order')
                ->field('user_id')
                ->group("user_id")
                ->where("state='0' and status=2 and business_id not in ($str_card_info) and ( (toys_number is null) or (toys_number='') ) and is_prize in (0,3,6) and create_time>'$show_start' and create_time<'$show_start2' ")
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

    public function getoutbusinessinfo1114(){
        $start_time=I('start_time');
        $end_time=I('end_time');
        $str_card_info=$this->getCardInfo();
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
        //玩具种类
        $toys_type_list = M('toys_business')
            ->field('id')
            ->where("state='0' and root_img_id=0 and create_time>'$today' and create_time<'$moring' and is_card=0 ")
            ->select();
        $toys_type_count=count($toys_type_list);

        //玩具个数
        $toys_number_list = M('toys_business_listing')
            ->field('id')
            ->where("state='0' and is_use=0 and post_create_time>'$today' and post_create_time<'$moring' ")
            ->select();
        $toys_number_count=count($toys_number_list);

        $out_toys_number_list = M('toys_business_listing')
            ->field('id')
            ->where("state='0' and is_use=0 and post_create_time>'$today' and post_create_time<'$moring' and out_state='1' ")
            ->select();
        $out_toys_number_count=count($out_toys_number_list);//出库

        $in_toys_number_list = M('toys_business_listing')
            ->field('id')
            ->where("state='0' and is_use=0 and post_create_time>'$today' and post_create_time<'$moring' and out_state='0' ")
            ->select();
        $in_toys_number_count=count($in_toys_number_list);//未出库


        //入库
        $in_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=11 and post_create_time>'$today' and post_create_time<'$moring' and is_prize=0 ")
            ->select();
        $in_count=count($in_list);
        $in_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=11 and post_create_time>'$today' and post_create_time<'$moring' and is_prize=0 ")
            ->select();
        $in_user_count=count($in_user_list);
        $in_count_info=$in_count."【玩具】—".$in_user_count."【用户】";

        //待取回
        $back_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=10 and is_prize=0 ")
            ->select();
        $back_count=count($back_list);
        $back_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=10 and is_prize=0 ")
            ->select();
        $back_user_count=count($back_user_list);
        $back_count_info=$back_count."【玩具】—".$back_user_count."【用户】";

        //已取回
        $aready_back_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=7 and post_create_time>'$today' and post_create_time<'$moring' and business_id not in ($str_card_info) and is_prize=0 ")
            ->select();
        $aready_back_count=count($aready_back_list);
        $aready_back_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=7 and post_create_time>'$today' and post_create_time<'$moring' and business_id not in ($str_card_info) and is_prize=0 ")
            ->select();
        $aready_back_user_list_count=count($aready_back_user_list);
        $aready_back_count_info=$aready_back_count."【玩具】—".$aready_back_user_list_count."【用户】";

        //玩乐中
        $fun_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=6 and post_create_time>'$today' and post_create_time<'$moring' and is_prize=0 ")
            ->select();
        $fun_count=count($fun_list);
        $fun_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=6 and post_create_time>'$today' and post_create_time<'$moring' and is_prize=0 ")
            ->select();
        $fun_user_list_count=count($fun_user_list);
        $fun_count_info=$fun_count."【玩具】—".$fun_user_list_count."【用户】";

        //送货中[送]
        $ordering_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=5 and is_prize=0  ")
            ->select();
        $ordering_count=count($ordering_list);
        $ordering_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=5 and is_prize=0 ")
            ->select();
        $ordering_user_list_count=count($ordering_user_list);
        $ordering_count_info=$ordering_count."【玩具】—".$ordering_user_list_count."【用户】";

        //待派单[送]
        $send_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=2 and business_id not in ($str_card_info) and toys_number<>'' and is_prize=0 ")
            ->select();
        $send_count=count($send_list);
        $send_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=2 and business_id not in ($str_card_info) and toys_number<>'' and is_prize=0 ")
            ->select();
        $send_user_list_count=count($send_user_list);
        $send_count_info=$send_count."【玩具】—".$send_user_list_count."【用户】";

        //备货中
        $prepare_list = M('toys_order')
            ->field('id')
            ->where("state='0' and status=2 and business_id not in ($str_card_info) and ( (toys_number is null) or (toys_number='') ) and is_prize=0 ")
            ->select();
        $prepare_count=count($prepare_list);
        $prepare_user_list = M('toys_order')
            ->field('user_id')
            ->group("user_id")
            ->where("state='0' and status=2 and business_id not in ($str_card_info) and ( (toys_number is null) or (toys_number='') ) and is_prize=0 ")
            ->select();
        $prepare_user_list_count=count($prepare_user_list);
        $prepare_count_info=$prepare_count."【玩具】—".$prepare_user_list_count."【用户】";

        $res=array(
            'in_count'=>$in_count_info,//入库
            'prepare_count'=>$prepare_count_info,//备货中
            'send_count'=>$send_count_info,//待派单[送]
            'ordering_count'=>$ordering_count_info,//送货中[送]
            'fun_count'=>$fun_count_info,//玩乐中
            'back_count'=>$back_count_info,//待取回
            'aready_back_count'=>$aready_back_count_info,//已取回
            'toys_type_count'=>$toys_type_count,//玩具种类
            'toys_number_count'=>$toys_number_count,//玩具个数
            'show_time'=>$show_time,
            'out_toys_number_count'=>$out_toys_number_count,
            'in_toys_number_count'=>$in_toys_number_count
        );
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
            $where_condition="o.state='0' and o.is_prize in (0,3,6) and i.post_create_time>'$today' and i.post_create_time<'$moring' and i.state='0' and i.postman_id>0 and ((i.status='5' and o.status=5) or (i.status='7' and o.status=10 ) )";
            $resultRes =$model-> table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                    ->field('count(o.id) as count,i.postman_id')
                    ->group("i.postman_id")
                    ->where($where_condition)
                    ->select();
            $status_name="配送员送和取";
        } elseif($order_status==2) {//取消送货量
            $where_condition="o.state='0' and o.post_create_time>'$today' and o.post_create_time<'$moring' and o.is_prize in (0,3,6) and o.cancel_reason in (1,2,3) and ((i.status='2' and o.status in (2,17) ) or (i.status='7' and o.status=10 ) ) ";
            $resultRes =$model-> table('baby_toys_order as o')
                    ->join(" left join baby_toys_listing as i on i.order_id=o.id")
                    ->field('count(o.id) as count,i.postman_id')
                    ->group("i.postman_id")
                    ->where($where_condition)
                    ->select();
            $status_name="取消送货量";
        } elseif($order_status==3) {//入库量
            $where_condition=" i.state='0' and i.create_time>'$today' and i.create_time<'$moring' and o.is_prize in (0,3,6) and i.status='11' and o.status=11 ";//
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
    
    //预约玩具列表
    public function getappointmentlist() {
        $search_user_id=I("search_user_id");
        $search_business_id=I("search_business_id");
        $search_toys_title=I("search_toys_title");
        $search_is_rent=I("search_is_rent");
        $search_number=I("search_number");
        $search_type=I("search_type");//0默认、1玩具种类
        $excel_state=I("excel_state");
        $search_datediff = I("search_datediff");//1：超过10天  0：全部
        if($excel_state==1) {
            $search_type=1;
        }
        if($search_is_rent<1) {
            $search_is_rent=2;
        }
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="a.state='0' ";
        if($search_type>0) {
            $this->assign('search_type',$search_type);
        }
        if($search_user_id>0) {
            $where2.=" and a.user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }
        if($search_is_rent>0) {
            if($search_is_rent==7){
                $where2.=" and a.is_rent='2' and a.remark like '%系统插队%' ";
            }else{
                $where2.=" and a.is_rent='$search_is_rent' ";
            }

            $this->assign('search_is_rent',$search_is_rent);
        }

        if($search_datediff==1){
            $now_time = date('Y-m-d H:i:s');
            $where2.=" and datediff('$now_time',a.create_time) >=10 ";
            $this->assign('search_datediff',$search_datediff);
        }
        if($search_toys_title) {
            $where2.=" and ( (a.toys_title like '%$search_toys_title%') or (b.key_words like '%$search_toys_title%') or (b.business_brand like '%$search_toys_title%') ) ";
            $this->assign('search_toys_title',$search_toys_title);
        }
        //有库存检查 开始
        $getNumRes= M('toys_appointment')
                    ->field("business_id")
                    ->group("business_id ")
                    ->where("business_id>0")
                    ->select();
        $arr_business_id=array();
        if($getNumRes && ($search_number==1)) {
            foreach ($getNumRes as $key => $value) {
                $business_id=$value['business_id'];
                $getBusinessRes=$this->getBusinessTotalNum($business_id);
                $toys_number=$getBusinessRes['use_count'];
                if($toys_number>0) {
                    $arr_business_id[]=$business_id;
                }
            }
        }
        if($search_number==1) {
            $this->assign('search_number',$search_number);
        }
        if($search_business_id>0) {
            $where2.=" and a.business_id='$search_business_id' ";
            $this->assign('search_business_id',$search_business_id);
        } elseif(($search_number==1) && ($arr_business_id) ) {
            $str_business_id=implode(",", $arr_business_id);
            $where2.=" and a.business_id in ($str_business_id) ";
        }
        //有库存检查 结束
        $model = new Model();
        if($search_type==1) {
            if($excel_state==1) {//导出

                $getexcelUserRes= $model->table('baby_toys_appointment as a')
                        ->field("a.toys_title,a.business_id")
                        ->join(" left join tmp_toys_app01 as app on app.business_id=a.business_id and a.business_id>0 ")
                        ->where($where2)
                        ->group("a.business_id ")
                        ->order("app.user_count desc ")
                        ->select();
                
                $xlsData=array();
                if($getexcelUserRes) {
                    $xlsName="预约玩具";
                    $xlsCell  = array(
                        array('toys_info','预约玩具信息')
                    );
                    foreach ($getexcelUserRes as $key => $value) {
                        $toys_title=$value['toys_title'];
                        $business_id=$value['business_id'];
                        if($business_id>0) {
                            $bus_count  = M('toys_appointment')
                                ->where("state='0' and business_id=$business_id and is_rent in (2,4) ")
                                ->count();
                            $toys_info=$business_id."\n".$toys_title."\n预约".$bus_count."人";
                        } else {
                            $toys_info=$toys_title;
                        }
                        $xlsData[]=array(
                            'toys_info'=>$toys_info
                        );
                    }
                    $this->exportExcel($xlsName,$xlsCell,$xlsData);
                }
            }

            //满足条件的总记录数
            $buscount= $model->table('baby_toys_appointment as a')
                            ->join(" left join baby_user as u on a.user_id=u.id")
                            ->join(" left join baby_toys_business as b on a.business_id=b.id")
                            ->join(" left join tmp_toys_app01 as app on app.business_id=a.business_id and a.business_id>0 ")
                            ->field("a.business_id")
                            ->where($where2)
                            ->group("a.business_id ")
                            ->select();
            $count=count($buscount);
            $page = new  Page($count,50);//实例化分页类，传入总记录数
            $show = $page->show(); //分页显示输出
            $getListRes= $model->table('baby_toys_appointment as a')
                        ->join(" left join baby_user as u on a.user_id=u.id")
                        ->join(" left join baby_toys_business as b on a.business_id=b.id")
                        ->field("a.*,u.nick_name,u.mobile,case when a.is_rent in (1,3) then 1 else 2 end rent_rank")
                        ->join(" left join tmp_toys_app01 as app on app.business_id=a.business_id and a.business_id>0 ")
                        ->where($where2)
                        ->group("a.business_id ")
                        ->order("app.user_count desc ")
                        ->limit($page->firstRow,$page->listRows)
                        ->select();
        } else {
            //满足条件的总记录数
            $count= $model->table('baby_toys_appointment as a')
                            ->join(" left join baby_user as u on a.user_id=u.id")
                            ->join(" left join baby_toys_business as b on a.business_id=b.id")
                            ->where($where2)
                            ->count();
            $page = new  Page($count,50);//实例化分页类，传入总记录数
            $show = $page->show(); //分页显示输出

                $getListRes= $model->table('baby_toys_appointment as a')
                    ->join(" left join baby_user as u on a.user_id=u.id")
                    ->join(" left join baby_toys_business as b on a.business_id=b.id")
                    ->field("a.*,u.nick_name,u.mobile,b.is_show,case when a.is_rent in (1,3) then 1 else 2 end rent_rank")
                    ->where($where2)
                    ->order("rent_rank desc,a.create_time desc")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();


        }
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $post_create_time=$value['post_create_time'];
                $true_time = $value['true_time'];
                $show_time="<font color='red'>".$true_time."</font><br>".$create_time."<br/>".$post_create_time;
                $user_id=$value['user_id'];
                $nick_name=$value['nick_name'];
                $mobile=$value['mobile'];
                $business_id=$value['business_id'];
                $toys_title=$value['toys_title'];
                $is_rent=$value['is_rent'];
                $public_name=$value['public_name'];
                $remark=$value['remark'];
                $id=$value['id'];
                $is_show = $value['is_show'];
                if($is_show==2){
                    $toys_title .= "(已下架)";
                }else
                if($is_rent==1) {
                    $rent_title="已租";
                } elseif($is_rent==5) {
                    $rent_title="已通知";
                } elseif($is_rent==4) {
                    $rent_title="重新排队";
                } elseif($is_rent==3) {
                    $rent_title="放弃";
                } elseif($is_rent==6) {
                    $rent_title="处理中";
                } else {
                    $rent_title="未租";
                }
                $user_info=$toys_info="";
                $user_info=$user_id;
                if($nick_name) {
                    $user_info.="<br/>".$nick_name ;
                }
                if($mobile) {
                    $user_info.="<br/>".$mobile ;
                }
                if($business_id>0) {
                    //排序计算 开始
                    if(($is_rent==2) ) {
                        $rank_count= M('toys_appointment')
                        ->where("state='0' and business_id=$business_id and is_rent in (2) and create_time<'$create_time' ")
                        ->count();
                        $show_rank=$rank_count+1;
                    } else {
                        $show_rank="";
                    }
                    //排序计算 结束
                    $getBusinessRes=$this->getBusinessTotalNum($business_id);
                    $toys_number=$getBusinessRes['use_count'];
                    if($toys_number<=0) {
                        $toys_number=0;
                    }

                    $bus_count  = M('toys_appointment')
                        ->where("state='0' and business_id=$business_id and is_rent in (2,4) ")
                        ->count();
                    $toys_info=$business_id."<br/>".$toys_title."<br/>预约".$bus_count."人";
                } else {
                    $toys_info=$toys_title;
                    $toys_number=0;
                }
                $res[]=array(
                    'user_id'=>$user_id,
                    'user_info'=>$user_info,
                    'toys_info'=>$toys_info,
                    'id'=>$id,
                    'rent_title'=>$rent_title,
                    'create_time'=>$show_time,
                    'public_name'=>$public_name,
                    'toys_number'=>$toys_number,
                    'show_rank'=>$show_rank,
                    'remark'=>$remark
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除预约玩具
    public function operator_appointmentstate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_appointment');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getappointmentlist.html");
    }

    //预约玩具操作页面
    public function pubappointment() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_appointment")
                        ->where("id=$id ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }
    //仓库记录列表
    public function getwarehouserelist() {
        $search_user_id=I("search_user_id");
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="a.state='0' ";
        if($search_user_id>0) {
            $where2.=" and a.user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_warehouse_remember as a')
                        ->join(" left join baby_user as u on a.user_id=u.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_warehouse_remember as a')
                    ->join(" left join baby_user as u on a.user_id=u.id")
                    ->field("a.*,u.nick_name,u.mobile")
                    ->where($where2)
                    ->order("id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $user_id=$value['user_id'];
                $nick_name=$value['nick_name'];
                $mobile=$value['mobile'];
                $problem=$value['problem'];
                $need_problem=$value['need_problem'];
                $handle_record=$value['handle_record'];
                $id=$value['id'];
                $user_info=$toys_info="";
                $user_info=$user_id;
                if($nick_name) {
                    $user_info.="<br/>".$nick_name ;
                }
                if($mobile) {
                    $user_info.="<br/>".$mobile ;
                }
                $res[]=array(
                    'user_info'=>$user_info,
                    'problem'=>$problem,
                    'id'=>$id,
                    'need_problem'=>$need_problem,
                    'handle_record'=>$handle_record,
                    'create_time'=>$create_time
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除仓库记录
    public function operator_warehouserestate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_warehouse_remember');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getwarehouserelist.html");
    }

    //仓库记录操作页面
    public function pubwarehousere() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_warehouse_remember")
                        ->where("id=$id ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }

    //停卡列表
    public function getstopcardlist() {
        $search_user_id=I("search_user_id");
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="a.state='0' and a.status=4 ";
        if($search_user_id>0) {
            $where2.=" and a.user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_card as a')
                        ->join(" left join baby_user as u on a.user_id=u.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_card as a')
                    ->join(" left join baby_user as u on a.user_id=u.id")
                    ->field("a.*,u.nick_name,u.mobile")
                    ->where($where2)
                    ->order("a.id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $end_time=$value['end_time'];
                $final_end_time=$value['final_end_time'];
                if($final_end_time!="0000-00-00 00:00:00") {
                    $show_time=$final_end_time."<br/>".$create_time;
                } else {
                    $show_time=$end_time."<br/>".$create_time;
                }
                $user_id=$value['user_id'];
                $nick_name=$value['nick_name'];
                $mobile=$value['mobile'];
                $status=$value['status'];
                $card_stop_day = $value['card_stop_day'];
                if($status==4) {
                    $status_name="停卡";
                } else {
                    $status_name="正常使用";
                }
                $id=$value['id'];
                $user_info=$toys_info="";
                $user_info=$user_id;
                if($nick_name) {
                    $user_info.="<br/>".$nick_name ;
                }
                if($mobile) {
                    $user_info.="<br/>".$mobile ;
                }
                $res[]=array(
                    'user_info'=>$user_info,
                    'id'=>$id,
                    'status_name'=>$status_name,
                    'show_time'=>$show_time,
                    'card_stop_day'=>$card_stop_day
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //赔付订单列表
    public function getpaymentlist() {
        $search_user_id=I("search_user_id");
        $search_status=I("search_status");
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.is_prize in(4,5) and c.root_img_id=0 ";//o.state='0' and
        if($search_user_id>0) {
            $where2.=" and o.user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }
        if($search_status>0) {
            $where2.=" and o.status='$search_status' ";
            $this->assign('search_status',$search_status);
        }
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_compensation as c on o.id=c.order_id")
            ->where($where2)
            ->count();

        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_order as o')
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_compensation as c on o.id=c.order_id")
            ->field("o.*,u.nick_name,u.mobile as umobile,c.business_title,c.business_des")
            ->where($where2)
            ->order("o.id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $user_id=$value['user_id'];
                $nick_name=$value['nick_name'];
                $mobile=$value['umobile'];
                $status=$value['status'];
                $id=$value['id'];
                $user_info=$user_id;
                if($nick_name) {
                    $user_info.="<br/>".$nick_name ;
                }
                if($mobile) {
                    $user_info.="<br/>".$mobile ;
                }

                $wx_source=$value['wx_source'];
                $total_price = $value['total_price'];
                $payment=$value['payment'];
                $trade_no_num = $value['trade_no']?$value['trade_no']:"";
                if($wx_source==4) {
                    $total_price.="<br/>支付宝-企业";
                } elseif($payment==2) {
                    $total_price.="<br/>微信";
                } elseif($payment==1) {
                    $total_price.="<br/>支付宝-个人";
                }
                if($trade_no_num){
                    $total_price.="<br/>".$trade_no_num;
                }

                $is_prize = $value['is_prize'];//4玩具坏、5零件丢失
                if($is_prize==4){
                    $is_prize_name = "玩具损坏";
                }elseif($is_prize==5){
                    $is_prize_name = "玩具丢失";
                }

                $toys_payment_title = $value['business_des'];//赔付原因
                $business_title = $value['business_title'];
                $toys_info = $business_title;

                if($status==1){
                    $status_name = "待支付";
                }elseif($status==7){
                    $status_name = "已完成";
                }elseif($status==10){
                    $status_name = "待取回";
                }elseif($status==8){
                    $status_name = "退款中";
                }elseif($status==9){
                    $status_name = "已退款";
                }
                $create_time = $value['create_time'];
                $post_create_time = $value['post_create_time'];
                $is_show = $value['state'];

                if($is_show==0){
                    $is_show_name = "展示";
                }elseif($is_show==1){
                    $is_show_name = "隐藏";
                }

                $res[]=array(
                    'user_info'=>$user_info,
                    'order_id'=>$id,
                    'total_price'=>$total_price,
                    'toys_payment_title'=>$toys_payment_title,
                    'is_prize_name'=>$is_prize_name,
                    'toys_info'=>$toys_info,
                    'status'=>$status,
                    'status_name'=>$status_name,
                    'time_info'=>$create_time."<br>".$post_create_time,
                    'is_show' => $is_show,
                    'is_show_name' => $is_show_name
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //赔付订单列表，配送员上传
    public function getpaymentlistorder() {
        $search_user_id=I("search_user_id");
        $search_status=I("search_status")?I("search_status"):1;
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.is_question>0 ";
        if($search_user_id>0) {
            $where2.=" and o.user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }
        if($search_status>0) {
            $where2.=" and o.is_question='$search_status' ";
            $this->assign('search_status',$search_status);
        }
        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->where($where2)
            ->count();

        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_order as o')
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->join(" left join baby_toys_business_listing as t on o.toys_number=t.id ")
            ->field("o.id,o.is_question,o.user_id,o.stock_remark,o.user_load_img,o.post_create_time,o.admin_load_img,u.nick_name,u.mobile,b.business_title,t.remark,t.order_remark")
            ->where($where2)
            ->order("o.id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();
//        var_dump($getListRes);die;
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {

                $user_id=$value['user_id'];
                $nick_name=$value['nick_name'];
                $mobile=$value['mobile'];
                $id=$value['id'];
                $user_info=$user_id;
                if($nick_name) {
                    $user_info.="<br/>".$nick_name ;
                }
                if($mobile) {
                    $user_info.="<br/>".$mobile ;
                }

                $is_question = $value['is_question'];//1:需要赔付  2：赔付已解决
                $stock_remark = $value['stock_remark'];//赔偿备注
                $remark = $value['remark'];//小编入库备注
                $order_remark = $value['order_remark'];//仓库入库备注
                $business_title = $value['business_title'];
                $post_create_time = $value['post_create_time'];

                $user_load_img = $value['user_load_img'];
                $user_load_img_arr = array();
                if($user_load_img){
                    $user_load_img_arr = explode(';',$user_load_img);
                }
                $admin_load_img = $value['admin_load_img'];
                $admin_load_img_arr = array();
                if($admin_load_img){
                    $admin_load_img_arr = explode(';',$admin_load_img);
                }


                $res[]=array(
                    'user_info'=>$user_info,
                    'order_id'=>$id,
                    'is_question'=>$is_question,
                    'stock_remark'=>$stock_remark,
                    'remark'=>$remark,
                    'order_remark'=>$order_remark,
                    'business_title' => $business_title,
                    'post_create_time'=>$post_create_time,
                    'user_load_img_arr'=>$user_load_img_arr,
                    'admin_load_img_arr'=>$admin_load_img_arr
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //取消订单列表，配送员上传
    public function getcancellistorder() {

        $search_user_id=I("search_user_id");
        $search_status=I("search_status")?I("search_status"):0;
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" o.cancel_reason in (4,6) and o.status in (10,17) ";

        if($search_status>0) {
            $where2="  o.cancel_reason='$search_status' and o.status in (10,17)  ";
            $this->assign('search_status',$search_status);
        }

        if($search_user_id>0) {
            $where2.=" and o.user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->where($where2)
            ->count();

        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_order as o')
            ->join(" left join baby_user as u on o.user_id=u.id")
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->field("o.id,o.cancel_reason,o.status,o.user_id,o.cancel_remark,o.post_create_time,u.nick_name,u.mobile,b.business_title")
            ->where($where2)
            ->order("o.id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();
//        var_dump($where2);die;
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {

                $user_id=$value['user_id'];
                $nick_name=$value['nick_name'];
                $mobile=$value['mobile'];
                $id=$value['id'];
                $user_info=$user_id;
                if($nick_name) {
                    $user_info.="<br/>".$nick_name ;
                }
                if($mobile) {
                    $user_info.="<br/>".$mobile ;
                }

                $cancel_reason = $value['cancel_reason']; //配送端取消类型  1：配送员原因取消  2:电话无人接听    3:暂时无法接收玩具  4:用户不换玩具  5:上门无人  6:玩具问题用户拒收  7:小编已处理
                $cancel_title = "";
                if($cancel_reason==4){
                    $cancel_title = "用户不换玩具";
                }elseif($cancel_reason==6){
                    $cancel_title = "玩具问题用户拒收";
                }elseif($cancel_reason==7){
                    $cancel_title = "小编已处理";
                }

                $cancel_remark = $value['cancel_remark'];//赔偿备注

                $business_title = $value['business_title'];
                $post_create_time = $value['post_create_time'];



                $res[]=array(
                    'user_info'=>$user_info,
                    'order_id'=>$id,
                    'cancel_reason'=>$cancel_reason,
                    'cancel_remark'=>$cancel_remark,
                    'cancel_title'=>$cancel_title,
                    'business_title' => $business_title,
                    'post_create_time'=>$post_create_time,
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //派单地图筛选
    public function gaodepointselectshow() {
        $search_user_id=I("search_user_id");
        $search_status=I("search_status");
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");

        $res=array();

        $model = new Model();
        $regionA= $model->table('baby_toys_citys_regions')
            ->where("state='0'")
            ->field('county as city_name,county_id as city_id')
            ->group("county")
            ->select();

        $cityInfo=array();
        $all_array=array();
        if (is_array($regionA) && count($regionA)) {
            foreach ($regionA as $key => $value) {
                $cityInfo[$key]['city_id']=$value['city_id']*1000;
                $cityInfo[$key]['city_name']=$value['city_name'];
                $city_id = $value['city_id'];

                $regionB = $model->table('baby_toys_citys_regions')
                    ->where("state='0'and county_id=".$city_id)
                    ->field('id as city_id,region as city_name')
                    ->select();

                foreach($regionB as &$val){
                    $val['city_id'] = $city_id*1000+$val['city_id'];
                }
                $cityInfo[$key]['children']=$regionB;

            }

        }
        $res = $cityInfo;
//        echo "<pre>";
//        print_r($res);die;
        $this->assign('res',$res);//赋值数据集
        $this->display();
    }

    //派单地图
    public function gaodepoint() {
        $search_user_id=I("search_user_id");
        $search_status=I("search_status");
        $allofthem = I("allofthem");
        $search_arr_user_id=I("arr_user_id");
        $line_look_status = I("line_look_status");//查看路线：1
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $model = new Model();

        $now_time = date("Y-m-d H:i:s");
        //获取区域和对应时间
        $get_region_ids = I("region_id");
        $dayall = I("dayall");

        if($allofthem==1){
            $get_region_ids = "";
        }
        //已经生成路线的用户
        $line_have_res= $model->table('baby_toys_line_plan')
            ->where(" state='0' and status in (1,5) ")
            ->field("user_id")
            ->group("user_id")
            ->select();
        if($line_have_res){
            foreach($line_have_res as $val){
                $line_have_res_arr[] = $val['user_id'];
            }
            $line_have_res_str = implode(',',$line_have_res_arr);
        }else{
            $line_have_res_str = "0";
        }
        if($line_look_status==1){
            //强制查看地图
            $line_have_res_str = "0";
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

        //查询限制账号1
//        $getQuestionsRes= $model->table('baby_toys_order_questions')
//            ->where("state='0'")
//            ->field('user_id')
//            ->select();

        $getQuestionsRes= $model->table('baby_toys_order')
            ->where("state='0' and hold in (1,2) and status in (2,17,10) ")
            ->field('user_id')
            ->group("user_id")
            ->select();

//        var_dump($getQuestionsRes);die;
        if($getQuestionsRes){
            foreach($getQuestionsRes as $val){
                $getQuestionsArr[] = $val['user_id'];
            }
        }else{
            $getQuestionsArr = array();
        }

        $str_card_info=$this->getCardInfo();

        $where3 =" and user_id not in ($common_user_id_str) ";

        if($get_region_ids){
            foreach($get_region_ids as $key=>$val){
                $tmp_city_id = $val;//区域查询
                $tmp_v = "day".$val;
                $tmp_k = I($tmp_v);//对应时间
                if($tmp_k==0){
                    $where_sql = "";
                }elseif($tmp_k==1){
                    $where_sql = " and datediff('$now_time',post_create_time) >=1 ";
                }elseif($tmp_k==2){
                    $where_sql = " and datediff('$now_time',post_create_time) >=2 ";
                }elseif($tmp_k==3){
                    $where_sql = " and datediff('$now_time',post_create_time) >=3 ";
                }elseif($tmp_k==4){
                    $where_sql = " and datediff('$now_time',post_create_time) >=4 ";
                }elseif($tmp_k==5){
                    $where_sql = " and datediff('$now_time',post_create_time) >=5 ";
                }elseif($tmp_k==7){
                    $where_sql = " and datediff('$now_time',post_create_time) >=7 ";
                }elseif($tmp_k==8){
                    $where_sql = " and datediff('$now_time',post_create_time) <1 ";
                }

                if($key==0){
                    $sql = "select user_id from baby_toys_order where state='0' and region_id like '%".$tmp_city_id."%' and business_id not in ($str_card_info) and status in (2,17,10) and is_prize in (0,3,6) $where_sql $where3 and user_id not in ($line_have_res_str) ";
                }else{
                    $sql .= " union  select user_id from baby_toys_order where state='0' and region_id like '%".$tmp_city_id."%' and business_id not in ($str_card_info) and status in (2,17,10) and is_prize in (0,3,6) $where_sql $where3 and user_id not in ($line_have_res_str) ";
                }



            }
//            echo $sql;die;
            $result = $model->query($sql);
        }

        if($allofthem==1){

                if($dayall==0){
                    $where_sql2 = "";
                }elseif($dayall==1){
                    $where_sql2 = " and datediff('$now_time',post_create_time) >=1 ";
                }elseif($dayall==2){
                    $where_sql2 = " and datediff('$now_time',post_create_time) >=2 ";
                }elseif($dayall==3){
                    $where_sql2 = " and datediff('$now_time',post_create_time) >=3 ";
                }elseif($dayall==4){
                    $where_sql2 = " and datediff('$now_time',post_create_time) >=4 ";
                }elseif($dayall==5){
                    $where_sql2 = " and datediff('$now_time',post_create_time) >=5 ";
                }elseif($dayall==7){
                    $where_sql2 = " and datediff('$now_time',post_create_time) >=7 ";
                }elseif($dayall==8){
                    $where_sql2 = " and datediff('$now_time',post_create_time) <1 ";
                }

            $sql2 = "select user_id from baby_toys_order where state='0' and business_id not in ($str_card_info) and status in (2,17,10) and is_prize in (0,3,6) $where_sql2 $where3 and user_id not in ($line_have_res_str) ";
            $result = $model->query($sql2);
        }
//var_dump($result);
        $tmp_user_ids_arr = array();
        if($result){
            foreach($result as $val){
                $tmp_user_ids_arr[] = $val['user_id'];
            }
            $tmp_user_ids_arr = array_unique($tmp_user_ids_arr);
        }

        $where2=" business_id not in ($str_card_info) and state='0' and is_prize in (0,3,5,6) and user_id not in ($common_user_id_str) and status in (2,17) and user_id not in ($line_have_res_str) ";
        $getUserRes= $model->table('baby_toys_order')
            ->where($where2)
            ->field("user_id")
            ->group("user_id")
            ->select();

        //待取回查询
        $getUserBackRes= $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_listing as i on i.order_id=o.id")
            ->where("o.business_id not in ($str_card_info) and o.state='0' and o.is_prize in (0,3,5,6) and o.user_id not in ($common_user_id_str) and o.status in (10) and i.status in (7) and postman_id=0 and o.user_id not in ($line_have_res_str)")
            ->field("user_id")
            ->group("user_id")
            ->select();
        foreach($getUserRes as $v){
            $user_id_s = $v['user_id'];
            $getUserRes_end[] = $user_id_s;
        }
        foreach($getUserBackRes as $v){
            $user_id_v = $v['user_id'];
            $getUserRes_end[] = $user_id_v;
        }
        $getUserRes_end = array_unique($getUserRes_end);
        if(count($tmp_user_ids_arr)){
            $getUserRes_end = $tmp_user_ids_arr;
        }

        if(!empty($search_arr_user_id)){
            $getUserRes_end = explode(',',$search_arr_user_id);
        }

        $res=array();
        $user_id_none = array();
        if($getUserRes_end) {
            if(!empty($getQuestionsArr)){
                $getUserRes_end = array_diff($getUserRes_end,$getQuestionsArr);
            }
            foreach ($getUserRes_end as $key => $value) {
                $tmp_user_id=$value;

                //只有赔付订单或者只有待取回订单的标记出来
                $new_order_address_arr_new = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info) and user_id not in ($line_have_res_str)")
                    ->field('status,is_stop_card')
                    ->select();

                $new_status = "（只有待取回订单）";
                if($new_order_address_arr_new){
                    foreach($new_order_address_arr_new as $val){
                        $new_every_status = $val['status'];
                        if(in_array($new_every_status,array(2,17))){
                            $new_status = "";
                        }
                    }
                }
                //是否是停卡取回玩具
                if($new_order_address_arr_new){
                    foreach($new_order_address_arr_new as $val){
                        $is_stop_card = $val['is_stop_card'];
                        if($is_stop_card==1){
                            $new_status = "<font color='red'>停卡取回</font>（只有待取回订单）";
                        }
                    }
                }
//var_dump($new_status);
                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info) and user_id not in ($line_have_res_str)")
                    ->field('address,lat,lng,post_create_time')
                    ->order('create_time desc')
                    ->find();

                $order_post_create_time = $new_order_address_arr['post_create_time'];//最新订单修改时间
                $order_post_create_time_stamp = abs((time()-strtotime($order_post_create_time))/86400);

                if( ($order_post_create_time_stamp<1) || ($order_post_create_time_stamp==1) ){
                    //1天内
                    $diff_img = "https://api.meimei.yihaoss.top/static3/album/2018/0515/c7f19135147b08ef.jpg";//绿色1
                }elseif( (($order_post_create_time_stamp>1) && ($order_post_create_time_stamp<2)) || ($order_post_create_time_stamp==2) ){
                    //1-2天
                    $diff_img = "https://api.meimei.yihaoss.top/static3/album/2018/0515/bc9146d1a51041d5.jpg";//蓝色2
                }elseif( (($order_post_create_time_stamp>2) && ($order_post_create_time_stamp<3)) || ($order_post_create_time_stamp==3) ){
                    //2-3天
                    $diff_img = "https://api.meimei.yihaoss.top/static3/album/2018/0515/7ebcaedbe7e9c151.jpg";//黄色3
                }elseif( (($order_post_create_time_stamp>3) && ($order_post_create_time_stamp<4)) || ($order_post_create_time_stamp==4) ){
                    //3-4天
                    $diff_img = "https://api.meimei.yihaoss.top/static3/album/2018/0515/deee658e983bba42.jpg";//紫色4
                }elseif( ($order_post_create_time_stamp>4) ){
                    //大于4天
                    $diff_img = "https://api.meimei.yihaoss.top/static3/album/2018/0515/6e542ca3c5e92103.jpg";//红色5
                }

                $order_address = $new_order_address_arr['address'];
                $lat = $new_order_address_arr['lat'];
                $lng = $new_order_address_arr['lng'];
                if($lat || $lng){
                    $res[]=array(
                        'latlng' => "[".$lng.",".$lat."],",
                        'user_id'=>$tmp_user_id,
                        'userinfo'=>$new_status.$tmp_user_id."--".$order_address,
                        'diff_img'=>$diff_img
                    );
                }else{
                    $user_id_none[] = $tmp_user_id;
                }


            }
        }
//var_dump($res);die;
        $new_res = json_encode($res);
//        echo $new_res;die;
        $this->assign('new_res',$new_res);
        $this->assign('res',$res);//赋值数据集
        $this->display();
    }

    //派单地图玩具展示
    public function gaodepointtoysinfo() {
        $this->checkSession();
        $user_id=I("user_id");
        $day_ago=date("Y-m-d 00:00:00", strtotime ("-2 day"));
        $search_condition_id=I("search_condition_id");
        $search_address=I("search_address");
        $allofthem = I("allofthem");
        $dayall = I("dayall");
        $excel_state=I("excel_state");//是否打印表格 1：生成
        $new_state=I("new_state");//是否生成路线 1：生成
        $search_line=I("new_line");
//        var_dump($_REQUEST);die;
        $search_arr_user_id=I("arr_user_id");
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        $search_address = I('address');

        //跑程序禁止单子----------------------start--------------------------------------------------
        $str_card_id=$str_card_info;
        $Model = new Model();//或者 $Model = D(); 或者 $Model = M();
        $line_sql777 = " select distinct user_id from baby_toys_line_plan where state='0' and status in (1,5) ";
        $line_res777 = $Model->query($line_sql777);

        if($line_res777){
            foreach($line_res777 as $val){
                $line_have_res_arr777[] = $val['user_id'];
            }
            $line_str777 = implode(',',$line_have_res_arr777);
        }else{
            $line_str777 = "0";
        }

        //查询所有需要判断的用户id
        $sql777 = " select distinct user_id from baby_toys_order where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and hold=0 and user_id not in ($line_str777) and hold_status=0   ";//and create_time>'2018-05-17 14:08:00'
        $res777 = $Model->query($sql777);

        if($res777){
            foreach($res777 as $val){
                $user_id = $val['user_id'];
                $sql_one = " select status,address,region_id,lat,lng,hold from baby_toys_order  where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id = '$user_id' ";
                $res_one = $Model->query($sql_one);
                $diff_order_address = array();
                $status_arr = array();
                $hold = 0;
                $region = 0;
                $hold_reason = "";
                $right_back_address = 0;
                $right_back_status = 0;
                $right_back_region = 0;
                $hold_back_arr = array();
                foreach($res_one as $value){
                    $diff_address = $value['address'];
                    $diff_order_address[] = $diff_address;
                    $diff_lat = $value['lat'];
                    $diff_lng = $value['lng'];
                    $diff_region_id = $value['region_id'];
                    $status = $value['status'];
                    $status_arr[] = $status;
                    $hold_back = $value['hold'];
                    $hold_back_arr[] = $hold_back;

                    //经纬度区域缺失
                    if( empty($diff_lat) || empty($diff_lng) || empty($diff_region_id) ){
                        $region=1;
                    }

                }

                //多地址
                $diff_order_address = array_unique($diff_order_address);
                $diff_order_address_count = count($diff_order_address);
                if($diff_order_address_count>1){
                    $hold = 1;
                    $hold_reason = "多地址";
                }else{
                    $right_back_address = 1;
                }

                //只有待取回订单
                $status_arr = array_unique($status_arr);
                $status_arr_count = count($status_arr);
                if($status_arr_count==1){
                    $new_status = $status_arr[0];
                    if($new_status==10){
                        $hold = 1;
                        $hold_reason = "只有待取回订单";
                    }else{
                        $right_back_status = 1;
                    }
                }

                if($status_arr_count>1){
                    $right_back_status = 1;
                }

                //经纬度缺失
                if($region==1){
                    $hold = 1;
                    $hold_reason = "经纬度缺失";
                }else{
                    $right_back_region = 1;
                }

                //已筛查的订单做标记
                $upsql7778 = " update baby_toys_order set hold_status=1 where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id='$user_id' ";
                $upres7778 = $Model->query($upsql7778);

                //禁止展示在地图
                if($hold==1){
                    $upsql777 = " update baby_toys_order set hold='$hold',hold_reason='$hold_reason' where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id='$user_id' ";
                    $upres777 = $Model->query($upsql777);
                }

                //纠错
                if( ($right_back_status==1)&&($right_back_region==1)&&($right_back_address==1)&&(in_array(1,$hold_back_arr)) ){
                    $upsql2777 = " update baby_toys_order set hold='0',hold_reason='恢复正常' where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id='$user_id' ";
                    $upres2777 = $Model->query($upsql2777);
                }




            }
        }

        //跑程序禁止单子----------------------end---------------------------------------------------


        //生成路线
        if($new_state==1){

            if(empty($search_arr_user_id)){
                die("请输入用户id");
            }
            if(empty($search_line)){
                die("请输入线路名称");
            }
            //判断此路线是否已经生成
            $line_is_res= $model->table('baby_toys_line_plan')
                ->where("user_id in ($search_arr_user_id) and state='0' and status=1 ")
                ->field("user_id")
                ->group("user_id")
                ->select();

            if($line_is_res){
                die("失败！次路线包含已生成路线的用户，请重新筛选用户");
            }
            //生成路线名称
            $search_line = date("Y-m-d")." / ".$search_line;
            $line_user_id_arr = explode(',',$search_arr_user_id);
            $add_line_main_data=array(
                'title'=>$search_line,
                'status'=>1,
                'create_time'=>date("Y-m-d H:i:s"),
                'post_create_time'=>date("Y-m-d H:i:s"),
            );

            $result = M('toys_line_plan')->add($add_line_main_data);
            if($result){
                $new_root_img_id = $result;
            }else{
                die("路线添加失败!");
            }
            $every_line_res = array();
            foreach($line_user_id_arr as $val){
                $line_user_id = $val;
                $every_line_son_res = M('toys_order')
                    ->where("state='0' and status in (2,10,17)  and user_id='$line_user_id' and business_id not in ($str_card_info) and is_prize in (0,3,5,6) ")
                    ->field('id,status')
                    ->select();

                if($every_line_son_res){
                    foreach($every_line_son_res as $value){
                        $temp_line_order_id = $value['id'];
                        $temp_line_status = $value['status'];
                        if($temp_line_status==10){
                            //取送判断
                            $temp_send_back = 2;//取
                        }else{
                            $temp_send_back = 1;//送
                        }
                        $every_line_res[] = array(
                            'title'=>$search_line,
                            'create_time'=>date("Y-m-d H:i:s"),
                            'post_create_time'=>date("Y-m-d H:i:s"),
                            'user_id'=>$line_user_id,
                            'order_id'=>$temp_line_order_id,
                            'status'=>1,
                            'root_img_id'=>$new_root_img_id,
                            'send_back'=>$temp_send_back
                        );
                    }
                }

            }
//            var_dump($every_line_res);die;
            $res_line = M('toys_line_plan')->addAll($every_line_res);
            if($res_line){
                die("生成路线成功");
            }else{
                die("生成路线失败");
            }
        }

        //获取区域和对应时间
        $get_region_ids = I("region_id");
        if($allofthem==1){
            $get_region_ids = "";
        }
        //已经生成路线的用户
        $line_have_res= $model->table('baby_toys_line_plan')
            ->where("  state='0' and status in (1,5) ")
            ->field("user_id")
            ->group("user_id")
            ->select();
        if($line_have_res){
            foreach($line_have_res as $val){
                $line_have_res_arr[] = $val['user_id'];
            }
            $line_have_res_str = implode(',',$line_have_res_arr);
        }else{
            $line_have_res_str = "0";
        }
        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6) and o.user_id not in ($line_have_res_str) ";
        if($search_address){
            $where2.= " and o.address like '%$search_address%'  ";
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
            if($get_region_ids && (($allofthem!=1))) {
                if($get_region_ids){
                    foreach($get_region_ids as $key=>$val){
                        $tmp_city_id = $val;//区域查询
                        $tmp_v = "day".$val;
                        $tmp_k = I($tmp_v);//对应时间
                        if($tmp_k==0){
                            $where_sql = "";
                        }elseif($tmp_k==1){
                            $where_sql = " and datediff('$now_time',post_create_time) >=1 ";
                        }elseif($tmp_k==2){
                            $where_sql = " and datediff('$now_time',post_create_time) >=2 ";
                        }elseif($tmp_k==3){
                            $where_sql = " and datediff('$now_time',post_create_time) >=3 ";
                        }elseif($tmp_k==4){
                            $where_sql = " and datediff('$now_time',post_create_time) >=4 ";
                        }elseif($tmp_k==5){
                            $where_sql = " and datediff('$now_time',post_create_time) >=5 ";
                        }elseif($tmp_k==7){
                            $where_sql = " and datediff('$now_time',post_create_time) >=7 ";
                        }elseif($tmp_k==8){
                            $where_sql = " and datediff('$now_time',post_create_time) <1 ";
                        }

                        if($key==0){
                            $sql = "select user_id from baby_toys_order where region_id like '%".$tmp_city_id."%' and business_id not in ($str_card_info) and status in (2,17,10) and is_prize in (0,3,6) $where_sql and state='0' and user_id not in ($line_have_res_str)";
                        }else{
                            $sql .= " union  select user_id from baby_toys_order where region_id like '%".$tmp_city_id."%' and business_id not in ($str_card_info) and status in (2,17,10) and is_prize in (0,3,6)  $where_sql and state='0' and user_id not in ($line_have_res_str) ";
                        }



                    }
                    $result = $model->query($sql);
                }

                $tmp_user_ids_arr = array();
                if($result){
                    foreach($result as $val){
                        $tmp_user_ids_arr[] = $val['user_id'];
                    }
                    $tmp_user_ids_arr = array_unique($tmp_user_ids_arr);
                }
            $tmp_user_id=$tmp_user_ids_arr;
            $real_arr_user_id=array();
            foreach ($tmp_user_id as $value) {
                if($value>0) {
                    $real_arr_user_id[]=$value;
                }
            }
            $str_user_id=implode(",", $real_arr_user_id);
                if(!empty($search_arr_user_id)){
                    $str_user_id = $search_arr_user_id;
                }
                $this->assign('str_user_id',$str_user_id);
                    $where2.=" and o.user_id in ($str_user_id) ";
                    $this->assign('arr_user_id',$search_arr_user_id);
                    $order_condion="FIELD(o.user_id,$str_user_id) ";
        }elseif($allofthem==1){
                    if($dayall==0){
                        $where_sql2 = "";
                    }elseif($dayall==1){
                        $where_sql2 = " and datediff('$now_time',post_create_time) >=1 ";
                    }elseif($dayall==2){
                        $where_sql2 = " and datediff('$now_time',post_create_time) >=2 ";
                    }elseif($dayall==3){
                        $where_sql2 = " and datediff('$now_time',post_create_time) >=3 ";
                    }elseif($dayall==4){
                        $where_sql2 = " and datediff('$now_time',post_create_time) >=4 ";
                    }elseif($dayall==5){
                        $where_sql2 = " and datediff('$now_time',post_create_time) >=5 ";
                    }elseif($dayall==7){
                        $where_sql2 = " and datediff('$now_time',post_create_time) >=7 ";
                    }elseif($dayall==8){
                        $where_sql2 = " and datediff('$now_time',post_create_time) <1 ";
                    }

                    $sql2 = "select user_id from baby_toys_order where state='0' and business_id not in ($str_card_info) and status in (2,17,10) and is_prize in (0,3,6) $where_sql2 and user_id not in ($line_have_res_str) ";
//                echo $sql2;
                    $result = $model->query($sql2);
                $tmp_user_ids_arr = array();
                if($result){
                    foreach($result as $val){
                        $tmp_user_ids_arr[] = $val['user_id'];
                    }
                    $tmp_user_ids_arr = array_unique($tmp_user_ids_arr);
                }

                $tmp_user_id=$tmp_user_ids_arr;
                $real_arr_user_id=array();
                foreach ($tmp_user_id as $value) {
                    if($value>0) {
                        $real_arr_user_id[]=$value;
                    }
                }
                $str_user_id=implode(",", $real_arr_user_id);
                if(!empty($search_arr_user_id)){
                    $str_user_id = $search_arr_user_id;
                }
                $this->assign('str_user_id',$str_user_id);
                $where2.=" and o.user_id in ($str_user_id) ";
                $this->assign('arr_user_id',$search_arr_user_id);
                $order_condion="FIELD(o.user_id,$str_user_id) ";

            }elseif(!empty($search_arr_user_id)){
                $this->assign('str_user_id',$search_arr_user_id);
                $where2.=" and o.user_id in ($search_arr_user_id) ";
                $this->assign('arr_user_id',$search_arr_user_id);
                $order_condion="FIELD(o.user_id,$search_arr_user_id) ";
            } else {
            $order_condion="update_time desc ";
        }

        $where2.=" and ((o.status in (2,17)) or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 ) )  ";

//echo $where2;
        if($excel_state==1) {
        //导出
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
//                    array('new_create_time','下单时间'),
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
                    //查询最新的订单表地址start
                    $new_order_address_arr = $model->table('baby_toys_order')
                        ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                        ->field('address')
                        ->order('create_time asc') //status asc,post_
                        ->select();
                    $order_address_arr = array();
                    $address_only = 0;
                    if($new_order_address_arr){
                        foreach($new_order_address_arr as $val){
                            $order_address_arr[] = $val['address'];
                        }
                        $order_address = $order_address_arr[0];
                        $order_address_arr = array_unique($order_address_arr);
                        $address_num = count($order_address_arr);
                        if($address_num>1){
                            $address_only = 1;
                            $address_chose = "";
                            foreach($order_address_arr as $vv){
                                $address_chose .= "\n□".$vv;
                            }
                        }
                    }
                    //查询最新的订单表地址end
                    $user_info=$send_toys=$pick_toys="";
                    if($order_user_name) {
                        $user_info.=$order_user_name;
                    }
                    if($order_mobile) {
                        $user_info.="\n".$order_mobile;
                    }
                    if($order_address && ($address_only==0)) {
                        $user_info.="\n".$order_address;
                    }
                    if($address_only==1){
                        $user_info.=$address_chose;
                    }
                    $send_toys=$pick_toys="";

                    //配送
                    $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","\n");
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
                    //取回备注
                    $toys_back_remark = $returnpickInfo['remark'];
                    $remark.=$toys_back_remark;
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                        $business_parts_lists = $returnpickInfo['business_parts_list'];
                    }
                    $user_act = " □ 玩具实物和所租是否相符\n □ 需安装玩具是否安装\n □ 玩具是否干净\n 签收人：____________";
                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id."\n".$tmp_new_create_time,
                        'user_info'=>$user_info,
//                        'new_create_time'=>$tmp_new_create_time,
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
//        var_dump($getUserRes);
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_user_id=$value['user_id'];
                $user_id_number = $value['user_id'];
                $order_address=$value['address'];

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address,hold,hold_reason')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                        $hold777 = $val['hold'];
                        $hold_reason777 = $val['hold_reason'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送11
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
                }
                $res[]=array(
                    'user_id_number'=>$user_id_number,
                    'user_id'=>$tmp_user_id,
                    'user_info'=>$user_info,
                    'send_toys'=>$send_toys,
                    'pick_toys'=>$pick_toys,
                    'remark'=>$remark,
                    'new_create_time'=>$tmp_new_create_time,
                    'hold'=>$hold777,
                    'hold_reason'=>$hold_reason777
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //异常派单地图玩具展示
    public function gaodepointtoysinfoneedtemp() {
        $this->checkSession();
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        //已经生成路线的用户
        $line_have_res= $model->table('baby_toys_line_plan')
            ->where("  state='0' and status in (1,5) ")
            ->field("user_id")
            ->group("user_id")
            ->select();
        if($line_have_res){
            foreach($line_have_res as $val){
                $line_have_res_arr[] = $val['user_id'];
            }
            $line_have_res_str = implode(',',$line_have_res_arr);
        }else{
            $line_have_res_str = "0";
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
        //查询异常用户的user_id  //        $wherenew.=" and (o.lat is null or o.lat='' or o.lng is null or o.lng='' or o.region_id is null or o.region_id='' ) ";
        $wherenew=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6) and o.user_id not in ($line_have_res_str)  ";
        $wherenew.=" and ((o.status in (2,17,10))  )  ";//or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 )

        $diff_user_id_res = $model->table('baby_toys_order as o')
            ->join(" left join baby_user_address as a on a.user_id=o.user_id ")
//            ->join(" left join baby_toys_listing as l on l.order_id=o.id")
            ->where($wherenew)
            ->field('o.user_id')
            ->group("o.user_id")
            ->select();

        $diff_user_id_arr = array();
        if($diff_user_id_res){
            foreach($diff_user_id_res as $val){
                $diff_order_address_arr = array();
                $diff_user_id = $val['user_id'];
                $diff_res = $model->table('baby_toys_order')
                    ->where("  state='0' and status in (2,5,17,10) and is_prize in (0,3,5,6) and user_id in ($diff_user_id) and business_id not in ($str_card_info) ")
                    ->field("user_id,lat,lng,region_id,address")
                    ->select();
                if($diff_res){
                    $diff_order_address = array();
                    foreach($diff_res as $value){
                        $diff_address = $value['address'];
                        $diff_order_address[] = $diff_address;
                        $diff_lat = $value['lat'];
                        $diff_lng = $value['lng'];
                        $diff_region_id = $value['region_id'];
                        //经纬度区域缺失
                        if( empty($diff_lat) || empty($diff_lng) || empty($diff_region_id) ){
                            $diff_user_id_arr[] = $diff_user_id;
                        }
                    }
                    $diff_order_address = array_unique($diff_order_address);
                    $diff_order_address_count = count($diff_order_address);
                    if($diff_order_address_count>0){
                        $diff_user_id_arr[] = $diff_user_id;
                    }
                }
            }
        }
        if($diff_user_id_arr){
            $diff_user_id_arr = array_unique($diff_user_id_arr);
            $new_user_id_str = implode(',',$diff_user_id_arr);
            $where_diff = " and o.user_id in ($new_user_id_str) ";
        }else{
            $where_diff = "";
        }
//var_dump($diff_user_id_arr);die;
        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6) and o.user_id not in ($line_have_res_str)  ";
        $where2.=" and o.user_id not in ($common_user_id_str) ";
        $where2.=" and ((o.status in (2,17)) or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 ) )  $where_diff  ";
        $order_condion="update_time desc ";

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
                $order_address=$value['address'];

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
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

    //异常派单地图玩具展示
    public function gaodepointtoysinfoneed() {
        $this->checkSession();
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        //已经生成路线的用户
        $line_have_res= $model->table('baby_toys_line_plan')
            ->where("  state='0' and status in (1,5) ")
            ->field("user_id")
            ->group("user_id")
            ->select();
        if($line_have_res){
            foreach($line_have_res as $val){
                $line_have_res_arr[] = $val['user_id'];
            }
            $line_have_res_str = implode(',',$line_have_res_arr);
        }else{
            $line_have_res_str = "0";
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

        //查询限制账号
        $getQuestionsRes= $model->table('baby_toys_order_questions')
            ->where("state='0'")
            ->field('user_id')
            ->select();
//        var_dump($getQuestionsRes);die;
        if($getQuestionsRes){
            foreach($getQuestionsRes as $val){
                $getQuestionsArr[] = $val['user_id'];
            }
        }else{
            $getQuestionsArr = array();
        }



        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6) and o.user_id not in ($line_have_res_str)  ";
        $where2.=" and o.user_id not in ($common_user_id_str) ";

        $wheresend =$where2." and o.status in (2,17) ";
        $whereback =$where2." and o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 ";
        $order_condion="update_time desc ";

        $sql = " select o.user_id
              from baby_toys_order as o
              left join baby_user_address as a on a.user_id=o.user_id where $wheresend
              union select o.user_id
              from baby_toys_order as o
              left join baby_user_address as a on a.user_id=o.user_id
              left join baby_toys_listing as l on l.order_id=o.id where $whereback
              ";
//        echo $sql;die;
        $getUserRes = $model->query($sql);

        $diff_user_id_arr = array();
        $diff_user_id_region_arr = array();
        $diff_user_id_arr_new = array();
        if($getUserRes){
            foreach($getUserRes as $val){
                $diff_order_address_arr = array();
                $diff_user_id_region = 0;
                $diff_user_id = $val['user_id'];
                $diff_res = $model->table('baby_toys_order')
                    ->where("  state='0' and status in (2,5,17,10) and is_prize in (0,3,5,6) and user_id in ($diff_user_id) and business_id not in ($str_card_info) ")
                    ->field("user_id,lat,lng,region_id,address")
                    ->select();
                if($diff_res){
                    $diff_order_address = array();
                    foreach($diff_res as $value){
                        $diff_address = $value['address'];
                        $diff_order_address[] = $diff_address;
                        $diff_lat = $value['lat'];
                        $diff_lng = $value['lng'];
                        $diff_region_id = $value['region_id'];
                        //经纬度区域缺失
                        if( empty($diff_lat) || empty($diff_lng) || empty($diff_region_id) ){
                            $diff_user_id_arr[] = $diff_user_id;
                        }
//                        if( !empty($diff_lat) && !empty($diff_lng) && !empty($diff_region_id) ){
//                            $diff_user_id_region_arr[] = $diff_user_id;
//                        }
                    }
//                    删除包含经纬度区域的user_id
//                    if($diff_user_id_region_arr){
//                        $diff_user_id_arr_new = array_diff($diff_user_id_arr,$diff_user_id_region_arr);
//                    }

                    $diff_order_address = array_unique($diff_order_address);
                    $diff_order_address_count = count($diff_order_address);
                    if($diff_order_address_count>1){
//                        $diff_user_id_arr[] = $diff_user_id;
                    }
                }
            }
        }

        if($diff_user_id_arr){//$diff_user_id_arr_new
            $diff_user_id_arr = array_unique($diff_user_id_arr);
            $getUserRes = array();
            foreach($diff_user_id_arr as $key => $value){
                $getUserRes["$key"]['user_id'] = $value;
            }
        }
//var_dump($getQuestionsArr);
        if($getQuestionsArr){
            //限制用户id加入查询数组
            foreach($getQuestionsArr as $v){
                $getUserRes[] = array(
                    'user_id'=>$v
                );
            }
        }
//var_dump($getUserRes);die;
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_user_id=$value['user_id'];
                $tmp_user_id_number=$value['user_id'];
                $order_address="";

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
                }

                if(in_array($tmp_user_id_number,$getQuestionsArr)){
                    $is_send_status = 1;
                }else{
                    $is_send_status = 0;
                }

                $res[]=array(
                    'user_id_number'=>$tmp_user_id_number,
                    'is_send_status'=>$is_send_status,
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

    //异常派单地图玩具展示新版测试
    public function gaodepointtoysinfoneedtest() {
        $this->checkSession();
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

//跑程序禁止单子----------------------start--------------------------------------------------
        $str_card_id=$str_card_info;
        $Model = new Model();//或者 $Model = D(); 或者 $Model = M();
        $line_sql777 = " select distinct user_id from baby_toys_line_plan where state='0' and status in (1,5) ";
        $line_res777 = $Model->query($line_sql777);

        if($line_res777){
            foreach($line_res777 as $val){
                $line_have_res_arr777[] = $val['user_id'];
            }
            $line_str777 = implode(',',$line_have_res_arr777);
        }else{
            $line_str777 = "0";
        }

        //查询所有需要判断的用户id
        $sql777 = " select distinct user_id from baby_toys_order where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and hold=0 and user_id not in ($line_str777) and hold_status=0   ";//and create_time>'2018-05-17 14:08:00'
        $res777 = $Model->query($sql777);

        if($res777){
            foreach($res777 as $val){
                $user_id = $val['user_id'];
                $sql_one = " select status,address,region_id,lat,lng,hold from baby_toys_order  where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id = '$user_id' ";
                $res_one = $Model->query($sql_one);
                $diff_order_address = array();
                $status_arr = array();
                $hold = 0;
                $region = 0;
                $hold_reason = "";
                $right_back_address = 0;
                $right_back_status = 0;
                $right_back_region = 0;
                $hold_back_arr = array();
                foreach($res_one as $value){
                    $diff_address = $value['address'];
                    $diff_order_address[] = $diff_address;
                    $diff_lat = $value['lat'];
                    $diff_lng = $value['lng'];
                    $diff_region_id = $value['region_id'];
                    $status = $value['status'];
                    $status_arr[] = $status;
                    $hold_back = $value['hold'];
                    $hold_back_arr[] = $hold_back;

                    //经纬度区域缺失
                    if( empty($diff_lat) || empty($diff_lng) || empty($diff_region_id) ){
                        $region=1;
                    }

                }

                //多地址
                $diff_order_address = array_unique($diff_order_address);
                $diff_order_address_count = count($diff_order_address);
                if($diff_order_address_count>1){
                    $hold = 1;
                    $hold_reason = "多地址";
                }else{
                    $right_back_address = 1;
                }

                //只有待取回订单
                $status_arr = array_unique($status_arr);
                $status_arr_count = count($status_arr);
                if($status_arr_count==1){
                    $new_status = $status_arr[0];
                    if($new_status==10){
                        $hold = 1;
                        $hold_reason = "只有待取回订单";
                    }else{
                        $right_back_status = 1;
                    }
                }

                if($status_arr_count>1){
                    $right_back_status = 1;
                }

                //经纬度缺失
                if($region==1){
                    $hold = 1;
                    $hold_reason = "经纬度缺失";
                }else{
                    $right_back_region = 1;
                }

                //已筛查的订单做标记
                $upsql7778 = " update baby_toys_order set hold_status=1 where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id='$user_id' ";
                $upres7778 = $Model->query($upsql7778);

                //禁止展示在地图
                if($hold==1){
                    $upsql777 = " update baby_toys_order set hold='$hold',hold_reason='$hold_reason' where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id='$user_id' ";
                    $upres777 = $Model->query($upsql777);
                }

                //纠错
                if( ($right_back_status==1)&&($right_back_region==1)&&($right_back_address==1)&&(in_array(1,$hold_back_arr)) ){
                    $upsql2777 = " update baby_toys_order set hold='0',hold_reason='恢复正常' where state='0' and business_id not in ($str_card_id) and status in (2,17,10) and is_prize in (0,3,6) and user_id='$user_id' ";
                    $upres2777 = $Model->query($upsql2777);
                }




            }
        }

        //跑程序禁止单子----------------------end---------------------------------------------------

        //查询限制账号
        $search_question_user_id = intval(I("search_question_user_id"));
        $where_search = "state='0' and hold in (1,2) and status in (2,17,5,10)  ";

        if($search_question_user_id>0){
            $where_search.=" and user_id = '$search_question_user_id' ";
            $this->assign('search_question_user_id',$search_question_user_id);
        }

        $getUserRes= $model->table('baby_toys_order')
            ->where($where_search)
            ->field('user_id')
            ->group("user_id")
            ->select();

//var_dump($getUserRes);die;
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $tmp_user_id=$value['user_id'];
                $tmp_user_id_number=$value['user_id'];
                $order_address="";

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address,hold,hold_reason,mobile')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                        $hold = $val['hold'];
                        $hold_reason = $val['hold_reason'];
                        $mobile_user = $val['mobile'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
                }

                if(in_array($tmp_user_id_number,$getQuestionsArr)){
                    $is_send_status = 1;
                }else{
                    $is_send_status = 0;
                }

                $card_time_info = "";
                if($tmp_user_id_number){
                    $new_card_id_arr = $model->table('baby_toys_order')
                        ->where("user_id='$tmp_user_id_number' and state='0' and status in (2,17,5,10) and card_id>0 ")
                        ->field('card_id')
                        ->group('card_id')
                        ->select();

                    if($new_card_id_arr){
                        foreach($new_card_id_arr as $value){
                            $card_id_tmp = $value['card_id'];
                            $new_card_id_info = $model->table('baby_toys_card')
                                ->where("id='$card_id_tmp'")
                                ->field('end_time,final_end_time')
                                ->find();
                            $card_end_time = $new_card_id_info['end_time'];
                            $card_final_end_time = $new_card_id_info['final_end_time'];
                            if($card_final_end_time!='0000-00-00 00:00:00'){
                                $card_time_info .= "卡id：".$card_id_tmp."<br>到期时间：".$card_final_end_time.'<br>';
                            }else{
                                $card_time_info .= "卡id：".$card_id_tmp."<br>到期时间：".$card_end_time.'<br>';
                            }
                        }
                    }
                }
                $user_info = $mobile_user.'<br>'.$user_info;
                $res[]=array(
                    'user_id_number'=>$tmp_user_id_number,
                    'is_send_status'=>$is_send_status,
                    'user_id'=>$tmp_user_id,
                    'user_info'=>$user_info,
                    'send_toys'=>$send_toys,
                    'pick_toys'=>$pick_toys,
                    'remark'=>$remark,
                    'new_create_time'=>$tmp_new_create_time,
                    'hold'=>$hold,
                    'hold_reason'=>$hold_reason,
                    'card_time_info'=>$card_time_info
                );

            }
        }
//        var_dump($res);die;
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //异常派单地图玩具地址修改
    public function gaodepointtoysinfoneededit() {
        $this->checkSession();
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();
        $user_id = I("user_id");
        if(empty($user_id)){
            die("用户错误");
        }
        $this->assign('user_id',$user_id);

        $where2=" o.business_id not in ($str_card_info) and o.state='0' and o.is_prize in (0,3,5,6) and o.user_id='$user_id'  ";
        $where2.=" and ((o.status in (2,17)) or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 ) ) ";

        $getUserRes= $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_listing as l on l.order_id=o.id")
            ->where($where2)
            ->field("o.id as order_id")
            ->select();
        $order_ids = "";
        $order_ids_arr = array();
        if($getUserRes){
            foreach($getUserRes as $val){
                $order_ids_arr[] = $val['order_id'];
            }
            $order_ids_arr = array_unique($order_ids_arr);
            $order_ids = implode(',',$order_ids_arr);
        }
        $this->assign('order_ids',$order_ids);
//        var_dump($order_ids);die;
        $regionA= $model->table('baby_toys_citys_regions')
            ->where("state='0'")
            ->field('id,county,region,county_id')
            ->order("county")
            ->select();
//var_dump($regionA);die;
        $cityInfo=array();
        if (is_array($regionA) && count($regionA)) {
            foreach ($regionA as $key => $value) {
                $county = $value['county'];
                $region = $value['region'];
                $new_name = $county.'-'.$region;
                $city_region_country_id = $value['county_id'];
                $city_id = $value['id'];
                $order_region_id_one = $city_region_country_id*1000;
                $order_region_id_two = $order_region_id_one+$city_id;
                $order_region_id = $order_region_id_one.",".$order_region_id_two;
                $cityInfo[] = array(
                    'new_name' => $new_name,
                    'region_id' => $city_id
                );

            }

        }
        $res = $cityInfo;
//        var_dump($res);die;


        $this->assign('res',$res);//赋值数据集
        $this->display();
    }

    //派单线路
    public function gaodepointline() {
        $this->checkSession();

        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        $where2 = "p.state='0' and p.root_img_id=0";
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_line_plan as p')
            ->join(" left join baby_user as u on p.postman_id=u.id ")
            ->where($where2)
            ->field('p.id')
            ->select();
        $count=count($user_count);
        $page = new  Page($count,10);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $getUserRes= $model->table('baby_toys_line_plan as p')
            ->join(" left join baby_user as u on p.postman_id=u.id ")
            ->where($where2)
            ->field('p.id,p.title,p.create_time,p.post_create_time,p.postman_id,p.remark,p.status,u.user_name')
            ->order("p.id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res = array();
        if($getUserRes){
            foreach($getUserRes as $val){
                $id = $val['id'];
                $title = $val['title'];
                $create_time = $val['create_time'];
                $post_create_time = $val['post_create_time'];
                $postman_id = $val['postman_id'];
                $postman_name = $val['user_name'];
                $remark = $val['remark'];
                $status = $val['status'];
                if($status==0){
                    $status_name="未生成路线";
                }elseif($status==1){
                    $status_name="<font color='red'>待派单</font>";
                }elseif($status==5){
                    $status_name="已分配配送员";
                }
                //查询完成量
                //总量
                $total_count_res = array();
                $total_count_res = $model->table('baby_toys_line_plan')
                    ->where("root_img_id=$id and state='0'")
                    ->field('user_id')
                    ->group("user_id")
                    ->select();
                $total_count = count($total_count_res);

                //完成量
                $total_count_end_res = array();
                $total_count_end_res = $model->table('baby_toys_line_plan')
                    ->where("root_img_id=$id and state='0' and status in(2,3) ")
                    ->field('user_id')
                    ->group("user_id")
                    ->select();
                $total_count_end = count($total_count_end_res);

                $process = $total_count_end.'/'.$total_count;

                $process_end_res = $model->table('baby_toys_line_plan')
                    ->where("root_img_id=$id and state='0' and status in(3,4) ")
                    ->field('user_id')
                    ->select();
                if($process_end_res){
                    $process_name = "<font color='red'>包含取消订单</font>";
                }else{
                    $process_name = "正常";
                }

                $res[] = array(
                    'id' => $id,
                    'title' => $title,
                    'create_time' => $create_time,
                    'post_create_time' => $post_create_time,
                    'postman_id' => $postman_id,
                    'postman_name' => $postman_name,
                    'remark' => $remark,
                    'status' => $status,
                    'status_name' => $status_name,
                    'process' => $process,
                    'process_name' => $process_name
                );
            }
        }
        $search_user_id = I('search_user_id');
        $search_title = "";
        if($search_user_id){
            $res_search = $model->table('baby_toys_line_plan')
                ->where("user_id=$search_user_id and state='0' and status in(1,5) ")
                ->field('title,root_img_id')
                ->order("id desc")
                ->find();
            if($res_search){
                $is_title = "确认删除？";
                $search_title = "用户".$search_user_id.'所在线路是：'.$res_search['title']
                    ."<br><a href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfodel?user_id=".$search_user_id."&search_root_img_id=".$res_search['root_img_id']." ' onclick='return confirm(1)' >此路线中删除该用户</a>";
            }else{
                $search_title = "该用户不在线路内";
            }
        }
        $this->assign('search_title',$search_title);
        $this->assign('search_user_id',$search_user_id);
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出

        $res_search_remark = $model->table('baby_toys_line_plan as t')
            ->join("baby_toys_order as o on t.order_id=o.id")
            ->join("baby_toys_business_listing as b on o.toys_number=b.id")
            ->join("baby_toys_business as bb on b.business_id=bb.id")
            ->where(" t.state='0' and t.status in(1) and o.toys_number>0 and o.status in (2) and (  (b.order_remark <> '' and b.order_remark is not null) or (b.remark <> '' and b.remark is not null)  ) ")
            ->field('b.order_remark,o.user_id,o.mobile,t.title,bb.business_title,b.id,b.business_id,b.remark')
            ->order('t.root_img_id')
            ->select();

        $this->assign('res_search_remark',$res_search_remark);

        $this->display();
    }

    //派单超过一天的订单信息列表
    public function gaodesendoutday() {
        $this->checkSession();

        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        $where2 = "p.state='0' and p.root_img_id>0 and datediff('$now_time',p.post_create_time) >=1 and p.status in (1,5)  ";
        //满足条件的总记录数1
        $user_count  = $model->table('baby_toys_line_plan as p')
            ->join(" left join baby_user as u on p.postman_id=u.id ")
            ->where($where2)
            ->field('p.id')
            ->select();
        $count=count($user_count);
        $page = new  Page($count,10);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $getUserRes= $model->table('baby_toys_line_plan as p')
            ->join(" left join baby_user as u on p.postman_id=u.id ")
            ->join(" left join baby_toys_order as o on p.order_id=o.id ")
            ->where($where2)
            ->field('p.*,u.user_name,o.status as o_status')
            ->order("p.id desc")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res = array();

//        echo "<pre>";
//        print_r($getUserRes);die;

        if($getUserRes){
            foreach($getUserRes as $val){
                $id = $val['id'];
                $order_id = $val['order_id'];
                $user_id = $val['user_id'];
                $title = $val['title'];
                $create_time = $val['create_time'];
                $post_create_time = $val['post_create_time'];
                $postman_id = $val['postman_id'];
                $postman_name = $val['user_name'];
                $remark = $val['remark'];
                $status = $val['status'];
                $o_status = $val['o_status'];

                if($o_status==5) {
                    $o_status_name="送货中";
                } elseif($o_status==6) {
                    $o_status_name="玩乐中";
                } elseif($o_status==7) {
                    $o_status_name="待入库";
                } elseif($o_status==8) {
                    $o_status_name="退款中";
                } elseif($o_status==9) {
                    $o_status_name="已退款";
                } elseif($o_status==10) {
                    $o_status_name="待取回";
                } elseif($o_status==11) {
                    $o_status_name="已入库";
                } elseif($o_status==12) {
                    $o_status_name="已过期";
                } elseif($o_status==14) {
                    $o_status_name="恢复玩乐";
                }elseif($o_status==16) {
                    $o_status_name="问题订单";
                }elseif($o_status==17) {
                    $o_status_name="重新配送";
                }else {
                    $o_status_name="";
                }

                if($status==0){
                    $status_name="未生成路线";
                }elseif($status==1){
                    $status_name="<font color='red'>待派单</font>";
                }elseif($status==5){
                    $status_name="已分配配送员";
                }

                $res[] = array(
                    'id' => $id,
                    'order_id' => $order_id,
                    'title' => $title,
                    'create_time' => $create_time,
                    'post_create_time' => $post_create_time,
                    'postman_id' => $postman_id,
                    'postman_name' => $postman_name,
                    'remark' => $remark,
                    'status' => $status,
                    'status_name' => $status_name,
                    'o_status_name' => $o_status_name,
                    'user_id' => $user_id
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //路线删除用户
    public function gaodesendoutdaydel(){
        $this->checkSession();
        $id = I("id");
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        if(empty($id)){
            die("请选择要删除的订单");
        }

        $data['state'] = '1' ;
        $data['post_create_time'] = $now_time ;
        $data['remark'] = '超时删除' ;
        $res_line = M('toys_line_plan')->where("id=$id")->data($data)->save();

        if($res_line){
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodesendoutday.html';
            </script>
        ";
        }else{
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodesendoutday.html';
            </script>
        ";
        }

    }

    //已分配路线玩具展示
    public function gaodepointlineinfo() {
        $this->checkSession();
        $excel_state=I("excel_state");//是否打印表格 1：生成
        $search_arr_user_id=I("arr_user_id");
        $search_root_img_id = I("search_root_img_id");
        $this->assign('search_root_img_id',$search_root_img_id);
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();
        if(empty($search_arr_user_id)){
            //已经生成路线的用户
            $line_have_res= $model->table('baby_toys_line_plan')
                ->where("  state='0' and status=1 and root_img_id=$search_root_img_id ")
                ->field("user_id")
                ->group("user_id")
                ->order("id")
                ->select();
            if($line_have_res){
                foreach($line_have_res as $val){
                    $line_have_res_arr[] = $val['user_id'];
                }
                $search_arr_user_id = implode(',',$line_have_res_arr);
            }else{
                die("错误操作");
            }
        }

        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6)  ";


        $this->assign('str_user_id',$search_arr_user_id);
        $where2.=" and o.user_id in ($search_arr_user_id) ";
        $this->assign('arr_user_id',$search_arr_user_id);
        $order_condion="FIELD(o.user_id,$search_arr_user_id) ";


        $where2.=" and ((o.status in (2,17)) or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 ) )  ";

        if($excel_state==1) {
            //导出
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
//                    array('new_create_time','下单时间'),
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
                    //查询最新的订单表地址start
                    $new_order_address_arr = $model->table('baby_toys_order')
                        ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                        ->field('address,toys_number')
                        ->order('create_time asc') //status asc,post_
                        ->select();
                    $order_address_arr = array();
                    $new_toys_nunmber_arr = array();
                    $address_only = 0;
                    if($new_order_address_arr){
                        foreach($new_order_address_arr as $val){
                            $order_address_arr[] = $val['address'];
                            $new_toys_nunmber_arr[] = $val['toys_number'];
                        }
                        $order_address = $order_address_arr[0];
                        $order_address_arr = array_unique($order_address_arr);
                        $address_num = count($order_address_arr);
                        if($address_num>1){
                            $address_only = 1;
                            $address_chose = "";
                            foreach($order_address_arr as $vv){
                                $address_chose .= "\n□".$vv;
                            }
                        }

                        //查询入库备注
                        if($new_toys_nunmber_arr){
                            $order_remark_str = "";
                            foreach($new_toys_nunmber_arr as $vvv){
                                $toys_number_r = $vvv;
                                $toys_number_res= $model->table('baby_toys_business_listing')
                                    ->where("  id=$toys_number_r ")
                                    ->field("order_remark")
                                    ->find();
                                $order_remark_business = $toys_number_res['order_remark'];
                                if(!empty($order_remark_business)){
                                    $order_remark_str .= "\n（编号".$toys_number_r."备注：".$order_remark_business."）";
                                }
                            }
                        }
//                        var_dump($order_remark_str);die;

                    }
                    //查询最新的订单表地址end
                    $user_info=$send_toys=$pick_toys="";
                    if($order_user_name) {
                        $user_info.=$order_user_name;
                    }
                    if($order_mobile) {
                        $user_info.="\n".$order_mobile;
                    }
                    if($order_address && ($address_only==0)) {
                        $user_info.="\n".$order_address;
                    }
                    if($address_only==1){
                        $user_info.=$address_chose;
                    }
                    $send_toys=$pick_toys="";

                    //配送
                    $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","\n");
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
                    //取回备注
                    $toys_back_remark = $returnpickInfo['remark'];
                    $remark.=$toys_back_remark;
                    if(!empty($order_remark_str)){
                        $remark.=$order_remark_str;
                    }
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                        $business_parts_lists = $returnpickInfo['business_parts_list'];
                    }
                    $user_act = " □ 玩具实物和所租是否相符\n □ 需安装玩具是否安装\n □ 玩具是否干净\n 签收人：____________";
                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id."\n".$tmp_new_create_time,
                        'user_info'=>$user_info,
//                        'new_create_time'=>$tmp_new_create_time,
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
                $order_address=$value['address'];

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
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

    //已分配路线订单删减操作
    public function gaodepointlineinfoadddel() {
        $this->checkSession();
        $excel_state=I("excel_state");//是否打印表格 1：生成
        $search_arr_user_id=I("arr_user_id");
        $search_root_img_id = I("search_root_img_id");
        $this->assign('search_root_img_id',$search_root_img_id);
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();
        if(empty($search_arr_user_id)){
            //已经生成路线的用户
            $line_have_res= $model->table('baby_toys_line_plan')
                ->where("  state='0' and root_img_id=$search_root_img_id ")//and status=1 
                ->field("user_id")
                ->group("user_id")
                ->select();
            if($line_have_res){
                foreach($line_have_res as $val){
                    $line_have_res_arr[] = $val['user_id'];
                }
                $search_arr_user_id = implode(',',$line_have_res_arr);
            }else{
                die("错误操作");
            }
        }

        $where2=" o.business_id not in ($str_card_info) and o.state='0' and a.state='0' and a.is_defalut='1' and o.is_prize in (0,3,5,6)  ";


        $this->assign('str_user_id',$search_arr_user_id);
        $where2.=" and o.user_id in ($search_arr_user_id) ";
        $this->assign('arr_user_id',$search_arr_user_id);
        $order_condion="FIELD(o.user_id,$search_arr_user_id) ";


        $where2.=" and o.status in (2,17,5,10) ";//or (o.status=10 and l.status=7 and l.state='0' and l.postman_id=0 )

        if($excel_state==1) {
            //导出
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
//                    array('new_create_time','下单时间'),
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
                    //查询最新的订单表地址start
                    $new_order_address_arr = $model->table('baby_toys_order')
                        ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                        ->field('address')
                        ->order('create_time asc') //status asc,post_
                        ->select();
                    $order_address_arr = array();
                    $address_only = 0;
                    if($new_order_address_arr){
                        foreach($new_order_address_arr as $val){
                            $order_address_arr[] = $val['address'];
                        }
                        $order_address = $order_address_arr[0];
                        $order_address_arr = array_unique($order_address_arr);
                        $address_num = count($order_address_arr);
                        if($address_num>1){
                            $address_only = 1;
                            $address_chose = "";
                            foreach($order_address_arr as $vv){
                                $address_chose .= "\n□".$vv;
                            }
                        }
                    }
                    //查询最新的订单表地址end
                    $user_info=$send_toys=$pick_toys="";
                    if($order_user_name) {
                        $user_info.=$order_user_name;
                    }
                    if($order_mobile) {
                        $user_info.="\n".$order_mobile;
                    }
                    if($order_address && ($address_only==0)) {
                        $user_info.="\n".$order_address;
                    }
                    if($address_only==1){
                        $user_info.=$address_chose;
                    }
                    $send_toys=$pick_toys="";

                    //配送
                    $returnsendInfo=$this->returnsendInfo("o.status in (2,17,5) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","\n");
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
                    //取回备注
                    $toys_back_remark = $returnpickInfo['remark'];
                    $remark.=$toys_back_remark;
                    if($returnpickInfo) {
                        $pick_toys=$returnpickInfo['pick_toys'];
                        $business_parts_lists = $returnpickInfo['business_parts_list'];
                    }
                    $user_act = " □ 玩具实物和所租是否相符\n □ 需安装玩具是否安装\n □ 玩具是否干净\n 签收人：____________";
                    $xlsData[]=array(
                        'user_id'=>$tmp_user_id."\n".$tmp_new_create_time,
                        'user_info'=>$user_info,
//                        'new_create_time'=>$tmp_new_create_time,
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
                $order_address=$value['address'];

                //查询最新的订单表地址start
                $new_order_address_arr = $model->table('baby_toys_order')
                    ->where("user_id='$tmp_user_id' and state='0' and status in (2,17,5,10) and is_prize in (0,3,6) and business_id not in ($str_card_info)")
                    ->field('address')
                    ->order('create_time asc')//status asc,post_
                    ->select();
                $order_address_arr = array();
                $address_only = 0;
                if($new_order_address_arr){
                    foreach($new_order_address_arr as $val){
                        $order_address_arr[] = $val['address'];
                    }
                    $order_address = $order_address_arr[0];
                    $order_address_arr = array_unique($order_address_arr);
                    $address_num = count($order_address_arr);
                    if($address_num>1){
                        $address_only = 1;
                        $address_chose = "";
                        foreach($order_address_arr as $vv){
                            $address_chose .= "<br>□".$vv;
                        }
                    }
                }
                //查询最新的订单表地址end

                $order_user_name=$value['user_name'];
                $order_mobile=$value['mobile'];

                $user_info=$send_toys=$pick_toys="";
                if($order_user_name) {
                    $user_info.=$order_user_name;
                }
                if($order_mobile) {
                    $user_info.="<br/>".$order_mobile;
                }
                if($order_address && ($address_only==0)) {
                    $user_info.="<br/>".$order_address;
                }
                $send_toys=$pick_toys="";
                //配送
                $returnsendInfo=$this->returnsendInfo("o.status in (2,17,5) and o.business_id not in ($str_card_info) and o.state='0' and o.user_id=$tmp_user_id and o.is_prize in (0,3,6) ","<br/>");
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
                //取回备注
                $toys_back_remark = $returnpickInfo['remark'];
                $remark.=$toys_back_remark;
                if($returnpickInfo) {
                    $pick_toys=$returnpickInfo['pick_toys'];
                }
                if($address_only==1){
                    $tmp_user_id.= "<br><font style='color:red'>该用户有多个地址，请跟进</font>";
                    $user_info .= $address_chose;
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

    //历史路线玩具展示
    public function gaodepointlineinfoend() {
        $this->checkSession();

        $search_root_img_id = I("search_root_img_id");
        $this->assign('root_img_id',$search_root_img_id);
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        //已经生成路线的用户
        $line_have_res= $model->table('baby_toys_line_plan')
            ->where("  state='0' and root_img_id=$search_root_img_id ")
            ->field("user_id")
            ->select();
        $count=count($line_have_res);
        $page = new  Page($count,20);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出

        $getUserRes= $model->table('baby_toys_line_plan as p')
            ->join(" left join baby_toys_order as o on p.order_id=o.id ")
            ->join(" left join baby_toys_business as b on o.business_id=b.id ")
            ->join(" left join baby_user as u on p.postman_id=u.id ")
            ->where("p.state='0' and p.root_img_id=$search_root_img_id")
            ->field("p.*,o.is_prize,o.address,b.business_title,u.user_name as postman_name,o.address")
            ->order("user_id")
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $value) {
                $line_id = $value['id'];
                $line_title = $value['title'];
                $post_create_time = $value['post_create_time'];
                $postman_id = $value['postman_id'];
                $postman_name = $value['postman_name'];
                $user_id = $value['user_id'];
                $status = $value['status'];
                $send_back = $value['send_back'];
                $final_num = $value['final_num'];
                $remark = $value['remark'];
                $business_title = $value['business_title'];
                $is_prize = $value['is_prize'];
                $address = $value['address'];

                if($send_back==1){
                    $send_back_name = "送";
                }elseif($send_back==2){
                    $send_back_name = "取";
                }

                if($is_prize==1){
                    $business_title = "礼品";
                }elseif($is_prize==2){
                    $business_title = "电池";
                }

                if($status==0){
                    $status_name="未生成路线";
                }elseif($status==1){
                    $status_name="待派单";
                }elseif($status==2){
                    $status_name="成功送达";
                }elseif($status==3){
                    $status_name="<font color='red'>用户原因取消</font>";
                }elseif($status==4){
                    $status_name="<font color='red'>配送员原因取消</font>";
                }elseif($status==5){
                    $status_name="送货中";
                }

                $res[]=array(
                    'line_id' => $line_id,
                    'line_title' => $line_title,
                    'post_create_time' => $post_create_time,
                    'postman_id' => $postman_id,
                    'postman_name' => $postman_name,
                    'user_id' => $user_id,
                    'status' => $status,
                    'send_back' => $send_back,
                    'final_num' => $final_num,
                    'remark' => $remark,
                    'business_title' => $business_title,
                    'status_name' => $status_name,
                    'send_back_name' => $send_back_name,
                    'address' => $address
                );
            }
        }
//        var_dump($res);die;
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //路线新增用户
    public function gaodepointlineinfoadd(){
        $this->checkSession();
        $search_arr_user_id=I("arr_user_id_new");
        $search_root_img_id = I("search_root_img_id");
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();


        if(empty($search_arr_user_id)){
            die("请输入用户id");
        }
        if(empty($search_root_img_id)){
            die("非法路线");
        }
        //判断此路线是否已经生成
        $line_is_res= $model->table('baby_toys_line_plan')
            ->where("user_id in ($search_arr_user_id) and state='0' and status=1 ")
            ->field("user_id")
            ->group("user_id")
            ->select();

        if($line_is_res){
            die("失败！此路线包含已生成路线的用户，请重新添加用户");
        }


        $search_line_res= $model->table('baby_toys_line_plan')
            ->where("id=$search_root_img_id")
            ->field("title")
            ->find();
        if($search_line_res){
            $search_line = $search_line_res['title'];
        }else{
            die("路线已失效");
        }

        $line_user_id_arr = explode(',',$search_arr_user_id);

        $every_line_res = array();
        foreach($line_user_id_arr as $val){
            $line_user_id = $val;
            $every_line_son_res = M('toys_order')
                ->where("state='0' and status in (2,10,17)  and user_id='$line_user_id' and business_id not in ($str_card_info) and is_prize in (0,3,5,6) ")
                ->field('id,status')
                ->select();

            if($every_line_son_res){
                foreach($every_line_son_res as $value){
                    $temp_line_order_id = $value['id'];
                    $temp_line_status = $value['status'];
                    if($temp_line_status==10){
                        //取送判断
                        $temp_send_back = 2;//取
                    }else{
                        $temp_send_back = 1;//送
                    }
                    $every_line_res[] = array(
                        'title'=>$search_line,
                        'create_time'=>date("Y-m-d H:i:s"),
                        'post_create_time'=>date("Y-m-d H:i:s"),
                        'user_id'=>$line_user_id,
                        'order_id'=>$temp_line_order_id,
                        'status'=>1,
                        'root_img_id'=>$search_root_img_id,
                        'send_back'=>$temp_send_back
                    );
                }
            }

        }

        $res_line = M('toys_line_plan')->addAll($every_line_res);
        if($res_line){
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfo?search_root_img_id=$search_root_img_id';
            </script>
        ";
        }else{
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfo?search_root_img_id=$search_root_img_id';
            </script>
        ";
        }

    }

    //路线删除用户
    public function gaodepointlineinfodel(){
        $this->checkSession();
        $search_user_id=I("user_id");
        $search_root_img_id = I("search_root_img_id");
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        if(empty($search_user_id)){
            die("请输入用户id");
        }
        if(empty($search_root_img_id)){
            die("非法路线");
        }

        $data['state'] = '1' ;
        $data['post_create_time'] = $now_time ;
        $res_line = M('toys_line_plan')->where("user_id=$search_user_id and root_img_id=$search_root_img_id and status=1")->data($data)->save();

        if($res_line){
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfo?search_root_img_id=$search_root_img_id';
            </script>
        ";
        }else{
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfo?search_root_img_id=$search_root_img_id';
            </script>
        ";
        }

    }

    //路线编辑用户
    public function gaodepointlineinfoendedit(){
        $this->checkSession();
        $search_root_img_id = I("root_img_id");
        $line_id = I("line_id");
        $remark = I('remark');
        $source = I("source");//1：单量  2：备注
        $final_num = I("final_num");
        $search_user_id=I("user_id");
        $str_card_info=$this->getCardInfo();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $now_time = date("Y-m-d H:i:s");
        $model = new Model();

        if($source==1){
            //单量更改
            $data = array();
            $data['final_num'] = $final_num;
            $data['post_create_time'] = $now_time ;
            $res_line = M('toys_line_plan')->where("user_id=$search_user_id and root_img_id=$search_root_img_id ")->data($data)->save();
        }elseif($source==2){
            //编辑备注
            $data = array();
            $data['remark'] = $remark;
            $data['post_create_time'] = $now_time ;
            $res_line = M('toys_line_plan')->where("id=$line_id and root_img_id=$search_root_img_id ")->data($data)->save();
        }



        if($res_line){
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfoend?search_root_img_id=$search_root_img_id';
            </script>
        ";
        }else{
            echo"
            <script>
                window.location.href='http://checkpic.meimei.yihaoss.top/Toys/gaodepointlineinfoend?search_root_img_id=$search_root_img_id';
            </script>
        ";
        }

    }

    //赔付订单详情
    public function getpaymentlistinfo() {
        $search_order_id=I("search_order_id");
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        if(empty($search_order_id)) {
            die("非法操作");
        }
        $this->assign('search_order_id',$search_order_id);
        $where2="order_id='$search_order_id' and state='0' ";


        $model = new Model();
        $res=array();

        $getListoneRes= $model->table('baby_toys_compensation')
            ->where($where2)
            ->select();

        foreach($getListoneRes as $value){
            $id = $value['id'];
            $order_id = $value['order_id'];
            $root_img_id = $value['root_img_id'];
            $business_title = $value['business_title'];
            $business_des = $value['business_des'];
            $business_pics = $value['business_pics'];
            $business_pics_arr = explode(';',$business_pics);
            $market_price = $value['market_price'];
            $member_price = $value['member_price'];
            $res[] = array(
                'id' => $id,
                'order_id' => $order_id,
                'root_img_id' => $root_img_id,
                'business_title' => $business_title,
                'business_des' => $business_des,
                'business_pics' => $business_pics_arr,
                'market_price' => $market_price,
                'member_price' => $member_price,
            );
        }

        $this->assign('res',$res);//赋值数据集

        $this->display();
    }

    //配送管理列表
    public function getpostmanorderlist() {

        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");

        $model = new Model();

        //查询运营账号
        $getCommonUserRes= $model->table('baby_common_user')
            ->where("user_id>0")
            ->field('user_id')
            ->select();
        foreach($getCommonUserRes as $val){
            $common_user_id_arr[] = $val['user_id'];
        }

        $common_user_id_str = implode(',',$common_user_id_arr);
        $str_card_info=$this->getCardInfo();
        $where2 =" o.user_id not in ($common_user_id_str) and case when o.status in (7,11) then o.state!='2' else o.state='0' end and o.business_id not in ($str_card_info) and o.is_prize in (0,3,6) ";

        $search_status = I("search_status");
        if($search_status){
            $where2 .= " and o.status='$search_status' ";
        }
        $this->assign('search_status',$search_status);

        $serach_order_id = I("serach_order_id");
        if($serach_order_id){
            $where2 .= " and o.id='$serach_order_id' ";
        }
        $this->assign('serach_order_id',$serach_order_id);

        $serach_user_id = I("serach_user_id");
        if($serach_user_id){
            $where2 .= " and o.user_id='$serach_user_id' ";
        }
        $this->assign('serach_user_id',$serach_user_id);

        $search_compensation_status = I("search_compensation_status");
        if($search_compensation_status){
            $where2 .= " and o.compensation_status='$search_compensation_status' ";
        }
        $this->assign('search_compensation_status',$search_compensation_status);

        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
            ->where($where2)
            ->count();

        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_order as o')
            ->join(" left join baby_toys_business as b on o.business_id=b.id")
            ->field("o.id,o.post_create_time,o.admin_load_img,o.user_load_img,o.status,o.toys_payment_title,o.compensation_status,o.toys_number,b.business_title")
            ->where($where2)
            ->order("post_create_time desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $post_create_time = $value['post_create_time'];
                $order_id = $value['id'];
                $status = $value['status'];
                $toys_payment_title = $value['toys_payment_title'];
                $compensation_status = $value['compensation_status'];
                $business_title = $value['business_title'];
                if($compensation_status==0){
                    $compensation_status_name = "不需要赔付";
                }elseif($compensation_status==1){
                    $compensation_status_name = "需要生成赔付订单";
                }elseif($compensation_status==2){
                    $compensation_status_name = "已经生成赔付订单";
                }
                $user_load_img = $value['user_load_img'];
                $user_load_img_arr = array();
                if($user_load_img){
                    $user_load_img_arr = explode(';',$user_load_img);
                }
                $admin_load_img = $value['admin_load_img'];
                $admin_load_img_arr = array();
                if($admin_load_img){
                    $admin_load_img_arr = explode(';',$admin_load_img);
                }
                $toys_number = $value['toys_number'];

                if($status==1) {
                    $status_name="待支付";
                } elseif($status==2) {
                    if($toys_number) {
                        $status_name="待出库";
                    } else {
                        $status_name="准备中";
                    }
                } elseif($status==5) {
                    $status_name="送货中";
                    $lis_status=5;
                } elseif($status==6) {
                    $status_name="玩乐中";
                    $lis_status=8;
                } elseif($status==7) {
                    $status_name="待入库";
                    $lis_status=9;
                } elseif($status==8) {
                    $status_name="退款中";
                } elseif($status==9) {
                    $status_name="已退款";
                } elseif($status==10) {
                    $status_name="待取回";
                    $lis_status=7;
                } elseif($status==11) {
                    $status_name="已入库";
                    $lis_status=11;
                } elseif($status==12) {
                    $status_name="已过期";
                } elseif($status==13) {
                    $status_name="已失效";
                } elseif($status==14) {
                    $status_name="恢复玩乐";
                    $lis_status=14;
                } elseif($status==15) {
                    $status_name="卡合并";
                }elseif($status==16) {
                    $status_name="问题订单";
                }elseif($status==17) {
                    $status_name="重新配送";
                    $lis_status=2;
                }else {
                    $status_name="";
                }

//                if($lis_status>0) {
                    $list_res = $model->table('baby_toys_listing as i')
                        ->join(" left join baby_user as u on i.postman_id=u.id")
                        ->where("i.order_id ='$order_id' and i.state='0' and i.status='8' and i.postman_id>0")
                        ->field('u.user_name,i.create_time')
                        ->order("i.id desc")
                        ->find();
                    $list_res_back = $model->table('baby_toys_listing as i')
                        ->join(" left join baby_user as u on i.postman_id=u.id")
                        ->where("i.order_id ='$order_id' and i.state='0' and i.status='9' and i.postman_id>0")
                        ->field('u.user_name,i.create_time')
                        ->order("i.id desc")
                        ->find();
                $list_res_ing = $model->table('baby_toys_listing as i')
                    ->join(" left join baby_user as u on i.postman_id=u.id")
                    ->join(" left join baby_toys_order as o on i.order_id=o.id ")
                    ->where("i.order_id ='$order_id' and i.state='0' and i.status='5' and i.postman_id>0 and o.status=5 ")
                    ->field('u.user_name')
                    ->order("i.id desc")
                    ->find();
                if($list_res){
                    $postman_name = "送：".$list_res['user_name']."<br>".$list_res['create_time'];
                }
                if($list_res_back){
                    $postman_name .= "<br><br>取：".$list_res_back['user_name']."<br>".$list_res_back['create_time'];
                }
                if($list_res_ing){
                    $postman_name = $list_res_ing['user_name'];
                }

//                }else{
//                    $postman_name = "";
//                }


                $res[]=array(
                    'order_id' => $order_id,
                    'post_create_time' => $post_create_time,
                    'status' => $status,
                    'toys_payment_title' => $toys_payment_title,
                    'compensation_status' => $compensation_status,
                    'compensation_status_name' => $compensation_status_name,
                    'user_load_img' => $user_load_img_arr,
                    'admin_load_img' => $admin_load_img_arr,
                    'status_name' => $status_name,
                    'postman_name' => $postman_name,
                    'business_title' => $business_title
                );
            }
        }

        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //抽奖列表
    public function getshareprize() {
        $search_user_id=I("search_user_id");
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" state='0' ";
        if($search_user_id>0) {
            $where2.=" and user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_share_prize')
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_share_prize')
            ->where($where2)
            ->order("id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $user_id=$value['user_id'];
                $id=$value['id'];
                $card_id = $value['card_id'];
                $order_id = $value['order_id'];
                $prize = $value['prize'];

                if($prize==1){
                    $prize_name = "延期1天";
                }elseif($prize==2){
                    $prize_name = "延期2天";
                }elseif($prize==3){
                    $prize_name = "延期3天";
                }elseif($prize==4){
                    $prize_name = "延期4天";
                }elseif($prize==5){
                    $prize_name = "延期5天";
                }elseif($prize==6){
                    $prize_name = "礼品一份";
                }elseif($prize==7){
                    $prize_name = "配送一次";
                }
                $res[]=array(
                    'id'=>$id,
                    'user_id'=>$user_id,
                    'create_time'=>$create_time,
                    'prize_name'=>$prize_name,
                    'card_id'=>$card_id,
                    'order_id'=>$order_id
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //抽奖列表三天
    public function getshareprizethree() {
        $search_user_id=I("search_user_id");
        $search_mobile = I("search_mobile");
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" state='0' ";
        if($search_user_id>0) {
            $where2.=" and user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }
        if($search_mobile>0) {
            $where2.=" and mobile='$search_mobile' ";
            $this->assign('search_mobile',$search_mobile);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_toys_share_prize_three')
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_share_prize_three')
            ->where($where2)
            ->order("id desc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $user_id=$value['user_id'];
                $id=$value['id'];
                $mobile = $value['mobile'];
                $prize = $value['prize'];
                $prize_end = $value['prize_end'];
                $info = $value['info'];

                if($prize==1){
                    $prize_name = "延期1天";
                }elseif($prize==2){
                    $prize_name = "延期3天";
                }elseif($prize==3){
                    $prize_name = "延期5天";
                }elseif($prize==4){
                    $prize_name = "延期7天";
                }elseif($prize==5){
                    $prize_name = "延期10天";
                }elseif($prize==6){
                    $prize_name = "礼品1份";
                }elseif($prize==7){
                    $prize_name = "配送1次";
                }
                if($prize_end==1){
                    $prize_end_name = "延期1天";
                }elseif($prize_end==2){
                    $prize_end_name = "延期3天";
                }elseif($prize_end==3){
                    $prize_end_name = "延期5天";
                }elseif($prize_end==4){
                    $prize_end_name = "延期7天";
                }elseif($prize_end==5){
                    $prize_end_name = "延期10天";
                }elseif($prize_end==6){
                    $prize_end_name = "礼品1份";
                }elseif($prize_end==7){
                    $prize_end_name = "配送1次";
                }
                $res[]=array(
                    'id'=>$id,
                    'user_id'=>$user_id,
                    'create_time'=>$create_time,
                    'prize_name'=>$prize_name,
                    'prize_end_name'=>$prize_end_name,
                    'info'=>$info,
                    'mobile'=>$mobile
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //小区列表
    public function parklist() {
        $search_park_name=I("search_park_name");
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" state='0' ";
        if($search_park_name) {
            $where2.=" and nick_name like '%$search_park_name%' ";
            $this->assign('search_park_name',$search_park_name);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_park_list')
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_park_list')
            ->where($where2)
            ->order("id asc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $start_time = "2018-09-14 10:00:00";

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $id=$value['id'];
                $region_name = $value['region_name'];
                $name = $value['name'];
                $nick_name = $value['nick_name'];

                $join_count = $model->table('baby_user_park_list')
                    ->where("park_id='$id' and state='0' and status=0")
                    ->count();

                $buy_res = $model->table('baby_user_park_list as u')
                    ->join('baby_toys_card as c on u.user_id=c.user_id')
                    ->where("u.park_id='$id' and u.state='0' and u.status=0 and c.card_name=4 and c.create_time>'$start_time' and c.state='0' ")
                    ->field('u.user_id')
                    ->group('u.user_id')
                    ->select();
                $buy_count = count($buy_res);

                $user_count = $model->table('baby_user_address')
                    ->where("park_id='$id'")
                    ->count();

                $res[]=array(
                    'id'=>$id,
                    'region_name'=>$region_name,
                    'name'=>$name,
                    'nick_name'=>$nick_name,
                    'join_count'=>$join_count,
                    'user_count'=>$user_count,
                    'buy_count'=>$buy_count
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //小区详情
    public function parklistinfo() {
        $search_user_id=I("search_user_id");
        $search_park_id=I("search_park_id");
        $this->assign('search_park_id',$search_park_id);
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" state='0' and status=0 and park_id='$search_park_id' ";
        if($search_user_id) {
            $where2.=" and user_id='$search_user_id' ";
            $this->assign('search_user_id',$search_user_id);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_user_park_list')
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_user_park_list')
            ->where($where2)
            ->order("id asc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $park_name = $value['park_name'];
                $user_id = $value['user_id'];
                $create_time = $value['create_time'];

                $now_time = date('Y-m-d H:i:s');
                $where_conditon = " user_id='$user_id' and state='0' and card_name in (2,3,4) and ( case when final_end_time>end_time then final_end_time else end_time end > '$now_time'  or status=4  OR start_time='0000-00-00 00:00:00' ) ";
                $is_have_res=M("toys_card")->field('id')->where($where_conditon)->find();

                if($is_have_res){
                    $user_info = "<font color='red'>会员</font>";
                }else{
                    $user_info = "非会员";
                }

                $start_time = "2018-09-14 10:00:00";
                $is_have_activity=M("toys_card")->field('id')->where("create_time>'$start_time' and state='0' and card_name in (2,4) and user_id='$user_id' ")->find();

                if($is_have_activity){
                    $user_info_s = "<font color='red'>已购卡</font>";
                }else{
                    $user_info_s = "未购卡";
                }

                $res[]=array(
                    'park_name'=>$park_name,
                    'user_id'=>$user_id,
                    'create_time'=>$create_time,
                    'user_info'=>$user_info,
                    'user_info_s'=>$user_info_s
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //小区建议开通
    public function parklistsuggest() {
        $search_park_name=I("search_park_name");
        $search_status = I("search_status")?I("search_status"):1;
        $this->checkSession();
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2=" state='0' and status=$search_status ";
        if($search_park_name) {
            $where2.=" and park_name like '%$search_park_name%' ";
            $this->assign('search_park_name',$search_park_name);
        }

        $model = new Model();
        //满足条件的总记录数
        $count  = $model->table('baby_user_park_list')
            ->where($where2)
            ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_user_park_list')
            ->where($where2)
            ->order("id asc ")
            ->limit($page->firstRow,$page->listRows)
            ->select();

        $start_time = "2018-09-14 10:00:00";

        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $id=$value['id'];
                $park_name = $value['park_name'];
                $create_time = $value['create_time'];
                $post_create_time = $value['post_create_time'];
                $status = $value['status'];
                if($status==1){
                    $status_name = '未处理';
                }elseif($status==2){
                    $status = '已处理';
                }


                $res[]=array(
                    'id'=>$id,
                    'park_name'=>$park_name,
                    'create_time'=>$create_time,
                    'post_create_time'=>$post_create_time,
                    'status'=>$status,
                    'status_name'=>$status_name
                );
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //停卡操作页面
    public function pubstopcard() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_card")
                        ->where("id=$id and state='0' and status='4' ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }

    //查询用户租赁记录
//    public function customerold() {
//        $this->display();
//    }

    //客服交接列表
    public function getcusplolist() {
        $search_user_id=I("search_user_id");
        $search_problem=I("search_problem");
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="state='0' ";
        /*if($search_user_id>0) {
            $where2.=" and problem like '%$search_user_id%' ";
            $this->assign('search_user_id',$search_user_id);
        }*/
        if($search_problem) {
        	$where2.=" and problem like '%$search_problem%' ";
            $this->assign('search_problem',$search_problem);
        }
        $model = new Model();
        //满足条件的总记录数
        $user_count  = $model->table('baby_toys_customer_problem')
                        ->where($where2)
                        ->select();
        $count=count($user_count);
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getUserRes= $model->table('baby_toys_customer_problem')
                    ->where($where2)
                    ->order("id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getUserRes) {
            foreach ($getUserRes as $key => $value) {
                $create_time=$value['create_time'];
                $problem=$value['problem'];
                $handle_record=$value['handle_record'];
                $public_name=$value['public_name'];
                $id=$value['id'];
                $res[]=array(
                    'problem'=>$problem,
                    'handle_record'=>$handle_record,
                    'id'=>$id,
                    'create_time'=>$create_time,
                    'public_name'=>$public_name
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除客服交接
    public function operator_cusplostate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_customer_problem');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getcusplolist.html");
    }
    //客服交接操作页面
    public function pubcusplo() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_customer_problem")
                        ->where("id=$id ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }
    //采购列表
    public function getbuspurlist() {
        $search_id=I("search_id");
        $search_business_id=I("search_business_id");
        $start_time=I('start_time');
        $end_time=I('end_time');
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="a.state='0' ";
        if($search_id>0) {
            $where2.=" and a.id='$search_id' ";
            $this->assign('search_business_id',$search_business_id);
        }
        if($search_business_id>0) {
            $where2.=" and a.business_id='$search_business_id' ";
            $this->assign('search_business_id',$search_business_id);
        }
        if($start_time) {
            $where2.=" and a.purchase_time>'$start_time' ";
            $this->assign("start_time",$start_time);
        }
        if($end_time) {
            $where2.=" and a.purchase_time<'$end_time' ";
            $this->assign("end_time",$end_time);
        }
        $model = new Model();
        //满足条件的总记录数
        $count= $model->table('baby_toys_business_purchasing as a')
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_business_purchasing as a')
                    ->where($where2)
                    ->order("a.id desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $id=$value['id'];
                $business_id=$value['business_id'];
                $price=$value['price']?$value['price']:0;
                $total_number=$value['total_number']?$value['total_number']:0;
                $mobile=$value['mobile'];
                $source=$value['source'];
                $purchase_time=$value['purchase_time'];
                $post_url=$value['post_url'];
                $toys_info="";
                if($business_id>0) {
                    
                    $busRes=M('toys_business')
                        ->field('business_title')
                        ->where("state='0' and id=$business_id  ")
                        ->find();
                    $toys_title=$busRes['business_title'];
                    $toys_info=$business_id."<br/>".$toys_title;
                }
                $res[]=array(
                    'id'=>$id,
                    'business_id'=>$business_id,
                    'toys_info'=>$toys_info,
                    'price'=>$price,
                    'total_number'=>$total_number,
                    'mobile'=>$mobile,
                    'source'=>$source,
                    'purchase_time'=>$purchase_time,
                    'post_url'=>$post_url
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除采购
    public function operator_buspurstate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_business_purchasing');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getbuspurlist.html");
    }

    //采购操作页面
    public function pubbuspur() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_business_purchasing")
                        ->where("id=$id ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }
    //奖品订单列表
    public function getorderprizelist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="o.is_prize='1' ";
        $model = new Model();
        $order_status=trim(I("order_status"));
        if($order_status) {
            $where2.=" and o.status=$order_status ";
            if($order_status!=11) {
                $where2.=" and o.state='0'  ";
            }
            $this->assign('order_status',$order_status);
        } else {
            $where2.=" and o.state='0' ";
        }
        $order_id=trim(I("order_id"));
        if($order_id) {
            $where2.=" and o.id=$order_id ";
            $this->assign('order_id',$order_id);
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
        //满足条件的总记录数
        $count  = $model->table('baby_toys_order as o')
                        ->join(" left join baby_toys_prize as b on o.business_id=b.id")
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $res=array();
        $listres = $model->table('baby_toys_order as o')
                    ->join(" left join baby_toys_prize as b on o.business_id=b.id")
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->where($where2)
                    ->field('o.id,o.order_num,o.combined_order_id,o.post_create_time,o.create_time,o.user_id,u.nick_name,u.mobile,o.business_id,b.prize_title2 as business_title,o.status,b.img')
                    ->order('o.post_create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        foreach ($listres as $key => $value) {
            $order_id=$value['id'];
            $order_num=$value['order_num'];
            $combined_order_id=$value['combined_order_id'];
            $tmp_post_create_time=$value['post_create_time'];
            $create_time=$value['create_time'];
            $tmp_user_id=$value['user_id'];
            $nick_name=$value['nick_name'];
            $mobile=$value['mobile'];
            $tmp_business_id=$value['business_id'];
            $business_title=$value['business_title'];
            $tmp_status=$value['status'];
            $tmp_img="http://api.meimei.yihaoss.top/".$value['img'];
            $user_info="";
            $user_info=$tmp_user_id;
            if($nick_name) {
                $user_info.="<br/>".$nick_name;
            }
            if($mobile) {
                $user_info.="<br/>".$mobile;
            }
            if($tmp_status==2) {
                $status_name="准备中";
            } elseif($tmp_status==5) {
                $status_name="送货中";
            } elseif($tmp_status==11) {
                $status_name="已送达";
            } else {
                $status_name="";
            }
            $order_info=$order_id."<br/>".$order_num."<br/>".$combined_order_id;
            $prize_info=$tmp_business_id."<br/>".$business_title;
            $show_time=$tmp_post_create_time."<br/>".$create_time;
            $res[]=array(
                'id'=>$order_id,
                'user_info'=>$user_info,
                'status_name'=>$status_name,
                'order_info'=>$order_info,
                'prize_info'=>$prize_info,
                'show_time'=>$show_time,
                'img'=>$tmp_img
            );
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    //删除奖品订单
    public function operator_prizeorderstate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_order');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/getorderprizelist.html");
    }
    //添加奖品订单页面
    public function pubtoysprize(){
        $top_label=M("toys_prize")
                ->field('id,prize_title2 as prize_title,img')
                ->where("state='0' ")
                ->select();
        $this->assign('top_label',$top_label);
        $this->display();
    }
    //热门搜索列表
    public function gethotsearchlist() {
        $this->checkSession(); 
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        $where2="a.state='0' ";
        
        $model = new Model();
        //满足条件的总记录数
        $count= $model->table('baby_toys_business_hot_search as a')
                        ->where($where2)
                        ->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $getListRes= $model->table('baby_toys_business_hot_search as a')
                    ->where($where2)
                    ->order("a.rank desc,a.post_create_time desc ")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $id=$value['id'];
                $search_title=$value['search_title'];
                $rank=$value['rank'];
                $res[]=array(
                    'rank'=>$rank,
                    'search_title'=>$search_title,
                    'id'=>$id
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }

    //删除热门搜索
    public function operator_hotsearchstate(){        
        $imgid = $_GET['imgid'];
        $album = M('toys_business_hot_search');
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Toys/gethotsearchlist.html");
    }

    //热门搜索操作页面
    public function pubhotsearch() { 
        $id=I("id");
        if($id>0) {
            $getUserRes= M("toys_business_hot_search")
                        ->where("id=$id ")
                        ->find();
        }
        $this->assign('res',$getUserRes);
        $this->display();
    }
    //玩具赔付页面
    public function toyspayment(){

        $order_id = $_REQUEST['order_id'];
        if(empty($order_id)){
            die("请选择要操作的订单");
        }
        $getUserRes= M("toys_order")
            ->where("id=$order_id ")
            ->find();
//        $user_id = $getUserRes['user_id'];
//        $business_id = $getUserRes['business_id'];
//        $toys_number = $getUserRes['toys_number'];

        $this->assign('getUserRes',$getUserRes);

        $user_load_img = $getUserRes['user_load_img'];

        $user_load_img_arr = array();
        if($user_load_img){
            $user_load_img_arr = explode(';',$user_load_img);
        }
        $this->assign('user_load_img_arr',$user_load_img_arr);

        $admin_load_img = $getUserRes['admin_load_img'];
        $admin_load_img_arr = array();
        if($admin_load_img){
            $admin_load_img_arr = explode(';',$admin_load_img);
        }

        $this->assign('admin_load_img_arr',$admin_load_img_arr);

        $this->display();
    }

    //玩具赔付页面
    public function toyssendmessage(){
        $this->display();
    }

}
?>
