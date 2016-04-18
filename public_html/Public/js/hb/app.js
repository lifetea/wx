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
        //console.log(winW-(3*fW));
        var cssNode = document.createTextNode(".fList li{width:"+fW+
            "px;height:"+fW+"px;} .fList .first{margin:10px "+fM+
            "px} .fList .second{margin-left: "+sML+"px;margin-right:"+
            sMR+"px;} .helpWrap{height:"+helpH+"} .redPacket{height:"+hbH+"}");
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
            $(".top_menu a").each(function () {
                $(this).removeClass("active");
            })
        }

        $("#separate").click(function(){
            resetWrap();
            $(this).addClass("active");
            $("#hbWrap").removeClass("hide");
        });

        $(".tabhome").click(function(){
            resetWrap();
            $(this).addClass("active");
            $("#myWrap").removeClass("hide");
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
        });
        //打开红包
        $("#open").click(function () {
            var userId   = $("#userId").val();
            var fId   = $("#fId").val();
            var url = "./open?userId="+userId;
            $.get(url, function(result){
                if (result != "0") {
                    toast("获得"+result+'元');
                }else{
                    toast("没有红包 赶紧解锁吧");
                }
            });
        });
        //查看详情
        $("#detail").click(function () {

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
        /**
         setTimeout(function(){
        $("#thumbs li").each(function(index,element){
          var i           = index;
          var ele         = element;
          var hubId       = $(ele).attr("data");

          (function(){
            var url = "http://wx.vlegend.cn/getHub?id="+hubId;
            $.get(url, function(result){
                var obj = jQuery.parseJSON(result);
                var load = $(ele).find(".load-wrap")[0];
                $(load).addClass("hide");
                var row         = $("<div class='row'></div>");
                for (var i = 0; i < obj.length; i++) {
                    var div     = $("<div class='col-md-6'><div><span class='namec'>"+obj[i]["voterid"]+" "+obj[i]["username"]+"</span><div class='btn-wrap'><div class='btg'></div><button onclick='vote(this)' data-hubid='"+obj[i]["hubid"]+"' data-voterid='"+obj[i]["voterid"]+"' class='btn-1' type='button'>投票</button><span class='count'>"+obj[i]["count"]+"</span><span class='p'>票</span></div></div></div>");
                    // var button  =$(div).find("button")[0];
                    // $(button).click(function(){

                    // });
                    row.append(div);
                };

                $(ele).append(row);
            });
          })();
        });
      },400);
         **/


            //报名提交信息
        $("#userSure").click(function(){
            var userName = $("#username").val();
            var wchat     = $("#wchat").val();
            var phone    = $("#phone").val();
            var userId   = $("#userId").val();
            //var hubId    = $("#hubId").val();
            if (!userName || !wchat || !phone) {
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

