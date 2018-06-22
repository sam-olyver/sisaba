<?php if ( ! defined('ABSPATH')) exit; ?>
<div class="content">
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">CONSULTAR RELATÓRIOS DE ABASTECIMENTO</h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
			<div class="form-group" id="nanp">
				<form action="" method="POST">
					<div class="form-group">	
						<label>Alimento</label>
						<select class="form-control" name="alimento">
							<option disabled selected>Selecione uma opção</option>
							<?php $modelo->getAlimentos(); ?>
						</select>
					</div>
					
					<div class="form-group">
						<label>Ano</label>
						<select class="form-control" name="ano">
							<option disabled selected>Selecione uma opção</option>
							<?php $modelo->getAnoAbastecimento(); ?>
						</select>
					</div>
					<div class="form-group">
						<label>Abastecimento</label>
						<select class="form-control" name="abastecimento">
							<option disabled selected>Selecione uma opção</option>
							<?php $modelo->getDataAbastecimento(); ?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-default">Pesquisar</button>	
					</div>
				</form>
			</div> 
			
			<div class="box-body">
				<label>Resultado</label>
				<table class="table table-bordered">
					<tr>
						<td> <strong>Abastecimento</strong> </td>
						<td> <strong>Alimento</strong> </td>
						<td> <strong>Total</strong> </td>
					</tr>
					<tr>	
						<?php echo $res = $modelo->somaAnualAba(false); ?>	
					</tr>
				</table>
			</div>
			
		</div>
	</div><!-- /.box-body -->
	
</div>

            
          


