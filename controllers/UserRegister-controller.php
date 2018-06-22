<?php
/**
 * User register - 
 *
 * @package SISABA
 * @since 0.1
 */
class UserRegisterController extends MainController
{
	
	public $login_required = true;
	public $permission_required = 'adm';
	public $logged_in;
	public $controlador = 'CRUD';

	
    public function index(){	
		if(!$this->logged_in)
			return header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		return;
	} 
	
	public function cadastro()
	{
		if(!$this->logged_in)
			return header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		$this->title = 'Cadastro de Usuários';
		$this->acao = 'novo usuário';
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$modelo = $this->load_model('user-register/user-register-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/user_register/form_register.php';
		require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function listar()
	{
		if(!$this->logged_in)
			return header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		$this->title = 'Usuários';
		$this->acao = 'Editar usuários';
		
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$modelo = $this->load_model('user-register/user-register-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/user_register/form_list.php';
		require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function del()
	{
		if(!$this->logged_in)
			return header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		$modelo = $this->load_model('user-register/user-register-model');
		$modelo->del_user($parametros);
		$this->listar();
	}
	
	public function edit()
	{
		if(!$this->logged_in)
			return header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		
		$this->title = 'Usuários';
		$this->acao = 'Editar usuários';
		
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$modelo = $this->load_model('user-register/user-register-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/user_register/form_update.php';
		require ABSPATH . '/views/_includes/footer.php';		
	}
	
	public function logView()
	{
		if(!$this->logged_in)
			return header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		$this->title = 'Painel de controle - Registro de Log do sistema';
		$this->acao = 'Log';
		
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$modelo = $this->load_model('user-register/user-register-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/user_register/logView.php';
		require ABSPATH . '/views/_includes/footer.php';	
		
	}
		
} // class UserRegisterController