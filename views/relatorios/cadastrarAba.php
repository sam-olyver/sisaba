<?php if ( ! defined('ABSPATH')) exit; ?>
<section class="content">
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Cadastrar Abastecimento</h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
			<div class="form-group" id="nanp">
				<form action="" method="POST">
				  <div class="form-group">
					<label for="exampleInputAno">Ano</label>
					<input type="number" class="form-control" name="ano" required>
				  </div>
				  
				  <div class="form-group">
					<label for="exampleInputAba">Abastecimento</label>
					<input type="text" class="form-control" name="aba" required>
				  </div>
				  
				  <button type="submit" class="btn btn-default">Enviar</button>
				</form>
			</div>          
		</div>
	</div><!-- /.box-body -->
</section>

<?php $modeloAba->inserirAba();?>


	