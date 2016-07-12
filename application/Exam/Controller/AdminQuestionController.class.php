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
		//起始结束时间
		$start_time="2000-01-01 00:00";
		if(isset($_POST['start_time']) && $_POST['start_time']!=""){
			$start_time=$_POST['start_time'];
			$this->assign("start_time",$start_time);
		}
		$map["time"][0] = array('gt',strtotime($start_time.":00"));
		$end_time="2030-12-30 00:00";
		if(isset($_POST['end_time']) && $_POST['end_time']!=""){
			$end_time=$_POST['end_time'];
			$this->assign("end_time",$end_time);
		}
		$map["time"][1] = array('lt',strtotime($end_time.":00"));
		if(isset($_POST['questionid']) && $_POST['questionid']!=""){
			$map["id"]=$_POST['questionid'];
			$this->assign("id",$map["id"]);
		}
		if(isset($_POST['keyword']) && $_POST['keyword']!=""){
			$keyword=$_POST['keyword'];
			$map["question"] = array("like","%$keyword%");
			$this->assign("keyword",$keyword);
		}
		if(isset($_POST['type']) && $_POST['type']!="" && $_POST["type"]!="0"){
			$map["type"] = $_POST['type'];
			$this->assign("type",$map["type"]);
		}
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
			$list[$i]["time"] = date("Y-m-d H:i:s", $va["time"]);
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
	function uploadfile(){
		$filename = $_FILES['file']['tmp_name']; 
		$realname = $_FILES["file"]["name"];
		// 获得文件扩展名
		$temp_arr = explode(".", $realname);
		$file_ext = array_pop($temp_arr);
		$file_ext = trim($file_ext);
		$file_ext = strtolower($file_ext); 
		$filelocal = dirname(__FILE__) . '\..\..\..';
		$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext; 
		$total = $filelocal . "\upload/" . $new_file_name;
	    move_uploaded_file($_FILES["file"]["tmp_name"], $total);
	    if (empty ($total)) {
	        echo '请选择要导入的CSV文件！'; 
	        exit; 
	    } 
	    $handle = fopen($total, 'r'); 
	    $result = $this->input_csv($handle); //解析csv 
	    $len_result = count($result); 
	    if($len_result==0){ 
	    	$this->error("没有任何数据！");
	    } 
	    for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值 
	    	$tmpi = $i + 2;
	    	$type = $result[$i][0];
	    	switch ($type) {
	    		case '选择题':
	    			$data[$i-1]["type"] = 1;
	    			break;
	    		case '多选题':
	    			$data[$i-1]["type"] = 2;
	    			break;
	    		case '判断题':
	    			$data[$i-1]["type"] = 3;
	    			break;
	    		default:
			    	$this->error("第$tmp行题型输入错误");
	    			break;
	    	}
	        $data[$i-1]["question"] = $result[$i][1];
	        $data[$i-1]["answer"] = $result[$i][3];
	        if ($data[$i-1]["question"] == "" || $data[$i-1]["answer"] == "") {
			    	$this->error("第$tmp行题目不完整错误");
	        }
	        $data[$i-1]["describe"] = $result[$i][4];
	        $data[$i-1]["time"] = time();
	    	if ($type == "判断题") {
		        $data[$i-1]["option"] = "";
	    	}else{
		        $data[$i-1]["option"] = $result[$i][2];
	    	}
	        $options = explode("|", $data[$i-1]["option"]);
	        $data[$i-1]["number"] = count($options);
	    } 
	    	M("questions")->addAll($data);
	    	$this->success("上传成功","index",0.5);

	    // $data_values = substr($data_values,0,-1); //去掉最后一个逗号
		return 0;
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
	function input_csv($handle) { 
	    $out = array (); 
	    $n = 0; 
	    while ($data = fgetcsv($handle)) { 
	    	$data = eval('return '.iconv('gbk','utf-8',var_export($data,true)).';');
	        $num = count($data); 
	        for ($i = 0; $i < $num; $i++) { 
	            $out[$n][$i] = $data[$i]; 
	        } 
	        $n++; 
	    } 
	    return $out; 
	} 
}