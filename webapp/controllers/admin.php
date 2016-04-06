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
			$crudAuth = $this->session->userdata('CRUD_AUTH');
			header("Location: " . base_url('admin/dashboard'));
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
			$this->load->view('admin/dashboard', false, false);
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
	
	public function logout(){
		$this->session->sess_destroy();
		header("Location: " . base_url('admin'));
	}
}