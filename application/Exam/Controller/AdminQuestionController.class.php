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
		// echo "string";
		$this->display();
	}
	function addquestion()
	{
		$this->display();
	}
	function editquestion(){
		$this->display();
	}
	function fileaddquestion(){
		$this->display();
	}
	
}