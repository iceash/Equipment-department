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
class AdminQuestionController extends AdminbaseController {
	function _initialize() {
		//解析函数
		/*parent::_initialize();
		$this->posts_model = D("Portal/Posts");
		$this->terms_model = D("Portal/Terms");
		$this->term_relationships_model = D("Portal/TermRelationships");*/
	}
	function index(){
		$questions = M('questions');
		$map = [];
		$count      = $questions->where($map)->count();
		$Page       = new \Think\Page($count,25);
		$show       = $Page->show();
		$list = $questions->where($map)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $i => $va) {
			switch ($va["type"]) {
				case '1':
					$list[$i]["type"] = "单选题";
					break;
				case '2':
					$list[$i]["type"] = "多选题";
					break;
				case '3':
					$list[$i]["type"] = "判断题";
					break;
				default:
					$list[$i]["type"] = "单选题";
					break;
			}
			$list[$i]["time"] = date("Y-m-d h:i:s", $va["time"]);
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign("count",$count);
		$this->display();
	}
	function addquestion()
	{
		$this->display();
	}
	function editquestion(){
		$map["id"] = $_GET["questionid"];
		$question = M("questions")->where($map)->find();
		$option = explode("|", $question["option"]);
		/*$question["option"] = array();
		for ($i=0; $i < count($option); $i++) { 
			$question["option"][chr(65) + $i] = $option[$i];
		}*/
		$question["option"] = $option;
		$question["answer"] = explode("|", $question["answer"]);
		$this->assign("que",$question);
		$this->display();
	}
	function fileaddquestion(){
		$this->display();
	}
	function ajaxAddquestion(){
		$question = $_POST['question'];
		$data["type"] = $question["type"];
		$data["question"] = $question["question"];
		$data["number"] = $question["number"];
		$data["option"] = implode("|", $question["option"]);
		$data["describe"] = $question["describe"];
		$data["time"] = time();
		switch ($question["type"]) {
			case '1':
				$data["answer"] = $question["answer"];
				break;
			case '2':
				$data["answer"] = implode("|", $question["multianswer"]);
				break;
			case '3':
				$data["answer"] = $question["judgeanswer"];
				$data["option"] = "";
				break;
			default:
				$data["answer"] = $question["answer"];
				break;
		}
		if (M("questions")->add($data)) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "新增出错";
		}
		$this->ajaxReturn($return);
	}
	function ajaxEditquestion(){
		$question = $_POST['question'];
		$data["id"] = $question["id"];
		$data["type"] = $question["type"];
		$data["question"] = $question["question"];
		$data["number"] = $question["number"];
		$data["option"] = implode("|", $question["option"]);
		$data["describe"] = $question["describe"];
		// $data["time"] = time();
		switch ($question["type"]) {
			case '1':
				$data["answer"] = $question["answer"];
				break;
			case '2':
				$data["answer"] = implode("|", $question["multianswer"]);
				break;
			case '3':
				$data["answer"] = $question["judgeanswer"];
				$data["option"] = "";
				break;
			default:
				$data["answer"] = $question["answer"];
				break;
		}
		if (M("questions")->where($map)->save($data) == 1) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "无修改";
		}
		$this->ajaxReturn($return);
	}
	function delQuestion(){
		$map["id"] = array("in",$_POST["id"]);
		if (M("questions")->where($map)->delete()) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "删除出错";
		}
		$this->ajaxReturn($return);
	}
}