<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Admin extends Model{
	//获取数据列表
	public static function getAdminList($filter){
		$where = '1=1';
		$order = 'a.status DESC, a.id DESC';

		if(!empty($filter['search_name'])){
			$where = "POSITION('".$filter['search_name']."' IN a.name)";
		}
		if($filter['search_status'] != ''){
			$where .= " AND a.status = ".$filter['search_status'];
		}

		if(!empty($filter['sort'])){
			$order = array("a.".$filter['field'] => $filter['sort']);
		}

		$result = Db::name('admin a')
            ->join('admin_group ag','a.group_id = ag.id', "left")
            ->field('a.id, a.name, a.nickname, a.status, a.add_time, ag.name as group_name')
            ->where($where)
            ->order($order)
            ->paginate($filter['size']);

		return $result;
	}

	//获取数量
	public static function getAdminCount(){
		$total = Db::name('admin')->field('id')->count();
		
		return $total;
	}

	//获取单条数据
	public static function getAdminOne($id){
		$result = Db::name('admin')->where("id = $id")->find();

		return empty($result) ? false : $result;
	}

	//添加
	public static function addAdmin($data){
		$admin_id = Db::name('admin')->insertGetId($data);

		return ($admin_id > 0) ? true : false;
	}

	//编辑
	public static function editAdmin($data){
		$result = Db::name('admin')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}

	//删除
	public static function deleteAdmin($id){
		return Db::name('admin')->delete($id);
	}

	//获取管理员群组
	public static function getAdminGroupList(){
		$admin_group_list = Db::name('admin_group')->field('id, name')->order('id ASC')->select();

		return $admin_group_list;
	}

	//判断该账号是否存在
	public static function adminExist($name){
		$id = Db::name('admin')->field('id')->where("name = '".$name."'")->find()['id'];

		return ($id > 0) ? true : false;
	}
}