<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class SiteConfig extends Model{
	//获取网站配置表
	public static function getSiteConfig(){
		$result = Db::name('site_config')->where('id = 1')->find();

		return $result;
	}

	//编辑网站配置
	public static function editSiteConfig($data){
		$result = Db::name('site_config')->where('id = 1')->setField($data);

		return ($result > 0) ? true : false;
	}
}