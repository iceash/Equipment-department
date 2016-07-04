<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Department\Controller;
use Common\Controller\AdminbaseController;
class AdminController extends AdminbaseController {
	function _initialize() {
		//解析函数
		/*parent::_initialize();
		$this->posts_model = D("Portal/Posts");
		$this->terms_model = D("Portal/Terms");
		$this->term_relationships_model = D("Portal/TermRelationships");*/
	}

	function index(){
		// echo "string";
		$this->display();
	}

	public function create(){
		$department=M('department')->where($map)->order("id DESC")->select();
		$this->assign("department",$department);
        $this->display();

	}

	public function add(){

		$Form   =   D('department');
		if($Form->create()) {
			$department =   $Form->add();
			if($department) {$this->success('数据添加成功！');}
			else{$this->error('数据添加错误！');}

		}
			$this->display();


	}

}



