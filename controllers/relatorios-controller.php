<?php

/**
 * Controlador do modulo de relatorios 
 *
 * @package Mvc
 * @since 1.1
 */

class RelatoriosController extends MainController
{
	public $login_required = true;
	public $permission_required = 'adm';
	public $logged_in;
	public $controlador = 'Relatórios';
	
	
    public function index(){
		return;
	} 
	
    public function novo()
    {
		if(!$this->logged_in)
			header('Location: \sisaba/login');
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		$this->title = 'NOVO RELATÓRIO';
		$this->acao = 'Adicionar Relatório';
		
		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
		require ABSPATH . '/views/relatorios/novo_relatorio.php';
		require ABSPATH . '/views/_includes/footer.php';

    }

    public function preview()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
    	$this->title = 'Novo Relatório';
		$this->acao = 'Preview';

		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$modelo = $this->load_model('relatorios/upload_new_relatorio-model');	
		
		if ($modelo === false) 
		{
			throw new Exception('arquivo invalido');
			return;
		}
		
		$gravar = $this->load_model('relatorios/gravar_relatorio-model');
		$gravar->setObj($modelo);
		
		$gravar->salvarRelatorio();
        
		$header = $modelo->obj->getCabecalho();
		$fk = $header['abastecimento'];
		$aba = $gravar->getAbastecimento($fk);
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/{$modelo->getTable()}.php";
        require ABSPATH . '/views/_includes/footer.php';
		
		
	}
	
	public function novoAba(){
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		$this->title = "ABASTECIMENTO";
		$this->acao = 'Cadastrar';
		
		$parametros = (func_num_args() >= 1) ? func_get_arg(0) : array();
		
		$modeloAba = $this->load_model('relatorios/cadastrar_abastecimento-model');	
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/cadastrarAba.php";
        require ABSPATH . '/views/_includes/footer.php';
		
	}
	
	
    public function acao()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->permission_required = 'all';
		
		$this->title = 'CONSULTAR RELATÓRIOS';
		$this->controlador = 'Relatórios';
		$this->acao = 'Escolha uma Opção de Consulta';

		$parametros = ( func_num_args() >= 1) ? func_get_arg(0) : array();
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/selecione_acao.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function resumoAba()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->permission_required = 'all';
		
		$this->title = 'RESUMO DE ABASTECIMENTO';
		$this->controlador = 'Relatórios';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/resumoAba.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function abastecimentoNp()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->permission_required = 'all'; 
		
		$this->title = 'ABASTECIMENTO NP';
		$this->controlador = 'Relatórios';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/abastecimentoNP.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function abastecimentoPER()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->permission_required = 'all';
		
		
		$this->title = 'ABASTECIMENTO PER';
		$this->controlador = 'Relatórios';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/abastecimentoPER.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function abastecimentoFLVO()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->permission_required = 'all';
		
		
		$this->title = 'ABASTECIMENTO FLVO';
		$this->controlador = 'Relatórios';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/abastecimentoFLVO.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function listagemRelatorios()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->permission_required = 'all';
		
		
		$this->title = 'LISTAGEM DE RELATÓRIOS';
		$this->controlador = 'Relatórios';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/relatorios/listar_relatorios.php';
        require ABSPATH . '/views/_includes/footer.php';	
	}
	public function listagemRelatoriosDesativados()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->permission_required = 'abastecimento';
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		if( ! in_array('abastecimento',$check_user) ){
			require_once '..' . VIEW_DIR . 'pages/examples/404.php';
			return;
		}
		
		
		$this->title = 'LISTAGEM DE RELATÓRIOS';
		$this->controlador = 'Relatórios';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . '/views/relatorios/listar_relatorios_inativo.php';
        require ABSPATH . '/views/_includes/footer.php';	
	}
	
	public function exibirRelatorio()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->title = 'Exibir Relatório';
		$this->acao = 'View';

		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$id = $parametros[0];
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		$update = $this->load_model('relatorios/update_relatorio-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/exibirRelatorio.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function viewRelatorio()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');	
		
		$this->title = 'Exibir Relatório';
		$this->acao = 'View';

		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$id = $parametros[0];
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		$update = $this->load_model('relatorios/update_relatorio-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/exibirRelatorioView.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function exibirEntregueMes()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->title = 'Exibir Relatório';
		$this->acao = 'View';
		 

		$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
		
		$id = $parametros[0];
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		$update = $this->load_model('relatorios/update_relatorio-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/exibirRelatorioMes.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function entregueMes()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->title = 'ENTREGUE MÊS';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/entregue_mes.php";
        require ABSPATH . '/views/_includes/footer.php';
		
	}
	
	public function qtdPrevista()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->title = 'PREVISTO ALTERADO X ESTOQUE POR ABASTECIMENTO';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/qtdPrevista.php";
        require ABSPATH . '/views/_includes/footer.php';
		
	}
	
	public function entregaAnual()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->title = 'ENTREGA ANUAL';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/entregueAnual.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function entregaAnualAba()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->title = 'ENTREGA POR ABASTECIMENTO';
		$this->acao = 'Consultar';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/entregueAnualAba.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
	public function entregaAnualAgr()
	{
		if (!$this->logged_in) 
			header('Location: \sisaba/login');
		
		$this->title = 'ENTREGA POR AGRUPAMENTO';
		$this->acao = 'Consulta';
		
		$modelo = $this->load_model('relatorios/listagem_relatorios-model');
		
		require ABSPATH . '/views/_includes/header.php';
		require ABSPATH . '/views/_includes/menu.php';
        require ABSPATH . "/views/relatorios/entregueAnualAgr.php";
        require ABSPATH . '/views/_includes/footer.php';
	}
	
} // class