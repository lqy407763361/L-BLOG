<?php
namespace app\index\model;

use think\Model;
use think\Db;

class User extends Model{
	//插入数据
	public static function getUserOne($name){
		$user_detail = Db::name('user')->field('id, name, password, status')->where("name = '".$name."'")->find();

		return $user_detail;
	}

	//添加用户
	public static function addUser($data){
		$user_id = Db::name('user')->insertGetId($data);

		return $user_id;
	}

	//登录后编辑
	public static function editUser($data){
		$result = Db::name('user')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}
}