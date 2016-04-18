<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $title;?></title>
<link rel="stylesheet" type="text/css" href="http://59.175.153.173/tpl/static/wall/css/base.css">
<link rel="stylesheet" type="text/css" href="http://59.175.153.173/tpl/static/wall/css/screen_shake.css">
<script type="text/javascript" src="http://hudong.hbtv.com.cn/assets/jquery.js" charset="utf-8"></script>
</head>

<body class="FUN WALL" style="background-image:url(http://59.175.153.173/tpl/static/wall/images/default_bg.jpg);" >
<div class="Panel Track" style="display: none; opacity: 1;overflow: hidden;">
    <div class="tracklist"></div>
    <div class="runlist" style="width:100%;position: absolute;overflow: visible;left:0;top:0;height:100%;"></div>
    <div class="cutdown-end"></div>
    <div class="track-tool"></div>
    <div class="track-result"></div>
</div>


<div class="round-welcome" style="display: block;">
    <div class="label top">微信扫一扫，点击菜单参与游戏！</div>
    <img src="<?php echo $qrcode;?>">
    <div class="label bottom"><span class="shake-icon shake"></span>游戏开始后不停摇动手机，已参与人数<span id="connectcount" style="color:#ff0000;">0</span>人</div>
    <div class="button-start">开始游戏</div>
    <!--<div class="button restart">重新报名</div>-->
</div>


<div class="result-layer" style="display: none;">
    <div class="result-label" style="display: none;">GAME OVER</div>
    <div class="result-cup" style="display: none;"></div>
</div>


<div class="cutdown-start"><?php echo $starttime;?></div>


<div class="Panel Top" style="top: 0px;">
    <img class="activity_logo" src="http://59.175.153.173/tpl/static/wall/images/default_logo.png">
    <div class="top_title" style="font-size: 30px">
        <div>欢迎您参加-<?php echo $title;?></div>
    </div>
    <img class="mp_account_codeimage" src="<?php echo $qrcode;?>">
</div>


<div class="Panel Bottom" style="bottom: 0px;">
    <div class="helperpanel pulse">
        搜索关注<span class="mp_account">恩施电视台</span>
    </div>
    <div class="navbar">
        <a class="navbaritem fullscreen" id="fullscreen" href="javascript:void(0);">
            <div class="icon"></div>
            <div class="label">全屏</div>
        </a>
        <a class="navbaritem rocker hover" href="javascript:window.location.reload();">
            <div class="icon"></div>
            <div class="label">摇一摇</div>
        </a>
    </div>
</div>


<script type="text/javascript">
var starttime = <?php echo $starttime;?>, diff = <?php echo $shownum;?>, showtime=<?php echo $showtime;?>, shakemax=<?php echo $shakemax;?>;

var $PlayeSeed, lineHeight;
var size;
var yuni;
var rankTopTen = [];
var tmr_cutdown_start;

	/*查询参与人数*/
	function getConnectNum(){
		$.post("<?php echo site_url('fpms_admin/shakes/num'); ?>", {"lasttime": "<?php echo time(); ?>"}, function(data){
			$("#connectcount").html(data.num);
		}, "json");
	}


	/*点击游戏开始*/
	$(".button-start").click(function(){
		var html = '';
		for (var i = 1; i <= diff; i++) {
			html+='<div class="trackline leftfadein"><div class="track-start" >'+i+'</div><div class="track-end" ></div></div>';
		};
		$(".Panel.Track .tracklist").append(html);

		$('.round-welcome').css('display','none');
		$('.Track').css('display','block');
		clearInterval(yuni);
			cutdown_start();
			resize();
	});


/*倒计时并开启游戏*/
function cutdown_start() {
	var a = $(".cutdown-start"),
	b = (a.html())* 1 + 1;
	a.html("").show().css({
		"margin-left":-a.width() / 2 + "px",
		"margin-top": -a.height() / 2 + "px",
		"font-size":   a.height() * 0.7 + "px",
		"line-height": a.height() + "px"
	}).addClass("cutdownan-imation");

	tmr_cutdown_start = window.setInterval(function() {
		b--;
		if (b == 0){
			$.post("<?php echo site_url('fpms_admin/shakes/start'); ?>", {"lasttime": "<?php echo time(); ?>"}, function(data){
				if (data.err == 0) {
					a.html("GO!");
					gameRun();
					hideSlogan();
				} else {
					Alert("error");
				}
			}, "json");
		} else {
			if (b < 0) {
				window.clearInterval(tmr_cutdown_start);
				a.hide();
				showSlogan();
			} else {
				a.html(b);
			}
		}
	},
	1000);
}


function resize() {
	var b = $(".Panel.Track"),
	a = b.find(".tracklist").children();
	size = lineHeight = b.height() / diff;
	var c = b.find(".runlist");
	roundLength = $(".Panel.Track .tracklist").width() - size;
	a.each(function() {
		$(this).css({
			height: size,
			"line-height": size + "px",
			"font-size": size * 3 / 5 + "px"
		}).find(".track-start,.track-end,player,.head").css({
			width: size + "px",
			height: size + "px",
			lineHeight:size + "px"
		});

		$(this).find(".nickname").css({
			height: size + "px",
			lineHeight:size + "px"
		});


		$PlayeSeed = $('<div class="player"><div class="head"></div><div class="nickname"></div></div>').css({
			width:  size - diff * 2,
			height: size - diff * 2
		});

	});
	c.css({width:b.find(".tracklist").width()-size*2,margin:'0 '+size+'px'});
}



/*获取用户数据*/
function gameRun(){
			tmr_GameDataLoad = window.setTimeout(gameRun,2000);
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('fpms_admin/shakes/json'); ?>",
				dataType: "json",
				cache: false,
				success: function(data) {
					if (data.status == 2) {
						clearTimeout(tmr_GameDataLoad);
						window.setTimeout(function() {
							showGameResult(data.res);
							hideSlogan();
						}, 660);
					} else {
						rankTopTen = data.res.slice(0, 3);
						var temp 	= '';
						for (var i = data.res.length - 1; i >= 0; i--) {
							$tem_fans = data.res[i];
							temp += '<div class="player" uid="'+$tem_fans.fansid+'" style="top: '+size*i+'px;left:'+$tem_fans.shakes+'%;"><div class="head shake" style="height:'+size+'px;width:'+size+'px;background-image: url('+$tem_fans.headimgurl+');"></div><div class="nickname" style="line-height:'+size+'px;height:'+size+'px;">'+$tem_fans.nickname+'</div></div>';
						};
						$(".Panel.Track .runlist").html(temp);
					}
				},
				error: function(data) {
					alert('error');
				}
			});

}


function showGameResult(res) {
	var b = $(".result-layer").show();
	var d = $(".result-label", b).show().addClass("pulse");
	var a = $(".result-cup", b).hide();
	var c = starttime;

	window.setTimeout(function() {
		d.fadeOut(function() {
			a.show(function() {
				if (c >= 1 && res[0]) {
					window.setTimeout(function() {
						var e = $PlayeSeed.clone().addClass("result").css({
							left: "50%",
							"margin-left": "-65px",
							width: "160px",
							height: "160px",
							bottom: "150px"
						});
						e.find(".head").css({
							"background-image": "url(" + res[0]["headimgurl"] + ")"
						}).addClass("shake");
						e.find(".nickname").html(res[0]["nickname"]);
						e.appendTo(a).addClass("bounce");
					},
					800);
				}
				if (c >= 2 && res[1]) {
					window.setTimeout(function() {
						var e = $PlayeSeed.clone().addClass("result").css({
							left: "40px",
							width: "100px",
							height: "100px",
							bottom: "120px"
						});
						e.find(".head").css({
							"background-image": "url(" + res[1]["headimgurl"] + ")"
						}).addClass("shake");
						e.find(".nickname").html(res[1]["nickname"]);
						e.appendTo(a).addClass("bounce");
					},
					1800);
				}
				if (c >= 3 && res[2]) {
					window.setTimeout(function() {
						var e = $PlayeSeed.clone().addClass("result").css({
							right: "30px",
							width: "70px",
							height: "70px",
							bottom: "100px"
						});
						e.find(".head").css({
							"background-image": "url(" + res[2]["headimgurl"] + ")"
						}).addClass("shake");
						e.find(".nickname").html(res[2]["nickname"]);
						e.appendTo(a).addClass("bounce");
					},
					2800);
				}
			})
		}).removeClass("pulse");
	},
	1000)
}
/*开始游戏后修改窗口*/
function showSlogan() {
	$(".Panel.Top").css({
		top: "-" + $(".Panel.Top").height() + "px"
	});
	$(".Panel.Bottom").css({
		bottom: "-" + $(".Panel.Bottom").height() + "px"
	});
}

/*游戏结束还原窗口*/
function hideSlogan() {
	$(".Panel.Top").css({
		top: 0
	});
	$(".Panel.Bottom").css({
		bottom: 0
	});
}



	yuni=setInterval(getConnectNum,1500);

	$(window).resize(resize);

	$('.reset').click(function(){
		window.location.reload();
	});


$('#fullscreen').click(function(){

    if($('#fullscreen').hasClass('in')){
        exitFullscreen();
        $('#fullscreen').removeClass("in");
    }else{
        fullscreen();
        $('#fullscreen').addClass("in");
    }

});

function fullscreen(){
    elem=document.body;
    if(elem.webkitRequestFullScreen){
        elem.webkitRequestFullScreen();
    }else if(elem.mozRequestFullScreen){
        elem.mozRequestFullScreen();
    }else if(elem.requestFullScreen){
        elem.requestFullscreen();
    }else{
        //浏览器不支持全屏API或已被禁用
    }
}

function exitFullscreen(){
    var elem=document;
    if(elem.webkitCancelFullScreen){
        elem.webkitCancelFullScreen();
    }else if(elem.mozCancelFullScreen){
        elem.mozCancelFullScreen();
    }else if(elem.cancelFullScreen){
        elem.cancelFullScreen();
    }else if(elem.exitFullscreen){
        elem.exitFullscreen();
    }else{
        //浏览器不支持全屏API或已被禁用
    }
}
</script>
</body>
</html>