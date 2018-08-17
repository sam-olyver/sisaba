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
			<table class="table table-hover">
				<thead>
				<tr>
				  <th colspan="7" class="text-center"><?php echo $indice['titulo_relatorio'] ; ?></th>
				</tr>
				  <tr>
					  <th>AGRUPAMENTO</th>
					  <th>DATA ENTREGA</th>
					  <th>ALIMENTO</th>
					  <th>QTDE NECESSARIA</th>
					  <th>QTDE AJUSTADA</th>
					  <th>QTDE EMBALAGEM</th>
				  </tr>
				</thead>

				<tbody>
				  
				<?php 
				$agrupamento = $modelo->obj->getAgrupamentos();
				$data_entrega = $modelo->obj->getDatas_entrega();
				$alimento = $modelo->obj->getAlimento();
				$qtde_necessaria = $modelo->obj->getQuantidade_necessaria();
				$qtde_ajustada = $modelo->obj->getQuantidade_ajustada();
				$qtde_embalagem = $modelo->obj->getQuantidade_embalagem();
				
				$lenght = $modelo->obj->getLenght($modelo->obj->getAlimento());
				for($i = 0; $i < $lenght; $i++)
				{
					echo "<tr>";
					echo "<td>{$agrupamento[$i]}</td>";
					echo "<td>{$data_entrega[$i]}</td>";
					echo "<td>{$alimento[$i]}</td>";
					echo "<td>".number_format($qtde_necessaria[$i],2,',
					','.')."</td>";
					echo "<td>".number_format($qtde_ajustada[$i],2,',','.')."</td>";
					echo "<td>".number_format($qtde_embalagem[$i],2,',','.')."</td>";
					echo "</tr>";
				}
				?>  
				</tbody>
			</table>
		  
			<div id="acao">
							
					<a href="/relatorios/novo" class="btn btn-default">Voltar</a>
			</div>
		</div><!-- /.box-body -->
</div><!-- /.box-info -->		


</div>
