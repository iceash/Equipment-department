<?php
namespace Attendance\Controller;
use Common\Controller\AdminbaseController;

class IndexController extends AdminbaseController{
	
	protected $dictionary_model;
	protected $attend_model;
	//protected $slidecat_model;
	
	function _initialize() {
		parent::_initialize();
		$this->dictionary_model = D("Common/Dictionary");
		$this->attend_model = D("Common/Attend");
		//$this->slidecat_model = D("Common/SlideCat");
		
	}
	
	function index(){
		if(isset($_POST['department']) && $_POST['department']!=""){
			$where['department']=$_POST['department'];
			$this->assign("department",$_POST['department']);
		}
		$attend_date=date('Y-m-d');
		if(isset($_POST['attend_date']) && $_POST['attend_date']!=""){
			$where['attend_date']=$_POST['attend_date'];
			$attend_date=$_POST['attend_date'];
		}
		$this->assign("attend_date",$attend_date);
		$ijo_count=$this->attend_model->where($where)->where("status='异常'")->count();
		$this->assign("ijo_count",$ijo_count);
		if(isset($_POST['keyword']) && $_POST['keyword']!=""){
			$keyword=$_POST['keyword'];
			$where['_string']='(name like "%'.$keyword.'%")OR(badge_number like "%'.$keyword.'%")';
			$this->assign("keyword",$keyword);
		}
		$attend=$this->attend_model->where($where)->order("name ASC")->select();
		$this->assign('attend',$attend);
		$department_list=$this->attend_model->group('department')->Field('department')->select();
		$this->assign("dep_list",$department_list);
		$this->display();
	}

	function setTime(){
		$where['key']='on_time_rule';
		$on_time_rule=$this->dictionary_model->where($where)->getField("value");
		$where['key']='off_time_rule';
		$off_time_rule=$this->dictionary_model->where($where)->getField("value");
		$this->assign("on_time_rule",$on_time_rule);
		$this->assign("off_time_rule",$off_time_rule);
		$this->display();
	}
	
	function setTime_post(){
		if(IS_POST){
			if($_POST['on_time_rule']&&$_POST['off_time_rule']){
				$on_check=strtotime($_POST['on_time_rule']);
				$off_check=strtotime($_POST['off_time_rule']);
				if($on_check&&$off_check){
					$where['key']='on_time_rule';
					$save_on=$this->dictionary_model->where($where)->setField("value",$_POST['on_time_rule']);
					$where['key']='off_time_rule';
					$save_off=$this->dictionary_model->where($where)->setField("value",$_POST['off_time_rule']);
					if ($save_on||$save_off!==false) {
						$this->success("设置成功！", U("index/index"));
					} else {
						$this->error("设置失败！");
					}					
				}else{
					$this->error("时间格式不正确");
				}
			}else{
				$this->error("时间不能为空");
			}
		}
	}
	function edit_index(){
		if(isset($_POST['ids'])){
			$ids = implode(",", $_POST['ids']);
			$data['status']=$_POST['status'];
			$data['content']=$_POST['content'];
			if ($this->attend_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("修改成功！");
			} else {
				$this->error("修改失败！");
			}
		}
	}
}