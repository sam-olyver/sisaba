<?php if ( ! defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sisaba | <?php echo $this->getTitle();?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>dist/css/skins/_all-skins.min.css">
  
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <script>
	//bloqueia a tecla f5 (refresh) para evitar operações duplicadas, refresh ainda pode ser utilizado pelos outros meios
	window.addEventListener('keydown', function (e) {
		var code = e.which || e.keyCode;
		if (code == 116) e.preventDefault();
		else return true;
	});
  </script>
  
  <style>
	/*estilo para botoes flutuantes*/
	/* Botão pesquisar e Mais Botões */
	
	.btnPesquisar{
		position: fixed;
		float: bottom;
		bottom: 15px;
		z-index: 100;
	}
	
	.btnMaisBotoes{
		position: fixed;
		float: bottom;
		bottom: 15px;
		right: 15px;
		z-index: 100;
	}
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ABA</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SISABA</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Tasks: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- NOME USUARIO TOPO HEADER-->  
              <span class="hidden-xs"><?php echo $_SESSION['userdata']['user_name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo VIEW_DIR; ?>dist/img/user2-160x160.png" class="img-circle" alt="User Image">
                <p><?php echo $_SESSION['userdata']['user_name'] .' - '. $_SESSION['userdata']['user_cargo']; ?></p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu -->
              <li class="user-footer">
              <div class="pull-left">
                  <a href="/sisaba/home/profile/" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="/sisaba/home/sair" class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $this->getTitle();?>
        <small>sisaba</small>
      </h1>
      <ol class="breadcrumb">
        <li><a><i class="glyphicon glyphicon-list-alt"></i><?php echo $this->getController(); ?></a></li>
        <li class="active"><?php echo $this->getAcao(); ?></li>
      </ol>
    </section>