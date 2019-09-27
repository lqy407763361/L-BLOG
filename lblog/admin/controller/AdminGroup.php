<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AdminGroup as adminGroupModel;

class AdminGroup extends Base{
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
        $data['status'] = 1;
        $data['view_power'] = array();
        $data['edit_power'] = array();
        $data['add_time'] = date('Y-m-d', time());
        $data['edit_time'] = '';
        $data['menu'] = config('custom.menu');

        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch('detail');
    }

    //编辑页面
    public function edit(){
        $id = (int)input('param.id');
        $data = adminGroupModel::getAdminGroupOne($id);
        $data['menu'] = config('custom.menu');

        if(!$data){
        	$this->error('该管理员群组不存在！');
        }

        $data['view_power'] = explode(',', $data['view_power']);
        $data['edit_power'] = explode(',', $data['edit_power']);
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

    	$result['list'] = adminGroupModel::getAdminGroupList($filter);
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
            echo ajax_return(0, '编辑管理员失败！'); exit;
        }

        $data = array(
            'name' => trim(input('param.name')),
            'description' => trim(input('param.description')),
            'status' => (int)input('param.status'),
            'view_power' => empty(input('param.view_power')) ? '' : implode(',', input('param.view_power')),
            'edit_power' => empty(input('param.edit_power')) ? '' : implode(',', input('param.edit_power')),
            'add_time' => time(),
        );

        if($this->validateFrom($data)){
            $result = adminGroupModel::addAdminGroup($data);

            if($result){
                echo ajax_return(1, '添加管理员群组成功！'); exit;
            }else{
                echo ajax_return(0, '添加管理员群组失败！'); exit;
            }
        }
    }

    //编辑操作
    public function editAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑管理员失败！'); exit;
        }

        $data = array(
            'id' => (int)input('param.id'),
            'name' => trim(input('param.name')),
            'description' => trim(input('param.description')),
            'status' => (int)input('param.status'),
            'view_power' => empty(input('param.view_power')) ? '' : implode(',', input('param.view_power')),
            'edit_power' => empty(input('param.edit_power')) ? '' : implode(',', input('param.edit_power')),
            'edit_time' => time(),
        );

        if($this->validateFrom($data)){
            $result = adminGroupModel::editAdminGroup($data);
            if($result){
                $get_power = adminGroupModel::getAdminGroupOne($data['id']);
                session('view_power', explode(',', $get_power['view_power']));
                session('edit_power', explode(',', $get_power['edit_power']));
            }

            echo ajax_return(1, '编辑管理员群组成功！'); exit;
        }
    }

    //删除操作
    public function deleteAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }
        
        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑管理员失败！'); exit;
        }

        $id = input('param.data');
        if(empty($id)){
            echo ajax_return(0, '请选择删除内容！'); exit;
        }else{
            adminGroupModel::deleteAdminGroup($id);
            echo ajax_return(1, '删除成功！'); exit;
        }
    }

    //验证类
    protected function validateFrom($data){
        if(utf8_strlen($data['name']) < 2 || utf8_strlen($data['name']) > 10){
            echo ajax_return(0, '群组名称长度为2-10位之间！'); exit;
        }

        if(!isset($data['id'])){
            $is_exist = adminGroupModel::adminGroupExist($data['name']);
            if($is_exist){
                echo ajax_return(0, '该群组已经存在！'); exit;
            }
        }

        return true;
    }

    //获得面包屑导航和管理员群组相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '管理员群组管理';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '管理员群组管理', 'href' => url('admin/adminGroup/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}