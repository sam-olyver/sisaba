<?php if ( ! defined('ABSPATH')) exit; ?>
<div class="content">
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Consultar por Abastecimento e Agrupamento</h3>
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
						<option disabled selected>Selecione uma Opção</option>
						<?php $modelo->getAnoAbastecimento(); ?>
						</select>
					</div>	
			</div>    
		</div>
	</div><!-- /.box-body -->
		
		
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Consultar por Data de Envio</h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
			<div class="form-group">
				<label>Data de Envio (Cadastro do Relatório)</label>
				<input type="date" class="form-control" name="data_envio" />
			</div>		
		</div>
	</div>
	
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Consultar por Data de Entrega</h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->	
			<div class="form-group">
				<label>Data de Entrega (Data dos 10 dias)</label>
				<input type="date" class="form-control" name="data_entrega" />
			</div>
		</div>
	</div>	
	
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Consultar por Tipo de Relatório</h3>
		</div>
		<div class="box-body">
			<!-- Color Picker -->	
			<div class="form-group">	
				<label>Relatório</label>
				<select class="form-control" name="titulo_relatorio">
					<option value="" disabled selected>Selecione uma Opção</option>
					<option value="SIMULADO - RELATORIO NECESSIDADE DE NAO PERECIVEIS POR ENTREGA NO AGRUPAMENTO">Simulado - Relatório Necessidade de Não Perecíveis por Entrega no Agrupamento</option>
					<option value="NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS">Necessidade de Alimentos Não Perecíveis</option>
					<option value="RESUMO TOTALIZAÇÃO">Resumo Totalização por Mês</option>
					<option value="RESUMO DE TOTALIZAÇÃO">Resumo Totalização por Aba</option>
				</select>
			</div>
						
			<div class="form-group">	
				<label>Tipo de Relatório</label>
				<select class="form-control" name="tipo_relatorio">
					<option value="" disabled selected>Selecione uma Opção</option>
					<option value="SIMULADO">Simulado</option>
					<option value="SEM CONSIDERAR O ESTOQUE DA UNIDADE">Sem Considerar o Estoque da Unidade</option>
					<option value="CONSIDERANDO O ESTOQUE DA UNIDADE">Considerando o Estoque da Unidade</option>
					<option value="POR MÊS">Por Mês</option>
					<option value="POR ABASTECIMENTO">Por Aba</option>
				</select>
			</div>
						
			<div class="form-group">	
				<label>Tipo de Alimento</label>
				<select class="form-control" name="tipo_alimento">
					<option value="" disabled selected>Selecione uma Opção</option>
					<option value="NÃO PERECÍVEL">Não Perecível</option>
					<option value="PERECÍVEL">Perecível</option>
					<option value="FEIRA">Feira</option>
				</select>
			</div>
		</div>
	</div>	
	
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
		<div class="form-group">
			<!--<button type="submit" class="btn btn-default">Pesquisar</button>-->
			<button type="submit" class="btn btn-primary btn-lg btn-block">Pesquisar</button>
			</form>			
		</div>
			
	
		</div>
	</div>	
	

	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Relatórios Encontrados</h3>
		</div>
		<div class="box-body">
		<!-- Color Picker -->
		<table class="table table-bordered">
			<?php $res = $modelo->consulta(); ?>
		</table>
			
	
		</div>
	</div>	
	
</div><!-- Div Container -->





            
          


