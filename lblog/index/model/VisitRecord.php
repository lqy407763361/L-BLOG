<?php
namespace app\index\model;

use think\Model;
use think\Db;

class VisitRecord extends Model{
	//获取该IP今日是否访问过
	public static function visitExist($data){
		$id = Db::name('visit_record')->field('id')->where("article_id = ".$data['article_id']." AND visit_ip = '".$data['visit_ip']."' AND add_time >= ".$data['start_time']." AND add_time < ".$data['end_time'])->find()['id'];

		return ($id > 0) ? true : false;
	}

	//用户访问前端，需要做访问量登记，1天1次
	public static function addVisitRecord($data){
		$visit_record_id = Db::name('visit_record')->insertGetId($data);

		return ($visit_record_id > 0) ? true : false;
	}
}