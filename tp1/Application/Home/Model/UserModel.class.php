<?php
    namespace Home\Model;
    use Think\Model;
    class UserModel extends Model {
    	protected $tableName = 'user'; 
    	public function index(){

    	    $User = M("User"); // 实例化User对象
    		// 查找status值为1name值为think的用户数据 
    		$data = $User->select();
    		return $data;
    	}
    }

?>