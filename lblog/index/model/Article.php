<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Article extends Model{
	//获取数据列表
	public static function getArticleList($filter = array()){
		$where = "a.status = 1 AND ac.status = 1";
		if(!empty($filter['category_id'])){
			$where .= " AND a.category_id = ".$filter['category_id'];
		}

		if(!empty($filter['search_title'])){
			$where .= " AND POSITION('".$filter['search_title']."' IN a.title)";
		}

		$result = Db::name('article a')
			->join('article_category ac','a.category_id = ac.id', "left")
            ->field('a.id, a.title, a.add_time')
            ->where($where)
            ->order("a.sort_order DESC, a.id DESC")
            ->paginate($filter['size'], false, ['type' => 'page\IndexPage', 'var_page' => 'page', 'query' => request()->param()]);

		return $result;
	}

	//获取单条数据
	public static function getArticleOne($id){
		$result = Db::name('article a')
            ->join('article_category ac','a.category_id = ac.id', "left")
            ->field('a.id, a.title, a.content, a.add_time, ac.id as category_id, ac.name as category_name')
			->where("a.id = $id")
			->find();

		return empty($result) ? false : $result;
	}

	//获取文章分类
	public static function getArticleCategoryList(){
		$where = "status = 1";

		$result = Db::name('articleCategory')
            ->field('id, name')
            ->where($where)
            ->order("sort_order DESC, id DESC")
            ->select();

		return $result;
	}

	//获取上一条文章详情
	public static function getPrevious($id){
		$result = Db::name('article a')
			->join('article_category ac','a.category_id = ac.id', "left")
			->field('a.id, a.title')
			->where("a.status = 1 AND ac.status = 1 AND a.id < $id")
			->order('a.id DESC')
			->find();

		return empty($result) ? false : $result;
	}

	//获取下一条文章详情
	public static function getNext($id){
		$result = Db::name('article a')
			->join('article_category ac','a.category_id = ac.id', "left")
			->field('a.id, a.title')
			->where("a.status = 1 AND ac.status = 1 AND a.id > $id")
			->order('a.id ASC')
			->find();

		return empty($result) ? false : $result;
	}

	//获取首页最新数据列表
	public static function getNewArticleList(){
		$result = Db::name('article a')
			->join('article_category ac','a.category_id = ac.id', "left")
            ->field('a.id, a.title, a.add_time')
            ->where("a.status = 1 AND ac.status = 1")
            ->order("a.id DESC")
            ->limit(5)
            ->select();

		return $result;
	}

	//获取首页热门数据列表
	public static function getHotArticleList(){
		$result = Db::name('article a')
			->join('article_category ac','a.category_id = ac.id', "left")
            ->field('a.id, a.title, a.add_time')
            ->where("a.status = 1 AND ac.status = 1")
            ->select();

    	foreach($result as $key=>$value){
    		$result[$key]['visit_sum'] = Db::name('visit_record')->field('id')->where("article_id = ".$value['id'])->count();
    	}
    	array_multisort(array_column($result, 'visit_sum'), SORT_DESC, $result);
    	$result = array_slice($result, 0, 5);

		return $result;
	}
}