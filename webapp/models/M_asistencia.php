<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'no se permite el acceso directo al script' );

class M_asistencia extends MY_Model {
	protected $db_b;
	
	function __construct() {
		parent::__construct ();
		$this->db_b = $this->load->database('beneficiarios', TRUE);
	}
	
	/**
	 * M&eacute;todo que obtiene si alg&uacute;n Taller se encuentra Activo o no.
	 *     
	 * @return cat_ciclo:inicio,fin Fecha de inicio y fecha fin del Taller Activo. Null en caso contrario.
	 */
	function getCicloActivo() {
		$this->sql = "SELECT  id_ciclo, to_char(fecha_inicio, 'DD-MM-YYYY') as inicio, to_char(fecha_fin, 'DD-MM-YYYY') as fin FROM cat_ciclo WHERE activo is true;;";
		$results = $this->db->query($this->sql);
		return $results->result_array ();
	}
	
	/**
	 * @param unknown $dato
	 */
	function getMatricula($dato) {
		$this->sql = "SELECT matricula	FROM registro_taller WHERE  matricula = '$dato';";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	function getTaller($hoy){
		$this->sql="select * from talleres WHERE fecha_inicio='$hoy' and activo is true";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	function registroDuplicado($idtaller, $matricula){
		$this->sql="SELECT matricula from asistencia WHERE id_taller=$idtaller and matricula='$matricula'";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	function insertaAsistencia($idtaller, $matricula, $usuario){
			$this->sql = "INSERT INTO asistencia(id_taller, matricula, id_usuario) VALUES($idtaller, '$matricula', $usuario) RETURNING matricula";
			$results = $this->db->query($this->sql);
			return $results;
	}
}