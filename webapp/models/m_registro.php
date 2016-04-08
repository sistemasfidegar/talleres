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
	 * Verifica si un beneficiario ya se registr&oacute; a alg&uacute;n Taller dentro del ciclo corriente.
	 * 
	 * @param  String:$matricula
	 * 
	 * @return String:matricula    Matr&iacute;cula del beneficiario registrado en el taller. Null en caso contrario.
	 * 
	 * @since  2016-04-07
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	function checkRegistroTaller($matricula) {
		$this->sql = "SELECT matricula 
				FROM registro_taller RT, cat_ciclo CC 
				WHERE RT.matricula = '$matricula' 
				AND RT.id_ciclo = CC.id_ciclo 
				AND CC.activo is true;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
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
	function getMatricula($dato) {
		$this->sql = "SELECT B.matricula_asignada
		FROM beneficiarios B
		INNER JOIN b_personal P on B.matricula_asignada = P.matricula_asignada
		WHERE  P.matricula_asignada = '$dato' OR P.CURP = '$dato' AND B.id_archivo in (1, 2, 3);";
		$results = $this->db_b->query($this->sql);
		return $results->result_array();
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
	function getMatriculaUnam($dato){
		$this->sql = "SELECT matricula_asignada 
		FROM  b_escolar 
		WHERE matricula_escuela = '$dato' AND id_archivo in (1, 2, 3) AND id_institucion in (1, 2);";
		$results = $this->db_b->query($this->sql);
		return $results->result_array();
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
		$this->sql = "SELECT S.id_plantel, S.plantel, S.direccion 
				FROM sede S, talleres T, taller_plantel TP, cat_ciclo CC 
				WHERE S.id_plantel = TP.id_plantel 
				AND T.id_taller = TP.id_taller 
				AND T.id_ciclo = CC.id_ciclo 
				AND CC.activo is true AND T.activo is true AND S.activo is true
				GROUP BY S.plantel, S.capacidad, S.total_asistentes, S.id_plantel, S.direccion
				HAVING (S.capacidad - S.total_asistentes) > 0 
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
	function getDatos($matricula) {
		$this->sql = "SELECT B.nombre, B.ap, B.am, B.matricula_asignada, P.curp, PL.plantel, I.institucion
		FROM beneficiarios B, b_escolar E, b_personal P, cat_institucion I, cat_plantel PL
		WHERE B.matricula_asignada = E.matricula_asignada
		AND B.matricula_asignada = P.matricula_asignada
		AND E.id_institucion = I.id_institucion
		AND E.id_plantel = PL.id_plantel
		AND B.matricula_asignada = '$matricula';";
		$results = $this->db_b->query($this->sql);
		return $results->result_array();
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
	function getDisponibilidadByPlantel($id_plantel) {
		$this->sql = "SELECT plantel, total_asistentes 
				FROM sede 
				WHERE id_plantel = $id_plantel 
				GROUP BY plantel, capacidad, total_asistentes  
				HAVING (capacidad - total_asistentes) > 0;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
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
		$this->sql = "SELECT T.taller, T.archivo, T.fecha_inicio
				FROM talleres T, cat_ciclo CC 
				WHERE T.id_ciclo = CC.id_ciclo AND CC.activo is true AND T.activo is true;";	
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
	function create($post, $asistentes) {
		//controlamos la transaccion
		$this->db->trans_begin();
		$dataRegistroTaller = array(
				'matricula' => $post['matricula'],
				'id_plantel' => $post['sede'],
				'fecha_registro' => date('Y-m-d H:i:s')
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
	function getRegistro($matricula){
		$this->sql = "SELECT matricula, plantel, s.direccion, TO_CHAR(fecha_registro, 'dd-mm-yyyy') fecha_registro
		FROM registro_taller rt, sede s
		WHERE rt.id_plantel = s.id_plantel
		AND matricula='$matricula'";
		$results = $this->db->query($this->sql);
		return $results->result_array();
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
	function getNombre($matricula){
		$this->sql = "SELECT nombre, ap, am FROM beneficiarios 	WHERE matricula_asignada='$matricula'";
		$results = $this->db_b->query($this->sql);
		return $results->result_array();
	}
}