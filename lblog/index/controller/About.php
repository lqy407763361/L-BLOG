<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\About as aboutModel;

class About extends Base{
    public function index(){
        //网站配置
        $result['site_config'] = $this->getSiteConfig();

        //单页内容
        $result['content'] = aboutModel::getAbout();

        $this->assign('result', $result);
        return $this->fetch();
    }

    public function sendMessage(){
    	$data = array(
            'title' => trim(input('param.title')),
            'name' => '',
            'content' => trim(input('param.content')),
            'status' => 0,
            'add_time' => time(),
        );

        if($this->validateFrom($data)){
            $result = aboutModel::sendMessage($data);

            if($result){ 
                echo ajax_return(1, '发送成功！'); exit;
            }else{
                echo ajax_return(0, '发送失败！'); exit;
            }
        }
    }

    //验证类
    protected function validateFrom($data){
        if(utf8_strlen($data['title']) <= 0){
            echo ajax_return(0, '请输入标题！'); exit;
        }

        if(utf8_strlen($data['content']) <= 0){
            echo ajax_return(0, '请输入内容！'); exit;
        }

        return true;
    }
}
