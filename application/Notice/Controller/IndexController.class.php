<?php
namespace Notice\Controller;
use Common\Controller\AdminbaseController;

class IndexController extends AdminbaseController{
	
	protected $screen_model;
	//protected $slidecat_model;
	
	function _initialize() {
		parent::_initialize();
		$this->screen_model = D("Common/NoticeScreen");
		//$this->slidecat_model = D("Common/SlideCat");
		
	}
	
	function index(){
		$status=1;
		$where="";
		if(isset($_POST['status']) && $_POST['status']!=""){
			$status=$_POST['status'];
		}
		$where="screen_status=$status";
		$this->assign("status",$status);
		$screen=$this->screen_model->where($where)->order("listorder ASC")->select();
		$this->assign('screen',$screen);
		$this->display();
	}
	
	function add(){
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			if ($this->screen_model->create()) {
				$_POST['screen_background']=sp_asset_relative_url($_POST['screen_background']);
				if ($this->screen_model->add()!==false) {
					$this->success("添加成功！", U("index/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->screen_model->getError());
			}
		}
	}
	
	function edit(){
		$id= intval(I("get.id"));
		$screen=$this->screen_model->where("screen_id=$id")->find();
		$this->assign($screen);
		$this->display();
	}
	
	function edit_post(){
		if(IS_POST){
			if ($this->screen_model->create()) {
				$_POST['screen_background']=sp_asset_relative_url($_POST['screen_background']);
				if ($this->screen_model->save()!==false) {
					$this->success("保存成功！", U("index/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->screen_model->getError());
			}
				
		}
	}
	
	function delete(){
		if(isset($_POST['ids'])){
			$ids = implode(",", $_POST['ids']);
			$data['screen_status']=0;
			if ($this->screen_model->where("screen_id in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			$id = intval(I("get.id"));
			if ($this->screen_model->delete($id)!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		
	}
	
	function toggle(){
		if(isset($_POST['ids']) && $_GET["display"]){
			$ids = implode(",", $_POST['ids']);
			$data['screen_status']=1;
			if ($this->screen_model->where("screen_id in ($ids)")->save($data)!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["hide"]){
			$ids = implode(",", $_POST['ids']);
			$data['screen_status']=0;
			if ($this->screen_model->where("screen_id in ($ids)")->save($data)!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	    //隐藏
	function ban(){
		
    	$id=intval($_GET['id']);
			$data['screen_status']=0;
    	if ($id) {
    		$rst = $this->screen_model->where("screen_id in ($id)")->save($data);
    		if ($rst) {
    			$this->success("公告隐藏成功！");
    		} else {
    			$this->error('公告隐藏失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    //显示
    function cancelban(){
    	$id=intval($_GET['id']);
		$data['screen_status']=1;
    	if ($id) {
    		$result = $this->screen_model->where("screen_id in ($id)")->save($data);
    		if ($result) {
    			$this->success("公告启用成功！");
    		} else {
    			$this->error('公告启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
	//排序
	public function listorders() {
		$status = parent::_listorders($this->screen_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
}