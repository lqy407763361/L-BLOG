<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Message extends Model{
	//获取数据列表
	public static function getMessageList($filter){
		$where = '1=1';
		$order = 'status DESC, id ASC';

		if(!empty($filter['search_title'])){
			$where = "POSITION('".$filter['search_title']."' IN title)";
		}
		if(!empty($filter['search_name'])){
			$where .= " AND POSITION('".$filter['search_name']."' IN name)";
		}
		if($filter['search_status'] != ''){
			$where .= " AND status = ".$filter['search_status'];
		}

		if(!empty($filter['sort'])){
			$order = array($filter['field'] => $filter['sort']);
		}

		$result = Db::name('message')->field('id, title, name, status, add_time')->where($where)->order($order)->paginate($filter['size']);
		
		return $result;
	}

	//获取数量
	public static function getMessageCount($filter = array()){
		$where = '1=1';
		if(!empty($filter['get_new_message']) && $filter['get_new_message'] == true){
			$where = "status = 0";
		}
		$total = Db::name('message')->field('id')->where($where)->count();
		
		return $total;
	}

	//获取单条数据
	public static function getMessageOne($id){
		$result = Db::name('message')->where("id = $id")->find();

		return empty($result) ? false : $result;
	}

	//编辑
	public static function editMessage($data){
		$result = Db::name('message')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}

	//删除
	public static function deleteMessage($id){
		return Db::name('message')->delete($id);
	}
}