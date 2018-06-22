<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="content">
	<div class="box box-info">
	
		<div class="box-body">
			<?php $modelo->exibirRelatorioSerializado($id); ?>	
		</div><!-- /.box-body -->
			
	</div><!-- /.box-info -->
	
	
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Header Relatório</h3>
		</div>
		
		<div class="box-body">
			<table class='table table-responsive'>
				<tr>
					<th>ID</th>
					<th>Abastecimento</th>
					<th>Agrupamento</th>
					<th>Data entrega</th>
					<th>Tipo Alimento</th>
					<th>Mês</th>
					<th>Ano</th>
					<th>Data Envio</th>
					<th>Status</th>
				</tr>
			
			<?php 
			$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
			if( ! in_array('abastecimento',$check_user) ){
				$btnState = 'disabled';
			}
			else
			{
				$btnState = null; 				
			}
			$modelo->viewHeader($id); 
			?>
			</table>
		</div>
		
		<div id="acao">
			<a href="/sisaba/relatorios/listagemRelatorios/0" class="btn btn-default">Voltar</a>
			<form method="POST" style="display: inline;">
				<input type="hidden" name="check" value="true" />
				<input type="hidden" name="status" value="1" />
				<button type="submit" class="btn btn-default" >Ativar</button>
			</form>
			
			<form method="POST" style="display: inline;">
				<input type="hidden" name="check" value="false" />
				<input type="hidden" name="status" value="0" />
				<button type="submit" class="btn btn-default" >Desativar</button>
			</form>
			
			<?php $update->updateStatus($id); ?>
		</div>
	</div>
	
</div>
         

 		
