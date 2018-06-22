<?php if ( ! defined('ABSPATH')) exit; ?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      
      <!-- search form -->
     
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header text-center">Menu Principal</li>
        <!-- MENU LATERAL - DROPDOWN 1 -->
         <li class="active treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i><span>Home</span>
			<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
		  <ul class="treeview-menu">
            <li><a href="/sisaba/home/">Início</a></li>
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i><span>Relatórios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class=""><a href="<?php echo HOME_URI;?>relatorios/novo/">Cadastrar Relatório</a></li>
            <li class=""><a href="<?php echo HOME_URI;?>relatorios/novoAba/">Cadastrar Abastecimento</a></li>
          </ul>
        </li>
		
		 <!-- MENU LATERAL - DROPDOWN 4 -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span>Consulta</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo HOME_URI;?>relatorios/acao/">Consultar relatórios</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/listagemRelatorios/0">Listar Relatórios Ativos</a></li>
			<li><a href="<?php echo HOME_URI;?>relatorios/listagemRelatoriosDesativados/0">Listar Relatórios Desativados</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/resumoAba/">Resumo de Abastecimento</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/abastecimentoNp/">Abastecimento NP</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/abastecimentoPER/">Abastecimento PER</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/abastecimentoFLVO/">Abastecimento FLVO</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/entregueMes">Entregue Mês</a></li>
            <li><a href="<?php echo HOME_URI;?>relatorios/qtdPrevista">Previsto Alterado X Estoque Por Aba</a></li>
			<li><a href="<?php echo HOME_URI;?>relatorios/entregaAnual">Entrega Anual de Alimento</a></li>
			<li><a href="<?php echo HOME_URI;?>relatorios/entregaAnualAba">Entrega Anual de Alimento Por ABA</a></li>
			<li><a href="<?php echo HOME_URI;?>relatorios/entregaAnualAgr">Entrega Anual de Alimento Por AGR</a></li>
          </ul>
        </li>

        <!-- MENU LATERAL - DROPDOWN 2 -->
        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-list-alt"></i>
            <span>Saídas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Gerar Saídas de Estoque</a></li>
          </ul>
        </li>
		
		<li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-wrench"></i> <span>Painel de controle</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/sisaba/UserRegister/cadastro">Cadastrar usuário</a></li>
            <li><a href="/sisaba/UserRegister/listar">Listar usuários</a></li>
            <li><a href="/sisaba/UserRegister/logView/0">Log</a></li>
          </ul>
        </li>
		
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>