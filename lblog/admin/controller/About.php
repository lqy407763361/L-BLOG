<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\About as aboutModel;

class About extends Base{
    protected function initialize(){
        $this->isLogin();
        $this->isViewPower();
    }

    //列表页
    public function index(){
        $data = aboutModel::getAbout();
        $result = array_merge($data, $this->getSiteInfo());

        $this->assign('result', $result);
        return $this->fetch();
    }

    //编辑操作
    public function editAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑单页失败！'); exit;
        }

        $data = array(
            'id' => (int)input('param.id'),
            'content' => trim(input('param.content')),
            'status' => (int)input('param.status'),
            'edit_time' => time(),
        );

        $result = aboutModel::editAbout($data);
        echo ajax_return(1, '编辑单页成功！'); exit;
    }

    //获得面包屑导航和单页相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '单页管理';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '单页管理', 'href' => url('admin/about/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}