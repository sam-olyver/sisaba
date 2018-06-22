<?php 
/**
 * Classe para registros de usuários
 *
 * @package Mvc
 * @since 0.1
 */

class UserRegisterModel extends UserLogin
{

	/**
	 * $form_data
	 *
	 * Os dados do formulário de envio.
	 *
	 * @access public
	 */	
	public $form_data;

	/**
	 * $form_msg
	 *
	 * As mensagens de feedback para o usuário.
	 *
	 * @access public
	 */	
	public $form_msg;

	/**
	 * $db
	 *
	 * O objeto da nossa conexão PDO
	 *
	 * @access public
	 */
	public $db;

	/**
	 * Construtor
	 * 
	 * Carrega  o DB.
	 *
	 * @since 0.1
	 * @access public
	 */
	public function __construct( $db = false ) {
		$this->db = $db;
	}

	/**
	 * Valida o formulário de envio
	 * 
	 * Este método pode inserir ou atualizar dados dependendo do campo de
	 * usuário.
	 *
	 * @since 0.1
	 * @access public
	 */
	public function validate_register_form () {
	
		// Configura os dados do formulário
		$this->form_data = array();
		
		// Verifica se algo foi postado
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty ( $_POST ) ) {
		
			// Faz o loop dos dados do post
			foreach ( $_POST as $key => $value ) {
			
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
				// Nós não permitiremos nenhum campos em branco
				if ( empty( $value ) ) {
					
					// Configura a mensagem
					$this->form_msg = '<p class="form_error">There are empty fields. Data has not been sent.</p>';
					
					// Termina
					return;
					
				}			
			
			}
		
		} else {
		
			// Termina se nada foi enviado
			return;
			
		}
		
		// Verifica se a propriedade $form_data foi preenchida
		if( empty( $this->form_data ) ) {
			return;
		}
		
		// Verifica se o usuário existe
		$db_check_user = $this->db->query (
			'SELECT * FROM `users` WHERE `user` = ?', 
			array( 
				chk_array( $this->form_data, 'user')		
			) 
		);
		
		// Verifica se a consulta foi realizada com sucesso
		if ( ! $db_check_user ) {
			$this->form_msg = '<p class="form_error">Internal error.</p>';
			return;
		}
		
		// Obtém os dados da base de dados MySQL
		$fetch_user = $db_check_user->fetch();
		
		// Configura o ID do usuário
		$user_id = $fetch_user['user_id'];
		
		// Precisaremos de uma instância da classe Phpass
		// veja http://www.openwall.com/phpass/
		$password_hash = new PasswordHash(8, FALSE);
		
		// Cria o hash da senha
		$password = $password_hash->HashPassword( $this->form_data['user_password'] );
		
		// Verifica se as permissões tem algum valor inválido: 
		// 0 a 9, A a Z e , . - _
		if ( preg_match( '/[^0-9A-Za-z\,\.\-\_\s ]/is', $this->form_data['user_permissions'] ) ) {
			$this->form_msg = '<p class="form_error">Use just letters, numbers and a comma for permissions.</p>';
			return;
		}		
		
		// Faz um trim nas permissões
		$permissions = array_map('trim', explode(',', $this->form_data['user_permissions']));
		
		// Remove permissões duplicadas
		$permissions = array_unique( $permissions );
		
		// Remove valores em branco
		$permissions = array_filter( $permissions );
		
		// Serializa as permissões
		$permissions = serialize( $permissions );
		
		
		// Se o ID do usuário não estiver vazio, atualiza os dados
		if ( ! empty( $user_id ) ) {

			$query = $this->db->update('users', 'user_id', $user_id, array(
				'user_password' => $password, 
				'user_name' => chk_array( $this->form_data, 'user_name'), 
				'user_session_id' => md5(time()), 
				'user_permissions' => $permissions,
				'user_rf' => chk_array($this->form_data, 'user_rf'),
				'user_cargo' => chk_array($this->form_data, 'user_cargo') 	
			));
			
			$this->logRegister($user_id, 'usuario alterado');
			
			// Verifica se a consulta está OK e configura a mensagem
			if ( ! $query ) {
				$this->form_msg = '<p class="form_error">Internal error. Data has not been sent.</p>';
				
				// Termina
				return;
			} else {
				$this->form_msg = '<p class="form_success">User successfully updated.</p>';
				
				
				// Termina
				return;
			}
		// Se o ID do usuário estiver vazio, insere os dados
		} else {
		
			// Executa a consulta 
			$query = $this->db->insert('users', array(
				'user' => chk_array( $this->form_data, 'user'), 
				'user_password' => $password, 
				'user_name' => chk_array( $this->form_data, 'user_name'), 
				'user_session_id' => md5(time()), 
				'user_permissions' => $permissions,
				'user_rf' => chk_array($this->form_data, 'user_rf'),
				'user_cargo' => chk_array($this->form_data, 'user_cargo') 		
			));
			
			$this->logRegister($this->db->pdo->lastInsertId(), 'usuario cadastrado');
			
			// Verifica se a consulta está OK e configura a mensagem
			if ( ! $query ) {
				$this->form_msg = '<p class="form_error">Internal error. Data has not been sent.</p>';
				
				// Termina
				return;
			} else {
				$this->form_msg = '<p class="form_success">User successfully registered.</p>';
				
				// Termina
				return;
			}
		}
	} // validate_register_form
	
	/**
	 * Obtém os dados do formulário
	 * 
	 * Obtém os dados para usuários registrados
	 *
	 * @since 0.1
	 * @access public
	 */
	  
	public function get_register_form ( $user_id = false ) {
	
		// O ID de usuário que vamos pesquisar
		$s_user_id = false;
		
		// Verifica se você enviou algum ID para o método
		if ( ! empty( $user_id ) ) {
			$s_user_id = (int)$user_id;
		}
		
		// Verifica se existe um ID de usuário
		if ( empty( $s_user_id ) ) {
			return;
		}
		
		// Verifica na base de dados
		$query = $this->db->query('SELECT * FROM `users` WHERE `user_id` = ?', array( $s_user_id ));
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->form_msg = '<p class="form_error">Usuário não existe.</p>';
			return;
		}
		
		// Obtém os dados da consulta
		$fetch_userdata = $query->fetch();
		
		// Verifica se os dados da consulta estão vazios
		if ( empty( $fetch_userdata ) ) {
			$this->form_msg = '<p class="form_error">User do not exists.</p>';
			return;
		}
		
		// Configura os dados do formulário
		foreach ( $fetch_userdata as $key => $value ) {
			$this->form_data[$key] = $value;
		}
		
		// Por questões de segurança, a senha só poderá ser atualizada
		$this->form_data['user_password'] = null;
		
		// Remove a serialização das permissões
		$this->form_data['user_permissions'] = unserialize($this->form_data['user_permissions']);
		
		// Separa as permissões por vírgula
		$this->form_data['user_permissions'] = implode(',', $this->form_data['user_permissions']);
	} // get_register_form
	
	/**
	 * Apaga usuários
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function del_user ( $parametros = array() ) {

		// O ID do usuário
		$user_id = null;
		
		$user_id = chk_array( $parametros, 0 );
		
		$query = $this->db->query('SELECT * FROM `users` WHERE `user_id` = ?', array( $user_id ));
		
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		
		// Verifica se o ID não está vazio
		if ( !empty( $user_id ) ) {
		
			// O ID precisa ser inteiro
			$user_id = (int)$user_id;
			
			// Deleta o usuário
			$query = $this->db->delete('users', 'user_id', $user_id);
			
			$this->logRegister($user_id, "usuario {$data[0]['user_name']} deletado");
			
			return;
		}
	} // del_user
	
	/**
	 * Obtém a lista de usuários
	 * 
	 * @since 0.1
	 * @access public
	 */
	public function get_user_list() {
	
		// Simplesmente seleciona os dados na base de dados 
		$query = $this->db->query('SELECT * FROM `users` ORDER BY user_id DESC');
		
		// Verifica se a consulta está OK
		if ( ! $query ) {
			return array();
		}
		// Preenche a tabela com os dados do usuário
		return $query->fetchAll();
	} // get_user_list
	
	public function update_senha_usuario()
	{
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !$_POST == null)
		{
			if(isset($_POST['senha']) && isset($_POST['confirma_senha']))
			{
				foreach ( $_POST as $key => $value ) {
				
					// Configura os dados do post para a propriedade $form_data
					$this->form_data[$key] = $value;
					
					// Nós não permitiremos nenhum campos em branco
					if ( empty( $value ) ) {
						
						// Configura a mensagem
						$this->form_msg = '<p class="form_error">There are empty fields. Data has not been sent.</p>';
						
						// Termina
						return;
						
					}			
				}	
			}
			
			if($this->form_data['senha'] == $this->form_data['confirma_senha'])
			{
				$password_hash = new PasswordHash(8, FALSE);
				$password_hash->HashPassword($this->form_data['senha']); 
				$password_hash->HashPassword($this->form_data['confirma_senha']);
				
				$query = $this->db->update('users', 'user_id', $this->form_data['user_id'], array(
				'user_password' => $password_hash->HashPassword($this->form_data['confirma_senha'])
				));
			
				// Verifica se a consulta está OK e configura a mensagem
				if( ! $query )
				{
					echo $this->form_msg = '<p class="text-danger">Internal error. Data has not been sent.</p>';
					$this->form_data = null;
					return;
				} 
				else 
				{
					echo $this->form_msg = '<p class="text-success">senha atualizada com sucesso.</p>';
					$this->form_data = null;
					return;
				}
			}
			else
			{
				echo $this->form_msg = '<p class="text-danger">senhas não conferem</p>';
				$this->form_data = null;
				return;
			}
						
		}
		return;	
	}//update_senha_usuario()
	
	public function validate_user_data()
	{
		if( 'POST' == $_SERVER['REQUEST_METHOD'] && !$_POST == null)
		{
			foreach ( $_POST as $key => $value ) 
			{
				// Configura os dados do post para a propriedade $form_data
				$this->form_data[$key] = $value;
				
				// Nós não permitiremos nenhum campos em branco
				if ( empty( $value ) )
				{
					// Configura a mensagem
					echo $this->form_msg = '<p class="text-danger">preencha todos os campos.</p>';
					// Termina
					return;
				}			
			}

			$consulta = $this->db->query("SELECT * FROM users WHERE user = ? AND user_rf = ? AND user_cargo = ?",
			array($this->form_data['userdata']['user'], $this->form_data['userdata']['user_rf'], $this->form_data['userdata']['user_cargo'])
			);
			
			
			if($consulta->rowCount())
			{
				echo $this->form_msg = "<p class='text-success'>Usuário encontrado, dados conferem</p>";
			}
			else
			{
				echo $this->form_msg = "<p class='text-danger'>Usuário não encontrado, dados não conferem</p>";
			}
			
		}
		
		
	}//validate_user_data()
	
	public function getUser($id)
	{
		$consulta = $this->db->query("SELECT user FROM users WHERE user_id = ?", array($id));
		return $consulta->fetchAll(PDO::FETCH_ASSOC);
	}
	
	private function logRegister($fk, $acao)
	{	
			date_default_timezone_set('America/Sao_Paulo');
			$dataLocal = date("F j, Y, g:i a", time());
			$this->db->insert('log', array(
				'data_registro' => date('Y-m-d'),
				'titulo_atividade' => "$acao",
				'resumo_atividade' => "usuario ID: #$fk $acao em $dataLocal",
				'user_action' => $_SESSION['userdata']['user_id'],
				'fk_usuarios' => $fk	
			));
			
			return $fk;
	}
	
	public function inverte_data( $data = null ) {
	
		// Configura uma variável para receber a nova data
		$nova_data = null;
		
		// Se a data for enviada
		if ( $data ) {
		
			// Explode a data por -, /, : ou espaço
			$data = preg_split('/\-|\/|\s|:/', $data);
			
			// Remove os espaços do começo e do fim dos valores
			$data = array_map( 'trim', $data );
			
			// Cria a data invertida
			$nova_data .= chk_array( $data, 2 ) . '-';
			$nova_data .= chk_array( $data, 1 ) . '-';
			$nova_data .= chk_array( $data, 0 );
			
			// Configura a hora
			if ( chk_array( $data, 3 ) ) {
				$nova_data .= ' ' . chk_array( $data, 3 );
			}
			
			// Configura os minutos
			if ( chk_array( $data, 4 ) ) {
				$nova_data .= ':' . chk_array( $data, 4 );
			}
			
			// Configura os segundos
			if ( chk_array( $data, 5 ) ) {
				$nova_data .= ':' . chk_array( $data, 5 );
			}
		}
		
		// Retorna a nova data
		return $nova_data;
	
	} // inverte_data
	
	public function logView()
	{
		
		$url = $_GET['path'];	
		
		// Limpa os dados
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
					
		// Cria um array de parâmetros
		$url = explode('/', $url);
		
		$pagina_atual = $url[2];
		$limite_pag = 10;
		$offset = $limite_pag * $pagina_atual;
		
			
		#QUERY
		$stm = 
		"
		SELECT log.data_registro, users.user_name, log.titulo_atividade, log.resumo_atividade  FROM log 
		INNER JOIN users on log.user_action = users.user_id 
		ORDER BY log.id_ultimos DESC
		LIMIT " .$limite_pag. " OFFSET " .$offset ."
		" ;
		
		$query = $this->db->query($stm, array());
		
		foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $data )
		{
			echo "
				<tr>
					<td>{$this->inverte_data($data['data_registro'])}</td>
					<td>{$data['user_name']}</td>
					<td>{$data['titulo_atividade']}</td>
					<td><textarea class='form-control' rows='3' style='resize: none' readonly>{$data['resumo_atividade']}</textarea></td>
				</tr>
				 ";
		}//foreach()
		
		return;
		
	}
	
	public function paginacao($pag = null)
	{
		if( empty($pag) )
			return;
		
		$url = $_GET['path'];	
		
		// Limpa os dados
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
					
		// Cria um array de parâmetros
		$url = explode('/', $url);
				
		$pagina_atual = $url[2];
		$limite_pag = 10;
		$offset = $limite_pag * $pagina_atual;
						
		$query = $this->db->query("SELECT COUNT(id_ultimos) as cont FROM log", array(null) );
				
		$indice = $query->fetchAll(PDO::FETCH_ASSOC);
				
		$cont = $indice[0]['cont'];
				
		$max = ceil($cont / $limite_pag);
		
		#PAGINAÇÃO
			echo "<div class='container text-center'>";
			echo "<ul class='pagination'>";
			
			if($pagina_atual >= 1)
				$prev = $pagina_atual - 1;
			else
				$prev = 1;
			
			if($pagina_atual < 1)
				echo "<li style='display:none;'><a href='". HOME_URI ."UserRegister/$pag/$prev'></a></li>";
			else
				echo "<li><a href='" . HOME_URI ."UserRegister/$pag/$prev'><i class='material-icons'>«</i></a></li>";
				$i = ($pagina_atual < $limite_pag ) ? 0 : $pagina_atual - 5;
				for($i;$i < $max; $i++)
				{
					if( (($i-$pagina_atual) / 10) >= 1)
						break;
					
					if($i == $pagina_atual)
						echo "<li class='active'><a href='".HOME_URI."UserRegister/$pag/$i'>$i</a></li>"; 
					else
						echo "<li><a href='".HOME_URI."UserRegister/$pag/$i'>$i</a></li>";
														
				}
			
			$next = $pagina_atual + 1;
			
			if($pagina_atual >= $max)
				echo "<li style='display:none;'><a href='#'><i>»</i></a></li>";
			else
				echo "<li><a href='".HOME_URI."UserRegister/$pag/$next'><i>»</i></a></li>";	
			
			
			echo "</ul>";
			echo "</div>";
	}//paginacao()
	
}//class UserRegisterModel