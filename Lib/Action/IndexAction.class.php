<?php
class IndexAction extends Action {	
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
	/**
	 * 退出登录
	 */
	public function logout(){
		session_unset();//清空session
		$array = array('result' => true,message => '退出成功!');
		echo json_encode($array);
	}
	
	
    public function index(){
	    $this->checkSession();
    	$this->display();
    }
	public function index_one(){
	    $this->checkSession();
    	$this->display('index');
    }
    public function top(){
    	$this->checkSession();
    	$this->display();
    }
   
    public function  left(){
    		$this->checkSession();
    		$user = M('user');
    		
    		//用户数量
    		$userWhere['state'] = array('in','0,3');
    		$count['user'] = $user->where($userWhere)->count();
    		
    		//宝贝数量
    		$baby['is_baby'] ='1';
    		$baby['state'] = $userWhere['state'];
    		$count['baby'] = $user->where($baby)->count();
    		
    		//昨日新增用户
    		$yesterday0 = date('Y-m-d 00:00:00',time()-24*60*60);
    		$yesterday1 = date('Y-m-d 23:59:59',time()-24*60*60);
    		$where['regist_time'] = array('between',"$yesterday0,$yesterday1");
    		$count['newUser'] = $user->where($where)->count();

    		//昨日新增宝贝
    		$where['is_baby'] ='1';
    		$count['newBaby'] = $user->where($where)->count();
    		
    		$this->assign('count',$count);
    		$this->display();
    }


	public function indexpage(){
		$this->display('indexpage');
	}
    
    public function  mainframe(){
	    	$this->checkSession();
	    	$this->display();
    }
  
    /****************************************************************************************/

    /**
     * 选择用户的排序方式
     * $value 值如下：
     * 0：一审 注册时间
     * 1：一审 修改时间
     * 2：二审注册时间
     * 3：二审修改时间
     */
    public function chooseOrder() {
    	
    	$value = $_GET['orderValue'];
    	

		switch ($value) {
			
			case 0:
				$this->checkusername($value,'regist_time');
				break;
			case 1:
				$this->checkusername($value,'last_modify_time');
				break;
			case 2:
				$this->checkagain($value,'regist_time');
				break;
			case 3:
				$this->checkagain($value,'last_modify_time');
				break;
			default:
				;
				break;
		}
    }
    
    /**
     * 查看宝贝相册或者普通用户相册
     * 0:宝贝相册
     * 1:普通用户的相册
     */
    public function choosePicture() {
    	$value = $_GET['orderValue'];
    	$this->checkpicture($value);
    }
    /**
     * 根据输入的用户名和性别等信息搜索用户 ,默认按注册时间排序
     */
    public function searchUser(){
    		import("ORG.Util.Page");
    	 
    		$phone = $_GET['findphone'];
    		$name = $_GET['findname'];
    		$id= $_GET['findid'];
    		$sex = $_GET['findsex'];
    		
    		$where['state'] ='0';
    		
    		//复合两种查询条件,一种是查询手机号，另一种查询用户名等其他信息，
    		if ($phone) {
    			$map1['mobile'] = $phone;
    		}
    		if ($name) {
    			$map2['user_name'] = array('like',"%$name%");
    		}
    		if ($id) { //年龄
    			$map2['id'] = $id;
    		}
    		if ($sex!=0) {//数据表中0（保密）1（男），2（女）
    			$map2['gender'] = $sex;
    		}
    		if (!empty($map2)) {
    			$map1['_complex'] = $map2;
    			$map1['_logic'] = 'OR';
    		}

    		//复合查询
    		if (!empty($map1	)) {
    			$where['_complex'] = $map1;
    		}
    		$user = M('user');
    		$count = $user->where($where) ->count();
    		$page = new Page($count);
    		$show = $page->show();
    		
    		$tempData = $user
    					 ->where($where)
    					 ->order('regist_time desc')
    					 ->limit($page->firstRow.','.$page->listRows)
    					 ->select();

    		$data = $tempData;
    		for($i = 0; $i < count ( $tempData ); $i ++) {
				$singleUser = $tempData [$i];
				if (empty ( $singleUser ['avatar'] )) {
					$data [$i] ['avatar'] = C ( 'WEB_PATH' ) . "/" . DEFAULT_AVATAR;
				} else {
					$data [$i] ['avatar'] = C ( 'WEB_PATH' ) . "/" . $singleUser ['avatar'];
				}
			}
    		$this->assign('page',$show);
    		$this->assign("list",$data);
    		$this->assign('type',0);
    		$this->display('checkusername');
    }
    /**
     * 查询是否有相册
     */
    public function searchUserAlbum(){
    		import("ORG.Util.Page");
    	 
	    	$phone = $_GET['findphone'];
	    	$name = $_GET['findname'];
	    	$id = $_GET['findid'];
	    	$sex = $_GET['findsex'];
	    	$isbaby = $_GET['isbaby'];
    		
	    	$where['state'] = array('in','0,3');

	    	//复合两种查询条件,一种是查询手机号，另一种查询用户名等其他信息，
	    	if ($phone) {
	    		$map1['mobile'] = $phone;
	    	}
	    	if ($isbaby != 0) {
	    		$map1['is_baby'] =$isbaby;   //是否是宝贝
	    	}
	    	if ($name) {
	    		$map1['user_name'] = array('like',"%$name%");
	    	}
	    	if ($id) { //ID
	    		$map1['id'] = $id;
	    	}
	    	if ($sex!=0) {//数据表中0（保密）1（男），2（女）
	    		$map1['gender'] = $sex;
	    	}
	    	
	    	//复合查询
	    	if (!empty($map1)) {
	    		$where['_complex'] = $map1;
	    	}
	    	
			//先查相册，根据是否有相册再查用户
		    $user_album = M('album_img');
		    $where2['state'] ='0';
		    $tempData =  $user_album ->field('id,user_id,img,state')
		    					      ->where($where2) //相片正常
		   						 	  ->order('create_time')
		  						  	  ->select();
			$uidArray = array();
		    foreach ($tempData as $key) {
		    	array_push($uidArray, $key['user_id']);
		    }
		    //替换图片的链接
		    $data2 = $tempData;
		    for ($i = 0; $i < count($tempData); $i++) {
		    		$data2[$i]['img'] = C('WEB_PATH').'/'.$tempData[$i]['img'];
		    }
		    
		    
		    //后查用户，因为有的用户没有相册------------根据输入的搜索条件，在有相册的用户（宝贝和用户）中进行搜索，过滤掉没有相册的用户
		    $where['id'] = array('in',$uidArray);
		    $user = M('user');
		    $count = $user->where($where) ->count();
		    $page = new Page($count);
		    $show = $page->show();
		    
		    $data =   $user	->field('id,state,user_name,mobile,regist_time')
		    				->where($where)
		   				    ->order('regist_time desc')
		    				->limit($page->firstRow.','.$page->listRows)
		    				->select();
		    
	    	$this->assign('page',$show);
	    	$this->assign("list",$data);
	    	$this->assign('list2',$data2);
	    	$this->display('checkpicture');
    }

    /**
     * 二次审核的搜索，按修改时间排序
     */
    public function searchUserAgain() {
	    	import("ORG.Util.Page");
	    	
	    	$phone = $_GET['findphone'];
	    	$name = $_GET['findname'];
	    	$id = $_GET['findid'];
	    	$sex = $_GET['findsex'];
	    	
	    	$where['state'] = '3';
	    	//复合两种查询条件,一种是查询手机号，另一种查询用户名等其他信息，
	    	if ($phone) {
	    		$map1['mobile'] = $phone;
	    	}
	    	
	    	if ($name) {
	    		$map2['user_name'] = array('like',"%$name%");
	    	}
	    	if ($id) { //ID
	    		$map2['id'] = $id;
	    	}
	    	if ($sex!=0) {//数据表中0（保密）1（男），2（女）
	    		$map2['gender'] = $sex;
	    	}
	    	if (!empty($map2)) {
	    		$map1['_complex'] = $map2;
	    		$map1['_logic'] = 'OR';
	    	}
	    	
	    	//复合查询
	    	if (!empty($map1	)) {
	    		$where['_complex'] = $map1;
	    	}
	    	$model = new Model();
	    	$user = M('user');

			$count = $user ->where($where) ->count();
	    	$page = new  Page($count);
	    	$show = $page->show();
	    	
	    	$tempData = $user	->where($where)
	    						->order('last_modify_time desc')    //
	    						->limit($page->firstRow.','.$page->listRows)
	   			 				->select();
// 	    	echo $data;
			$uidArray = array();
			foreach ($tempData as $value) {
				array_push($uidArray, $value['id']);
			}
// 			var_dump($uidArray);exit();
	    	$freeze_user = M('freeze_user');
	    	$where2['user_id'] = array('in',$uidArray);
	    	$where2['is_pass'] ='0';
	    	$data2 = $freeze_user
	    						  ->where($where2)
	    						  ->select();

	    	$data = $tempData;
	    	for ($i = 0; $i < count($tempData); $i++) {
	    			$singleUID  = $tempData[$i];
	    			$uid = $singleUID['id'];
	    			$userRecord = "";
	    			for ($j =0 ;$j <count($data2) ;$j++){
	    				$singleRow = $data2[$j];
	    				if ($singleRow['user_id'] == $uid) {
	    					$remark = $singleRow['remark'];
	    					$userRecord = $userRecord.$remark."、";
	    				}
	    			}
					//控制变量不可更改，拷贝一个数组
	    			$data[$i]['currentState'] = $userRecord;
	    			if (empty($data[$i]['currentState']) ) {
	    				$data[$i]['currentState'] = "无未通过项";
	    			}
	    			if ( empty($singleUID['avatar'])){
	                    $data[$i]['avatar'] = C('WEB_PATH')."/".DEFAULT_AVATAR;
        	        }else{
				   		$data[$i]['avatar'] = C('WEB_PATH').'/'.$singleUID['avatar'];
	    			}
			}

	    	$this->assign("list",$data);
	    	$this->assign('page',$show);
	    	$this->assign('type',3);
	    	$this->display('checkagain');
	    	 
    }
/**********************************************************************************************/
    /**
     *查询用户名,state =0  ,默认按用户的注册时间排序($type = 0 ;)
     */
    public function  checkusername($type = 0 , $time='regist_time'){
    	$this->checkSession();
    		
    	import("ORG.Util.Page");
    	$user  = M('user');
    	$where['state'] ='0';
     	$count = $user->where($where)->count(); //
     	$page = new  Page($count);
     	$show = $page->show();
     	
     	$tempData = $user 	->where($where)
     				 		->order("$time desc")    //按注册时间降序
     				 		->limit($page->firstRow.','.$page->listRows)
     				 		->select();
     	$data = $tempData;
     	for ($i = 0; $i < count($tempData); $i++) {
     		$singleUser = $tempData[$i];
			if ( empty($singleUser['avatar'])){
                $data[$i]['avatar'] = C('WEB_PATH')."/".DEFAULT_AVATAR;
	        }else{
     		    $data[$i]['avatar'] = C('WEB_PATH')."/".$singleUser['avatar'];
			}
     	}
     	$this->assign("list",$data);
     	$this->assign('page',$show);
     	$this->assign('type',$type); //0注册时间，1修改时间
    	$this->display('checkusername');
    }

    /**
     * 检查相册相片(宝贝相册，普通用户相册)
     */
    public function checkpicture(){
    	
    	$this->checkSession();
    		
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('album_img');
    	$where2['state'] ='0';//图片状态正常
		
    	$where2['album_id'] =0;
		$where2['img_cate'] = array('eq','0');
		$where2['root_album_id'] >0;
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,state')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
		
		
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,img_description,type,is_theme,operation,img_cate,show_cate,admire_count,review_count')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_cate = $data['img_cate'];
				$img_description = $data['img_description'];
				$type = $data['type'];
				$show_cate = $data['show_cate'];
				$Imgs=explode(';',$tmpImg);
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				//计算赞数和评论数
				$admireCount =$data['admire_count'];
				$reviewCount=$data['review_count'];
				//$admireCount = M('admire')->where(" img_id={$img_id} and is_cancel='0' ")->count();
				//$reviewCount = M('review')->where(" img_id={$img_id} and is_del='0' ")->count();
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,	
				'img_cate'=>$img_cate,
				'img_description'=>$img_description,
				'type'=>$type,
				'admirecount'=>$admireCount,
				'reviewcount'=>$reviewCount,
				'show_cate'=>$show_cate
			);
		}
		$show_cate = M('show_cate');
		$show_cates=array();	
		$show_datas = $show_cate ->field('id,cate_name')
					
					  ->order('id asc')
					  ->select();
					  
		    foreach ($show_datas as $key => $data){
		    	$id = $data['id'];
		    	$cate_name = $data['cate_name'];
				$show_cates[]=array(
				'id' =>$id,
				'cate_name' =>$cate_name
			);
		}
		$this->assign("show_cates",$show_cates);
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('checkpicture');
    }

	 public function sharepicture(){
    	
    	$this->checkSession();
    		
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('album_img');
    	$where2['state'] ='0';//图片状态正常
		
    	$where2['album_id'] =0;
		$where2['img_cate'] = array('neq','0');
		$where2['root_album_id'] >0;
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,state')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
		
		
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,img_description,type,is_theme,operation,img_cate,show_cate')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_cate = $data['img_cate'];
				$img_description = $data['img_description'];
				$type = $data['type'];
				$show_cate = $data['show_cate'];
				$Imgs=explode(';',$tmpImg);
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				//计算赞数和评论数
				$admireCount = M('admire')->where(" img_id={$img_id} and is_cancel='0' ")->count();
				$reviewCount = M('review')->where(" img_id={$img_id} and is_del='0' ")->count();
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,	
				'img_cate'=>$img_cate,
				'img_description'=>$img_description,
				'type'=>$type,
				'admirecount'=>$admireCount,
				'reviewcount'=>$reviewCount,
				'show_cate'=>$show_cate
			);
		}
		$show_cate = M('show_cate');
		$show_cates=array();	
		$show_datas = $show_cate ->field('id,cate_name')
					
					  ->order('id asc')
					  ->select();
					  
		    foreach ($show_datas as $key => $data){
		    	$id = $data['id'];
		    	$cate_name = $data['cate_name'];
				$show_cates[]=array(
				'id' =>$id,
				'cate_name' =>$cate_name
			);
		}
		$this->assign("show_cates",$show_cates);
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('sharepicture');
    }

	
	 public function specailshailist(){
    	
    	$this->checkSession();
    		
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('album_img');
    	$where2['show_state'] ='0';//图片状态正常
    	$where2['album_id'] =0;
		$where2['root_album_id'] >0;
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,show_state')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
		
		
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,img_description,type,is_theme,operation,img_cate,show_cate,admire_count,review_count')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_cate = $data['img_cate'];
				$img_description = $data['img_description'];
				$type = $data['type'];
				$show_cate = $data['show_cate'];
				$Imgs=explode(';',$tmpImg);
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				//计算赞数和评论数
				$admireCount = intval($data['admire_count']);
				$reviewCount = intval($data['review_count']);

				//$admireCount = M('admire')->where(" img_id={$img_id} and is_cancel='0' ")->count();
				//$reviewCount = M('review')->where(" img_id={$img_id} and is_del='0' ")->count();
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,	
				'img_cate'=>$img_cate,
				'img_description'=>$img_description,
				'type'=>$type,
				'admirecount'=>$admireCount,
				'reviewcount'=>$reviewCount,
				'show_cate'=>$show_cate
			);
		}
		$show_cate = M('show_cate');
		$show_cates=array();	
		$show_datas = $show_cate ->field('id,cate_name')
					
					  ->order('id asc')
					  ->select();
					  
		    foreach ($show_datas as $key => $data){
		    	$id = $data['id'];
		    	$cate_name = $data['cate_name'];
				$show_cates[]=array(
				'id' =>$id,
				'cate_name' =>$cate_name
			);
		}
		$this->assign("show_cates",$show_cates);
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('specailshailist');
    }


    public function searchPassPic(){
    
    	$this->checkSession();
    	$userid = $_POST['userid'];	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('album_img');
    	$where2['state'] ='0';//图片状态正常
    	$where2['album_id'] =0;
		$where2['img'] = array('like','static%');
    	$where2['user_id'] =$userid;
    	$data2 =  $user_album ->field('id, user_id,img,img_description,type,is_theme,operation,img_cate,show_cate')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		
		
	
		$res=array();	
		$datas = $user_album ->field('id, user_id,img,img_description,type,is_theme,operation,img_cate,show_cate')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_cate = $data['img_cate'];
				$img_description = $data['img_description'];
				$type = $data['type'];
				$show_cate = $data['show_cate'];
				$Imgs=explode(';',$tmpImg);
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,	
				'img_cate'=>$img_cate,
				'img_description'=>$img_description,
				'type'=>$type,
				'show_cate'=>$show_cate
			);
		}
		$show_cate = M('show_cate');
		$show_cates=array();	
		$show_datas = $show_cate ->field('id,cate_name')
					
					  ->order('id asc')
					  ->select();
					  
		    foreach ($show_datas as $key => $data){
		    	$id = $data['id'];
		    	$cate_name = $data['cate_name'];
				$show_cates[]=array(
				'id' =>$id,
				'cate_name' =>$cate_name
			);
		}
		$this->assign("show_cates",$show_cates);
		//print_r($res);exit;
    	$this->assign("res",$res);
    
    	$this->display('searchPassPic');
    }
	
	
	 public function searchSpecialPic(){
    
    	$this->checkSession();
    	$cate_id = $_POST['oper_selected'];	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('album_img');
    	$where2['state'] ='0';//图片状态正常
		$where2['show_state'] ='0';//图片状态正常
    	$where2['show_cate'] =$cate_id;
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,img_description,type,is_theme,operation,img_cate,show_cate')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		
		
	
		$res=array();	
		$datas = $user_album ->field('id, user_id,img,img_description,type,is_theme,operation,img_cate,show_cate')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_cate = $data['img_cate'];
				$img_description = $data['img_description'];
				$type = $data['type'];
				$show_cate = $data['show_cate'];
				$Imgs=explode(';',$tmpImg);
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,	
				'img_cate'=>$img_cate,
				'img_description'=>$img_description,
				'type'=>$type,
				'show_cate'=>$show_cate
			);
		}
		$show_cate = M('show_cate');
		$show_cates=array();	
		$show_datas = $show_cate ->field('id,cate_name')
					
					  ->order('id asc')
					  ->select();
					  
		    foreach ($show_datas as $key => $data){
		    	$id = $data['id'];
		    	$cate_name = $data['cate_name'];
				$show_cates[]=array(
				'id' =>$id,
				'cate_name' =>$cate_name
			);
		}
		$this->assign("show_cates",$show_cates);
		//print_r($res);exit;
    	$this->assign("res",$res);
    
    	$this->display('specailshailist');
    }
    /**
     * 被操作过的日志列表
     */
    public function checklist(){
    		$this->checkSession();
    		
    	    import("ORG.Util.Page");
    		/**
			多表查询
    		 */
    		$model = new Model();
    		
    		//满足条件的总记录数
    		$count  = $model->table(array('baby_user' => 'user','baby_freeze_user' => 'freeze_user'))
    						->where('user.id = freeze_user.user_id')
    						->count();
    		$page = new  Page($count,100);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出
    		
    		//进行分页数据查询
    		$list = $model->table(array('baby_user' => 'user','baby_freeze_user' => 'freeze_user'))
    						->where('user.id = freeze_user.user_id')
    						->field('user.id,user.user_name,freeze_user.remark,freeze_user.create_time')
    						->order('freeze_user.id desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
    		
    		$this->assign('list',$list);//赋值数据集
    		$this->assign('page',$show);//赋值分页输出
    		$this->display();
    }
    /**
     * 二次审核 state = '3'
     */
    public  function checkagain ($type =3,$time ='last_modify_time'){
	    	$this->checkSession();
	    	
	    	import("ORG.Util.Page");
	    	$model = new Model();
	    	$user = M('user');
	    	$where['state'] = '3';

			$count = $user ->where($where) ->count();
	    	$page = new  Page($count);
	    	$show = $page->show();
	    	
	    	$tempData = $user   ->where($where)
	    						->order("$time desc")    //
	    						->limit($page->firstRow.','.$page->listRows)
	   			 				->select();
			$uidArray = array();
			foreach ($tempData as $value) {
				array_push($uidArray, $value['id']);
			}
			
	    	$freeze_user = M('freeze_user');
	    	$where2['user_id'] = array('in',$uidArray);
	    	$where2['is_pass'] ='0';
	    	$data2 = $freeze_user	->where($where2)
	    						  	->select();

	    	$data = $tempData;
	    	for ($i = 0; $i < count($tempData); $i++) {
    			$singleUID  = $tempData[$i];
    			$uid = $singleUID['id'];
    			$userRecord = "";
    			for ($j =0 ;$j <count($data2) ;$j++){
    				$singleRow = $data2[$j];
    				if ($singleRow['user_id'] == $uid) {
    					$remark = $singleRow['remark'];
    					$userRecord = $userRecord.$remark."、";
    				}
    			}
				//控制变量不可更改，拷贝一个数组
    			$data[$i]['currentState'] = $userRecord;
    			if (empty($data[$i]['currentState']) ) {
    				$data[$i]['currentState'] = "无未通过项";
    			}
    			if ( empty($singleUID['avatar'])){
                     $data[$i]['avatar'] = C('WEB_PATH')."/".DEFAULT_AVATAR;
        	    }else{
			         $data[$i]['avatar'] = C('WEB_PATH').'/'.$singleUID['avatar'];
				}
	 		}
	    	
	    	$this->assign("list",$data);
	    	$this->assign('page',$show);
	    	$this->assign('type',$type);//3.修改时间，2注册时间（默认修改时间排序）
    		$this->display('checkagain');
    }
    
    /**
     * 举报处理
     */
    public function jubao() {
    	$this->checkSession();
    	
    	$dataRes = array();
    	
    	import("ORG.Util.Page");
    	
    	$user = M('user');
    	$user_tip = M('user_tip');
    	
    	//满足条件的总记录数
    	$count  = $user_tip->count();
    	$page = new  Page($count,100);//实例化分页类，传入总记录数
    	$show = $page->show(); //分页显示输出
    	
    	$list = $user_tip ->order('create_time desc') ->select();

    	$dataRes = $list;
		foreach ($list as $row =>$singleList) {
			$userInfo = $user->where(array('id' => $singleList['user_id']))->find();
			$tipuserInfo = $user->where(array('id' => $singleList['tip_id']))->find();
			$dataRes[$row]['user_name'] = $userInfo['user_name'];
			$dataRes[$row]['tip_name'] = $tipuserInfo['user_name'];
		}
		
    	$this->assign('list',$dataRes);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display();
    }
/*******************************************************************************************************/
    /**
     * 对用户的限制操作,需要操作两个表, baby_user  & baby_freeze_user
     */
    public function  operator_user(){
    		$this->checkSession();
    	
	    	//$id=在数据库中的用户id，$operValue =select选择的操作
	     $user_id = $_POST['user_id'];
	     $operValue =$_POST['operValue'];
	     
	     
	     $remark ="";							//记录
	     $state ="0";							//用户状态
	     $days = 0;								//限制天数
	     $show_nick = '1';						//是否显示昵称,默认1显示
	     $show_sign = '1';						//是否显示签名,默认1显示
	     $show_interest = '1';					//是否显示填写的兴趣,
 	     $show_skill = '1';						//是否显示填写的技能,
	     $show_avatar = '1' ;					//是否要显示用户的头像
 	     $show_fee_scale = '1';					//是否显示技能价格
 	     $show_haunt = '1';						//是否显示常出没地
	     $is_pass = '0';							//默认是不通过的
	     
	     $timeStamp = time();					//当前时间戳。。
	     $begindate = date('Y-m-d H:i:s',$timeStamp);//当前时间格式化
	     switch ($operValue) {
	     	case 4:{//昵称违规
	     		$state  ="3";
	     		$remark = "用户名违规";
	     		$show_nick = '0';//不显示昵称
	     		break;
	     	}
	     	case 5:{//签名违规
	     		$state  ="3";
	     		$remark = "个性签名违规";
	     		$show_sign = '0';//不显示签名
	     		break;
	     	}
	     	case 6:{//兴趣填写违规
	     		$state = "3";
	     		$remark = "兴趣违规";
	     		$show_interest = '0';
	     		break;
	     	}
	     	case 7:{
	     		$state ="3";
	     		$remark = "技能违规";
	     		$show_skill = '0';
	     		break;
	     	}
	     	case 8:{//头像违规
	     		$state  ="3";
	     		$remark = "头像违规";
	     		$show_avatar = '0';//不显示头像(使用一张默认的图代替用户上传的头像)
	     		break;
	     	}
	     	case 9:{//收费标准违规
	     		$state  ="3";
	     		$remark = "技能说明违规";
	     		$show_fee_scale = '0';//不显示收费标准)
	     		break;
	     	}
	     	case 10:{//常出没地违规
	     		$state  ="3";
	     		$remark = "常出没地违规";
	     		$show_haunt = '0';//不显示常出没地)
	     		break;
	     	}
	     	 
	     	case 1:{//冻结一天
	     		$state = "1";
	     		$days = 1;
	     		$remark = "因发布违法违规信息，封帐号一天";
	     		break;
	     	}
	     	case 2:{//冻结三天
	     		$state = "1";
	     		$days = 3;
	     		$remark = "因发布违法违规信息，封帐号三天";
	          	break;
	     	}
	     	case 3:{//永久冻结
	     		$state = "2";
	     		$remark = "因发布违法违规信息，永久封闭账号";
	     		break;
	     	}
	     	default:{
	     		break;
	     	}
	     }
	     $endtimeStamp = $timeStamp +$days*24*60*60;
	     $endDate = date('Y-m-d H:i:s',$endtimeStamp);
	     
	     /**
	 	 先修改用户表的用户状态
	      */
	     $user = M('user');
	     $where['id'] = $user_id;
	     $retRes  = $user ->field('state') ->where($where) ->select();
	     
		 $firstRes = $retRes[0];
	     if ($state == $firstRes['state']) {
	     	 $result1 = true;   //要改的状态和数据库一样就不改了
	     }else {
		     $data['state'] = $state;
		   	 $result1 = $user->where($where)->data($data)->save(); //数据库state的值与当前的state不同
	     }
	     
	     /**
			再将处理的用户加入操作列表（操作日志 ）
	      */
	     $freeze_user  = M('freeze_user');
	     
	     $condition['user_id']		= $user_id;
	 	 $condition['remark'] 		= $remark;
	 	 $condition['days'] 			= $days;
	     $condition['is_show_nick']  = $show_nick;
	     $condition['is_show_sign']  = $show_sign;
	     $condition['is_show_interests']  = $show_interest;
	     $condition['is_show_skills']  = $show_skill;
	     $condition['is_show_avatar']  = $show_avatar;
	     $condition['is_show_feescale'] = $show_fee_scale;
	     $condition['is_show_haunt'] = $show_haunt;
	     $condition['is_pass'] = $is_pass;
	     //返回数据
	     $array =array();
	     
	     //向数据库插入一条新的记录（不会重复插入）
	     $queryResult = $freeze_user->where($condition) ->select();
// 	     echo("$queryResult");
	     
	     if ($queryResult) {
// 	     	echo "已经执行过该操作";
	     	$array = array(
	     			'flag'  => false,
	     			'reMsg'    =>"您已经执行过该操作!",
	     	);
	     }else {
	     	
	     	$condition['begin_time'] 	= $begindate;
	     	$condition['end_time']  	= $endDate;
	     	$condition['create_time']   = $begindate;
	     	$condition['modify_time']   = $begindate;
	     	
		    $result2 = $freeze_user->data($condition) ->add();
		     
		     if ($result1 && $result2) {
		     	$array = array(
		     			'flag'  => true,
		     			'reMsg'    => "操作成功!",
		     	);
		     }else {
		     	$array = array(
		     			'flag'  => false,
		     			'reMsg'    => "操作失败!",
		     	);
		     }
	     }
	    	echo json_encode($array);
     	
    }
    
    /**
     * 操作相册，屏蔽不合格图片
     */
    public function operator_album(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['oper'];
		$album = M('album_img');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['operation'] = $oprator;
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
    
    /**
     * 二审用户的某项信息经修改通过审核
     */
    public function pass_user() {
    	
	    	$user_id = $_POST['user_id'];
	    	$operValue =$_POST['operValue'];
    	
	    	$where['user_id'] = $user_id;
    		switch ($operValue) {
    			case 1:{ //用户名
    				$where['is_show_nick'] ='0';
    				break;
    			}
    			case 2:{ //头像
    				$where['is_show_avatar'] ='0';
    				break;
    			}
    			case 3:{ //兴趣
    				$where['is_show_interests'] ='0';
    				break;
    			}
    			case 4:{ //技能
    				$where['is_show_skills'] = '0';
    				break;
    			}
    			case 5:{ //收费标准
    				$where['is_show_feescale'] = '0';
    				break;
    			}
    			case 6:{ //签名
    				$where['is_show_sign'] = '0';
    				break;
    			}
    			case 7:{ //常出没地
    				$where['is_show_haunt'] = '0';
    				break;
    			}
    			default:
    				;
    			break;
    		}
    		$updateWhere = $where;
    		$freeze_user = M('freeze_user');
    		
    	 	//最新的记录
    		$result1 = $freeze_user->where($where)->order('id desc')->limit(1)->select();
    		
    		//未查到记录或查到记录已通过审核
    		if (!$result1 || ( $result1[0]['is_pass'] =='1' ) ) {
    			$array = array(
    					'flag'  => false,
    					'reMsg'    => "该项已通过审核!",
    			);
    		}else{
    			//修改is_pass状态
    			$data['is_pass'] = '1';
    			$data['modify_time'] = date('Y-m-d H:i:s',time());
    			$result  = $freeze_user->where($updateWhere)->data($data) ->save();
    			
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
    		}
    		
	   	echo json_encode($array);;
    }

	  /**
     * 话题列表
     */
    public function postlist(){
    	$this->checkSession();	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$post_img = M('post_img');
    	$where2['state'] ='0';
	    $where2['root_img_id']  = array('exp',' is null ');
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出



		$list = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->field('post.id,post.user_id,user.nick_name,post.img_description,post.is_recommend,post.img,post.post_create_time,post.post_class')
    						->order('post.post_create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
						
		
		
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$tmpImg = $value['img'];
					$nick_name = $value['nick_name'];
					$desc = $value['img_description'];
					$user_id = $value['user_id'];
					$is_recommend = $value['is_recommend'];
					$post_class = $value['post_class'];
					$Imgs=explode(';',$tmpImg);
					if(count($Imgs)>1){
						$is_theme = 1;
					}else{
						$is_theme =0;
					}
					$imgUrl=array();	
					if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
							$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
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
				
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postlist');
    }

	
	public function postnewlist(){
    	$this->checkSession();	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$post_img = M('post_img');
    	$where2['state'] ='0';
	    $where2['root_img_id']  = array('exp',' is null ');
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出



		$list = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->field('post.id,post.user_id,user.nick_name,post.img_description,post.is_recommend,post.img,post.post_create_time,post.post_class,post.post_cate_id')
    						->order('post.post_create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
						
		
		
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$tmpImg = $value['img'];
					$nick_name = $value['nick_name'];
					$desc = $value['img_description'];
					$user_id = $value['user_id'];
					$is_recommend = $value['is_recommend'];
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
							$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
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
					'post_cate_id'=>$post_cate_id
				
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postnewlist');
    }	
		
	 public function otherpostlist(){
    	$this->checkSession();	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$post_img = M('post_img');
		$model = new Model();
		//查询运营用户
		
		$yunList = $model->table(array('baby_common_user' => 'u'))
    						->field('u.user_id')
    						->select();
		if(is_array($yunList) && count($yunList) > 0){
			foreach($yunList as $key=>$value){
				$tmpArray .= $value['user_id'].',';			
			}
		}
		$otherData = substr($tmpArray,0,strlen($tmpArray)-1);
		//echo $otherData;exit;
		$where2['user_id']  = array('not in',$otherData);
		$where2['state'] = '0';
		$where2['root_img_id']  = array('exp',' is null ');
		//满足条件的总记录数
    		$count  = $model->table(array('baby_post_img' => 'post'))
    						->where($where2)
    						->count();
			///echo $count;exit;
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出



		$list = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null  and post.user_id not in ($otherData) ")
    						->field('post.id,post.user_id,user.nick_name,post.img_description,post.is_recommend,post.img,post.post_create_time,post.post_class,post.post_cate_id')
    						->order('post.post_create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
			
		///print_r($list);exit;
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$tmpImg = $value['img'];
					$nick_name = $value['nick_name'];
					$desc = $value['img_description'];
					$user_id = $value['user_id'];
					$is_recommend = $value['is_recommend'];
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
							$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
						}
					}
					$admireCount = M('post_admire')->where(" img_id={$id} and is_cancel='0' ")->count();
					$reviewCount = M('post_review')->where(" img_id={$id} and is_del='0' ")->count();
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
					'admire_count'=>$admireCount,
					'review_count'=>$reviewCount
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('otherpostlist');
    }
	
	
	public function postlistpage(){
    	$this->checkSession();	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$post_img = M('post_img');
    	$where2['state'] ='0';
	    $where2['root_img_id']  = array('exp',' is null ');
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出



		$list = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->field('post.id,post.user_id,user.nick_name,post.img_description,post.is_recommend,post.img,post.post_create_time')
    						->order('post.post_create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
						
		
		
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$tmpImg = $value['img'];
					$nick_name = $value['nick_name'];
					$desc = $value['img_description'];
					$user_id = $value['user_id'];
					$is_recommend = $value['is_recommend'];
					$Imgs=explode(';',$tmpImg);
					if(count($Imgs)>1){
						$is_theme = 1;
					}else{
						$is_theme =0;
					}
					$imgUrl=array();	
					if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
							$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
						}
					}
					$res[]=array(
					'id'=>$id,
					'nick_name'=>$nick_name,
					'img_description'=>$desc,
					'imgUrl'=>$imgUrl,
					'is_theme'=>$is_theme,
					'user_id'=>$user_id,
					'is_recommend'=>$is_recommend
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postlistpage');
    }
	//发布话题
	public function publicpost(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	
		public function publicpostpage(){
			$user_name = $_GET['user_name'];
			$model = new Model();
    		$count  = $model->table(array('baby_user' => 'user'))
    						->where("user.user_name='{$user_name}' ")
    						->select();
    		$user_id = $count[0]['id'];
			$this->assign('user_id',$user_id);			
			$this->display();
		}
	
//回复话题
	public function replaypost(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	
		public function replaypostpage(){
			$user_name = $_GET['user_name'];
			$model = new Model();
    		$count  = $model->table(array('baby_user' => 'user'))
    						->where("user.user_name='{$user_name}' ")
    						->select();
    		$user_id = $count[0]['id'];
			$this->assign('user_id',$user_id);			
			$this->display();
		}
	
	
	 public function buylist(){
    	
    	$this->checkSession();
    		
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('worthbuy_img');
    	$where2['state'] ='0';//图片状态正常
    	$where2['album_id'] =0;
		
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,state,img_description,current_price,original_price,news,is_recommend,good_action,create_time,admire_count,post_url')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,is_theme,operation,img_description,current_price,original_price,news,is_recommend,good_action,create_time,admire_count,post_url,expire_time')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_description = $data['img_description'];
				$is_recommend = $data['is_recommend'];
				$create_time = $data['create_time'];
				$admire_count = $data['admire_count'];
				$post_url = $data['post_url'];
				$current_price = $data['current_price'];
				$original_price = $data['original_price'];
				$news = $data['news'];
				$good_action = $data['good_action'];
				$expire_time = $data['expire_time'];
				$Imgs=explode(';',$tmpImg);
				if(count($Imgs) > 1){
					$is_theme = 1;
				}else{
					$is_theme = 0;
				}
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,
				'img_description'=>$img_description,
				'is_recommend'=>$is_recommend,
				'create_time'=>$create_time,
				'admire_count'=>$admire_count,
				'post_url'=>$post_url,
				'current_price'=>$current_price,
				'original_price'=>$original_price,
				'news'=>$news,
				'good_action'=>$good_action,
				'expire_time'=>$expire_time
			);
		}
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('buylist');
    }

	 public function buynewlist(){
    	
    	$this->checkSession();
    		
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('worthbuy_new');
    	$where2['state'] ='0';//图片状态正常
    	
		
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,state,img_description,current_price,original_price,news,is_recommend,good_action,create_time,post_url')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
							
		$count  =$user_album ->where($where2)->count();
		///echo $count;exit('qq');
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field(' * ')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
			//var_dump($datas);exit('aaa');		  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$img_description = $data['img_description'];
				$is_recommend = $data['is_recommend'];
				$create_time = $data['create_time'];
				$post_url = $data['post_url'];
				$current_price = $data['current_price'];
				$original_price = $data['original_price'];
				$news = $data['news'];
				$good_action = $data['good_action'];
				$expire_time = $data['expire_time'];
				$Imgs=explode(';',$tmpImg);
				if(count($Imgs) > 1){
					$is_theme = 1;
				}else{
					$is_theme = 0;
				}
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'img_description'=>$img_description,
				'is_recommend'=>$is_recommend,
				'create_time'=>$create_time,
				'post_url'=>$post_url,
				'current_price'=>$current_price,
				'original_price'=>$original_price,
				'news'=>$news,
				'good_action'=>$good_action,
				'expire_time'=>$expire_time
			);
		}
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('buynewlist');
    }
	
	 public function operator_buy(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['is_recommend'];
		$album = M('worthbuy_img');
		//$where['id'] = $imgid;
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
	
	
	 public function operator_goodaction(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['good_action'];
		$album = M('worthbuy_img');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['good_action'] = $oprator;
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
	
	
	 public function operator_goodactionnew(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['good_action'];
		$album = M('worthbuy_new');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['good_action'] = $oprator;
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
	
	public function operator_post_class(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['post_class'];
		$album = M('post_img');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['post_class'] = $oprator;
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
	
		public function operator_postnew_class(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['post_cate_id'];
		$album = M('post_img');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['post_cate_id'] = $oprator;
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
	
	public function operator_show_cate(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['cate_name'];
		$album = M('album_img');
		$where['id'] = array('in',$imgid);
		$data['show_cate'] = $oprator;
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
	
	public function publicbuy(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	
	public function publicbuynew(){
	
		
		$this->display('publicbuynew');
	}

	public function operator_buystate(){
    	
    	$imgid = $_GET['imgid'];
		$album = M('worthbuy_img');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/buylist.html");
    }
	
	public function operator_buystatenew(){
    	
    	$imgid = $_GET['imgid'];
		$album = M('worthbuy_new');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/buynewlist.html");
    }
	
	//图片评论
	
	public function replayimg(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	//值得买评论
	public function reviewbuy(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	
	  /**
     * 话题列表
     */
    public function postreplylist(){
    	$this->checkSession();
    	$id = $_GET['id'];
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$post_img = M('post_img');
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
					$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
				}
			} else {
				$imgUrl=array();	
			}
			$admireCount = M('post_admire')->where("img_id={$id} and is_cancel='0' ")->count();
			$reviewCount = M('post_review')->where("img_id={$id} and is_del='0' ")->count();
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
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postreplylist');
    }
	
	public function reviewreplaypost(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	//reviewreplaypost
	
	//秀秀赞
	public function admireimg(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	
	//热点赞
	public function admirepost(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	
	//值得买赞
	public function admirebuy(){
	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$this->display();
	}
	//删除后台审核图片
	
	public function operator_imagestate(){
    	
    	$imgid = $_GET['id'];
		$album = M('album_img');
		$where['id'] = array('in',$imgid);
		$data['state'] = '1' ;
		$data['show_state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/checkpicture.html");
    }
	
	public function operator_specialstate(){
    	
    	$imgid = $_GET['id'];
		$album = M('album_img');
		$where['id'] = array('in',$imgid);
		$data['show_state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/specailshailist.html");
    }
	//热点设置为精品
	 public function operator_post(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['is_recommend'];
		$album = M('post_img');
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
	//值得买修改
	public function updatebuy(){
		$this->display();
	}
	public function updatebuynew(){
		$this->display();
	}
	 public function searchbuylist(){
    	
    	$this->checkSession();
    	$img_description = $_POST['img_description'];	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('worthbuy_img');
    	$where2['state'] ='0';//图片状态正常
    	$where2['album_id'] =0;
		$where2['img_description'] = array('like',"%{$img_description}%");
		$where2['img'] = array('like','static%');
    	$data2 =  $user_album ->field('id, user_id,img,state,post_url,img_description,is_recommend,create_time,admire_count')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,is_theme,operation,img_description,current_price,original_price,news,is_recommend,good_action,create_time,admire_count,post_url,expire_time')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
					  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_description = $data['img_description'];
				$is_recommend = $data['is_recommend'];
				$create_time = $data['create_time'];
				$current_price = $data['current_price'];
				$original_price = $data['original_price'];
				$news = $data['news'];
				$admire_count = $data['admire_count'];
				$expire_time = $data['expire_time'];
				$post_url = $data['post_url'];
				$Imgs=explode(';',$tmpImg);
				if(count($Imgs) > 1){
					$is_theme = 1;
				}else{
					$is_theme = 0;
				}
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,
				'img_description'=>$img_description,
				'is_recommend'=>$is_recommend,
				'create_time'=>$create_time,
				'admire_count'=>$admire_count,
				'post_url'=>$post_url,
				'expire_time'=>$expire_time,
				'new'=>$new,
				'current_price'=>$current_price,
				'original_price'=>$original_price
			);
		}
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('buylist');
    }
	
	 public function searchbuynewlist(){
    	
    	$this->checkSession();
    	$img_description = $_POST['img_description'];	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('worthbuy_new');
    	$where2['state'] ='0';//图片状态正常
		$where2['img_description'] = array('like',"%{$img_description}%");
    	$data2 =  $user_album ->field('id, user_id,img,state,post_url,img_description,create_time')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		//echo $count;exit;
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,img_description,current_price,original_price,news,good_action,create_time,post_url,expire_time')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
			//print_r($datas);exit;		  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$img_description = $data['img_description'];
				$is_recommend = $data['is_recommend'];
				$create_time = $data['create_time'];
				$current_price = $data['current_price'];
				$original_price = $data['original_price'];
				$news = $data['news'];
				$admire_count = $data['admire_count'];
				$expire_time = $data['expire_time'];
				$post_url = $data['post_url'];
				$Imgs=explode(';',$tmpImg);
				if(count($Imgs) > 1){
					$is_theme = 1;
				}else{
					$is_theme = 0;
				}
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,
				'img_description'=>$img_description,
				'is_recommend'=>$is_recommend,
				'create_time'=>$create_time,
				'admire_count'=>$admire_count,
				'post_url'=>$post_url,
				'expire_time'=>$expire_time,
				'new'=>$new,
				'current_price'=>$current_price,
				'original_price'=>$original_price
			);
		}
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('buynewlist');
    }
	//删除话题列表
	public function operator_poststate(){
    	
    	$imgid = $_GET['imgid'];
		$album = M('post_img');
		$where['id'] = array('in',$imgid);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/postlist.html");
    }
	//搜索热点
	public function searchpostlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
		//先查相册的数据库，
		$img_description = $_POST['img_description'];
    	$post_img = M('post_img');
    	$where2['state'] ='0';
	    $where2['root_img_id']  = array('exp',' is null ');
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出



		$list = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null and post.img_description like '%{$img_description}%' ")
    						->field('post.id,post.user_id,user.nick_name,post.img_description,post.is_recommend,post.img,post.post_create_time')
    						->order('post.post_create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
						
		
		
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$tmpImg = $value['img'];
					$nick_name = $value['nick_name'];
					$desc = $value['img_description'];
					$user_id = $value['user_id'];
					$is_recommend = $value['is_recommend'];
					$Imgs=explode(';',$tmpImg);
					if(count($Imgs)>1){
						$is_theme = 1;
					}else{
						$is_theme =0;
					}
					$imgUrl=array();	
					if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
							$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
						}
					}
					$res[]=array(
					'id'=>$id,
					'nick_name'=>$nick_name,
					'img_description'=>$desc,
					'imgUrl'=>$imgUrl,
					'is_theme'=>$is_theme,
					'user_id'=>$user_id,
					'is_recommend'=>$is_recommend
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('searchpostlist');
	}
	
	
	//搜索热点
	public function searchpostnewlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
		//先查相册的数据库，
		$img_description = $_POST['img_description'];
    	$post_img = M('post_img');
    	$where2['state'] ='0';
	    $where2['root_img_id']  = array('exp',' is null ');
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null ")
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出



		$list = $model->table(array('baby_user' => 'user','baby_post_img' => 'post'))
    						->where("post.user_id = user.id and post.state='0' and post.root_img_id is null and post.img_description like '%{$img_description}%' ")
    						->field('post.id,post.user_id,user.nick_name,post.img_description,post.is_recommend,post.img,post.post_create_time,post.post_cate_id')
    						->order('post.post_create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
						
		
		
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$tmpImg = $value['img'];
					$nick_name = $value['nick_name'];
					$desc = $value['img_description'];
					$user_id = $value['user_id'];
					$is_recommend = $value['is_recommend'];
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
							$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
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
					'post_cate_id'=>$post_cate_id
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postnewlist');
	}
	
	public function operator_replypostlist(){
    	
    	$imgid = $_GET['id'];
		$album = M('post_img');
		$where['id'] = array('in',$imgid);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/postlist.html");
    }
	
	public function getUser1(){
		$cid = $_GET['cid'];
		$group_id = $_GET['group_id'];
        if($group_id>0) {
            $where_group_condition['type']='1';
            $where_group_condition['img_id']=$group_id;
            $where_group_condition['state']='0';
            $getGroupInfo=M('post_idol')->field('user_id')
                      ->where($where_group_condition)
                      ->select();
            $tmp_user_arr=array();
            foreach ($getGroupInfo as $key => $value) {
               $tmp_user_arr[]=$value['user_id'];
            }
            $user_info=implode(",",$tmp_user_arr);
            $where['user_id']  = array('not in',$user_info);
        }
		///echo $cid;exit;;
		$common_user = M('common_user');
		$where['cid'] =$cid;
		$userdatas = $common_user ->field('user_id')
					  ->where($where)
					  ->select();
		///echo json_encode($userdatas);
        $values="";
		if(is_array($userdatas) && count($userdatas)){
			foreach($userdatas as $key=>$value){
				$values .= $value['user_id'].',';
			}
		}
		$tempUsers = substr($values,0,-1);
		//echo $tempUsers;exit;
		///echo json_encode($userdatas);
		$user = M('user');
		$where1['id'] = array('in',$tempUsers);
		/*$users = $user ->field('id,nick_name')
					  ->where($where1)
					  ->select();*/
		$checkusers = $user ->field('id,nick_name,user_name')
                      ->where($where1)
                      ->select();
        $users=array();
        if($checkusers) {
            foreach ($checkusers as $key => $value) {
                $users[]=array(
                    'id'=>$value['id'],
                    'nick_name'=>$value['nick_name']."——".$value['user_name'],
                    );
            }
        }
		echo json_encode($users);
		
	}
    public function city(){
    	$city_where['city_id'] = '0';
        $city_where['county_id'] = '0';
        $cityList = M('citys') ->field('province_id,name')
            ->where($city_where)
            ->select();
        $this->assign('cityList',$cityList);
       	$groupList = M('post_group')->field('id,group_name')->where("state='0'")->order('id desc')->select();
        $this->assign('groupList',$groupList);//赋值数据集
        $businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    //获取城市
    public function getCity(){
        $province_id=I("pid");
        if($province_id) {
        	$where['province_id'] = $province_id;
        }
        $where['city_id'] = array('gt','0');
        $where['county_id'] = '0';
        $where['street_id'] = '0';
        $cityList = M('citys') ->field('city_id,name')
            ->where($where)
            ->select();
        echo json_encode($cityList);
    }
    //获取县
    public function getCounty(){
    	$province_id=I("province");
    	if($province_id) {
        	$where['province_id'] = $province_id;
        }
        $city_id=I("pid");
        if($city_id) {
        	$where['city_id'] = $city_id;
        }
        $where['county_id'] = array('gt','0');
        $where['street_id'] = '0';
        $cityList = M('citys') ->field('county_id,name')
            ->where($where)
            ->select();
        
        echo json_encode($cityList);
    }
    //获取乡镇
    public function getStreet(){
        $province_id=I("province");
    	if($province_id) {
        	$where['province_id'] = $province_id;
        }
        $city_id=I("city");
        if($city_id) {
        	$where['city_id'] = $city_id;
        }
        $county_id=I("pid");
        if($county_id) {
        	$where['county_id'] = $county_id;
        }
        
        $where['street_id'] = array('gt','0');
        $cityList = M('citys') ->field('street_id,name')
            ->where($where)
            ->select();
        
        echo json_encode($cityList);
    }


	public function getUser(){
		$this->display('getuser');
	}
	
	public function publicbusiness(){
		$businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
		$this->display('addbusiness');
	}
	
	public function publicspecial(){
	
		$model = new Model();
		
		$list = $model->table(array('baby_show_cate' => 'show'))
						
						->field('show.cate_name,show.id')
    					->select();
		
		$res=array();
			foreach($list as $key=>$value){
				$id = intval($value['id']);
				$cate_name = $value['cate_name'];
				$res[]=array(
					'id'=>$id,
					'cate_name'=>$cate_name,
					);
			}
		//print_r($res);exit;
    	$this->assign("res",$res);
		$this->display('publicspecial');
	}
	
	public function head(){
		$businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
		
		$this->display('head');
	}
	
	public function businesslist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$post_img = M('worthbuy_business');
    	$where2['state'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_worthbuy_business' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_worthbuy_business' => 'business'))
    						->where($where2)
    						->field('business.id,business.album_img,business.post_url,business.state,business.create_time')
    						->order('business.create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$album_img = "http://api.meimei.yihaoss.top/".$value['album_img'];
					$post_url = $value['post_url'];
					$state = $value['state'];
					$create_time = $value['create_time'];		
					$res[]=array(
					'id'=>$id,
					'album_img'=>$album_img,
					'post_url'=>$post_url,
					'state'=>$state,
					'create_time'=>$create_time
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('businesslist');
	
	
	}
	
	public function specailheadlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$post_img = M('static_special');
    	$where2['state'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_static_special' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_static_special' => 'business'))
    						->where($where2)
    						->field('business.id,business.img')
    						->limit($page->firstRow,$page->listRows)
    						->select();
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$album_img = "http://api.meimei.yihaoss.top/".$value['img'];
							
					$res[]=array(
					'id'=>$id,
					'img'=>$album_img
				
					);
			}
	
	
	
	
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('specailheadlist');
	
	
	}
	
	public function headlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$post_img = M('head');
    	$where2['state'] ='0';
    	//$where2['online'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_head' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_head' => 'business'))
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
					}
					
							
					$res[]=array(
					'id'=>$id,
					'img'=>$album_img,
					'name'=>$name
					);
			}
	
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('headlist');
	
	
	}
	public function operator_business(){
    	
    	$id = $_GET['id'];
		$album = M('worthbuy_business');
		$where['id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/businesslist.html");
    }
	
	
	
	public function operator_head(){
    	
    	$id = $_GET['id'];
		$album = M('head');
		$where['id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/headlist.html");
    }
	
	public function operator_specialhead(){
    	
    	$id = $_GET['id'];
		$album = M('static_special');
		$where['id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/specailheadlist.html");
    }
	
	public function buy_recommendlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$post_img = M('worthbuy_recommend');
    	$where2['state'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_worthbuy_recommend' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_worthbuy_recommend' => 'business'))
    						->where($where2)
    						->field('business.id,business.user_id,business.good_url,business.state,business.reason,business.create_time')
    						->order('business.create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$post_url = $value['good_url'];
					$state = $value['state'];
					$reason = $value['reason'];
					$user_id = $value['user_id'];
					$create_time = $value['create_time'];		
					$res[]=array(
						'id'=>$id,
						'post_url'=>$post_url,
						'state'=>$state,
						'reason'=>$reason,
						'user_id'=>$user_id,
						'create_time'=>$create_time
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('buy_recommendlist');
	
	
	}
	
	public function operator_buyrecommend(){
    	
    	$imgid = $_POST['imgid'];
    	$oprator = $_POST['state'];
		$album = M('worthbuy_recommend');
		//$where['id'] = $imgid;
		$where['id'] = array('in',$imgid);
		$data['state'] = $oprator;
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
	public function addalbum(){
		$id = $_GET['id'];
		$user_id = $_GET['user_id'];
		$this->assign('id',$id);//赋值数据集
		$this->assign('user_id',$user_id);
    	$this->display('addalbum');
	
	}
	
	public function postalbumlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$post_img = M('post_album');
    	$where2['state'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_post_album' => 'album'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_post_album' => 'album'))
    						->where($where2)
    						->field('album.img_id,album.state,album.post_title,album.post_album,album.create_time')
    						->order('album.create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['img_id'];
					$post_album = $value['post_album'];
					$state = $value['state'];
					$post_title = $value['post_title'];
					$create_time = $value['create_time'];		
					$res[]=array(
						'id'=>$id,
						'post_album'=>$post_album,
						'state'=>$state,
						'post_title'=>$post_title,
						'create_time'=>$create_time
					);
			}
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postalbumlist');
	
	
	}
	
	public function operator_postalbum(){
    	
    	$id = $_GET['id'];
		$album = M('post_album');
		$where['img_id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		/**
		$postImg = M('post_img');
		$where2['id'] = array('in',$id);
		$datas['state'] = '0';
		$res = $postImg->where($where2)->data($datas)->save();
		**/
		header("Location:http://checkpic.meimei.yihaoss.top/Index/postalbumlist.html");
    }
	
	
	public function onweruser(){
		
		$this->display('onweruser');
	
	}
	
	
	public function searchAlbum(){
    
    	$this->checkSession();
    	$userid = $_POST['userid'];	
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('albums');
    	$where2['state'] ='0';//图片状态正常
    	$where2['user_id'] =$userid;
		$where2['root_album_id'] =0;
    	$data2 =  $user_album ->field('id,album_description')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field('id,album_description')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
		//print_r($datas);exit;			  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
				$album_description = $data['album_description'];
				
				$res[]=array(
					'id' =>$img_id,
					'album_description'=>$album_description
				);
			}
			//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('searchAlbumList');
    }
	
	public function searchAlbumImg(){
    	
    	$this->checkSession();
    	$id = $_GET['id'];
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('album_img');
    	
		
    	$data2 =  $user_album ->field('id, user_id,img,state')
    						  ->where(" state='0' and (root_album_id={$id} or album_id={$id} )") //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where(" state='0' and (root_album_id={$id} or album_id={$id} )")->count();
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field('id,user_id,img,img_description,type,is_theme,operation,img_cate')
					  ->where(" state='0' and (root_album_id={$id} or album_id={$id} )")
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
				  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
		    	$user_id = $data['user_id'];
				$tmpImg=$data['img'];
				$is_theme = $data['is_theme'];
				$operator = $data['operation'];
				$img_cate = $data['img_cate'];
				$img_description = $data['img_description'];
				$type = $data['type'];
				$Imgs=explode(';',$tmpImg);
				$imgUrl=array();	
				if(is_array($Imgs)){
					foreach ($Imgs as $key=>$tmpImg){
						$imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
					}
				}
				
				$res[]=array(
				'id' =>$img_id,
				'user_id' =>$user_id,
				'imgUrl'=>$imgUrl,
				'is_theme'=>$is_theme,
				'operation'=>$operator,	
				'img_cate'=>$img_cate,
				'img_description'=>$img_description,
				'type'=>$type
			);
		}
		
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('AlbumImgList');
    }
	
	//获取二级相册
	public function getSecondAlbum(){
    
    	$this->checkSession();
    	$albumid = $_GET['id'];	
		
    	import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('albums');
    	$where2['state'] ='0';//图片状态正常
		$where2['root_album_id'] =$albumid;
    	$data2 =  $user_album ->field('id,album_description')
    						  ->where($where2) //相片正常
    						  ->order('create_time')
    						  ->select();
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
	
		$res=array();	
		$datas = $user_album ->field('id,album_description')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
		//print_r($datas);exit;			  
		    foreach ($datas as $key => $data){
		    	$img_id = $data['id'];
				$album_description = $data['album_description'];
				
				$res[]=array(
					'id' =>$img_id,
					'album_description'=>$album_description
				);
			}
			//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('SecondAlbumList');
    }
	
	public function postgrouplist(){
		import("ORG.Util.Page");
		$title=I("group_name");
		if($title) {
			$where2['group_name']=array('like',"%".$title."%");
			$this->assign('group_name',$title);
		}
		//先查相册的数据库，
    	$user_album = M('post_group');
		
    	$where2['state'] ='0';//图片状态正常
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();
		
		$res=array();	
		$datas = $user_album ->field('id,group_name,user_id')
					  ->where($where2)
					  ->order('create_time desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
		//print_r($datas);exit;			  
		    foreach ($datas as $key => $data){
				$users = M('user');
				
		    	$img_id = $data['id'];
				$album_description = $data['group_name'];
				$user_id = intval($data['user_id']);
				///echo $user_id;exit;
				$condition['id'] = $user_id;
				$users = $users->where($condition)->find();
				$nick_name = $users['nick_name'];	  
				$res[]=array(
					'id' =>$img_id,
					'group_name'=>$album_description,
					'nick_name'=>$nick_name
				);
			}
			//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('page',$show);
    	$this->display('postgrouplist');
	
	
	}
	//新版商家列表
	public function newbusinesslist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	//$where2='state=0';
    	$where2['state']='0';
        $business = M('business');

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
		
		//print_r($res);exit;
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('newbusinesslist');
	
	
	}
	public function npublicbusiness(){
		$business_type=I("business_type");
		$this->assign('business_type',$business_type);
		$city_where['city_id'] = '0';
        $city_where['county_id'] = '0';
        $cityList = M('citys') ->field('province_id,name')
            ->where($city_where)
            ->select();
        $this->assign('cityList',$cityList);

	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
		$userList = M('user')->where('user_role="1"')->select();
		$this->assign('userList',$userList);
		$regionA = M('citys_regions') 
            ->field('county as city_name,county_id as id')
            ->group("county")
            ->select();

        $this->assign('regionA',$regionA);
		
		$this->display();
	}
    public function publicnewpost() {
        $groupList = M('post_group')->field('id,group_name')->where("state='0'")->order('id desc')->select();
        $this->assign('groupList',$groupList);//赋值数据集
        $businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    /*删除商家*/
    public function  del_business(){
    	$business_type=I("business_type");
        $id = $_GET['id'];        
        $where['id'] = array('in',$id);
        $data['state'] = '1' ;
        if($business_type==2) {
        	$album= M('business2');
        	$album->where($where)->data($data)->save();
        	header("Location:http://checkpic.meimei.yihaoss.top/Others/virtualbusinesslist.html");
        } elseif($business_type==1) {
        	$album= M('business');
        	$album->where($where)->data($data)->save();
        	header("Location:http://checkpic.meimei.yihaoss.top/Index/newbusinesslist.html");
        }        
    }

    public function postcoverlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$post_img = M('post_album');
    	$where2['state'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_post_cover' => 'cover'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_post_cover' => 'cover'))
    						->where($where2)
    						->field('cover.id,cover.img_id,cover.state,cover.post_title,cover.post_album,cover.create_time')
    						->order('cover.create_time desc')
    						->limit($page->firstRow,$page->listRows)
    						->select();
		
		$res=array();
				foreach($list as $key=>$value){
					$id = $value['id'];
					$img_id = $value['img_id'];
					$post_album = $value['post_album'];
					$state = $value['state'];
					$post_title = $value['post_title'];
					$create_time = $value['create_time'];		
					$res[]=array(
						'cover_id'=>$id,
						'id'=>$img_id,
						'post_album'=>$post_album,
						'state'=>$state,
						'post_title'=>$post_title,
						'create_time'=>$create_time
					);
			}
		
    	$this->assign("res",$res);
    	//$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('postcoverlist');	
	}

	public function operator_postcover(){
    	
    	$id = $_GET['id'];
		$album = M('post_cover');
		$where['id'] = $id;
		//$where['img_id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/postcoverlist.html");
    }

    public function addcover(){
		$id = $_GET['id'];
		$user_id = $_GET['user_id'];
		$this->assign('id',$id);//赋值数据集
		$this->assign('user_id',$user_id);
		$groupList = M('post_group')->field('id,group_name')->where("state='0'")->order('id desc')->select();
        $this->assign('groupList',$groupList);//赋值数据集
        $businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集

    	$this->display('addcover');
	
	}

	public function update_business(){
		$business_type=I("business_type");
		$this->assign('business_type',$business_type);
		$business_id=I("id");
		if($business_id) {
			if($business_type==2) {
				$businessList = M('business2')->where('id='.$business_id)->find();
			} elseif($business_type==1) {
				$businessList = M('business')->where('id='.$business_id)->find();
			}
			
		}
		$business_time=$businessList['business_time'];
		$business_start_time=$businessList['business_start_time'];
		$business_end_time=$businessList['business_end_time'];
		if(preg_match("/--/",$business_time)) {
			$temp_business_time=explode('--', $business_time);
		}
		if(empty($business_start_time) && $temp_business_time[0]) {
			$businessList['business_start_time']=$temp_business_time[0];
		}
		if(empty($business_end_time) && $temp_business_time[1]) {
			$businessList['business_end_time']=$temp_business_time[1];
		}
		$business_pic=$businessList['business_pic'];
		if($business_pic) {
			$businessList['business_pic']="http://api.meimei.yihaoss.top/".$business_pic;
		}
		$cover=$businessList['cover'];
		if($cover) {
			$businessList['cover']="http://api.meimei.yihaoss.top/".$cover;
		}
		$business_map=$businessList['business_map'];
		if($business_map) {
			$businessList['business_map']="http://api.meimei.yihaoss.top/".$business_map;
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
		//print_r($businessList);
		$this->assign('businessList',$businessList);

		/*省*/
		$city_where['city_id'] = '0';
        $city_where['county_id'] = '0';
        $cityList = M('citys') ->field('province_id,name')
            ->where($city_where)
            ->select();
        $this->assign('cityList',$cityList);

        

	
		$userModel = M('user');
		$where['state'] = '0';
		$uidArray = array(
		1738,2354,
		738,1243,1266,1267,1263,1248,1536,1549,676,674,5957,5946,2772,
		47,125,126,823,834,931,1208,1200,4931,8684,199,
		898,1252,847,4784,4958,932,7694,7695,133,175,890,
		
		1,22,7696,913,971,2776,4742,1241,4914,5504,8687,5294,938,8934,8935,8937,8938,8939,8942,
		5257,2,182,868,1837,5264,5296,6909,7289,7399,8582,863,1241,3,8943,8944,8945,8946,8947,8948,8990);
		//$uidArray = array(125, 126, 127, 823, 834,847, 898, 1252,2, 182, 863, 868,1837,47, 890, 913, 931, 932, 1200, 1241,3, 938, 971, 1208,738, 1243, 1248, 1263, 1266, 1267,1, 22, 133, 198, 199,2354,2776,4742,4784,4914,4931,4958,5257,5264,5294,5296,5504,5946,2772,1738,7694,7695,7696,7289,6909,7399,8582,8684,8687,8795,8947);
		$where['id'] = array('in',$uidArray);
		$list = $userModel->field('id,nick_name')->where($where)->order('id')->select();
		$this->assign('list',$list);//赋值数据集
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
	public function refundorderlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['o.state'] ='0';
        //$where2['o.payment'] ='1';//array('in','45,46')
        $where2['o.payment'] =array('in','1,7');
        $where2['o.status'] ='4';
        //$where2['o.pay_price'] =array('gt','0');
        $model = new Model();


        //满足条件的总记录数
        $count  = $model->table('baby_order as o')
                        ->join(" left join baby_business_new as b on o.business_id=b.id")
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->where($where2)
                        ->count();
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出


        $res = $model->table('baby_order as o')
                    ->join(" left join baby_business_new as b on o.business_id=b.id")
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->where($where2)
                    ->field('o.id,o.user_id,u.nick_name,o.payment,o.status,o.pay_price,o.business_id,b.business_title,o.order_num,o.post_create_time,o.wx_source')
                    ->order('o.post_create_time desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        if($res) {
            foreach ($res as $key => $value) {
                $tmp_payment=$value['payment'];
                $tmp_wx_source=$value['wx_source'];
                if($tmp_payment==1) {
                    if($tmp_wx_source==4) {
                        $tmp_wx_source="-企业";
                    } else {
                        $tmp_wx_source="-个人";
                    }
                }
                if($tmp_payment==1) {
                    $payment_name="支付宝".$tmp_wx_source;
                } elseif($tmp_payment==7) {
                    $payment_name="支付宝".$tmp_wx_source."+红包";
                }
                $res[$key]['payment_name']=$payment_name;
            }
        }
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }

	public function otherrefundorderlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	import("ORG.Alipay.Page");
    	
    	$where2['o.state'] ='0';
    	$where2['o.payment'] =array('neq','1');
    	$where2['o.status'] ='4';
    	//$where2['o.pay_price'] =array('gt','0');
		$model = new Model();


		//满足条件的总记录数
    	$count  = $model->table('baby_order as o')
    					->join(" left join baby_business_new as b on o.business_id=b.id")
    					->join(" left join baby_user as u on o.user_id=u.id")
    					->where($where2)
    					->count();
    	$page = new  Page($count,30);//实例化分页类，传入总记录数
    	$show = $page->show(); //分页显示输出


		$res = $model->table('baby_order as o')
    				->join(" left join baby_business_new as b on o.business_id=b.id")
    				->join(" left join baby_user as u on o.user_id=u.id")
    				->where($where2)
    				->field('o.id,o.user_id,u.nick_name,o.payment,o.status,o.pay_price,o.business_id,b.business_title,o.order_num,o.post_create_time')
    				->order('o.create_time desc')
    				->limit($page->firstRow,$page->listRows)
    				->select();
    	$this->assign('res',$res);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display();	
	}
	public function wxrefundorderlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	import("ORG.Alipay.Page");
    	
    	$where2['o.state'] ='0';
    	//$where2['o.payment'] ='2';
    	$where2['o.payment'] =array('in','2,8');
    	//$where2['o.status'] ='4';
    	//$where2['o.pay_price'] =array('gt','0');
		$model = new Model();
		$order_id=trim(I("order_id"));
		if($order_id) {
			$where2['o.id'] =$order_id;
			$this->assign('order_id',$order_id);
		}
		$order_num=trim(I("order_num"));
		if($order_num) {
			$where2['o.order_num'] =array('like','%'.$order_num.'%');;
			$this->assign('order_num',$order_num);
		}
		$order_status=trim(I("order_status"));
		if($order_status) {
			$where2['o.status'] =$order_status;
			$this->assign('order_status',$order_status);
		}


		//满足条件的总记录数
    	$count  = $model->table('baby_order as o')
    					->join(" left join baby_business_new as b on o.business_id=b.id")
    					->join(" left join baby_user as u on o.user_id=u.id")
    					->where($where2)
    					->count();
    	$page = new  Page($count,30);//实例化分页类，传入总记录数
    	$show = $page->show(); //分页显示输出


		$res = $model->table('baby_order as o')
    				->join(" left join baby_business_new as b on o.business_id=b.id")
    				->join(" left join baby_user as u on o.user_id=u.id")
    				->where($where2)
    				->field('o.id,o.user_id,u.nick_name,u.email,o.payment,o.status,o.pay_price,o.business_id,b.business_title,o.order_num,o.post_create_time,o.trade_no,o.wx_source')
    				->order('o.post_create_time desc')
    				->limit($page->firstRow,$page->listRows)
    				->select();
    	$this->assign('res',$res);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display();	
	}
	
	public function wxupdate_order(){
		$order_id=I("id");
		if($order_id) {
			$orderList = M('order')->where('id='.$order_id)->find();
		}
		
		$this->assign('orderList',$orderList);

		
		$this->display('wxpublicorder');
	}
	/*修改微信订单*/
    public function  wxupdate(){

        $order_id = I('id');
        $status = I('status');
        $data['status'] = $status;
        $data['verification'] = '';
        $data['post_create_time'] = date("Y-m-d H:i:s");
        if($status=='5') {
            if($order_id) {
                $check_order = M('order')->where("id=".$order_id)->find();
                $packet_id=$check_order['packet_id'];
            }
            if($packet_id>0) {
                $packet_data['is_grab_use'] = '0';
                M('packet')->where("id=".$packet_id)->data($packet_data)->save();
            }
            $data['verification'] = '';
        }        
        if($order_id) {
        	$where['id'] = $order_id;
        	$result = M('order')->where($where)->data($data)->save();
        }        
        header("Location:http://checkpic.meimei.yihaoss.top/Index/wxrefundorderlist.html");
    }
    /*订单列表*/
    public function orderlist(){
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
		$user_id=trim(I("user_id"));
		if($user_id) {
			$where2['o.user_id'] =$user_id;
			$this->assign('user_id',$user_id);
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
    	$count  = $model->table('baby_order as o')
    					->join(" left join baby_business_new as b on o.business_id=b.id")
    					->join(" left join baby_user as u on o.user_id=u.id")
    					->join(" left join baby_user as s on o.seller_id=s.id")
    					->where($where2)
    					->count();
    	$page = new  Page($count,30);//实例化分页类，传入总记录数
    	$show = $page->show(); //分页显示输出


		$res = $model->table('baby_order as o')
    				->join(" left join baby_business_new as b on o.business_id=b.id")
    				->join(" left join baby_user as u on o.user_id=u.id")
    				->join(" left join baby_user as s on o.seller_id=s.id")
    				->where($where2)
    				->field('o.id,o.order_role,o.baby_name,o.babys_idol_id,o.user_id,u.nick_name,u.email,o.seller_id,s.nick_name as seller_name,s.email as seller_email,b.business_contact,o.payment,o.status,o.price,o.pay_price,o.business_id,b.business_title,o.order_num,o.post_create_time,o.is_communication,b.business_location,u.mobile,o.wx_source')
    				->order('o.post_create_time desc')
    				->limit($page->firstRow,$page->listRows)
    				->select();
    	foreach($res as $key=>$value) {
    		$order_role=$value['order_role'];
    		if($order_role==1) {
    			$res[$key]['business_id']=$value['babys_idol_id'];
    			$res[$key]['business_title']=$value['baby_name'];
    		}
    		$tmp_payment=$value['payment'];
            $tmp_wx_source=$value['wx_source'];
            if($tmp_wx_source==4) {
                $tmp_wx_source="-企业";
            } else {
                $tmp_wx_source="-个人";
            }
            if($tmp_payment==1) {
                $payment_name="支付宝".$tmp_wx_source;
            } elseif($tmp_payment==2) {
                $payment_name="微信";
            } elseif($tmp_payment==5) {
                $payment_name="免费";
            } elseif($tmp_payment==6) {
                $payment_name="红包";
            } elseif($tmp_payment==7) {
                $payment_name="支付宝".$tmp_wx_source."+红包";
            } elseif($tmp_payment==8) {
                $payment_name="微信+红包";
            } elseif($tmp_payment==9) {
                $payment_name="扫码";
            } else {
                $payment_name="其他";
            }
            $res[$key]['payment_name']=$payment_name;
    	}
    	$this->assign('res',$res);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display();	
	}

/*编辑订单列表页面*/
	public function update_order(){
		$order_id=I("id");
		$business_type=I("business_type");
		if($order_id) {
			if($business_type==2) {
				$orderList = M('order2')->where('id='.$order_id)->find();
			} elseif($business_type==1) {
				$orderList = M('order')->where('id='.$order_id)->find();
			}
			
		}
		$this->assign('business_type',$business_type);
		$this->assign('orderList',$orderList);		
		$this->display('publicorder');
	}
	/*修改订单列表*/
    public function  updateOrder(){

        $order_id = I('id');
        $status = I('status');
        $business_type=I("business_type");
        $data['status'] = $status;
        if($status=='5') {
        	if($order_id) {
        		if($business_type==2) {
        			$check_order = M('order2')->where("id=".$order_id)->find();
        		} elseif($business_type==1) {
        			$check_order = M('order')->where("id=".$order_id)->find();
        		}
	        	
	        	$packet_id=$check_order['packet_id'];
	        }
	        if($packet_id>0) {
	        	$packet_data['is_grab_use'] = '0';
	        	M('packet')->where("id=".$packet_id)->data($packet_data)->save();
	        }
	        $data['verification'] = '';
        }
        
        $data['is_communication'] = I('is_communication');
        $data['communication'] = I('communication');
        $data['post_create_time'] = date("Y-m-d H:i:s");
        if($order_id) {
        	 $where_condition['id'] = $order_id;
        	 
        } 
        if($business_type==2) {
        	M('order2')->where($where_condition)->data($data)->save();      
        	header("Location:http://checkpic.meimei.yihaoss.top/Others/virtualorderlist.html");
        } elseif($business_type==1) {
        	M('order')->where($where_condition)->data($data)->save();      
        	header("Location:http://checkpic.meimei.yihaoss.top/Index/orderlist.html");
        }
        
    }

    //获取商家城市筛选
    public function getRegion(){
        $county_id=I("id");
        if($county_id) {
        	$where['county_id'] = $county_id;
        }
        $cityList = M('citys_regions') 
            ->field('id as id,region as city_name')
            ->where($where)
            ->select();
        
        echo json_encode($cityList);
    }

    public function mainheadlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$where2['state'] ='0';
    	//$where2['online'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_main_head' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_main_head' => 'business'))
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
					}
					
							
					$res[]=array(
					'id'=>$id,
					'img'=>$album_img,
					'name'=>$name
					);
			}
	
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('mainheadlist');
	
	
	}

	public function operator_main_head(){
    	
    	$id = $_GET['id'];
		$album = M('main_head');
		$where['id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/mainheadlist.html");
    }
    public function mainhead(){
		$businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
		$this->display('mainhead');
	}

	public function newhead(){
		$businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
		
		$this->display('newhead');
	}
	public function newheadlist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$where2['state'] ='0';
    	//$where2['online'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_new_head' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出


		$list = $model->table(array('baby_new_head' => 'business'))
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
					}
					
							
					$res[]=array(
					'id'=>$id,
					'img'=>$album_img,
					'name'=>$name
					);
			}
	
    	$this->assign("res",$res);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('newheadlist');	
	}
	public function operator_new_head(){
    	
    	$id = $_GET['id'];
		$album = M('new_head');
		$where['id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/newheadlist.html");
    }

    public function operator_special_title(){
    	
    	$id = $_GET['id'];
		$album = M('special');
		$where['id'] = array('in',$id);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/specialtitlelist.html");
    }

    public function specialtitlelist(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	$where2['state'] ='0';
    	//$where2['online'] ='0';
		$model = new Model();


		//满足条件的总记录数
    		$count  = $model->table(array('baby_special' => 'business'))
    						->where($where2)
    						->count();
    		$page = new  Page($count,50);//实例化分页类，传入总记录数
    		$show = $page->show(); //分页显示输出

		$list= $model->table(array('baby_special' => 'business'))
    						->where($where2)
    						->field('business.id,business.special_name,business.special_type,business.sort')
    						->limit($page->firstRow,$page->listRows)
    						->order("business.id desc ")
    						->select();
    	$this->assign("res",$list);
    	$this->assign('list',$list);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display('specialtitlelist');
	
	
	}
	public function specialtitle(){
		$special_id=I("id");
		if($special_id) {
			$specialList = M('special')->where('id='.$special_id)->find();
			$this->assign('specialList',$specialList);
		}
		/*$businessList = M('business')->field('id,business_title')->where("state=0")->order('id desc')->select();
        //赋值数据集*/
		$this->display('specialtitle');
	}

	/*订单列表*/
    public function orderlist17(){
		$this->checkSession();	
    	import("ORG.Util.Page");
    	import("ORG.Alipay.Page");
    	
    	$where2['o.state'] ='0';
		$model = new Model();


		//满足条件的总记录数
    	$count  = $model->table('baby_order17 as o')
    					->join(" left join baby_business as b on o.business_id=b.id")
    					->join(" left join baby_user as u on o.user_id=u.id")
    					->join(" left join baby_user as s on o.seller_id=s.id")
    					->where($where2)
    					->count();
    	$page = new  Page($count,30);//实例化分页类，传入总记录数
    	$show = $page->show(); //分页显示输出


		$res = $model->table('baby_order17 as o')
    				->join(" left join baby_business as b on o.business_id=b.id")
    				->join(" left join baby_user as u on o.user_id=u.id")
    				->join(" left join baby_user as s on o.seller_id=s.id")
    				->where($where2)
    				->field('o.id,o.user_id,u.nick_name,u.email,o.seller_id,s.nick_name as seller_name,s.email as seller_email,b.business_contact,o.payment,o.status,o.pay_price,o.business_id,b.business_title,o.order_num,o.post_create_time,o.is_communication,b.business_location')
    				->order('o.post_create_time desc')
    				->limit($page->firstRow,$page->listRows)
    				->select();
    	$this->assign('res',$res);//赋值数据集
    	$this->assign('page',$show);//赋值分页输出
    	$this->display();	
	}

	/*编辑订单列表页面*/
	public function update_order17(){
		$order_id=I("id");
		if($order_id) {
			$orderList = M('order17')->where('id='.$order_id)->find();
		}
		
		$this->assign('orderList',$orderList);

		
		$this->display('publicorder17');
	}
	/*修改订单列表*/
    public function  updateOrder17(){

        $order_id = I('id');
        $status = I('status');
        $data['status'] = $status;
        if($status=='5') {
        	$data['verification'] = '';
        }
        $data['post_create_time'] = date("Y-m-d H:i:s");
        if($order_id) {
        	 $where['id'] = $order_id;
        	 $result = M('order17')->where($where)->data($data)->save();
        }        
        header("Location:http://checkpic.meimei.yihaoss.top/Index/orderlist17.html");
    }
    public function specaillist(){
		import("ORG.Util.Page");
		//先查相册的数据库，
    	$user_album = M('show_cate');
		
    	$where2['state'] ='0';//图片状态正常
		$count  =$user_album ->where($where2)->count();
		$page = new Page($count,50);
		$show = $page->show();		
		$res=array();	
		$datas = $user_album
					  ->where($where2)
					  ->order('id desc')
					  ->limit($page->firstRow,$page->listRows)
					  ->select();
    	$this->assign("res",$datas);
    	$this->assign('page',$show);
    	$this->display('specaillist');
	
	
	}
	//添加话题--类似文章
    public function publicpostimg() {
        $groupList = M('post_group')->field('id,group_name')->where("state='0'")->order('id desc')->select();
        $this->assign('groupList',$groupList);//赋值数据集
        $businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    public function publicpostlist(){
        $this->checkSession();  
        import("ORG.Util.Page");
        $img_description = I('img_description');        
        if(!empty($img_description)) {
        	$where_condition=" and img_description like '%$img_description%' ";
        } else {
        	$where_condition="";
        }
        //先查相册的数据库，
        $post_img = M('post_img');
        $model = new Model();
        //满足条件的总记录数
        $count  = $post_img->where("state='0' and root_img_id is null ".$where_condition)->count();
        $page = new  Page($count,50);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $list = $post_img
                ->where("state='0' and root_img_id is null ".$where_condition)
                ->field('id,user_id,img_description,is_recommend,img,post_create_time,post_class,post_cate_id,link_state,link_nick_name')
                ->order('create_time desc')
                ->limit($page->firstRow,$page->listRows)
                ->select(); 
        $res=array();
        foreach($list as $key=>$value){
            $id = $value['id'];
            $tmpImg = $value['img'];            
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
                    $imgUrl[]="http://api.meimei.yihaoss.top/".substr($tmpImg, 0,-4);
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
                'post_cate_id'=>$post_cate_id        
            );
        }
        
        //print_r($res);exit;
        $this->assign("res",$res);
        $this->assign('list',$list);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();
    }
    public function replaypostimg(){
		$this->display();
	}
	//群删除
	public function operator_groupstate(){
    	
    	$imgid = $_GET['id'];
		$album = M('post_group');
		$where['id'] = array('in',$imgid);
		$data['state'] = '1' ;
		$result = $album->where($where)->data($data)->save();
		header("Location:http://checkpic.meimei.yihaoss.top/Index/postgrouplist.html");
    }
    public function albumreviewlist(){
    	$temp_img_id=I("img_id");
        import("ORG.Util.Page");
        //先查相册的数据库，
        $user_album = M('review');
        
        $where2['is_del'] ='0';//图片状态正常
        if($temp_img_id>0) {
        	$where2['img_id']=$temp_img_id;
        }
        
        $count  =$user_album ->where($where2)->count();
        $page = new Page($count,50);
        $show = $page->show();
        
        $res=array();   
        $datas = $user_album ->field('id,demand,user_id')
                      ->where($where2)
                      ->order('id desc')
                      ->limit($page->firstRow,$page->listRows)
                      ->select();          
        foreach ($datas as $key => $data){
            $users = M('user');
            
            $img_id = $data['id'];
            $user_id = intval($data['user_id']);

            $condition['id'] = $user_id;
            $users = $users->where($condition)->find();
            $nick_name = $users['nick_name'];     
            $res[]=array(
                'id' =>$img_id,
                'demand'=>$data['demand'],
                'user_id'=>$user_id,
                'nick_name'=>$nick_name
            );
        }
        $this->assign("res",$res);
        $this->assign('page',$show);
        $this->display();    
    }
    //秀秀评论删除
    public function operator_albumreviewstate(){
        $imgid = $_GET['id'];
        $album = M('review');
        $where['id'] = array('in',$imgid);
        $result = $album->where($where)->find();
        $img_id=$result['img_id'];
        if($img_id>0) {
            $albumInfo=M("album_img")->where("id=$img_id")->find();
            $get_review_count=$albumInfo['review_count'];
            if($get_review_count<1) {
                $review_count=0;
            } else {
                $review_count=$get_review_count-1;
            }
            $album_data['review_count']=$review_count;
            M("album_img")->where("id=$img_id")->data($album_data)->save();
        }
        $data['is_del'] = '1' ;
        $album->where($where)->data($data)->save();
        header("Location:http://checkpic.meimei.yihaoss.top/Index/albumreviewlist?img_id=$img_id");
    }
    public function publicgroup(){
    	$businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
    	$this->display();
    }
    public function webPublicOrder(){
        $this->display();
    }
    public function editgroup() {
    	$group_id=I('id');
    	if($group_id>0) {
    		$data['id']=$group_id;
    		$groupList = M('post_group')->where($data)->find();
    		$cover=$groupList['cover'];
    		if($cover) {
    			$groupList['cover']="http://api.meimei.yihaoss.top/".$cover;
    		}
    		$user_id=intval($groupList['user_id']);
    		if($user_id>0) {
    			$user_data['id']=$user_id;
    			$userList = M('user')->field('user_role')->where($user_data)->find();
    			$tmpuserRore=intval($userList['user_role']);
    		}
    		if($tmpuserRore==3) {
    			$is_user=1;
    		} else {
    			$is_user=0;
    		}
    		$groupList['is_user']=$is_user;
    		$this->assign('specialList',$groupList);//赋值数据集
    	}
        $businessList = M('business_new')->field('id,business_title')->where("state=0")->order('id desc')->select();
        $this->assign('businessList',$businessList);//赋值数据集
        $this->display();
    }
    public function groupIdol(){
    	$group_id=I("group_id");
        $this->assign('group_id',$group_id);
        $this->display();    
    }
    /*订单数量*/
    public function orderCount(){
        $this->checkSession();  
        import("ORG.Util.Page");
        import("ORG.Alipay.Page");
        
        $where2['o.state'] ='0';
        $model = new Model();
        //满足条件的总记录数
        $Allcount= $model->table('baby_order as o')
                        ->join(" left join baby_user as u on o.user_id=u.id")
                        ->where($where2)
                        ->group("o.user_id")
                        ->select();
        $count=count($Allcount);
        $page = new  Page($count,30);//实例化分页类，传入总记录数
        $show = $page->show(); //分页显示输出
        $res = $model->table('baby_order as o')
                    ->join(" left join baby_user as u on o.user_id=u.id")
                    ->where($where2)
                    ->group("o.user_id")
                    ->field('o.user_id,u.nick_name,u.mobile,count(o.user_id) as user_count')
                    ->order('user_count asc,o.id desc')
                    ->limit($page->firstRow,$page->listRows)
                    ->select();
        $this->assign('res',$res);//赋值数据集
        $this->assign('page',$show);//赋值分页输出
        $this->display();   
    }
    
}
