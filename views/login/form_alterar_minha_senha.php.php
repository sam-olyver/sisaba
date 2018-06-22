<?php if ( ! defined('ABSPATH')) exit;?>
<?php if ( ! defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SISABA | Esqueci minha senha</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo VIEW_DIR;?>plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>SISABA</b>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <section class="content" style="background-color:#fff;">
<form action="" method="POST">
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['userdata']['user_id'];?>" />	
  <div class="form-group">
    <label>Nova senha: </label>
	<input type="password" class="form-control" name="senha" required />
  </div>
  <div class="form-group">
    <label>Confirma senha:</label>
    <input type="password" class="form-control" name="confirma_senha" required />
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" required> Verifiquei e anotei minha nova senha!
    </label>
  </div>
  <div class="form-group">
	<button class="btn btn-default" type="submit" >Salvar</button>
  </div>
 <?php $modelo->update_senha_usuario();?> 
</form>
</section>

    
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo VIEW_DIR;?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo VIEW_DIR;?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo VIEW_DIR;?>plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>

