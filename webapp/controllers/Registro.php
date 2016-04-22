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
		if(!empty($this->input->post())){
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
		} else {
			header("Location: " . base_url());
		}
	}
	
	function getBeneficiarioReimpresion(){
		if(!empty($this->input->post())){
			$matricula =  $this->input->post('matricula');
			$aux = $this->m_registro->getMatricula($matricula);
		
			$aux = isset($aux[0]['matricula_asignada']) ? $aux[0]['matricula_asignada'] : null;
		
			if (!is_null($aux)) {
				$registro = $this->m_registro->checkRegistroTaller($aux);
		
				if(empty($registro)) {
					echo 'bad';
				} else {
					echo $aux;
				}
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url());
		}
	}
	
	function getBeneficiarioUnam(){
		if(!empty($this->input->post())){
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
		} else {
			header("Location: " . base_url());
		}
	}
	
	function getBeneficiarioUnamReimpresion(){
		if(!empty($this->input->post())){
			$matricula =  $this->input->post('matricula_escuela');
			$aux = $this->m_registro->getMatriculaUnam($matricula);
		
			$aux = isset($aux[0]['matricula_asignada']) ? $aux[0]['matricula_asignada'] : null;
		
			if (!is_null($aux)) {
				$registro = $this->m_registro->checkRegistroTaller($aux);
					
				if(empty($registro)) {
					echo 'bad';
				} else {
					echo $aux;
				}
			} else {
				echo 'bad';
			}
		} else {
			header("Location: " . base_url());
		}
	}
	
	function nuevo($matricula = "") {
		if(!empty($matricula)){
			$datos['title'] = 'Registro Taller';
			$disponibilidad = $this->m_registro->getDisponibilidad();
			
			$this->load->view('layout/header', $datos, false);
			
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
						$datos['disponible'] = 3;
						$this->load->view('registro/nuevo', $datos, false);
					}
				} else {
					//sin talleres disponibles
					$datos['disponible'] = 2;
					$this->load->view('registro/nuevo', $datos, false);
				}
			} else {
				//sin sedes disponibles
				$datos['disponible'] = 1;
				$this->load->view('registro/nuevo', $datos, false);
			}
			
			$this->load->view('layout/footer', false, false);
		} else {
			header("Location: " . base_url());
		}
	}
	
	function guardar() {
		if(!empty($this->input->post())){
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
		} else {
			header("Location: " . base_url());
		}
	}
	
	function pdf($matricula = ""){
		if(!empty($matricula)){
			$this->load->library('Pdf');
	    	$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	    	$pdf->SetCreator(PDF_CREATOR);
	    	$pdf->SetAuthor('Cony Jaramillo');
	    	$pdf->SetTitle('Comprobante');
	    	$pdf->SetSubject('Registro Coordinadores y Promotores');
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
	    
	    
	    	// ---------------------------------------------------------
	    	// establecer el modo de fuente por defecto
	    	$pdf->setFontSubsetting(true);
	    
	    	// Establecer el tipo de letra
	    
	    	$pdf->SetFont('helvetica', '', 14, '', true);
	    
	    	// Añadir una página
	    	// Este método tiene varias opciones, consulta la documentación para más información.
	    	
	    	$pdf->AddPage();
	    	//fijar efecto de sombra en el texto
	    	$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(255, 255, 255), 'opacity' => 1, 'blend_mode' => 'Normal'));
	    	
	    	//$matricula=$this->input->post('matricula');
	    	$registro = $this->m_registro->getRegistro($matricula);
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
	    	
	    	$nombre = $this->m_registro->getNombre($matricula);
	    	
	    	if(!empty($nombre)) {
	    		$datos['nombre'] = $nombre[0]['nombre'];
	    		$datos['paterno'] = $nombre[0]['ap'];
	    		$datos['materno'] = $nombre[0]['am'];
	    			
	    	}
	    	
	    	if(empty($datos['nombre'])) {
	    		redirect(base_url());
	    	}
	    	
	    	$talleres = $this->m_registro->getTallerByPlantel($datos['id_plantel']);
	    	
	    	if(!empty($talleres)) {
	    		$datos['taller'] = $talleres;
	    	}
	    	
	    	if(empty($datos['taller'])) {
	    		redirect(base_url());
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
	    			h3{
	    				line-height: 3;
	    				text-align: center;
	    				font-weight: normal;
	    				font-size: 9;
	    				}
						h4{
	    					text-align: center;
	    					font-weight: bold;
							font-size: 10;
							line-height: 1;
	    					color: #070005;
					}
	    			
	    			h5{
	    					text-align: justify;
	    					font-weight: bold;
							font-size: 10;
							line-height: 4;
	    					color:  #070005;
					}
	    			p {
	    			 	line-height: 1.5;
	    				color: #5E5D5D;
	    				font-weight: bold;
						text-align: letf;
	    				font-size: 9;
					}
				";
	    	
	    	$html .= "</style>";
	    	
	    	$html .='<h1>'.$datos['nombre'].' '.$datos['paterno'].' '.$datos['materno'].'</h1>';//$datos['nombre'].' '.$datos['paterno'].' '.$datos['materno'].
	    	
	    	//$html .="<p><h5></h5></p>";
	    	
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
	    	
	    	# Imagenes del Encabezado
	    	
			
			
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
	    	
	    	$html1 .='<h1>CICLO DE CONFERENCIAS "PREPÁRATE"</h1><br><br>';
	    	$html1 .='<table border="0" width="100%">
	    			 <tr><td >&nbsp;</td></tr>';
	    	
	    	
	    	foreach ($datos['taller'] as $value)////width="100%" height="100%"
	    	{
	    		
	    		$html1 .='
		    			<tr>
		    				<td width="5%"></td>
	    					<td width="3%">&nbsp;</td> 
		    				<td width="50%"><p>'.$value['taller'].'</p></td>
		    				<td width="3%">&nbsp;</td>
		    				<td width="20%"><p>'.$value['fecha_inicio'].'</p></td>
		    				<td width="20%"><p>9:30 am</p></td>
		    			</tr>
		    			<tr><td >&nbsp;</td></tr>	    			
	    			';    		
	    	}
	    	
	    	$html1 .='</table>';
	    	//$pdf->writeHTML($html1, true, 0, true, 0);
	    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '95', $html1, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	    	$pdf->lastPage();
	    	
	    	$pdf->AddPage();
	    	$html3 ='
	    			<br/>
	    			<h1>Cómo llegar:</h1>
	    			<br/>
					'.$datos['ruta'].'	
			    	 <br/><br/><br/>
					 <img src="'. base_url() .'/resources/img/'.$datos['imagen'].'" alt="test alt attribute"  border="0" />
					 </div>';
	    	//MODIFICAR ULTIMAS LEYENDAS
	    	$html3 .='<ul>
						  <li>Telefono y horario de atención</li>
						  <li>llevar identificación</li>
	    				  <li>No olvides llevar el presente documento cada vez que asistas a las conferencias</li>
					 </ul>  ';
			// output the HTML content
			$pdf->writeHTML($html3, true, false, true, false, '');
			    	
			    	
	    	$pdf->lastPage();
	    	 
	    	$nombre_archivo = utf8_decode("Registro.pdf");
	    	
	    
	    	$pdf->Output($nombre_archivo, 'I');
	    	
	    	ob_end_flush();
		} else {
			header("Location: " . base_url());
		}
	}
}