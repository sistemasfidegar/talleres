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
			$temp = $this->m_asistencia->getMatricula($matricula);
		
			$aux = isset($temp[0]['matricula']) ? $temp[0]['matricula'] : null;
		
			if (!is_null($aux)) {
				$espera = ($temp[0]['espera'] == "f") ? true : null;
				
				if (!is_null($espera)) {
					$taller = $this->m_asistencia->getTaller(fecha_actual(), $aux);
					$idtaller = isset($taller[0]['id_taller']) ? $taller[0]['id_taller'] : null;
					$nombreTaller = isset($taller[0]['taller']) ? $taller[0]['taller'] : "";
					
					if (!is_null($idtaller)) {
						$asistencia = $this->m_asistencia->insertaAsistencia($idtaller, $aux, $usuario['id_usuario']);
						
						if (!is_null($asistencia)) {
							echo $nombreTaller;
						} else {
							echo 'error';
						}
					} else {
						echo 'sintaller';
					}
				} else {
					echo 'nocumple';
				}
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url('admin'));
		}
	}
	
	function registroAsistenciaUnam() {
		if($this->session->userdata('CRUD_AUTH')) {
			$usuario = $this->session->userdata('CRUD_AUTH');
			$matricula = $this->input->post('matricula_escuela');
			$temp = $this->m_asistencia->getMatriculaUnam($matricula);
	
			$aux = isset($temp[0]['matricula']) ? $temp[0]['matricula'] : null;
	
			if (!is_null($aux)) {
				$taller = $this->m_asistencia->getTaller(fecha_actual(), $aux);
				$idtaller = isset($taller[0]['id_taller']) ? $taller[0]['id_taller'] : null;
				$nombreTaller = isset($taller[0]['taller']) ? $taller[0]['taller'] : "";
					
				if (!is_null($idtaller)) {
					$asistencia = $this->m_asistencia->insertaAsistencia($idtaller, $aux, $usuario['id_usuario']);

					if (!is_null($asistencia)) {
						echo $nombreTaller;
					} else {
						echo 'error';
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

	function listaAsistencia($matricula=""){
		
		if(!empty($matricula)){
			$this->load->library('Pdf');
			$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Cony Jaramillo');
			$pdf->SetTitle('Comprobante');
			$pdf->SetSubject('Impresión de Asistencia Conferencias "PREPÁrate"');
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
			
			$registro = $this->m_asistencia->getRegistro($matricula);
			$datos = array('matricula' => '', 'plantel' => '', 'direccion' => '', 'fecha' => '', 'ruta' => '', 'imagen' => '', 'nombre' => '',
					'paterno' => '', 'materno' => '', 'taller' => '');
			
			if(!empty($registro)) {
				$datos['matricula'] = $registro[0]['matricula'];
				$datos['id_plantel'] = $registro[0]['id_plantel'];
				$datos['plantel'] = $registro[0]['plantel'];
				$datos['direccion'] = $registro[0]['direccion'];
				$datos['espacio'] = $registro[0]['espacio'];
				$datos['fecha'] = $registro[0]['fecha_registro'];
				$datos['ruta'] = $registro[0]['ruta'];
				$datos['imagen'] = $registro[0]['imagen'];
			}
			
			if(empty($datos['matricula'])) {
				redirect(base_url());
			}
			
			$nombre = $this->m_asistencia->getNombre($matricula);
			if(!empty($nombre)) {
				$datos['nombre'] = $nombre[0]['nombre'];
				$datos['paterno'] = $nombre[0]['ap'];
				$datos['materno'] = $nombre[0]['am'];
			
			}
			
			if(empty($datos['nombre'])) {
				redirect(base_url());
			}
			
			$noTalleres= $this->m_asistencia->noTalleres($matricula);
			$noTalleres = isset($noTalleres[0]['suma']) ? $noTalleres[0]['suma'] : null;
			
			if(!is_null($noTalleres)) {
				$noTalleres= ($noTalleres/100);
			}
			$datos['asistencia']=array();
			$datos['asistencia']=$this->m_asistencia->getTalleresAsistencia($matricula);
			
			if(!empty($datos['asistencia'])) {
				$datos['asistencia'] = $asistencia;
				
				
			}
			
			$talleres = $this->m_asistencia->getTallerByPlantel($matricula);
			
			if(!empty($talleres)) {
				$datos['taller'] = $talleres;
			}
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
	    	
	    	$html .='<h1>'.$datos['nombre'].' '.$datos['paterno'].' '.$datos['materno'].'</h1>';//$datos['nombre'].' '.$datos['paterno'].' '.$datos['materno'].
	    	
	    	
	    	
	    	$html .='<table border="0">
		    			<tr>
		    				<td><h2>FECHA DE REGISTRO</h2></td>
		    				<td colspan="2"><p>'.fecha_con_letra($datos['fecha']).'</p></td>
		    			</tr>
		    			<tr>
		    				<td><h2>SEDE</h2></td>
		    				<td colspan="2"><p>'.$datos['plantel'].'&nbsp;&nbsp;('.$datos['espacio'].')</p></td>
		    			</tr>
		    			<tr>
		    				<td><h2>DIRECCIÓN</h2></td>
		    				<td colspan="2"><p>'.$datos['direccion'].'</p></td>
		    			</tr>
	    			</table>';
	    	
	    	$html .="";
	    	
	    	// Imprimimos el texto con writeHTMLCell()
	    	//$pdf->writeHTML($html, true, 0, true, 0);
	    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	    	
	    	$style1 = array('padding'=>'auto' );
	    	$style2 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
	    	$pdf->Line(15, 83, 195, 83, $style2);
	    		
	    	$tipos=array('C128A');
	    	$pdf->SetFont('helvetica', '', 9, '', true);
	    	$pdf->Cell(170,40,$matricula,0,0,'C');
	    	$pdf->write1DBarcode($matricula, 'C128A', 75,63,52,11, 0.4, $style1, 'N');
	    	
	    	$html1 =" <style type=text/css>
	    			p{
	    				text-align: left;
	    				font-weight: bold;
						font-size: 9;
						line-height: 1.5;
	    				color:  #070005;
	    			}
	    			h1 {
						 width: 100%;
						 font-weight: bold;
						 font-size: 13;
						 line-height: 2;
						 text-align: center;
						 color: #4C4C4C;
						}
	    			</style>";
	    	
	    	$html1 .='<h1>ASISTENCIA AL CICLO DE CONFERENCIAS "PREP&Aacute;RATE"</h1>';
	    	$html1 .='<table border="0" width="100%">
	    			 <tr><td >&nbsp;</td></tr>';
	    	
	    	$count=0;
	    	
	    	foreach ($datos['taller'] as $value)////width="100%" height="100%"
	    	{
	    			
	    		$selected="No asistió";
	    		foreach ($datos['asistencia'] as $vol){
	    			
		    		if(in_array($vol['id_taller'],$value)){
		    			$selected="Asistió";
		    			$count++;
		    		}
	    		}
	    		$html1 .='
		    			<tr>
		    				<td width="10%"></td>
	    					
		    				<td width="60%"><p>'.$value['taller'].'</p></td>
		    				<td width="10%"><p>'.$value['fecha_inicio'].'</p></td> 
		    				<td width="3%">&nbsp;</td>
		    				<td width="10%"><p>'.$selected.'</p></td>
		    				
		    			</tr>
		    			<tr><td >&nbsp;</td></tr>	    			
	    			';    	
	    		
	    	}
	    	
	    	$html1 .='</table>';
	    	
	    	//$pdf->writeHTML($html1, true, 0, true, 0);
	    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '85', $html1, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	    	$porcentaje=$count*$noTalleres*100;
	    	$html2 =" <style type=text/css>
	    			
	    			h1{
						 width: 60%;
						 font-weight: bold;
						 font-size: 16;
						 line-height: 2;
						 text-align: center;
						 color: #4C4C4C;
						}
	    			</style>";
	    	$html2.='<h1>Porcentaje de Asistencia: '.$porcentaje.'%</h1>';
	    	
	    	
	    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '210', $html2, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	    	$pdf->lastPage();
			

	    	$nombre_archivo = utf8_decode("Asistencia.pdf");
			$pdf->Output($nombre_archivo, 'I');
			ob_end_flush();
		}
		else
		{
			header("Location: " . base_url());
		}
	}

	
}