<?php
return array(
	//'配置项'=>'配置值'
	//
	'SRC'=>"http://wx.vlegend.cn/Public",
	//微信菜单
	// {
	//    	"type":"click",
	//        "name":"准备中",
	//        "key":"zhunbei"
	//    },

	//  {
	//     "type":"view",
	//     "name":"个人主页",
	//     "url":"http://wx.vlegend.cn/ji?self=1"
	// },


	// {
	//     "type":"view",
	//     "name":"游戏排名",
	//     "url":"http://wx.vlegend.cn/best"
	// },


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
//						{
//							"type":"click",
//						"name":"准备中",
//						"key":"zhunbei"
//						},

//		            {
//						"type":"view",
//		                "name":"积分排名",
//		                "url":"http://wx.vlegend.cn/top"
//		            },
//
//			        {
//						"type":"view",
//		                "name":"快速刷分",
//		                "url":"http://wx.vlegend.cn/ji?self=1"
//		            },
	// "button":[
	// {
	//      "name":"关于爱车",
	//      "sub_button":[
	//       {
	//          "type":"view",
	//          "name":"我要硬件",
	//          "url":"http://t.cn/RUFWwMN"
	//       },
	//       {
	//          "type":"view",
	//          "name":"我要软件",
	//          "url":"http://wx.vlegend.cn/soft"
	//       }]
	//  },

	/*
    *			       {
                        "type":"view",
                        "name":"游戏中心",
                        "url":"http://wx.vlegend.cn/bird.html"
                       },
    */

	'menuJson'=>'{
			"button":[
		       {
		           "name":"车侣威擎",
		           "sub_button":[
			       {
					"type":"view",
					"name":"APP下载",
					"url":"http://a.app.qq.com/o/simple.jsp?pkgname=cn.vlegend.app"
			       },
					{
		                "type":"view",
		                "name":"关于我们",
		                "url":"http://mp.weixin.qq.com/s?__biz=MzI0MDA5OTY1NA==&mid=402633080&idx=1&sn=9d1c56bd10410cf996c9d00c2fc52a91&scene=1&srcid=0312y4NvoCo1LZ9lycj4Thgg#rd"
		            }]
		       },
		       {
		           "name":"礼品派送",
		           "sub_button":[

						{
							"type":"click",
							"name":"红包活动",
							"key":"hb"
						}
		            ]
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
		               "type":"click",
		               "name":"每日签到",
		               "key":"qiandao"
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