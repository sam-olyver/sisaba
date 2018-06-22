<?php if ( ! defined('ABSPATH')) exit; ?>
<div class="content">	
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">RESUMO DE TOTALIZAÇÃO POR MÊS</h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
			<div class="form-group" id="resumo">
				<form action="" method="POST">
					<div class="form-group"> 
						<input type="hidden" value="resumo totalização por mês" name="tipo_relatorio" class="form-control" />
					</div>
					
					<div class="form-group">
						<label>Tipo alimento</label>
						<select name="tipo_alimento" class="form-control" required>
							<option disabled selected>Selecione uma opção</option>	
							<option value="não perecível">NÃO PERECÍVEL</option>	
							<option value="perecível">PERECÍVEL</option>	
							<option value="feira">FEIRA</option>
							<option value="leve leite">Leve Leite</option>							
						</select>	
					</div>	
					<div class="form-group">	
						<label>Ano</label>
						
						<select name="ano" class="form-control">
							<option disabled selected>Selecione uma opção</option>
							<?php $modelo->getAnoAbastecimento(); ?>	
						</select>
					</div>
					<div class="form-group">
					  <label for="exampleInputEmail1">Selecione o mês</label>
						<select class="form-control" name="mes" required >
							<option disabled selected>Selecione uma opção</option>
							<option value="janeiro">Janeiro</option>
							<option value="fevereiro">Fevereiro</option>
							<option value="março">Março</option>
							<option value="abril">Abril</option>
							<option value="maio">Maio</option>
							<option value="junho">Junho</option>
							<option value="Julho">Julho</option>
							<option value="agosto">Agosto</option>
							<option value="setembro">Setembro</option>
							<option value="outubro">Outubro</option>
							<option value="novembro">Novembro</option>
							<option value="dezembro">Dezembro</option>
						</select>
					</div>
					
					<div class="form-group">
						<button type="submit" class="btn btn-default">Pesquisar</button>	
					</div>
							
				</form>
			</div> 
				
			<?php echo $res = $modelo->entregueMes(); ?>
		</div>
	</div><!-- /.box-body -->

	
	
		
</div>

            
          


