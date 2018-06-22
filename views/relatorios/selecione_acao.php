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
						<label>Abastecimento</label>
						<input type="text" name="abastecimento" class="form-control" placeholder="Ex: 6"  />
					</div>
					
					<div class="form-group">
						<label>Agrupamento</label>
						<input type="number" name="agrupamento" class="form-control" placeholder="Ex: 4" max="4"  />
					</div>
					
					<div class="form-group">
						<label>Ano</label>
						<select class="form-control" name='ano'>
						<option disabled selected>Selecione uma opção</option>
						<?php $modelo->getAnoAbastecimento(); ?>
						</select>
					</div>
					
					<div class="form-group">
						<label>Data de envio (Cadastro do Relatório)</label>
						<input type="date" class="form-control" name="data_envio" />
					</div>
					<div class="form-group">
						<label>Data de entrega (Data dos 10 dias)</label>
						<input type="date" class="form-control" name="data_entrega" />
					</div>
					
					<div class="form-group">	
						<label>Relatório</label>
						<select class="form-control" name="titulo_relatorio">
							<option value="" disabled selected>Selecione uma opção</option>
							<option value="SIMULADO - RELATORIO NECESSIDADE DE NAO PERECIVEIS POR ENTREGA NO AGRUPAMENTO">SIMULADO - RELATORIO NECESSIDADE DE NAO PERECIVEIS POR ENTREGA NO AGRUPAMENTO</option>
							<option value="NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS">NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS</option>
							<option value="RESUMO TOTALIZAÇÃO">RESUMO TOTALIZAÇÃO (MÊS)</option>
							<option value="RESUMO DE TOTALIZAÇÃO">RESUMO DE TOTALIZAÇÃO (ABA)</option>
						</select>
					</div>
					
					<div class="form-group">	
						<label>Tipo de relatório</label>
						<select class="form-control" name="tipo_relatorio">
							<option value="" disabled selected>Selecione uma opção</option>
							<option value="SIMULADO">SIMULADO</option>
							<option value="SEM CONSIDERAR O ESTOQUE DA UNIDADE">SEM CONSIDERAR O ESTOQUE DA UNIDADE</option>
							<option value="CONSIDERANDO O ESTOQUE DA UNIDADE">CONSIDERANDO O ESTOQUE DA UNIDADE</option>
							<option value="POR MÊS">POR MÊS</option>
							<option value="POR ABASTECIMENTO">POR ABASTECIMENTO</option>
						</select>
					</div>
					
					<div class="form-group">	
						<label>Tipo de alimento</label>
						<select class="form-control" name="tipo_alimento">
							<option value="" disabled selected>Selecione uma opção</option>
							<option value="NÃO PERECÍVEL">NÃO PERECÍVEL</option>
							<option value="PERECÍVEL">PERECÍVEL</option>
							<option value="FEIRA">FEIRA</option>
						</select>
					</div>
					
					<div class="form-group">
						<button type="submit" class="btn btn-default">Pesquisar</button>	
					</div>
				</form>
			</div>    
			
			<div class="table-responsive">
				<div class="box-body">
					<table class="table table-bordered">
						<?php $res = $modelo->consulta(); ?>
					</table>
				</div>
			</div>
		</div>
	</div><!-- /.box-body -->
		
</div>

            
          


