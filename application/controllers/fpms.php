<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 粉丝积分管理系统 Fans Points Management System
 * 功能：前端微信模块
 */
class Fpms extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}


	/**
	* 后台登陆入口
	*/
	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '%s未填');
		$this->form_validation->set_rules('username', '用户名', 'required|trim');
		$this->form_validation->set_rules('password', '密码', 'required|trim');
		if ($this->form_validation->run() == FALSE)
		{
			$data['msg'] = '';
			$this->load->view('header');
			$this->load->view('login', $data);
			$this->load->view('footer');
		}
		else
		{
			$post_data = $this->input->post(NULL, TRUE);
			$admins = $this->fpms_model->get_tables('admins', 'adminsid desc', array('adminsname'=>$post_data['username']));
			if ((count($admins) != 1) || ($admins[0]['adminspass'] != $this->fpms_library->get_pass($post_data['password'])))
			{
				$data['msg'] = '您的用户名或密码不正确';
				$this->load->view('header');
				$this->load->view('login', $data);
				$this->load->view('footer');
			}
			else
			{
				$this->session->set_userdata($admins[0]);
				redirect('fpms_admin');
			}
		}
	}


	/**
	* 提示消息显示页面
	*/
	public function message()
	{
		$data['message']=$this->session->userdata('message');
		$this->load->view('header');
		$this->load->view('message', $data);
		$this->load->view('footer');
	}


	/**
	* 微信接入入口
	* @param string $publicid 接入的公众号ID
	*/
	public function wechat($publicid = 'fpms')
	{
		$accounts = $this->fpms_model->get_tables('accounts', 'accountsid desc', array('publicid'=>$publicid), 'appid,appsecret,token,wallverify,redpackstartid,redpackendid');
		if (count($accounts) != 1) { exit('未注册公众号');}
		$this->load->library('wechat', $accounts[0]);
		$weObj = $this->wechat;
		$weObj->valid();
		$type = $weObj->getRev()->getRevType();
		if ($publicid != $weObj->getRevTo()) { $weObj->text("公众号不匹配")->reply();exit;}
		$openid = $weObj->getRevFrom();
		$token = $this->fpms_library->get_token($publicid, $openid);

		switch($type) {
			case Wechat::MSGTYPE_TEXT:
				$msg_content = $weObj->getRevContent();
				$msg_post = array(
					'openid' => $openid,
					'publicid' => $publicid,
					'infomation' => $msg_content,
					'infostype' => 1,
					'infostime' => time(),
					'infosstatus' => $accounts[0]['wallverify'],
				);
				$this->fpms_model->set_tables('infos', $msg_post);
				if ($publicid == 'gh_502f6a0d68f3' && is_numeric($msg_content) && mb_strlen($msg_content) == 11)
				{
					$redpackstartid = intval($accounts[0]['redpackstartid']);
					$redpackendid = intval($accounts[0]['redpackendid']);
					$result = $this->fpms_model->get_tables('redpacks', FALSE, array('redpacksid >'=>$redpackstartid, 're_password'=>$msg_content), 'redpacksid,re_openid,re_remark,redpackstime,redpacksstatus');
					if (count($result) > 0)
					{
						$redpacksstatus = intval($result[0]['redpacksstatus']);
						$redpacksid = intval($result[0]['redpacksid']);
						switch($redpacksstatus)
						{
							case 0:
								if ($redpacksid > $redpackendid)
								{
									$weObj->text("您好，长江云本轮".($redpackendid-$redpackstartid)."个红包已经发放完毕，敬请期待下一轮红包雨，感谢您的关注！")->reply();
									exit;
								}
								$set_redpack = array(
										'openid' => $openid,
										'redpackstime' => time(),
										'redpacksstatus' => 1
								);
								$this->fpms_model->set_tables('redpacks', $set_redpack, array('redpacksid'=>$redpacksid));
								$weObj->text("红包密码正确，您在游戏中得了".$result[0]['re_remark']."个红包，正在为您计算红包金额，请留意微信首屏的官方【服务通知】，红包每人限领一次。")->reply();
								break;
							case 1:
								$weObj->text("你的红包正在排队等候发送中，请留意微信首屏的官方【服务通知】，红包每人限领一次。")->reply();
								break;
							default:
								$weObj->text("你的红包".date('m月d日', $result[0]['redpackstime'])."已发放了哟，请留意微信首屏的官方【服务通知】，红包每人限领一次。\n\n注：微信官方判断为异常微信号的领不到红包")->reply();
						}
					}
					else
					{
						$weObj->text("红包密码不对哟。")->reply();
					}
				}
				else
				{
					$weObj->text("文字已接收")->reply();
					/*
					if (mb_strpos($msg_content,'红包') == FALSE)
					{
						$weObj->text("文字已接收")->reply();
					}
					else
					{
						$weObj->text("红包密码为1开头的11位数字。\n\n红包由微信官方【服务通知】发放，请返回微信首屏领取。\n\n<a href='http://hudong.hbtv.com.cn/html/game_catch2.php'>得红包入口在这里，先玩游戏才有红包拿哦！</a>")->reply();
					}
					*/
				}
				break;
			case Wechat::MSGTYPE_IMAGE:
				$msg_pic = $weObj->getRevPic();
				$msg_post = array(
					'openid' => $openid,
					'publicid' => $publicid,
					'infomation' => $msg_pic['mediaid'],
					'infostype' => 2,
					'infostime' => time(),
					'infosstatus' => $accounts[0]['wallverify'],
				);
				$this->fpms_model->set_tables('infos', $msg_post);
				$weObj->text("图片已接收")->reply();
				break;
			case Wechat::MSGTYPE_EVENT:
				$msg_event = $weObj->getRevEvent();
				switch($msg_event['event'])
				{
					case Wechat::EVENT_SUBSCRIBE:
						$this->fpms_model->get_fans($publicid, $openid);
						$weObj->text("Hi！欢迎关注。")->reply();
						break;
					case Wechat::EVENT_UNSUBSCRIBE:
						$weObj->text('再次关注就会更新菜单哟')->reply();
						break;
					case Wechat::EVENT_MENU_VIEW:
						$this->fpms_model->get_fans($publicid, $openid);
						break;
					case Wechat::EVENT_MENU_CLICK:
						switch($msg_event['key'])
						{
							case 'wechatshake':
								$msg_data = array(array(
									'Title' => '摇一摇大抽奖',
									'Description' => '点击进入',
									'PicUrl' => '',
									'Url' => site_url('fpms/urls/'.$publicid.'/'.$openid.'/'.$token.'/fpms/wechatshake'),
								));
								$weObj->news($msg_data)->reply();
								break;
							case 'sign':
								$msg_data = array(array(
									'Title' => '签到',
									'Description' => '',
									'PicUrl' => '',
									'Url' => site_url('fpms/urls/'.$publicid.'/'.$openid.'/'.$token.'/fpms_points/sign'),
								));
								$weObj->news($msg_data)->reply();
								break;
							default:
								$weObj->text('未定义菜单项')->reply();
						}
						break;
				}
				break;
			default:
				$this->fpms_model->get_fans($publicid, $openid);
				$weObj->text('欢迎访问')->reply();
		}
	}


	/**
	* 微信墙显示
	* @param string $accountsid 公众号内部编号
	* @param string $action 数据输出类型
	* @return json 微信上墙信息
	*/
	public function wechatwall($accountsid = 0, $action = 'html')
	{
		$accountsid = intval($accountsid);
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$accountsid), 'publicid,wallstatus,walltime');
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		$wallstatus = intval($accounts[0]['wallstatus']);
		$walltime = intval($accounts[0]['walltime']);
		if ($wallstatus == 0) { return FALSE;}
		switch ($action)
		{
			case 'json':
				$infosid = intval($this->input->post('lastid'));
				$wechatwall = $this->fpms_model->get_wechatwall($publicid, $infosid, $walltime, 1, 16);
				echo json_encode($wechatwall);
				break;
			default:
				$template = $this->fpms_model->get_tables('templates', 'templatesid desc', array('accountsid'=>$accountsid, 'templatestype'=>'wechatwall', 'templatesstatus'=>1));
				if (count($template) == 0) { exit('<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>模板未找到</body></html>');}
				$data = array(
					'tpl' => $template[0],
					'tplurl' => base_url('uploads/wechatwall'),
					'wallurl' => site_url('fpms/wechatwall/'.$accountsid.'/json'),
				);
				$this->load->view($template[0]['templatesurl'], $data);
		}
	}


	/**
	* 摇一摇互动
	* @param string $accountsid 公众号内部编号
	* @param string $action 数据输出类型
	* @return json 摇一摇抽奖
	*/
	public function wechatshake($action = 'html')
	{
		$openid = $this->session->userdata('openid');
		if ($openid == FALSE) { return FALSE;}
		$publicid = $this->session->userdata('publicid');
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('publicid'=>$publicid), 'accountsid,shakestatus,shaketime,shakemax,shaketoken,winstatus,wintime');
		if (count($accounts) != 1) { return FALSE;}
		$accountsid = intval($accounts[0]['accountsid']);
		$shakestatus = intval($accounts[0]['shakestatus']);
		$shaketime = intval($accounts[0]['shaketime']);
		$shakemax = intval($accounts[0]['shakemax']);
		$shaketoken = intval($accounts[0]['shaketoken']);
		switch ($action)
		{
			case 'json':
				$post_token = intval($this->input->post('token'));
				if ($shaketoken == $post_token)
				{
					if ($shakestatus == 1)
					{
						$count = intval($this->input->post('count'));
						$shake_result = $this->fpms_model->set_tables('fans', array('shakes'=>$count, 'lasttime'=>time()), array('openid'=>$openid));
						if ($count >= $shakemax)
						{
							$this->fpms_model->set_tables('accounts', array('shakestatus'=>2), array('accountsid'=>$accountsid));
							$shakestatus = 2;
						}
					}
				}
				else
				{
					$shakestatus = 9;
				}
				$wechatshake = array(
					'isact' => $shakestatus,
				);
				echo json_encode($wechatshake);
				break;
			default:
				$winstatus = intval($accounts[0]['winstatus']);
				if ($winstatus == 0)
				{
					$fansid = $this->session->userdata('fansid');
					$winstime = time() - intval($accounts[0]['wintime']);
					$wins = $this->fpms_model->get_tables('wins', FALSE, array('accountsid'=>$accountsid, 'fansid'=>$fansid, 'winsstatus'=>1, 'winstime >'=>$winstime));
					if (count($wins) > 0)
					{
						$this->session->set_userdata('message', '你已获奖了。<br>时间：'.date('m-d H:i', $wins[0]['winstime']).'<br>排名：'.$wins[0]['winsrank'].'<br>密码：'.$wins[0]['winstoken']);
						redirect('fpms/message');
					}
				}
				$this->fpms_model->set_tables('fans', array('lasttime'=>time()), array('openid'=>$openid));
				$data = array(
					'accountsid' => $accountsid,
					'isact' => $shakestatus,
					'shaketime' => $shaketime,
					'shakemax' => $shakemax,
					'shaketoken' => $shaketoken,
					'shakeurl' => site_url('fpms/wechatshake/json'),
					'shakes' => 0,
				);
				if ($shakestatus > 0)
				{
					$fans = $this->fpms_model->get_tables('fans', FALSE, array('openid'=>$openid), 'shakes');
					if (count($fans) != 0)
					{
						$data['shakes'] = $fans[0]['shakes'];
					}
				}
				$this->load->view('wechatshake_wap', $data);
		}
	}


	/**
	* 红包接口
	* @param string $accountsid 公众号内部编号
	* @param string $action 数据输出类型
	* @return json 微信红包信息
	*/
	public function wechatredpack($accountsid = 0, $action = 'html')
	{
		$accountsid = intval($accountsid);
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$accountsid), 'publicid,redpackstatus,redpackstartid,redpackendid');
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		$redpackstatus = intval($accounts[0]['redpackstatus']);
		$redpackstartid = intval($accounts[0]['redpackstartid']);
		$redpackendid = intval($accounts[0]['redpackendid']);
		switch ($action)
		{
			//接收微信红包订单
			case 'json':
				$re_openid = $this->input->post('t1');
				$re_token = $this->input->post('t2');
				$re_remark = $this->input->post('t3');
				$re_password = intval($this->input->post('t4'));
				$data = array(
						'isact' => $redpackstatus,
						'msg' => '红包密码【'.$re_password.'】，快去“长江云”公众号回复密码领取红包吧。',
				);
				if ($this->fpms_library->get_pass($re_openid) != $re_token)
				{
					$data['msg'] = '信息验证未通过，请关闭此页面后重新进入。';
					//$data['msg'] = $this->fpms_library->get_pass($re_openid).'/'.$re_token;
					echo json_encode($data);
					exit;
				}
				if ($redpackstatus == 1)
				{
					$re_openid_result = $this->fpms_model->get_tables('redpacks', FALSE, array('redpacksid >'=>$redpackstartid, 're_openid'=>$re_openid), 're_time');
					if (count($re_openid_result) > 0)
					{
						$data['msg'] = '你'.date('m月d日', $re_openid_result[0]['re_time']).'已申领过了，每人限领一次哟。';
						echo json_encode($data);
						exit;
					}
					$re_password_result = $this->fpms_model->get_tables('redpacks', FALSE, array('redpacksid >'=>$redpackstartid, 're_password'=>$re_password), 're_time');
					if (count($re_password_result) > 0)
					{
						$data['msg'] = '密码太简单了，换一个吧。';
						echo json_encode($data);
						exit;
					}
					$re_data = array(
							're_openid' => $re_openid,
							're_password' => $re_password,
							're_remark' => $re_remark,
							're_time' => time(),
							'publicid' => $publicid,
					);
					$result = $this->fpms_model->set_tables('redpacks', $re_data);
					if ($result == FALSE)
					{
						$data['msg'] = '本次红包申领出错了，请再提交一次试试。';
					}
				}
				echo json_encode($data);
				break;
			//发放微信红包
			case 'bill':
				$result = $this->db->query('select redpacksid,re_openid from redpacks where redpacksid < '.$redpackendid.' and redpacksid > '.$redpackstartid.' and redpacksstatus = 1 limit 30')->result();
				//var_dump($result);
				//exit;
				foreach($result as $item)
				{
								$mch_billno = '1309235301'.date("YmdHis").mt_rand(1000,9999);
								$total_amount = mt_rand(100,120);
								$set_redpack = array(
										'mch_billno' => $mch_billno,
										'total_amount' => $total_amount,
										'redpacksstatus' => 2
								);
								$this->fpms_model->set_tables('redpacks', $set_redpack, array('redpacksid'=>$item->redpacksid));
								$send_redpack = array(
										'mch_billno' => $mch_billno,
										'total_amount' => $total_amount,
										'total_num' => 1,
										're_openid' => $item->re_openid,
										're_token' => $this->fpms_library->get_pass($item->re_openid),
										'send_name' => '长江云',
										'wishing' => '玩游戏，长江云发红包，看谁得得多',
										'act_name' => '玩游戏长江云发红包',
										'remark' => '快点拆开看看吧',
								);
								//var_dump($send_redpack);
								$this->fpms_library->get_curl('http://hudong.hbtv.com.cn/pay/redpack.php', $send_redpack, 15);
				}
				break;
			//显示红包活动
			default:
				if ($redpackstatus == 0)
				{
					exit('<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>红包接口已关闭</body></html>');
				}
				else
				{
					exit('<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>未查询到您的红包</body></html>');
				}
		}
	}


	/**
	 * 接收微信信息
	 * @param string $publicid 公众号
	 * @param string $openid 关注者
	 * @param string $token 由公众号和关注者加密生成的通信令牌
	 * @param int $status 状态0未审核、1审核通过
	 * @param int $post_type 接收类型（1文字/2图片/3语音/62视频）
	 * @param string $post->content 接收内容
	 * @return bool(TRUE)
	 */
	public function infos($publicid = 'publicid', $openid = 'openid', $token = 'token', $status = 0)
	{
		if ($this->fpms_library->get_token($publicid, $openid) != $token) { return FALSE;}
		$type = $this->input->post('type');
//		$content = $this->input->post('content', TRUE);
		$content = $this->input->post('content');
		$createtime = time();
		$savepath = 'uploads/weixin/';
		$data = array(
				'openid' => $openid,
				'publicid' => $publicid,
				'infomation' => $content,
				'infostype' => $type,
				'infostime' => $createtime,
				'infosstatus' => $status,
		);
		switch ($type)
		{
			//文字信息
			case 1:
				break;
			//图片信息
			case 2:
				$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('publicid'=>$publicid));
				if (count($accounts) != 1) { return FALSE;}
				$this->load->library('wechat', $accounts[0]);
				$upload_file = $savepath.date('YmdHis', time()).'_'.$openid.'.jpg';
				$fp = fopen($upload_file, 'w');
				fwrite($fp, $this->wechat->getMedia($content));
				fclose($fp);
				$data['infomation'] = $upload_file;
				break;
			//语音信息
			case 3:
				break;
			//视频信息
			case 62:
				break;
		}
		$this->fpms_model->set_tables('infos', $data);
	}


	/**
	 * 接收微信事件跳转处理
	 * @param string $publicid 公众号
	 * @param string $openid 关注者
	 * @param string $token 由公众号和关注者加密生成的通信令牌
	 * @param string $url_class 跳转类
	 * @param string $url_function 跳转函数
	 */
	public function urls($publicid = 'publicid', $openid = 'openid', $token = 'token', $url_class = 'fpms_points', $url_function = 'index', $url_parameter = 'html')
	{
		if ($this->fpms_library->get_token($publicid, $openid) != $token)
		{
			$this->session->set_userdata('message', '地址已过期，请点击微信菜单访问页面。');
			redirect('fpms/message');
		}
		$fans = $this->fpms_model->get_fans($publicid, $openid);
		if ($fans == FALSE)
		{
			$this->session->set_userdata('message', '获取用户信息失败，请稍后重新访问。');
			redirect('fpms/message');
		}
		redirect($url_class.'/'.$url_function.'/'.$url_parameter);
	}


	/**
	* 生成分享接口签名
	* @post param string $accountsid,$url
	* @return array 分享签名
	*/
	public function jsapi($accountsid = 0)
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>intval($accountsid)), 'appid,appsecret,cjyun');
		if (count($accounts) != 1) { return FALSE;}
		$this->load->library('wechat', $accounts[0]);
		//$this->fpms_library->update_access_token($accounts[0]['cjyun'], $accounts[0]['appid']);
		$data = $this->wechat->getJsSign(rawurldecode($_GET['url']));
		$this->load->view('jsapi', $data);
	}


	/**
	* 生成网页授权接口
	* @post param string $accountsid,$url
	* @return array 分享签名
	*/
	public function oauth($accountsid = 0)
	{
		$accountsid = intval($accountsid);
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$accountsid), 'appid,appsecret,cjyun');
		if (count($accounts) != 1) {
			$this->session->set_userdata('message', '未找到公众号信息');
			redirect('fpms/message');
		}
		$this->load->library('wechat', $accounts[0]);
		//$this->fpms_library->update_access_token($accounts[0]['cjyun'], $accounts[0]['appid']);
		$oauthfans = $this->wechat->getOauthAccessToken();
		if ($oauthfans == false)
		{
			$this->session->set_userdata('message', '获取用户信息失败，请稍后重新访问。');
			redirect('fpms/message');
		}
		$openid = isset($oauthfans['openid'])?$oauthfans['openid']:false;
		$state = isset($_GET['state'])?$_GET['state']:false;
		if (($openid == FALSE) || ($state == FALSE))
		{
			$this->session->set_userdata('message', '信息接收不全，请稍后重新访问。');
			redirect('fpms/message');
		}
		$fans = $this->fpms_model->get_tables('fans', FALSE, array('openid'=>$openid));
		if (count($fans) != 1)
		{
			$scope = isset($oauthfans['scope'])?$oauthfans['scope']:false;
			$newaccounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$accountsid), 'accountsname,accountsqrcode,publicid');
			if ($scope == 'snsapi_userinfo')
			{
				$newfans = $this->wechat->getOauthUserinfo($oauthfans['access_token'], $openid);
				if ($newfans == FALSE)
				{
					$this->session->set_userdata('message', '获取用户信息失败，请稍后重新访问。');
					redirect('fpms/message');
				}
				else
				{
					// TODO 写入用户信息
					$this->session->set_userdata('message', '请先关注公众号<br>【'.$newaccounts[0]['accountsname'].'】');
					redirect('fpms/message');
				}
			}
			else
			{
				$this->session->set_userdata('message', '请先关注公众号<br>【'.$newaccounts[0]['accountsname'].'】');
				redirect('fpms/message');
			}
		}
		else
		{
			$this->session->set_userdata($fans[0]);
			redirect('fpms/'.$state);
		}
	}


	/**
	* 测试接口
	* @post param int $accountsid
	* @return array 测试
	*/
	public function test($accountsid = 1, $v1 = 0, $v2 = 86400)
	{
		$accounts = $this->fpms_model->get_tables('accounts', 'accountsid desc', array('accountsid'=>$accountsid));
		if (count($accounts) != 1) { exit('accountsid error');}
		/*
		$result = $this->db->select('redpacksid')->from('redpacks')->order_by('redpacksid desc')->limit(1)->get()->result();
		foreach($result as $item)
		{
			$redpacksid = $item->redpacksid;
			break;
		}
		*/
		$redpacksid = $this->db->query('select redpacksid from redpacks order by redpacksid desc limit 1')->row()->redpacksid;
		if (intval($redpacksid) > 2)
		{
			echo '红包领完了';
		}
		echo '<pre>';
		var_dump($redpacksid);
		echo '</pre>';
		exit;
		$infosid = $v1;
		$walltime = $v2;
		$wechatwall = $this->fpms_model->get_wechatwall($accounts[0]['publicid'], $infosid, $walltime);
	}


	/**
	* 摇一摇数据导入
	* @return {"yao_id":yao_id, "yao_msg":[{"openid":openid, ...}, {...}, ...]}
	*/
	public function yaotvs($accountsid = 0)
	{
		$accounts = $this->fpms_model->get_tables('accounts', 'accountsid desc', array('accountsid'=>$accountsid));
		if (count($accounts) != 1) { return FALSE;}
		$yaotv_id = $accounts[0]['yaoid'];
		//$token = 'token';
		//$timestamp = time();
		//$nonce = $this->fpms_library->get_nonce();
		//$signature = $this->fpms_library->get_signature($token, $timestamp, $nonce);
		//$yaotv_url = 'http://119.29.62.133:9111/test';
		$nonce = '1';
		$token = 'aaa';
		$timestamp = '2015-06-08 12:14:00.0';
		$signature = sha1($nonce.$token.$timestamp);
		//exit($signature);
		$yaotv_url = 'http://119.29.62.133:9111/test';
		$data = array(
			'token' => $token,
			'timestamp' => $timestamp,
			'nonce' => $nonce,
			'signature' => $signature,
			'id' => $yaotv_id,
		);
		echo var_dump($data);
		echo '<hr>';
		//$result = $this->fpms_library->http_post($yaotv_url, $data);
		$result = json_decode($result, TRUE);
		echo var_dump($result);
		echo '<hr>'.$result['yao_id'];
		if ($yaotv_id < $result['yao_id'])
		{
			$this->db->insert_batch('yaotvs', $result['yao_msg']);
			foreach ($result['yao_msg'] as $item)
			{
				echo var_dump($item);
				echo '<hr>';
			}
		}
	}


}

/* End of file fpms.php */
/* Location: ./application/controllers/fpms.php */