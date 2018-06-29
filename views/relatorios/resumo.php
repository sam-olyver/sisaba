<?php if ( ! defined('ABSPATH')) exit; ?>
<?php 	
		$indice = $modelo->obj->getCabecalho();
?>
<div class="content">
	<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"></h3>
			</div>
			
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
						<tr>
							  <th class="text-center" colspan="7"><?php echo $indice['titulo_relatorio'] ; ?></th>
						  </tr>
						  <tr>
							  <th class="text-center" colspan="7"><?php echo $indice['tipo_relatorio']; ?></th>
						  </tr>
						  <tr>   
							<th colspan="1"></th>	
							<th>PERÍODO</th>
							<th>AGRUPAMENTO</th>
							<th colspan="5">ABASTECIMENTO</th>
						  </tr>
						  <tr>
							<td colspan="1"></td>
							<td><?php echo $indice['periodo']; ?></td>
							<td>
							<?php
								if($indice['tipo_relatorio'] != 'POR MÊS')
									echo $indice['agrupamento']; 
							?></td>
							<td colspan="4"><?php echo $aba; ?></td>
						  </tr>
						  <tr>
							  <th>Situação Guia+Item</th>
							  <th>Alimento</th>
							  <th>Total Embalagem Fechada</th>
							  <th>Total Embalagem Fracionada</th>
							  <th>Totalização</th>
						  </tr>
						</thead>

						<tbody>
		<?php 
				$situacao = $modelo->obj->getSituacao();
				$alimento = $modelo->obj->getAlimento();
				$total_embalagem_fechada = $modelo->obj->getTotal_embalagem_fechada();
				$total_embalagem_fracionada = $modelo->obj->getTotal_embalagem_fracionada();
				$totalizacao = $modelo->obj->getTotalizacao();
				
				$lenght = $modelo->obj->getLenght($modelo->obj->getAlimento());
				for($i = 0; $i < $lenght; $i++)
				{
					echo "<tr>";
					echo "<td>{$situacao[$i]}</td>";
					echo "<td>{$alimento[$i]}</td>";
					echo "<td>{$total_embalagem_fechada[$i]}</td>";
					echo "<td>{$total_embalagem_fracionada[$i]}</td>";
					echo "<td>".number_format($totalizacao[$i], 2, ',', '.')."</td>";
					echo "</tr>";
				}
				?>
						</tbody>
					</table>
			</div>
			<div id="acao">
				<a href="/sisaba/relatorios/novo" class="btn btn-default">Voltar</a>
			</div>
		</div><!-- /.box-body -->
	</div><!-- /.box-info -->	
</div>
