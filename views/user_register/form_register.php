<?php if ( ! defined('ABSPATH')) exit; 
$modelo->validate_register_form();
?>
<div class="content">

<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		
		<div class="box-body">

			<form action="" method="POST">
			  <div class="form-group">
				<label>Nome: </label>
				<input type="text" class="form-control" name="user_name" required>
			  </div>
			  
			  <div class="form-group">
				<label>Login:</label>
				<input type="text" class="form-control" name="user" required>
			  </div>
			  
			  <div class="form-group">
				<label>Senha:</label>
				<input type="password" class="form-control" name="user_password" required>
			  </div>
			  <div class="form-group">
				<label>Permissões</label>
				<select class="form-control" name="user_permissions" required>
					<option disabled selected>Selecione um opção</option>
					<option value="apenas_consulta">Apenas consulta</option>
					<option value="abastecimento, all, adm">Abastecimento</option>
				</select>
			  </div>
			  <div class="form-group">
				<label>RF: </label>
				<input type="text" class="form-control" name="user_rf" required>
			  </div>
			  <div class="form-group">
				<label>Cargo: </label>
				<input type="text" class="form-control" name="user_cargo" required>
			  </div>
			  <div class="checkbox">
				<label>
				  <input type="checkbox" required>Verifiquei todos os dados, prosseguir!
				</label>
			  </div>
			  <button type="submit" class="btn btn-default">Salvar</button>
			</form>
		</div>
	</div>		
</div>
