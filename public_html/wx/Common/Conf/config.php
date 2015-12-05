<?php
return array(
	//'配置项'=>'配置值'
	//
	'SRC'=>"http://wx.vlegend.cn/Public",
	//微信菜单
			           //  {
		            //    "type":"view",
		            //    "name":"违章查询",
		            //    "url":"http://t.cn/RvDTIdz"
		            // }
	'menuJson'=>'{
		      "button":[
		      {
		           "name":"关于爱车",
		           "sub_button":[
		            {
		               "type":"view",
		               "name":"我要硬件",
		               "url":"http://t.cn/RUFWwMN"
		            },
		            {
		               "type":"click",
		               "name":"我要软件",
		               "key":"hongbao"
		            }]
		       },
		       {
				"type":"view",
				"name":"来玩我",
				"url":"http://t.cn/RUDmi8I"
		       },		       
		       {
		           "name":"积分兑换",
		           "sub_button":[
		            {
		               "type":"view",
		               "name":"积分规则",
		               "url":"http://t.cn/RUsifc2"
		            },
		            {
		               "type":"view",
		               "name":"每日签到",
		               "url":"http://t.cn/Rykc5qP"
		            },
		            {
		                "type":"view",
		                "name":"积分排名",
		                "url":"http://t.cn/RykccaW"
		            },
					{
		                "type":"view",
		                "name":"兑换奖品",
		                "url":"http://t.cn/RykccaW"
		            }]
		       }
		      ]
		 }',
);