<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_data extends CI_Model {

	public function get_mail($id=null){
		$id_user = $this->session->userdata('id');

		return $this->db
			->where('id_mail', $id)
			->where('(dest = '.$id_user.' OR origin = '.$id_user.')')
			->get('mail')
			->row();
	}

	public function get_mail_full($id=null){
		$id_user = $this->session->userdata('id');

		$data = $this->db
			->where('id_mail', $id)
			->where('(dest = '.$id_user.' OR origin = '.$id_user.')')
			->get('mail')
			->row();

		if(!empty($data)){
			$data->dest = $this->db
				->where(array('id_user' => $data->dest))
				->get('user_mail')
				->row();

			$data->origin = $this->db
				->where(array('id_user' => $data->origin))
				->get('user_mail')
				->row();
		}

		return $data;
	}

	public function set_read($id){
		$this->db
			->where('id_mail', $id)
			->update('mail', array('readed' => '1'));

		return $this->db->affected_rows();
	}

	public function isForMe($id=null){
		$id_user = $this->session->userdata('id');

		$total = $this->db
			->where('id_mail', $id)
			->where('dest', $id_user)
			->from('mail')
			->count_all_results();

		if($total >= 1){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function get_user($id){
		return $this->db
			->where('id_user', $id)
			->where('role', 'user')
			->get('user_mail')
			->row();
	}
}