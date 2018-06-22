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
						<label>Ano</label>
						<select name="ano" class="form-control" required>
							<option disabled selected>Selecione uma opção</option>
							<?php $modelo->getAnoAbastecimento(); ?>
						</select>
					</div>
					
						
					
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<?php $nav = $modelo->resumoAba(); ?>		
						</table>
						<table class="table table-bordered table-hover">
							<?php $modelo->totalizacaoResumoAba(); ?>		
						</table>
					</div>
					
					<div class="form-group btnMaisBotoes">
						<div class="dropup" >
							
							<a href="#" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Navegação 
								<span class="caret"></span>
							</a>
							
							<ul class="dropdown-menu" role="menu">
								<?php if(isset($nav))$modelo->getIndice($nav);?> 
							</ul>
							
						</div>	
					</div>
					
					<div class="form-group btnPesquisar">	
						<button type="submit" class="btn btn-default ">Pesquisar</button>	
					</div>
				</form>
			</div>          
		</div>
	</div><!-- /.box-body -->
</div>

            
          


