<?php 
header('Content-Type: text/html; charset=utf-8');
	/*
		电台数据入库
	*/
	//读取文档中数据
	$arr = file("./radio.txt");
	echo "<pre>";
	// 将接送串处理成数组
	foreach ($arr as $key => $value) {
	    $arr[$key]=json_decode($value,true);
	    unset($arr[$key]["_id"]);
	    
	}
	
	// print_r($arr);die;
	// 循环添加入库
	foreach($arr as $k=>$v){
		$radio_url=$v["live_url"];
		$radio_name=$v["name"];
		$radio_img=$v["cover"];
		$category_name=$v["category"];
		$radio_praise_num=0;
		$radio_format=$v["live_format"];
		$radio_location=$v["location"];
		$tag_radio_name=$v["tag"];


			try{  

				$servername = "52.42.54.201";
				$username = "root";
				$password = "root";

			    $pdo = new PDO("mysql:host=$servername;dbname=solo", $username, $password);
			    $sql="insert into radio (radio_url,radio_name,radio_img,category_name,radio_praise_num,radio_format,radio_location,tag_radio_name) values('$radio_url','$radio_name','$radio_img','$category_name','$radio_praise_num','$radio_format','$radio_location','$tag_radio_name')";
			    $res=$pdo->exec($sql);
				echo "数据添加成功，受影响行数为： ".$res.'<br>';  
			}catch(Exception $e){  
				die("Error!:".$e->getMessage().'<br>');  
			} 


	}

		 
?> 
