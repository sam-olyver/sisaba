<?php
/**
 * home 
 *
 * @package SISABA
 * @since 0.1
 */
class HomeController extends MainController
{

	public $login_required = true;
	public $permission_required = 'all';
	//public $logged_in;
	public $controlador = 'Home';

	
    public function index() 
	{
		if (!$this->logged_in) 
			header('Location: login');	
		
		// Título da página
		$this->title = 'Bem vindo';
		$this->acao = 'Início';
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();

		$modelo = $this->load_model('home/home-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require '..' . VIEW_DIR . 'index.php';
		require ABSPATH . '/views/_includes/footer.php';
		
    } // index
	
	public function sair()
	{
		$this->logout(true);	
	}
	
	public function profile()
	{
		if (!$this->logged_in) 
			return;	
		
		$this->title = 'Perfil';
		$this->acao = 'Página do Usuário';
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/home/profile.php';
		require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function alterarSenha()
	{
		if (!$this->logged_in)
			header('Location: login');			
		
		$this->title = 'Perfil';
		$this->acao = 'Alterar senha do Usuário';
		
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$modelo = $this->load_model('user-register/user-register-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/home/form_alterar_senha.php';
		require ABSPATH . '/views/_includes/footer.php';
		
	}
		
} // class HomeController