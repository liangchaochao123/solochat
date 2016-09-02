<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use PDO;
use Session;
/*
	首页展示
	room  用户房间列表
	返回数据类型 json
*/
class RoomController extends Controller {
	/*
		创建房间
	*/
    public function room_create(){
    	/*
			创建房间
			room_title     		房间标题
		    room_img  	   		房间头像
		    user_id 	   		用户id
		    tag_room_name  		房间tag
		    category_name  		房间分类
		    room_create_time	房间创建时间
		*/
    	// echo 456;die;
    	$put=file_get_contents('php://input');
		$create=json_decode($put,true);
		// print_r($create);
		$room_title=$create["room_title"];
		$room_img=$create["room_img"];
		$user_id=$create["user_id"];
		$tag_room_name=$create["tag_room_name"];
		$category_name=$create["category_name"];
		$room_create_time=time();
		/*
			房间创建
		*/
		$sql_room_create="insert into room(room_title,room_img,user_id,tag_room_name,category_name,room_create_time) values('$room_title','$room_img','$user_id','$tag_room_name','$category_name','$room_create_time')";
		// echo $sql_room_create;
		$data_room_create=A('Db')->db_exec($sql_room_create);
		if($data_room_create){
			$login=array(
				"code"=>1,
				"message"=>"room create ok",
				"room"=>$data_room_create,
				);
			echo json_encode($login);
		}else{
			$login=array(
				"code"=>0,
				"message"=>"room create fail",
				"room"=>"",
				);
			echo json_encode($login);
		}
	    
	}
	/*
		首页房间列表展示
	*/
	public function room_lists(){
		/*
			房间列表展示10位
			room_listen    房间正在收听人数
            room_id        房间id
            room_title     房间title
            room_img       房间img
            user_id        用户id
            nickname       昵称
	    */
	    $sql_radio="select count(room.room_id) as room_listen,room.room_id,room.room_title,room.room_img,user.user_id,user.nickname from user_room inner join user on user_room.user_id=user.user_id INNER JOIN room on user_room.room_id=room.room_id  GROUP BY user_room.room_id order by room_listen desc limit 10";
		// $radio=$this->db($sql_radio);
		$room_lists=A('Db')->db_all($sql_radio);
		// echo count($room);die;
		// print_r($radio);die;
		if($room_lists){	
			/*
				1 成功
				0 失败
			*/
			$data=array(
				"code"=>1,
				"message"=>"room lists ok",
				"room"=>$room_lists,
				);
			echo json_encode($data);
		}else{
			$data=array(
				"code"=>0,
				"message"=>"room lists fail",
				"room"=>"",
				);
			echo json_encode($data);
		}
	}
	/*
		用户头像展示
	*/
	public function room_img(){
		/*
			获取客户端数据
			用户id从session中获取
			room_id  房间id
		*/
		$put=file_get_contents('php://input');
		$room_img = json_decode($put,true);
		// print_r($room_img);die;
		$user_id=$room_img["user_id"];
		$room_id=$room_img["room_id"];
		/*
			用户没有登录不能对房间内进行操作
		*/
		if(empty(session("user_id"))){
			// echo 1;die;
			$sql_img="select * from room where room_id=$room_id";
			// echo $sql_img;die;
			$room_img=A('Db')->db_one($sql_img);
			print_r($room_img);
			if($room_img){
				$data=array(
					"code"=>1,
					"message"=>"room_img ok",
					"room"=>$room_img,
					);
				echo json_encode($data);
			}else{
				$data=array(
					"code"=>0,
					"message"=>"room_img fail",
					"room"=>"",
					);
				echo json_encode($data);
			}
		}else{
			// echo 2;die;
			$sql_img="select count(room.room_id) as room_listen,room.room_id,room.room_title,room.room_img,user.user_id,user.nickname,user.avatar from user_room inner join user on user_room.user_id=user.user_id INNER JOIN room on user_room.room_id=room.room_id where room.room_id=$room_id  GROUP BY user_room.room_id order by room_listen desc limit 10";
			// echo $sql_img;die;
			$room_lists=A('Db')->db_all($sql_img);		
			print_r($room_lists);
		}
		
	}
	/*
		用户收听
	*/
	public function room_listens(){
		/*
			获取客户端数据
			用户id从session中获取
			room_id  房间id
		*/
		$put=file_get_contents('php://input');
		$room_listens = json_decode($put,true);
		// print_r($room_listens);die;
		$user_id=$room_listens["user_id"];
		$room_id=$room_listens["room_id"];
		/*
			用户没有登陆直接播放
			用户登陆直接存表
		*/
		if(empty($user_id)){
			$data=array(
					"code"=>1,
					"message"=>"room_listen no_login",
					"room"=>"",
					);
				echo json_encode($data);
		}else{
			$sql_listens="insert into user_room(user_id,room_id) values($user_id,$room_id)";
			// echo $sql_listens;
			$room_img=A('Db')->db_exec($sql_listens);		
			// print_r($room_img);
			if($room_img){
				$data=array(
					"code"=>1,
					"message"=>"room_listen ok",
					"room"=>"",
					);
				echo json_encode($data);
			}else{
				$data=array(
					"code"=>0,
					"message"=>"room_listen fail",
					"room"=>"",
					);
				echo json_encode($data);
			}
		}
	}
	/*
		用户取消收听
	*/
	public function room_unlistens(){
		/*
			获取客户端数据
			用户id从session中获取
			room_id  房间id
		*/
		$put=file_get_contents('php://input');
		$unlistens = json_decode($put,true);
		// print_r($unlistens);die;
		$user_id=$unlistens["user_id"];
		$room_id=$unlistens["room_id"];
		if(empty($user_id)){
			$data=array(
				"code"=>1,
				"message"=>"room_unlisten no_login",
				"room"=>"",
				);
			echo json_encode($data);
		}else{
			/*
				取消收听删除收听表（user_room）中数据
				1  取消成功
				0  取消失败
			*/
			$sql_unlistens="delete  from user_room where user_id=$user_id and room_id=$room_id";
			// echo $sql_unlistens;
			$data_room_unlistens=A("Db")->db_exec($sql_unlistens);
			if($data_room_unlistens){
				$data=array(
					"code"=>1,
					"message"=>"room_unlisten ok",
					"room"=>"",
					);
				echo json_encode($data);
			}else{
				$data=array(
					"code"=>0,
					"message"=>"room_unlisten fail",
					"room"=>"",
					);
				echo json_encode($data);
			}
		}
	}
	/*
		房间点赞
	*/
	public function room_likes(){
		/*
			获取客户端数据
			用户id从session中获取
			room_id  房间id
		*/

		$put=file_get_contents('php://input');
		$room_likes = json_decode($put,true);
		// print_r($room_likes);die;
		$user_id=$room_likes["user_id"];
		$room_id=$room_likes["room_id"];
		/*
			根据session判断用户登录的状态
		*/
		if(empty($user_id)){
			/*
				跳转到登陆页面
			*/

			$this->error("please login");
		}else{
			$sql_likes="update room set room_praise_num=room_praise_num+1 where room_id=$room_id";
			// echo $sql_likes;die;
			$data_room_likes=A("Db")->db_exec($sql_likes);
			if($data_room_likes){
				/*
					点赞成功后将点赞后的数量返还给前台
				*/
				$sql_likes_num="select room_praise_num from room where room_id=$room_id";
				// echo $sql_likes_num;die;
				$room_likes_num=A("Db")->db_one($sql_likes_num);
				// print_r($room_likes_num);die;
				$data=array(
					"code"=>1,
					"message"=>"room_likes ok",
					"room"=>$room_likes_num,
					);
				echo json_encode($data);
			}else{
				$data=array(
					"code"=>0,
					"message"=>"room_likes fail",
					"room"=>"",
					);
				echo json_encode($data);
			}
		}
	}
}