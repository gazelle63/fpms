<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>微信墙</title>
<style type="text/css">
body, div, span, p, a, ul, ol, li, h1, h2, h3, h4, h5, h6, form { font-family: inherit; font-weight: normal; font-style: normal; font-size: 100%; margin: 0; padding: 0; border: 0; outline: 0; }
ol, ul, li { list-style: none outside none; }
html,body { height: 100%; overflow: hidden;}
body {
	background: #000000 url(<?php echo $tplurl.'/'.$tpl['p1']?>) no-repeat;
	background-size: 100% 100%;
	color: #ffffff;
	font-family: "microsoft yahei";
	font-size: 16px;
}
#logo {
	background: url(<?php echo $tplurl.'/'.$tpl['p2']?>) no-repeat 50% 50%;
	background-size: <?php echo $tpl['p5']?> <?php echo $tpl['p6']?>;
	width: 100%;
	height: <?php echo $tpl['p7']?>;
}
#mainwall {
	width: <?php echo $tpl['p8']?>;
	height: <?php echo $tpl['p9']?>;
	margin: 10px auto;
	overflow: hidden;
}
#mainwall li {
	background: url(<?php echo $tplurl.'/'.$tpl['p3']?>) no-repeat;
	background-size: 100% 100%;
	width: 100%;
	overflow: hidden;
}
.avatardiv {
	float: left;
	width: 160px;
}
.avatardiv img {
	display: block;
	width: 80px;
	height: 54px;
	margin: 20px 40px 10px;
	border-radius: 50%;
}
.avatardiv p {
	width: 120px;
	line-height: 1.3em;
	text-align: center;
	margin: 10px auto 20px;
}
.messagediv {
	margin: 20px 20px 20px 160px;
}
.messagediv p {
	font-size: 2em;
	line-height: 1.4em;
	font-weight: bold;
	letter-spacing: 1px;
}
.messagediv img {
	display: block;
	max-width: 60%;
	max-height: 200px;
	margin: 20px;
	border-radius: 5px;
}
#prewall {
	display: none;
	width: 160%;
	height: 30%;
	margin: -2% 0 -3% -27%;
}
#light {
	display: block;
	width: 40%;
	position: fixed;
	top: 0;
	right: 0;
	-webkit-animation: lightmove 10s linear normal infinite ;
}
@-webkit-keyframes lightmove
{
	0% {width: 40%;}
	50% {width: 80%;}
	100% {width: 40%;}
}
</style>
</head>
<body>
<div id="logo"></div>
<ol id="mainwall"></ol>
<ol id="prewall"></ol>
<img id="light" src="<?php echo $tplurl.'/'.$tpl['p4']?>">

<script type="text/javascript" src="http://hudong.hbtv.com.cn/assets/jquery.js"></script>
<script type="text/javascript">
var wallurl = "<?php echo $wallurl;?>";
var lastid = 0;
var scrolltop = 0;
var scrollheight = 180;
function prewall() {
		$.post(wallurl, {"lastid": lastid}, function(data){
			if (data.wall_num > 0)
			{
				$.each(data.wall_msg, function(i){
					infosid = data.wall_msg[i]["infosid"];
					headimgurl = data.wall_msg[i]["headimgurl"];
					if (!headimgurl) { headimgurl="<?php echo base_url('uploads/nopic.png');?>";}
					nickname = data.wall_msg[i]["nickname"];
					nickname = " ";
					infostype = data.wall_msg[i]["infostype"];
					if (infostype == "1")
					{
						infomation = data.wall_msg[i]["infomation"];
					}
					else
					{
						infomation = "<img src=\""+data.wall_msg[i]["infomation"]+"\">";
					}
					$("#prewall").append("<li id=\""+infosid+"\"><div class=\"avatardiv\"><img src=\""+headimgurl+"\"><p>"+nickname+"</p></div><div class=\"messagediv\"><p>"+infomation+"</p></div></li>");
				});
				lastid = infosid;
				$("#mainwall").find("li:gt(60)").remove();
			}
		}, "json");
}

function mainwall() {
	$this = $("#prewall li:first");
	if ($this.length == 0)
	{
		$last = $("#mainwall li:last");
		if ($last.length > 0)
		{
			if ($last.position().top == scrolltop)
			{
				$("#mainwall").animate({scrollTop:"0px"}, 1000);
			}
			else
			{
				scrolltop = $last.position().top;
				$("#mainwall").animate({scrollTop:"+="+scrollheight+"px"}, 5000);
			}
		}
	}
	else
	{
		$("#mainwall").animate({scrollTop:"0px"}, 600);
		$this.animate({height:"0px", opacity:0}, 1000, function(){
			$this.prependTo("#mainwall").css("height","auto").animate({opacity:1}, 2000);
		});
	}
}

prewall();
setInterval("prewall()", 60000);
setInterval("mainwall()", 5000);
</script>
</body>
</html>
