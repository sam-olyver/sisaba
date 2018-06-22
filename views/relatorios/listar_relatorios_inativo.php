<?php if ( ! defined('ABSPATH')) exit; ?>
<div class="content">

	<div class="box box-info">
	
		<div class="box-body">
		
			<div class="row table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>ID</th>
						<th>ABA/AGR/ANO</th>
						<th>TÍTULO RELATÓRIO</th>
						<th>TIPO RELATÓRIO</th>
						<th>TIPO ALIMENTO</th>
						<th>MÊS</th>
						<th>STATUS</th>
						<th>AÇÃO</th>
					</tr>
					
					<?php $modelo->listarRelatorios(0);?>	
					
				</table>
			</div>
			
			
		</div><!-- /.box-body -->
			<?php $modelo->paginacao('listagemRelatoriosDesativados');?>
	</div><!-- /.box-info -->
	
</div>          


