<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends CI_Controller{

	function __construct(){
		parent::__construct();

		if($this->session->userdata('role') != 'user'){
			redirect('login');
		}
	}

	public function index(){
		$data = array(
			'menu' => 1,
			'data' => base_url('mail/data/inbox')
		);
		$this->load->view('home', $data);
	}

	public function sent(){
		$data = array(
			'menu' => 2,
			'data' => base_url('mail/data/sent')
		);
		$this->load->view('home', $data);
	}

	public function trash($id=null){
		if(is_null($id)){
			$data = array(
				'menu' => 3,
				'data' => base_url('mail/data/trash')
			);
			$this->load->view('home', $data);
		}
		else{
			$isForMe = $this->m_data->isForMe($id);

			if($isForMe){
				$where = array('id_mail' => $id);
				$data = array('trash' => '1');
				$this->crud->update($where, $data, 'mail');
				$this->session->set_flashdata('success', 'Pesan berhasil dimasukkan ke keranjang sampah');
				redirect('mail/view/'.$id);
			}
			else{
				show_404();
			}
		}
	}

	public function compose(){
		$this->load->library('form_validation');

		if($this->form_validation->run('compose') == FALSE){
			$this->load->view('compose');
		}
		else{
			$data = array(
				'title' => $this->input->post('title'),
				'content' => $this->input->post('content'),
				'origin' => $this->session->userdata('id'),
				'dest' => $this->config->item('admin_id'),
			);
			$this->crud->insert($data, 'mail');
			$this->session->set_flashdata('success', 'Pesan berhasil dikirim');
			redirect('mail');
		}
	}

	public function view($id=null){
		$mail = $this->m_data->get_mail_full($id);

		if(!empty($mail)){

			$id_user = $this->session->userdata('id');
			if($mail->trash == 1){
				$menu = 3;
			}
			elseif($id_user == $mail->origin->id_user){
				$menu = 2;
			}
			elseif($id_user == $mail->dest->id_user){
				$menu = 1;

				if($mail->readed == 0){
					$this->m_data->set_read($id);
				}
			}
			else{
				$menu = 0;
			}

			$data['mail'] = $mail;
			$data['menu'] = $menu;
			$data['prefix'] = '';
			$this->load->view('content', $data);
		}
		else{
			show_404();
		}
	}

	public function content($id=null){
		$ref = $this->input->server('HTTP_REFERER');
		if($ref == base_url('mail/view/'.$id)){
			$data['mail'] = $this->m_data->get_mail($id);

			if(!empty($data['mail'])){
				$this->load->view('content_frame', $data);
			}
			else{
				show_404();
			}
		}
		else{
			// show_404();
		}
	}

	public function data($type = "inbox"){
		if($this->input->is_ajax_request()){

			$this->load->library('ssp');

			$table = 'mail';
			$primaryKey = 'id_mail';
			$columns = array(
				array(
					'db' => 'id_mail',
					'dt' => 0,
					'formatter' => function($d, $row){
						$id = $this->session->userdata('id');
						$where = array(
							'id_user' => ($row['origin'] == $this->session->userdata('id') ? $row['dest'] : $row['origin'])
						);

						$data = $this->crud->get_where($where, 'user_mail')->row_array();
						return 
						'
						<a href="'.base_url('mail/view/'.$d).'">
							<div class="w-100">
								<p class="m-0">
									<b>'.(isset($data['nama']) ? $data['nama'] : 'null').'</b>
									'.($row['readed'] == 0 && $row['dest'] == $id ? '<span class="badge badge-primary">Baru</span>' : '').'
									<span class="float-right badge">'.mail_date($row['date']).'</span>
								</p>
								<span class="text-secondary">'.$row['title'].'</span>
							</div>
						</a>
						';
					}
				),
				array('db' => 'title', 'dt' => null),
				array('db' => 'content', 'dt' => null),
				array('db' => 'date', 'dt' => null),
				array('db' => 'origin', 'dt' => null),
				array('db' => 'dest', 'dt' => null),
				array('db' => 'readed', 'dt' => null),
			);

			$id = $this->session->userdata('id');
			$id = $this->db->escape($id);

			if($type == "inbox"){
				$where = "dest = $id AND origin <> $id AND trash = '0'";
			}
			elseif($type == "sent"){
				$where = "origin = $id AND dest <> $id";
			}
			elseif($type == "trash"){
				$where = "dest = $id AND origin <> $id AND trash = '1'";
			}
			else{
				show_404();
				exit();
			}

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
			);

			echo json_encode(
				SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where)
			);
		}
		else{
			show_404();
		}
	}

	// MANIPULATE

	public function restore($id=null){
		$isForMe = $this->m_data->isForMe($id);

		if($isForMe){
			$where = array('id_mail' => $id);
			$data = array('trash' => '0');
			$this->crud->update($where, $data, 'mail');
			$this->session->set_flashdata('success', 'Pesan telah direstore kembali');
			redirect('mail/view/'.$id);
		}
		else{
			show_404();
		}
	}
}