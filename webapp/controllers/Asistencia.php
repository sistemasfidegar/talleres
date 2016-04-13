<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Asistencia extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->helper('my_date_helper');
		$this->load->model('m_asistencia');
	}
	
	public function index() {
		if($this->session->userdata('CRUD_AUTH')) {
			$datos['title'] = 'Asistencia';
			$hoy = new DateTime(fecha_actual());
			
			// Taller Activo
			$aux = $this->m_asistencia->getCicloActivo();
			$inicio = isset ($aux[0]['inicio']) ? new DateTime ($aux[0]['inicio']) : null;
			$fin = isset ($aux[0]['fin']) ? new DateTime ($aux[0]['fin']) : null;
			
			$this->load->view('layout/header', $datos, false );
			$this->load->view('admin/nav', false, false);
			
			if (!is_null($inicio) && ! is_null($fin)) {
				if ($hoy >= $inicio && $hoy <= $fin) {
					
					$this->load->view('asistencia/asistencia_beneficiario', $datos, false );
				
				} else {
					$datos ['disponible'] = 1;
					$this->load->view('asistencia/asistencia_beneficiario', $datos, false );
				}
			} else {
				$datos ['disponible'] = 1;
				$this->load->view('asistencia/asistencia_beneficiario', $datos, false );
			}
			
			$this->load->view('layout/footer', false, false );
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	function registroAsistencia() {
		if($this->session->userdata('CRUD_AUTH')) {
			$usuario = $this->session->userdata('CRUD_AUTH');
			$matricula = $this->input->post('matricula');
			$aux = $this->m_asistencia->getMatricula($matricula);
		
			$aux = isset($aux[0]['matricula']) ? $aux[0]['matricula'] : null;
		
			if (!is_null($aux)) {
				$taller = $this->m_asistencia->getTaller(fecha_actual());
				$idtaller = isset($taller[0]['id_taller']) ? $taller[0]['id_taller'] : null;
				
				if (!is_null($idtaller)) {
					$registro = $this->m_asistencia->registroDuplicado($idtaller, $aux);
					$registro = isset($registro[0]['matricula']) ? $registro[0]['matricula'] : null;
					
					if (!is_null($registro)){
						echo 'registrado';
					} else {
						$asistencia = $this->m_asistencia->insertaAsistencia($idtaller, $aux, $usuario['id_usuario']);
						echo $taller[0]['taller'];
					}
				} else {
					echo 'sintaller';
				}
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url('admin'));
		}
		
	}	
}