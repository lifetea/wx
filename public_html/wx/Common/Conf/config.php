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

		  //      {
				// "type":"view",
				// "name":"来玩我",
				// "url":"http://wx.vlegend.cn/sdgs.html?cert=1"
		  //      },
			           //  {
		            //    "type":"click",
		            //    "name":"每日签到",
		            //    "key":"qiandao"
		            // },
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
		               "type":"view",
		               "name":"我要软件",
		               "url":"http://wx.vlegend.cn/soft"
		            }]
		       },
			    {
		        	"type":"click",
		            "name":"准备中",
		            "key":"zhunbei"
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
		                "name":"积分排名",
		                "url":"http://wx.vlegend.cn/top"
		            },
		            {
		                "type":"view",
		                "name":"游戏排名",
		                "url":"http://wx.vlegend.cn/best"
		            },
		            {
		                "type":"view",
		                "name":"个人主页",
		                "url":"http://wx.vlegend.cn/ji?self=1"
		            },			            	            
					{
		                "type":"view",
		                "name":"兑换奖品",
		                "url":"http://wx.vlegend.cn/gift"
		            }]
		       }
		      ]
		 }',
);