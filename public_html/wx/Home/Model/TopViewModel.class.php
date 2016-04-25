<?php
namespace Home\Model;
use Think\Model\ViewModel;
class TopViewModel extends ViewModel {
   public $viewFields = array(
     'User'=>array('id','username','nickname','headimgurl','_type'=>'right'),
     'Money_log'=>array('money','_on'=>'User.id = Money_log.uid'),
   );
 }

