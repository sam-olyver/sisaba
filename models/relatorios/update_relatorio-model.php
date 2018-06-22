<?php

class UpdateRelatorioModel extends MainModel
{
	public $db;
	public $update_file = null;
	
	
	public function __construct(){$this->db = new MyPdo();}
	
	
	public function updateRelatorio($table, $id)
	{
		//if($this->update_file == null)
			//return;
		
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			
			
			/*if($table == 'nanp')
			{
				$query = $this->db->insert("{$table}",
					array(
						'data_envio' => $this->head['data_envio'],
						'titulo_relatorio' => $this->head['titulo_relatorio'],
						'tipo_relatorio' => $this->head['tipo_relatorio'],
						'tipo_alimento' => $this->head['tipo_alimento'],
						'data_entrega' => $this->inverte_data($this->head['data_entrega']),
						'agrupamento' => $this->head['agrupamento'],
						'abastecimento' => $this->head['abastecimento'],
						"relatorio_{$table}" => serialize($relatorio)
				));	
			}	
			
			if($table == 'resumo')
			{
				$query = $this->db->insert("{$table}",
					array(
						'titulo_relatorio' => $this->head['titulo_relatorio'],
						'tipo_relatorio' => $this->head['tipo_relatorio'],
						'agrupamento' => $this->head['agrupamento'],
						'abastecimento' => $this->head['abastecimento'],
						'periodo' => $this->inverte_data($this->head['periodo']),
						'tipo_alimento' => $this->head['tipo_alimento'],
						'data_envio' => date('Y-m-d'),
						"relatorio_{$table}" => serialize($relatorio)
				));	
			}
			
			if($table == 'simulado')
			{
				$query = $this->db->insert("{$table}",
					array(
						  'titulo_relatorio' => $this->head['titulo_relatorio'],
						  'tipo_relatorio' => $this->head['tipo_relatorio'],
						  'tipo_alimento' => $this->head['tipo_alimento'],
						  'data_envio' => date('Y-m-d'),
						  'abastecimento' => $this->head['abastecimento'],
						  "relatorio_{$table}" => serialize($relatorio)
				));	
			}*/
			
		}//if request	

		return;
	}//updateRelatorio()

	public function updateStatus($id = null)
	{
		if( empty($id) )
			return;
		
		$check_user = chk_array($_SESSION['userdata'], 'user_permissions');
		if( ! in_array('abastecimento',$check_user) ||  ! in_array('all',$check_user) ||  ! in_array('adm',$check_user) ){
			
			return;
		}
		else
		{
			if( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) )
			{
				if( isset($_POST['check']) && ($_POST['check'] == 'true' || $_POST['check'] == 'false') )
				{
					$this->db->update('relatorios', "id_relatorio", $id, array(
						'status' => $_POST['status']
					));
					
					$this->logStatusRelatorio($id, $_POST['acao']);
					
				}
				else 
					if( ! isset($_POST['check']) )
						return;
				return;
			}
		}
		return;
	}//updateStatus
	
	private function logStatusRelatorio($fk, $status)
	{	
			date_default_timezone_set('America/Sao_Paulo');
			$dataLocal = date("F j, Y, g:i a", time());
			$this->db->insert('log', array(
				'data_registro' => date('Y-m-d'),
				'titulo_atividade' => 'Status de um relatório alterado',
	'resumo_atividade' => "relatório ID: #{$fk} foi {$status} pelo usuario {$_SESSION['userdata']['user_name']}, {$dataLocal}",
				'user_action' => $_SESSION['userdata']['user_id'],
				'fk_relatorio' => $fk	
			));
			
			return;
	}
		
}//UpdateRelatorioModel()
?>