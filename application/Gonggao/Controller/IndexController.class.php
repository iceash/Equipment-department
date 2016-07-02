<?php
namespace Gonggao\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	
	function index(){
		$screen=D("Common/NoticeScreen")->where("screen_status=1")->order("listorder ASC")->select();
		$this->assign('screen',$screen);
		$interval=D("Common/Notice")->where("department='测试'")->getField("interval");
		$this->assign('interval',$interval);
		$this->display();
	}
}