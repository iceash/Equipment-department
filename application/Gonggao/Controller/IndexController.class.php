<?php
namespace Gonggao\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	
	function index(){
		$department="'".$_GET['department']."'";
		$screen=D("Common/NoticeScreen")->where("screen_status=1 and department=".$department)->order("listorder ASC")->select();
		$this->assign('screen',$screen);
		$interval=D("Common/Notice")->where("department=".$department)->getField("interval");
		$this->assign('interval',$interval);
		$this->display();
	}
}