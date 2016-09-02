<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use PDO;
use Session;
/*
	用户第三方资料入库
*/
class LoginController extends Controller {

    public function index(){

    	$put=file_get_contents('php://input');
		$array=json_decode($put,true);
		// print_r($array);die;
    	// echo $array;die;
    	// print_r($array);die;
    	// echo $array['token'];
    	/*
			生成token
		*/
			/*
				1.去除非空的字段
			*/
			foreach ($array['post'] as $key => $value) {         
				if($value=="")
				{   
					unset($array['post'][$key]);
				}
			}
			/*
				2.根据键值进行排序
			*/
			ksort($array['post']);//排序
			/*
				3.字符串拼接 key=value &
			*/
			$token="";
			foreach ($array['post'] as $key => $value)
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
			if(md5($token)==$array['token']){

			    $nickname=$array['post']['nickname'];
			    $avatar=addslashes($array['post']['avatar']);
			    $platform=$array['post']['platform'];
			    $openid=md5($array['post']['openid']);
			    $gender=$array['post']['gender'];
			    $location=$array['post']['location'];
			    $description=$array['post']['description'];
			    /*
					查看用户是否为会员
			    */
			    $sql_user="select * from user where openid='$openid'";
			    $user=A('Db')->db_one($sql_user);
				// echo $user;die;print
				// print_r($user);die;
					// var_dump($user);die;
				if($user){	
					/*
						session
						user_id  用户id
						nickname 用户昵称
					*/
					session("user_id",$user['user_id']);
					session("nickname",$user['nickname']);
					$user_id = session('user_id');
					$nickname = session('nickname');
					// echo $user_id;
					// echo $nickname;die;
					$login=array(
						"code"=>1,
						"message"=>"ok",
						"user"=>$user,
					);
					echo json_encode($login);
				}else{
					$sql="insert into user (nickname,avatar,platform,openid,gender,location,description) values('$nickname','$avatar','$platform','$openid','$gender','$location','$description')";
				    // echo $sql;die;
				    $res=A('Db')->db_exec($sql);

					// echo $res;die;
					// var_dump($res);die;
					if($res){
						/*
							openid 根据唯一标识查询刚入库的成员信息
						*/
						$sql_user="select * from user where openid='$openid'";
			    		$user=A('Db')->db_one($sql_user);
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
    /*
		退出登录，清除session
    */
    public function logout(){
    	session(null);
    }
    /*
		用户信息修改
    */

	public function update(){
		/*
			接收用户要修改的信息
		*/
		$put=file_get_contents('php://input');
		$arr=json_decode($put,true);
		// print_r($array);die;
		$user_id=$arr["user_id"];
		$avatar=$arr["avatar"];
		$nickname=$arr["nickname"];
		$gender=$arr["gender"];
		$description=$arr["description"];
		/*
			判断用户是否登陆
		*/
		if(empty($user_id)){
			$update=array(
				"code"=>0,
				"message"=>"login page",
				"user"=>"",
			);
			echo json_encode($update);
		}else{
			/*
				修改的sql语句
			*/
			$sql_update="update user set avatar='$avatar' , nickname='$nickname' , gender='$gender' , description='$description' where user_id=$user_id";
			// echo $sql_update;die;
			$res=A("Db")->db_exec($sql_update);
			// var_dump($res);die;
			if($res){	
				/*
					1 成功
					0 失败
				*/
				$update=array(
					"code"=>1,
					"message"=>"ok",
					"user"=>$res,
				);
				echo json_encode($update);
			}else{
				$update=array(
					"code"=>0,
					"message"=>"fail",
					"user"=>$res,
				);
				echo json_encode($update);
			}
		}
		
	}
}