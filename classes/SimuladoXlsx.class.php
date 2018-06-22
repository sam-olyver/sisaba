<?php
	require_once 'MainRelatorio.class.php';

	class SimuladoXlsx extends MainRelatorio
	{	
		private $simplexlsx;
		
		//##### METODOS MAGICOS
		
		public function __construct($dados = array()){	
			if(empty($dados))
				exit;		
			
			$check = $_FILES['relatorio_upload_file']['type'];
			
			if(  $check != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
			{
				echo "<h1>ARQUIVO INVALIDO<br></h1>";
				echo "<a href='http://localhost/sisaba/relatorios/novo'>VOLTAR</a>";
				exit;
			}
			
			//instancia simulado	
			$this->upload($dados['file_name'],$dados['tmp_name']);
			
			$this->simplexlsx = new SimpleXLSX(UP_ABSPATH.$dados['file_name']);
				
			$this->extrair_cabecalho($dados['titulo_relatorio'], $dados['relatorio'], $dados['abastecimento'], $dados['agrupamento'], $dados['data_inicio'],$dados['data_final']);
			
		
			$this->extrair_dados();
			
			unlink(UP_ABSPATH.$dados['file_name']);
		}
		
		
		//##### METODOS
		public function upload($file_name, $file_tmp)
		{
			
			//move o arquivo para o diretorio de upload
			move_uploaded_file($file_tmp,UP_ABSPATH.$file_name) or die("ERRO AO TENTAR MOVER ARQUIVO");
			
			
			//verifica se o arquivo foi transferido para o diretorio de upload
			if(!file_exists(UP_ABSPATH.$file_name))
			{
				echo "Arquivo XLSX não encontrado, erro ao fazer upload";
				exit;
			}
			
		}
		
		
		public function extrair_cabecalho($title, $tipo, $abastecimento, $agrupamento, $data_inicio, $data_final)
		{
				
			
			$this->cabecalho = array(	
			
				'titulo_relatorio' => (string)$this->simplexlsx->rows(1,0,1),
				
				'tipo_relatorio' => 'SIMULADO',
					
				'tipo_alimento' => 'Não Perecível',
				
				'data_envio' => date('Y-m-d'),
												
				'abastecimento' => $abastecimento,
				
				'agrupamento' => $agrupamento
				
			);
		}
		
		public function extrair_dados()
		{
			$i = 3;
			$length = count($this->simplexlsx->rows());
			for($i; $i < $length; $i++)
			{		
				$this->dados[] = array(
			
					'alimento' => $this->alimento[] = (string)$this->simplexlsx->rows(1,$i,2),
				
					'agrupamento' => $this->agrupamentos[] = (string)$this->simplexlsx->rows(1,$i,0),
				
					'data_entrega' => $this->datas_entrega[] = (string)$this->formatarDataExcel($this->simplexlsx->rows(1,$i,1)),
				
					'quantidade_necessaria' => $this->quantidade_necessaria[] = str_replace(',', '.', (string) $this->simplexlsx->rows(1,$i,3)),
				
					'quantidade_ajustada' => $this->quantidade_ajustada[] = str_replace(',', '.', (string) $this->simplexlsx->rows(1,$i,5)),
				
					'quantidade_embalagem' => $this->quantidade_embalagem[] = str_replace(',', '.', (string) $this->simplexlsx->rows(1,$i,7))
				
				);	
			}
			
		}
		
		public function formatarDataExcel($dias)
		{
			$dias -= 2;
			$data = '01/01/1900';
			$data = DateTime::createFromFormat('d/m/Y', $data);
			$data->add(new DateInterval('P'.$dias.'D')); // 2 dias 
			return $this->inverte_data($data->format('d/m/Y'));
			
		}
		
		//####### GETTERS e SETTERS
		public function getSimplexlsx(){
			return $this->simplexlsx;
		}
		
		public function setSimplexlsx($obj){
			$this->simplexlsx = $obj;
		}

		//##### METODOS PARA DEBUG
		
		public function get_dados_obj()
		{
			return [
				$this->getAlimento(),
				$this->getAgrupamentos(),
				$this->getDatas_entrega(),
				$this->getQuantidade_necessaria(),
				$this->getQuantidade_ajustada(),
				$this->getQuantidade_embalagem()
			];
		}
		
		
	}	
	
?>