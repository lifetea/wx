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
    }else if(!!userId){
        $(".tabhome").addClass("active");
        $("#myWrap").removeClass("hide");
    }


    //红包
    $("#separate").click(function(){
        resetWrap();
        $("#separate").addClass("active");
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
        var userId   = $("#userId").val();
        var url = "./getWithdraw?userId="+userId;
        $.get(url, function(result){
            if (!!result) {
                resetWrap();
                $("#withdraw").addClass("active");
                $("#withdrawWrap").removeClass("hide");
                $("#giftCount").text(result.giftcount);
                $("#withdrawLog").empty();
                var arr = result.moneyLog;
                $(".credit").text(result.money);
                for(var i=0;i<arr.length;i++){
                    var str = arr[i].money>0 ? "获得了":"提现了";
                    var tr  = $("<tr><td>"+str+"</td><td>"+arr[i].money+
                        "元</td><td>"+arr[i].time+"</td></tr>");
                    $("#withdrawLog").append(tr);
                }
            }else{
                toast("好像出了点问题");
            }
        });
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
    //need
    $("#needHelp").click(function(){
        toast("分享出去让朋友帮忙");
    });
    //助力
    $("#zu").click(function(){
        var subscribe = $("#subscribe").val();
        if(subscribe == "1") {
            doZu();
        }else{
            $("#focusNotice").modal('show');
        }


    });
    $(".close-overlay").click(function(){
        doZu();
    });

    function doZu(){
        var userId   = $("#userId").val();
        var fId   = $("#fId").val();
        var url = "./zu?userId="+userId+"&fId="+fId;
        $.get(url, function(result){
            if (result.msg == "1") {
                toast("拆红包成功");
                var len = $("#fList").length;
                var str = len == 3? "first":"second";
                var ele = $("<li class='"+str+"'><img src='"+result.headimgurl+"'/></li>");
                $("#fList").append(ele);
            }else{
                toast("已经拆过红包过了");
            }
        });
    }
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
                toast("没有红包 赶紧让人来拆红包吧");
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
                    toast("报名成功,获得两个红包");
                    $("#zuCount").text(parseInt($("#zuCount").text())+2);
                };
            });
            $("#applyContainer").modal('hide');


        }
    });


    $("#rule").click(function(){
        $("#ruleContainer").modal('show');
    });

});



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

