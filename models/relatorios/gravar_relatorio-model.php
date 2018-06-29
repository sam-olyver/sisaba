<?php
	
	class GravarRelatorioModel extends MainModel
	{
		public $db;
		public $obj;
		public $table;
		public $campos_registro;
		public $fk_relatorio;
		public $id_cronograma;
		public $alimentos_after_insert = array();
		public $id_alimentos_exist = array();
		public $alimentos_exist = array();
		public $alimentos_not_exist = array();
		
		#################
		
		public function __construct(){$this->db = new MyPdo();}
		
		public function setObj($object){$this->obj = $object;}
		
		public function getObj(){return $this->obj;}
		
		public function getFkTipoAlimento()
		{
			$query_fk_tipo_alimento = $this->db->query("SELECT id_tipo_alimento FROM tipo_alimento WHERE tipo_alimento = ?", array($this->obj->obj->cabecalho['tipo_alimento']));
			
			foreach($query_fk_tipo_alimento->fetchAll(PDO::FETCH_ASSOC) as $fk){}
			
			return $fk['id_tipo_alimento'];
		}
		
		public function getAbastecimento($key)
		{
			if( empty($key) )
				return;
			
			$stm = "SELECT aba FROM abastecimento WHERE id_abastecimento = ?";
			$query = $this->db->query($stm, array($key));
			$abastecimento = $query->fetchAll(PDO::FETCH_ASSOC);
			
			return $abastecimento[0]['aba']; 
		}
		
		public function salvarRelatorio()
		{
			if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST))
			{
				try{	
				//inicia a transação
				$this->db->pdo->beginTransaction();
				
				//recupera a id do cronograma
				$fk_cronograma = $this->recuperaCronograma();
				
				//passo 1, inserir dados na tabela relatorios
				$query_relatorio_fk = $this->inserirRelatorios($fk_cronograma);
				
				//passo 2 e 3  comparar alimentos e verificar se existe, se não existir insere na base
				$query_compara_alimentos = $this->compararAlimentos();
				
				//passo 4, insere os alimentos nas tabelas de registros referenciando o fk da tabela 'relatorio'
				$query_inserir_registros = $this->inserirRegistros($query_relatorio_fk);
				
				//passo 5 e 6, seleciona 'registros_relatorio' aba e compara com o selecionado pelo usuario e insere/atualiza a fk
				
				$query_registrar_relatorios = $this->registrarRelatorios($fk_cronograma);
				
				//encerra a transação
				$this->db->pdo->commit();
				
				}catch (PDOException $exception){
						//removendo alterações
						$this->db->rollback();
						printf('Não foi possível realizar a operação: %s' , $exception);
						unset($_SERVER['REQUEST_METHOD']);
						unset($_POST);
					}finally 
					{ 
						//exibindo erros
						unset($_POST);
						unset($_SERVER['REQUEST_METHOD']);
						$codigo = $this->db->pdo->errorCode();
						$info = $this->db->pdo->errorInfo();
						return;
					}
			}//if		
			return;
		}//salvarRelatorio()
		
		private function recuperaCronograma()
		{
			if( $this->obj->obj->cabecalho['tipo_relatorio'] == 'POR MÊS' )
				return 1;
			
			$fk = $this->db->query(
				"SELECT id_cronograma FROM cronograma WHERE fk_aba = (SELECT id_agrupamento FROM agrupamento WHERE fk_abastecimento = ? AND agrupamento = ?)",				
				array(
					$this->obj->obj->cabecalho['abastecimento'], $this->obj->obj->cabecalho['agrupamento']
				));
			$this->id_cronograma = $fk->fetchAll(PDO::FETCH_ASSOC);
			
			return $this->id_cronograma[0]['id_cronograma'];			
		}//recuperaCronograma()
		
		//passo 1
		private function inserirRelatorios($fk = null)
		{			
			if( $this->obj->obj->cabecalho['tipo_relatorio'] == 'POR MÊS' )
				$fk = 1;
			else
				if($fk === null)
					return;	
				
			$tipo = $this->obj->obj->cabecalho['tipo_relatorio'];
			
			
			$query = $this->db->insert('relatorios', array(
				'fk_cronograma' => $fk,
				'fk_registros' => null,
				'titulo_relatorio' => $this->obj->obj->cabecalho['titulo_relatorio'],
				'tipo_relatorio' =>  $this->obj->obj->cabecalho['tipo_relatorio'],
				'tipo_alimento' => $this->obj->obj->cabecalho['tipo_alimento'],
				'periodo' => ( isset($this->obj->obj->cabecalho['periodo']) ) ? $this->obj->obj->cabecalho['periodo'] : null,
				'mes'	=> ( isset($_POST['mes']) ) ? $_POST['mes'] : null,
				'ano'	=> ( isset($_POST['ano']) ) ? $_POST['ano'] : null,		
				'data_envio' => date('Y-m-d'),
				'data_entrega' => ( $this->obj->obj->cabecalho['titulo_relatorio'] == 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS' ) ? $this->inverte_data($this->obj->obj->cabecalho['data_entrega']) : null,
				'status' => 1,
				'serialize_file' => serialize($this->obj->obj->get_dados_obj())
			));
			
			$this->fk_relatorio = $this->db->pdo->lastInsertId();	
			
			echo $html = "<p class='bg-success'>Relatório salvo com sucesso ID: #{$this->fk_relatorio}</p>";
			
			
			return  $this->ultimosRelatorios($this->fk_relatorio);
		}//inserirRelatorios()
		
		private function ultimosRelatorios($fk)
		{	
			date_default_timezone_set('America/Sao_Paulo');
			$dataLocal = date("F j, Y, g:i a", time());
			$this->db->insert('log', array(
				'data_registro' => date('Y-m-d'),
				'titulo_atividade' => 'Novo relatório adicionado',
				'resumo_atividade' => "relatório ID: #$fk adicionado em $dataLocal",
				'user_action' => $_SESSION['userdata']['user_id'],
				'fk_relatorio' => $fk	
			));
			
			return $fk;
		}
		//passo 2
		private function compararAlimentos()
		{
			//recupera os alimentos populados no objeto
			$alimentos = $this->obj->obj->getAlimento();
			 
			//contador do objeto relatorio
			$lenght_array = count($alimentos);
			
			$fk_tipo_alimento = $this->getFkTipoAlimento();
			
			//recupera os alimentos da base de dados
			$query_recupera_alimentos = $this->db->query(
			"SELECT * FROM alimentos",
			array(null)
			);
			
			//conta quantas linhas a consulta retornou
			$lenght_db = $query_recupera_alimentos->rowCount();
			
			if( $lenght_db == 0)
			{
				$this->db->insert('alimentos', array(
					'alimento' => 'teste',
					'fk_tipo_alimento' => 1
				));	
				
				$query_recupera_alimentos = $this->db->query(
				"SELECT * FROM alimentos",
				array(null)
				);
				
				//conta quantas linhas a consulta retornou
				$lenght_db = $query_recupera_alimentos->rowCount();
			}
			
			//se tiver mais de uma linha iremos varrer o objeto
			if( $lenght_db > 0 )
			{
				$resultado_consulta = array();
				
				foreach( $query_recupera_alimentos->fetchAll(PDO::FETCH_ASSOC) as $res )
				{
					$resultado_consulta[] = array(
						'id_alimento' 		=> $res['id_alimento'],
						'alimento'	 		=> $res['alimento'], 
						'fk_tipo_alimento' 	=> $res['fk_tipo_alimento']
					);	
				}
				
				for($i = 0; $i < count($alimentos); $i++)
				{
					$not_exist_count = 0;
					for($j = 0; $j < count($resultado_consulta); $j++)
					{
						
						if( $alimentos[$i] == $resultado_consulta[$j]['alimento']  )
						{
							$this->alimentos_exist[] = $resultado_consulta[$j];
						}
						else
						{
							$not_exist_count++;
							if( $not_exist_count == count($resultado_consulta) )
							{
								if(  ! in_array($alimentos[$i], $this->alimentos_not_exist) )
								{
									$this->alimentos_not_exist[] = $alimentos[$i];
								}//if
							}		
						}//else	
					}//for		
				}//for	
			}//if
			else
				return;
			
			if( ! empty($this->alimentos_not_exist) )
			{
				$this->alimentos_not_exist['fk_tipo_alimento'] = $fk_tipo_alimento;				
				$this->inserir_alimentos_not_exist($this->alimentos_not_exist);
			}
			else
			{
				return;
			}
		}//compararAlimentos()
		
		//passo 3
		private function inserir_alimentos_not_exist($foods = array())
		{
			if(empty($foods))
				return;
			
			$lenght = count($foods);
			
			// -1 por causa do ultimo registro ser a fk_alimento
			for($i = 0; $i < ($lenght-1); $i++)
			{	
				$this->db->insert('alimentos', array(
					'alimento' => $foods[$i],
					'fk_tipo_alimento' => $foods['fk_tipo_alimento']
				));
				
				$this->alimentos_exist[] = array(
					'id_alimento' => $this->db->pdo->lastInsertId(),
					'alimento' => $foods[$i],
					'fk_tipo_alimento' => $foods['fk_tipo_alimento']
				);	
				
			}
			return;
		}//inserir_alimentos_not_exist()
		
		//passo 4
		private function inserirRegistros($fk_last_id = null)
		{
			
			//sai da função caso não receba a lastID
			if($fk_last_id == null)
				return;
			
			
			//compara e define a tabela de registros
			switch($this->obj->obj->cabecalho['titulo_relatorio'])
			{
				case 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS':
				$this->table = 'registros_nanp';
				break;
				
				case 'RESUMO DE TOTALIZAÇÃO':
				$this->table = 'registros_resumo';
				break;
				
				case 'RESUMO TOTALIZAÇÃO':
				$this->table = 'registros_resumo';
				break;
				
				case 'SIMULADO - RELATORIO NECESSIDADE DE NAO PERECIVEIS POR ENTREGA NO AGRUPAMENTO':
				$this->table = 'registros_simulado';
				break;
				
				default:
					return;
			}
			
			//recupera os alimentos do banco de dados
			$query = $this->db->query('SELECT * FROM alimentos', array(null));
			
			//conta quantas linhas o objeto contem para ser usado como contandor nos proximos laços de insert
			$lenght_array = count($this->obj->obj->getAlimento());
			
			//conta quantas linhas retornaram do banco
			$lenght_db = $query->rowCount();
			
			//recupera os alimentos do objeto
			$alimentos = $this->obj->obj->getAlimento();
			$array_fk_alimentos = array();
			//varre o objeto e atribui a um array
			foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
			{
				$array_fk_alimentos[] = array(
					'id_alimento' => $res['id_alimento'],
					'alimento' => $res['alimento'],
					'fk_tipo_alimento' => $res['fk_tipo_alimento']
				);
			}
			
			//contador que sera usado para comparar se os alimentos do objeto e do banco de dados são iguais
			$iguais = 0;
			
			for($i = 0; $i < $lenght_array; $i++)
			{
				if( search_for_value($alimentos[$i], $array_fk_alimentos, true) ) 
					$iguais++;					
			}
			
			//se o contador do array for igual ao contador de registro comparado com o banco de dados, significa que os alimentos estão registrados
			
			if($lenght_array === $iguais)
			{
				if($this->table == 'registros_nanp')
				{
					$i = 0;
					while( array_key_exists($i, $this->obj->obj->alimento) )
					{
						$arg1 = $this->obj->obj->alimento[$i];
						
						$query = $this->db->query('SELECT * FROM alimentos WHERE alimento = ?', array($arg1) );
						
						foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
						{$alimento_fk = $res['id_alimento'];}
						
						$this->db->insert($this->table, array(
						'fk_relatorio' => $fk_last_id,
						'fk_alimento' => $alimento_fk,
						'qtd_prevista' => $this->obj->obj->quantidade_prevista[$i],
						'fator' => $this->obj->obj->fator[$i],
						'estoque_unidade' => $this->obj->obj->estoque_unidade[$i],
						'qtd_ajustada' => $this->obj->obj->quantidade_ajustada[$i],
						'capacidade' => $this->obj->obj->capacidade[$i],
						'total_embalagem' => $this->obj->obj->total_embalagem[$i]
						));
						$i++;
					}
				}
				
				if($this->table == 'registros_resumo')
				{
					$i = 0;
					
					while( array_key_exists($i, $this->obj->obj->alimento) )
					{
						$arg1 = $this->obj->obj->alimento[$i];
						
						$query = $this->db->query('SELECT * FROM alimentos WHERE alimento = ?', array($arg1) );
						
						foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
						{$alimento_fk = $res['id_alimento'];}
													
						$this->db->insert($this->table, array(
						'fk_relatorio' => $fk_last_id,
						'fk_alimento' => $alimento_fk,
						'situacao' => $this->obj->obj->situacao[$i],
						'total_embalagem_fechada' => $this->obj->obj->total_embalagem_fechada[$i],
						'total_embalagem_fracionada' => $this->obj->obj->total_embalagem_fracionada[$i],
						'totalizacao' => $this->obj->obj->totalizacao[$i],
						'periodo' => null
						));
						
						$i++;
					}
				}
				
				if($this->table == 'registros_simulado')
				{
					$i = 0;
					while( array_key_exists($i, $this->obj->obj->alimento) )
					{
						$arg1 = $this->obj->obj->alimento[$i];
						
						$query = $this->db->query('SELECT * FROM alimentos WHERE alimento = ?', array($arg1) );
						
						foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
						{$alimento_fk = $res['id_alimento'];}	
						
						$this->db->insert($this->table, array(
						'fk_relatorio' => $fk_last_id,
						'fk_alimento' => $alimento_fk,
						'data_entrega' => $this->obj->obj->datas_entrega[$i],
						'qtd_necessaria' => $this->obj->obj->quantidade_necessaria[$i],
						'qtd_ajustada' => $this->obj->obj->quantidade_ajustada[$i],
						'qtd_embalagem' => $this->obj->obj->quantidade_embalagem[$i]
						));
						$i++;
					}
				}
			}//if
			else
			{
				return;
			}
		}//inserirRegistros()
		//passo 6
		private function registrarRelatorios($fk = null)
		{
			if( empty($fk) )
				return;
			
			if( $this->obj->obj->cabecalho['titulo_relatorio'] == 'RESUMO TOTALIZAÇÃO' && $this->obj->obj->cabecalho['tipo_relatorio'] == 'POR MÊS' )
				return;
			
			$consulta = $this->db->query("SELECT fk_abastecimento FROM `agrupamento` WHERE id_agrupamento = (SELECT fk_aba FROM cronograma WHERE id_cronograma = ?)",
			array(
				$fk
			));
			$parametro = $consulta->fetchAll(PDO::FETCH_ASSOC);
			
			if($parametro[0]['fk_abastecimento'] === $_POST['abastecimento'])
			{
				
			    //*
				$query = $this->db->query("SELECT * FROM registros_relatorios WHERE agr = (SELECT id_agrupamento FROM agrupamento WHERE agrupamento = ? AND fk_abastecimento = ? ) AND aba = ?", 
				array(
					$this->obj->obj->cabecalho['agrupamento'],
					$_POST['abastecimento'],
					$_POST['abastecimento']
				));
				
				foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res){}
				
				if($res['aba'] === $parametro[0]['fk_abastecimento'])
				{
					
					$update_campos = $this->recuperarCamposUpdate($this->fk_relatorio);
					
					$agrupamento = $this->db->query('SELECT id_agrupamento FROM agrupamento WHERE fk_abastecimento = ? AND agrupamento = ?',
					array(
						$_POST['abastecimento'], 
						$_POST['agrupamento']
					));
					
					foreach($agrupamento->fetchAll(PDO::FETCH_ASSOC) as $agr){}
					
					
					if($update_campos == null)
						return;
					
					$abastecimento = $res['aba'];
					$agrupamento = $agr['id_agrupamento'];
					$stmt = "UPDATE `registros_relatorios` SET $update_campos = :fk_relatorio WHERE aba = :aba AND agr = :agr";
					
					$update = $this->db->pdo->prepare( $stmt );
					$update->bindParam(":this->fk_relatorio", $this->fk_relatorio, PDO::PARAM_INT);
					$update->bindParam(":aba", $abastecimento, PDO::PARAM_INT);
					$update->bindParam(":agr",$agrupamento, PDO::PARAM_INT);
					$data_array = array(
						':fk_relatorio'=>(int)$this->fk_relatorio,
						':aba' =>(int)$res['aba'],
						':agr'=>(int)$agr['id_agrupamento']
					);
					
					$check_exec = $update->execute($data_array);
					
					$fk_registro = $this->db->query("SELECT id_registros FROM registros_relatorios WHERE aba = ? AND agr = ?", array($res['aba'], $agr['id_agrupamento']));
					
					
					
					return $this->insertFkRegistro($fk_registro->fetchAll(PDO::FETCH_ASSOC));
				}
			}
			
		}//registrarRelatorios()
		
		private function insertFkRegistro($fk)
		{
			
			if(empty($fk))
				return;
			
			$update = $this->db->update('relatorios', 'id_relatorio', $this->fk_relatorio, 
			array(
				'fk_registros' => $fk[0]['id_registros']
			));
			
			return;
		}
		
		//passo 5
		private function recuperarCamposUpdate($key = null)
		{
			if($key == null)
				return;
			
			#NANP
			if( $this->obj->obj->cabecalho['titulo_relatorio'] == 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS' && $this->obj->obj->cabecalho['tipo_relatorio'] == 'CONSIDERANDO O ESTOQUE DA UNIDADE')
			{
				return 'fk_registros_nanp_considerando';				
			}
			
			if( $this->obj->obj->cabecalho['titulo_relatorio'] == 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS' && $this->obj->obj->cabecalho['tipo_relatorio'] == 'SEM CONSIDERAR O ESTOQUE DA UNIDADE')
			{
				return 'fk_registros_nanp_sem_considerar';		
			}
			
			#RESUMO  - ABA
			if( $this->obj->obj->cabecalho['titulo_relatorio'] == 'RESUMO DE TOTALIZAÇÃO' && $this->obj->obj->cabecalho['tipo_relatorio'] == 'POR ABASTECIMENTO' && $this->obj->obj->cabecalho['tipo_alimento'] == 'NÃO PERECÍVEL')
			{
				return 'fk_registros_resumo_aba_np';	
			}
			
			if( $this->obj->obj->cabecalho['titulo_relatorio'] == 'RESUMO DE TOTALIZAÇÃO' && $this->obj->obj->cabecalho['tipo_relatorio'] == 'POR ABASTECIMENTO' && $this->obj->obj->cabecalho['tipo_alimento'] == 'PERECÍVEL')
			{
				return 'fk_registros_resumo_aba_per';		
			}
			
			if( $this->obj->obj->cabecalho['titulo_relatorio'] == 'RESUMO DE TOTALIZAÇÃO' && $this->obj->obj->cabecalho['tipo_relatorio'] == 'POR ABASTECIMENTO' && $this->obj->obj->cabecalho['tipo_alimento'] == 'FEIRA')
			{
				return 'fk_registros_resumo_aba_flvo';		
			}
			
			#SIMULADO
			if( $this->obj->obj->cabecalho['tipo_relatorio'] == 'SIMULADO')
			{
				return 'fk_registros_simulado';		
			}
			
		}//recuperarCamposUpdate()
		
	}//GravarRelatorioModel 
?>