<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title><?php echo $name;?></title>
<style>
body,a,img,div,p,span,ol,ul,li,h1,h2,h3,h4,h5,h6 {margin:0px;padding:0px;border:0px;outline:0px;list-style:none;font:inherit;}
body {
  font-size: 20px;
  background: #000000;
  color: #666666;
  -webkit-tap-highlight-color: transparent;
  -webkit-user-select: none;
}
video {
  display: block;
  width: 100%;
  height: 240px;
  z-index: 1;
}
ul {
  border-top: 1px solid #37b1e5;
  overflow: hidden;
  background-color: #404040;
}
ul li {
  border-bottom: 1px solid #666666;
  line-height: 60px;
  text-align: center;
  float: left;
  width: 50%;
  background: url(<?php echo $picurl;?>/bg_corner_gray.png) no-repeat 100% 100%;
}
ul li.playing {
  color: #37b1e5;
  border-color: #37b1e5;
  background-image: url(<?php echo $picurl;?>/bg_corner_blue.png);
  box-shadow: 0px 0px 10px 5px #37b1e5;
}
#liveradio {
  display: block;
  width: 100%;
  height: 0;
}
#video_toggle {
  font-size: 14px;
  width: 70%;
  margin: 10px auto;
  border-radius: 15px;
  background-color: #404040;
}
#video_toggle span {
  border-radius: 15px;
  display: inline-block;
  width: 50%;
  line-height: 25px;
  height: 25px;
  text-align: center;
}
#video_toggle span.playtoggle {
  background-color: #37b1e5;
  color: #2b2b2b;
}
#copyright {
  text-align: center;
  font-size: 16px;
  line-height: 30px;
  color: #ffffff;
  background: #000000;
  letter-spacing: 1px;
  padding: 20px 0 10px;
}
</style>
</head>

<body>
<img id="liveradio" src="<?php echo $poster_fm;?>">
<video id="liveplayer" width="100%" controls="controls" webkit-playsinline="webkit-playsinline" preload="none" poster="<?php echo $poster_tv;?>" src="<?php echo $stream_tv;?>"></video>
<div id="video_toggle"><span class="playtoggle">电视</span><span>广播</span></div>
<ul>
<?php
$message = '';
foreach($tv as $item)
{
	$message .= '<li stream="'.$item['stream'].'">'.$item['name'].'</li>';
}
echo $message;
?>
</ul>
<ul style="display:none;">
<?php
$message = '';
if (count($fm) >0)
{
	foreach($fm as $item)
	{
		$message .= '<li stream="'.$item['stream'].'" img="'.$item['img'].'">'.$item['name'].'</li>';
	}
}
echo $message;
?>
</ul>
<div id="copyright"><?php echo $name;?><br><span style="font-size:14px;color:#333333;letter-spacing: 0;">“长江云--湖北新媒体云平台”共建单位</span></div>

<script src="http://hudong.hbtv.com.cn/assets/jquery.js"></script>
<script>
//var iapple=/(iphone|ipod|ipad)/i.test(navigator.userAgent);
var liveplayer = $("#liveplayer").get(0);
var stream = "<?php echo $stream_tv;?>";
var img = "<?php echo $poster_tv;?>";

$(document).ready(function(){
  mobiwidth = $(window).width();
  mobiheight = mobiwidth*0.75;
  $("#liveplayer").height(mobiheight);
  $("ul li").first().addClass("playing");

  $("#video_toggle").on("click","span",function(){
    $("#video_toggle span").removeClass("playtoggle");
    if ($(this).addClass("playtoggle").index() == 1) {
      $("ul").hide().last().show();
    } else {
      $("ul").hide().first().show();
    }
  });

  $("ul").on("click", "li", function(){
    $this = $(this);
    $("ul li").removeClass("playing");
    $this.addClass("playing");
    stream = $this.attr("stream");
    img = $this.attr("img");
    if (typeof img == "undefined") {
      $("#liveradio").height(0);
      $("#liveplayer").height(mobiheight);
    }
    else {
      $("#liveradio").attr({"src":img}).height(mobiheight);
      $("#liveplayer").height(0);
    }
    liveplayer.src = stream;
    liveplayer.load();
    liveplayer.play();
  });

});
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
var shareData = {
	title: "<?php echo $name;?>",
	desc: "<?php echo $desc;?>",
	link: window.location.href,
	imgUrl: "<?php echo $poster_tv;?>"
};
document.write("<s"+"cript type='text/javascript' src='http://mgf.hbtv.com.cn/cjsc/wechatjsapi_cjy.php?url="+window.location.href.split('#')[0].replace('&','@')+"'></scr"+"ipt>");
</script>
</body>
</html>