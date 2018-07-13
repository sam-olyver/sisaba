<?php if ( ! defined('ABSPATH')) exit;?>

<section class="content">

<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		
		<div class="box-body">
			<form action="" method="POST">
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['userdata']['user_id'];?>" />	
  <div class="form-group">
    <label>Nova senha </label>
	<input type="password" class="form-control" name="senha" required />
  </div>
  <div class="form-group">
    <label>Confirma senha </label>
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

		</div>
</div>		


</section>

