<?php
namespace app\admin\model;

use think\Model;
use think\Db;

class Article extends Model{
	//获取数据列表
	public static function getArticleList($filter){
		$where = '1=1';
		$order = 'a.status DESC, a.id DESC';

		if(!empty($filter['search_title'])){
			$where = "POSITION('".$filter['search_title']."' IN a.title)";
		}
		if($filter['search_status'] != ''){
			$where .= " AND a.status = ".$filter['search_status'];
		}

		if(!empty($filter['sort'])){
			$order = array("a.".$filter['field'] => $filter['sort']);
		}

		$result = Db::name('article a')
            ->join('article_category ac','a.category_id = ac.id', "left")
            ->field('a.id, a.title, a.status, a.add_time, ac.name as category_name')
            ->where($where)
            ->order($order)
            ->paginate($filter['size']);

		return $result;
	}

	//获取数量
	public static function getArticleCount(){
		$total = Db::name('article')->field('id')->count();

		return $total;
	}

	//获取单条数据
	public static function getArticleOne($id){
		$result = Db::name('article')->where("id = $id")->find();

		return empty($result) ? false : $result;
	}

	//添加
	public static function addArticle($data){
		$article_id = Db::name('article')->insertGetId($data);

		return ($article_id > 0) ? true : false;
	}

	//编辑
	public static function editArticle($data){
		$result = Db::name('article')->where('id = '.$data['id'])->setField($data);

		return ($result > 0) ? true : false;
	}

	//删除
	public static function deleteArticle($id){
		return Db::name('article')->delete($id);
	}

	//获取文章分类
	public static function getArticleCategoryList(){
		$article_category_list = Db::name('article_category')->field('id, name')->order('id ASC')->select();

		return $article_category_list;
	}

	//判断该文章是否存在
	public static function getArticleExist($title){
		$id = Db::name('article')->field('id')->where("title = '".$title."'")->find()['id'];

		return ($id > 0) ? true : false;
	}
}