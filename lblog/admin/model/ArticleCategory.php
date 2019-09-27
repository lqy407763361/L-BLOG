<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class ArticleCategory extends Model{
	//获取数据列表
	public static function getArticleCategoryList($filter){
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

		$result = Db::name('article_category')->field('id, name, status, add_time')->where($where)->order($order)->paginate($filter['size']);
		
		return $result;
	}

	//获取单条数据
	public static function getArticleCategoryOne($id){
		$result = Db::name('article_category')->where("id = $id")->find();

		return empty($result) ? false : $result;
	}

	//添加
	public static function addArticleCategory($data){
		$article_category_id = Db::name('article_category')->insertGetId($data);

		return ($article_category_id > 0) ? true : false;
	}

	//编辑
	public static function editArticleCategory($data){
		$result = Db::name('article_category')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}

	//删除
	public static function deleteArticleCategory($id){
		return Db::name('article_category')->delete($id);
	}

	//判断该分类是否存在
	public static function articleCategoryExist($name){
		$id = Db::name('article_category')->field('id')->where("name = '".$name."'")->find()['id'];

		return ($id > 0) ? true : false;
	}
}