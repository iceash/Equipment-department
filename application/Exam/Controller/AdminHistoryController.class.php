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
	function _initialize() {
		if ($_SESSION["ADMIN_ID"] != 1) {
			$this->error("您没有访问权限！");
    			exit();
		}
	}
	function index(){
		$scores = new \Exam\Model\UserDepartmentViewModel();
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
		//查询所在部门
		$dep_list = M("department")->group("department_name")->select();
		$this->assign("dep_list",$dep_list);
		if(isset($_POST['department']) && $_POST['department']!=""){
			$map['department_name']=$_POST['department'];
			$this->assign("department",$_POST['department']);
		}
		//考试名称
		if(isset($_POST['examname']) && $_POST['examname']!=""){
			$map['examname'] = array("like",'%'.$_POST['examname'].'%');
			$this->assign("examname",$_POST['examname']);
		}
		//考生名称
		if(isset($_POST['username']) && $_POST['username']!=""){
			$map['username'] = $_POST['username'];
			$this->assign("username",$_POST['username']);
		}
		//分数
		$min_score = 0;
		if (isset($_POST["min_score"]) && $_POST["min_score"]!="") {
			$min_score = $_POST["min_score"];
			$this->assign("min_score",$min_score);
		}
		$map["score"][0] = array("gt",$min_score);
		$max_score = 100000;
		if (isset($_POST["max_score"]) && $_POST["max_score"]!="") {
			$max_score = $_POST["max_score"];
			$this->assign("max_score",$max_score);
		}
		$map["score"][1] = array("lt",$max_score);

		$count      = $scores->where($map)->count();
		$Page       = new \Think\Page($count,25);
		$show       = $Page->show();
		$list = $scores->where($map)->order('time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $i => $va) {
			$list[$i]["submit_time"] = date("Y-m-d H:i:s", $va["time"]);
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