<?php if ( ! defined('ABSPATH')) exit; ?>
<div class="content">

	<div class="box box-info">
	
		<div class="box-body">
		
			<div class="row table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>DATA</th>
						<th>USUÁRIO</th>
						<th>TÍTULO DA ATIVIDADE</th>
						<th>RESUMO DA ATIVIDADE</th>
					</tr>
					
					<?php $modelo->logView(); ?>	
		
				</table>
			</div>
			
		</div><!-- /.box-body -->
			<?php $modelo->paginacao('logView');?>
	</div><!-- /.box-info -->
	
</div>          


