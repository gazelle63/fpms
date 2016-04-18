<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<meta baidu-gxt-verify-token="7728ab5022dc87a6a631f8690df6df51">
<title>粉丝积分管理系统</title>
<style type="text/css">
html, body, div, span, p, a, ul, ol, li, h1, h2, h3, h4, h5, h6, object, form, iframe { font-family: inherit; font-weight: normal; font-style: normal; font-size: 100%; margin: 0; padding: 0; border: 0; outline: 0; list-style: none outside none; }
::-webkit-scrollbar {
	width: 10px;
	height: 10px;
}
::-webkit-scrollbar-thumb {
	background-color: rgba(0,0,0,0.3);
}
::-webkit-scrollbar-thumb:hover {
	background-color: rgba(0,0,0,0.7);
}
::-webkit-input-placeholder {
	color: rgba(0,0,0,0.3);
}
body {
	font-family: "microsoft yahei";
	background-color: #ffffff;
	color: #333333;
	font-size: 16px;
}
input[type='text'], input[type='password'], select {
	display: block;
	font-size: 18px;
	width: 8em;
	height: 2em;
	border:1px solid rgba(0,0,0,0.2);
	margin: 20px auto;
	background-color: #ffffff;
	color: #000000;
	text-align: center;
}
input[type='submit'], button {
	display: block;
	font-size: 18px;
	font-weight: bold;
	width: 8em;
	height: 2em;
	margin: 20px auto 10px;
}
input:focus, select:focus {
	box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.2);
	outline: 0;
}
input.btn_input {
	display: inline-block;
	text-align: right;
	width: 50px;
	height: 30px;
	padding: 0;
	border: 0;
	margin: 0 5px;
	border-radius: 3px;
	cursor: pointer;
	overflow: hidden;
	transition: all 0.3s ease-in 0.1s;
}
input.btn_switch {
	display: inline-block;
	text-indent: -99em;
	background: url(http://hudong.hbtv.com.cn/uploads/btn_switch.png) no-repeat 0 0;
	width: 75px;
	height: 30px;
	padding: 0;
	border: 0;
	margin: 0 5px;
	border-radius: 15px;
	cursor: pointer;
	overflow: hidden;
	transition: all 0.3s ease-in 0.1s;
}
input.btn_switch[value="0"] {
	background-position: -75px 0;
}
input.btn_switch[value="2"] {
	background-position: -36px 0;
}
tr:first-of-type {
	background-color: #dddddd;
}
tr:hover {
	background-color:#eeeeee;
}
td {
	word-break:break-all;
}
#centerbox {
	box-sizing: border-box;
	position: absolute;
	left: 10%;
	top: 20%;
	font-size: 20px;
	background-color: rgba(255, 255, 255, 0.9);
	color: #333333;
	width: 80%;
	line-height: 25px;
	padding: 10px 20px;
	box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.3);
	border-radius: 5px;
}
#nav {
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	width: 100px;
	background-color: rgba(255, 255, 255, 0.7);
	padding: 10px;
	box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.3);
}
#nav a {
	display: block;
	font-size: 14px;
	text-align: center;
	text-decoration: none;
	background-color: #ffffff;
	color: #000000;
	line-height: 25px;
	border:1px solid rgba(0,0,0,0.3);
	border-radius: 5px;
	margin: 10px auto;
}
#nav a:hover {
	box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.2);
}
#main {
	display: block;
	position: absolute;
	top: 10px;
	bottom: 10px;
	left: 130px;
	right: 10px;
	background-color: #ffffff;
	box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.2);
}
.ul_float_a li {
	float: left;
	font-size: 14px;
	text-align: center;
	text-decoration: none;
	background-color: #ffffff;
	color: #666666;
	line-height: 25px;
	border:1px solid rgba(0,0,0,0.3);
	border-radius: 3px;
	padding: 10px;
	margin: 10px;
}
</style>
</head>
<body>
