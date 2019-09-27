<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as adminModel;

class Admin extends Base{
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
        $data['group_id'] = 0;
        $data['name'] = '';
        $data['nickname'] = '';
        $data['description'] = '';
        $data['status'] = 1;
        $data['add_time'] = date('Y-m-d', time());
        $data['edit_time'] = '';
        $data['last_login_time'] = '';
        $data['admin_group_list'] = adminModel::getAdminGroupList();

        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch('detail');
    }

    //编辑页面
    public function edit(){
        $id = (int)input('param.id');
        $data = adminModel::getAdminOne($id);

        if(!$data){
        	$this->error('该管理员账号不存在！');
        }

        $data['add_time'] = date('Y-m-d', $data['add_time']);
        $data['edit_time'] = date('Y-m-d', $data['edit_time']);
        $data['last_login_time'] = date('Y-m-d', $data['last_login_time']);
        $data['admin_group_list'] = adminModel::getAdminGroupList();

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

    	$result['list'] = adminModel::getAdminList($filter);
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
            'group_id' => (int)input('param.group_id'),
            'name' => trim(input('param.name')),
            'nickname' => trim(input('param.nickname')),
            'password' => trim(input('param.password')),
            'confirm_password' => trim(input('param.confirm_password')),
            'description' => trim(input('param.description')),
            'status' => (int)input('param.status'),
            'add_time' => time(),
            'add_ip' => get_ip(),
        );

        if($this->validateFrom($data)){
            $data['password'] = get_encrypt($data['password']);
            unset($data['confirm_password']);
            $result = adminModel::addAdmin($data);

            if($result){ 
                echo ajax_return(1, '添加管理员成功！'); exit;
            }else{
                echo ajax_return(0, '添加管理员失败！'); exit;
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
            'group_id' => (int)input('param.group_id'),
            'name' => trim(input('param.name')),
            'nickname' => trim(input('param.nickname')),
            'password' => trim(input('param.password')),
            'confirm_password' => trim(input('param.confirm_password')),
            'description' => trim(input('param.description')),
            'status' => (int)input('param.status'),
            'edit_time' => time(),
        );

        if($this->validateFrom($data)){
            $data['password'] = get_encrypt($data['password']);
            unset($data['confirm_password']);
            
            $result = adminModel::editAdmin($data); 
            echo ajax_return(1, '编辑管理员成功！'); exit;
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
            adminModel::deleteAdmin($id);
            echo ajax_return(1, '删除成功！'); exit;
        }
    }

    //验证类
    protected function validateFrom($data){
        if(utf8_strlen($data['name']) < 2 || utf8_strlen($data['name']) > 13){
            echo ajax_return(0, '账号长度为2-13位之间！'); exit;
        }

        if(!isset($data['id'])){
            $is_exist = adminModel::adminExist($data['name']);
            if($is_exist){
                echo ajax_return(0, '该账号已经存在！'); exit;
            }
        }

        if(strlen($data['password']) < 5 || strlen($data['password']) > 15){
            echo ajax_return(0, '密码长度为6-15位之间！'); exit;
        }

        if($data['password'] != $data['confirm_password']){
            echo ajax_return(0, '密码不一致！'); exit;
        }

        if(empty($data['group_id'])){
            echo ajax_return(0, '请选择管理员群组！'); exit;
        }

        return true;
    }

    //获得面包屑导航和管理员相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '管理员管理';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '管理员管理', 'href' => url('admin/admin/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}