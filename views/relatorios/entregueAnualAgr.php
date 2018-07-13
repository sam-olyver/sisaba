<?php if ( ! defined('ABSPATH')) exit; ?>
<div class="content">
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
			<div class="form-group" id="nanp">
				<form action="" method="POST">
					<div class="form-group">	
						<label>Alimento</label>
						<select class="form-control" name="alimento">
							<option disabled selected>Selecione uma Opção</option>
							<?php $modelo->getAlimentos(); ?>
						</select>
					</div>
					
					<div class="form-group">
						<label>Ano</label>
						<select class="form-control" name="ano">
							<option disabled selected>Selecione uma Opção</option>
							<?php $modelo->getAnoAbastecimento(); ?>
						</select>
					</div>
					<div class="form-group">
						<label>Abastecimento</label>
						<select class="form-control" name="abastecimento">
							<option disabled selected>Selecione uma Opção</option>
							<?php $modelo->getDataAbastecimento(false); ?>
						</select>
					</div>
					<div class="form-group">
						<label>Agrupamento</label>
						<select class="form-control" name="agrupamento" required>
							<option disabled selected>Selecione uma Opção</option>
							<option value="4">4</option>
							<option value="3">3</option>
							<option value="2">2</option>
							<option value="1">1</option>
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
						<td> <strong>Agrupamento</strong> </td>
						<td> <strong>Alimento</strong> </td>
						<td> <strong>Total</strong> </td>
					</tr>
					<tr>	
						<?php echo $res = $modelo->somaAnualAgr(false); ?>	
					</tr>
				</table>
			</div>
			
		</div>
	</div><!-- /.box-body -->
	
</div>

            
          


