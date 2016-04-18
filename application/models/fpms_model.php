<?php
class Fpms_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * 获取通用信息
	 * @param string $table 相关表
	 * @param string $orderby 排序条件
	 * @param array $where 过滤条件
	 * @param string $select 选择字段
	 * @return array 相关结果数组
	 */
	public function get_tables($table, $orderby = FALSE, $where = FALSE, $select = FALSE)
	{
		if ($select != FALSE)
		{
			$this->db->select($select);
		}
		if ($where != FALSE)
		{
			$this->db->where($where);
		}
		if ($orderby != FALSE)
		{
			$this->db->order_by($orderby);
		}
		$query = $this->db->get($table);
		return $query->result_array();
	}


	/**
	 * 获取关注者信息
	 * @param string $publicid 公众号原始账号
	 * @param string $openid 关注者原始账号
	 * @return array 关注者信息
	 */
	public function get_fans($publicid, $openid)
	{
		$fans_openid = $this->session->userdata('openid');
		if (($fans_openid != FALSE) && ($fans_openid == $openid))
		{
			return $this->session->all_userdata();
		}
		else
		{
			$query = $this->db->get_where('fans', array('openid'=>$openid));
			if($query->num_rows() == 0)
			{
				$accounts = $this->get_tables('accounts', FALSE, array('publicid'=>$publicid));
				if (count($accounts) != 1) { return FALSE;}
				$this->load->library('wechat', $accounts[0]);
				//$this->fpms_library->update_access_token($accounts[0]['cjyun'], $accounts[0]['appid']);
				$fans = $this->wechat->getUserInfo($openid);
				if (element('errcode', $fans) == FALSE)
				{
					//添加关注者信息，初始积分10
					$data_fans = elements(array('openid','nickname','sex','language','city','province','country','headimgurl','subscribe_time'), $fans);
					$data_fans['publicid'] = $publicid;
					$data_fans['points'] = 10;
					$data_fans['level'] = 1;
					$data_fans['lasttime'] = time();
					//开始添加关注者的事务处理
					$this->db->trans_start();
					$this->db->insert('fans', $data_fans);
					$data_fans['fansid'] = $this->db->insert_id('fans');
					//注册增加10积分
					$data_points = array(
							'adminsid' => 1,
							'fansid' => $data_fans['fansid'],
							'rulesid' => 1,
							'points' => $data_fans['points'],
							'pointstime' => $data_fans['lasttime'],
					);
					$this->db->insert('points', $data_points);
					$this->db->trans_complete();
					//结束添加关注者的事务处理
					if ( $this->db->trans_status() === FALSE)
					{
						return FALSE;
					}
					else
					{
						$this->session->set_userdata($data_fans);
						return $data_fans;
					}
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				$result = $query->row_array();
				//更新关注者积分信息
				//$result['points'] = $this->get_points($result['fansid']);
				$this->session->set_userdata($result);
				return $result;
			}
		}
	}


	/**
	 * 获取微信墙信息
	 * @param int $fansid 关注者编号
	 * @return int 最新积分
	 */
	public function get_wechatwall($publicid, $lastid, $walltime, $infosstatus, $limit)
	{
		$lastid = intval($lastid);
		$this->db->select('infosid, infomation, infostype, infostime, infosstatus, nickname, headimgurl');
		$this->db->where('infos.publicid', $publicid);
		if ($infosstatus < 2)
		{
			$this->db->where('infosstatus', $infosstatus);
		}
		$this->db->where('infosid >', $lastid);
		if ($lastid == 0)
		{
			$this->db->where('infostime >', time()-$walltime);
		}
		$this->db->order_by('infosid', 'asc');
		if ($limit > 0)
		{
			$this->db->limit($limit);
		}
		$this->db->from('infos');
		$this->db->join('fans', 'infos.openid = fans.openid', 'left');
		$query = $this->db->get();
		$num = $query->num_rows();
		$data = array(
				'wall_num' => $num,
				'wall_msg' => array(),
		);
		if ($num > 0)
		{
			$data['wall_msg'] = $query->result_array();
		}
		return $data;
	}


	/**
	 * 获取摇一摇信息
	 * @param int $publicid 关注者编号
	 * @return int 最新积分
	 */
	public function get_wechatshake($publicid, $limit)
	{
		$this->db->select('fansid, nickname, headimgurl, shakes');
		$this->db->where('publicid', $publicid);
		$this->db->order_by('shakes', 'desc');
		$this->db->from('fans');
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result_array();
	}


	/**
	 * 获取更新积分信息
	 * @param int $fansid 关注者编号
	 * @return int 最新积分
	 */
	public function get_points($fansid)
	{
		$this->db->select_sum('points');
		$this->db->where('fansid', $fansid);
		$query = $this->db->get('points');
		$new_points = $query->row()->points;
		$this->db->update('fans', array('points'=>$new_points, 'lasttime'=>time()), array('fansid'=>$fansid));
		return $new_points;
	}


	/**
	 * 获取商品信息
	 * @param int $goodsid 商品编号
	 * @return array 商品信息
	 */
	public function get_goods($goodsid)
	{
		$this->db->where('goodsid', $goodsid);
		$query = $this->db->get('goods');
		if ($query->num_rows() == 1)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * 获得微摇中奖序列号
	 * @return number 最后的原始中奖序列号
	 */
	public function get_yaoid($accountsid)
	{
		$this->db->select('yaoid');
		$this->db->order_by('yaotvsid desc');
		$this->db->limit(1);
		$query = $this->db->get('yaotvs');
		if ($query->num_rows() == 1)
		{
			return $query->row()->yaoid;
		}
		else
		{
			return 0;
		}
	}


	/**
	 * 添加编辑通用信息
	 * @param string $table 相关表
	 * @param array $data 相关信息
	 * @param array $where 过滤条件
	 * @return boolean 是否添加成功
	 */
	public function set_tables($table, $data, $where = FALSE)
	{
		if ($where == FALSE)
		{
			$this->db->insert($table, $data);
		}
		else
		{
			$this->db->update($table, $data, $where);
		}
		if ($this->db->affected_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * 添加积分事件日志
	 * @param number $adminsid 管理员账号ID
	 * @param number $fansid 关注者账号ID
	 * @param number $rulesid 规则账号ID
	 * @param number $points 积分变化值
	 * @param number $pointstime 操作时间
	 * @return number 最新积分，失败返回否
	 */
	public function set_points($adminsid, $fansid, $rulesid, $points, $pointstime)
	{
		$data = array(
				'adminsid' => intval($adminsid),
				'fansid' => intval($fansid),
				'rulesid' => intval($rulesid),
				'points' => intval($points),
				'pointstime' => intval($pointstime),
		);
		$this->db->insert('points', $data);
		if ($this->db->affected_rows() == 1)
		{
			$new_points = $this->get_points(intval($fansid));
			$this->session->set_userdata('points', $new_points);
			return $new_points;
		}
		else
		{
			return FALSE;
		}
	}


}

/* End of file fpms_model.php */
/* Location: ./application/controllers/fpms_model.php */