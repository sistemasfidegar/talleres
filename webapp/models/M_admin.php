<?php
if (! defined ( 'BASEPATH' ))
	exit ('no se permite el acceso directo al script');

class M_admin extends MY_Model {
	protected $db_b;
	
	function __construct() {
		parent::__construct ();
		$this->db_b = $this->load->database('beneficiarios', TRUE);
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
			$this->db->where('activo', true);
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
	public function save($post = "") {
		if(!empty($post)) {
			$data = array(
					'nombre' => $post['nombre'],
					'apellido_paterno' => $post['paterno'],
					'apellido_materno' => $post['materno'],
					'email' => strtoupper($post['email']),
					'usuario' => $this->security->xss_clean($post['usuario']),
					'password' => $this->security->xss_clean($post['password']),
					'id_plantel' => $post['sede'],
					'perfil' => 'Capturista'
			);
			
			if($this->db->insert('usuario', $data)) {
				return true;
			} else {
				return false;
			}
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
	
	/**
	 * Obtiene todos los datos de un usuario de acuerdo a su identificador.
	 * 
	 * @param  int:$id_usuario  Identificador del usuario a buscar.
	 * 
	 * @return List:usuario    Lista de los datos del usuario encontrado. Null en caso contrario.
	 * 
	 * @since  2016-04-11
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getUsuarioById($id_usuario = "") {
		$results = "";
		
		if(!empty($id_usuario)) {
			$this->db->select('*');
			$this->db->from('usuario');
			$this->db->where('id_usuario', $this->security->xss_clean($id_usuario));
			$query = $this->db->get();
			$usuarioInstance = $query->row_array();
			$query->free_result();
			return $usuarioInstance;
		}
		
		return $results;
	}
	
	/**
	 * Obtiene todos los datos de un taller de acuerdo a su identificador.
	 *
	 * @param  int:$id_taller  Identificador del taller a buscar.
	 *
	 * @return List            Instancia del taller encontrado. Null en caso contrario.
	 *
	 * @since  2016-05-06
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getTallerById($id_taller = "") {
		$results = "";
	
		if(!empty($id_taller)) {
			$this->db->select('*');
			$this->db->from('talleres');
			$this->db->where('id_taller', $id_taller);
			$this->db->where('activo', true);
			$query = $this->db->get();
			$tallerInstance = $query->row_array();
			$query->free_result();
			return $tallerInstance;
		}
	
		return $results;
	}
	
	/**
	 * Obtiene el primer y &uacute;ltimo registro de asistencia a un taller de un Beneficiario en espec&iacute;fico.
	 * 
	 * @param string:$matricula      Matricula a buscar.
	 * @param int:$id_taller         Identificador del taller a buscar.
	 * 
	 * @return List:                 Lista de los datos de la asistencia encontrada. Null en caso contrario.
	 *
	 * @since  2016-05-06
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getAsistenciaByTaller($matricula = "", $id_taller = "") {
		$results = "";
		
		if(!empty($matricula) || !empty($id_taller)) {
			$this->sql = "SELECT to_char(MIN(fecha), 'DD-MM-YYYY HH24:mm:ss') AS inicio, to_char(MAX(fecha), 'DD-MM-YYYY HH24:mm:ss') AS final 
					FROM asistencia 
					WHERE matricula = UPPER('$matricula') 
					AND id_taller = $id_taller;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
		
		return $results;
	}
	
	/**
	 * Obtiene todas las matriculas inscritas en una Sede en espec&iacute;fico.
	 *
	 * @param  int:$id_plantel  Identificador del plantel a buscar.
	 *
	 * @return List:sedes       Lista de los datos de la Sede encontrada. Null en caso contrario.
	 *
	 * @since  2016-05-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getBeneficiariosByPlantel($id_plantel = "") {
		$results = "";
	
		if(!empty($id_plantel)) {
			$this->sql = "SELECT DISTINCT(matricula) 
					FROM registro_taller 
					WHERE espera IS FALSE 
					AND matricula NOT IN (SELECT DISTINCT matricula FROM registro_taller WHERE espera IS TRUE) 
					AND id_plantel = $id_plantel;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene todas las matriculas inscritas en una Sede en espec&iacute;fico.
	 *
	 * @param  int:$id_plantel  Identificador del plantel a buscar.
	 *
	 * @return List:sedes       Lista de los datos de la Sede encontrada. Null en caso contrario.
	 *
	 * @since  2016-07-08
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getBeneficiariosByPlantelRecuperate($id_plantel = "") {
		$results = "";
	
		if(!empty($id_plantel)) {
			$this->sql = "SELECT DISTINCT(matricula)
			FROM registro_taller_recuperate
			WHERE id_plantel = $id_plantel;";
			$results = $this->db->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Obtiene nombre completo, matr&iacute;cula asignada y correo electr&oacute;nico de ls beneficiatios registrados de acuerdo al par&aacute;metro de b&uacute;squeda.
	 *
	 * @param  List:$matriculas      Listado de Matr&iacute;culas asignadas a buscar.
	 *
	 * @return List:Beneficiario     Datos de los beneficiarios a buscar. Null en caso contrario.
	 *
	 * @since  2016-05-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function getNombres($matriculas = ""){
		$results = "";
	
		if(!empty($matriculas)) {
			$this->sql = "SELECT B.nombre, B.ap, B.am, B.matricula_asignada, P.curp, P.email 
			FROM beneficiarios B
			INNER JOIN b_personal P on B.matricula_asignada = P.matricula_asignada
			WHERE B.matricula_asignada IN ($matriculas) 
			ORDER BY B.ap ASC;";
			$results = $this->db_b->query($this->sql);
			return $results->result_array();
		}
	
		return $results;
	}
	
	/**
	 * Verifica si el usuario a crear en la Base de Datos existe o no.
	 * 
	 * @param  String:$usuario     Nombre de usuario a buscar.
	 * 
	 * @return List                Datos del usuario encontrado. Null en caso contrario.
	 * 
	 * @since  2016-04-14
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function checkUser($usuario = "") {
		$usuarioInstance = "";
		
		if(!empty($usuario)) {
			$this->db->select('*');
			$this->db->from('usuario');
			$this->db->where('usuario', $this->security->xss_clean($usuario));
			$query = $this->db->get();
			$usuarioInstance = $query->row_array();
			$query->free_result();
		}
		
		return $usuarioInstance;
	}
	
	/**
	 * Verifica si la contrase&ntilde;a ingresada perteneciente a un usuario en espec&iacute;fico es la correcta.
	 * 
	 * @param String:$actual     Contrase&nacute;a actual registrada en la Base de Datos.
	 * @param int:$id_usuario    Identificador del usuario a buscar.
	 * 
	 * @return boolean           True en caso exitoso. False en caso contrario.
	 * 
	 * @since  2016-04-11
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function checkPass($actual = "", $id_usuario = "") {
		if(!empty($actual) || !empty($id_usuario)) {
			$this->db->select('usuario');
			$this->db->from('usuario');
			$this->db->where('id_usuario', $this->security->xss_clean($id_usuario));
			$this->db->where('password', $this->security->xss_clean($actual));
			$query = $this->db->get();
			$usuarioInstance = $query->row_array();
			$query->free_result();
			
			if (!empty($usuarioInstance)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Actualiza la contrase&ntilde;a de un usuario en espec&iacute;fico.
	 * 
	 * @param String:$password      Nueva contrase&nacute;a.
	 * @param int:$id_usuario       Identificador del usuario a actualizar su contrase&ntilde;a.
	 * 
	 * @return boolean              True en caso de actualizado exitoso. False en caso contrario.
	 * 
	 * @since  2016-04-11
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function changePass($password = "", $id_usuario = "") {
		if(!empty($password) || !empty($id_usuario)) {
			$data = array(
					'password' => $this->security->xss_clean($password),
			);
			
			$this->db->where('id_usuario', $this->security->xss_clean($id_usuario));
			
			if($this->db->update('usuario', $data)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Se encarga de construir una Tabla en HTML con todos los beneficiarios registrados en los talleres que no est&eacute;n en lista de espera de una Sede en espec&iacute;fico.
	 * 
	 * @param  int:$id_plantel   Identificador de la Sede a buscar sus beneficiarios.
	 * @param  int:$taller       Identificador del Taller a buscar.
	 *
	 * @return html              Listado de todos los beneficiarios inscritos. Null en caso contrario.
	 *
	 * @since  2016-05-05
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function builtBeneficiarios($id_plantel = "", $taller = "") {
		$html = '';
		
		if(!empty($id_plantel) && !empty($taller)) {
			set_time_limit(0);
			ini_set('memory_limit', '-1');
			ini_set('max_execution_time', '0');
			ini_set('zlib.output_compression', '0');
			ini_set('implicit_flush', '1');
			ignore_user_abort(true);
			$beneficiarioInstance = $this->getBeneficiariosByPlantel($id_plantel);
			$tallerInstance = $this->getTallerById($taller);
		
			//Se construye la tabla que contiene a todas los beneficiarios de acuerdo a la Sede asignada dadas de alta en la BD
			$html .= '<table id="tbl-export" class="table table-hover list table-condensed table-striped">'.chr(13);
			$html .= '<caption style="text-align: center; font-size: 125%; font-weight: bold;">'. (isset($tallerInstance['taller']) ? $tallerInstance['taller'] : "") .'</caption>'.chr(13);
			$html .= '<thead>'.chr(13);
			$html .= '<tr>'.chr(13);
			$html .= '<th>Matr&iacute;cula</th>'.chr(13);
			$html .= '<th>Nombre Completo</th>'.chr(13);
			$html .= '<th>CURP</th>'.chr(13);
			//$html .= '<th>Email</th>'.chr(13);
			//$html .= '<th>Fecha Hora Entrada</th>'.chr(13);
			//$html .= '<th>Fecha Hora Salida</th>'.chr(13);
			$html .= '<th>TALLER 7</th>'.chr(13);
			$html .= '</tr>'.chr(13);
			$html .= '</thead>'.chr(13);
			$html .= '<tbody class="buscar">'.chr(13);
			
			if(!empty($beneficiarioInstance)){
				$array_beneficiario = array();
				
				foreach ($beneficiarioInstance as $nuevo) {
					$array_beneficiario[] = "'". $nuevo['matricula'] ."'";
				}
				
				$beneficiariosSeperados = implode(",", $array_beneficiario);
				$beneficiarios = $this->getNombres($beneficiariosSeperados);
			
				foreach ($beneficiarios as $row) {
					$asistencia = $this->getAsistenciaByTaller($row['matricula_asignada'], $taller);
					
					$html .= '<tr>'.chr(13);
					$html .= '<td>' . (isset($row['matricula_asignada']) ? $row['matricula_asignada'] : "") . '</td>'.chr(13);
					$html .= '<td>' . (isset($row['ap']) ? $row['ap'] : "") . ' ' . (isset($row['am']) ? $row['am'] : "") . ' ' . (isset($row['nombre']) ? $row['nombre'] : "") . '</td>'.chr(13);
					$html .= '<td>' . (isset($row['curp']) ? $row['curp'] : "") . '</td>'.chr(13);
					//$html .= '<td>' . (isset($row['email']) ? $row['email'] : "") . '</td>'.chr(13);
					//$html .= '<td>' . (isset($asistencia[0]['inicio']) ? $asistencia[0]['inicio'] : "") . '</td>'.chr(13);
					//$html .= '<td>' . (isset($asistencia[0]['final']) ? $asistencia[0]['final'] : "") . '</td>'.chr(13);
					$html .= '<td>' . (isset($asistencia[0]['inicio']) ? "ASISTI&Oacute;" : "") . '</td>'.chr(13);
					$html .= '</tr>';
				}
			}
		
			$html .= '</tbody>'.chr(13);
			$html .= '</table>'.chr(13);
		
			return $html;
		} else {
			return $html;
		}
	}
	
	/**
	 * Se encarga de construir una Tabla en HTML con todos los beneficiarios registrados en los talleres de Recup&eacute;rate.
	 *
	 * @param  int:$id_plantel   Identificador de la Sede a buscar sus beneficiarios.
	 * @param  int:$taller       Identificador del Taller a buscar.
	 *
	 * @return html              Listado de todos los beneficiarios inscritos. Null en caso contrario.
	 *
	 * @since  2016-07-08
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function builtBeneficiariosRecuperate($id_plantel = "", $taller = "") {
		$html = '';
	
		if(!empty($id_plantel) && !empty($taller)) {
			set_time_limit(0);
			ini_set('memory_limit', '-1');
			ini_set('max_execution_time', '0');
			ini_set('zlib.output_compression', '0');
			ini_set('implicit_flush', '1');
			ignore_user_abort(true);
			$beneficiarioInstance = $this->getBeneficiariosByPlantelRecuperate($id_plantel);
			$tallerInstance = $this->getTallerById($taller);
	
			//Se construye la tabla que contiene a todas los beneficiarios de acuerdo a la Sede asignada dadas de alta en la BD
			$html .= '<table id="tbl-export" class="table table-hover list table-condensed table-striped">'.chr(13);
			$html .= '<caption style="text-align: center; font-size: 125%; font-weight: bold;">'. (isset($tallerInstance['taller']) ? $tallerInstance['taller'] : "") .'</caption>'.chr(13);
			$html .= '<thead>'.chr(13);
			$html .= '<tr>'.chr(13);
			$html .= '<th>Matr&iacute;cula</th>'.chr(13);
			$html .= '<th>Nombre Completo</th>'.chr(13);
			$html .= '<th>CURP</th>'.chr(13);
			//$html .= '<th>Email</th>'.chr(13);
			//$html .= '<th>Fecha Hora Entrada</th>'.chr(13);
			//$html .= '<th>Fecha Hora Salida</th>'.chr(13);
			$html .= '<th>TALLER 8</th>'.chr(13);
			$html .= '</tr>'.chr(13);
			$html .= '</thead>'.chr(13);
			$html .= '<tbody class="buscar">'.chr(13);
				
			if(!empty($beneficiarioInstance)){
				$array_beneficiario = array();
	
				foreach ($beneficiarioInstance as $nuevo) {
					$array_beneficiario[] = "'". $nuevo['matricula'] ."'";
				}
	
				$beneficiariosSeperados = implode(",", $array_beneficiario);
				$beneficiarios = $this->getNombres($beneficiariosSeperados);
					
				foreach ($beneficiarios as $row) {
					$asistencia = $this->getAsistenciaByTaller($row['matricula_asignada'], $taller);
						
					$html .= '<tr>'.chr(13);
					$html .= '<td>' . (isset($row['matricula_asignada']) ? $row['matricula_asignada'] : "") . '</td>'.chr(13);
					$html .= '<td>' . (isset($row['ap']) ? $row['ap'] : "") . ' ' . (isset($row['am']) ? $row['am'] : "") . ' ' . (isset($row['nombre']) ? $row['nombre'] : "") . '</td>'.chr(13);
					$html .= '<td>' . (isset($row['curp']) ? $row['curp'] : "") . '</td>'.chr(13);
					//$html .= '<td>' . (isset($row['email']) ? $row['email'] : "") . '</td>'.chr(13);
					//$html .= '<td>' . (isset($asistencia[0]['inicio']) ? $asistencia[0]['inicio'] : "") . '</td>'.chr(13);
					//$html .= '<td>' . (isset($asistencia[0]['final']) ? $asistencia[0]['final'] : "") . '</td>'.chr(13);
					$html .= '<td>' . (isset($asistencia[0]['inicio']) ? "ASISTI&Oacute;" : "") . '</td>'.chr(13);
					$html .= '</tr>';
				}
			}
	
			$html .= '</tbody>'.chr(13);
			$html .= '</table>'.chr(13);
	
			return $html;
		} else {
			return $html;
		}
	}
	
	/**
	 * Se encarga de construir una Tabla en HTML con todos los usuarios existentes en la Base de Datos con el perfil 'Capturista'
	 * 
	 * @return html         Listado de todos los usuarios. Null en caso contrario.
	 * 
	 * @since  2016-04-08
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
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
	
	/**
	 * Elimina a un usuario en espec&iacute;fico de la base de datos.
	 * 
	 * @param int:$post['usuarioId']    Identificador del usuario a eliminar.
	 * 
	 * @return boolean                  True en caso de eliminado exitoso. False en caso contrario.
	 * 
	 * @since  2016-04-08
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function delete($post = "") {
		if(!empty($post)) {
			$this->db->where('id_usuario', $post['usuarioId']);
			
			if($this->db->delete('usuario')) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Edita la informaci&oacute;n personal de un usuario en espec&iacute;fico.
	 * 
	 * @param  List:$post      Atributos del usuario a editar ('nombre', 'apellido paterno', 'apellido materno', 'email' y 'sede').
	 * 
	 * @return boolean         True en caso de editado exitoso. False en caso contrario
	 * 
	 * @since  2016-04-08
	 * @author Ing. Alfredo Mart&iacute;nez Cobos
	 */
	public function edit($post = "") {
		if(!empty($post)) {
			$data = array(
					'nombre' => $post['nombre'],
					'apellido_paterno' => $post['paterno'],
					'apellido_materno' => $post['materno'],
					'email' => strtoupper($post['email']),
					'id_plantel' => $post['sede'],
			);
			
			$this->db->where('id_usuario', $post['usuarioId']);
			
			if($this->db->update('usuario', $data)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}