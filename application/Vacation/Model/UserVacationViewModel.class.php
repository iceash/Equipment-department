<?php
namespace Vacation\Model;
use Think\Model\ViewModel;
class UserVacationViewModel extends ViewModel {
    public $Vacation = array(
        'scores'=>array('id','danwei','ren','didian','time','psren','endtime','note'),
        'users'=>array('department_name','_on'=>'scores.userid=users.id'),
    );
}
?>