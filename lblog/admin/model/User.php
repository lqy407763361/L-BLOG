<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class User extends Model{
	//获取数据列表
	public static function getUserList($filter){
		$where = '1=1';
		$order = 'status DESC, id ASC';

		if(!empty($filter['search_name'])){
			$where = "POSITION('".$filter['search_name']."' IN name)";
		}
		if($filter['search_status'] != ''){
			$where .= " AND status = ".$filter['search_status'];
		}

		if(!empty($filter['sort'])){
			$order = array($filter['field'] => $filter['sort']);
		}

		$result = Db::name('user')->field('id, name, status, register_time, next_login_time')->where($where)->order($order)->paginate($filter['size']);

		return $result;
	}

	//获取单条数据
	public static function getUserOne($id){
		$result = Db::name('user u')
            ->join('register_type rt','u.register_type_id = rt.id', "left")
            ->field('u.*, rt.name as register_type_name')
            ->where("u.id = $id")
            ->find();

		return empty($result) ? false : $result;
	}

	//获取数量
	public static function getUserCount($filter = array()){
		$where = '1=1';
		if(!empty($filter['get_new_user']) && $filter['get_new_user'] == true){
			$month_time = time()-2592000;
			$where = "register_time > $month_time";
		}
		$total = Db::name('user')->field('id')->where($where)->count();
		
		return $total;
	}

	//添加
	public static function addUser($data){
		$user_id = Db::name('user')->insertGetId($data);

		return ($user_id > 0) ? true : false;
	}

	//编辑
	public static function editUser($data){
		$result = Db::name('user')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}

	//删除
	public static function deleteUser($id){
		return Db::name('user')->delete($id);
	}
}