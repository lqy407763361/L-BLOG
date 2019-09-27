<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Article as articleModel;

class Article extends Base{
    public function index(){
        //用户访问前端，需要做访问量登记，1天1次
        $this->visitRecord();

        //网站配置
        $result['site_config'] = $this->getSiteConfig();

        $filter = array(
            'category_id' => (int)input('param.category_id'),
            'size' => $this->getSiteConfig()['site_list_limit'],
        );

    	$result['list'] = articleModel::getArticleList($filter);
    	$result['page'] = $result['list']->render();
    	$result['article_category_list'] = articleModel::getArticleCategoryList();

        $this->assign('result', $result);
        return $this->fetch();
    }

    public function detail(){
        $id = (int)input('param.id');
        $data = articleModel::getArticleOne($id);

        if(empty($id) || !$data){
            $this->error('该文章不存在！');
        }

        //用户访问前端，需要做访问量登记，1天1次
        $this->visitRecord($id);

        //网站配置
        $result['site_config'] = $this->getSiteConfig();

        //面包屑导航
        $data['breadcrumb']['list'] = array(array('text' => '首页', 'href' => url('index/index/index')), array('text' => $data['category_name'], 'href' => url('index/article/index', ['category_id' => $data['category_id']])), array('text' => $data['title'], 'href' => url('index/article/detail', ['id' => $data['id']])));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        //获取上一条、下一条文章详情
        $data['preview']['previous'] = articleModel::getPrevious($id);
        if(!empty($data['preview']['previous'])){
            $data['preview']['previous']['name'] = '上一条：';
            $data['preview']['previous']['url'] = url('index/article/detail', ['id' => $data['preview']['previous']['id']]);
        }
        $data['preview']['next'] = articleModel::getNext($id);
        if(!empty($data['preview']['next'])){
            $data['preview']['next']['name'] = '下一条：';
            $data['preview']['next']['url'] = url('index/article/detail', ['id' => $data['preview']['next']['id']]);
        }

        $result = array_merge($data, $this->navRight());

        $this->assign('result', $result);
    	return $this->fetch();
    }

    protected function navRight(){
        $data['article_category_list'] = articleModel::getArticleCategoryList();

        return $data; 
    }
}