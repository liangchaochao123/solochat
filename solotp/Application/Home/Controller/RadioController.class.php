<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use PDO;
use Session;

class RadioController extends Controller {
	
	
	/*
		首页展示
		radio 电台列表
		返回数据类型 json
	*/
    public function radio_lists(){
		/*
		电台列表展示收听人数前5位
	    */
	    $sql_radio="select count(radio.radio_id) as radio_listen, user.user_id,user.avatar,radio.radio_id,radio.radio_url,radio.radio_name from user_radio inner JOIN user on user_radio.user_id=user.user_id INNER JOIN radio on user_radio.radio_id=radio.radio_id where radio_listen_status=1 group by user_radio.radio_id ORDER BY radio_listen desc limit 5";
		// $radio=$this->db($sql_radio);
		$radio=A('Db')->db_all($sql_radio);
		// $radio=$pdo->query($sql_radio)->fetchAll(PDO::FETCH_ASSOC);
		// echo count($radio);die;
		// print_r($radio);die;
		if($radio){	
			/*
				1 成功
				0 失败
			*/
			$radio_lists=array(
				"code"=>1,
				"message"=>"ok",
				"radio"=>$radio,
			);
			echo json_encode($radio_lists);
		}else{
			$login=array(
				"code"=>0,
				"message"=>"fail",
				"radio"=>$radio,
			);
			echo json_encode($login);
		}   
	}
	/*
		radio 电台收听
		返回数据类型 json
	*/
	public function radio_listen(){
		/*
			获取客户端数据
			用户id从session中获取
			radio_id  电台id
		*/
		$put=file_get_contents('php://input');
		$listen=json_decode($put,true);
		// print_r($listen);die;
		$user_id=$listen["user_id"];
		$radio_id=$listen["radio_id"];
		/*
			判断用户是否登录，没有登陆直接播放
			用户登陆直接入库
		*/
		if(empty($user_id)){
			$listen=array(
				"code"=>1,
				"message"=>"radio_listen no_login",
				"user"=>"",
			);
		    echo json_encode($listen);
		}else{
			/*
			是否收听过，表中是否存在收听记录
			*/
			$sql_listen="select * from user_radio where user_id=$user_id and radio_id=$radio_id";
			// echo $sql;
			$into_listen=A("Db")->db_all($sql_listen);
			// print_r($into_listen);
			if($into_listen){
				/*
					1  正在收听
					0  未在收听
				*/
				if($into_listen["radio_listen_status"]==1){
					$listen=array(
						"code"=>1,
						"message"=>"radio_listen ok",
						"user"=>$into_listen,
					);
				    echo json_encode($listen);
				}else{
					/*
						改为收听状态
					*/
					$sql_listen_status="update user_radio set radio_listen_status=1 where user_id=$user_id and radio_id=$radio_id";
					// echo $sql;
					$listen_status=A("Db")->db_exec($sql_listen_status);
					if($listen_status){
						$listen=array(
							"code"=>1,
							"message"=>"radio_listen ok",
							"user"=>$listen_status,
						);
						echo json_encode($listen);
					}
				}
				
			}else{
				/*
					在表中记录收听的电台
				*/
				$sql_listen="insert into user_radio(user_id,radio_id) values($user_id,$radio_id)";
				// echo $sql;die;
				$listen=A("Db")->db_exec($sql_listen);
				if($listen){
					/*
						改为收听状态
					*/
					$sql_listen="update user_radio set radio_listen_status=1 where user_id=$user_id and radio_id=$radio_id";
					$listen_status=A("Db")->db_exec($sql_listen_status);
					$listen=array(
						"code"=>1,
						"message"=>"radio_listen ok",
						"user"=>$into_listen,
					);
					echo json_encode($listen);
				}
			}
		}

	}
	/*
		radio 电台取消收听
		返回数据类型 json
		传入用户id和电台id
	*/
	public function radio_unlisten(){
		/*
			获取客户端数据
			user_id   用户id
			radio_id  电台id
			radio_listen_status 收听的状态
		*/
		$put=file_get_contents('php://input');
		$unlisten=json_decode($put,true);
		// print_r($unlisten);die;
		$user_id=$unlisten["user_id"];
		$radio_id=$unlisten["radio_id"];
		/*
			取消收听删除收听表（user_radio）中数据
			1  取消成功
			0  取消失败
		*/
		if(empty($user_id)){
			$data=array(
				"code"=>1,
				"message"=>"radio_unlisten no_login",
				"user"=>"",
			);
			echo json_encode($data);
		}else{
			$sql_unlisten="update user_radio set radio_listen_status=0 where user_id=$user_id and radio_id=$radio_id";
			// echo $sql_unlisten;die;
			$out_unlisten=A("Db")->db_exec($sql_unlisten);
			// print_r($out_unlisten);
			
			if($out_unlisten){
				$data=array(
					"code"=>1,
					"message"=>"ok",
					"user"=>$unlisten,
				);
				echo json_encode($data);
				
			}else{
				$data=array(
					"code"=>0,
					"message"=>"unlisten fail",
					"user"=>$unlisten,
				);
				echo json_encode($data);
			}

		}
		
	}
	/*
		redio 电台收藏
		1  已收藏
		0  未收藏
	*/
		public function radio_favourite(){
			$put=file_get_contents('php://input');
			$favourite=json_decode($put,true);
			// print_r($favourite);die;
			$user_id=$favourite["user_id"];
			$radio_id=$favourite["radio_id"];
			/*
				判断用户是否登陆
			*/
			if(empty($user_id)){
				$this->error("please login");
			}else{
				/*
				改为收藏状态
				*/
				$sql_favourite_status="update user_radio set radio_favourite_status=1 where user_id=$user_id and radio_id=$radio_id";
				// echo $sql_favourite_status;die;
				$favourite_status=A("Db")->db_exec($sql_favourite_status);
				if($favourite_status){
					$favourite=array(
						"code"=>1,
						"message"=>"radio_favourite ok",
						"user"=>$favourite_status,
					);
					echo json_encode($favourite);
				}else{
					$favourite=array(
						"code"=>0,
						"message"=>"radio_favourite fail",
						"user"=>$favourite_status,
					);
					echo json_encode($favourite);
				}
			}
			

		}

	/*
		redio 电台取消收藏
	*/
		public function radio_unfavourite(){
			$put=file_get_contents('php://input');
			$favourite=json_decode($put,true);
			// print_r($favourite);die;
			$user_id=$favourite["user_id"];
			$radio_id=$favourite["radio_id"];
			/*
				判断用户是否登录
			*/
			if(empty($user_id)){
				$this->error("please login");
			}else{
				/*
				改为未收藏状态
				*/
				$sql_unfavourite_status="update user_radio set radio_favourite_status=0 where user_id=$user_id and radio_id=$radio_id";
				// echo $sql_unfavourite_status;die;
				$unfavourite_status=A("Db")->db_exec($sql_unfavourite_status);
				if($unfavourite_status){
					$favourite=array(
						"code"=>1,
						"message"=>"ok",
						"user"=>$unfavourite_status,
					);
					echo json_encode($favourite);
				}else{
					$favourite=array(
						"code"=>0,
						"message"=>"fail",
						"user"=>$unfavourite_status,
					);
					echo json_encode($favourite);
				}
			}
			
		}
		/*
			电台点赞
		*/
		public function radio_likes(){
			$put=file_get_contents('php://input');
			$likes=json_decode($put,true);
			// print_r($likes);
			$radio_id=$likes["radio_id"];
			/*
				判断用户是否登陆
			*/
			if(empty($user_id)){
				$this->error("please login");
			}else{
				/*
					点赞+1
				*/
				$sql_likes="update radio set radio_praise_num=radio_praise_num+1 where radio_id=$radio_id";
				// echo $sql_likes;die;
				$data_likes=A("Db")->db_exec($sql_likes);
				// print_r($data_likes);die;
				if($data_likes){
					/*
						返回点赞总数量
					*/
					$sql_likes_num="select radio_praise_num from radio where radio_id=$radio_id";
					$data_likes_num=A("Db")->db_one($sql_likes_num);
					// print_r($data_likes_num);die;
					$return_likes=array(
						"code"=>1,
						"message"=>"ok",
						"user"=>$data_likes,
					);
					echo json_encode($return_likes);
				}else{
					$return_likes=array(
						"code"=>0,
						"message"=>"fail",
						"user"=>$data_likes,
					);
					echo json_encode($return_likes);
				}
			}
			
		}


	
	
}