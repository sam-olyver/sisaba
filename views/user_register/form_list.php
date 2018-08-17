<?php if ( ! defined('ABSPATH')) exit; 
// Carrega todos os métodos do modelo
$lista = $modelo->get_user_list();
?>
<div class="content">

	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title"></h3>
		</div>
		
		<div class="box-body">
		
		
			<table class="table table-bordered table-hover">
			 <thead>
			 <tr>
			 <th>CÓDIGO</th>
			 <th>USUÁRIO</th>
			 <th>NOME</th>
			 <th>PERMISSÕES</th>
			 <th>EDIÇÃO</th>
			 </tr>
			 </thead>
			 
			 <tbody>
			 
			 <?php foreach ($lista as $fetch_userdata): ?>
			 
			 <tr>
			 
			 <td> <?php echo $fetch_userdata['user_id'] ?> </td>
			 <td> <?php echo $fetch_userdata['user'] ?> </td>
			 <td> <?php echo $fetch_userdata['user_name'] ?> </td>
			 <td> <?php echo implode( ',', unserialize( $fetch_userdata['user_permissions'] ) ) ?> </td>
			 
			 <td> 
			 <a href="/UserRegister/edit/<?php echo $fetch_userdata['user_id'] ?>" class="btn btn-default">Editar</a>
			 <a href="/UserRegister/del/<?php echo $fetch_userdata['user_id'] ?>" onclick="if(confirm('Deseja excluir este usuário?'))return true;else return false;" class="btn btn-default">Excluir</a>
			 </td>
			 
			 </tr>
			 
			 <?php endforeach;?>
			 
			 </tbody>
			</table>
	
		</div>
	</div>
</div>
