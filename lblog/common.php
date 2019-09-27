<?php
/**
 * L-BLOG
 *
 * @author  lqy407763361
 * @github 	https://github.com/lqy407763361
 * @码云 	https://gitee.com/lqy407763361
 */

/**
 * 处理AJAX传递序列化后的表单字符串参数
 * 需要注意的是	JQ序列化表单serialize()不识别为空的单选和多选框  控制器接收处需要另外做判断
 */
function split_ajax_str($str){
	if(empty($str)){

		return false;
	}

	$array = array();
	$k = '';

	$arr = explode('&',$str);
	foreach($arr as $key=>$item){
		$arr2[] = explode('=',$item);
		$k = $arr2[$key][0];

		if(array_key_exists($k,$array)){
			$array[$k] .= ','.$arr2[$key][1];
		}else{
			$array[$k] = $arr2[$key][1];
		}
	}

	return $array;
}

/**
 * 处理AJAX回调函数
 */
function ajax_return($code = 0, $msg = '', $data = array()){
	$array = array('code' => $code, 'msg' => $msg, 'data' => $data);
	$result = json_encode($array, JSON_UNESCAPED_UNICODE);

	return $result;
}

/**
 * 判断中文字符串长度
 * 格式为 UTF-8
 */
function utf8_strlen($str = ''){
	$len = mb_strlen(trim($str), 'utf-8');

	return $len;
}

/**
 * 判断中英文混合字符长度
 * 中文格式为 UTF-8
 */
function getMixStrLen($str = ''){
	$len = (strlen(trim($str)) + utf8_strlen($str)) / 2;

	return $len;
}

/**
 * 获取用户IP地址
 */
function get_ip(){
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

	return $ip;
}

/**
 * 加密
 * 默认密码为000000
 */
function get_encrypt($str = ''){
	$str = trim($str);

	if(empty($str)){
		$str = '000000';
	}

	$password = sha1(substr(md5($str), 0, -1));

	return $password;
}

/**
 * 生成用户token
 * 长度自定义  默认32位
 */
function create_token($size = ''){
	$size = ($size != '') ? $size : 32;
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$token = '';
	
	for($i = 0; $i < $size; $i++){
		$word = substr($str, mt_rand(0, strlen($str)), 1);
		$token .= $word;
	}

	return $token;
}

/**
 * 打印数组格式
 */
function d($arr = array()){
	echo "<pre>"; var_dump($arr);

	return $arr;
}