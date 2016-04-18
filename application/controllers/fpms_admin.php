<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 粉丝积分管理系统 Fans Points Management System
 * 功能：后台管理模块
 */
class Fpms_admin extends CI_Controller {
	private $accountsid = 0;
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('adminsid') == FALSE) { redirect('fpms');}
		$this->accountsid = intval($this->session->userdata('accountsid'));
	}


	/**
	* 后台功能列表
	*/
	public function index()
	{
		$normal = array(
				'微信墙' => 'fpms_admin/walls',
				'摇一摇' => 'fpms_admin/shakes',
				'中奖名单' => 'fpms_admin/wins',
				'模板信息' => 'fpms_admin/templates',
                '微信菜单' => 'fpms_admin/menus',
				'退出后台' => 'fpms_admin/logout',
		);
		$super = array(
				'---------' => 'fpms_admin/test',
				'微信点歌' => 'fpms_admin/helps',
				'微摇信息' => 'fpms_admin/yaotvs',
				'微信信息' => 'fpms_admin/infos',
				'红包信息' => 'fpms_admin/redpacks',
				'会员信息' => 'fpms_admin/fans',
				'积分信息' => 'fpms_admin/points',
				'积分兑换' => 'fpms_admin/exchanges',
				'商品规则' => 'fpms_admin/goods',
				'积分规则' => 'fpms_admin/rules',
				'分类规则' => 'fpms_admin/types',
				'公众号' => 'fpms_admin/accounts',
				'管理员' => 'fpms_admin/admins',
		);
		if ($this->session->userdata('adminslevel') == 9)
		{
			$data['nav'] = array_merge($normal, $super);
		}
		else
		{
			$data['nav'] = $normal;
		}
		$this->load->view('header');
		$this->load->view('admin', $data);
		$this->load->view('footer');
	}


		public function test()
		{
			echo date('H:i:s').'<hr>';
			for($i=1;$i<50;$i++)
			{
				$mch_billno = '1309235301'.date("YmdHis").mt_rand(1000,9999);
				$total_amount = mt_rand(100,120);
				$re_openid = 'oG1fft2U3_japyz88_ibRiLYTY3g';
				$send_redpack = array(
						'mch_billno' => $mch_billno,
						'total_amount' => $total_amount,
						'total_num' => 1,
						're_openid' => $re_openid,
						're_token' => $this->fpms_library->get_pass($re_openid),
						'send_name' => '长江云',
						'wishing' => '捞元宵，抢长江云红包，看谁捞得多',
						'act_name' => '捞元宵，抢长江云红包捞元宵，抢长江云红包',
						'remark' => '捞元宵，抢红包',
				);
				//var_dump($send_redpack);
				$time_start = microtime('get_as_float');
				$this->fpms_library->get_curl('http://hudong.hbtv.com.cn/pay/redpack2.php', $send_redpack);
				//$weibo = $this->fpms_library->get_curl2('http://m.weibo.cn/page/json?containerid=1005052418542712_-_WEIBO_SECOND_PROFILE_WEIBO&page='.$i, $send_redpack);
				//var_dump(json_decode($weibo)->ok);
				$time_end = microtime('get_as_float')-$time_start;
				if ($time_end > 5)
				{
					echo '<span style="font-size:2rem;color:#990000;">'.$time_end.'</span><br>';
				}
				else
				{
					echo $time_end.'<br>';
				}
			}
		}


	/**
	* 帮助信息
	*/
	public function helps()
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid), 'publicid,appid,appsecret,token,cjyun');
		if (count($accounts) != 1) { return FALSE;}
		$this->load->library('wechat', $accounts[0]);
		$callback = site_url('fpms/oauth/'.$this->accountsid);
		$state = 'wechatshake';
		$scope = 'snsapi_base';
		$accounts[0]['oauthurl'] = $this->wechat->getOauthRedirect($callback, $state, $scope);
		$this->load->view('header');
		$this->load->view('helps', $accounts[0]);
		$this->load->view('footer');
	}


	/**
	* 生成网页授权接口
	* @post param string $accountsid,$url
	* @return array 分享签名
	*/
	public function menus($action='html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid), 'appid,appsecret,token,cjyun');
		if (count($accounts) != 1) { return FALSE;}
		$this->load->library('wechat', $accounts[0]);
		$callback = site_url('fpms/oauth/'.$this->accountsid);
		$state = 'wechatshake';
		$scope = 'snsapi_base';
		$oauthurl = $this->wechat->getOauthRedirect($callback, $state, $scope);

		switch ($action)
		{
			case 'set':
				$wosicesihao = array (
					'button' => array (
						array (
							'name' => '云上恩施',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '直播',
									'url' => 'http://hudong.hbtv.com.cn/cjyuncalendar/platform/estv',
								),
								array (
									'type' => 'view',
									'name' => '点播',
									'url' => 'http://m.enshi.cjyun.org/dianbo',
								),
								array (
									'type' => 'view',
									'name' => '关于云上恩施',
									'url' => 'http://m.enshi.cjyun.org/hyzl',
								),
							),
						),
						array (
							'name' => '发红包',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '小金猴翻长江云',
									'url' => 'http://hudong.hbtv.com.cn/html/game_jump.php',
								),
								array (
									'type' => 'view',
									'name' => '捞元宵抢红包',
									'url' => 'http://hudong.hbtv.com.cn/html/game_catch.php',
								),
								array (
									'type' => 'view',
									'name' => '抢红包2',
									'url' => 'http://hudong.hbtv.com.cn/html/game_catch2.php',
								),
							),
						),
						array (
							'name' => '摇一摇',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '网页版',
									'url' => $oauthurl,
								),
								array (
									'type' => 'click',
									'name' => '事件版',
									'key' => 'wechatshake',
								),
							),
						),
					),
				);
				$cjyun0107 = array (
					'button' => array (
						array (
							'name' => '长江新闻',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '新闻日历',
									'url' => 'http://www.hbtv.com.cn/hbtv/ctxw/hbxw/index_new.html',
								),
								array (
									'type' => 'view',
									'name' => '图片新闻',
									'url' => 'http://m.hbtv.com.cn/gallery',
								),
								array (
									'type' => 'view',
									'name' => '湖北之声',
									'url' => 'http://www.hbtv.com.cn/cjmedia/hbzs.shtml',
								),
								array (
									'type' => 'view',
									'name' => '广电直播',
									'url' => 'http://m.hbtv.com.cn/live',
								),
							),
						),
						array (
							'name' => '2016两会',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '两会现场声',
									'url' => 'http://m.hbtv.com.cn/qglh2016',
								),
								array (
									'type' => 'view',
									'name' => '两会关键词',
									'url' => 'http://cntvnews-cdn-td.mtq.tvm.cn/data/cctvnews/keywords/lianghui2016.html?from=singlemessage&isappinstalled=0',
								),
								array (
									'type' => 'view',
									'name' => '湖北吸引力',
									'url' => 'http://m.hbtv.com.cn/qglh2016',
								),
								array (
									'type' => 'view',
									'name' => '大V影像志',
									'url' => 'http://m.hbtv.com.cn/qglh2016',
								),
							),
						),
						array (
							'name' => '网友互动',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '微博爆料',
									'url' => 'http://weibo.com/u/1966715680',
								),
								array (
									'type' => 'view',
									'name' => '今日头条',
									'url' => 'http://toutiao.com/m4273783271/',
								),
								array (
									'type' => 'view',
									'name' => '粉丝福利',
									'url' => 'http://gsactivity.diditaxi.com.cn/gulfstream/activity/v2/giftpackage/index?g_channel=69cca685511e5a2313f52a87af0b6b01',
								),
								array (
									'type' => 'view',
									'name' => '下载App',
									'url' => 'http://www.hbtv.com.cn/cjmedia/download_m.shtml',
								),
							),
						),
					),
				);
				$estv1212 = array (
					'button' => array (
						array (
							'name' => '云上恩施',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '直播',
									'url' => 'http://hudong.hbtv.com.cn/cjyuncalendar/platform/estv',
								),
								array (
									'type' => 'view',
									'name' => '点播',
									'url' => 'http://m.enshi.cjyun.org/dianbo',
								),
								array (
									'type' => 'view',
									'name' => '微信矩阵',
									'url' => 'http://m.enshi.cjyun.org/rmwx',
								),
								array (
									'type' => 'view',
									'name' => '关于云上恩施',
									'url' => 'http://m.enshi.cjyun.org/hyzl',
								),
                                array (
                                    'type' => 'view',
                                    'name' => '广电资源推介',
                                    'url' => 'http://viewer.maka.im/pcviewer/I5M75PU8',
                                ),
							),
						),
                        array (
                            'name' => '最美天使',
                            'sub_button' => array (
                                array (
                                    'type' => 'click',
                                    'name' => '优秀护理团队',
                                    'key' => '优秀护理团队组',
                                ),
                                array (
                                    'type' => 'click',
                                    'name' => '最美白衣天使',
                                    'key' => '最美白衣天使组',
                                ),
                                array (
                                    'type' => 'click',
                                    'name' => '感动白衣天使',
                                    'key' => '感动白衣天使组',
                                ),
                                array (
                                    'type' => 'click',
                                    'name' => '技艺才艺天使',
                                    'key' => '技艺',
                                ),
                                array (
                                    'type' => 'click',
                                    'name' => '上镜人气天使',
                                    'key' => '上镜',
                                ),
                            ),
                        ),
                        array (
                            'name' => '春季车展',
                            'sub_button' => array (
                                array (
                                    'type' => 'view',
                                    'name' => '线上展厅',
                                    'url' => 'http://xiu.hbswlc.com/v-U704ND2X9E?eqrcode=1&from=singlemessage&isappinstalled=0',
                                ),
                                array (
                                    'type' => 'view',
                                    'name' => '砍价无下限',
                                    'url' => 'http://xiu.hbswlc.com/v-U704VAU5K3?eqrcode=1&from=singlemessage&isappinstalled=0',
                                ),
                                array (
                                    'type' => 'view',
                                    'name' => '十佳车型评选',
                                    'url' => 'http://yes02.lxuiu.cc/plugin.php?id=hejin_toupiao&model=votea&vid=1',
                                ),
                                array (
                                    'type' => 'view',
                                    'name' => '摇一摇',
                                    'url' => $oauthurl,
                                ),
                            ),
                        ),
					),
				);
				$lttv0115 = array (
					'button' => array (
						array (
							'name' => '媒体矩阵',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '罗田新闻',
									'url' => 'http://m.luotiannews.com',
								),
								array (
									'type' => 'view',
									'name' => '走遍罗田',
									'url' => 'http://dwz.cn/2uOkIb',
								),
								array (
									'type' => 'view',
									'name' => '印象罗田',
									'url' => 'http://dwz.cn/2uOq9S',
								),
								array (
									'type' => 'view',
									'name' => '唱响罗田',
									'url' => 'http://dwz.cn/2uOofZ',
								),
							),
						),
						array (
							'name' => '微直播',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '微直播',
									'url' => 'http://m.luotiannews.com/p/2884.html',
								),
								array (
									'type' => 'view',
									'name' => '手机电视',
									'url' => 'http://m.luotiannews.com/live',
								),
								array (
									'type' => 'view',
									'name' => '凤城茶馆',
									'url' => 'http://bbs.luotiannews.com/',
								),
								array (
									'type' => 'view',
									'name' => '兴趣部落',
									'url' => 'http://dwz.cn/2uOu2u',
								),
							),
						),
						array (
							'name' => '会议互动',
							'sub_button' => array (
								array (
									'type' => 'view',
									'name' => '找座位',
									'url' => 'http://dwz.cn/2ATZeV',
								),
								array (
									'type' => 'view',
									'name' => '会务指南',
									'url' => 'http://dwz.cn/2AGvro',
								),
								array (
									'type' => 'view',
									'name' => '摇一摇',
									'url' => $oauthurl,
								),
								array (
									'type' => 'view',
									'name' => '会议直播',
									'url' => 'http://www.luotiannews.com/',
								),
							),
						),
					),
				);
				if ($this->wechat->createMenu($$accounts[0]['token']))
				{
					echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>菜单更新成功</body></html>';
				}
				else
				{
					echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>菜单更新失败</body></html>';
				}
				break;
			default:
				$menus = $this->wechat->getMenu();
				$data['menus'] = $menus;
				$data['menusurl'] = site_url('fpms_admin/menus/set');
				$this->load->view('header');
				$this->load->view('menus', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 微信墙管理
	*/
	public function walls($action = 'html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid), 'accountsid,publicid,wallstatus,wallverify,walltime');
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		$walltime = intval($accounts[0]['walltime']);
		switch ($action)
		{
			case 'json':
				$infosid = intval($this->input->post('lastid'));
				$wechatwall = $this->fpms_model->get_wechatwall($publicid, $infosid, $walltime, 2, 0);
				echo json_encode($wechatwall);
				break;
			//信息审核修改
			case 'wallfans':
				$infosid = intval($this->input->post('infosid'));
				$wall = $this->fpms_model->get_tables('infos', FALSE, array('infosid'=>$infosid), 'openid,publicid');
				if (count($wall) != 1)
				{
					$data = array('inputval'=>'参数错误');
				}
				else
				{
					$fans = $this->fpms_model->get_fans($wall[0]['publicid'], $wall[0]['openid']);
					$this->session->unset_userdata('openid');
					$data = array('inputval'=>$fans['nickname']);
				}
				echo json_encode($data);
				break;
			//信息审核修改
			case 'wallmessage':
				$infosid = intval($this->input->post('infosid'));
				$status = intval($this->input->post('status'));
				if ($status == 1) { $wallmessage = 0;} else { $wallmessage = 1;}
				if ($this->fpms_model->set_tables('infos', array('infosstatus'=>$wallmessage), array('infosid'=>$infosid)) == TRUE)
				{
					$data = array(
						'status' => $wallmessage
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			//修改开始时间
			case 'walltime':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('walltime'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			//不需要审核开关
			case 'wallverify':
				$status = intval($this->input->post('status'));
				if ($status == 1) { $wallverify = 0;} else { $wallverify = 1;}
				if ($this->fpms_model->set_tables('accounts', array('wallverify'=>$wallverify), array('accountsid'=>$this->accountsid)) == TRUE)
				{
					$data = array(
						'status' => $wallverify
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			//微信墙开关
			case 'wallstatus':
				$status = intval($this->input->post('status'));
				if ($status == 1) { $wallstatus = 0;} else { $wallstatus = 1;}
				if ($this->fpms_model->set_tables('accounts', array('wallstatus'=>$wallstatus), array('accountsid'=>$this->accountsid)) == TRUE)
				{
					$data = array(
						'status' => $wallstatus
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			default:
				$infos = $this->fpms_model->get_wechatwall($publicid, 0, $walltime, 2, 0);
				$data['accounts'] = $accounts;
				$data['infos'] = $infos['wall_msg'];
				$data['wechatwallurl'] = site_url('fpms/wechatwall/'.$this->accountsid);
				$data['wallsurl'] = site_url('fpms_admin/walls');
				$this->load->view('header');
				$this->load->view('walls', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 摇一摇管理
	* @param string $accountsid 公众号内部编号
	* @param string $action 数据输出类型
	* @return json 摇一摇抽奖
	*/
	public function shakes($action = 'html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid), 'publicid,shakestatus,shaketime,shakemax,shakeend,shakewin,shaketoken');
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		$shakestatus = intval($accounts[0]['shakestatus']);
		$shakeend = intval($accounts[0]['shakeend']);
		switch ($action)
		{
			case 'json':
				$lefttime = $shakeend-time();
				if ($lefttime <= 0)
				{
					$lefttime = 0;
					$this->fpms_model->set_tables('accounts', array('shakestatus'=>2), array('accountsid'=>$this->accountsid));
				}
				$shake_result = $this->fpms_model->get_wechatshake($publicid, 14);
				$wechatshake = array(
					'status' => $shakestatus,
					'lefttime' => $lefttime,
					'res' => $shake_result,
				);
				echo json_encode($wechatshake);
				break;
			case 'num':
				$fans = $this->fpms_model->get_tables('fans', FALSE, array('publicid' => $publicid, 'lasttime >' => $accounts[0]['shaketoken']), 'fansid');
				$wechatshake = array(
					'num' => count($fans),
				);
				echo json_encode($wechatshake);
				break;
			case 'start':
				$shakeend = time() + intval($accounts[0]['shaketime']);
				if ($this->fpms_model->set_tables('accounts', array('shakestatus'=>1, 'shakeend'=>$shakeend), array('accountsid'=>$this->accountsid)))
				{
					$wechatshake = array(
						'err' => 0,
					);
				}
				else
				{
					$wechatshake = array(
						'err' => 1,
					);
				}
				echo json_encode($wechatshake);
				break;
			//摇一摇数据保存
			case 'save':
				$post_wins = $this->input->post('wins');
				$shakerank = 1;
				$shakewin = intval($accounts[0]['shakewin']);
				$newwins = array();
				foreach ($post_wins['res'] as $item)
				{
					if ($shakerank <= $shakewin)
					{
						$newwins[] = array(
							'accountsid' => $this->accountsid,
							'fansid' => $item['fansid'],
							'winsrank' => $shakerank,
							'winsdata' => '摇一摇 / '.$item['shakes'].' 次 / '.$item['nickname'],
							'winstime' => $shakeend,
							'winstoken' => mt_rand(100000, 999999),
							'winsstatus' => 1,
						);
					}
					$shakerank++;
				}
				$this->db->insert_batch('wins', $newwins);
				//file_put_contents($shakeend, json_encode($this->input->post('wins')));
				$wechatshake = array(
					'err' => 0,
				);
				echo json_encode($wechatshake);
				break;
			//摇一摇数据清空
			case 'shakeempty':
				$this->fpms_model->set_tables('fans', array('shakes'=>0), array('publicid'=>$publicid));
				$this->fpms_model->set_tables('accounts', array('shakestatus'=>0, 'shaketoken'=>time()), array('accountsid'=>$this->accountsid));
				$wechatshake = array(
					'err' => 0,
				);
				echo json_encode($wechatshake);
				break;
			//摇一摇数据清空
			case 'shakequit':
				$this->fpms_model->set_tables('accounts', array('shakestatus'=>2), array('accountsid'=>$this->accountsid));
				$wechatshake = array(
					'err' => 0,
				);
				echo json_encode($wechatshake);
				break;
			//修改获奖人数
			case 'shakewin':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('shakewin'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			//修改最大摇晃数
			case 'shakemax':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('shakemax'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
				//修改摇晃时间
			case 'shaketime':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('shaketime'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			//摇一摇开关
			case 'shakestatus':
				$status = intval($this->input->post('status'));
				if ($status == 1) { $shakestatus = 0;} else { $shakestatus = 1;}
				$shakeend = time() + intval($accounts[0]['shaketime']);
				if ($this->fpms_model->set_tables('accounts', array('shakestatus'=>$shakestatus, 'shakeend'=>$shakeend), array('accountsid'=>$this->accountsid)))
				{
					$data = array(
						'status' => $shakestatus
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			case 'pc':
				$fans = $this->fpms_model->get_tables('fans', FALSE, array('publicid' => $publicid, 'shakes >' => 0), 'fansid');
				if (count($fans) > 0) { exit('<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>数据未清空</body></html>');}
				if ($shakestatus > 0) { exit('<!DOCTYPE html><html><head><meta charset="utf-8"><title>通知</title></head><body>状态未重置</body></html>');}
				$data = array(
					'shaketime' => $accounts[0]['shaketime'],
					'shakemax' => $accounts[0]['shakemax'],
					'shakewin' => $accounts[0]['shakewin'],
					'shakesurl' => site_url('fpms_admin/shakes'),
				);
				$this->load->view('wechatshake_pc', $data);
				break;
			default:
				$data['accounts'] = $accounts;
				$data['fans'] = $this->fpms_model->get_tables('fans', 'shakes desc', array('publicid'=>$publicid,'lasttime >'=>intval($accounts[0]['shaketoken'])));
				$data['wechatshakeurl'] = site_url('fpms_admin/shakes/pc');
				$data['shakesurl'] = site_url('fpms_admin/shakes');
				$this->load->view('header');
				$this->load->view('shakes', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 红包管理
	*/
	public function redpacks($action = 'html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid), 'publicid,redpackstatus,redpackstartid,redpackendid');
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		switch ($action)
		{
			case 'redpackmessage':
				$redpacksid = intval($this->input->post('redpacksid'));
				$status = intval($this->input->post('status'));
				if ($status == 1) { $redpackmessage = 0;} else { $redpackmessage = 1;}
				if ($this->fpms_model->set_tables('redpacks', array('redpacksstatus'=>$redpackmessage), array('redpacksid'=>$redpacksid)) == TRUE)
				{
					$data = array(
						'status' => $redpackmessage
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			//红包发放查询
			case 'mch_billno':
				$data = array(
					'status' => $this->input->post('mch_billno')
				);
				echo json_encode($data);
				break;
			//修改红包领取开始编号
			case 'redpackstartid':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('redpackstartid'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			//修改红包领取结束编号
			case 'redpackendid':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('redpackendid'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			//红包开关
			case 'redpackstatus':
				$status = intval($this->input->post('status'));
				if ($status == 1) { $redpackstatus = 0;} else { $redpackstatus = 1;}
				if ($this->fpms_model->set_tables('accounts', array('redpackstatus'=>$redpackstatus), array('accountsid'=>$this->accountsid)) == TRUE)
				{
					$data = array(
						'status' => $redpackstatus
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			default:
				$redpacks = $this->db->where('publicid',$publicid)->order_by('redpacksid desc')->limit(300)->get('redpacks')->result_array();
				$data['accounts'] = $accounts;
				$data['redpacks'] = $redpacks;
				$data['redpacksurl'] = site_url('fpms_admin/redpacks');
				$this->load->view('header');
				$this->load->view('redpacks', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 模板管理
	*/
	public function templates($action = 'html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid));
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		switch ($action)
		{
			//模板审核修改
			case 'templatemessage':
				$templatesid = intval($this->input->post('templatesid'));
				$status = intval($this->input->post('status'));
				if ($status == 1) { $templatemessage = 0;} else { $templatemessage = 1;}
				if ($this->fpms_model->set_tables('templates', array('templatesstatus'=>$templatemessage), array('templatesid'=>$templatesid)) == TRUE)
				{
					$data = array(
						'status' => $templatemessage
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			//修改参数
			case 'templateaction':
				$templatesid = intval($this->input->post('templatesid'));
				$action = $this->input->post('action');
				$inputval = $this->input->post('inputval');
				$this->fpms_model->set_tables('templates', array($action=>$inputval), array('templatesid'=>$templatesid));
				$data = array(
					'action' => $action,
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			default:
				$data = array(
					'accounts' => $accounts,
					'templates' => $this->fpms_model->get_tables('templates', 'templatesid desc', array('accountsid'=>$this->accountsid)),
					'templatestype' => array('wechatwall'=>'微信墙', 'wecatshake'=>'摇一摇'),
					'templatesurl' => site_url('fpms_admin/templates'),
				);
				$this->load->view('header');
				$this->load->view('templates', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 中奖管理
	*/
	public function wins($action = 'html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid));
		if (count($accounts) != 1) { return FALSE;}
		$publicid = $accounts[0]['publicid'];
		switch ($action)
		{
			//中奖审核修改
			case 'winmessage':
				$winsid = intval($this->input->post('winsid'));
				$status = intval($this->input->post('status'));
				if ($status == 1) { $winmessage = 0;} else { $winmessage = 1;}
				if ($this->fpms_model->set_tables('wins', array('winsstatus'=>$winmessage), array('winsid'=>$winsid)) == TRUE)
				{
					$data = array(
						'status' => $winmessage
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			//修改历史时间
			case 'wintime':
				$inputval = intval($this->input->post('inputval'));
				$this->fpms_model->set_tables('accounts', array('wintime'=>$inputval), array('accountsid'=>$this->accountsid));
				$data = array(
					'inputval' => $inputval
				);
				echo json_encode($data);
				break;
			//多次获奖开关
			case 'winstatus':
				$status = intval($this->input->post('status'));
				if ($status == 1) { $winstatus = 0;} else { $winstatus = 1;}
				if ($this->fpms_model->set_tables('accounts', array('winstatus'=>$winstatus), array('accountsid'=>$this->accountsid)) == TRUE)
				{
					$data = array(
						'status' => $winstatus
					);
				}
				else
				{
					$data = array(
						'status' => $status
					);
				}
				echo json_encode($data);
				break;
			default:
				$wintime = time() - intval($accounts[0]['wintime']);
				$data = array(
					'accounts' => $accounts,
					'wins' => $this->fpms_model->get_tables('wins', 'winsid asc', array('accountsid'=>$this->accountsid, 'winstime >'=>$wintime)),
					'winsurl' => site_url('fpms_admin/wins'),
				);
				$this->load->view('header');
				$this->load->view('wins', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 微摇信息
	*/
	public function yaotvs()
	{
		$data['yaotvs'] = $this->fpms_model->get_tables('yaotvs', 'yaotvsid desc');
		$this->load->view('header');
		$this->load->view('yaotvs', $data);
		$this->load->view('footer');
	}


	/**
	* 微信信息
	*/
	public function infos()
	{
		$data['infos'] = $this->db->order_by('infosid desc')->limit(300)->get('infos')->result_array();
		$this->load->view('header');
		$this->load->view('infos', $data);
		$this->load->view('footer');
	}


	/**
	* 积分日志
	*/
	public function points()
	{
		$data['points'] = $this->fpms_model->get_tables('points', 'pointsid desc');
		$this->load->view('header');
		$this->load->view('points', $data);
		$this->load->view('footer');
	}


	/**
	* 积分兑换
	*/
	public function exchanges()
	{
		$data['exchanges'] = $this->fpms_model->get_tables('exchanges', 'exchangesid desc');
		$this->load->view('header');
		$this->load->view('exchanges', $data);
		$this->load->view('footer');
	}


	/**
	* 积分管理
	*/
	public function rules($action = 'html')
	{
		switch($action)
		{
			case 'add':
				$data_post = $this->input->post(NULL, TRUE);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('rules', $data_post);
				break;
			case 'edit':
				$data_post = $this->input->post(NULL, TRUE);
				$data_where = array(
						'rulesid' => $data_post['rulesid'],
				);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('rules', $data_post, $data_where);
				break;
		}
		$rulestypesid = $this->fpms_model->get_tables('types', 'typesid desc', array('typestable'=>'rules'));
		foreach($rulestypesid as $item)
		{
			$data['rulestypesid'][$item['typesid']] = $item['typesname'];
		}
		$data['rules'] = $this->fpms_model->get_tables('rules', 'rulesid desc');
		$this->load->view('header');
		$this->load->view('rules', $data);
		$this->load->view('footer');
	}


	/**
	* 商品管理
	*/
	public function goods($action = 'html')
	{
		switch($action)
		{
			case 'add':
				$data_post = $this->input->post(NULL, TRUE);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('goods', $data_post);
				break;
			case 'edit':
				$data_post = $this->input->post(NULL, TRUE);
				$data_where = array(
						'goodsid' => $data_post['goodsid'],
				);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('goods', $data_post, $data_where);
				break;
		}
		$goodstypesid = $this->fpms_model->get_tables('types', 'typesid desc', array('typestable'=>'goods'));
		foreach($goodstypesid as $item)
		{
			$data['goodstypesid'][$item['typesid']] = $item['typesname'];
		}
		$data['goods'] = $this->fpms_model->get_tables('goods', 'goodsid desc');
		$this->load->view('header');
		$this->load->view('goods', $data);
		$this->load->view('footer');
	}


	/**
	* 分类管理
	*/
	public function types($action = 'html')
	{
		switch($action)
		{
			case 'add':
				$data_post = $this->input->post(NULL, TRUE);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('types', $data_post);
				break;
			case 'edit':
				$data_post = $this->input->post(NULL, TRUE);
				$data_where = array(
						'typesid' => $data_post['typesid'],
				);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('types', $data_post, $data_where);
				break;
		}
		$data['typestable'] = array(
				'rules' => '积分类',
				'goods' => '商品类',
		);
		$data['types'] = $this->fpms_model->get_tables('types', 'typesid desc');
		$this->load->view('header');
		$this->load->view('types', $data);
		$this->load->view('footer');
	}


	/**
	* 公众号
	*/
	public function accounts($action = 'html')
	{
		switch($action)
		{
			case 'add':
				$data_post = $this->input->post(NULL, TRUE);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('accounts', $data_post);
				break;
			case 'edit':
				$data_post = $this->input->post(NULL, TRUE);
				$data_where = array(
						'accountsid' => $data_post['accountsid'],
				);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('accounts', $data_post, $data_where);
				break;
		}
		$data['accounts'] = $this->fpms_model->get_tables('accounts', 'accountsid desc');
		$this->load->view('header');
		$this->load->view('accounts', $data);
		$this->load->view('footer');
	}


	/**
	* 关注者
	*/
	public function fans($action = 'html')
	{
		$accounts = $this->fpms_model->get_tables('accounts', FALSE, array('accountsid'=>$this->accountsid));
		if (count($accounts) != 1) { return FALSE;}
		switch ($action)
		{
			case 'refreshfans':
				$inputval = $this->input->post('inputval');
				$this->load->library('wechat', $accounts[0]);
				$updatefans = $this->wechat->getUserInfo($inputval);
				$data_fans = elements(array('nickname','sex','language','city','province','country','headimgurl','subscribe_time'), $updatefans);
				$this->db->update('fans', $data_fans, array('openid'=>$inputval));
				$wechatuser = array(
					'err' => $updatefans['nickname'],
				);
				echo json_encode($wechatuser);
				break;
			default:
				$publicid = $accounts[0]['publicid'];
				$walltime = intval($accounts[0]['walltime']);
				$data = array(
					'fans' => $this->fpms_model->get_tables('fans', 'lasttime desc', array('publicid'=>$publicid, 'lasttime >'=>(time()-86400*2))),
					'fansurl' => site_url('fpms_admin/fans'),
				);
				$this->load->view('header');
				$this->load->view('fans', $data);
				$this->load->view('footer');
		}
	}


	/**
	* 管理员
	*/
	public function admins($action = 'html')
	{
		switch($action)
		{
			case 'add':
				$data_post = $this->input->post(NULL, TRUE);
				unset($data_post['submit']);
				$this->fpms_model->set_tables('admins', $data_post);
				break;
			case 'edit':
				$data_post = $this->input->post(NULL, TRUE);
				$data_where = array(
						'adminsid' => $data_post['adminsid'],
				);
				if ($data_post['adminspass'] != '')
				{
					$data_post['adminspass'] = $this->fpms_library->get_pass($data_post['adminspass']);
				}
				else
				{
					unset($data_post['adminspass']);
				}
				unset($data_post['submit']);
				$this->fpms_model->set_tables('admins', $data_post, $data_where);
				break;
		}
		$data['adminslevel'] = array(
				'1' => '操作员',
				'9' => '管理员',
		);
		$data['admins'] = $this->fpms_model->get_tables('admins', 'adminsid desc');
		$this->load->view('header');
		$this->load->view('admins', $data);
		$this->load->view('footer');
	}


	/**
	* 退出管理后台
	*/
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('fpms');
	}


}

/* End of file fpms_admin.php */
/* Location: ./application/controllers/fpms_admin.php */