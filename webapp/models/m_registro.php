<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'no se permite el acceso directo al script' );

class M_registro extends MY_Model {
	protected $db_b;
	
	function __construct() {
		parent::__construct ();
		$this->db_b = $this->load->database('beneficiarios', TRUE);
	}
	
	/**
	 * M&eacute;todo que obtiene si alg&uacute;n Taller se encuentra Activo o no.
	 *
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 *        
	 * @return cat_ciclo:inicio,fin Fecha de inicio y fecha fin del Taller Activo. Null en caso contrario.
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
	 */
	function getMatricula($dato) {
		$this->sql = "SELECT B.matricula_asignada
		FROM beneficiarios B
		INNER JOIN b_personal P on B.matricula_asignada = P.matricula_asignada
		WHERE  P.matricula_asignada = '$dato' OR P.CURP = '$dato' and B.id_archivo IN (1,2);";
		$results = $this->db_b->query($this->sql);
		return $results->result_array();
	}
	
	function getDisponibilidad() {
		$this->sql = "SELECT S.id_plantel, S.plantel 
				FROM sede S, talleres T, taller_plantel TP, cat_ciclo CC 
				WHERE S.id_plantel = TP.id_plantel 
				AND T.id_taller = TP.id_taller 
				AND T.id_ciclo = CC.id_ciclo 
				AND CC.activo is true AND T.activo is true AND S.activo is true
				GROUP BY S.plantel, S.capacidad, S.total_asistentes, S.id_plantel  
				HAVING (S.capacidad - S.total_asistentes) > 0 
				ORDER BY S.plantel ASC;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
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
	
	function getDisponibilidadByPlantel($id_plantel) {
		$this->sql = "SELECT plantel 
				FROM sede 
				WHERE id_plantel = $id_plantel 
				GROUP BY plantel, capacidad, total_asistentes  
				HAVING (capacidad - total_asistentes) > 0;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	function getTalleres() {
		$this->sql = "SELECT T.taller, T.archivo 
				FROM talleres T, cat_ciclo CC 
				WHERE T.id_ciclo = CC.id_ciclo AND CC.activo is true AND T.activo is true;";	
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
}