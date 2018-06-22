<?php if ( ! defined('ABSPATH')) exit; ?>
<?php	
		$indice = $modelo->obj->getCabecalho();
?>
<div class="content">
	<div class="content">
		<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title"></h3>
				</div>
				
				<div class="box-body">

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
						  <th>AGRUPAMENTO</th>
						  <th>ABASTECIMENTO</th>
						  <th colspan="4">DATA DE ENTREGA</th>
					  </tr>
					  <tr>
						<td colspan="1"></td>
						<td><?php echo $indice['agrupamento'];?></td>
						<td><?php echo $indice['abastecimento'];?></td>
						<td><?php echo $indice['data_entrega']; ?></td>
					  </tr>
					</thead>

					<thead>
					  <tr>
						  <th>Alimento</th>
						  <th>Qtde.Prevista</th>
						  <th>Fator</th>
						  <th>Estq.Unidade</th>
						  <th>Qtde.Ajustada</th>
						  <th>Capacidade</th>
						  <th>Total Embalagem</th>
					  </tr>
					</thead>
					<tbody>

					<?php 
					$alimento = $modelo->obj->getAlimento();
					$qtde_prevista = $modelo->obj->getQuantidade_prevista();
					$fator = $modelo->obj->getFator();
					$estoque_unidate = $modelo->obj->getEstoque_unidade();
					$qtde_Ajustada = $modelo->obj->getQuantidade_ajustada();
					$capacidade = $modelo->obj->getCapacidade();
					$total_embalagem = $modelo->obj->getTotal_embalagem();
					
					$lenght = $modelo->obj->getLenght($modelo->obj->getAlimento());
					for($i = 0; $i < $lenght; $i++)
					{
						echo "<tr>";
						echo "<td>{$alimento[$i]}</td>";
						echo "<td>".number_format($qtde_prevista[$i],2,',','.')."</td>";
						echo "<td>".number_format($fator[$i],2,',','.')."</td>";
						echo "<td>".number_format($estoque_unidate[$i],2,',','.')."</td>";
						echo "<td>".number_format($qtde_Ajustada[$i],2,',','.')."</td>";
						echo "<td>".number_format($capacidade[$i],2,',','.')."</td>";
						echo "<td>".number_format($total_embalagem[$i],2,',','.')."</td>";
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
