<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use PDO;
use Session;

class DbController extends Controller {
	/*
		db_one   查询一条
		db_all   查询多条
		db_exec  添加、修改、删除
		sql      接收的sql语句
	*/

	public function db_one($sql){
		/*
			pdo连接	
			servername 服务器ip
			username   数据库账号
			password   数据库密码
		*/
		$servername = '127.0.0.1';
		$username = "root";
		$password = "root";
		$pdo = new PDO("mysql:host=$servername;dbname=solo;", $username, $password);
	    // print_r($pdo);die;
	    $pdo->exec('set names utf-8');
	    $data=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
	    return $data;
	}
	public function db_all($sql){
		$servername = '127.0.0.1';
		$username = "root";
		$password = "root";
		$pdo = new PDO("mysql:host=$servername;dbname=solo;", $username, $password);
	    // print_r($pdo);die;
	    $pdo->exec('set names utf-8');
	    $data=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	    return $data;
	}
	public function db_exec($sql){
		$servername = '127.0.0.1';
		$username = "root";
		$password = "root";
		$pdo = new PDO("mysql:host=$servername;dbname=solo;", $username, $password);
	    // print_r($pdo);die;
	    $pdo->exec('set names utf-8');
	    $data=$pdo->exec($sql);
	    // print_r($data);die;
	    return $data;
	}
}





