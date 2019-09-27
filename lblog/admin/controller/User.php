<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\User as userModel;

class User extends Base{
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

    //编辑页面
    public function edit(){
        $id = (int)input('param.id');
        $data = userModel::getUserOne($id);

        if(!$data){
        	$this->error('该用户不存在！');
        }

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

    	$result['list'] = userModel::getUserList($filter);
        $result['page'] = $result['list']->render();

    	if(!empty($result)){
			echo ajax_return('1', '查询成功！', $result); exit;
		}else{
			echo ajax_return('2', '暂无数据！', $result); exit;
		}
    }

    //编辑操作
    public function editAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑用户失败！'); exit;
        }

        $data = array(
            'id' => (int)input('param.id'),
            'status' => (int)input('param.status'),
            'edit_time' => time(),
        );

        userModel::editUser($data);
        echo ajax_return(1, '编辑用户成功！'); exit;   
    }

    //删除操作
    public function deleteAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }
        
        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑用户失败！'); exit;
        }

        $id = input('param.data');
        if(empty($id)){
            echo ajax_return(0, '请选择删除内容！'); exit;
        }else{
            userModel::deleteUser($id);
            echo ajax_return(1, '删除成功！'); exit;
        }
    }

    //获得面包屑导航和用户相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '用户管理';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '用户管理', 'href' => url('admin/user/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}