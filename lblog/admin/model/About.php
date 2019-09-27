<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class About extends Model{
	//获取网站配置表
	public static function getAbout(){
		$result = Db::name('about')->where('id = 1')->find();

		return $result;
	}

	//编辑网站配置
	public static function editAbout($data){
		$result = Db::name('about')->where('id = 1')->setField($data);

		return ($result > 0) ? true : false;
	}
}