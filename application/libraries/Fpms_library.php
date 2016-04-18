<?php
class Fpms_library
{
	public function __construct()
	{
		log_message('debug', "Fpms_library Class Initialized");
	}


	/**
	 * 获取令牌信息
	 * @param string $publicid 公众号原始账号
	 * @param string $openid 关注者原始账号
	 * @return string 加密的令牌
	 */
	public function update_access_token($cjyun, $appid)
	{
/*
		if (intval($cjyun) > 0)
		{
			$file_token = 'wechat_access_token_'.$appid;
			$data = json_decode(file_get_contents($file_token));
			if ($data->expire < time()) {
				//媒体云平台获得令牌
				$url = 'http://data.cjyun.org/wechat/notify/token?appid='.$appid;
				$res = json_decode($this->get_curl($url, FALSE, 3));
				$access_token = $res->access_token;
				if ($access_token) {
					$data->expire = time() + 300;
					$data->token = $access_token;
					file_put_contents($file_token, json_encode($data));
				}
			}
		}
*/
	}


	/**
	 * 获取远程页面信息
	 * @param string $url 远程页面地址
	 * @param array $postdata 传送的参数
	 * @return string 返回信息
	 */
	public function get_curl($url, $postdata = FALSE, $timeout = 1)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		if ($postdata != FALSE)
		{
			curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		}
		$msg = curl_exec($curl);
		curl_close($curl);
		return $msg;
	}


	public function get_curl2($url, $postdata = FALSE)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		if ($postdata != FALSE)
		{
			curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		}
		$msg = curl_exec($curl);
		curl_close($curl);
		return $msg;
	}


	/**
	 * 获取令牌信息
	 * @param string $publicid 公众号原始账号
	 * @param string $openid 关注者原始账号
	 * @return string 加密的令牌
	 */
	public function get_token($publicid, $openid)
	{
		$date_today = date('Ymd', time());
		$token = md5(sha1($publicid).sha1($date_today).sha1($openid));
		return $token;
	}


	/**
	 * 获取加密信息
	 * @param string $publicid 公众号原始账号
	 * @param string $openid 关注者原始账号
	 * @return string 加密的令牌
	 */
	public function get_pass($src_pass)
	{
		$pass = md5(sha1($src_pass).sha1('fpms').sha1($src_pass));
		return $pass;
	}


	/**
	 * 生成随机字串
	 * @param number $length 长度，默认为16，最长为32字节
	 * @return string
	 */
	public function get_nonce($length=16)
	{
		// 密码字符集，可任意添加你需要的字符
		$chars = "abcdefghjkmnpqrstuvwxyz23456789";
		$str = "";
		for($i = 0; $i < $length; $i++)
		{
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $str;
	}


	/**
	 * 生成微信安全码
	 * @param string $token 令牌
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字串
	 * @return string $signature 安全码
	 */
	public function get_signature($token, $timestamp, $nonce)
	{
		$signature = array($token, $timestamp, $nonce);
		sort($signature, SORT_STRING);
		$signature = implode($signature);
		$signature = sha1($signature);
		return $signature;
	}


	/**
	 * 生成微信安全码
	 * @param string $cellphone 令牌
	 * @param string $captcha 时间戳
	 * @param string $corp 随机字串
	 * @return string $error 返回代码
	 */
	public function send_sms($cellphone, $captcha, $corp = '湖北卫视')
	{
		$content = URLEncode('验证码为 '.$captcha.' （有效期五分钟，切勿告知他人），请在页面中输入以完成验证。【'.$corp.'】');
		$url = 'http://utf8.sms.webchinese.cn/?Uid=hbtv_bidding&Key=4beeda70e1d74ceeadaf&smsMob='.$cellphone.'&smsText='.$content;
		return $this->http_get($url);
	}


}

/* End of file Fpms_library.php */
/* Location: ./application/libraries/Fpms_library.php */