<?php
namespace Home\Model;
use Think\Model\ViewModel;
class ScoreViewModel extends ViewModel {
   public $viewFields = array(
     'User'=>array('id','nickname','openid','headimgurl','unionid','_type'=>'right'),
     'Log'=>array('userid','score', '_on'=>'User.id = Log.userid'),
   );
 }

