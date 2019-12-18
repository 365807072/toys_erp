<?php
   class LoginToAction extends Action{
   	 
	   	public  function index(){
	   		$this->display('../index.php');
	   	}
	   	
	   	
	   	public function loginto(){
	   			   		
	   		$this->display('loginto');
	   	}
   	
	   	/**
	   	 * 确认登录
	   	 */ 
	   	public function confirmLogin1(){
	   		import("ORG.Util.Session");
	   		$username = $_POST['username'];
	   		$password = $_POST['password'];
	   		if (empty($username)) {
	   			echo "<script>alert('用户名不能为空！');history.go(-1);</script>";
	   			exit();
	   		}
	   		if (empty($password)) {
	   			echo "<script>alert('密码不能为空！');history.go(-1);</script>";
	   			exit();
	   		}
			$password = md5(strtoupper(md5($password ))) ;
			$user  = M('user');
			$list = $user->field('id')->where("user_name='{$username}' and password='{$password}' and state='0' ")->order('id')->select(); 
			$userId = $list[0]['id'];
			
			if(!empty($userId)){
					$lifeTime =7*24*60*60;//session有效期

				Session::set('username', $username);
				Session::setExpire($lifeTime);
				cookie('username',$username);
	   			$url = U('Index/index');
				$url = str_replace('/Index/checkpicture.html','',$url);
				$nextUrl = 'http://checkpic.meimei.yihaoss.top/index.php';
				echo "<script>top.location.href=\"$nextUrl\";</script>";
	   		}else {
	   			echo "<script>
	   					alert('您输入的用户名或密码错误！');
	   					history.go(-1);
	   					</script>";
	   			
	   		}

	}
}
