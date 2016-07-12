<?php 
namespace Exam\Model;
use Think\Model\ViewModel;
class UserDepartmentViewModel extends ViewModel {
   public $viewFields = array(
     'scores'=>array('id','userid','username','examid','examname','score','is_pass','time','result'),
     'users'=>array('department_name','_on'=>'scores.userid=users.id'),
   );
 }
 ?>