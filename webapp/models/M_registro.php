<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'no se permite el acceso directo al script' );

class M_registro extends MY_Model {
	protected $db_b;
	
	function __construct() {
		parent::__construct ();
		//cargamos la BD de Beneficiarios Prepa Si		
		$this->db_b = $this->load->database('beneficiarios', TRUE);
	}
	
	/**
	 * Obtiene los datos del ciclo corriente activo.
	 * 
	 * @return List:cat_ciclo  Datos del ciclo activo. Null en caso contrario.
	 * 
	 * @since  2016-04-08
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getCicloActivo(){
		$this->sql = "SELECT * 
				FROM cat_ciclo CC 
				WHERE CC.activo is true;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	/**
	 * Verifica si un beneficiario ya se registr&oacute; a alg&uacute;n Taller dentro del ciclo corriente.
	 * 
	 * @param  String:$matricula
	 * 
	 * @return String:matricula    Matr&iacute;cula del beneficiario registrado en el taller. Null en caso contrario.
	 * 
	 * @since  2016-04-07
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function checkRegistroTaller($matricula = "") {
		$results = "";
		
		if(!empty($matricula)) {
			$this->sql = "SELECT matricula, espera 
					FROM registro_taller RT, cat_ciclo CC 
					WHERE RT.matricula = UPPER('$matricula') 
					AND RT.id_ciclo = CC.id_ciclo 
					AND CC.activo is true;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene las Sedes que se encuentran activas y que tengan talleres dentro de un ciclo corriente
	 * 
	 * @return int:id_plantel  Idenfiticador de la Sede a buscar. Null en caso contrario.
	 * @return String:plantel  Nombre de la Sede a buscar. Null en caso contrario.
	 * 
	 * @since  2016-04-07
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getPlantelesActivos() {
		$this->sql = "SELECT S.id_plantel, S.plantel 
				FROM sede S, talleres T, taller_plantel TP, cat_ciclo CC 
				WHERE S.id_plantel = TP.id_plantel 
				AND T.id_taller = TP.id_taller 
				AND T.id_ciclo = CC.id_ciclo 
				AND CC.activo is true AND T.activo is true AND S.activo is true
				GROUP BY S.plantel, S.capacidad, S.total_asistentes, S.id_plantel  
				ORDER BY S.plantel ASC;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	/**
	 * Obtiene todos los datos de una Sede en espec&iacute;fico.
	 * 
	 * @param int:$id_plantel          Identificador de la Sede a obtener.
	 * 
	 * @return List:$plantelInstance   Datos del plantel buscado. Null en caso contrario.
	 * 
	 * @since  2016-04-12
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getPlantelById($id_plantel = "") {
		$results = "";
		
		if(!empty($id_plantel)) {
			$this->db->select('*');
			$this->db->from('sede');
			$this->db->where('id_plantel', $id_plantel);
			$query = $this->db->get();
			$plantelInstance = $query->row_array();
			$query->free_result();
			return $plantelInstance;
		}
		
		return $results;
	}
	
	/**
	 * M&eacute;todo que obtiene si alg&uacute;n Taller se encuentra Activo o no.
	 *
	 * @return cat_ciclo:inicio,fin Fecha de inicio y fecha fin del Taller Activo. Null en caso contrario.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getTallerActivo() {
		$this->sql = "SELECT to_char(fecha_inicio, 'DD-MM-YYYY') as inicio, to_char(fecha_fin, 'DD-MM-YYYY') as fin FROM cat_ciclo WHERE activo is true;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	/**
	 * Obtiene si la persona a buscar es un beneficiario o no.
	 * 
	 * @param String:$dato                 Dato a buscar (Matr&iacute;cula PS o CURP).
	 * 
	 * @return String:matricula_asignada   Matr&iacute;cula asignada. Null en caso contrario.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatricula($dato = "") {
		$results = "";
		
		if(!empty($dato)) {
			$this->sql = "SELECT B.matricula_asignada, P.fecha_nacimiento 
			FROM beneficiarios B 
			INNER JOIN b_personal P on B.matricula_asignada = P.matricula_asignada 
			WHERE  P.matricula_asignada = UPPER('$dato') OR P.CURP = UPPER('$dato') AND B.id_archivo in (1, 2, 3);";
			$results = $this->db_b->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene la matr&iacute;cula PS que se registr&oacute;, m&eacute;todo alterno para el Taller de Recup&eacute;rate.
	 *
	 * @param  String:$dato            Matr&iacute;cula PS a obtener la matr&iacute;cula PS.
	 *
	 * @return String:matricula        Matr&iacute;cula PS encontrada. Null en caso contrario.
	 *
	 * @since  2016-07-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaRecuperate($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT matricula FROM registro_taller_recuperate WHERE matricula = '$dato' LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene la matr&iacute;cula PS que se registr&oacute; al Ciclo de Conferencias PREP&Aacute;rate y que no est&aacute; en lista de espera.
	 *
	 * @param  String:$dato           Matr&iacute;cula PS a obtener la matr&iacute;cula PS.
	 *
	 * @return String:matricula       Matr&iacute;cula PS encontrada. Null en caso contrario.
	 *
	 * @since  2016-07-26
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaRegistroTaller($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT matricula FROM registro_taller WHERE matricula = '$dato' AND espera IS FALSE LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene la matr&iacute;cula PS que se registr&oacute; al Ciclo de Conferencias PREP&Aacute;rate y que no est&aacute; en lista de espera.
	 *
	 * @param  String:$dato           Matr&iacute;cula UNAM a obtener la matr&iacute;cula PS.
	 *
	 * @return String:matricula       Matr&iacute;cula PS encontrada. Null en caso contrario.
	 *
	 * @since  2016-07-26
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaUnamRegistroTaller($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT RTU.matricula FROM registro_taller_unam RTU, registro_taller RT WHERE RTU.matricula = RT.matricula 
					AND RTU.matricula_unam = '$dato' AND RT.espera IS FALSE LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene si la persona a buscar es un beneficiario o no.
	 * 
	 * @param  String:$dato                Dato a buscar (matr&iacute;cula escuela UNAM).
	 * 
	 * @return String:matricula_asignada   Matr&iacute;cula asignada. Null en caso contrario.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaUnam($dato = ""){
		$results = "";
		
		if(!empty($dato)) {
			$this->sql = "SELECT E.matricula_asignada, P.fecha_nacimiento
			FROM  b_escolar E 
			INNER JOIN b_personal P on E.matricula_asignada = P.matricula_asignada 
			WHERE E.matricula_escuela = UPPER('$dato') AND E.id_archivo in (1, 2, 3) AND E.id_institucion in (1, 2, 15);";
			$results = $this->db_b->query($this->sql);
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
	 *
	 * @since  2016-07-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getMatriculaUnamRecuperate($dato = "") {
		$results = "";
	
		if(!empty($dato)) {
			$this->sql = "SELECT matricula FROM registro_taller_unam_recuperate WHERE matricula_unam = '$dato' LIMIT 1;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtenemos todas las Sedes que se encuentren disponibles y activas, considerando que tenga talleres activos y se encuentre dentro de un ciclo activo
	 * 
	 * @return List:id_plantel, plantel, direccion     Lista de identificadores de los planteles as&iacute; como su nombre y direcci&oacute;n de estos. Null en caso contrario.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getDisponibilidad() {
		$this->sql = "SELECT S.id_plantel, S.plantel, S.direccion, S.url, S.ruta_transporte 
				FROM sede S, talleres T, taller_plantel TP, cat_ciclo CC 
				WHERE S.id_plantel = TP.id_plantel 
				AND T.id_taller = TP.id_taller 
				AND T.id_ciclo = CC.id_ciclo 
				AND CC.activo is true AND T.activo is true AND S.activo is true
				GROUP BY S.plantel, S.capacidad, S.total_asistentes, S.id_plantel, S.direccion, S.url, S.ruta_transporte 
				HAVING ((S.capacidad + 100) - S.total_asistentes) > 0 
				ORDER BY S.plantel ASC;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	/**
	 * Obtiene la informaci&oacute;n personal del beneficiario.
	 * 
	 * @param String:$matricula     Matricula asignada a buscar.
	 * 
	 * @return List:Beneficiario    Listado de atributos del beneficiario. Null en caso contrario.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getDatos($matricula = "") {
		$results = "";
		
		if(!empty($matricula)) {
			$this->sql = "SELECT B.nombre, B.ap, B.am, B.matricula_asignada, P.curp, PL.plantel, I.institucion 
			FROM beneficiarios B, b_escolar E, b_personal P, cat_institucion I, cat_plantel PL
			WHERE B.matricula_asignada = E.matricula_asignada
			AND B.matricula_asignada = P.matricula_asignada
			AND E.id_institucion = I.id_institucion
			AND E.id_plantel = PL.id_plantel
			AND B.matricula_asignada = UPPER('$matricula');";
			$results = $this->db_b->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Verifica si una Sede todav&iacute;a cuenta con disponibilidad de asistentes.
	 * 
	 * @param  int:$id_plantel       Identificador de la Sede a buscar.
	 * 
	 * @return String:plantel        Nombre de la Sede encontrada. Null en caso contrario.
	 * @return int:total_asistentes  N&uacute;mero de asistentes que se han registrado en la Sede.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getDisponibilidadByPlantel($id_plantel = "") {
		$results = "";
		
		if(!empty($id_plantel)) {
			$this->sql = "SELECT plantel, capacidad, total_asistentes 
					FROM sede 
					WHERE id_plantel = $id_plantel 
					GROUP BY plantel, capacidad, total_asistentes  
					HAVING ((capacidad + 100) - total_asistentes) > 0;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene todos los talleres que se encuentren activos dentro de un ciclo activo.
	 * 
	 * @return talleres:taller, archivo      Nombre y archivo (imagen) del taller. Null en caso contrario.
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getTalleres() {
		$this->sql = "SELECT T.taller, T.archivo, to_char(T.fecha_inicio, 'DD-MM-YYYY') as fecha_inicio
				FROM talleres T, cat_ciclo CC 
				WHERE T.id_ciclo = CC.id_ciclo AND CC.activo is true AND T.activo is true 
				ORDER BY T.id_taller ASC;";	
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	/**
	 * Inserta el registro del asistente as&iacute; como actualiza el campo 'total_asistentes' de la tabla 'Sede'.
	 * 
	 * @param  List:$post         Matr&iacute;cula asignada e identificador del plantel
	 * @param  int:$asistentes    N&uacute;mero de asistentes a incrementar con el nuevo registro.
	 * 
	 * @return boolean            True en caso de registro y actualizado exitoso. Null en caso contrario.  
	 * 
	 * @since  2016-04-04
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function create($post = "", $asistentes = "", $espera = "") {
		if(!empty($post) || !empty($asistentes) || !empty($espera)) {
			//obtenemos el ciclo activo
			$ciclo = $this->getCicloActivo();
			//controlamos la transaccion
			$this->db->trans_begin();
			$dataRegistroTaller = array(
					'matricula' => strtoupper($post['matricula']),
					'id_plantel' => $post['sede'],
					'id_ciclo' => $ciclo[0]['id_ciclo'],
					'espera' => $espera
			);
			
			$this->db->insert('registro_taller', $dataRegistroTaller);
			
			//actualizamos la lista de asistencia
			$dataSede = array(
					'total_asistentes' => ($asistentes + 1),
			);
				
			$this->db->where('id_plantel', $post['sede']);
			$this->db->update('sede', $dataSede);
			
			if($this->db->trans_status() === TRUE){
				//se realiza el insert y update
				$this->db->trans_commit();
				return true;
			} else {
				//ocurre error y abortamos el proceso
				$this->db->trans_rollback();
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Obtiene los datos de un beneficiatio registrado.
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
			AND matricula = UPPER('$matricula');";
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
	function getRegistroRecuperate($matricula = ""){
		$results = "";
	
		if(!empty($matricula)) {
			$this->sql = "SELECT rt.matricula, rtr.id_plantel, s.plantel, s.ruta_transporte as ruta, s.imagen, s.direccion, TO_CHAR(rt.fecha_registro, 'dd-mm-yyyy') fecha_registro, s.espacio
			FROM registro_taller_recuperate rtr, registro_taller rt, sede s
			WHERE rtr.matricula = rt.matricula 
			AND rtr.id_plantel = s.id_plantel
			AND rt.matricula = UPPER('$matricula');";
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
			$this->sql = "SELECT nombre, ap, am FROM beneficiarios 	WHERE matricula_asignada = UPPER('$matricula')";
			$results = $this->db_b->query($this->sql);
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
	function getTallerByPlantel($sede = ""){
		$results = "";
		
		if(!empty($sede)){
		$this->sql="SELECT TA.taller, to_char(TA.fecha_inicio, 'DD-MM-YYYY') as fecha_inicio
				FROM talleres TA, cat_ciclo CC, taller_plantel TP, sede S 
				WHERE TP.id_taller= TA.id_taller AND TP.id_plantel=S.id_plantel AND 
				TA.id_ciclo = CC.id_ciclo AND CC.activo is true AND TA.activo is true
				AND S.id_plantel=$sede 
				ORDER BY TA.id_taller ASC;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene los talleres correspondientes al plantel y de acuerdo a un d&iacute;a en espec&iacute;fico.
	 *
	 * @param  int:$sede  Identificador del plantel.
	 * @param  Date:$hoy  Fecha a buscar.
	 *
	 * @return List       Talleres encontrados. Null en caso contrario.
	 *
	 * @since  2016-05-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function getTalleresByPlantelAndDate($sede = "", $hoy = ""){
		$results = "";
	
		if(!empty($sede) || !empty($hoy)){
			$this->sql = "SELECT TA.taller, TA.id_taller 
					FROM talleres TA, cat_ciclo CC, taller_plantel TP, sede S 
					WHERE TP.id_taller = TA.id_taller AND TP.id_plantel = S.id_plantel 
					AND TA.id_ciclo = CC.id_ciclo AND CC.activo IS TRUE AND TA.activo IS TRUE 
					AND S.id_plantel = $sede AND TA.fecha_inicio <= '$hoy' 
					ORDER BY TA.id_taller ASC;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene el número de pagos del beneficiario.
	 *
	 * @param  String:$matricula     Matricula asignada a buscar.
	 *
	 * @return List: nombre, fecha del talleres. Null en caso contrario.
	 *
	 * @author cony jaramillo
	 */
	function noPagos($matricula){
		$results="";
		if(!empty($matricula)){
			$this->sql="SELECT * FROM pagobeneficiarios WHERE matricula_asignada = UPPER('$matricula');";
			$results = $this->db_b->query($this->sql);
			return $results->result_array();
		}
		return $results;
	}
}