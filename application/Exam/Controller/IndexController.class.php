<?php
namespace Exam\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	
	function index(){
        $this->display();
	}
    function paper(){
        $this->display();
    }
}