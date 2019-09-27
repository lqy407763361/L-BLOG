<?php
/**
 * L-BLOG
 *
 * @author  lqy407763361
 * @github 	https://github.com/lqy407763361
 * @码云 	https://gitee.com/lqy407763361
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as adminModel;
use app\admin\model\Message as messageModel;
use app\admin\model\User as userModel;
use app\admin\model\AdminGroup as adminGroupModel;
use app\admin\model\SiteConfig as siteConfigModel;

class Base extends Controller{
	//判断是否登录  未登录需要跳转至登录页面
	public function isLogin(){
	    if(empty(session('admin_id')) && empty(session('admin_token'))){
	        $this->redirect('admin/login/index');
	    }
	}

	//防止跳过导航栏  空降URL
	//判断该账号是否存在相关查看权限  无权限则返回首页
	public function isViewPower(){
		if(!empty(session('view_power'))){
			in_array(lcfirst(request()->controller()), session('view_power')) ? true : $this->redirect('admin/index/index');
		}
	}

	//判断该账号是否存在相关修改权限  无权则阻止修改
	public function isEditPower(){
		if(!empty(session('edit_power'))){
			return in_array(lcfirst(request()->controller()), session('edit_power')) ? true : false;
		}
	}

	//获取管理员/其他公共相关信息
	public function getAdminDetail(){
        $admin_detail['admin'] = adminModel::getAdminOne(session('admin_id'));
        $admin_detail['admin_group'] = adminGroupModel::getAdminGroupOne(session('admin_group_id'));
        $result['admin_nickname'] = $admin_detail['admin']['nickname'];
        $result['admin_group_name'] = $admin_detail['admin_group']['name'];
        $result['admin_login_time'] = date('Y-m-d H:i:s', $admin_detail['admin']['last_login_time']);
        $result['admin_login_ip'] = $admin_detail['admin']['last_login_ip'];
        $result['message_new'] = messageModel::getMessageCount($filter = array('get_new_message' => true));
        $result['message_all'] = messageModel::getMessageCount();
        $result['user_new'] = userModel::getUserCount($filter = array('get_new_user' => true));
        $result['user_all'] = userModel::getUserCount();
        $result['remind'] = $result['message_new'] + $result['user_new'];

        return $result;
	}

	//获取系统配置
	public function getSiteConfig(){
		
		return siteConfigModel::getSiteConfig();
	}

	//CSRF防范
	public function checkCSRF($admin_token){
		if($admin_token != session('admin_token')){
			session(null);

			return false;
		}

		return true;
	}
}