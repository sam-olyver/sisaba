<?php

	class UploadNewRelatorioModel extends MainModel
	{
		//nome da view da tabela do relatório
		public $table;
		//ira armazenar o objeto relatorio	
		public $obj;
		
		public function __construct()
		{

			if(isset($_POST) && !empty($_POST)){
				foreach($_POST as $form_name => $value)
				{
					$variavel = $form_name;
					$$variavel = $value;		
				}
				$file = $_FILES['relatorio_upload_file'];
			}
			else
			{
				exit;
			}
			
			switch($relatorio) {
						case 'Nanp':
							$title = 'NECESSIDADE DE ALIMENTOS NÃO PERECÍVEIS';
							$tipo = null;
							$this->table = 'nanp';
							break;
						case 'ResumoAba':
							$title = 'RESUMO DE TOTALIZAÇÃO';
							$tipo = 'POR ABASTECIMENTO';
							$this->table = 'resumo';
							break;
						case 'ResumoMes':
							$title = 'RESUMO TOTALIZAÇÃO';
							$tipo = 'POR MÊS';
							$this->table = 'resumo';
							break;
						case 'SimuladoXlsx':	
							$title = 'SIMULADO';
							$tipo = null;
							$this->table = 'simulado';
						break;
					}
					
			$form_dados = array(
			'titulo_relatorio' => $title,
			'relatorio' => $tipo,
			'data_inicio' => $data_inicio, 
			'data_final' => $data_final, 
			'abastecimento' => $abastecimento, 
			'agrupamento' => $agrupamento, 
			'file_name' => $file['name'],
			'tmp_name' => $file['tmp_name']
			);				
			
			try{
				$this->obj = new $relatorio($form_dados);
				return $this->obj;
			}catch(Exception $e)
			{
				echo "<h1>Arquivo Invalido</h1>";
				return false;
			}
			
		}
		
		public function getTable()
		{
			return $this->table;
		}
		public function getObj()
		{
			return $this->obj;
		}
		
		
	}
	
?>	