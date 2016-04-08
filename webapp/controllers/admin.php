<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('my_date_helper');
		$this->load->model('m_admin');
		$this->load->model('m_registro');
	}
	
	public function index() {
		if ($this->m_admin->login()) {
			header("Location: " . base_url('asistencia'));
		} else {
			$datos['title'] = 'Inicio de Sesi&oacute;n';
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/login', false, false);
			$this->load->view('layout/footer', false, false);
		}
	}
	
	public function dashboard(){
		if($this->session->userdata('CRUD_AUTH')) {
			$datos['title'] = 'Administrador';
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/nav', false, false);
			$this->load->view('asistencia/asistencia_beneficiario', false, false);
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function nuevo() {
		if($this->session->userdata('CRUD_AUTH')) {
			$datos['title'] = 'Agregar Usuario';
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/nav', false, false);
			$this->load->view('layout/aviso', false, false);
			$datos['sedes'] = $this->m_registro->getPlantelesActivos();
			$this->load->view('admin/nuevo', $datos, false);
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function listar() {
		if($this->session->userdata('CRUD_AUTH')) {
			$datos['title'] = 'Listar Usuarios';
			$datos['usuarios'] = $this->m_admin->builtUsuarios();
			$datos['sedes'] = $this->m_registro->getPlantelesActivos();
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/nav', false, false);
			$this->load->view('layout/aviso', false, false);
			$this->load->view('admin/list', $datos, false);
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function create() {
		if($this->session->userdata('CRUD_AUTH')) {
			if($this->m_admin->save($this->input->post())) {
				echo 'ok';
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function delete() {
		if($this->session->userdata('CRUD_AUTH')) {
			$post = $this->input->post();
		
			if(!empty($post["usuarioId"]) && is_numeric($post["usuarioId"])) {
				if ($this->m_admin->delete($post)) {
					echo 'ok';
				} else {
					echo 'bad';
				}
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function edit() {
		if($this->session->userdata('CRUD_AUTH')) {
			if ($this->m_admin->edit($this->input->post())) {
				echo 'ok';
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function logout(){
		if($this->session->userdata('CRUD_AUTH')) {
			$this->session->sess_destroy();
		}
		
		header("Location: " . base_url('admin'));
	}
	
	public function profile() {
		if($this->session->userdata('CRUD_AUTH')) {
			$usuario = $this->session->userdata('CRUD_AUTH');
			$datos['title'] = 'Perfil';
			$datos['sedes'] = $this->m_registro->getPlantelesActivos();
			$datos['plantel'] = $this->m_registro->getPlantelById($usuario['id_plantel']);
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/nav', false, false);
			$this->load->view('layout/aviso', false, false);
			$this->load->view('admin/edit', $datos, false);
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function excel() {
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=usuarios" . date("YmdHis") . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		echo utf8_decode($_POST['datos_a_enviar']);
	}
}