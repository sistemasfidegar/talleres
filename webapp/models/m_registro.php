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
		return $results->result_array ();
	}
	
	/**
	 * 
	 * @param unknown $dato
	 */
	function getMatricula($dato) {
		$this->sql = "SELECT B.matricula_asignada
		FROM beneficiarios B
		INNER JOIN b_personal P on B.matricula_asignada = P.matricula_asignada
		WHERE  P.matricula_asignada = '$dato' OR P.CURP = '$dato' and B.id_archivo IN (1,2);";
		$results = $this->db_b->query($this->sql);
		return $results->result_array();
	}
}