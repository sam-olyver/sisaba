<?php
/**
 * LoginController 
 *
 * @package 
 * @since 0.1
 */
class LoginController extends MainController
{
    public function index() 
	{
		// Título da página
		$this->title = 'Login';	
		
		// Parametros da função
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();

		if ($this->logged_in) 
			header('Location: home');	
		else
			require '..' . VIEW_DIR . 'pages/examples/login.php';

    } // index
	
	public function esqueciMinhaSenha()
	{
		return; 
		
		$modelo = $this->load_model('user-register/user-register-model');
		require ABSPATH . '/views/login/esqueci_minha_senha.php';
	}
	
	public function trocarSenha()
	{//erro de url 
		require ABSPATH . '/views/login/form_alterar_minha_senha.php';
	}
	
	
} // class LoginController