<?php
if (!isset($_POST['re_token'])){exit;}
require_once "../../paylib/WxPay.JsApiPay.php";
$tools = new JsApiPay();
$token = $tools->GetPass($_POST['re_openid']);
if (md5($token) != md5($_POST['re_token'])){exit;}
$input = new WxPayRedPack();
$input->SetMch_billno($_POST['mch_billno']);
$input->SetTotal_amount($_POST['total_amount']);
$input->SetTotal_num($_POST['total_num']);
$input->SetOpenid($_POST['re_openid']);
$input->SetSend_name($_POST['send_name']);
$input->SetWishing($_POST['wishing']);
$input->SetAct_name($_POST['act_name']);
$input->SetRemark($_POST['remark']);
$order = WxPayApi::redPack($input);
?>
