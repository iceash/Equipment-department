<?php
namespace Exam\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	function _initialize() {
		//解析函数
		// parent::_initialize();
		$userid = $_SESSION["user"]["id"];
		if(!$userid){
			$this->error("您还没有登录！",U("User/login/index"));
		}
	}
	function index(){
		$exams = M("exams")->field("id,name")->select();
		$this->assign("exams",$exams);
		$map["userid"] = $_SESSION["user"]["id"];
		$scores = M("scores")->where($map)->field("id,examname,time")->select();
		$this->assign("scores",$scores);
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
    function submit_paper(){
    	$answers = $_POST;
    	$map["id"] = $answers["examid"];
    	$exam = M("exams")->where($map)->find();
		$settings = json_decode($exam["settings"]);
		$exam_questions = json_decode($exam["exam_questions"]);
		$qusetion_ids = array_merge($exam_questions->single, $exam_questions->multi, $exam_questions->judge);
		$map["id"] = array("in",$qusetion_ids);
		$questions = M("questions")->where($map)->select();
		for ($i=0; $i < count($questions); $i++) { 
			$list[$questions[$i]["id"]] = $questions[$i];
		}
		//answer中存放考生提交的答案
		//list中以题目id为索引存放题目完整信息
		//exam_questions中以题型为索引存放题目id
		//result存放答题的完整结果信息
		$get_score = 0;
		for ($i=0; $i < $settings->single->number; $i++) { 
			$id = $exam_questions->single[$i];
			$tmp["question"] = $list[$id]["question"];
			$tmp["type"] = "single" ;
			$tmp["option"] = $list[$id]["option"];
			$tmp["selected"] = $answers["radio_answer"][$id];
			$tmp["answer"] = $list[$id]["answer"];
			$tmp["describe"] = $list[$id]["describe"];
			$tmp["score"] = $settings->single->score;
			if ($tmp["selected"] == $tmp["answer"]) {
				$tmp["bingo"] = 1;
				$get_score += $tmp["score"];
			}else{
				$tmp["bingo"] = 0;
			}
			$result[] = $tmp;
		}

		for ($i=0; $i < $settings->multi->number; $i++) { 
			$id = $exam_questions->multi[$i];
			$tmp["question"] = $list[$id]["question"];
			$tmp["type"] = "multi" ;
			$tmp["option"] = $list[$id]["option"];
			$tmp["selected"] = implode("|", $answers["checkbox_answer"][$id]);
			$tmp["answer"] = $list[$id]["answer"];
			$tmp["describe"] = $list[$id]["describe"];
			$tmp["score"] = $settings->multi->score;
			if ($tmp["selected"] == $tmp["answer"]) {
				$tmp["bingo"] = 1;
				$get_score += $tmp["score"];
			}else{
				$tmp["bingo"] = 0;
			}
			$result[] = $tmp;
		}

		for ($i=0; $i < $settings->judge->number; $i++) { 
			$id = $exam_questions->judge[$i];
			$tmp["question"] = $list[$id]["question"];
			$tmp["type"] = "judge" ;
			$tmp["option"] = $list[$id]["option"];
			$tmp["selected"] = $answers["judge_answer"][$id];
			$tmp["answer"] = $list[$id]["answer"];
			$tmp["describe"] = $list[$id]["describe"];
			$tmp["score"] = $settings->judge->score;
			if ($tmp["selected"] == $tmp["answer"]) {
				$tmp["bingo"] = 1;
				$get_score += $tmp["score"];
			}else{
				$tmp["bingo"] = 0;
			}
			$result[] = $tmp;
		}
		$data["userid"] = $_SESSION["user"]["id"];
		$data["username"] = $_SESSION["user"]["user_nicename"];
		$data["examid"] = $answers["examid"];
		$data["examname"] = $exam["name"];
		$data["score"] = $get_score;
		if ($get_score >= $exam["pass_line"]) {
			$data["is_pass"] = 1;
		}else{
			$data["is_pass"] = 0;
		}
		$data["time"] = time();
		$data["result"] = json_encode($result);
		if (M("scores")->add($data)) {
			$back["status"] = 1;
			$back["info"] = "交卷成功，您的得分为".$get_score."分";
		}else{
			$back["status"] = 0;
			$back["info"] = "交卷失败";
		}
		$this->ajaxReturn($back);
    }
}