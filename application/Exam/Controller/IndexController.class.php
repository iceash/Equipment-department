<?php
namespace Exam\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	function _initialize() {
		//解析函数
		// parent::_initialize();
		$userid = $_SESSION["ADMIN_ID"];
		if(!$userid){
			$this->error("您还没有登录！",U("User/login/index"));
		}else{
			$map["id"] = $userid;
			$user = M("users")->where($map)->find();
			$_SESSION["nicename"] = $user["user_nicename"];
		}
	}
	function index(){
		$condition["userid"] = array("in",array(0,$_SESSION["ADMIN_ID"]));
		$exams = M("exams")->field("id,name")->where($condition)->order("create_time desc")->select();
		$this->assign("exams",$exams);
		$map["userid"] = $_SESSION["ADMIN_ID"];
		$scores = M("scores")->where($map)->field("id,examname,time")->order("time desc")->select();
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
		$data["userid"] = $_SESSION["ADMIN_ID"];
		$data["username"] = $_SESSION["nicename"];
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
    function exam_rand(){
    	$questions = M("questions");
    	$countSingle = $questions->where("type=1")->count();
    	$countMulti = $questions->where("type=2")->count();
    	$countJudge = $questions->where("type=3")->count();
    	$this->assign("countSingle",$countSingle);
    	$this->assign("countMulti",$countMulti);
    	$this->assign("countJudge",$countJudge);
		$this->display();
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
		$data["userid"] = $_SESSION["ADMIN_ID"];
		$data["exam_questions"] = json_encode($exam_questions);
		if (M("exams")->add($data)) {
			$return["status"] = 1;
		}else{
			$return["status"] = 0;
			$return["info"] = "新增出错";
		}
		$this->ajaxReturn($return);
	}
}