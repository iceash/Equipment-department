<?php
namespace Attendance\Controller;
use Common\Controller\AdminbaseController;

class IndexController extends AdminbaseController{
	
	protected $dictionary_model;
	protected $attend_model;
	//protected $slidecat_model;
	
	function _initialize() {
		//parent::_initialize();
		$this->dictionary_model = D("Common/Dictionary");
		$this->attend_model = D("Common/Attend");
		//$this->slidecat_model = D("Common/SlideCat");
		
	}
	function index()
	{
		if(isset($_POST['department']) && $_POST['department']!=""){
			$where['department']=$_POST['department'];
			$this->assign("department",$_POST['department']);
		}
		$start_time=date('Y-m-d')." 00:00";
		if(isset($_POST['start_time']) && $_POST['start_time']!=""){
			$start_time=$_POST['start_time'];
		}
		$this->assign("start_time",$start_time);
		$end_time=date('Y-m-d')." 23:59";
		if(isset($_POST['end_time']) && $_POST['end_time']!=""){
			$end_time=$_POST['end_time'];
		}
		$this->assign("end_time",$end_time);
		$where['CHECKTIME']=array(array('gt',$start_time),array('lt',$end_time));
		if(isset($_POST['keyword']) && $_POST['keyword']!=""){
			$keyword=$_POST['keyword'];
			$where['_string']='(name like "%'.$keyword.'%")OR(badge_number like "%'.$keyword.'%")';
			$this->assign("keyword",$keyword);
		}
		$attend=M('checkinout')->where($where)->order("CHECKTIME desc")->select();
		$this->assign('attend',$attend);
		$department_list=M('department')->Field('department_name')->select();
		$this->assign("dep_list",$department_list);
		$this->display();
	}
	function attend(){
		if(isset($_POST['department']) && $_POST['department']!=""){
			$where['department']=$_POST['department'];
			$this->assign("department",$_POST['department']);
		}
		$attend_date=date('Y-m-d',strtotime("-1 day"));
		if(isset($_POST['attend_date']) && $_POST['attend_date']!=""){
			$attend_date=$_POST['attend_date'];
		}
		$where['attend_date']=$attend_date;
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
		$department_list=M('department')->Field('department_name')->select();
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
	function add_attend(){
		$users=M('users_role')->where('role_id=3')->Field('user_nicename,department_name')->select();
		$Attend=M('attend');
		$today=date('Y-m-d');
		$week=date('w');
		if($week=='0'||$week=='6'){
			$is_weekend=1;//测试时修改这里
		}else{
			$is_weekend=0;
		}
		$wd['key']='on_time_rule';
		$on=M('dictionary')->where($wd)->getField('value');
		$wd['key']='off_time_rule';
		$off=M('dictionary')->where($wd)->getField('value');
		for ($i=0; $i < count($users); $i++) { 
			$data['name']=$users[$i]['user_nicename'];
			$data['department']=$users[$i]['department_name'];
			$data['attend_date']=$today;
			$Attend->add($data);
			$map['NAME']=$users[$i]['user_nicename'];
			$map['CHECKTIME']=array(array('gt',$today." 00:00"),array('lt',$today." 23:59"));
			$check_time=M('checkinout')->where($map)->Field('CHECKTIME')->order('CHECKTIME')->select();
			if(count($check_time)==2){
				$data2['on_time']=date('H:i:s', strtotime($check_time[0]['checktime']));
				$data2['off_time']=date('H:i:s', strtotime($check_time[1]['checktime']));
			}elseif (count($check_time)==1) {
				$data2['on_time']=date('H:i:s', strtotime($check_time[0]['checktime']));
			}
			if($is_weekend){
				$data2['status']='正常';
				$data2['content']='周末';
			}else{
				if(count($check_time)==0){
					$data2['status']='异常';
					$data2['content']='缺勤';
				}elseif (count($check_time)==1) {
					$data2['status']='异常';
					$data2['content']='记录缺失';
				}else{
					$on_cha=strtotime($check_time[0]['checktime'])-strtotime($today." ".$on);
					$off_cha=strtotime($check_time[1]['checktime'])-strtotime($today." ".$off);
					$data2['status']='正常';
					$data2['content']="";
					if($on_cha>0){
						$data2['status']='异常';
						$data2['content']=$data2['content'].'迟到';
					}
					if($off_cha<0){
						$data2['status']='异常';
						$data2['content']=$data2['content'].'早退';
					}
				}
			}
			$Attend->where($data)->save($data2);
			$data2='';
		}
		echo('success');
	}
}