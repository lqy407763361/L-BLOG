<?php
namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;
use app\admin\model\Login as loginModel;
use app\admin\model\Admin as adminModel;
use app\admin\model\AdminGroup as adminGroupModel;
use app\admin\model\SiteConfig as siteConfigModel;

class Login extends Base{
	//跳转至首页页面
	public function index(){
		//判断用户是否登录  如果已经登录则跳转至首页
		if(!empty(session('admin_id')) && !empty(session('admin_token'))){
			$this->redirect('admin/index/index');
		}
		
		return $this->fetch('common/login');
	}

	//登录操作
	public function loginAction(){
		$data = array(
            'name' => trim(input('param.name')),
            'password' => trim(input('param.password')),
            'captcha' => input('param.captcha'),
        );

        //后台最大登录次数
        $admin_login_max_number = siteConfigModel::getSiteConfig()['admin_login_max_number'];
        //如果当天登陆错误次数超过五次  拒绝访问
  //       $redis = $this->redisStart();
  //       if($redis->get(get_ip()) >= $admin_login_max_number){
		// 	echo ajax_return(0, '您今天登陆错误次数达到'.$admin_login_max_number.'次！'); exit;
		// }

		$result = loginModel::getLoginOne($data['name']);
        if($this->validateFrom($data, $result)){
        	//保存必要参数至SESSION  以及创建32位token值  用以防范CSRF攻击
        	session('admin_id', $result['id']);
        	session('admin_group_id', $result['group_id']);
        	session('admin_token', create_token());
        	session('admin_last_login_time', date('Y-m-d H:i:s', $result['last_login_time']));
        	session('admin_last_login_ip', $result['last_login_ip']);

        	//获取权限
			$get_power = adminGroupModel::getAdminGroupOne($result['id']);
			session('view_power', explode(',', $get_power['view_power']));
			session('edit_power', explode(',', $get_power['edit_power']));
        	
        	//编辑账号最后登录时间
        	$last_login_admin = array(
        		'id' => $result['id'],
        		'last_login_ip' => get_ip(),
				'last_login_time' => time(),
        	);
        	$last_login_admin_id = adminModel::editAdmin($last_login_admin);
        	if($last_login_admin_id){
        		echo ajax_return(1, '登陆成功！'); exit;
        	}else{
        		echo ajax_return(0, '登录失败！'); exit;
        	}
        }
	}

	//退出登录操作
	public function loginOutAction(){
		session(null);

		$this->redirect('admin/login/index');
	}

	//验证类
	protected function validateFrom($data, $result){
		if(!captcha_check($data['captcha'])){
			echo ajax_return(0, '验证码错误！'); exit;
		}

		if(empty($result) || (get_encrypt($data['password']) != $result['password'])){
			//登录用户名或密码错误  需要写入数据库记录
			$record = array(
				'admin_name' => trim($data['name']),
				'login_ip' => get_ip(),
				'login_time' => time(),
			);
			$login_record_id = loginModel::addLoginRecord($record);
			if($login_record_id){
				//将IP存到redis里面  登录次数+1
				// $redis = $this->redisStart();
				// $tomorrow = strtotime(date('Y-m-d',strtotime('+1 day')));
				// $active_time = $tomorrow-time();
				// $error_sum = $redis->get(get_ip());
				// if(empty($error_sum)){
				// 	$redis->set(get_ip(), '1', $active_time);
				// }else{
				// 	$error_sum = $error_sum+1;
				// 	$redis->set(get_ip(), $error_sum, $active_time);
				// }
			}
			echo ajax_return(0, '账号或密码错误！'); exit;
		}

		if($result['status'] != 1){
			echo ajax_return(0, '该账号未被启用！'); exit;
		}

		return true;
	}

	//生成验证码
	public function createCaptcha(){
		$captcha = new Captcha();
		//验证码位数
        $captcha->length = 4;
        //验证码字体大小(px)
        $captcha->fontSize = 14;
        //是否添加杂点
        $captcha->useNoise = false;

		return $captcha->entry();
	}
}