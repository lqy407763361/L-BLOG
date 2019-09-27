<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\admin\model\Article as articleModel;
use app\admin\model\Message as messageModel;
use app\admin\model\User as userModel;
use app\admin\model\Admin as adminModel;
use app\admin\model\VisitRecord as visitRecordModel;

class Index extends Base{
	protected function initialize(){
        $this->isLogin();
    }

    public function index(){
    	$result = $this->getSiteInfo();
    	$result['article_count'] = articleModel::getArticleCount();
    	$result['message_count'] = messageModel::getMessageCount();
    	$result['user_count'] = userModel::getUserCount();
    	$result['admin_count'] = adminModel::getAdminCount();

        //根据本周时间戳获取文章访问量
        $start_time = strtotime('this week Monday');
        for($i=0; $i<7; $i++){
            $filter['start_time'] = $start_time + ($i*86400);
            $filter['end_time'] = $start_time + ($i*86400) + 86400;
            $result['visit_sum'][$i] = visitRecordModel::getVisitSumByWeek($filter);
        }

        $this->assign('result', $result);
        return $this->fetch();
    }

    //获得面包屑导航和管理员群组相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '仪表盘';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '仪表盘', 'href' => url('admin/index/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}