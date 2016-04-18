<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 粉丝积分管理系统 Fans Points Management System
 * 功能：商城模块
 */
class Fpms_goods extends CI_Controller {
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
	* 商城首页
	*/
	public function index()
	{
		$data['fans'] = $this->fans;
		$data['goods'] = $this->fpms_model->get_tables('goods', 'goodsid desc');
		$this->load->view('header');
		$this->load->view('fpms_goods', $data);
		$this->load->view('footer');
	}


	/**
	 * 积分兑换
	 * @param int $goodsid 商品编号
	 */
	public function exchanges($goodsid)
	{
		$data_fans = $this->fans;
		//更新关注者积分信息
		$data_fans['points'] = $this->fpms_model->get_points($data_fans['fansid']);
		//获得商品信息
		$data_goods = $this->fpms_model->get_goods(intval($goodsid));
		if ($data_goods == FALSE)
		{
			$this->session->set_userdata('message', $data_fans['nickname'].'，你选择的商品暂时无效。');
			redirect('fpms/message');
		}
		//判断商品数量是否可以购买
		if ($data_goods['goodsamount'] < 1)
		{
			$this->session->set_userdata('message', $data_fans['nickname'].'，你选择的【'.$data_goods['goodsname'].'】已兑换完，下回早点来呀。');
			redirect('fpms/message');
		}
		//判断积分是否足够兑换
		$goods_points = $data_goods['goodspoints'] * $data_goods['goodsdiscount'] / 100;
		$goods_balance = $goods_points - $data_fans['points'];
		if ($goods_balance > 0)
		{
			$this->session->set_userdata('message', $data_fans['nickname'].'，你的积分不够呀，兑换【'.$data_goods['goodsname'].'】还差 '.$goods_balance.' 点。');
			redirect('fpms/message');
		}
		//开始积分兑换的事务处理
		$this->db->trans_start();
		$goods_time = time();
		//扣取商品积分日志
		$this->fpms_model->set_points(1, $data_fans['fansid'], 2, -$goods_points, $goods_time);
		//添加商品兑换记录
		$data_exchanges = array(
				'fansid' => $data_fans['fansid'],
				'goodsid' => $data_goods['goodsid'],
				'goodspoints' => $goods_points,
				'goodscash' => $data_goods['goodscash'],
				'exchangestoken' => 0,
				'exchangestime' => $goods_time,
				'exchangesstatus' => 0,
		);
		$this->fpms_model->set_tables('exchanges', $data_exchanges);
		//改写商品数量
		$this->fpms_model->set_tables('goods', array('goodsamount'=>$data_goods['goodsamount']-1), array('goodsid'=>$data_goods['goodsid']));
		$this->db->trans_complete();
		//结束积分兑换的事务处理
			if ( $this->db->trans_status() === FALSE)
			{
				$this->session->set_userdata('message', $data_fans['nickname'].'，积分兑换失败，再努力一次吧。');
				redirect('fpms/message');
			}
			else 
			{
				$this->session->set_userdata('message', '恭喜【'.$data_fans['nickname'].'】，以 '.$goods_points.' 积分兑换【'.$data_goods['goodsname'].'】成功。');
				redirect('fpms/message');
			}
	}


}

/* End of file fpms_goods.php */
/* Location: ./application/controllers/fpms_goods.php */