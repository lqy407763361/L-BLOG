<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class AdminGroup extends Model{
	//获取数据列表
	public static function getAdminGroupList($filter){
		$where = '1=1';
		$order = 'status DESC, id ASC';

		if(!empty($filter['search_name'])){
			$where = "POSITION('".$filter['search_name']."' IN name)";
		}
		if($filter['search_status'] != ''){
			$where .= " AND status = ".$filter['search_status'];
		}

		if(!empty($filter['sort'])){
			$order = array($filter['field'] => $filter['sort']);
		}

		$result = Db::name('admin_group')->field('id, name, status, add_time')->where($where)->order($order)->paginate($filter['size']);
		
		return $result;
	}

	//获取单条数据
	public static function getAdminGroupOne($id){
		$result = Db::name('admin_group')->where("id = $id")->find();

		return empty($result) ? false : $result;
	}

	//添加
	public static function addAdminGroup($data){
		$admin_group_id = Db::name('admin_group')->insertGetId($data);

		return ($admin_group_id > 0) ? true : false;
	}

	//编辑
	public static function editAdminGroup($data){
		$result = Db::name('admin_group')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}

	//删除
	public static function deleteAdminGroup($id){
		return Db::name('admin_group')->delete($id);
	}

	//判断该群组是否存在
	public static function adminGroupExist($name){
		$id = Db::name('admin_group')->field('id')->where("name = '".$name."'")->find()['id'];

		return ($id > 0) ? true : false;
	}
}