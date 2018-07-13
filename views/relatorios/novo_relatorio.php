<?php if ( ! defined('ABSPATH')) exit; ?>
<section class="content">
<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		
		<div class="box-body">
<form action="/sisaba/relatorios/preview" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleInputEmail1">Selecione um Tipo de Relatório</label>
    <select class="form-control" name="relatorio" required>
		<option value=" " readonly selected>Selecione uma Opção</option>
		<option value="SimuladoXlsx">Simulado Excel</option>
		<option value="Nanp">Necessidade de Alimentos sem Considerar Estoque</option>
		<option value="Nanp">Necessidade de Alimentos Considerando Estoque</option>
		<option value="ResumoMes">Resumo Totalização por Mês</option>
		<option value="ResumoAba">Resumo Totalização por Aba</option>
	</select>
  </div>
  <div class="form-group">
  <label for="exampleInputEmail1">Mês</label>
	<select class="form-control" name="mes">
		<option value=" " readonly selected>Selecione uma Opção</option>
		<option value="janeiro">Janeiro</option>
		<option value="fevereiro">Fevereiro</option>
		<option value="março">Março</option>
		<option value="abril">Abril</option>
		<option value="maio">Maio</option>
		<option value="junho">Junho</option>
		<option value="Julho">Julho</option>
		<option value="agosto">Agosto</option>
		<option value="setembro">Setembro</option>
		<option value="outubro">Outubro</option>
		<option value="novembro">Novembro</option>
		<option value="dezembro">Dezembro</option>
	</select>
  </div>
  <div class="form-group">
  <label for="exampleInputEmail1">Ano</label>
	<select class="form-control" name="ano" required>
		<option readonly >Selecione uma Opção</option>
		<?php $modelo->getAnoAbastecimento(); ?>
	</select>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Período</label>
    <input type="date" class="form-control" name="data_inicio" >
  </div>
  
  <div class="form-group">
    <input type="date" class="form-control" name="data_final" >
  </div>
  
   <div class="form-group">
    <label for="exampleInputEmail1">Selecione o Abastecimento</label>
    <select class="form-control" name="abastecimento" >
		<option value="0" readonly selected>Selecione uma Opção</option>
		<?php $modelo->getDataAbastecimento();?>	
	</select>
  </div>
  
   <div class="form-group">
    <label for="exampleInputEmail1">Selecione o Agrupamento</label>
    <select class="form-control" name="agrupamento" >
		<option value="0" readonly selected>Selecione uma Opção</option>
		<option value="4">4</option>
		<option value="3">3</option>
		<option value="2">2</option>
		<option value="1">1</option>
	</select>
  </div>
  
  <div class="form-group">
    <label for="exampleInputFile">Arquivo Relatório</label>
    <input type="file" name="relatorio_upload_file" required>
    <p class="help-block">upload apenas de arquivos xml ou xlsx(no caso do simulado relatório de apenas um agrupamento por vez)!</p>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" required> Verifiquei todos os dados e arquivos, prosseguir!
    </label>
  </div>
  <button type="submit" class="btn btn-default">Enviar</button>
</form>

		</div>
</div>		

</section>