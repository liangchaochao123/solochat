<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use PDO;
/*
	用户第三方资料入库
*/
class LoginController extends Controller {

    public function index(){

    	// echo $_POST;die;
    	// print_r($_POST);die;
    	// echo $_POST['token'];
    	/*
			生成token
		*/
			/*
				1.去除非空的字段
			*/
			foreach ($_POST['post'] as $key => $value) {         
				if($value=="")
				{   
					unset($_POST['post'][$key]);
				}
			}
			/*
				2.根据键值进行排序
			*/
			ksort($_POST['post']);//排序
			/*
				3.字符串拼接 key=value &
			*/
			$token="";
			foreach ($_POST['post'] as $key => $value)
			{
				$token.=$key.'='.$value.'&';
			}
			// echo $token;die;
			/*
				mac 自己定义的加密方式
			*/
			$mac=md5('solochat-PHP');
			$token.=$mac;
			// echo $token;die;
			if(md5($token)==$_POST['token']){
				/*
					pdo连接	
					servername 服务器ip
					username   数据库账号
					password   数据库密码
		    	*/
				$servername = $_SERVER['SERVER_ADDR'];
				// echo $servername;die;
				$username = "root";
				$password = "root";
				$pdo = new PDO("mysql:host=$servername;dbname=solo;", $username, $password);
			    // print_r($pdo);die;
			    $pdo->exec('set names utf-8');
			    
			    $nickname=$_POST['post']['nickname'];
			    $avatar=addslashes($_POST['post']['avatar']);
			    $platform=$_POST['post']['platform'];
			    $openid=md5($_POST['post']['openid']);
			    $gender=$_POST['post']['gender'];
			    $location=$_POST['post']['location'];
			    $description=$_POST['post']['description'];
			    /*
					查看用户是否为会员
			    */
			    $sql_user="select * from user where openid='$openid'";
				$user=$pdo->query($sql_user)->fetch(PDO::FETCH_ASSOC);
				// echo $user;die;print
				// print_r($user);die;
					// var_dump($user);die;
				if($user){	
					$login=array(
							"code"=>1,
							"message"=>"ok",
							"user"=>$user,
							);
						echo json_encode($login);
				}else{
					$sql="insert into user (nickname,avatar,platform,openid,gender,location,description) values('$nickname','$avatar','$platform','$openid','$gender','$location','$description')";
				    // echo $sql;die;
				    
					$res=$pdo->exec($sql);
					// echo $res;die;
					// var_dump($res);die;
					if($res){
						/*
							openid 根据唯一标识查询刚入库的成员信息
						*/
						$sql_user="select * from user where openid='$openid'";
						$user=$pdo->query($sql_user)->fetch(PDO::FETCH_ASSOC);
						// print_r($user);die;
						$login=array(
							"code"=>1,
							"message"=>"ok",
							"user"=>$user,
							);
						echo json_encode($login);
					}
				}
				
			}else{
				$login=array(
						"code"=>0,
						"message"=>"fail",
						"user"=>"",
						);
					echo json_encode($login);
			}
    	
    }
}