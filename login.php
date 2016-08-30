<?php
header('Content-Type: text/html; charset=utf-8');
	//设置post的数据 
	$post = array(
		array ( 
		    'nickname' => 'chahen', 
		    'avatar' => 'F:\picture\00 (1).jpg', 
		    'platform' => 'facebook' ,
		    'openid'=>'1',
		    'gender'=>"G",
		    'location'=>"beijing",
		    'description'=>"boss",
		),
		array ( 
		    'nickname' => 'biaoque', 
		    'avatar' => 'F:\picture\00 (2).jpg', 
		    'platform' => 'facebook' ,
		    'openid'=>'2',
		    'gender'=>"G",
		    'location'=>"beijing",
		    'description'=>"leader",
		),
		array ( 
		    'nickname' => 'xiaoer', 
		    'avatar' => 'F:\picture\00 (3).jpg', 
		    'platform' => 'facebook' ,
		    'openid'=>'3',
		    'gender'=>"M",
		    'location'=>"beijing",
		    'description'=>"staff",
		),
		array ( 
		    'nickname' => 'pander', 
		    'avatar' => 'F:\picture\00 (4).jpg', 
		    'platform' => 'facebook' ,
		    'openid'=>'4',
		    'gender'=>"G",
		    'location'=>"beijing",
		    'description'=>"staff",
		),
	);
// print_r($post);die;
/*
	生成token
*/
	/*
		1.去除非空的字段
	*/
	foreach ($post[2] as $key => $value) {         
		if($value=="")
		{   
			unset($post[2][$key]);
		}
	}
	/*
		2.根据键值进行排序
	*/
	ksort($post[2]);//排序
	/*
		3.字符串拼接 key=value &
	*/
	$token="";
	foreach ($post[2] as $key => $value)
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
	/*
		body  传输数组
		token 加密的数据
		post  原数据
	*/
	$body=array(
		"token"=>md5($token),
		"post"=>$post[2],
		);
	// echo json_encode($body);
	//登录地址 
	$url = "http://localhost/tp/index.php/Home/login/index"; 
	//模拟登录 
	login_post($url, $body); 
 
	//模拟登录 
	function login_post($url, $body) { 
		// echo 456;
	    $curl = curl_init();//初始化curl模块 
	    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址 
	    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);//是否自动显示返回的信息 
	    curl_setopt($curl, CURLOPT_POST, 1);//post方式提交 
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));//要提交的信息 
	    curl_exec($curl);//执行cURL 
	    curl_close($curl);//关闭cURL资源，并且释放系统资源 
	} 


?>