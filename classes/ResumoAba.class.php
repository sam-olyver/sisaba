<?php
	require_once 'MainRelatorio.class.php';
	
	class ResumoAba extends MainRelatorio 
	{
		
		
		
		///####### METODOS MAGICOS
		
		public function __construct($dados = array()){
			if(empty($dados))
				exit;
			
			$check = $_FILES['relatorio_upload_file']['type'];
			if( $check != 'text/xml' )
			{
				echo "<h1>ARQUIVO INVALIDO<br></h1>";
				echo "<a href='../".HOSTNAME."/sisaba/relatorios/novo'>VOLTAR</a>";
				exit;
			}
			
			$this->xml = $this->upload($dados['file_name'],$dados['tmp_name']);
			$this->extrair_cabecalho($dados['titulo_relatorio'], $dados['relatorio'], $dados['abastecimento'], $dados['agrupamento'], $dados['data_inicio'],$dados['data_final']);
			$this->extrair_dados();
		}
					
		
		//####### METODOS
		
		
		public function upload($file_name, $file_tmp)
		{
			
			//move o arquivo para o diretorio de upload
			move_uploaded_file($file_tmp,UP_ABSPATH.$file_name) or die("ERRO AO TENTAR MOVER ARQUIVO");
			
			
			//verifica se o arquivo foi transferido para o diretorio de upload
			if(!file_exists(UP_ABSPATH.$file_name))
			{
				echo "Arquivo XML não encontrado, erro ao fazer upload";
				exit;
			}
			
			
			//verifica se o arquivo pode ser alterado
			if(!is_writable(UP_ABSPATH.$file_name))
			{
				echo "Arquivo XML não pode ser alterado";
				exit;
			}
	
			//abre arquivo para leitura e escrita
			$manipular_arquivo = fopen(UP_ABSPATH.$file_name, "r+") or die("ERRO AO ABRIR ARQUIVO");
			
			//codigo a ser alterado
			$encoding_xml = "<?xml version='1.0' encoding='iso-8859-1' ?><!-- ";
			
			//tamanho da string da alteração	
			$length = strlen($encoding_xml);
			
			fwrite($manipular_arquivo,$encoding_xml, $length) or die("ERRO AO TENTAR ALTERAR ARQUIVO");
			//altera arquivo fazendo merger entre o arquivo original e o codigo da alteração
				
			//fecha o arquivo alterado	
			fclose($manipular_arquivo) or die("ERRO AO FECHAR ARQUIVO");
			
			//cria a instancia do arquivo xml alterado e atribui ao atributo da classe
			$upload_file = simplexml_load_file(UP_ABSPATH.$file_name) or die("ERRO AO CRIAR OBJETO XML");
			
			//deleta o arquivo do diretorio de upload
			unlink(UP_ABSPATH.$file_name);
			
			//retorna o xml
			return $upload_file;
				
		}	

		public function extrair_cabecalho($title, $tipo, $abastecimento, $agrupamento, $data_inicio, $data_final)
		{
			$this->cabecalho = array(
			
				'titulo_relatorio' => $title,
			
				'tipo_relatorio' => $tipo,
				
				'agrupamento' => $agrupamento,
				
				'abastecimento' => $abastecimento,
								
				'periodo' => $this->inverte_data($data_inicio) . ' a ' . $this->inverte_data($data_final),
								
				'tipo_alimento' => (string)$this->xml->ENTREGA,
								
				'data_envio' => date('Y-m-d')

			);
		}
		
		public function extrair_dados()
		{
			
			$consulta = $this->xml->LIST_G_COD_ALMN->G_COD_ALMN; 
			
			
			
			foreach($consulta as $this->dados)
			{
				
				if( $this->dados->AGRI != ''  )
				{
					$alimento = $this->dados->TXT_DCR_ALMN .' '. $this->dados->AGRI;
				}
				else
				{
					$alimento = $this->dados->TXT_DCR_ALMN;	
				}
				
				$this->alimento[] = (string) $alimento;
				
				$this->situacao[] = (string)$this->dados->CF_3 ;
				
				$this->total_embalagem_fechada[] = str_replace(',', '.', (string)$this->dados->QTD_ALMN_EMB_FCHD) . " " . (string)$this->dados->COD_TIP_EMB_FCHD . " " . str_replace(',', '.', $this->dados->QTD_CPCD_EMB_FCHD) . " " . (string)$this->dados->NOM_MDD_MATE;
				
				$this->total_embalagem_fracionada[] = str_replace(',', '.', (string)$this->dados->QTD_ALMN_EMB_FRCO) . " " . (string)$this->dados->COD_TIP_EMB_FRCO . " " . str_replace(',', '.', $this->dados->QTD_CPCD_EMB_FRCO) . " " . (string)$this->dados->NOM_MDD_MATE;
				
				$this->totalizacao[] = str_replace(',', '.', (string)$this->dados->CF_4); 
			}
		}
		
		
		//##### METODOS PARA DEBUG
		
		public function get_dados_obj()
		{
			return [
				$this->getAlimento(),
				$this->getSituacao(),
				$this->getTotal_embalagem_fechada(),
				$this->getTotal_embalagem_fracionada(),
				$this->getTotalizacao()
			];
		}
	}

?>