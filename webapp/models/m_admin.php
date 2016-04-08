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
			$this->db->where('password', sha1($this->security->xss_clean($crudAuth['password'])));
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
				'usuario' => $this->security->xss_clean($post['usuario']),
				'password' => sha1($this->security->xss_clean($post['password'])),
				'id_plantel' => $post['sede'],
				'perfil' => 'Capturista'
		);
		
		if($this->db->insert('usuario', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Obtiene todos los usuarios dados de alta en la Base de Datos que no tengan el perfil de 'Programador'.
	 * 
	 * @return List        Listado de usuarios obtenidos. Null en caso contrario.
	 * 
	 * @since  2016-04-07
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getUsuarios() {
		$this->sql = "SELECT U.*, S.plantel, S.id_plantel 
				FROM usuario U, sede S 
				WHERE U.id_plantel = S.id_plantel 
				AND U.perfil <> 'Programador' 
				ORDER BY U.apellido_paterno ASC;";
		$results = $this->db->query($this->sql);
		return $results->result_array();
	}
	
	public function builtUsuarios() {
		$html = '';
		$usuarioInstance = $this->getUsuarios();
		
		//Se construye la tabla que contiene a todas las personas dadas de alta en la BD
		$html .= '<table id="tbl-export" class="table table-hover list table-condensed table-striped">'.chr(13);
		$html .= '<thead>'.chr(13);
		$html .= '<tr>'.chr(13);
		$html .= '<th>Nombre Completo</th>'.chr(13);
		$html .= '<th>Email</th>'.chr(13);
		$html .= '<th>Sede</th>'.chr(13);
		$html .= '<th>Usuario</th>'.chr(13);
		$html .= '<th>Perfil</th>'.chr(13);
		$html .= '<th>Acciones</th>'.chr(13);
		$html .= '</tr>'.chr(13);
		$html .= '</thead>'.chr(13);
		$html .= '<tbody class="buscar">'.chr(13);
		
		foreach ($usuarioInstance as $row) {
			$html .= '<tr>'.chr(13);
			$html .= '<td>' . (isset($row['nombre']) ? $row['nombre'] : "") . ' '. (isset($row['apellido_paterno']) ? $row['apellido_paterno'] : "") . ' ' . (isset($row['apellido_materno']) ? $row['apellido_materno'] : "") . '</td>'.chr(13);
			$html .= '<td>' . (isset($row['email']) ? $row['email'] : "") . '</td>'.chr(13);
			$html .= '<td>' . (isset($row['plantel']) ? $row['plantel'] : "") . '</td>'.chr(13);
			$html .= '<td>' . (isset($row['usuario']) ? $row['usuario'] : "") . '</td>'.chr(13);
			$html .= '<td>' . (isset($row['perfil']) ? $row['perfil'] : "") . '</td>'.chr(13);
			$html .= '<td><button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" data-user="' . $row['id_usuario'] . '" data-nombre="' . $row['nombre'] . '" data-paterno="' . $row['apellido_paterno'] . '" data-materno="' . $row['apellido_materno'] . '" data-email="' . $row['email'] . '" data-plantel="' . $row['plantel'] . '" data-idplantel="' . $row['id_plantel'] . '"><span class="glyphicon glyphicon-edit" aria-hidden="true">Editar</span></button>'.chr(13);
			$html .= '<td><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" data-user="' . $row['id_usuario'] . '" data-whatever="'. $row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'] . '"><span class="glyphicon glyphicon-trash" aria-hidden="true">Eliminar</span></button>'.chr(13);
			$html .= '</tr>';
		}
		
		$html .= '</tbody>'.chr(13);
		$html .= '</table>'.chr(13);
		
		return $html;
	}
	
	public function delete($post) {
		$this->db->where('id_usuario', $post['usuarioId']);
		
		if($this->db->delete('usuario')) {
			return true;
		} else {
			return false;
		}
	}
	
	public function edit($post) {
		$data = array(
				'nombre' => $post['nombre'],
				'apellido_paterno' => $post['paterno'],
				'apellido_materno' => $post['materno'],
				'email' => $post['email'],
				'id_plantel' => $post['sede'],
		);
		
		$this->db->where('id_usuario', $post['usuarioId']);
		
		if($this->db->update('usuario', $data)) {
			return true;
		} else {
			return false;
		}
	}
}