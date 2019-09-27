<?php
namespace app\index\model;

use think\Model;
use think\Db;

class SiteConfig extends Model{
	//获取网站配置表
	public static function getSiteConfig(){
		$result = Db::name('site_config')->where('id = 1')->find();

		return $result;
	}
}