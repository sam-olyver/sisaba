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
			 <th>ID</th>
			 <th>Usuário</th>
			 <th>Name</th>
			 <th>Permissões</th>
			 <th>Edição</th>
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
			 <a href="/sisaba/UserRegister/edit/<?php echo $fetch_userdata['user_id'] ?>" class="btn btn-default">Edit</a>
			 <a href="/sisaba/UserRegister/del/<?php echo $fetch_userdata['user_id'] ?>" onclick="if(confirm('Deseja excluir este usuário?'))return true;else return false;" class="btn btn-default">Delete</a>
			 </td>
			 
			 </tr>
			 
			 <?php endforeach;?>
			 
			 </tbody>
			</table>
	
		</div>
	</div>
</div>