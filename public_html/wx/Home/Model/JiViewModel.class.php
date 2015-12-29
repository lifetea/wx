<?php
namespace Home\Model;
use Think\Model\ViewModel;
class JiViewModel extends ViewModel {
   public $viewFields = array(
     'User'=>array('id','headimgurl','unionid','_type'=>'right'),
     'Ji'=>array('userid','friendid','_on'=>'User.id = Ji.friendid'),
   );
 }

