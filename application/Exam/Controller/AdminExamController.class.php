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
class AdminExamController extends AdminbaseController {
	function _initialize() {
		//解析函数
		/*parent::_initialize();
		$this->posts_model = D("Portal/Posts");
		$this->terms_model = D("Portal/Terms");
		$this->term_relationships_model = D("Portal/TermRelationships");*/
	}
	function index(){
		$exams = M("exams")->where($map)->order("id DESC")->field("id,name,type,create_time")->select();
		$this->assign("exams",$exams);
		// dump($exams);
		$this->display();
	}
	function exam_rand(){
		$this->display();
	}
	function exam_manual(){
		$this->display();
	}
	function paper(){
		$map["id"] = $_GET["id"];
		$exam = M("exams")->where($map)->find();
		$settings = json_decode($exam["settings"]);
		$exam_questions = json_decode($exam["exam_questions"]);
		$ids = array_merge($exam_questions->single, $exam_questions->multi, $exam_questions->judge);
		$map["id"] = array("in",$ids);
		$questions = M("questions")->where($map)->select();
		for ($i=0; $i < count($questions); $i++) { 
			$list[$questions[$i]["id"]] = $questions[$i];
		}
		$this->assign("settings",$settings);
		$this->assign("exam_questions",$exam_questions);
		$this->assign("exam",$exam);
		$this->assign("list",$list);
		$this->display();
	}
	function editexam(){
		$map["id"] = $_GET["id"];
		$exam = M("exams")->where($map)->find();
		$settings = json_decode($exam["settings"]);
		$this->assign("exam",$exam);
		$this->assign("settings",$settings);
		$this->display();
	}
	function select_question(){
		$map["type"] = $_GET["type"];
		$questions = M("questions");
		$count      = $questions->where($map)->count();
		$Page       = new \Think\Page($count,25);
		$show       = $Page->show();
		$list = $questions->where($map)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		// $question = M("questions")->where($map)->select();
		$this->assign("questions",$list);
		$this->assign('page',$show);
		$this->assign("count",$count);
		$this->assign("type",$_GET["type"]);
		$this->display();
	}
	function view_question(){
		$type = $_POST["type"];
		switch ($type) {
			case '1':
				$type_name = "single";
				break;
			case '2':
				$type_name = "multi";
				break;
			case '3':
				$type_name = "judge";
				break;
		}
		$ids = $_POST["exam_questions"][$type_name];
		if (count($ids) == 0) {
			$ids[0] = 0;
		}
		$map["type"] = $type;
		$map["id"] = array("in",$ids);
		$questions = M("questions")->where($map)->select();
		foreach ($questions as $num => $va) {
			if ($va["type"] == "3") {
				$questions[$num]["option"] = array("A、对","B、错");
			}else{
				$option = explode("|", $va["option"]);
				for ($i=0; $i < count($option); $i++) { 
					$option[$i] = chr(65 + $i)."、".$option[$i];
				}
				$questions[$num]["option"] = $option;
			}
		}
		$this->assign("questions",$questions);
		$this->display();
	}
	function ajaxAddExam(){
		$data = $_POST["data"];
		$map["name"] = $data["name"];
		$count = M("exams")->where($map)->count();
		if ($count > 0) {
			$return["status"] = 0;
			$return["info"] = "试卷名称重复";
		}
		$data["create_time"] = time();
		$data["type"] = "手工组卷";
		if (M("exams")->add($data)) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "新增出错";
		}
		$this->ajaxReturn($return);
	}
	function ajaxEditExam(){
		$data = $_POST["data"];
		if (M("exams")->save($data)) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "无修改";
		}
		$this->ajaxReturn($return);
	}
	function ajaxAddRandExam(){
		$data = $_POST["data"];
		$map["name"] = $data["name"];
		$count = M("exams")->where($map)->count();
		if ($count > 0) {
			$return["status"] = 0;
			$return["info"] = "试卷名称重复";
		}
		$settings = json_decode($data["settings"]);
		$allquestion = M("questions")->field("id,type")->select();
		foreach ($allquestion as $key => $va) {
			$forRand[$va["type"]][] = $va["id"];
		}
		$return["info"] = "";
		$return["status"] = 1;
		if (count($forRand["1"]) < $settings->single->number) {
			$return["info"] .= "单选题题库数量不足  ";
			$return["status"] = 0;
		}
		if (count($forRand["2"]) < $settings->multi->number) {
			$return["info"] .= "多选题题库数量不足  ";
			$return["status"] = 0;
		}
		if (count($forRand["3"]) < $settings->judge->number) {
			$return["info"] .= "判断题题库数量不足  ";
			$return["status"] = 0;
		}
		if ($return["status"] == 0) {
			$return["info"] .= "请在题库增加题量或减少所需题量";
			$this->ajaxReturn($return);
		}
		$count = 0;
		while ($count < $settings->single->number) {
			$i = rand(0,count($forRand["1"])-1);
			$tmp = $forRand["1"][$i];
			if (!in_array($tmp, $exam_questions["single"])) {
				$exam_questions["single"][] = (int)$tmp;
			}
			$count = count($exam_questions["single"]);
		}
		$count = 0;
		while ($count < $settings->multi->number) {
			$i = rand(0,count($forRand["2"])-1);
			$tmp = $forRand["2"][$i];
			if (!in_array($tmp, $exam_questions["multi"])) {
				$exam_questions["multi"][] = (int)$tmp;
			}
			$count = count($exam_questions["multi"]);
		}
		$count = 0;
		while ($count < $settings->judge->number) {
			$i = rand(0,count($forRand["3"])-1);
			$tmp = $forRand["3"][$i];
			if (!in_array($tmp, $exam_questions["judge"])) {
				$exam_questions["judge"][] = (int)$tmp;
			}
			$count = count($exam_questions["judge"]);
		}
		$data["create_time"] = time();
		$data["type"] = "随机组卷";
		$data["exam_questions"] = json_encode($exam_questions);
		if (M("exams")->add($data)) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "新增出错";
		}
		$this->ajaxReturn($return);
	}
	function delExam(){
		$map["id"] = $_POST["id"];
		if (M("exams")->where($map)->delete()) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "删除出错";
		}
		$this->ajaxReturn($return);
	}
	
}