<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'no se permite el acceso directo al script' );

class M_asistencia extends MY_Model {
	protected $db_b;
	
	function __construct() {
		parent::__construct ();
		//cargamos la BD de Beneficiarios Prepa Si
		$this->db_b = $this->load->database('beneficiarios', TRUE);
	}
	
	function checkImpresionValidacion($dato = "") {
		$results = "";
		
		if(!empty($dato)) {
			$this->sql = "SELECT * FROM impresion_validacion WHERE matricula = UPPER('$dato')";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * M&eacute;todo que obtiene si alg&uacute;n Taller se encuentra Activo o no.
	 *     
	 * @return cat_ciclo:inicio,fin Fecha de inicio y fecha fin del Taller Activo. Null en caso contrario.
	 */
	function getCicloActivo() {
		$this->sql = "SELECT  id_ciclo, to_char(fecha_inicio, 'DD-MM-YYYY') as inicio, to_char(fecha_fin, 'DD-MM-YYYY') as fin FROM cat_ciclo WHERE activo is true;";
		$results = $this->db->query($this->sql);
		return $results->result_array ();
	}
	
	/**
	 * @param unknown $dato
	 */
	function getMatricula($dato = "") {
		$results = "";
		
		if(!empty($dato)) {
			$this->sql = "SELECT matricula, espera FROM registro_taller WHERE matricula = UPPER('$dato') AND espera = FALSE LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene la matr&iacute;cula PS que se registr&oacute; m&eacute;todo alterno para el Taller de Recup&eacute;rate.
	 *
	 * @param String:$dato            Matr&iacute;cula PS a obtener la matr&iacute;cula PS.
	 *
	 * @return String:matricula       Matr&iacute;cula PS encontrada. Null en caso contrario.
	 *
	 * @since  2016-07-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaRecuperate($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT * FROM registro_taller_recuperate WHERE matricula = UPPER('$dato') LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene la matr&iacute;cula PS que se registr&oacute; al sistema de acuerdo al par&aacute;metro de busqueda.
	 * 
	 * @param String:$dato            Matr&iacute;cula UNAM a obtener la matr&iacute;cula PS.
	 * 
	 * @return String:matricula       Matr&iacute;cula PS encontrada. Null en caso contrario.
	 *
	 * @since  2016-05-23
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaUnam($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT matricula FROM registro_taller_unam WHERE matricula_unam = '$dato' LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene la matr&iacute;cula PS que se registr&oacute; m&eacute;todo alterno para el Taller de Recup&eacute;rate.
	 *
	 * @param String:$dato            Matr&iacute;cula UNAM a obtener la matr&iacute;cula PS.
	 *
	 * @return String:matricula       Matr&iacute;cula PS encontrada. Null en caso contrario.
	 * @return int:id_plantel         Identificador de la Sede ligada al beneficiario. Null en caso contrario.
	 *
	 * @since  2016-07-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaUnamRecuperate($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT rtr.matricula, rtr.id_plantel FROM registro_taller_unam_recuperate rtur, registro_taller_recuperate rtr 
					WHERE rtur.matricula = rtr.matricula 
					AND matricula_unam = '$dato' LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * 
	 *
	 * @return 
	 * @author Cony Jaramillo
	 */
	function getTaller($hoy = "", $matricula=""){
		$results = "";
		
		if(!empty($hoy)) {
			$this->sql="SELECT * FROM talleres t, taller_plantel tp  
					WHERE t.id_taller = tp.id_taller  AND t.fecha_inicio = '$hoy'  
					AND tp.id_plantel = (SELECT id_plantel FROM registro_taller WHERE matricula = UPPER('$matricula') AND espera = FALSE LIMIT 1) AND t.activo is true;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 *
	 *
	 * @return
	 * @author Cony Jaramillo
	 */
	function getTallerRecuperate($hoy = "", $matricula=""){
		$results = "";
	
		if(!empty($hoy)) {
			$this->sql = "SELECT * FROM talleres t, taller_plantel tp
			WHERE t.id_taller = tp.id_taller AND t.fecha_inicio = '$hoy'
			AND tp.id_plantel = (SELECT id_plantel FROM registro_taller_recuperate WHERE matricula = UPPER('$matricula') LIMIT 1) AND t.activo is true;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * 
	 *
	 * @return 
	 * @author Cony Jaramillo
	 */
	function registroDuplicado($idtaller = "", $matricula = ""){
		$results = "";
		
		if(!empty($idtaller) || !empty($matricula)) {
			$this->sql="SELECT matricula FROM asistencia WHERE id_taller = $idtaller AND matricula = UPPER('$matricula');";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	/**
	 * 
	 *
	 * @return 
	 * @author Cony Jaramillo
	 */
	function insertaAsistencia($idtaller = "", $matricula = "", $usuario = ""){
		$results = "";
		
		if(!empty($idtaller) || !empty($matricula) || !empty($usuario)) {
			$this->sql = "INSERT INTO asistencia(id_taller, matricula, id_usuario) VALUES($idtaller, UPPER('$matricula'), $usuario) RETURNING matricula";
			$results = $this->db->query($this->sql);
			return $results;
		}
		
		return $results;
	}
	/**
	 * 
	 *
	 * @return 
	 * @author Cony Jaramillo
	 */
	function noTalleres($matricula=""){
		$results = "";
		if (!empty($matricula)){
			$this->sql = "SELECT count(*) AS suma from taller_plantel WHERE id_plantel = (SELECT id_plantel FROM registro_taller WHERE matricula = UPPER('$matricula'));";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		return $results;
			
				
	}
	/**
	 * Obtiene nombre  del beneficiatio registrado.
	 *
	 * @param  String:$matricula     Matricula asignada a buscar.
	 *
	 * @return List:Beneficiario    nombre del beneficiario. Null en caso contrario.
	 *
	 * @author cony jaramillo
	 */
	function getNombre($matricula = ""){
		$results = "";
	
		if(!empty($matricula)) {
			$this->sql = "SELECT nombre, ap, am FROM beneficiarios WHERE matricula_asignada = UPPER('$matricula')";
			$results = $this->db_b->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	function getTalleresAsistencia($matricula = ""){
		if(!empty($matricula)) {
			$this->sql = "SELECT DISTINCT(T.id_taller), T.taller FROM asistencia A, talleres T WHERE A.id_taller = T.id_taller AND matricula = UPPER('$matricula') ORDER BY T.id_taller ASC;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		return $results;
		
	}
	/**
	 * Obtiene los talleres correspondientes al plantel.
	 *
	 * @param  int:sede identificador del plantel.
	 *
	 * @return List: nombre, fecha del talleres. Null en caso contrario.
	 *
	 * @author cony jaramillo
	 */
	function getTallerByPlantel($matricula = ""){
		$results="";
	
		if(!empty($matricula)){
			$this->sql="SELECT TA.id_taller,TA.taller, to_char(TA.fecha_inicio, 'DD-MM-YYYY') as fecha_inicio
			FROM talleres TA, cat_ciclo CC, taller_plantel TP, sede S
			WHERE TP.id_taller= TA.id_taller 
			AND TP.id_plantel=S.id_plantel 
			AND	TA.id_ciclo = CC.id_ciclo AND CC.activo is true AND TA.activo is true
			AND S.id_plantel = (SELECT id_plantel FROM registro_taller where matricula = UPPER('$matricula'))
			ORDER BY TA.id_taller ASC;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		return $results;
	}

	/**
	 * Obtiene los datos del beneficiatio registrado.
	 *
	 * @param  String:$matricula     Matricula asignada a buscar.
	 *
	 * @return List:Beneficiario    Listado de atributos del beneficiario. Null en caso contrario.
	 *
	 * @author cony jaramillo
	 */
	function getRegistro($matricula = ""){
		$results = "";
	
		if(!empty($matricula)) {
			$this->sql = "SELECT matricula, rt.id_plantel, plantel, s.ruta_transporte as ruta, s.imagen, s.direccion, TO_CHAR(fecha_registro, 'dd-mm-yyyy') fecha_registro, s.espacio
			FROM registro_taller rt, sede s
			WHERE rt.id_plantel = s.id_plantel
			AND rt.matricula = UPPER('$matricula') 
			AND espera IS FALSE;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	function insertImpresionValidacion($matricula = "") {
		$results = "";
		
		if(!empty($matricula)) {
			$data = array(
					'matricula' => strtoupper($matricula),
					'validacion' => true
			);
			
			if($this->db->insert('impresion_validacion', $data)) {
				return true;
			} else {
				return false;
			}
		}
		
		return $results;
	}
	
}