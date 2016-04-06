<?php
if (! defined ( 'BASEPATH' ))
	exit ('no se permite el acceso directo al script');

class M_admin extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Funci&oacute;n que realiza la tarea de verificar que el usuario inicie correctamente sesi&oacute;n en el Sistema.
	 *
	 * @param  post[crudRegistro]:Array()     Contiene los par&aacute;metros 'name' y 'password' para la inserci&oacute;n de datos.
	 *
	 * @return boolean                        True en caso de iniciar sesi&oacute;n correctamente. False en caso contrario.
	 * 
	 * @since  2016-04-06
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function login() {
		// Recuperando información de POST
		$crudAuth = $this->input->post('crudAuth', true);
		// Validando valores de formulario
		if(empty($crudAuth['name']) || empty($crudAuth['password'])) {
			return false;
		}
			
		// Verificación de datos de usuario
		if (!empty($crudAuth) AND isset($crudAuth['name']) AND isset($crudAuth['password'])) {
			// Autenticación por Base de datos
			$this->db->select('*');
			$this->db->from('usuario');
			$this->db->where('usuario', $this->security->xss_clean($crudAuth['name']));
			$this->db->where('password', $this->security->xss_clean($crudAuth['password']));
			$query = $this->db->get();
			$usuarioInstance = $query->row_array();
			$query->free_result();
	
			if (!empty($usuarioInstance)) { // Usuario de base de datos existente
				$this->session->set_userdata('CRUD_AUTH', $usuarioInstance);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Realiza el guardado del nuevo usuario.
	 * 
	 * @param Array():$post    Contiene los atributos de 'nombre', 'paterno', 'materno', 'email', 'usuario', 'password' y 'sede'.
	 * 
	 * @return boolean         True en caso de guardado exitoso. False en caso contrario.
	 * 
	 * @since  2016-04-06
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function save($post) {
		$data = array(
				'nombre' => $post['nombre'],
				'apellido_paterno' => $post['paterno'],
				'apellido_materno' => $post['materno'],
				'email' => $post['email'],
				'usuario' => $post['usuario'],
				'password' => $post['password'],
				'id_plantel' => $post['sede'],
				'perfil' => 'Capturista'
		);
		
		if($this->db->insert('usuario', $data)) {
			return true;
		} else {
			return false;
		}
	}
}