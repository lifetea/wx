$(document).ready(function(){
    var V = {};
    V.init = function(){
        var winH  = window.innerHeight;
        var winW  = document.body.clientWidth||window.innerWidth;
        var fW    = parseInt(winW/640*120);
        var fM    = parseInt((winW-(3*fW))/6);
        var sMR   = parseInt(winW/640*64);
        var sML   = parseInt((winW-sMR-2*fW)/2);
        var helpH = parseInt(winW/640*356);
        var hbH = parseInt(winW/640*249);
        var hbT = parseInt(winW/640*108);
        var zuW = parseInt(winW/640*252);
        var zuH = parseInt(winW/640*61);
        var zuT = parseInt(winW/640*222);
        var zuMT = parseInt(winW/640*60);
        var hbtipT = parseInt(winW/640*46);
        var aW = parseInt(winW/640*160);
        //console.log(winW-(3*fW));
        var cssNode = document.createTextNode(".fList li{width:"+fW+
            "px;height:"+fW+"px;} .fList .first{margin:10px "+fM+
            "px} .fList .second{margin-left: "+sML+"px;margin-right:"+
            sMR+"px;} .helpWrap{height:"+helpH+"px;padding-top:"+zuT+"px} .redPacket{height:"+
            hbH+"px;} .zu{width:"+zuW+"px;height:"+
            zuH+"px;line-height:"+zuH+"px} .hbTip{height:"+hbT+"px;padding-top:"+
            hbtipT+"px;} .avatar{width:"+aW+"px;} .fhelpWrap{height:"+helpH+
            "px;} #zu{margin-top:"+zuMT+"px;}");
        var style = document.createElement("style");
        style.type = "text/css";
        style.appendChild(cssNode);
        document.head.appendChild(style);
    }
    V.init();
    $(window).resize(function(){
        V.init();
    });
});


$(function(){
    // (separate)
    function resetWrap(){
        $("#hbWrap").addClass("hide");
        $("#myWrap").addClass("hide");
        $("#withdrawWrap").addClass("hide");
        $("#topWrap").addClass("hide");
        $("#detailWrap").addClass("hide");
        $("#friendWrap").addClass("hide");

        $(".top_menu a").each(function () {
            $(this).removeClass("active");
        })
    }
    var fId      = $("#fId").val();
    var userId   = $("#userId").val();

    if(!!fId && !!userId && fId != userId){
        $("#friendWrap").removeClass("hide");
    }
    //红包
    $("#separate").click(function(){
        resetWrap();
        $(this).addClass("active");
        $("#hbWrap").removeClass("hide");
    });
    //friend
    $("#friend").click(function(){
        resetWrap();
        $("#friendWrap").removeClass("hide");
    });
    //个人
    $(".tabhome").click(function(){
        var playerStatus = $("#playerStatus").val();
        if(playerStatus == 1){
            resetWrap();
            $(this).addClass("active");
            $("#myWrap").removeClass("hide");
        }else{
            toast("请先报名");
        }
    });
    //排行
    $("#paihang").click(function(){
        resetWrap();
        $(this).addClass("active");
        $("#topWrap").removeClass("hide");
    });
    //提现
    $("#withdraw").click(function(){
        resetWrap();
        $(this).addClass("active");
        $("#withdrawWrap").removeClass("hide");
    });
    //报名
    $("#apply").click(function(){
        var status = $("#playerStatus").val();
        if (status == "1") {
            toast("已报名");
        }else{
            $("#applyContainer").modal('show');
        }
    });
    //助力
    $("#zu").click(function(){
        var subscribe = $("#subscribe").val();
        if(subscribe == "1"){
            var userId   = $("#userId").val();
            var fId   = $("#fId").val();
            var url = "./zu?userId="+userId+"&fId="+fId;
            $.get(url, function(result){
                if (result == "1") {
                    toast("解锁成功");
                }else{
                    toast("已经解锁过了");
                }
            });
        }else{
            $("#focusNotice").modal('show');
        }
    });
    $("#goFollow").click(function(){
        window.location.href = "http://mp.weixin.qq.com/s?__biz=MzI0MDA5OTY1NA==&mid=402621433&idx=1&sn=20ea6b6e84c2a997cbd60287e4cf01d1";
    });
    //打开红包
    $("#open").click(function () {
        var userId   = $("#userId").val();
        var fId   = $("#fId").val();
        var url = "./open?userId="+userId;
        $.get(url, function(result){
            if (result != "0") {
                toast("获得"+result+'元');
                $("#zuCount").text(parseInt($("#zuCount").text())-1);

            }else{
                toast("没有红包 赶紧解锁吧");
            }
        });
    });

    //兑换
    $("#exchange").click(function () {
        console.log("ha");
        var userId   = $("#userId").val();
        var fId   = $("#fId").val();
        var url = "./withdraw?userId="+userId;
        $.get(url, function(result){
            if (result == "1") {
                toast("提现成功,隔天打款");
            }else if(result == "-1"){
                toast("未满五元");
            }else{
                toast("兑换失败");
            }
        });
    });

    //报名提交信息
    $("#userSure").click(function(){
        var userName = $("#username").val();
        var wchat     = $("#wchat").val();
        var phone    = $("#phone").val();
        var userId   = $("#userId").val();
        //var hubId    = $("#hubId").val();
        if (!userName || !phone) {
            $("#userError").text("请完善资料");
        }else{

            var url = "./voters?userId="+userId+"&userName="+userName+"&wchat="+wchat+"&phone="+phone;
            $.get(url, function(result){
                $("#playerStatus").val("1");
                if (result == "1") {
                    toast("报名成功");
                };
            });
            $("#applyContainer").modal('hide');


        }
    });

    //剩余时间
    setInterval(function(){
        var end     = new Date(2016,03,15);
        var start   = new Date();
        var time    = end.getTime() - start.getTime();
        var day     = parseInt(time/1000/60/60/24);
        var hour    = parseInt(time/1000/60/60 - day*24);
        var mini  = parseInt(time/1000/60 - day*24*60- hour*60);
        var sec  = parseInt((time/1000 -day*24*60*60 -hour*60*60 -mini*60));
        var ms      = parseInt((time -day*24*60*60*1000 -hour*60*60*1000 -mini*60*1000 - sec*1000));
        $("#day").text(day);
        $("#hour").text(hour);
        $("#mini").text(mini);
        $("#hm").text(ms);
        $("#sec").text(sec);
    },93);

    $("#rule").click(function(){
        $("#ruleContainer").modal('show');
    });

});

function vote(ele){
    var status    = $("#subscribe").val();
    if (status != 0) {
        var voterId   = $(ele).attr("data-voterid");
        var hubId     = $(ele).attr("data-hubid");
        var userId    = $("#userId").val();
        var url = "http://wx.vlegend.cn/vote?userid="+userId+"&voterid="+voterId+"&hubid="+hubId;
        $.get(url, function(result){
            // console.log(result);
            if (result == 1) {
                toast("投票成功");
                var span = $(ele).next();
                $(span).text(parseInt($(span).text())+1);
            };
            if (result == 2) {
                toast("今日已投票");
            };
        });
    }else{
        $("#voteNotice").modal('show');
    }



}
//投票页
function voteView(ele){
    //console.log(ele);
    $("#plug").addClass("hide");
    $("#soso").addClass("hide");
    $("#searchList").addClass("hide");
    var voterId    = $(ele).attr("data-voterid");
    var hubId    = $(ele).attr("data-hubid");
    var username   = $(ele).attr("data-username");
    var userId     = $("#userId").val();
    //voteViewCount

    var button     = $("#voteContainer").find("button.btn-1")[0];
    $(button).attr("data-voterid",voterId);
    $(button).attr("data-hubid",hubId);
    var url = "http://wx.vlegend.cn/getVoter?userId="+voterId+"&hubid="+hubId;
    $.get(url, function(result){

        var obj = jQuery.parseJSON(result);
        var img     = $("#voteContainer").find("img.small")[0];
        $(img).attr("src","http://wx.vlegend.cn/Public/tp/"+obj["pic"]);
        $("#voteUserInfo").text(voterId+" "+username);
        $("#voteViewCount").text(obj["count"]);
        $("#voteContainer").modal('show');
    });

}


//提示
function toast(text){
    var $toast = $('#toast');
    if ($toast.css('display') != 'none') {
        return;
    }
    $("#toastText").text(text);
    $toast.show();
    //发送
    setTimeout(function () {
        $toast.hide();
    }, 2000);
}

