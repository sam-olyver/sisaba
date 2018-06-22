<?php

	class CadastrarAbastecimentoModel extends MainModel
	{
		public $db;
		
		public function __construct(){$this->db = new MyPdo();}
	
		
		
		public function inserirAba()
		{
			if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST))
			{
				//executa uma consulta para verificar se existe os dados cadastrados na base
				$res = $this->db->query(
				"SELECT ano, aba FROM abastecimento WHERE ano = ? AND aba = ?", array($_POST['ano'], ($_POST['aba'])
				));
				
				//recuperando os dados	
				foreach($res->fetchAll(PDO::FETCH_ASSOC) as $value){}
				
				//se a consulta retorna vazia formata o array $value para comparação
				if(!isset($value) || empty($value) )
				{
					$value = array(
					'ano' =>  null,
					'aba' => null);
				}
				//compara $value com o POST do usuario
				if($_POST['ano'] != $value['ano'] && $_POST['aba'] != $value['aba'])
				{
					//se os dados forem diferentes insere os novos registros
					try{
						//iniciando transação
						$this->db->pdo->beginTransaction();
						
						//insere o novo abastecimento
						$query_aba = $this->db->insert('abastecimento',
						array(
						'ano' => $_POST['ano'],
						'aba' => $_POST['aba']
						));
						
						//recupera a id do abastecimento	
						$fk = $this->db->pdo->lastInsertId();
						
						//insere a atividade na tabela de log
						$this->ultimoRegistro($fk);
						
						//insere os 4 agr na tabela de agrupamentos 
						$query_agr4 = $this->db->insert('agrupamento',
						array(
						'fk_abastecimento' => $fk,
						'agrupamento' => 4
						));
						$fk_agr4 = $this->db->pdo->lastInsertId();
						
						$query_agr3 = $this->db->insert('agrupamento',
						array(
						'fk_abastecimento' => $fk,
						'agrupamento' => 3
						));
						$fk_agr3 = $this->db->pdo->lastInsertId();
						
						$query_agr2 = $this->db->insert('agrupamento',
						array(
						'fk_abastecimento' => $fk,
						'agrupamento' => 2
						));
						$fk_agr2 = $this->db->pdo->lastInsertId();
						
						$query_agr1 = $this->db->insert('agrupamento',
						array(
						'fk_abastecimento' => $fk,
						'agrupamento' => 1
						));
						$fk_agr1 = $this->db->pdo->lastInsertId();
						
						//insere a fk no cronograma
						$query_crono1 = $this->db->insert('cronograma',
						array(
						'fk_aba' => $fk_agr4
						));
						
						$query_crono2 = $this->db->insert('cronograma',
						array(
						'fk_aba' => $fk_agr3
						));
						
						$query_crono3 = $this->db->insert('cronograma',
						array(
						'fk_aba' => $fk_agr2
						));
						
						$query_crono4 = $this->db->insert('cronograma',
						array(
						'fk_aba' => $fk_agr1
						));
						
						//insere o abastecimento e os 4 agrupamentos na tabela de registros relatorios
						$query_registros4 = $this->db->insert('registros_relatorios',
						array(
						'aba' => $fk,
						'agr' => $fk_agr4
						));
						
						$query_registros3 = $this->db->insert('registros_relatorios',
						array(
						'aba' => $fk,
						'agr' => $fk_agr3
						));
						
						$query_registros2 = $this->db->insert('registros_relatorios',
						array(
						'aba' => $fk,
						'agr' => $fk_agr2
						));
						
						$query_registros1 = $this->db->insert('registros_relatorios',
						array(
						'aba' => $fk,
						'agr' => $fk_agr1
						));
						
						//encerrando transação
						$this->db->pdo->commit();
						unset($_POST);
						unset($_SERVER['REQUEST_METHOD']);
					} catch (PDOException $exception) {
						//removendo alterações
						$this->db->rollback();
						printf('Não foi possível realizar a operação: %s' , $exception);
						unset($_SERVER['REQUEST_METHOD']);
						unset($_POST);
					} finally { 
						//exibindo erros
						$codigo = $this->db->pdo->errorCode();
						$info = $this->db->pdo->errorInfo();
						return;}
				}//if SELECT
			}//if POST
			unset($_POST);
			unset($_SERVER['REQUEST_METHOD']);
			return;
		}//inserirAba()
		
		private function ultimoRegistro($fk)
		{	
			date_default_timezone_set('America/Sao_Paulo');
			$dataLocal = date("F j, Y, g:i a", time());
			echo $html = "<p class='bg-success'>Abastecimento salvo com sucesso ID: #$fk</p>";
			
			$this->db->insert('log', array(
				'data_registro' => date('y-m-d'),
				'titulo_atividade' => 'Novo cadastro de abastecimento',
				'resumo_atividade' => "cadastro do abastecimento em $dataLocal",
				'user_action' => $_SESSION['userdata']['user_id'],
				'fk_abastecimento' => $fk	
			));
			
			return $fk;
		}
	}//class CadastrarAbastecimentoModel