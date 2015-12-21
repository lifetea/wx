<?php
namespace Home\Model;
use Think\Model\ViewModel;
class BestViewModel extends ViewModel {
   public $viewFields = array(
     'User'=>array('id','nickname','headimgurl','_type'=>'right'),
     'Best'=>array('score', '_on'=>'User.id = Best.userid'),
   );
 }

