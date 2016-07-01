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
			if($this->session->userdata('CRUD_AUTH')['perfil'] == 'Programador' || $this->session->userdata('CRUD_AUTH')['perfil'] == 'Administrador') {
				$datos['title'] = 'Agregar Usuario';
				$this->load->view('layout/header', $datos, false);
				$this->load->view('admin/nav', false, false);
				$datos['sedes'] = $this->m_registro->getPlantelesActivos();
				$this->load->view('admin/nuevo', $datos, false);
				$this->load->view('layout/footer', false, false);
			} else {
				header("Location: " . base_url('asistencia'));
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function listar() {
		if($this->session->userdata('CRUD_AUTH')) {
			if($this->session->userdata('CRUD_AUTH')['perfil'] == 'Programador' || $this->session->userdata('CRUD_AUTH')['perfil'] == 'Administrador') {
				$datos['title'] = 'Listar Usuarios';
				$datos['usuarios'] = $this->m_admin->builtUsuarios();
				$datos['sedes'] = $this->m_registro->getPlantelesActivos();
				$this->load->view('layout/header', $datos, false);
				$this->load->view('admin/nav', false, false);
				$this->load->view('admin/list', $datos, false);
				$this->load->view('layout/footer', false, false);
			} else {
				header("Location: " . base_url('asistencia'));
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function create() {
		if($this->session->userdata('CRUD_AUTH')) {
			if($this->session->userdata('CRUD_AUTH')['perfil'] == 'Programador' || $this->session->userdata('CRUD_AUTH')['perfil'] == 'Administrador') {
				//verificamos el nombre de usuario
				$usuarioInstance = $this->m_admin->checkUser($this->input->post('usuario'));
				
				if(empty($usuarioInstance)) {
					if($this->m_admin->save($this->input->post())) {
						echo 'ok';
					} else {
						echo 'bad';
					}
				} else {
					echo 'usuario';
				}
			} else {
				header("Location: " . base_url('asistencia'));
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function delete() {
		if($this->session->userdata('CRUD_AUTH')) {
			if($this->session->userdata('CRUD_AUTH')['perfil'] == 'Programador' || $this->session->userdata('CRUD_AUTH')['perfil'] == 'Administrador') {
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
				header("Location: " . base_url('asistencia'));
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
	
	public function editPass() {
		if($this->session->userdata('CRUD_AUTH')) {
			$actual = $this->input->post('actual');
			$id_usuario = $this->input->post('usuarioId');
			
			if($this->m_admin->checkPass($actual, $id_usuario)) {
				$password = $this->input->post('password');
				
				if ($this->m_admin->changePass($password, $id_usuario)) {
					echo 'ok';
				} else {
					echo 'bad';
				}
			} else {
				echo 'nocoincide';
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
	
	public function attendance() {
		if($this->session->userdata('CRUD_AUTH')) {
			$usuario = $this->session->userdata('CRUD_AUTH');
			$datos['title'] = 'Lista Asistencia';
			$datos['talleres'] = $this->m_registro->getTalleresByPlantelAndDate($usuario['id_plantel'], fecha_actual());
			$datos['plantel'] = $this->m_registro->getPlantelById($usuario['id_plantel']);
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/nav', false, false);
			$this->load->view('admin/attendance', $datos, false);
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function ajaxGetBeneficiarios($taller = "") {
		if($this->session->userdata('CRUD_AUTH')) {
			set_time_limit(0);
			ini_set('memory_limit', '-1');
			ini_set('max_execution_time', '0');
			ini_set('zlib.output_compression', '0');
			ini_set('implicit_flush', '1');
			ignore_user_abort(true);
			$usuario = $this->session->userdata('CRUD_AUTH');
			$datos = $this->m_admin->builtBeneficiarios($usuario['id_plantel'], $taller);
			echo $datos;
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function profile() {
		if($this->session->userdata('CRUD_AUTH')) {
			$usuario = $this->session->userdata('CRUD_AUTH');
			$datos['title'] = 'Perfil';
			$datos['sedes'] = $this->m_registro->getPlantelesActivos();
			$datos['usuario'] = $this->m_admin->getUsuarioById($usuario['id_usuario']);
			$datos['plantel'] = $this->m_registro->getPlantelById($usuario['id_plantel']);
			$this->load->view('layout/header', $datos, false);
			$this->load->view('admin/nav', false, false);
			$this->load->view('admin/edit', $datos, false);
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function excel() {
		if($this->session->userdata('CRUD_AUTH')) {
			if($this->session->userdata('CRUD_AUTH')['perfil'] == 'Programador' || $this->session->userdata('CRUD_AUTH')['perfil'] == 'Administrador') {
				header("Content-type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=usuarios" . date("YmdHis") . ".xls");
				header("Pragma: no-cache");
				header("Expires: 0");
			
				echo utf8_decode($_POST['datos_a_enviar']);
			} else {
				header("Location: " . base_url('asistencia'));
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function excelPdf() {
		if($this->session->userdata('CRUD_AUTH')) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=beneficiarios" . date("YmdHis") . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
				
			echo utf8_decode($_POST['datos_a_enviar']);
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	public function pdf() {
		if($this->session->userdata('CRUD_AUTH')) {
			$name = "asistencia" . date("YmdHis") . ".pdf";
			//file_get_contents is standard function
			$content = file_get_contents($_POST['datos_a_enviar']);
			header('Content-Type: application/pdf');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize( $content ));
			header('Content-disposition: inline; filename="' . $name . '"');
			header('Cache-Control: public, must-revalidate, max-age=0');
			header('Pragma: public');
			header('Expires: 0');
				
			echo utf8_decode($content);
			/*$this->load->library('Pdf');
			$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Alfredo Mtz Cobos');
			$pdf->SetTitle('Comprobante');
			$pdf->SetSubject('Impresión de asistencia Conferencias "Prepárate"');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
			ob_start();
			// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0, 64, 255), array(0, 1, 0));
			$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
			 
			// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			 
			// se pueden modificar en el archivo tcpdf_config.php de libraries/config
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// se pueden modificar en el archivo tcpdf_config.php de libraries/config
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			 
			// se pueden modificar en el archivo tcpdf_config.php de libraries/config
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			 
			//relación utilizada para ajustar la conversión de los píxeles
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->setFontSubsetting(true);
			$pdf->SetFont('helvetica', '', 14, '', true);
			$pdf->AddPage();
			//fijar efecto de sombra en el texto
			$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(255, 255, 255), 'opacity' => 1, 'blend_mode' => 'Normal'));
			
			//preparamos y maquetamos el contenido a crear
			
			$html ="";
			$html .= "<style type=text/css>";
			$html .=" h1 {
			
						    width: 100%;
						    font-weight: bold;
						    font-size: 13;
						    line-height: 2;
						    text-align: center;
						    color: #4C4C4C;
						}
	    			h2{
	    					text-align: justify;
	    					font-weight: bold;
							font-size: 9;
							line-height: 1.5;
	    					color:  #070005;
	    			}
	    			p{
	    			 	line-height: 1.5;
	    				color: #5E5D5D;
	    				font-weight: bold;
						text-align: letf;
	    				font-size: 9;
					}
				";
			
			$html .= "</style>";
	    	
	    	$html .= $_POST['datos_a_enviar'];
	    	$html .="";
	    	
	    	// Imprimimos el texto con writeHTMLCell()
	    	//$pdf->writeHTML($html, true, 0, true, 0);
	    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	    	$pdf->lastPage();
	    	$nombre_archivo = utf8_decode("Asistencia.pdf");
			$pdf->Output($nombre_archivo, 'I');
			ob_end_flush();*/
		} else {
			header("Location: " . base_url('admin'));
		}
	}
}