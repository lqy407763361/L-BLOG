<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\SiteConfig as siteConfigModel;
use think\Image;

class SiteConfig extends Base{
    protected function initialize(){
        $this->isLogin();
        $this->isViewPower();
    }
    
    //首页
    public function index(){
        $data = siteConfigModel::getSiteConfig();
        $result = array_merge($data, $this->getSiteInfo());
        
        //允许上传文件类型的提示
        $result['allow_upload_type'] = empty($result['allow_upload_type']) ? config('custom.allow_upload_type') : $result['allow_upload_type'];

        //图片路径
        if(!empty($result['wechat_image'])){
            $result['wechat_image_url'] = config('custom.site_url').$result['wechat_image'];
        }

        $this->assign('result', $result);
        return $this->fetch();
    }

    //编辑操作
    public function editAction(){
        if(!$this->isEditPower()){
            echo ajax_return(0, '您没有修改本模块的权限！'); exit;
        }

        if(!$this->checkCSRF(input('param.admin_token'))){
            echo ajax_return(0, '编辑网站配置失败！'); exit;
        }

        $wechat_image_size = $_FILES['wechat_image']['size'];
        if($wechat_image_size > 0){
            $wechat_image = Image::open(input('file.wechat_image'));
            $site_detail = siteConfigModel::getSiteConfig();

            //判断上传的图片是否符合规定类型
            $type = $wechat_image->type();
            $allow_type = empty($site_detail['allow_upload_type']) ? config('custom.allow_upload_type') : $site_detail['allow_upload_type'];
            $type_arr = explode(",", $allow_type);
            if(!in_array($type, $type_arr)){
                echo ajax_return(0, '上传图片格式非法！'); exit;
            }

            //判断上传的图片是否符合规定大小
            $allow_size = empty($site_detail['allow_upload_size']) ? config('custom.allow_upload_size') : $site_detail['allow_upload_size'];
            if(($wechat_image_size/1048576) > $allow_size){
                echo ajax_return(0, '上传图片大小非法！'); exit;
            }

            //上传图片
            $image_path = "static/admin/upload/".time().'.'.$type;
            $wechat_image->thumb(150, 150)->save($image_path);
        }else{
            $image_path = trim(input('param.wechat_image_url'));
        }

        $data = array(
            'id' => (int)input('param.id'),
            'meta_title' => trim(input('param.meta_title')),
            'meta_tag_description' => trim(input('param.meta_tag_description')),
            'meta_tag_keywords' => trim(input('param.meta_tag_keywords')),
            'qq' => trim(input('param.qq')),
            'wechat_image' => $image_path,
            'sinaweibo' => trim(input('param.sinaweibo')),
            'github' => trim(input('param.github')),
            'site_login_max_number' => empty(trim(input('param.site_login_max_number'))) ? config('custom.site_login_max_number') : (int)input('param.site_login_max_number'),
            'admin_login_max_number' => empty(trim(input('param.admin_login_max_number'))) ? config('custom.admin_login_max_number') : (int)input('param.admin_login_max_number'),
            'site_config' => trim(input('param.site_config')),
            'site_list_limit' => empty(trim(input('param.site_list_limit'))) ? config('custom.site_list_limit') : (int)input('param.site_list_limit'),
            'admin_list_limit' => empty(trim(input('param.admin_list_limit'))) ? config('custom.admin_list_limit') : (int)input('param.admin_list_limit'),
            'email' => empty(trim(input('param.email'))) ? config('custom.email') : trim(input('param.email')),
            'smtp_host' => empty(trim(input('param.smtp_host'))) ? config('custom.smtp_host') : trim(input('param.smtp_host')),
            'smtp_username' => empty(trim(input('param.smtp_username'))) ? config('custom.smtp_username') : trim(input('param.smtp_username')),
            'smtp_password' => empty(trim(input('param.smtp_password'))) ? config('custom.smtp_password') : trim(input('param.smtp_password')),
            'smtp_port' => empty(trim(input('param.smtp_port'))) ? config('custom.smtp_port') : trim(input('param.smtp_port')),
            'allow_upload_size' => empty(trim(input('param.allow_upload_size'))) ? config('custom.allow_upload_size') : (int)input('param.allow_upload_size'),
            'allow_upload_type' => empty(trim(input('param.allow_upload_type'))) ? config('custom.allow_upload_type') : trim(input('param.allow_upload_type')),
        );

        if($this->validateFrom($data)){
            $result = siteConfigModel::editSiteConfig($data);
            echo ajax_return(1, '编辑网站配置成功！'); exit;
        }
    }

    //验证类
    protected function validateFrom($data){
        if(utf8_strlen($data['meta_title']) <= 2 || utf8_strlen($data['meta_title']) > 20){
            echo ajax_return(0, 'Meta标题长度为2-10位之间！'); exit;
        }

        if(utf8_strlen($data['meta_tag_description']) <= 2 || utf8_strlen($data['meta_tag_description']) > 200){
            echo ajax_return(0, 'Meta标签描述长度为2-200位之间！'); exit;
        }

        if(utf8_strlen($data['meta_tag_description']) <= 2 || utf8_strlen($data['meta_tag_description']) > 20){
            echo ajax_return(0, 'Meta标签关键词长度为2-20位之间！'); exit;
        }

        if(utf8_strlen($data['site_config']) < 1){
            echo ajax_return(0, '网站底部配置不能为空！'); exit;
        }

        if(utf8_strlen($data['email']) < 1){
            echo ajax_return(0, '邮箱地址不能为空！'); exit;
        }

        if(utf8_strlen($data['smtp_host']) < 1){
            echo ajax_return(0, 'SMTP主机不能为空！'); exit;
        }

        if(utf8_strlen($data['smtp_username']) < 1){
            echo ajax_return(0, 'SMTP用户名不能为空！'); exit;
        }

        if(utf8_strlen($data['smtp_password']) < 1){
            echo ajax_return(0, 'SMTP密码不能为空！'); exit;
        }

        if(utf8_strlen($data['smtp_port']) < 1){
            echo ajax_return(0, 'SMTP端口不能为空！'); exit;
        }

        return true;
    }

    //获得面包屑导航和网站配置相关信息
    protected function getSiteInfo(){
        $data = $this->getAdminDetail();
        $data['breadcrumb']['title'] = '网站配置';
        $data['breadcrumb']['list'] = array(array('text' => '仪表盘', 'href' => url('admin/index/index')), array('text' => '网站配置', 'href' => url('admin/siteConfig/index')));
        $data['breadcrumb']['count'] = count($data['breadcrumb']['list']);

        return $data;
    }
}