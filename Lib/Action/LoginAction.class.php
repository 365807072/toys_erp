<?php
   class LoginAction extends Action{
   	 
	   	public  function index(){
	   		$this->display('../index.php');
	   	}
	   	
	   	public function login(){   		
	   		$this->display('');
	   	}
   	
	   	/**
	   	 * 确认登录
	   	 */ 
	   	public function confirmLogin(){
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
	   		
	   		if ($username =='admin' && $password == '720515') {

	   			$lifeTime =7*24*60*60;//session有效期

				Session::set('username', $username);
				Session::setExpire($lifeTime);
				cookie('username',$username);
	   			$url = U('Index/index');
	   			//echo $_SERVER['SCRIPT_NAME']."<br/>";
				//echo $url;exit;
				//$url = str_replace('/'.MODULE_NAME.'/'.ACTION_NAME,'',$url);
				//简单的将多余的字符串替换掉，原理不知道
				$url = str_replace('/Index/checkpicture.html','',$url);
				//echo $url;exit;
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
