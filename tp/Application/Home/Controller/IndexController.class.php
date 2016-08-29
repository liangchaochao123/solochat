<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
/*
	首页展示
	radio 电台列表
	room  用户房间列表
	返回数据类型 json
*/
class IndexController extends Controller {

    public function index(){
    	 	$testJSON=array('name'=>'中文字符串','value'=>'test'); 
    	 	foreach($testJSON as $k=>$v){
    	 		$arr[$k]=json_encode($v);
    	 	} 

    	 	// print_r($arr);die;
    	 	// print_r($testJSON);die;
			$aa=json_encode($arr);
			// echo $aa;die;
			// $bb=iconv('gb2312','utf-8',$aa);
			$bb=iconv('UCS-2', 'UTF-8',$aa);
			// echo $bb;die;
			var_dump($bb); 



			die;

        $Model = new Model();
        /*
			电台列表展示
        */
		$sql_radio = "select  count(user_radio.user_id) as radio_listen_num,radio.radio_id,radio_name,radio_img,radio_url from user_radio INNER JOIN user on  user_radio.user_id=user.user_id INNER JOIN radio on user_radio.radio_id=radio.radio_id group by user_radio.radio_id order by radio_listen_num desc ";

		$radio = $Model->query($sql_radio);
		// print_r($radio);die;
		if($radio){
			/*
				成功返回数据
			*/
			$data_radio=array(
				"status"=>2,
				"info"=>"true",
				"list"=>$radio
				);
			echo json_encode($data_radio);
		}else{
			/*
				失败返回数据
			*/
			$data_radio=array(
				"status"=>0,
				"info"=>"false",
				"list"=>"系统繁忙！"
				);
			echo json_encode($data_radio);
		}

		/*
			房间列表展示
		*/
		$sql_room = "select  count(user_room.user_id) as room_listen_num,room.room_id,room_title,room_img,room_url,user.user_name from user_room INNER JOIN user on  user_room.user_id=user.user_id INNER JOIN room on user_room.room_id=room.room_id group by user_room.room_id";

		$room = $Model->query($sql_room);

		if($room){
			/*
				成功返回数据
			*/
			$data_room=array(
				"status"=>2,
				"info"=>"true",
				"list"=>$room
				);
			echo json_encode($data_room);
		}else{
			/*
				失败返回数据
			*/
			$data_room=array(
				"status"=>0,
				"info"=>"false",
				"list"=>"系统繁忙！"
				);
			echo json_encode($data_room);
		}
			
		
    }
    public function in_radio(){

    }



}