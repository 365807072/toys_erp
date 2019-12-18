<?php
class CustomerAction extends Action{
    //在类初始化方法中，引入相关类库    
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
    //获取客服列表二级
    public function getCustomerProsNextList() {
        $search_id=I("id");
        $model = new Model();
        if($search_id>0) {
            $where_condition=" p.state='0' and (p.root_img_id=$search_id or p.id=$search_id ) ";
            $getListRes= $model->table('baby_toys_customer_problems as p')
                        ->field("p.id,p.line_name_id,p.house_name_id,p.user_id,p.create_time,p.problem,p.status,p.problem_level,p.order_id,p.handle_record")
                        ->where($where_condition)
                        ->order("p.create_time desc")
                        ->select();
        }
        $res=array();
        if($getListRes) {
            foreach ($getListRes as $key => $value) {
                $create_time=$value['create_time'];
                $show_id=$value['id'];
                $line_name_id=$value['line_name_id'];
                $house_name_id=$value['house_name_id'];
                $user_id=$value['user_id'];
                $problem=$value['problem'];
                $status=$value['status'];//解决状态（2未解决、1已解决）
                $problem_level=$value['problem_level'];//问题等级（2一般、1紧急）
                $order_id=$value['order_id'];
                if($order_id==0) {
                    $order_id="";
                }
                $handle_record=$value['handle_record'];
                if($line_name_id==13) {
                    $line_name="荆哲";
                } elseif($line_name_id==12) {
                    $line_name="马思敏";
                } elseif($line_name_id==11) {
                    $line_name="杜志改";
                } elseif($line_name_id==10) {
                    $line_name="李永利";
                } elseif($line_name_id==9) {
                    $line_name="李玉清";
                } elseif($line_name_id==8) {
                    $line_name="吴美艳";
                } elseif($line_name_id==7) {
                    $line_name="樊颖";
                } elseif($line_name_id==6) {
                    $line_name="张同艳";
                } elseif($line_name_id==5) {
                    $line_name="张桐";
                } elseif($line_name_id==4) {
                    $line_name="李嘉欣";
                } elseif($line_name_id==3) {
                    $line_name="谭硕";
                } elseif($line_name_id==2) {
                    $line_name="王贫";
                } elseif($line_name_id==1) {
                    $line_name="韦杰";
                } else {
                    $line_name="";
                }
                if($house_name_id==1005) {
                    $house_name="丁东";
                } elseif($house_name_id==1004) {
                    $house_name="刘瑞";
                } elseif($house_name_id==1003) {
                    $house_name="钟文东";
                } elseif($house_name_id==1002) {
                    $house_name="张国华";
                } elseif($house_name_id==1001) {
                    $house_name="李静";
                } else {
                    $house_name="";
                }
                $res []=array(
                    'id'=>$show_id,
                    'line_name_id'=>$line_name_id,
                    'line_name'=>$line_name,
                    'house_name_id'=>$house_name_id,
                    'house_name'=>$house_name,
                    'create_time'=>$create_time,
                    'user_id'=>$user_id,
                    'problem' => $problem,
                    'status'=>$status,
                    'problem_level'=>$problem_level,
                    'order_id'=>$order_id,
                    'handle_record'=>$handle_record
                );

            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->display();
    }
    //删除问题
    public function operator_cuspronextstate(){        
        $imgid = $_GET['img_id'];
        $getListRes= M('toys_customer_problems')
                        ->field("root_img_id")
                        ->where('id=$imgid')
                        ->find();
        $get_root_img_id=$getListRes['root_img_id'];
        if($get_root_img_id>0) {
            $show_id=$get_root_img_id;
        } else {
            $show_id=$imgid;
        }
        $where['id'] = array('in',$imgid);
        $data['state'] = '1' ;
        $result = M('toys_customer_problems')->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Customer/getCustomerProsNextList?id=$show_id");
    }

}
?>
