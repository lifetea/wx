<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!-- saved from url=(0062)http://www.html5tricks.com/demo/html5-css3-windmill/index.html -->
<html class=" -webkit-"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta charset="UTF-8">

  <title>HTML5/CSS3实现大风车旋转动画DEMO演示</title>

    <style>

body{
  padding:0px;
  margin:0px;
  background:hsl(212,50%,50%);
}

.sun {
  width:40px;
  height:40px;
  border-radius:360px;
  background:white;
  right:40px;
  top:-120px;
  position:absolute;
  	animation-name: sunrise;
	  animation-duration: 1s;
	  animation-timing-function: ease;
	  animation-iteration-count: 1;	
	  animation-direction: normal;
	  animation-delay: .1;
	  animation-play-state: running;
	  animation-fill-mode: forwards;
}

@keyframes sunrise {

	0% {
		  top: -120px;
	}

25% {
  top:19px;
  right: 40px;
	}

50% {
  top:25px;
  right: 40px;
	}

100% {
  top:18px;
  right: 40px;
	}

}

.wmd1{
   -webkit-transform: scale(.6);
  position:absolute;
  top:180px;
  left:200px;
  perspective: 1000px;
}

.base{ }

.blades{
  width: 350px;
  height: 350px;
  left: 10%;
  top: 10%;
  z-index:2;
  border-radius: 50%;
  position: absolute;
  margin-top: -30px;
  margin-left: 50px;

  animation: spin 6s linear infinite;
}

.blade1 {
  		background: white;
  position:absolute;
	  width:41px;
	  height:139px;
  top:-10px;
  left:150.5px;
  transform:rotate(0deg);
  display:inline-block;
  background:
    linear-gradient(135deg, transparent 20px, white 0),
    linear-gradient(225deg, transparent 20px, white 0),
    linear-gradient(315deg, transparent 20px, white 0),
    linear-gradient(45deg, transparent  20px,  white 0);
  background-position: top left, top right, bottom right, bottom left;
  background-size: 50% 50%;
  background-repeat: no-repeat;
}

.blade2 {
  		background:white;
  position:absolute;
	  width:41px;
	  height:139px;
  top:105.5px;
  left:41px;
  transform:rotate(-90deg);
  display:inline-block;
  background:
    linear-gradient(135deg, transparent 20px, white 0),
    linear-gradient(225deg, transparent 20px, white 0),
    linear-gradient(315deg, transparent 20px, white 0),
    linear-gradient(45deg, transparent  20px,  white 0);
  background-position: top left, top right, bottom right, bottom left;
  background-size: 50% 50%;
  background-repeat: no-repeat;
}

.blade3 {
  		background:white;
  position:absolute;
	  width:41px;
	  height:139px;
  top:105.5px;
  right:41px;
  transform:rotate(-270deg);
  display:inline-block;
  background:
    linear-gradient(135deg, transparent 20px, white 0),
    linear-gradient(225deg, transparent 20px, white 0),
    linear-gradient(315deg, transparent 20px, white 0),
    linear-gradient(45deg, transparent  20px,  white 0);
  background-position: top left, top right, bottom right, bottom left;
  background-size: 50% 50%;
  background-repeat: no-repeat;
}

.blade4 {
  		background:white;
  position:absolute;
	  width:41px;
	  height:139px;
  bottom:-10px;
  left:150.5px;
  transform:rotate(180deg);
  display:inline-block;
  background:
    linear-gradient(135deg, transparent 20px, white 0),
    linear-gradient(225deg, transparent 20px, white 0),
    linear-gradient(315deg, transparent 20px, white 0),
    linear-gradient(45deg, transparent  20px,  white 0);
  background-position: top left, top right, bottom right, bottom left;
  background-size: 50% 50%;
  background-repeat: no-repeat;
}

.vane1{
  width:1px;
  height:350px;
  left:175px;
  background:white;
  position:absolute;
  transform:rotate(90deg);
}

.vane2{
  width:1px;
  height:350px;
  left:171.5px;
  background:white;
  position:absolute;
  transform:rotate(180deg);
}

.base .bottom_base{
  position:absolute;
  width:90px;
  height:100px;
  left:162px;
  border-right: 16px solid transparent;
  border-left: 16px solid transparent;
  border-bottom: 380px solid white;
  opacity:.8;
  z-index:-1;
  top:42.5px;
}

ul{
  position:absolute;
  top:180px;
  left:-30px;
}
li{
  width:10px;
  height:10px;
  background:white;
  padding:2px;
  display:block;
  margin: 30px;
  box-shadow: inset 0px -2px 0px lightgray; 
}

li:nth-child(2){
  position:absolute;
  top:-45px;
  left:20px;
}

li:nth-child(1){
  position:absolute;
  top:35px;
  left:50px;
}

li:nth-child(3){
  position:absolute;
  top:75px;
  left:50px;
}

@keyframes spin {
0% {
   	transform:rotate(0deg);
 }
 100% {
   	transform:rotate(-360deg);
 }
}

</style>

    <script async="" type="text/javascript" src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/ca-pub-4188263447419139.js"></script><script src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/prefixfree.min.js"></script>

</head>

<body>
<div style="text-align:center;clear:both;margin-bottom:50px">
<script src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/gg_bd_ad_720x90.js" type="text/javascript"></script><div style="width:728px;margin:10px auto;">
<script async="" src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/adsbygoogle.js"></script>
<!-- html5tricks-demo -->
<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-4188263447419139" data-ad-slot="1639624407" data-adsbygoogle-status="done"><ins id="aswift_0_expand" style="display:inline-table;border:none;height:90px;margin:0;padding:0;position:relative;visibility:visible;width:728px;background-color:transparent"><ins id="aswift_0_anchor" style="display:block;border:none;height:90px;margin:0;padding:0;position:relative;visibility:visible;width:728px;background-color:transparent"><iframe width="728" height="90" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allowfullscreen="true" onload="var i=this.id,s=window.google_iframe_oncopy,H=s&amp;&amp;s.handlers,h=H&amp;&amp;H[i],w=this.contentWindow,d;try{d=w.document}catch(e){}if(h&amp;&amp;d&amp;&amp;(!d.body||!d.body.firstChild)){if(h.call){setTimeout(h,0)}else if(h.match){try{h=s.upd(h,i)}catch(e){}w.location.replace(h)}}" id="aswift_0" name="aswift_0" style="left:0;position:absolute;top:0;"></iframe></ins></ins></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<div style="display:none"><script language="javascript" type="text/javascript" src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/16741667.js"></script><a href="http://www.51.la/?16741667" target="_blank"><img alt="51.la 专业、免费、强健的访问统计" src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/icon_0.gif" style="border:none"></a>
</div><div style="display:none"><script language="javascript" type="text/javascript" src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/17278758.js"></script><a href="http://www.51.la/?17278758" target="_blank"><img alt="51.la 专业、免费、强健的访问统计" src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/icon_0.gif" style="border:none"></a>
</div><style type="text/css">.source-url{font-size:15px;text-align:center}</style>

<script src="./HTML5_CSS3实现大风车旋转动画DEMO演示_files/follow.js" type="text/javascript"></script>
</div>
  <!-- the windmill -->


<div class="wmd1">
  <div class="blades">
    <div class="blade2"></div>
    <div class="blade1"></div>  
    <div class="vane1"></div>
    <div class="blade3"></div>
    <div class="blade4"></div>
    <div class="vane2"></div>
  </div>
  <div class="base">
    <div class="bottom_base">
      <ul>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
      </ul>
    </div>
</div>

  <div class="wmd1">
  <div class="blades">
    <div class="blade2"></div>
    <div class="blade1"></div>  
    <div class="vane1"></div>
    <div class="blade3"></div>
    <div class="blade4"></div>
    <div class="vane2"></div>
  </div>
  <div class="base">
    <div class="bottom_base">
      <ul>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
      </ul>
    </div>
</div>

<div class="wmd1">
  <div class="blades">
    <div class="blade2"></div>
    <div class="blade1"></div>  
    <div class="vane1"></div>
    <div class="blade3"></div>
    <div class="blade4"></div>
    <div class="vane2"></div>
  </div>
  <div class="base">
    <div class="bottom_base">
      <ul>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
      </ul>
    </div>
</div>



</div></div></div></body></html>