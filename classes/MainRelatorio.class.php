<?php
	//require_once 'config.php';
	/*
	*	Nanp S/estoque (abastecimento/ano/xml/ex: 1aba1 resumo-19-01)
	*	Nanp C/estoque (abastecimento/ano/relatorios_ano/rel c est)
	*	ResumoMês (abastecimento/ano/relatorios_ano/rel mes)
	*	ResumoPorAba (abastecimento/ano/relatorios_ano/relatorios)
	*	Simulado (abastecimento/ano/quantificao/xlsx)
	*
	*/
	abstract class MainRelatorio 
	{
		//##### ATRIBUTOS
		
		//recebe o objeto xml
		public $xml;
		
		//recebe objeto xlsx(excel)
		public $xlsx;
				
		//acessa objeto do arquivo e extrai os dados necessario para os atributos definidos 		
		public $dados = array();
		
		//cabeçalho que identifica relatorio
		public $cabecalho = array();
		
		//##### ATRIBUTOS RELATORIOS
		
		public $alimento = array();
		public $quantidade_prevista = array();
		public $fator = array();
		public $estoque_unidade = array();
		public $quantidade_ajustada = array();
		#######
		public $capacidade = array();
		public $total_embalagem = array();
		#####
		public $situacao = array();
		public $total_embalagem_fechada = array();
		public $total_embalagem_fracionada = array();
		public $totalizacao = array();
		//#####
		public $agrupamentos = array();
		//data dos dez dias
		public $datas_entrega = array();
		public $quantidade_necessaria = array();
		public $quantidade_embalagem = array();
				
		//##### METODOS MAGICOS
		
		public abstract function __construct($dados = array());
						
		
		//##### METODOS DE IMPLEMENTAÇÃO
		
		public abstract function upload($file_name, $file_tmp);
		
		public abstract function extrair_cabecalho($title, $tipo, $abastecimento, $agrupamento, $data_inicio, $data_final);
		
		public abstract function extrair_dados();
				
		
		
		
		//##### SETTERS e GETTERS
		public function getXml(){
			return $this->xml;
		}

		public function setXml($xml){
			$this->xml = $xml;
		}

		public function getXlsx(){
			return $this->xlsx;
		}

		public function setXlsx($xlsx){
			$this->xlsx = $xlsx;
		}

		public function getDados(){
			return $this->dados;
		}

		public function setDados($dados){
			$this->dados[] = $dados;
		}

		public function getCabecalho(){
			return $this->cabecalho;
		}

		public function setCabecalho($cabecalho){
			$this->cabecalho[] = $cabecalho;
		}

		public function getAlimento(){
			return $this->alimento;
		}

		public function setAlimento($alimento){
			$this->alimento[] = $alimento;
		}

		public function getQuantidade_prevista(){
			return $this->quantidade_prevista;
		}

		public function setQuantidade_prevista($quantidade_prevista){
			$this->quantidade_prevista[] = $quantidade_prevista;
		}

		public function getFator(){
			return $this->fator;
		}

		public function setFator($fator){
			$this->fator[] = $fator;
		}

		public function getEstoque_unidade(){
			return $this->estoque_unidade;
		}

		public function setEstoque_unidade($estoque_unidade){
			$this->estoque_unidade[] = $estoque_unidade;
		}

		public function getQuantidade_ajustada(){
			return $this->quantidade_ajustada;
		}

		public function setQuantidade_ajustada($quantidade_ajustada){
			$this->quantidade_ajustada[] = $quantidade_ajustada;
		}

		public function getCapacidade(){
			return $this->capacidade;
		}

		public function setCapacidade($capacidade){
			$this->capacidade[] = $capacidade;
		}

		public function getTotal_embalagem(){
			return $this->total_embalagem;
		}

		public function setTotal_embalagem($total_embalagem){
			$this->total_embalagem[] = $total_embalagem;
		}

		public function getSituacao(){
			return $this->situacao;
		}

		public function setSituacao($situacao){
			$this->situacao[] = $situacao;
		}

		public function getTotal_embalagem_fechada(){
			return $this->total_embalagem_fechada;
		}

		public function setTotal_embalagem_fechada($total_embalagem_fechada){
			$this->total_embalagem_fechada[] = $total_embalagem_fechada;
		}

		public function getTotal_embalagem_fracionada(){
			return $this->total_embalagem_fracionada;
		}

		public function setTotal_embalagem_fracionada($total_embalagem_fracionada){
			$this->total_embalagem_fracionada[] = $total_embalagem_fracionada;
		}

		public function getTotalizacao(){
			return $this->totalizacao;
		}

		public function setTotalizacao($totalizacao){
			$this->totalizacao[] = $totalizacao;
		}

		public function getAgrupamentos(){
			return $this->agrupamentos;
		}

		public function setAgrupamentos($agrupamentos){
			$this->agrupamentos[] = $agrupamentos;
		}

		public function getDatas_entrega(){
			return $this->datas_entrega;
		}

		public function setDatas_entrega($datas_entrega){
			$this->datas_entrega[] = $datas_entrega;
		}

		public function getQuantidade_necessaria(){
			return $this->quantidade_necessaria;
		}

		public function setQuantidade_necessaria($quantidade_necessaria){
			$this->quantidade_necessaria[] = $quantidade_necessaria;
		}

		public function getQuantidade_embalagem(){
			return $this->quantidade_embalagem;
		}

		public function setQuantidade_embalagem($quantidade_embalagem){
			$this->quantidade_embalagem[] = $quantidade_embalagem;
		}
			
		
		//##### METODOS PARA DEBUG
		public function getLenght($matriz){
			return count($matriz);
		}
		
		public function get_tipo_relatorio()
		{
			return [
				$this->cabecalho['titulo_relatorio'],
				$this->cabecalho['tipo_relatorio']
			];		
		}
		
		
		public abstract function get_dados_obj();

		
		public function inverte_data( $data = null ) {
	
		// Configura uma variável para receber a nova data
		$nova_data = null;
		
		// Se a data for enviada
		if ( $data ) {
		
			// Explode a data por -, /, : ou espaço
			$data = preg_split('/\-|\/|\s|:/', $data);
			
			// Remove os espaços do começo e do fim dos valores
			$data = array_map( 'trim', $data );
			
			// Cria a data invertida
			$nova_data .= chk_array( $data, 2 ) . '-';
			$nova_data .= chk_array( $data, 1 ) . '-';
			$nova_data .= chk_array( $data, 0 );
			
			// Configura a hora
			if ( chk_array( $data, 3 ) ) {
				$nova_data .= ' ' . chk_array( $data, 3 );
			}
			
			// Configura os minutos
			if ( chk_array( $data, 4 ) ) {
				$nova_data .= ':' . chk_array( $data, 4 );
			}
			
			// Configura os segundos
			if ( chk_array( $data, 5 ) ) {
				$nova_data .= ':' . chk_array( $data, 5 );
			}
		}
		
		// Retorna a nova data
		return $nova_data;
	
	} // inverte_data


	
}//class MainRelatorio
	

?>