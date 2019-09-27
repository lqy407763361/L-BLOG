<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class VisitRecord extends Model{
	//获取该文章访问量
	public static function getArticleVisitSum($id){
		$total = Db::name('visit_record')->field('id')->where("article_id = $id")->count();

		return $total;
	}

	//根据一周获取文章访问量
	public static function getVisitSumByWeek($filter){
		$total = Db::name('visit_record')->field('id')->where("article_id = 0 AND add_time >= ".$filter['start_time']." AND add_time < ".$filter['end_time'])->count();

		return $total;
	}
}