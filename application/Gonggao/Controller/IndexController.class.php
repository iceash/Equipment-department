<?php
namespace Gonggao\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	
	function index(){
		$screen=D("Common/NoticeScreen")->where("screen_status=1")->order("listorder ASC")->select();
		$this->assign('screen',$screen);
		$this->display();
	}
}