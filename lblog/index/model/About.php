<?php
namespace app\index\model;

use think\Model;
use think\Db;

class About extends Model{
	//获取数据
	public static function getAbout(){
		$result = Db::name('about')->where('id = 1')->find();

		return $result;
	}

	//插入数据
	public static function sendMessage($data){
		$message_id = Db::name('message')->insertGetId($data);

		return ($message_id > 0) ? true : false;
	}
}