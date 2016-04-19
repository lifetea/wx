<?php
namespace Home\Model;
use Think\Model\ViewModel;
class PlayerViewModel extends ViewModel {
   public $viewFields = array(
     'User'=>array('id','username','nickname','headimgurl','_type'=>'right'),
     'Zu_log'=>array('friend','_on'=>'User.id = Zu_log.friend'),
   );
 }

