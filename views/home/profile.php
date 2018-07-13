<?php if ( ! defined('ABSPATH')) exit; ?>

<section class="content" >
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		
		<div class="box-body">
			<form>
			  <div class="form-group">
				<label for="exampleInputEmail1">Nome </label>
				<input type="text" class="form-control" value="<?php echo $_SESSION['userdata']['user_name']?>" disabled/>
			  </div>
			  <div class="form-group">
				<label for="exampleInputEmail1">Login </label>
				<input type="text" class="form-control" value="<?php echo $_SESSION['userdata']['user']?>" disabled/>
			  </div>
			  
			  <div class="form-group">
				<label for="exampleInputEmail1">Cargo</label>
				<input type="text" class="form-control" value="<?php echo $_SESSION['userdata']['user_cargo']?>" disabled/>
			  </div>
			  <div class="form-group">
				<label for="exampleInputPassword1">RF </label>
				<input type="text" class="form-control" value="<?php echo $_SESSION['userdata']['user_rf']?>" disabled/>
			  </div>
			  <?php foreach($_SESSION['userdata']['user_permissions'] as $permissoes)?>
			  <div class="form-group">
				<label for="exampleInputPassword1">Permissões </label> 
				<input type="text" class="form-control" value="<?php echo $permissoes?>" disabled/>
			  </div>
			  <div class="form-group">
				<a href="/sisaba/home/alterarSenha/" class="btn btn-default">Alterar minha senha</a>
			  </div>
			</form>
		</div>
	</div>			
</section>

	