<?php
class ListagemRelatoriosModel extends MainModel
{
	public $db;
	public $relatorio_view;
	public $file_data;
	private $relatorio_total;
	
	public function __construct(){
		$this->db = new MyPdo();
	}
	
	public function getDataAbastecimento($arg = true)
	{
		$query  = $this->db->query("SELECT * FROM abastecimento", array());
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
		{
			if($arg){
			echo "<option value='{$res['id_abastecimento']}'>{$res['aba']}/{$res['ano']}</option>";
			}
			else
			{
				echo "<option value='{$res['abastecimento']}'>{$res['aba']}/{$res['ano']}</option>";
			}
		}
	}
	
	public function getAnoAbastecimento()
	{
		$query = $this->db->query("SELECT DISTINCT ano FROM abastecimento");
		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $year)
		{
			echo "<option value='{$year['ano']}'>{$year['ano']}</option>";
		}
		
	}
	
	public function getAlimentos()
	{
		$stm = "SELECT alimento FROM alimentos ";
		$query = $this->db->query($stm, array(null));

		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $food)
		{
			echo "<option value='{$food['alimento']}'>{$food['alimento']}</option>";
		}		
	}
	
	public function setIndice($parametro)
	{
		$lista = 
		"
		<ul class='nav nav-pills nav-stacked'>
		$parametro
		</ul>
		";
		
		return $lista;
	}
	
	public function getIndice($par = null)
	{
		if( empty($par) )
			return;
		
		echo $par;
	}
	
	public function somaAnual()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) )
		{
			$arg1 = $_POST['alimento'];
			$arg2 = $_POST['ano'];
			
			$stm = "
			SELECT alimentos.alimento as produto, SUM(registros_resumo.totalizacao) as total FROM `registros_resumo` 
			INNER JOIN relatorios ON registros_resumo.fk_relatorio = relatorios.id_relatorio
			INNER JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento
			WHERE alimentos.alimento = '{$arg1}' AND relatorios.status = 1 AND relatorios.ano = {$arg2} AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.tipo_relatorio = 'POR MÊS'
			";
			
			
			
			$query = $this->db->query($stm, array(null));
			
			$res = $query->fetchAll(PDO::FETCH_ASSOC);
			
			echo "<td>".$res[0]['produto']."</td>";
			echo "<td>".number_format($res[0]['total'], 2, ',', '.')."</td>";
			
			return;
		}
	}
	
	public function somaAnualAba()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) )
		{
			$arg1 = $_POST['alimento'];
			$arg2 = $_POST['ano'];
			$arg3 = $_POST['abastecimento'];
			
			$stm = "
			SELECT alimentos.alimento as produto, SUM(registros_resumo.totalizacao) as total, abastecimento.aba as abastecimento FROM `registros_resumo` 

			INNER JOIN relatorios ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
			INNER JOIN cronograma ON cronograma.id_cronograma = relatorios.fk_cronograma
			INNER JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
			INNER JOIN abastecimento ON abastecimento.id_abastecimento = agrupamento.fk_abastecimento
			INNER JOIN alimentos ON alimentos.id_alimento = registros_resumo.fk_alimento 


			WHERE alimentos.alimento = '{$arg1}' AND relatorios.status = 1 AND relatorios.ano = {$arg2} AND abastecimento.aba = '{$arg3}' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.tipo_relatorio = 'POR ABASTECIMENTO'
			";
			
			
			
			$query = $this->db->query($stm, array(null));
			
			$res = $query->fetchAll(PDO::FETCH_ASSOC);
			
			
			echo "<td>".$res[0]['abastecimento']."</td>";
			echo "<td>".$res[0]['produto']."</td>";
			echo "<td>".number_format($res[0]['total'], 2, ',', '.')."</td>";
			
			
			return;
		}
	}
	public function somaAnualAgr()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) )
		{
			$arg1 = $_POST['alimento'];
			$arg2 = $_POST['ano'];
			$arg3 = $_POST['abastecimento'];
			$arg4 = $_POST['agrupamento'];
			
			$stm = "
			SELECT alimentos.alimento as produto, SUM(registros_resumo.totalizacao) as total, abastecimento.aba as abastecimento, agrupamento.agrupamento as agrupamento FROM `registros_resumo` 

			INNER JOIN relatorios ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
			INNER JOIN cronograma ON cronograma.id_cronograma = relatorios.fk_cronograma
			INNER JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
			INNER JOIN abastecimento ON abastecimento.id_abastecimento = agrupamento.fk_abastecimento
			INNER JOIN alimentos ON alimentos.id_alimento = registros_resumo.fk_alimento 

			WHERE 
            alimentos.alimento = '{$arg1}' AND   
            abastecimento.aba = '{$arg3}' AND 
            agrupamento.agrupamento = '{$arg4}' AND
            registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND 
            relatorios.tipo_relatorio = 'POR ABASTECIMENTO' AND
            relatorios.ano = {$arg2} AND
            relatorios.status = 1
			";
			
			
			
			$query = $this->db->query($stm, array(null));
			
			$res = $query->fetchAll(PDO::FETCH_ASSOC);
			
			
			echo "<td>".$res[0]['abastecimento']."</td>";
			echo "<td>".$res[0]['agrupamento']."</td>";
			echo "<td>".$res[0]['produto']."</td>";
			echo "<td>".number_format($res[0]['total'], 2, ',', '.')."</td>";
			
			
			return;
		}
	}
	public function consulta()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) )
		{
			$stm = 
			"
			SELECT DISTINCT relatorios.id_relatorio, relatorios.titulo_relatorio, relatorios.tipo_relatorio, relatorios.tipo_alimento, relatorios.mes, relatorios.ano, relatorios.data_envio, relatorios.status, agrs.agrupamento, aba.aba, aba.ano as ano_aba FROM relatorios 

			INNER JOIN cronograma as crono on relatorios.fk_cronograma = crono.id_cronograma
			INNER JOIN agrupamento as agrs on crono.fk_aba = agrs.id_agrupamento 
			INNER JOIN abastecimento as aba on agrs.fk_abastecimento = aba.id_abastecimento 
			";
			
			$header_table = 
			"
			<tr>
				<th>ID</th>
				<th>TÍTULO RELATÓRIO</th>
				<th>TIPO RELATÓRIO</th>
				<th>TIPO ALIMENTO</th>
				<th>ABA/AGR</th>
				<th>MÊS</th>
				<th>ANO</th>
				<th>DATA ENVIO</th>
				<th>AÇÃO</th>
			</tr>
			";
			
			if( isset($_POST['data_entrega']) && !empty($_POST['data_entrega']) ) 
			{
				$where = 
				" 
				 WHERE relatorios.data_entrega = ? AND relatorios.status = 1 LIMIT 15
				";
				
				$stm .= $where; 
				
				$data_entrega = $_POST['data_entrega'];
				
				$query = $this->db->query( $stm, array( $data_entrega ) );
				
				echo $header_table;	
				
				foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $data )
				{
					$url = HOME_URI;
					$url .= "relatorios/exibirRelatorio/{$data['id_relatorio']}/";
			
					echo "<tr>";
					echo "<td>". $data['id_relatorio'] ."</td>";
					echo "<td>". $data['titulo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_alimento'] ."</td>";
					if( $data['tipo_relatorio'] == 'POR MÊS' ){
						echo "<td></td>";
					}
					else{ 
						echo "<td>". $data['aba'] .' ABA AGR '. $data['agrupamento'] ."</td>";
					}
					echo "<td>". $data['mes'] ."</td>";
					echo "<td>". $data['ano'] ."</td>";
					echo "<td>". $data['data_envio'] ."</td>";
					echo "<td><a href='{$url}' class='btn btn-default'>Abrir</a></td>";
					echo "</tr>";
				}
				
				return;
			}else	
			if( isset($_POST['data_envio']) && !empty($_POST['data_envio'])  )
			{
				$where = 
				"
				 WHERE relatorios.data_envio = ? AND status = 1 LIMIT 20
				";
				
				$stm .= $where;
				$data_envio = $_POST['data_envio'];
				
				
				$query = $this->db->query( $stm, array( $data_envio ));
				
				echo $header_table;	
				foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
				{
					$url = HOME_URI;
					$url .= "relatorios/exibirRelatorio/{$data['id_relatorio']}/";
			
					echo "<tr>";
					echo "<td>". $data['id_relatorio'] ."</td>";
					echo "<td>". $data['titulo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_alimento'] ."</td>";
					if( $data['tipo_relatorio'] == 'POR MÊS' ){
						echo "<td></td>";
					}
					else{ 
						echo "<td>". $data['aba'] .' ABA AGR '. $data['agrupamento'] ."</td>";
					}
					echo "<td>". $data['mes'] ."</td>";
					echo "<td>". $data['ano'] ."</td>";
					echo "<td>". $data['data_envio'] ."</td>";
					echo "<td><a href='{$url}' class='btn btn-default'>Abrir</a></td>";
					echo "</tr>";
				}
				return;
			}else
			if( !empty($_POST['abastecimento']) && !empty($_POST['agrupamento']) && !empty( $_POST['ano'] ) )
			{
				$where = 
				"
				 WHERE aba.ano = ? AND aba.aba = ? AND  agrs.agrupamento = ? AND relatorios.status = 1 LIMIT 20
				";
				
				$stm .= $where; 
				
				$query = $this->db->query( $stm, array($_POST['ano'], $_POST['abastecimento'], $_POST['agrupamento']));
				
				echo $header_table;	
				foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
				{
					$url = HOME_URI;
					$url .= "relatorios/exibirRelatorio/{$data['id_relatorio']}/";
			
					echo "<tr>";
					echo "<td>". $data['id_relatorio'] ."</td>";
					echo "<td>". $data['titulo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_alimento'] ."</td>";
					if( $data['tipo_relatorio'] == 'POR MÊS' ){
						echo "<td></td>";
					}
					else{ 
						echo "<td>". $data['aba'] .' ABA AGR '. $data['agrupamento'] ."</td>";
					}
					echo "<td>". $data['mes'] ."</td>";
					echo "<td>". $data['ano'] ."</td>";
					echo "<td>". $data['data_envio'] ."</td>";
					echo "<td><a href='{$url}' class='btn btn-default'>Abrir</a></td>";
					echo "</tr>";
				}
				return;
			}else
			if( !empty($_POST['titulo_relatorio']) && !empty($_POST['tipo_relatorio']) && !empty($_POST['tipo_alimento']) )
			{
				
				$where = 
				"
				 WHERE relatorios.titulo_relatorio = ? AND relatorios.tipo_relatorio = ? AND relatorios.tipo_alimento = ? AND relatorios.status = '1' LIMIT 15
				";
																									
				$stm .= $where;
				
				$query = $this->db->query(
				$stm,
				array(
					$_POST['titulo_relatorio'],$_POST['tipo_relatorio'],$_POST['tipo_alimento']
				));
				
				echo $header_table;	
				foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
				{
					$url = HOME_URI;
					$url .= "relatorios/exibirRelatorio/{$data['id_relatorio']}/";
			
					echo "<tr>";
					echo "<td>". $data['id_relatorio'] ."</td>";
					echo "<td>". $data['titulo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_relatorio'] ."</td>";
					echo "<td>". $data['tipo_alimento'] ."</td>";
					if( $data['tipo_relatorio'] == 'POR MÊS' ){
						echo "<td></td>";
					}
					else{ 
						echo "<td>". $data['aba'] .' ABA AGR '. $data['agrupamento'] ."</td>";
					}
					echo "<td>". $data['mes'] ."</td>";
					echo "<td>". $data['ano'] ."</td>";
					echo "<td>". $data['data_envio'] ."</td>";
					echo "<td><a href='{$url}' class='btn btn-default'>Abrir</a></td>";
					echo "</tr>";
				}
				return;
			}
			
			return;
		}//if
			else
				return;	
	}//consulta()
		
	public function exibirRelatorio($key)
	{
		$stm = 
		"
		SELECT * FROM relatorios
		INNER JOIN cronograma as crono on relatorios.fk_cronograma = crono.id_cronograma INNER JOIN agrupamento as agrs on crono.fk_aba = agrs.id_agrupamento INNER JOIN abastecimento as aba on agrs.fk_abastecimento = aba.id_abastecimento
		WHERE relatorios.id_relatorio = ?
		";
		
		$query = $this->db->query($stm, array($key));
		
		$this->file_data = $query->fetchAll(PDO::FETCH_ASSOC);
		
		$relatorio = unserialize($this->file_data[0]['serialize_file']);
		
		switch($this->file_data[0]['titulo_relatorio'])
		{
			case 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS':
				return $this->viewNanp($relatorio);
			break;
			
			case 'SIMULADO - RELATORIO NECESSIDADE DE NAO PERECIVEIS POR ENTREGA NO AGRUPAMENTO':
				return $this->viewSimulado($relatorio);
			break;
			
			case 'RESUMO TOTALIZAÇÃO':
				
				if($this->file_data[0]['tipo_relatorio'] == 'POR MÊS')
					return $this->viewResumoMes($relatorio);
				
				if($this->file_data[0]['tipo_relatorio'] == 'POR ABASTECIMENTO')
					return $this->viewResumo($relatorio);
			break;
			
			case 'RESUMO DE TOTALIZAÇÃO':
				if($this->file_data[0]['tipo_relatorio'] == 'POR MÊS')
					return $this->viewResumoMes($relatorio);
				
				if($this->file_data[0]['tipo_relatorio'] == 'POR ABASTECIMENTO')
					return $this->viewResumo($relatorio);
			break;
			
			default:
				return;
		}
		 
	}//exibirRelatorio()
	
	public function exibirRelatorioSerializado($key)
	{
		$stm = 
		"
		SELECT * FROM relatorios
		INNER JOIN cronograma as crono on relatorios.fk_cronograma = crono.id_cronograma INNER JOIN agrupamento as agrs on crono.fk_aba = agrs.id_agrupamento INNER JOIN abastecimento as aba on agrs.fk_abastecimento = aba.id_abastecimento
		WHERE relatorios.id_relatorio = ?
		";
		
		$query = $this->db->query($stm, array($key));
		
		$this->file_data = $query->fetchAll(PDO::FETCH_ASSOC);

		$relatorio = unserialize($this->file_data[0]['serialize_file']);
		
		switch($this->file_data[0]['titulo_relatorio'])
		{
			case 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS':
				return $this->viewNanp($relatorio);
			break;
			
			case 'SIMULADO - RELATORIO NECESSIDADE DE NAO PERECIVEIS POR ENTREGA NO AGRUPAMENTO':
				return $this->viewSimulado($relatorio);
			break;
			
			case 'RESUMO TOTALIZAÇÃO':
				if($this->file_data[0]['tipo_relatorio'] == 'POR MÊS')
					return $this->viewResumoMes($relatorio);	
				else
					return $this->viewResumo($relatorio);
			break;
			
			case 'RESUMO DE TOTALIZAÇÃO':
				if($this->file_data[0]['tipo_relatorio'] == 'POR MÊS')
					return $this->viewResumoMes($relatorio);	
				else
					return $this->viewResumo($relatorio);
			break;
		}
		 
	}//Serializado()
	
	public function exibirEntregueMEs($key)
	{
		$stm = 
		"
		SELECT aliments.alimento AS alimentos, SUM(resumo.totalizacao) as total, resumo.situacao FROM relatorios AS rels INNER JOIN registros_resumo AS resumo  ON rels.id_relatorio = resumo.fk_relatorio AND resumo.situacao = 'ENTREGUE+ENTREGUE' AND rels.tipo_alimento = 'PERECÍVEL' INNER JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma INNER JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba INNER JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento INNER JOIN alimentos AS aliments ON resumo.fk_alimento = aliments.id_alimento 
		WHERE rels.id_relatorio = ? AND rels.status = 1 AND rels.tipo_relatorio = 'POR MÊS'
		GROUP BY aliments.id_alimento
		ORDER BY aliments.alimento ASC
		";
		
		$query = $this->db->query($stm, array($key));
		
		$data_relatorio = $query->fetchAll(PDO::FETCH_ASSOC);
			
		$table_html = 
		"
		<table class='table table-hover'>
        <thead>
		  <tr>
              <th class='text-center' colspan='7'>RESUMO DE TOTALIZAÇÃO</th>
          </tr>
          <tr>
              <th class='text-center' colspan='7'>POR MÊS</th>
          </tr>
         
          <tr>
              <th>Situação Guia+Item</th>
              <th>Alimento</th>
              <th>Totalização</th>
          </tr>
        </thead>

        <tbody>
		";
		
		echo $table_html;
		
		$lenght = count($data_relatorio);
		
		for($i = 0; $i < $lenght; $i++)
		{
			echo "<tr>";
			echo "<td>".$data_relatorio[$i]['situacao']."</td>";
			echo "<td>".$data_relatorio[$i]['alimentos']."</td>";
			echo "<td>".number_format($data_relatorio[$i]['total'],2, ',', '.')."</td>";
			echo "</tr>";
		}
		
		echo 
		"
			</tbody>
		</table>
		";
	}
	
	
	public function viewNanp($data_relatorio = array())
	{
		$limit_linha = count($data_relatorio);
		$limit_coluna = count($data_relatorio[0]);
		
		$i = 0;
		
		$alimento = $data_relatorio[$i];
		$qtde_prevista = $data_relatorio[++$i];
		$fator = $data_relatorio[++$i];
		$estoque_unidate = $data_relatorio[++$i];
		$qtde_Ajustada = $data_relatorio[++$i];
		$capacidade = $data_relatorio[++$i];
		$total_embalagem = $data_relatorio[++$i];
		
		$titulo_relatorio = $this->file_data[0]['titulo_relatorio'];
		$tipo_relatorio = $this->file_data[0]['tipo_relatorio'];
		$agr = $this->file_data[0]['agrupamento'];
		$aba = $this->file_data[0]['aba'];
		$data_entrega = $this->inverte_data( $this->file_data[0]['data_entrega'] );
		
		$table_html = 
		"
			<table class='table table-hover'>
		   <thead>
			  <tr>
				  <th class='text-center' colspan='7'>$titulo_relatorio</th>
			  </tr>
			  <tr>
				  <th class='text-center' colspan='7'>$tipo_relatorio</th>
			  </tr>
			  <tr>    
				  <th colspan='1'></th>
				  <th>AGRUPAMENTO</th>
				  <th>ABASTECIMENTO</th>
				  <th colspan='4'>DATA ENTREGA</th>
			  </tr>
			  <tr>
				<td colspan='1'></td>
				<td>$agr</td>
				<td>$aba</td>
				<td>$data_entrega</td>
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
		";
		
		echo $table_html;
		
		for($l = 0; $l < $limit_coluna; $l++)
		{
			echo "<tr>";
            echo "<td>{$alimento[$l]}</td>";
            echo "<td>".number_format($qtde_prevista[$l],2, ',', '.')."</td>";
            echo "<td>".number_format($fator[$l],2,',','.')."</td>";
            echo "<td>".number_format($estoque_unidate[$l],2, ',', '.')."</td>";
            echo "<td>".number_format($qtde_Ajustada[$l],2,',','.')."</td>";
            echo "<td>".number_format($capacidade[$l],2,',','.')."</td>";
            echo "<td>".number_format($total_embalagem[$l],2,',','.')."</td>";
			echo "</tr>";
		
		}
		
		echo 
		"
			</tbody>
		</table>
		";		
		
		return;
	}//viewNanp()
	
	public function viewResumo($data_relatorio = array())
	{
		
		$limit_linha = count($data_relatorio);
		$limit_coluna = count($data_relatorio[0]);
		
		$i = 0;
		
		$alimento = $data_relatorio[$i];
		$situacao = $data_relatorio[++$i];
		$total_embalagem_fechada = $data_relatorio[++$i];
		$total_embalagem_fracionada = $data_relatorio[++$i];
		$totalizacao = $data_relatorio[++$i];
		
		$titulo_relatorio = $this->file_data[0]['titulo_relatorio'];
		$tipo_relatorio = $this->file_data[0]['tipo_relatorio'];
		$agr = $this->file_data[0]['agrupamento'];
		$aba = $this->file_data[0]['aba'];
		$periodo = $this->file_data[0]['periodo'];
		
		$table_html = 
		"
		<table class='table table-hover'>
        <thead>
        <tr>
              <th class='text-center' colspan='7'>$titulo_relatorio</th>
          </tr>
          <tr>
              <th class='text-center' colspan='7'>$tipo_relatorio</th>
          </tr>
          <tr>   
			<th colspan='1'></th>	
            <th>PERÍODO</th>
            <th>AGRUPAMENTO</th>
            <th colspan='5'>ABASTECIMENTO</th>
          </tr>
          <tr>
			<td colspan='1'></td>
            <td>$periodo</td>
            <td>$agr</td>
            <td colspan='4'>$aba</td>
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
		";
		
		echo $table_html;
		
		for($l = 0; $l < $limit_coluna; $l++)
		{
			
			if( isset($_POST['situacao']) && $_POST['situacao'] == 0)
			{
				echo "<tr>";
				echo "<td>{$situacao[$l]}</td>";
				echo "<td>{$alimento[$l]}</td>";
				echo "<td>{$total_embalagem_fechada[$l]}</td>";
				echo "<td>{$total_embalagem_fracionada[$l]}</td>";
				echo "<td>".number_format($totalizacao[$l],2,',','.')."</td>";
				echo "</tr>";
			}
				else
					if( isset($_POST['situacao']) && $_POST['situacao'] == 1)
					{
						if($situacao[$l] == 'ENTREGUE+ENTREGUE')
						{
							echo "<tr>";
							echo "<td>{$situacao[$l]}</td>";
							echo "<td>{$alimento[$l]}</td>";
							echo "<td>{$total_embalagem_fechada[$l]}</td>";
							echo "<td>{$total_embalagem_fracionada[$l]}</td>";
							echo "<td>".number_format($totalizacao[$l],2,',','.')."</td>";
							echo "</tr>";
						}
					}
				else
				{
					echo "<tr>";
					echo "<td>{$situacao[$l]}</td>";
					echo "<td>{$alimento[$l]}</td>";
					echo "<td>{$total_embalagem_fechada[$l]}</td>";
					echo "<td>{$total_embalagem_fracionada[$l]}</td>";
					echo "<td>".number_format($totalizacao[$l],2,',','.')."</td>";
					echo "</tr>";
				}					
		}
		
		echo 
		"
			</tbody>
		</table>
		";		
		
		return;	
	}//viewResumo()
	
	public function viewResumoMes( $data_relatorio = array() )
	{
		if( empty($data_relatorio) ) 
			return;
		
		$table_html = 
		"
		<table class='table table-hover'>
        <thead>
		  <tr>
              <th class='text-center' colspan='7'>RESUMO DE TOTALIZAÇÃO</th>
          </tr>
          <tr>
              <th class='text-center' colspan='7'>POR MÊS</th>
          </tr>
         
          <tr>
              <th>Situação Guia+Item</th>
              <th>Alimento</th>
              <th>Totalização</th>
          </tr>
        </thead>

        <tbody>
		";
		
		echo $table_html;
		
		$lenght = count($data_relatorio[0]);
		
		for($i = 0; $i < $lenght; $i++)
		{
			if( isset($_POST['situacao']) && $_POST['situacao'] == 0 )
			{
				echo "<tr>";
				echo "<td>".$data_relatorio[1][$i]."</td>";
				echo "<td>".$data_relatorio[0][$i]."</td>";
				echo "<td>".number_format($data_relatorio[4][$i], 2, ',', '.')."</td>";
				echo "</tr>";				
			}
			else
				if( isset($_POST['situacao']) && $_POST['situacao'] == 1 )
				{
					if($data_relatorio[1][$i] == 'ENTREGUE+ENTREGUE')
					{
						echo "<tr>";
						echo "<td>".$data_relatorio[1][$i]."</td>";
						echo "<td>".$data_relatorio[0][$i]."</td>";
						echo "<td>".number_format($data_relatorio[4][$i], 2, ',', '.')."</td>";
						echo "</tr>";
					}
				}
			else
			{
				echo "<tr>";
				echo "<td>".$data_relatorio[1][$i]."</td>";
				echo "<td>".$data_relatorio[0][$i]."</td>";
				echo "<td>".number_format($data_relatorio[4][$i], 2, ',', '.')."</td>";
				echo "</tr>";
			}				
		}
		
		echo 
		"
			</tbody>
		</table>
		";		
		
		return;	
	}//viewResumoMes
	
	public function viewSimulado($data_relatorio = array())
	{
		$limit_linha = count($data_relatorio);
		$limit_coluna = count($data_relatorio[0]);
		
		$i = 0;
		/*indices e variaveis invertidas, debugar matriz*/
		$agrupamento = $data_relatorio[$i];
		$data_entrega =  $data_relatorio[++$i];
		$alimento =  $data_relatorio[++$i];
		$qtde_necessaria =  $data_relatorio[++$i];
		$qtde_ajustada =  $data_relatorio[++$i];
		$qtde_embalagem =  $data_relatorio[++$i];
		
		$titulo_relatorio = $this->file_data[0]['titulo_relatorio'];
		$tipo_relatorio = $this->file_data[0]['tipo_relatorio'];
		
		
		$table_html = 
		"
			<table class='table table-hover'>
        <thead>
        <tr>
          <th colspan='7' class='text-center'>$titulo_relatorio</th>
        </tr>
          <tr>
              <th>ALIMENTO</th>
              <th>AGRUPAMENTO</th>
              <th>DATA ENTREGA</th>
              <th>QTDE NECESSARIA</th>
              <th>QTDE AJUSTADA</th>
              <th>QTDE EMBALAGEM</th>
          </tr>
        </thead>

        <tbody>
		";
		
		echo $table_html;
		
		for($l = 0; $l < $limit_coluna; $l++)
		{
			echo "<tr>";
            echo "<td>{$agrupamento[$l]}</td>";
            echo "<td>{$data_entrega[$l]}</td>";
            echo "<td>{$this->inverte_data($alimento[$l])}</td>";
            echo "<td>".number_format($qtde_necessaria[$l],2,',','.')."</td>";
            echo "<td>".number_format($qtde_ajustada[$l],2,',','.')."</td>";
            echo "<td>".number_format($qtde_embalagem[$l],2,',','.')."</td>";
			echo "</tr>";
		
		}
		
		echo 
		"
			</tbody>
		</table>
		";		
		
		return;
	}
	
	public function viewHeader($id = null)
	{
		if( empty($id) )
			return;
		
		if( $this->file_data[0]['status'] == 0 )
			$status = 'desativado';
		else
			$status = 'ativo';
		
		if( $this->file_data[0]['tipo_relatorio'] == 'POR MÊS' )
		{
			$this->file_data[0]['aba'] = null;
			$this->file_data[0]['agrupamento'] = null;
		}
		
		echo "
			<tr>
				<td>{$this->file_data[0]['id_relatorio']}</td>
				<td>{$this->file_data[0]['aba']}</td>
				<td>{$this->file_data[0]['agrupamento']}</td>
				<td>{$this->file_data[0]['data_dez_dias']}</td>
				<td>{$this->file_data[0]['tipo_alimento']}</td>
				<td>{$this->file_data[0]['mes']}</td>
				<td>{$this->file_data[0]['ano']}</td>
				<td>{$this->inverte_data( $this->file_data[0]['data_envio'] )}</td>
				<td>{$status}</td>
			</tr>
		";
		
		
		
		return;
	}
	
	public function resumoAba()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST) )
		{
			$stm = 
					"
					SELECT * From (
				
					SELECT abas.aba as abas, agrs.agrupamento as agrs,alis.id_alimento as id_alimentos,alis.alimento as alimentos,Sum(regsimus.qtd_ajustada) as qtd_ajustadas,abas.ano FROM  relatorios as rels  
					
						LEFT OUTER JOIN `registros_simulado` as regsimus on rels.id_relatorio = regsimus.fk_relatorio 
						
						LEFT OUTER JOIN  cronograma as cros on rels.fk_cronograma = cros.id_cronograma  
						
						LEFT OUTER JOIN agrupamento as agrs on agrs.id_agrupamento = cros.fk_aba 
						
						LEFT OUTER Join abastecimento as abas on agrs.fk_abastecimento=abas.id_abastecimento 
						
						LEFT OUTER JOIN alimentos as alis on  regsimus.fk_alimento= alis.id_alimento 
						
						where rels.status = 1 AND abas.ano = ?
						Group by abas.aba, agrs.agrupamento,alis.id_alimento
						ORDER BY abas.aba ASC, agrs.agrupamento ASC, alimentos ASC
									) 
				Simulado LEFT OUTER Join (
					SELECT * FROM( 
						SELECT aban.aba as abn, aban.ano, agrn.agrupamento as agn,alin.id_alimento as id_alimentosn, alin.alimento as alimentosn, SUM(regnanpn.qtd_prevista) as qtd_previstasn, SUM(regnanpn.qtd_ajustada) as qtd_ajustadasn FROM relatorios as reln 
						
							RIGHT OUTER JOIN registros_nanp as regnanpn on reln.id_relatorio = regnanpn.fk_relatorio 
							
							RIGHT OUTER JOIN alimentos as alin on regnanpn.fk_alimento = alin.id_alimento 
							
							RIGHT OUTER JOIN cronograma as cron on reln.fk_cronograma = cron.id_cronograma 
							
							RIGHT OUTER JOIN agrupamento as agrn on agrn.id_agrupamento = cron.fk_aba 
							
							RIGHT OUTER Join abastecimento as aban on aban.id_abastecimento = agrn.fk_abastecimento 
							
							where reln.status = 1 AND reln.tipo_relatorio like '%SEM%' and aban.ano = ? 
							Group by aban.aba, agrn.agrupamento,alin.id_alimento
							ORDER BY aban.aba ASC, agrn.agrupamento ASC, alimentosn ASC
							) as Nan )
				
						Nanp on Nanp.abn = Simulado.abas And Nanp.agn = Simulado.agrs And Simulado.id_alimentos=Nanp.id_alimentosn AND Simulado.ano = Nanp.ano
						
						
	UNION
	SELECT * From (
					
					SELECT abas.aba as abas, agrs.agrupamento as agrs,alis.id_alimento as id_alimentos,alis.alimento as alimentos,Sum(regsimus.qtd_ajustada) as qtd_ajustadas,abas.ano FROM  relatorios as rels  
					
						RIGHT OUTER JOIN `registros_simulado` as regsimus on rels.id_relatorio = regsimus.fk_relatorio 
						
						RIGHT OUTER JOIN  cronograma as cros on rels.fk_cronograma = cros.id_cronograma  
						
						RIGHT OUTER JOIN agrupamento as agrs on agrs.id_agrupamento = cros.fk_aba 
						
						RIGHT OUTER Join abastecimento as abas on agrs.fk_abastecimento=abas.id_abastecimento 
						
						RIGHT OUTER JOIN alimentos as alis on  regsimus.fk_alimento= alis.id_alimento 
						
						where rels.status = 1 AND abas.ano = ?
						Group by abas.aba, agrs.agrupamento,alis.id_alimento
						ORDER BY abas.aba ASC, agrs.agrupamento ASC, alimentos ASC
									) 
				Simulado RIGHT OUTER Join (
					SELECT * FROM( 
						SELECT aban.aba as abn, aban.ano, agrn.agrupamento as agn,alin.id_alimento as id_alimentosn, alin.alimento as alimentosn, SUM(regnanpn.qtd_prevista) as qtd_previstasn, SUM(regnanpn.qtd_ajustada) as qtd_ajustadasn FROM relatorios as reln 
						
							LEFT OUTER JOIN registros_nanp as regnanpn on reln.id_relatorio = regnanpn.fk_relatorio 
							
							LEFT OUTER JOIN alimentos as alin on regnanpn.fk_alimento = alin.id_alimento 
							
							LEFT OUTER JOIN cronograma as cron on reln.fk_cronograma = cron.id_cronograma 
							
							LEFT OUTER JOIN agrupamento as agrn on agrn.id_agrupamento = cron.fk_aba 
							
							LEFT OUTER Join abastecimento as aban on aban.id_abastecimento = agrn.fk_abastecimento 
							
							where reln.status = 1 AND reln.tipo_relatorio like '%SEM%' and aban.ano = ? 
							Group by aban.aba, agrn.agrupamento,alin.id_alimento
							ORDER BY aban.aba ASC, agrn.agrupamento ASC, alimentosn ASC
							) as Nan )
				
						Nanp on Nanp.abn = Simulado.abas And Nanp.agn = Simulado.agrs And Simulado.id_alimentos=Nanp.id_alimentosn AND Simulado.ano = Nanp.ano
						GROUP BY abas,abn,agrs,agn,alimentosn,alimentos
						ORDER BY abas ASC,abn ASC,agrs ASC,agn ASC,alimentosn ASC,alimentos ASC
					";
					
					$ano = $_POST['ano'];
					
					$query = $this->db->query($stm, array($ano,$ano,$ano,$ano));
					
					$relatorio_data_html = array();
					
					foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
					{
						
						if
						( 
							(
								( ! empty($data['abas']) && ! empty($data['agrs']) ) || 
								( ! empty($data['abn']) && ! empty($data['agn']) ) 
							) &&
							( ! empty($data['alimentosn']) || ! empty($data['alimentos']) ) 
						)
						{
						
							if( ! empty($data['alimentos']) )	
							{
								$relatorio_data_html[]= array( 
									'abastecimento' => $data['abas'],
									'agrupamento'  =>  $data['agrs'], 
									'alimento' => $data['alimentos'], 
									'simulado' => number_format($data['qtd_ajustadas'], 2, ',', '.'), 
									'necessaria' => number_format($data['qtd_previstasn'], 2, ',', '.'), 
									
									'ajustada' => number_format($data['qtd_ajustadasn'], 2, ',', '.'),
									
									'porcentagem' => 
									number_format( $porcentual = ( $data['qtd_previstasn'] != 0 ) ? ( ($data['qtd_ajustadasn'] / $data['qtd_previstasn']) -1 ) * 100 : 0, 2 )		
								);
							}
							else if( ! empty($data['alimentosn']) )
							{
								$relatorio_data_html[]= array( 
									'abastecimento' => $data['abn'],
									'agrupamento'  =>  $data['agn'], 
									'alimento' => $data['alimentosn'], 
									'simulado' => number_format($data['qtd_ajustadas'], 2, ',', '.'), 
									'necessaria' => number_format($data['qtd_previstasn'], 2, ',', '.'), 
									'ajustada' => number_format($data['qtd_ajustadasn'], 2, ',', '.'),
									'porcentagem' => 
									
									number_format( $porcentual = ( $data['qtd_previstasn'] != 0 ) ? ( ($data['qtd_ajustadasn'] / $data['qtd_previstasn']) -1 ) * 100 : 0, 2 )		
								);
							}
						}
						
					}//foreach
					
					array_sort($relatorio_data_html,'abastecimento', SORT_ASC);
					
					$relatorio = multiSort($relatorio_data_html, 'abastecimento','agrupamento', 'alimento');
					
					//atribuindo resultado da consulta e ordenação do relatório no escopo global da model para a totalizacao
					$this->relatorio_total = $relatorio;
					
					$aux_aba = 0;
					$aux_agr = 0;
					
					$html = null;
						
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						$header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									SIMULADO
								</th>
								<th>
									NECESSARIA S/
								</th>
								<th>
									AJUSTADA C/
								</th>
								<th>
									%
								</th>
							</tr>
						";
							
						
						
						if( $aux_aba != $relatorio[$i]['abastecimento'] || $aux_agr != $relatorio[$i]['agrupamento'] && $relatorio[$i]['abastecimento']  )
						{
							echo $header_aba = 
							"
								<td>
									<th>
										<p class='text-center' id='" . $relatorio[$i]['abastecimento'] . $relatorio[$i]['agrupamento'] . "'>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}</p>
									</th>
									<th colspan='3'></th>
								</td>
							";
							
							echo $header;
							
							$html .= 
							"
								<li><a href='#".$relatorio[$i]['abastecimento'].$relatorio[$i]['agrupamento']."'><p>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}</p></a></li>
							";
						}
						
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>{$relatorio[$i]['simulado']}</td>
							<td>{$relatorio[$i]['necessaria']}</td>
							<td>{$relatorio[$i]['ajustada']}</td>
							<td>{$relatorio[$i]['porcentagem']}</td>
						</tr>					
						";
						
						
						
						//guarda o ultimo valor da varredura no array
						$aux_aba = $relatorio[$i]['abastecimento'];
						$aux_agr = $relatorio[$i]['agrupamento'];
					
					
						
					
					}//for
					$html .= 
						"
							<li><a href='#total'><p>TOTALIZAÇÃO</p></a></li>
						";
			return $this->setIndice($html);
		}//if
		
	}//resumoAba()
	
	
	
	
	public function abastecimentoNp()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$stm = 
			"
			(Select * From 

				(SELECT abas.aba as abaSI, agrs.agrupamento as agrsSI,alis.alimento as alimentos_sim,Sum(regsimus.qtd_ajustada) as qtd_ajustadas, alis.id_alimento FROM  relatorios as rels  LEFT JOIN `registros_simulado` as regsimus on rels.id_relatorio = regsimus.fk_relatorio LEFT JOIN  cronograma as cros on rels.fk_cronograma = cros.id_cronograma  LEFT JOIN agrupamento as agrs on agrs.id_agrupamento = cros.fk_aba  LEFT Join abastecimento as abas on agrs.fk_abastecimento=abas.id_abastecimento LEFT JOIN alimentos as alis on  regsimus.fk_alimento= alis.id_alimento 
			where rels.status = 1 AND rels.tipo_relatorio='Simulado' and abas.ano = ?
			Group by abas.aba, agrs.agrupamento,alis.alimento
			ORDER BY abas.aba ASC, agrs.agrupamento ASC, alis.alimento ASC )
            
            as Simulado  LEFT Join 
            
            (SELECT * From(SELECT abas.aba AS abaNP, agrs.agrupamento AS agrsNP, aliments.alimento AS alimentos_nanp, SUM(regsnp.qtd_ajustada) as ajustada, aliments.id_alimento FROM relatorios AS rels LEFT JOIN registros_nanp AS regsnp  ON rels.id_relatorio = regsnp.fk_relatorio LEFT JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma LEFT JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba LEFT JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento LEFT JOIN alimentos AS aliments ON regsnp.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'Não Perecível' AND rels.tipo_relatorio = 'SEM CONSIDERAR O ESTOQUE DA UNIDADE' AND abas.ano = ?
			GROUP BY abas.aba, agrs.agrupamento, aliments.alimento
            ORDER BY abas.aba ASC, agrs.agrupamento ASC, aliments.alimento ASC) as Nan) as Nanp 
            
            on Nanp.abaNP=Simulado.abaSI and Nanp.agrsNP= Simulado.agrsSI and Nanp.id_alimento= Simulado.id_alimento 
            
            LEFT Join 
            
            (Select * From(SELECT abas.aba AS abaRE, agrs.agrupamento AS agrsRE, aliments.alimento AS alimentos_resumo, resumo.totalizacao, resumo.situacao, aliments.id_alimento FROM relatorios AS rels LEFT JOIN registros_resumo AS resumo  ON rels.id_relatorio = resumo.fk_relatorio AND resumo.situacao = 'ENTREGUE+ENTREGUE' LEFT JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma LEFT JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba LEFT JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento LEFT JOIN alimentos AS aliments ON resumo.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'Não Perecível'  AND abas.ano = ? AND rels.tipo_relatorio = 'POR ABASTECIMENTO'
            GROUP BY abas.aba, agrs.agrupamento, aliments.alimento
			ORDER BY abas.aba ASC, agrs.agrupamento ASC, aliments.alimento ASC) as Res) as Resumo on Resumo.abaRE=Nanp.abaNP and Resumo.agrsRE=Nanp.agrsNP and Resumo.id_alimento=Nanp.id_alimento) 
            
            UNION 
            
            (Select  * From 

(SELECT abas.aba as abas, agrs.agrupamento as agrs,alis.alimento as alimentos,Sum(regsimus.qtd_ajustada) as qtd_ajustadas, alis.id_alimento FROM  relatorios as rels  RIGHT JOIN `registros_simulado` as regsimus on rels.id_relatorio = regsimus.fk_relatorio RIGHT JOIN  cronograma as cros on rels.fk_cronograma = cros.id_cronograma  RIGHT JOIN agrupamento as agrs on agrs.id_agrupamento = cros.fk_aba  RIGHT Join abastecimento as abas on agrs.fk_abastecimento=abas.id_abastecimento RIGHT JOIN alimentos as alis on  regsimus.fk_alimento= alis.id_alimento 
			where rels.status = 1 AND rels.tipo_relatorio='Simulado' and abas.ano = ?
			Group by abas.aba, agrs.agrupamento,alis.alimento
			ORDER BY abas.aba ASC, agrs.agrupamento ASC, alis.alimento ASC)
            
            as Simulado  RIGHT Join 
            
            (SELECT * From(SELECT abas.aba AS abas, agrs.agrupamento AS agrs, aliments.alimento AS alimentos, SUM(regsnp.qtd_ajustada) as ajustada, aliments.id_alimento FROM relatorios AS rels RIGHT JOIN registros_nanp AS regsnp  ON rels.id_relatorio = regsnp.fk_relatorio RIGHT JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma RIGHT JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba RIGHT JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento RIGHT JOIN alimentos AS aliments ON regsnp.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'Não Perecível' AND rels.tipo_relatorio = 'SEM CONSIDERAR O ESTOQUE DA UNIDADE' AND abas.ano = ?
			GROUP BY abas.aba, agrs.agrupamento, aliments.alimento
            ORDER BY abas.aba ASC, agrs.agrupamento ASC, aliments.alimento ASC) as Nan) as Nanp 
            
            on Nanp.abas=Simulado.abas and Nanp.agrs= Simulado.agrs and Nanp.id_alimento= Simulado.id_alimento 
            
            RIGHT Join 
            
            (Select * From(SELECT abas.aba AS abas, agrs.agrupamento AS agrs, aliments.alimento AS alimentos, resumo.totalizacao, resumo.situacao, aliments.id_alimento FROM relatorios AS rels RIGHT JOIN registros_resumo AS resumo  ON rels.id_relatorio = resumo.fk_relatorio AND resumo.situacao = 'ENTREGUE+ENTREGUE' RIGHT JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma RIGHT JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba RIGHT JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento RIGHT JOIN alimentos AS aliments ON resumo.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'Não Perecível' AND abas.ano = ? AND rels.tipo_relatorio = 'POR ABASTECIMENTO'
            GROUP BY abas.aba, agrs.agrupamento, aliments.alimento
			ORDER BY abas.aba ASC, agrs.agrupamento ASC, aliments.alimento ASC) as Res) as Resumo on Resumo.abas=Nanp.abas and Resumo.agrs=Nanp.agrs and Resumo.id_alimento=Nanp.id_alimento)
			";
			
			$ano = $_POST['ano'];
					
					$query = $this->db->query($stm, array($ano,$ano,$ano,$ano,$ano,$ano));
					
					$relatorio_data_html = array();
					
					foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
					{
						
						if
						( 
							(
								( ! empty($data['abaSI']) && ! empty($data['agrsSI']) ) || 
								( ! empty($data['abaNP']) && ! empty($data['agrsNP']) ) ||								
								( ! empty($data['abaRE']) && ! empty($data['agrsRE']) )
							) &&
							( ! empty($data['alimentos_sim']) || ! empty($data['alimentos_nanp']) || ! empty($data['alimentos_resumo']) ) 
						)
						{
						
							if( ! empty($data['alimentos_sim']) )	
							{
								$relatorio_data_html[]= array( 
									'abastecimento' => $data['abaSI'],
									'agrupamento'  =>  $data['agrsSI'], 
									'alimento' => $data['alimentos_sim'], 
									'simulado' => number_format($data['qtd_ajustadas'], 2, ',', '.'), 
									'explodido' => number_format($data['ajustada'], 2, ',', '.'), 
									'entregue' => number_format($data['totalizacao'], 2, ',', '.')	
								);
							}
							else if( ! empty($data['alimentos_nanp']) )
							{
								$relatorio_data_html[]= array( 
									'abastecimento' => $data['abaNP'],
									'agrupamento'  =>  $data['agrsNP'], 
									'alimento' => $data['alimentos_nanp'], 
									'simulado' => number_format($data['qtd_ajustadas'], 2, ',', '.'), 
									'explodido' => number_format($data['ajustada'], 2, ',', '.'), 
									'entregue' => number_format($data['totalizacao'], 2, ',', '.')	
								);
							}
							else if( ! empty($data['alimentos_resumo']) )
							{
								$relatorio_data_html[]= array( 
									'abastecimento' => $data['abaRE'],
									'agrupamento'  =>  $data['agrsRE'], 
									'alimento' => $data['alimentos_resumo'], 
									'simulado' => number_format($data['qtd_ajustadas'], 2, ',', '.'), 
									'explodido' => number_format($data['ajustada'], 2, ',', '.'), 
									'entregue' => number_format($data['totalizacao'], 2, ',', '.')	
								);	
							}
						}
					}//foreach
					
					array_sort($relatorio_data_html,'abastecimento', SORT_ASC);
					
					$relatorio = multiSort($relatorio_data_html, 'abastecimento','agrupamento', 'alimento');
					
					$aux_aba = 0;
					$aux_agr = 0;
					$html = null;
					
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						
						$header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									SIMULADO
								</th>
								<th>
									EXPLODIDO 
								</th>
								<th>
									ENTREGUE 
								</th>
							</tr>
						";
							
						
						
						if( $aux_aba != $relatorio[$i]['abastecimento'] || $aux_agr != $relatorio[$i]['agrupamento'] && $relatorio[$i]['abastecimento']  )
						{
							echo $header_aba = 
							"
								<tr>
									<td colspan='4'>
										<p class='text-center' id='" . $relatorio[$i]['abastecimento'] . $relatorio[$i]['agrupamento'] . "'><strong>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}</strong></p>
									</td>
								</tr>
							";
							
							echo $header;
							
							$html .= 
							"
								<li><a href='#".$relatorio[$i]['abastecimento'].$relatorio[$i]['agrupamento']."'><p>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}</p></a></li>
							";
						}
						
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>{$relatorio[$i]['simulado']}</td>
							<td>{$relatorio[$i]['explodido']}</td>
							<td>{$relatorio[$i]['entregue']}</td>
						</tr>					
						";
						
						
						
						//guarda o ultimo valor da varredura no array
						$aux_aba = $relatorio[$i]['abastecimento'];
						$aux_agr = $relatorio[$i]['agrupamento'];
					
					
						
					
					}//for
					$html .= 
						"
							<li><a href='#total'><p>TOTALIZAÇÃO</p></a></li>
						";
			return $this->setIndice($html);
		}//if	
	}//abastecimentoNp()
	
	
	
	public function abastecimentoPer()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{	
	
			$stm =  "
			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				LEFT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				LEFT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				LEFT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				LEFT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				LEFT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
			
			WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'PERECÍVEL' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
			GROUP BY abastecimento.aba, agrupamento.agrupamento, alimentos.id_alimento
			
			UNION            

			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				RIGHT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				RIGHT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				RIGHT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				RIGHT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				RIGHT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
            
            WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'PERECÍVEL' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
            GROUP BY abastecimento.aba, agrupamento.agrupamento, alimentos.id_alimento
			";
			$ano = $_POST['ano'];
			$query = $this->db->query($stm, array($ano, $ano));
			$relatorio_data_html = array();
					
					
					
					foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
					{
							$relatorio_data_html[]= array( 
								'abastecimento' => $data['aba'],
								'agrupamento'  =>  $data['agrupamento'], 
								'alimento' => $data['alimento'], 
								'totalizacao' => number_format($data['totalizacao'], 2, ',', '.'), 
							);	
					}//foreach
					
					array_sort($relatorio_data_html,'abastecimento', SORT_ASC);
					
					$relatorio = multiSort($relatorio_data_html, 'abastecimento','agrupamento', 'alimento');
					
					//atribuindo resultado da consulta e ordenação do relatório no escopo global da model para a totalizacao
					$this->relatorio_total = $relatorio;
					
					$aux_aba = 0;
					$aux_agr = 0;
					$html = null;
					
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						
						$header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									ENTREGUE 
								</th>
							</tr>
						";
							
						
						
						if( $aux_aba != $relatorio[$i]['abastecimento'] || $aux_agr != $relatorio[$i]['agrupamento'] && $relatorio[$i]['abastecimento']  )
						{
							echo $header_aba = 
							"
							<tr>
								<td colspan='2'>
								<p class='text-center' id='" . $relatorio[$i]['abastecimento'] . $relatorio[$i]['agrupamento'] . "'>
								<strong>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']} </strong>
								</p>
								</td>
							</tr>
							";
							
							echo $header;
							
							$html .= 
							"
								<li><a href='#".$relatorio[$i]['abastecimento'].$relatorio[$i]['agrupamento']."'><p>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}</p></a></li>
							";
						}
						
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>{$relatorio[$i]['totalizacao']}</td>
						</tr>					
						";
						
						
						
						//guarda o ultimo valor da varredura no array
						$aux_aba = $relatorio[$i]['abastecimento'];
						$aux_agr = $relatorio[$i]['agrupamento'];
					
					
						
					
					}//for
					$html .= 
						"
							<li><a href='#total'><p>TOTALIZAÇÃO</p></a></li>
						";
			return $this->setIndice($html);
		}//if	
		return;
	}//abastecimentoPer()
	
	
	
	public function abastecimentoFlvo()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{	
	
			$stm =  "
			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				LEFT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				LEFT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				LEFT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				LEFT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				LEFT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
			
			WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'FEIRA' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
			GROUP BY abastecimento.aba, agrupamento.agrupamento, alimentos.id_alimento
			
			UNION            

			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				RIGHT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				RIGHT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				RIGHT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				RIGHT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				RIGHT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
            
            WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'FEIRA' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
            GROUP BY abastecimento.aba, agrupamento.agrupamento, alimentos.id_alimento
			";
			
			$query = $this->db->query($stm, array($_POST['ano'],$_POST['ano']));
			$relatorio_data_html = array();
					
					
					
					foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
					{
							$relatorio_data_html[]= array( 
								'abastecimento' => $data['aba'],
								'agrupamento'  =>  $data['agrupamento'], 
								'alimento' => $data['alimento'], 
								'totalizacao' => number_format($data['totalizacao'], 2, ',', '.'), 
							);	
					}//foreach
					
					array_sort($relatorio_data_html,'abastecimento', SORT_ASC);
					
					$relatorio = multiSort($relatorio_data_html, 'abastecimento','agrupamento', 'alimento');
					
					//atribuindo resultado da consulta e ordenação do relatório no escopo global da model para a totalizacao
					$this->relatorio_total = $relatorio;
					
					$aux_aba = 0;
					$aux_agr = 0;
					$html = null;
					
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						
						$header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									ENTREGUE 
								</th>
							</tr>
						";
							
						
						
						if( $aux_aba != $relatorio[$i]['abastecimento'] || $aux_agr != $relatorio[$i]['agrupamento'] && $relatorio[$i]['abastecimento']  )
						{
							echo $header_aba = 
							"	
							<tr>
								<td colspan='2'>
									
										<p class='text-center' id='" . $relatorio[$i]['abastecimento'] . $relatorio[$i]['agrupamento'] . "'>
										<strong>
										
										{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}
										</strong>
										</p>
									
								</td>
							</tr>	
							";
							
							echo $header;
							
							$html .= 
							"
								<li><a href='#".$relatorio[$i]['abastecimento'].$relatorio[$i]['agrupamento']."'><p>{$relatorio[$i]['abastecimento']}º ABA AGR {$relatorio[$i]['agrupamento']}</p></a></li>
							";
						}
						
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>{$relatorio[$i]['totalizacao']}</td>
						</tr>					
						";
						
						
						
						//guarda o ultimo valor da varredura no array
						$aux_aba = $relatorio[$i]['abastecimento'];
						$aux_agr = $relatorio[$i]['agrupamento'];
					
					
						
					
					}//for
					$html .= 
						"
							<li><a href='#total'><p>TOTALIZAÇÃO</p></a></li>
						";
			return $this->setIndice($html);
		}//if	
		return;
	}//abastecimentoFlvo()
	
	
	
	public function entregueMes()
	{
		if( $_SERVER['REQUEST_METHOD'] === 'POST' )
		{
			$stm =  
			"
			SELECT DISTINCT id_relatorio, relatorios.* FROM relatorios
			INNER JOIN cronograma as crono on relatorios.fk_cronograma = crono.id_cronograma
            INNER JOIN agrupamento as agrs on crono.fk_aba = agrs.fk_abastecimento
            INNER JOIN abastecimento as aba on agrs.fk_abastecimento = aba.id_abastecimento
			WHERE 
			relatorios.titulo_relatorio = 'RESUMO TOTALIZAÇÃO' AND relatorios.tipo_relatorio = 'POR MÊS'
			AND relatorios.tipo_alimento = ? AND relatorios.mes = ? AND relatorios.status = 1 AND relatorios.ano = ?
			";
			
			$query = $this->db->query($stm, array($_POST['tipo_alimento'],$_POST['mes'],$_POST['ano']) );
			echo 
			"
				<div class='row'>
					<table class='table table-bordered'>
						<tr>
							<th>ID</th>
							<th>TÍTULO RELATÓRIO</th>
							<th>TIPO RELATÓRIO</th>
							<th>TIPO ALIMENTO</th>
							<th>MÊS</th>
							<th>ANO</th>
							<th>DATA ENVIO</th>
							<th>STATUS</th>
							<th>AÇÃO</th>
						</tr>
			";
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
			{
				if( $data['status'] == 0 )
					$data['status'] = "<p class='text-danger' >desativado</p>";
				else
					$data['status'] = "<p class='text-success' >ativado</p>";
				
				$url = HOME_URI;
				$url .= "relatorios/exibirEntregueMes/{$data['id_relatorio']}/";
				echo "
					<tr>
						<td>{$data['id_relatorio']}</td>
						<td>{$data['titulo_relatorio']}</td>
						<td>{$data['tipo_relatorio']}</td>
						<td>{$data['tipo_alimento']}</td>
						<td>{$data['mes']}</td>
						<td>{$data['ano']}</td>
						<td>{$data['data_envio']}</td>
						<td>{$data['status']}</td>
						<td> <a href='{$url}' class='btn btn-default'>Abrir</a> </td>
					</tr>
					 ";
			}
			
			echo 
			"
			</table>
				</div>
			";
		}//if
	}//entregueMes()
	
	public function qtdPrevistaEstoqueNp()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$stm = 
			"
			SELECT abas.aba AS abas, agrs.agrupamento AS agrs, aliments.alimento AS alimentos, SUM(nanp.qtd_prevista) as qtd_prevista, SUM(nanp.estoque_unidade) as estoque_unidade FROM relatorios AS rels INNER JOIN registros_nanp AS nanp  ON rels.id_relatorio = nanp.fk_relatorio AND rels.tipo_relatorio = 'CONSIDERANDO O ESTOQUE DA UNIDADE' INNER JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma INNER JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba INNER JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento INNER JOIN alimentos AS aliments ON nanp.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'NÃO PERECÍVEL' AND abas.ano = ?
            GROUP BY abas.aba, agrs.agrupamento, aliments.id_alimento
			ORDER BY abas.aba ASC, agrs.agrupamento ASC, aliments.alimento ASC
			";
			
			$query = $this->db->query( $stm, array( $_POST['ano'] ) );
			$html = null;
			$header = 	
			"
				<tr>	
					<th>
						PRODUTOS
					</th>
					<th>
						PREVISTO ALTERADO
					</th>
					<th>
						ESTOQUE UNIDADE
					</th>
					<th>
						%
					</th>
				</tr>
				";
				
			$aux_aba = 0;
			$aux_agr = 0;
			
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
			{
			
				if( $aux_aba != $data['abas'] || $aux_agr != $data['agrs'] )
				{
					echo $header_aba = 
					"
						<td>
							<th>
								<p class='text-center' id='" . $data['abas'] . $data['agrs'] . "'>{$data['abas']}º ABA AGR {$data['agrs']}</p>
							</th>
							<th colspan='3'></th>
						</td>
					";
					
					echo $header;
					
					$html .= 
					"
						<li><a href='#".$data['abas'].$data['agrs']."'><p>{$data['abas']}º ABA AGR {$data['agrs']}</p></a></li>
					
					";
				}
				
				echo 
				"
				<tr>
					<td>{$data['alimentos']}</td>
					<td>".number_format($data['qtd_prevista'],2,',','.')."</td>
					<td>".number_format($data['estoque_unidade'],2,',','.')."</td>
					<td>".
					number_format( $porcentual = ( $data['estoque_unidade'] == 0 ) ? 0 : ($data['estoque_unidade'] / $data['qtd_prevista']), 2,',','.')
					."</td>
				</tr>					
				";
				
				
				
				//guarda o ultimo valor da varredura no array
				$aux_aba = $data['abas'];
				$aux_agr = $data['agrs'];
			}
			
			$html .= 
			"
				<li><a href='#total'><p>TOTALIZAÇÃO</p></a></li>
			";
			
			return $this->setIndice($html);
		}//if	
	}//qtdPrevistaEstoqueNp()
	
	
	
	public function listarRelatorios($status = 1)
	{
		
		$url = $_GET['path'];	
		
		// Limpa os dados
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
					
		// Cria um array de parâmetros
		$url = explode('/', $url);
		
		$pagina_atual = $url[2];
		$limite_pag = 15;
		$offset = $limite_pag * $pagina_atual;
		
			
		#QUERY
		$stm = 
		"
		SELECT relatorios.id_relatorio, relatorios.titulo_relatorio, relatorios.tipo_relatorio, relatorios.tipo_alimento, relatorios.mes, relatorios.ano, relatorios.data_envio, relatorios.status, agrs.agrupamento, aba.aba, aba.ano as ano_aba 
		FROM relatorios INNER JOIN cronograma as crono on relatorios.fk_cronograma = crono.id_cronograma INNER JOIN agrupamento as agrs on crono.fk_aba = agrs.id_agrupamento INNER JOIN abastecimento as aba on agrs.fk_abastecimento = aba.id_abastecimento 
		WHERE relatorios.status = ?
		GROUP BY id_relatorio
		ORDER BY relatorios.id_relatorio DESC LIMIT " .$limite_pag. " OFFSET " .$offset;
		
		$query = $this->db->query($stm, array($status));
		
		foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $data )
		{
			$url = HOME_URI;
			$url .= "relatorios/exibirRelatorio/{$data['id_relatorio']}/";
			
			if($data['status'] == 0)
				$status = "<p class='text-danger'>desativado</p>";
			
			else
				$status = "<p class='text-success'>ativo</p>";	
			
			
			echo "
				<tr>
					<td>{$data['id_relatorio']}_{$data['ano']}</td>";
					
					if( $data['tipo_relatorio'] == 'POR MÊS' ){
						echo "<td></td>";
					}
					else{ 
						echo "<td>{$data['aba']}º ABA AGR {$data['agrupamento']} {$data['ano_aba']}</td>";
					}
					
					echo "
					<td>{$data['titulo_relatorio']}</td>
					<td>{$data['tipo_relatorio']}</td>
					<td>{$data['tipo_alimento']}</td>
					<td>{$data['mes']}</td>
					<td>{$status}</td>
					<td><a href='{$url}' class='btn btn-default'>Abrir</a></td>
				</tr>
				 ";
		}
		
		return;
		
	}//listarRelatorios()
	
	public function paginacao($pag = null)
	{
		if($pag == null)
			return;
		
		$url = $_GET['path'];	
		
		// Limpa os dados
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
					
		// Cria um array de parâmetros
		$url = explode('/', $url);
				
		$pagina_atual = $url[2];
		$limite_pag = 15;
		$offset = $limite_pag * $pagina_atual;
						
		$query = $this->db->query("SELECT COUNT(id_relatorio) as cont FROM relatorios", array(null) );
				
		$indice = $query->fetchAll(PDO::FETCH_ASSOC);
				
		$cont = $indice[0]['cont'];
				
		$max = ceil($cont / $limite_pag);
		
		#PAGINAÇÃO
			echo "<div class='container text-center'>";
			echo "<ul class='pagination'>";
			
			if($pagina_atual >= 1)
				$prev = $pagina_atual - 1;
			else
				$prev = 1;
			
			if($pagina_atual < 1)
				echo "<li style='display:none;'><a href='". HOME_URI ."relatorios/$pag/$prev'></a></li>";
			else
				echo "<li><a href='" . HOME_URI ."relatorios/$pag/$prev'><i class='material-icons'>«</i></a></li>";
				$i = ($pagina_atual < $limite_pag ) ? 0 : $pagina_atual - 5;
				for($i;$i < $max; $i++)
				{
					if( (($i-$pagina_atual) / 10) >= 1)
						break;
					
					if($i == $pagina_atual)
						echo "<li class='active'><a href='".HOME_URI."relatorios/$pag/$i'>$i</a></li>"; 
					else
						echo "<li><a href='".HOME_URI."relatorios/$pag/$i'>$i</a></li>";
														
				}
			
			$next = $pagina_atual + 1;
			
			if($pagina_atual >= $max)
				echo "<li style='display:none;'><a href='#'><i>»</i></a></li>";
			else
				echo "<li><a href='".HOME_URI."relatorios/$pag/$next'><i>»</i></a></li>";	
			
			
			echo "</ul>";
			echo "</div>";
	}//paginacao()
	
	public function totalizacaoResumoAba()
	{	
		if($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST) )
		{
					echo "
					<p class='text-center' id='total'><strong>TOTALIZAÇÃO</strong></p>
					";
					$header = 	
								"
									<tr>	
										<th>
											PRODUTOS
										</th>
										<th>
											SIMULADO
										</th>
										<th>
											NECESSARIA S/
										</th>
										<th>
											AJUSTADA C/
										</th>
										<th>
											%
										</th>
									</tr>
								";
					echo $header;
					
					$relatorio_data_html = array();
			
			foreach($this->totalSimulado() as $data_simulado)
			{
				$relatorio_data_html[] = array(
				'alimento' => $data_simulado['alimento'],
				'simulado' => $data_simulado['simulado'],
				'necessaria' => 0,
				'ajustada' => 0,
				'porcentagem' => 0
				);	
			}
			
			foreach($this->totalExplodido() as $data_explodido)
			{
				$cont = 0;
				for($i = 0; $i < count($relatorio_data_html); $i++ )
				{
					if($relatorio_data_html[$i]['alimento'] == $data_explodido['alimento'])
					{
						$relatorio_data_html[$i]['necessaria'] += $data_explodido['prevista'];
						$relatorio_data_html[$i]['ajustada'] += $data_explodido['explodido'];
						$cont++;
					}
					
					if($cont == count($relatorio_data_html) )
					{
						$relatorio_data_html[] = array(
						'alimentos' => $data_explodido['alimento'],
						'simulado' => 0,
						'explodido' => $data_explodido['prevista'],
						'entregue' => 0
						);
					}
				}
				
				
			}
					
					$relatorio = multiSort($relatorio_data_html, 'alimento');
					
					//atribuindo resultado da consulta e ordenação do relatório no escopo global da model para a totalizacao
					
					
											
					for($i = 0; $i < count($relatorio); $i++)
					{
						$porcentagem = number_format(
						$porcentual = ( $relatorio[$i]['ajustada'] != 0 ) ? ( ($relatorio[$i]['ajustada'] / $relatorio[$i]['necessaria']) - 1 )* 100: 0, 2 );
						
						$relatorio[$i]['porcentagem'] = $porcentagem;
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>"
							.number_format($relatorio[$i]['simulado'], 2, ',', '.').
							"</td>
							<td>".
							number_format($relatorio[$i]['necessaria'], 2, ',', '.')
							."</td>
							<td>".
							number_format($relatorio[$i]['ajustada'], 2, ',', '.')
							."</td>
							<td>{$relatorio[$i]['porcentagem']}</td>
						</tr>					
						";	
					}//for
					return;
		}//if
		return;
	}//totalizacaoResumoAba()
	
	public function totalizacaoAbastecimentoNP()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$relatorio_data_html = array();
			
			foreach($this->totalSimulado() as $data_simulado)
			{
				$relatorio_data_html[] = array(
				'alimento' => $data_simulado['alimento'],
				'simulado' => $data_simulado['simulado'],
				'explodido' => 0,
				'entregue' => 0
				);	
			}
			
			foreach($this->totalExplodido() as $data_explodido)
			{
				$cont = 0;
				for($i = 0; $i < count($relatorio_data_html); $i++ )
				{
					if($relatorio_data_html[$i]['alimento'] == $data_explodido['alimento'])
					{
						$relatorio_data_html[$i]['explodido'] += $data_explodido['explodido'];
						$cont++;
					}
					
					if($cont == count($relatorio_data_html) )
					{
						$relatorio_data_html[] = array(
						'alimentos' => $data_explodido['alimento'],
						'simulado' => 0,
						'explodido' => $data_explodido['explodido'],
						'entregue' => 0
						);
					}
				}
			}
			
			foreach($this->totalEntregue() as $data_entregue)
			{
				$cont = 0;
				for($i = 0; $i < count($relatorio_data_html); $i++ )
				{
					if($relatorio_data_html[$i]['alimento'] == $data_entregue['alimento'])
					{
						$relatorio_data_html[$i]['entregue'] += $data_entregue['entregue'];
						$cont++;
					}
					
					if($cont == count($relatorio_data_html) )
					{
						$relatorio_data_html[] = array(
						'alimento' => $data_entregue['alimento'],
						'simulado' => 0,
						'explodido' => 0,
						'entregue' => $data_entregue['entregue']
						);
					}
				}
			}
			
			$relatorio = multiSort($relatorio_data_html, 'alimento');
					
				echo "
					<p class='text-center' id='total'><strong>TOTALIZAÇÃO</strong></p>
					";
					echo $header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									SIMULADO
								</th>
								<th>
									EXPLODIDO 
								</th>
								<th>
									ENTREGUE 
								</th>
							</tr>
						";
					
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>".
							number_format($relatorio[$i]['simulado'], 2, ',', '.')
							."</td>
							<td>".
							number_format($relatorio[$i]['explodido'], 2, ',', '.')
							."</td>
							<td>".
							number_format($relatorio[$i]['entregue'], 2, ',', '.')
							."</td>
						</tr>					
						";
						
					}//for
					
			return;
		}//if	
		return;
	}//totalizacaoAbastecimentoNP()
	public function totalSimulado()
	{
		$stm = "
			SELECT alis.alimento as alimento,Sum(regsimus.qtd_ajustada) as simulado FROM  relatorios as rels 
			INNER JOIN `registros_simulado` as regsimus on rels.id_relatorio = regsimus.fk_relatorio
			INNER JOIN  cronograma as cros on rels.fk_cronograma = cros.id_cronograma
			INNER JOIN agrupamento as agrs on agrs.id_agrupamento = cros.fk_aba
			INNER Join abastecimento as abas on agrs.fk_abastecimento=abas.id_abastecimento
			INNER JOIN alimentos as alis on  regsimus.fk_alimento= alis.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_relatorio='Simulado' AND abas.ano = ?
			Group by alis.alimento
			ORDER BY alis.alimento ASC";
				
		$query = $this->db->query($stm, array($_POST['ano']));
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function totalExplodido()
	{
		$stm = "
			SELECT aliments.alimento AS alimento, SUM(regsnp.qtd_ajustada) as explodido, SUM(regsnp.qtd_prevista) as prevista  FROM relatorios AS rels 
			INNER JOIN registros_nanp AS regsnp  ON rels.id_relatorio = regsnp.fk_relatorio 
			INNER JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma 
			INNER JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba 
			INNER JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento 
			INNER JOIN alimentos AS aliments ON regsnp.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'Não Perecível' AND rels.tipo_relatorio = 'SEM CONSIDERAR O ESTOQUE DA UNIDADE' AND abas.ano = ?
			GROUP BY aliments.alimento
			ORDER BY aliments.alimento ASC";
		
		$query = $this->db->query($stm, array($_POST['ano']));
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function totalEntregue()
	{
		$stm = "
			SELECT aliments.alimento AS alimento, SUM(resumo.totalizacao) as entregue FROM relatorios AS rels
			INNER JOIN registros_resumo AS resumo  ON rels.id_relatorio = resumo.fk_relatorio AND resumo.situacao = 'ENTREGUE+ENTREGUE' 
			INNER JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma 
			INNER JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba 
			INNER JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento 
			INNER JOIN alimentos AS aliments ON resumo.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'Não Perecível'  AND abas.ano = 2018 AND rels.tipo_relatorio = 'POR ABASTECIMENTO'
			GROUP BY aliments.alimento
			ORDER BY aliments.alimento ASC";
		
		$query = $this->db->query($stm, array($_POST['ano']));
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	public function totalizacaoAbastecimentoPER()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{	
	
			$stm =  "
			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				LEFT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				LEFT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				LEFT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				LEFT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				LEFT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
			
			WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'PERECÍVEL' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
			GROUP BY alimentos.alimento
            
			UNION            

			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				RIGHT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				RIGHT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				RIGHT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				RIGHT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				RIGHT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
            
            WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'PERECÍVEL' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
            GROUP BY alimentos.alimento
			";
			$ano = $_POST['ano'];
			$query = $this->db->query($stm, array($ano, $ano));
			$relatorio_data_html = array();
					
			echo "<p class='text-center' id='total'><strong>TOTALIZAÇÃO</strong></p>";		
					
					foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
					{
							$relatorio_data_html[]= array( 
								'abastecimento' => $data['aba'],
								'agrupamento'  =>  $data['agrupamento'], 
								'alimento' => $data['alimento'], 
								'totalizacao' => number_format($data['totalizacao'], 2, ',', '.'), 
							);	
					}//foreach
					
					array_sort($relatorio_data_html,'abastecimento', SORT_ASC);
					
					$relatorio = multiSort($relatorio_data_html, 'alimento');
					
					
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						
						$header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									ENTREGUE 
								</th>
							</tr>
						";
							
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>{$relatorio[$i]['totalizacao']}</td>
						</tr>					
						";
					}//for
				
			return;
		}//if	
		return;	
	}//totalizacaoAbastecimentoPER()
	
	public function totalizacaoAbastecimentoFLVO()
	{
		
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{	
	
			$stm =  "
			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				LEFT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				LEFT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				LEFT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				LEFT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				LEFT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
			
			WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'FEIRA' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
			GROUP BY alimentos.id_alimento
			
			UNION            

			SELECT abastecimento.aba, agrupamento.agrupamento, alimentos.alimento, SUM(registros_resumo.totalizacao) as totalizacao, registros_resumo.situacao, relatorios.status FROM relatorios
				
				RIGHT JOIN registros_resumo ON relatorios.id_relatorio = registros_resumo.fk_relatorio  
				RIGHT JOIN cronograma ON relatorios.fk_cronograma = cronograma.id_cronograma
				RIGHT JOIN agrupamento ON agrupamento.id_agrupamento = cronograma.fk_aba
				RIGHT JOIN abastecimento ON agrupamento.fk_abastecimento = abastecimento.id_abastecimento
				RIGHT JOIN alimentos ON registros_resumo.fk_alimento = alimentos.id_alimento 
            
            WHERE relatorios.tipo_relatorio='POR ABASTECIMENTO' AND relatorios.tipo_alimento = 'FEIRA' AND registros_resumo.situacao = 'ENTREGUE+ENTREGUE' AND relatorios.status = 1 AND abastecimento.ano = ?
            GROUP BY alimentos.id_alimento
			";
			
			$query = $this->db->query($stm, array($_POST['ano'],$_POST['ano']));
			$relatorio_data_html = array();
					
					
					foreach($query->fetchAll(PDO::FETCH_ASSOC) as $data)
					{
							$relatorio_data_html[]= array( 
								'abastecimento' => $data['aba'],
								'agrupamento'  =>  $data['agrupamento'], 
								'alimento' => $data['alimento'], 
								'totalizacao' => number_format($data['totalizacao'], 2, ',', '.'), 
							);	
					}//foreach
					
					array_sort($relatorio_data_html,'abastecimento', SORT_ASC);
					
					$relatorio = multiSort($relatorio_data_html, 'alimento');
					
					
					
					echo "
					<p class='text-center' id='total'><strong>TOTALIZAÇÃO</strong></p>
					";
					for($i = 0; $i < count($relatorio); $i++)
					{
						
						
						$header = 	
						"
							<tr>	
								<th>
									PRODUTOS
								</th>
								<th>
									ENTREGUE 
								</th>
							</tr>
						";
							
							
						echo 
						"
						<tr>
							<td>{$relatorio[$i]['alimento']}</td>
							<td>{$relatorio[$i]['totalizacao']}</td>
						</tr>					
						";
						
					}//for
					
			return;
		}//if	
		return;		
	}//totalizacaoAbastecimentoFLVO()
	
	public function totalizacaoQtdPrevista()
	{
		if( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) )
		{	
		#query totalizacao	
			$stm_total = 
			"
			SELECT aliments.alimento AS alimentos, SUM(nanp.qtd_prevista) as qtd_prevista, SUM(nanp.estoque_unidade) as estoque_unidade  FROM relatorios AS rels INNER JOIN registros_nanp AS nanp  ON rels.id_relatorio = nanp.fk_relatorio AND rels.tipo_relatorio = 'CONSIDERANDO O ESTOQUE DA UNIDADE' INNER JOIN cronograma as cros ON rels.fk_cronograma = cros.id_cronograma INNER JOIN agrupamento AS agrs ON agrs.id_agrupamento = cros.fk_aba INNER JOIN abastecimento AS abas ON agrs.fk_abastecimento = abas.id_abastecimento INNER JOIN alimentos AS aliments ON nanp.fk_alimento = aliments.id_alimento 
			WHERE rels.status = 1 AND rels.tipo_alimento = 'NÃO PERECÍVEL' AND abas.ano = ?
            GROUP BY aliments.alimento
			ORDER BY aliments.alimento ASC
			";
			$query = $this->db->query($stm_total, array($_POST['ano']) );
			$totalizacao = $query->fetchAll(PDO::FETCH_ASSOC);
			
			echo "
			<p class='text-center' id='total'><strong>TOTALIZAÇÃO</strong></p>
			";
			
			echo 
			"
				<tr>
					<th>PRODUTOS</th>
					<th>PREVISTO ALTERADO</th>
					<th>ESTOQUE UNIDADE</th>
					<th>%</th>
				</tr>
			";
			foreach($totalizacao as $sum_data)
			{
				echo "<tr>";
				echo "<td>".$sum_data['alimentos']."</td>";	
				echo "<td>".number_format($sum_data['qtd_prevista'], 2, ',', '.')."</td>";	
				echo "<td>".number_format($sum_data['estoque_unidade'], 2, ',', '.')."</td>";
				echo "<td>".number_format( $porcentual = ( $sum_data['estoque_unidade'] == 0 ) ? 0 : ($sum_data['estoque_unidade'] / $sum_data['qtd_prevista']), 2, ',', '.')."</td>";	
				echo "</tr>";	
			}
			
		}	
	}//totalizacaoQtdPrevista()
	
}//class ListagemRelatoriosModel