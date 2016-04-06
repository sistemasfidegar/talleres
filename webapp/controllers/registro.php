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
		
		if (!is_null($inicio) && ! is_null($fin)) {
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
		
		$this->load->view('layout/footer', false, false );
	}
	
	function getBeneficiario() {
		$matricula = $this->input->post('matricula');
		$aux = $this->m_registro->getMatricula($matricula);
	
		$aux = isset($aux[0]['matricula_asignada']) ? $aux[0]['matricula_asignada'] : null;
	
		if (!is_null($aux)) {
			$registro = $this->m_registro->checkRegistroTaller($aux);
			
			if(empty($registro)) {
				echo $aux;
			} else {
				echo 'registro';
			}
		} else {
			echo 'bad';
		}
	}
	
	function getBeneficiarioUnam(){
		$matricula =  $this->input->post('matricula_escuela');
		$aux = $this->m_registro->getMatriculaUnam($matricula);
	
		$aux = isset($aux[0]['matricula_asignada']) ? $aux[0]['matricula_asignada'] : null;
	
		if (!is_null($aux)) {
			$registro = $this->m_registro->checkRegistroTaller($aux);
			
			if(empty($registro)) {
				echo $aux;
			} else {
				echo 'registro';
			}
		} else {
			echo 'bad';
		}
	}
	
	function nuevo($matricula) {
		$datos['title'] = 'Registro Taller';
		$disponibilidad = $this->m_registro->getDisponibilidad();
		
		$this->load->view('layout/header', $datos, false);
		$this->load->view('layout/aviso', false, false);
		
		if(!empty($disponibilidad)) {
			$talleres = $this->m_registro->getTalleres();
			
			if(!empty($talleres)) {
				$beneficiario = $this->m_registro->getDatos($matricula);
				
				if(!empty($beneficiario)) {
					$datos['matricula'] = $matricula;
					$datos['beneficiario'] = $beneficiario[0];
					$datos['sedes'] = $disponibilidad;
					$datos['talleres'] = $talleres;
					$this->load->view('registro/nuevo', $datos, false);
				} else {
					//expediente con inconsistencias
					$datos['nodisponile'] = 3;
					$this->load->view('activacion/activacion', $datos, false);
				}
			} else {
				//sin talleres disponibles
				$datos['nodisponile'] = 2;
				$this->load->view('activacion/activacion', $datos, false);
			}
		} else {
			//sin sedes disponibles
			$datos['nodisponile'] = 1;
			$this->load->view('activacion/activacion', $datos, false);
		}
		
		$this->load->view('layout/footer', false, false);
	}
	
	function guardar() {
		//verificamos disponibilidad del plantel elegido
		$disponibilidad = $this->m_registro->getDisponibilidadByPlantel($this->input->post('sede'));
		
		if(!empty($disponibilidad)) {
			//tratamos de realizar la insercion de datos
			if($this->m_registro->create($this->input->post(), $disponibilidad[0]['total_asistentes'])) {
				echo 'ok';
			} else {
				echo 'bad';
			}
		} else {
			echo 'nodisponible';
		}
	}
}