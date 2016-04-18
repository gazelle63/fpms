<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 粉丝积分管理系统 Fans Points Management System
 * 功能：积分模块
 */
class Fpms_points extends CI_Controller {
	private $fans;

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('openid') == FALSE)
		{
			$this->session->set_userdata('message', '获取用户信息失败，请稍后重新访问。');
			redirect('fpms/message');
		}
		else
		{
			$this->fans = $this->session->all_userdata();
		}
	}


	/**
	* 我的积分首页
	*/
	public function index()
	{
		$data['fans'] = $this->fans;
		$this->load->view('header');
		$this->load->view('fpms_points', $data);
		$this->load->view('footer');
	}


	/**
	* 短信身份认证
	*/
	public function idents($action='reg')
	{
		if ($action == 'captcha')
		{
			$cellphone = $this->input->post('cellphone', TRUE);
			if (is_numeric($cellphone) && $cellphone > 10000000000 && $cellphone < 20000000000)
			{
				$idents = $this->fpms_model->get_tables('idents', 'identsid desc', array('cellphone'=>$cellphone, 'lasttime >'=>time()));
				if (count($idents) > 0)
				{
					
					$captcha_msg = '验证过程进行中，请不要重复点击。（请'.(floor(($idents[0]['lasttime'] - time())/60)+1).'分钟后再试）';
				}
				else
				{
					$nonce = $this->fpms_library->get_nonce(4);
					$encode_nonce = $this->fpms_library->get_pass($nonce);
					if ($this->fpms_model->set_tables('idents', array('cellphone'=>$cellphone, 'captcha'=>$encode_nonce, 'lasttime'=>(time()+300))))
					{
						/*
						 * 发送手机验证码
						if ($this->fpms_library->send_sms($cellphone, $nonce, '采购中心') > 0)
						{
							$captcha_msg = '验证码已发送(五分钟内有效)：'.$nonce;
						}
						else
						{
							$captcha_msg = '验证码发送失败，请确认你的手机号码无误。';
						}
						*/
						$captcha_msg = '验证码已发送(五分钟内有效)：'.$nonce;
					}
					else
					{
						$captcha_msg = '好像出了点问题，请再次点击发送验证码';
					}
				}
			}
			else
			{
				$captcha_msg = '电话号码错误，无法发送验证码';
			}
			exit($captcha_msg);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_message('required', '%s未填');
		$this->form_validation->set_message('numeric', '%s应填数字');
		$this->form_validation->set_message('exact_length', '%s长度不正确');
		$this->form_validation->set_rules('name', '真实姓名', 'required|trim');
		$this->form_validation->set_rules('cellphone', '电话号码', 'required|trim|numeric|exact_length[11]');
		$this->form_validation->set_rules('captcha', '短信验证码', 'required|trim');
		if ($this->form_validation->run() == FALSE)
		{
			$data['msg'] = '';
			$data['fans'] = $this->fans;
			$this->load->view('header');
			$this->load->view('fpms_idents', $data);
			$this->load->view('footer');
		}
		else
		{
			$post_data = $this->input->post(NULL, TRUE);
			$idents = $this->fpms_model->get_tables('idents', 'identsid desc', array('cellphone'=>$post_data['cellphone'],'lasttime >'=>time()));
			if ((count($idents) != 1) || ($idents[0]['captcha'] != $this->fpms_library->get_pass($post_data['captcha'])))
			{
				$data['msg'] = '您的短信验证码不正确或者已过期';
				$data['fans'] = $this->fans;
				$this->load->view('header');
				$this->load->view('fpms_idents', $data);
				$this->load->view('footer');
			}
			else
			{
				//验证成功的数据库事务处理开始
				$this->db->trans_start();
				$levels = $this->fpms_model->set_tables('fans', array('name'=>$post_data['name'], 'cellphone'=>$post_data['cellphone'], 'level'=>2, 'lasttime'=>time()), array('fansid'=>$post_data['fansid']));
				$points = $this->fpms_model->set_points(1, $post_data['fansid'], 2, 1000, time());
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE)
				{
					$data['msg'] = '数据更新失败，请再提交一次。';
					$data['fans'] = $this->fans;
					$this->load->view('header');
					$this->load->view('fpms_idents', $data);
					$this->load->view('footer');
				}
				else
				{
					$this->session->set_userdata('level', 2);
					redirect('fpms_points');
				}
			}
		}
	}


	/**
	 * 微信签到事件
	 */
	public function sign()
	{
		$sign_fans = $this->fans;
		//判断今日签到是否重复
		$date_now = time();
		$date_today = $date_now - date('H', $date_now)*60*60 - date('i', $date_now)*60;
		$data_sign = $this->fpms_model->get_tables('points', 'pointsid desc', array('pointstime >'=>$date_today,'fansid'=>$sign_fans['fansid'],'rulesid'=>3), 'pointsid');
		if (count($data_sign) > 0)
		{
			$this->session->set_userdata('message', $sign_fans['nickname'].'，今天已签到，明天继续来呀。');
			redirect('fpms/message');
		}
		//给签到事件增加积分
		$points = $this->fpms_model->set_points(1, $sign_fans['fansid'], 3, 20, time());
		if ($points == FALSE)
		{
			$this->session->set_userdata('message', $sign_fans['nickname'].'，签到未成功，再努力一次吧。');
			redirect('fpms/message');
		}
		else
		{
			$this->session->set_userdata('message', '恭喜 '.$sign_fans['nickname'].' 签到成功，获得20积分。');
			redirect('fpms/message');
		}
	}


}

/* End of file fpms_points.php */
/* Location: ./application/controllers/fpms_points.php */