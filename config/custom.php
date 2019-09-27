<?php
/**
 * L-BLOG
 *
 * @author  lqy407763361
 * @github  https://github.com/lqy407763361
 * @码云  https://gitee.com/lqy407763361
 */

//自定义配置文件
return [
    //网站登录最大次数
    'site_login_most_number' => 5,
    //后台登录最大次数
    'admin_login_max_number' => 5,

    //网站列表页最大数量
    'site_list_limit' => 10,
    //后台列表页最大数量
    'admin_list_limit' => 20,

    //邮箱地址
    'email' => '407763361@qq.com',
    //SMTP主机
    'smtp_host' => '',
    //SMTP用户名
    'smtp_username' => '',
    //SMTP密码
    'smtp_password' => '',
    //SMTP端口
    'smtp_port' => '',

    //后台允许上传的文件大小(M)
    'allow_upload_size' => 20,
    //后台允许上传的文件类型
    'allow_upload_type' => 'jpg,jpeg,png,zip',

    //网站路径
    'site_url' => 'http://www.l_blog.com/',

    //权限设置
    'menu' => [
        '文章管理' => 'article',
        '文章分类管理' => 'articleCategory',
        '单页管理' => 'about',
        '信息管理' => 'message',
        '会员管理' => 'user',
        '管理员管理' => 'admin',
        '管理员群组管理' => 'adminGroup',
        '网站配置管理' => 'siteConfig',
        '日志管理' => 'siteLog',
    ],
];
