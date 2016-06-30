<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Exam\Controller;
use Common\Controller\AdminbaseController;
class AdminHistoryController extends AdminbaseController {
	function index(){
		// echo "string";
		$scores = M("scores");
		$count      = $scores->where($map)->count();
		$Page       = new \Think\Page($count,25);
		$show       = $Page->show();
		$list = $scores->where($map)->order('time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $i => $va) {
			$list[$i]["submit_time"] = date("Y-m-d h:i:s", $va["time"]);
		}
		$this->assign("scores",$list);
        $this->display();
	}
	function change_score(){
		$data = $_POST["data"];
		$map["id"] = $data["id"];
		if (M("scores")->where($map)->save($data)) {
			$return["status"] = 1;
			$return["info"] = "修改成功";
		}else{
			$return["status"] = 0;
			$return["info"] = "无修改";
		}
		$this->ajaxReturn($return);
	}
	function view_score(){
		$map["id"] = $_GET["id"];
		$scores = M("scores")->where($map)->find();
		$result = json_decode($scores["result"]);
		foreach ($result as $i => $va) {
			$exam_questions[$va->type][] = $va;
		}
		$this->assign("exam",$scores);
		$this->assign("exam_questions",$exam_questions);
		$this->display();
	}
	
}