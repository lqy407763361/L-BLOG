<?php
namespace app\index\controller;

use think\Controller;
use think\captcha\Captcha;
use app\index\model\Article as articleModel;
use app\index\model\User as userModel;

class Index extends Base{
    public function index(){
    	//用户访问前端，需要做访问量登记，1天1次
        $this->visitRecord();

        //网站配置
        $result['site_config'] = $this->getSiteConfig();

        //最新文章列表
        $result['new_article_list'] = articleModel::getNewArticleList();
        //热门文章列表
        $result['hot_article_list'] = articleModel::getHotArticleList();

        $this->assign('result', $result);
        return $this->fetch();
    }

    public function search(){
    	//用户访问前端，需要做访问量登记，1天1次
        $this->visitRecord();
        
        //网站配置
        $result['site_config'] = $this->getSiteConfig();

        $filter = array(
            'search_title' => trim(input('param.search_title')),
            'size' => $this->getSiteConfig()['site_list_limit'],
        );
        $result['list'] = articleModel::getArticleList($filter);
        $result['page'] = $result['list']->render();

        $this->assign('result', $result);
        $this->assign('search_title', $filter['search_title']);
        return $this->fetch();
    }

    //注册
    public function register(){
        if(!captcha_check(trim(input('param.captcha')))){
            echo ajax_return(-1, '* 验证码错误！'); exit;
        }

        $name = trim(input('param.name'));
        if(getMixStrLen($name) < 2 || getMixStrLen($name) > 10){
            echo ajax_return(-2, '* 昵称长度不合法！'); exit;
        }

        $is_existence = userModel::getUserOne($name);
        if(!empty($is_existence)){
            echo ajax_return(-2, '* 昵称已存在！'); exit;
        }

        $password = trim(input('param.password'));
        if(strlen($password) < 6 || strlen($password) > 12){
            echo ajax_return(-3, '* 密码长度不合法！'); exit;
        }

        $data = array(
            'name' => $name,
            'password' => get_encrypt($password),
            'status' => 1,
            'register_type_id' => 1,
            'register_ip' => get_ip(),
            'next_login_ip' => get_ip(),
            'register_time' => time(),
            'next_login_time' => time(),
            'edit_time' => '',
        );

        $user_id = userModel::addUser($data);
        if($user_id){
            cookie('user_id', $user_id, 86400);
            cookie('name', $name, 86400);

            echo ajax_return(1, '注册成功！'); exit;
        }else{
            echo ajax_return(0, '注册失败！'); exit;
        }
    }

    //登录
    public function login(){
        $name = trim(input('param.name'));
        $user_detail = userModel::getUserOne($name);

        if(empty($user_detail)){
            echo ajax_return(-2, '* 昵称错误或不存在！'); exit;
        }

        $password = get_encrypt(trim(input('param.password')));
        if($password != $user_detail['password']){
            echo ajax_return(-3, '* 密码错误！'); exit;
        }

        if($user_detail['status'] == 0){
            echo ajax_return(-1, '该账号已被禁用！'); exit;
        }

        $is_remember = trim(input('param.is_remember'));
        if($is_remember == 1){
            $end_time = 2592000;
        }else{
            $end_time = 86400;
        }

        cookie('user_id', $user_detail['id'], $end_time);
        cookie('name', $name, $end_time);

        $data = array(
            'id' => $user_detail['id'],
            'next_login_ip' => get_ip(),
            'next_login_time' => time(),
        );
        userModel::editUser($data);

        echo ajax_return(1, '登录成功！'); exit;
    }

    //退出登录
    public function loginOff(){
        cookie('user_id', null);
        cookie('name', null);

        echo ajax_return(1, '退出成功！'); exit;
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
