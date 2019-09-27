<?php
/**
 * L-BLOG
 *
 * @author  lqy407763361
 * @github 	https://github.com/lqy407763361
 * @码云 	https://gitee.com/lqy407763361
 */
namespace app\index\controller;

use think\Controller;
use app\index\model\VisitRecord as visitRecordModel;
use app\index\model\SiteConfig as siteConfigModel;

class Base extends Controller{
	//用户访问前端，需要做访问量登记，1天1次
	//文章统计和页面统计在同一个表，用文章ID区分
	public function visitRecord($article_id = 0){
		$data = array(
			'article_id' => $article_id,
			'visit_ip' => get_ip(),
			'start_time' => strtotime(date('Y-m-d', time())),
			'end_time' => strtotime(date('Y-m-d', strtotime('+1 day'))),
		);
		$is_exist = visitRecordModel::visitExist($data);
		if($is_exist){
            return false;
        }

        $data['add_time'] = time();
        unset($data['start_time']);
        unset($data['end_time']);
        $result = visitRecordModel::addVisitRecord($data);

        return $result;
	}

	//获取系统配置
	public function getSiteConfig(){
		
		return siteConfigModel::getSiteConfig();
	}
}
