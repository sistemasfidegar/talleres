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
			$this->sql = "SELECT matricula	FROM registro_taller WHERE  matricula = '$dato';";
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
			$this->sql="select * from talleres t, taller_plantel tp  
					WHERE t.id_taller=tp.id_taller  and fecha_inicio='$hoy'  
					and tp.id_plantel=(SELECT id_plantel from registro_taller where matricula='$matricula')  and activo is true;";
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
			$this->sql="SELECT matricula from asistencia WHERE id_taller=$idtaller and matricula='$matricula'";
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
			$this->sql = "INSERT INTO asistencia(id_taller, matricula, id_usuario) VALUES($idtaller, '$matricula', $usuario) RETURNING matricula";
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
			$this->sql = "select count(*) as suma from taller_plantel where id_plantel = (select id_plantel from registro_taller where matricula='$matricula');";
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
			$this->sql = "SELECT nombre, ap, am FROM beneficiarios 	WHERE matricula_asignada='$matricula'";
			$results = $this->db_b->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	function getTalleresAsistencia($matricula = ""){
		if(!empty($matricula)) {
			$this->sql = "select T.id_taller,T.taller, fecha  from asistencia A, talleres T where A.id_taller=T.id_taller and matricula='$matricula' ORDER BY T.id_taller ASC;";
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
			AND S.id_plantel=(SELECT id_plantel FROM registro_taller where matricula='$matricula')
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
			$this->sql = "SELECT matricula, rt.id_plantel, plantel, s.ruta_transporte as ruta, s.imagen, s.direccion, TO_CHAR(fecha_registro, 'dd-mm-yyyy') fecha_registro,s.espacio
			FROM registro_taller rt, sede s
			WHERE rt.id_plantel = s.id_plantel
			AND matricula='$matricula'";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
}