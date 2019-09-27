<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\ArticleCategory as articleCategoryModel;

class ArticleCategory extends Base{
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
        $data['name'] = '';
        $data['description'] = '';
        $data['sort_order'] = 0;
        $data['status'] = 1;
        $data['add_time'] = date('Y-m-d', time());
        $data['edit_time'] = '';

        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch('detail');
    }

    //编辑页面
    public function edit(){
        $id = (int)input('param.id');
        $data = articleCategoryModel::getArticleCategoryOne($id);

        if(!$data){
        	$this->error('该文章分类不存在！');
        }

        $data['add_time'] = date('Y-m-d', $data['add_time']);
        $data['edit_time'] = (!empty($data['edit_time'])) ? date('Y-m-d', $data['edit_time']) : '';

        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch('detail');
    }

    //获取列表操作
    public function getListAction(){
        $filter = array(
            'search_name' => trim(input('param.search_name')),
            'search_status' => trim(input('param.search_status')),
            'field' => input('param.field'),
            'sort' => input('param.sort'),
            'size' => $this->getSiteConfig()['admin_list_limit'],
        );

    	$result['list'] = articleCategoryModel::getArticleCategoryList($filter);
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
            echo ajax_return(0, '编辑文章分类失败！'); exit;
        }

        $data = array(
            'name' => trim(input('param.name')),
            'description' => trim(input('param.description')),
            'sort_order' => (int)input('param.sort_order'),
            'status' => (int)input('param.status'),
            'add_time' => time(),
        );

        if($this->validateFrom($data)){
            $result = articleCategoryModel::addArticleCategory($data);

            if($result){ 
                echo ajax_return(1, '添加文章分类成功！'); exit;
            }else{
                echo ajax_return(0, '添加文章分类失败！'); exit;
            }
        }
    }

    //编辑操作
    public function editAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑文章分类失败！'); exit;
        }

        $data = array(
            'id' => (int)input('param.id'),
            'name' => trim(input('param.name')),
            'description' => trim(input('param.description')),
            'status' => (int)input('param.status'),
            'edit_time' => time(),
        );

        if($this->validateFrom($data)){
            articleCategoryModel::editArticleCategory($data);
            echo ajax_return(1, '编辑文章分类成功！'); exit;
        }
    }

    //删除操作
    public function deleteAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }
        
        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑文章分类失败！'); exit;
        }

        $id = input('param.data');
        if(empty($id)){
            echo ajax_return(0, '请选择删除内容！'); exit;
        }else{
            articleCategoryModel::deleteArticleCategory($id);
            echo ajax_return(1, '删除成功！'); exit;
        }
    }

    //验证类
    protected function validateFrom($data){
        if(utf8_strlen($data['name']) < 2 || utf8_strlen($data['name']) > 10){
            echo ajax_return(0, '群组名称长度为2-10位之间！'); exit;
        }

        if(!isset($data['id'])){
            $is_exist = articleCategoryModel::articleCategoryExist($data['name']);
            if($is_exist){
                echo ajax_return(0, '该分类已经存在！'); exit;
            }
        }

        return true;
    }

    //获得面包屑导航和文章分类相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '文章分类管理';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '文章分类管理', 'href' => url('admin/articleCategory/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}