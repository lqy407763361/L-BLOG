<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Login extends Model{
	//根据登录用户名  获取单条数据
	public static function getLoginOne($name){
		$result = Db::name('admin')->field('id, group_id, name, password, status, last_login_time, last_login_ip')->where("name = '".$name."'")->find();

		return empty($result) ? false : $result;
	}

	//添加一条登录的数据
	public static function addLoginRecord($data){
		$login_record_id = Db::name('login_record')->insertGetId($data);

		return ($login_record_id > 0) ? true : false;
	}
}