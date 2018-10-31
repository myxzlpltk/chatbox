<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index(){
		$this->load->library('form_validation');
		if($this->form_validation->run('login') == FALSE){
			$this->load->view('login');
		}
		else{
			$where = array('username' => $this->input->post('username'));
			$user = $this->crud->get_where($where, 'user_mail')->row();
			if(!empty($user)){
				if(password_verify($this->input->post('password'), $user->password)){
					if(in_array($user->role, array('user', 'admin'))){
						$data = array(
							'id' => $user->id_user,
							'nama' => $user->nama,
							'username' => $user->username,
							'role' => $user->role,
							'valid' => true
						);
						$this->session->set_userdata($data);
					}

					if($user->role == 'user'){
						redirect('mail');
					}
					elseif($user->role == 'admin'){
						redirect('admin');
					}
					else{
						$this->session->set_flashdata('error', 'Tipe akun tidak dikenali');
						redirect('login');
					}
				}
				else{
					$this->session->set_flashdata('error', 'Kata sandi salah!');
					redirect('login');
				}
			}
			else{
				$this->session->set_flashdata('error', 'Akun tidak ada!');
				redirect('login');
			}
		}
	}
}
