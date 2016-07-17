<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Vacation\Controller;
use Common\Controller\AdminbaseController;
class AdminController extends AdminbaseController {
	function _initialize() {
		//解析函数
		/*parent::_initialize();
		$this->posts_model = D("Portal/Posts");
		$this->terms_model = D("Portal/Terms");
		$this->term_relationships_model = D("Portal/TermRelationships");*/
	}
	//新增休假
	public function add(){
		$Form   =   D('vacation');
		if($Form->create()) {
			$vacation =   $Form->add();
			if($vacation) {$this->success('数据添加成功！');}
			else{$this->error('数据添加错误！');}
		}
		$this->display();
	}
	//部门
	function lst(){
		$dep_list = M("department")->group("department_name")->select();
		$this->assign("dep_list",$dep_list);
		if(isset($_POST['department']) && $_POST['department']!=""){
			$map['danwei']=$_POST['department'];
			$this->assign("department",$_POST['department']);
		}
		//考生名称
		if(isset($_POST['username']) && $_POST['username']!=""){
			$map['ren'] = $_POST['username'];
			$this->assign("username",$_POST['username']);
		}
		$vacation=M('vacation')->where($map)->order("id DESC")->select();
		$this->assign("vacation",$vacation);
		$this->display();
	}












}

