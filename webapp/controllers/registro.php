<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Registro extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->helper('my_date_helper');
		$this->load->model('m_registro');
	}
	
	public function index() {
		$datos['title'] = 'Registro';
		$hoy = new DateTime(fecha_actual());
		
		// Taller Activo
		$aux = $this->m_registro->getTallerActivo();
		$inicio = isset ($aux[0]['inicio']) ? new DateTime ($aux[0]['inicio']) : null;
		$fin = isset ($aux[0]['fin']) ? new DateTime ($aux[0]['fin']) : null;
		
		$this->load->view('layout/header', $datos, false );
		
		if (!is_null($inicio) && ! is_null ($fin)) {
			if ($hoy >= $inicio && $hoy <= $fin) {
				$this->load->view('registro/busca_beneficiario', $datos, false );
			} else {
				$datos ['disponible'] = 1;
				$this->load->view('registro/busca_beneficiario', $datos, false );
			}
		} else {
			$datos ['disponible'] = 1;
			$this->load->view('registro/busca_beneficiario', $datos, false );
		}
		
		$this->load->view ('layout/footer', false, false );
	}
	
	function getBeneficiario() {
		$matricula = $this->input->post('matricula');
		$aux = $this->m_activacion->getMatricula($matricula);
	
		$aux = isset($aux[0]['matricula_asignada']) ? $aux[0]['matricula_asignada'] : null;
	
		if (! is_null($aux)) {
		}
	}
}