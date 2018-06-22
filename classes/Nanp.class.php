<?php	
	class Nanp extends MainRelatorio{

		//####### ATRIBUTOS
		
		/*dados
		
		quantidade_prevista  SUM_QTD_ALMN_ENTR . C1
		fator PER_ARRN_EMB
		estoque_unidade =  QTD_DIF_UNID
		quantidade_ajustada  SUM_QTD_RESV_LOTE . C2 
		capacidade  QTD_EMB_ENVD . C3 . IND_TIP_EMB
		total_embalagem  SUM_QTD_RESV_LOTE_QTD_EMB_ENVD . E2*/
				
		
		//####### METODOS MAGICOS
		
		public function __construct($dados = array()){
			if(empty($dados))
				exit;
			
			$check = $_FILES['relatorio_upload_file']['type'];
			if( $check != 'text/xml' )
			{
				echo "<h1>ARQUIVO INVALIDO<br></h1>";
				echo "<a href='http://localhost/sisaba/relatorios/novo'>VOLTAR</a>";
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
				
				'tipo_relatorio' => (string)$this->xml->CF_ESTQ,
					
				'tipo_alimento' => (string)$this->xml->CF_TIPO_ENTREGA,
				
				'data_entrega' => (string)$this->xml->LIST_G_NOM_FORN_ALMN->G_NOM_FORN_ALMN->LIST_G_DT_ENTG_ALMN->G_DT_ENTG_ALMN->DT_ENTG_ALMN,
				
				'data_envio' => date('Y-m-d'),
				
				'agrupamento' => $agrupamento,
				
				'abastecimento' => $abastecimento
				
			);
		}
		
		public function extrair_dados()
		{
			
			$consulta = $this->xml->LIST_G_NOM_FORN_ALMN->G_NOM_FORN_ALMN->LIST_G_DT_ENTG_ALMN->G_DT_ENTG_ALMN->LIST_G_COD_TIP_UNID->G_COD_TIP_UNID; 
			
			foreach($consulta as $this->dados)
			{
				$this->alimento[] = (string)$this->dados->TXT_DCR_ALMN;
				$this->quantidade_prevista[] = str_replace(',', '.', (string)$this->dados->SUM_QTD_ALMN_ENTR);
				$this->fator[] = str_replace(',', '.', (string)$this->dados->PER_ARRN_EMB);
				$this->estoque_unidade[] = str_replace(',', '.', (string)$this->dados->QTD_DIF_UNID);
				$this->quantidade_ajustada[] = str_replace(',', '.', (string)$this->dados->SUM_QTD_RESV_LOTE); 
				$this->capacidade[] = str_replace(',', '.', (string)$this->dados->QTD_EMB_ENVD);
				$this->total_embalagem[] = str_replace(',', '.', (string)$this->dados->SUM_QTD_RESV_LOTE_QTD_EMB_ENVD);
			}
		}
		
		//##### METODOS PARA DEBUG
		
		public function get_dados_obj()
		{
			return [
				$this->getAlimento(),
				$this->getQuantidade_prevista(),	
				$this->getFator(),
				$this->getEstoque_unidade(),			
				$this->getQuantidade_ajustada(),	
				$this->getCapacidade(),	
				$this->getTotal_embalagem()
			];
		}
		
}//fim classe
	

?>