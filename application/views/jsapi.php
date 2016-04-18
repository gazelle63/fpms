wx.config({
  debug: false,
  appId: '<?php echo $appId;?>',
  timestamp: <?php echo $timestamp;?>,
  nonceStr: '<?php echo $nonceStr;?>',
  signature: '<?php echo $signature;?>',
  jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
  ]
});

wx.ready(function () {
  wx.onMenuShareTimeline(shareData);
  wx.onMenuShareAppMessage(shareData);
  wx.onMenuShareQQ(shareData);
  wx.onMenuShareWeibo(shareData);
  wx.onMenuShareQZone(shareData);
});

wx.error(function (res) {
  //alert(res.errMsg);
});
