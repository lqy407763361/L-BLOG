<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Article as articleModel;
use app\admin\model\VisitRecord as visitRecordModel;

class Article extends Base{
    protected function initialize(){
        $this->isLogin();
        $this->isViewPower();
    }

    //列表页
    public function index(){
        $result = $this->getSiteInfo();

        $this->assign('result', $result);
        return $this->fetch();
    }

    //添加页面
    public function add(){
    	$data['id'] = 0;
        $data['category_id'] = 0;
        $data['title'] = '';
        $data['content'] = '';
        $data['reading'] = 0;
        $data['status'] = 1;
        $data['sort_order'] = 1;
        $data['add_time'] = date('Y-m-d', time());
        $data['edit_time'] = '';
        $data['article_category_list'] = articleModel::getArticleCategoryList();

        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch('detail');
    }

    //编辑页面
    public function edit(){
        $id = (int)input('param.id');
        $data = articleModel::getArticleOne($id);

        if(!$data){
        	$this->error('该文章不存在！');
        }

        $data['add_time'] = date('Y-m-d', $data['add_time']);
        $data['edit_time'] = date('Y-m-d', $data['edit_time']);
        $data['article_category_list'] = articleModel::getArticleCategoryList();
        $data['reading'] = visitRecordModel::getArticleVisitSum($id);

        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch('detail');
    }

    //获取列表操作
    public function getListAction(){
        $filter = array(
            'search_title' => trim(input('param.search_title')),
            'search_status' => trim(input('param.search_status')),
            'field' => input('param.field'),
            'sort' => input('param.sort'),
            'size' => $this->getSiteConfig()['admin_list_limit'],
        );

    	$result['list'] = articleModel::getArticleList($filter);
        $result['page'] = $result['list']->render();

    	if(!empty($result)){
			echo ajax_return('1', '查询成功！', $result); exit;
		}else{
			echo ajax_return('2', '暂无数据！', $result); exit;
		}
    }

    //添加操作
    public function addAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑文章失败！'); exit;
        }

        $data = array(
            'category_id' => (int)input('param.category_id'),
            'title' => trim(input('param.title')),
            'content' => trim(input('param.content')),
            'reading' => 0,
            'status' => (int)input('param.status'),
            'sort_order' => (int)input('param.sort_order'),
            'add_time' => time(),
        );

        if($this->validateFrom($data)){
            $result = articleModel::addArticle($data);

            if($result){ 
                echo ajax_return(1, '添加文章成功！'); exit;
            }else{
                echo ajax_return(0, '添加文章失败！'); exit;
            }
        }
    }

    //编辑操作
    public function editAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑文章失败！'); exit;
        }

        $data = array(
            'id' => (int)input('param.id'),
            'category_id' => (int)input('param.category_id'),
            'title' => trim(input('param.title')),
            'content' => trim(input('param.content')),
            'status' => (int)input('param.status'),
            'sort_order' => (int)input('param.sort_order'),
            'edit_time' => time(),
        );
        if($this->validateFrom($data)){
            $result = articleModel::editArticle($data);
            echo ajax_return(1, '编辑文章成功！'); exit;
        }
    }

    //删除操作
    public function deleteAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }
        
        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑文章失败！'); exit;
        }

        $id = input('param.data');
        if(empty($id)){
            echo ajax_return(0, '请选择删除内容！'); exit;
        }else{
            articleModel::deleteArticle($id);
            echo ajax_return(1, '删除成功！'); exit;
        }
    }

    //验证类
    protected function validateFrom($data){
        if(utf8_strlen($data['title']) <= 0 || utf8_strlen($data['title']) > 20){
            echo ajax_return(0, '账号长度为0-20位之间！'); exit;
        }

        if(!isset($data['id'])){
            $is_exist = articleModel::getArticleExist($data['title']);
            if($is_exist){
                echo ajax_return(0, '该文章已经存在！'); exit;
            }
        }

        if(empty($data['category_id'])){
            echo ajax_return(0, '请选择文章分类！'); exit;
        }

        if(utf8_strlen(strip_tags($data['content'])) <= 0){
            echo ajax_return(0, '请输入内容！'); exit;
        }

        return true;
    }

    //获得面包屑导航和文章相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '文章管理';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '文章管理', 'href' => url('admin/article/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}