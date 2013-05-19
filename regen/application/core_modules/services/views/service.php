<style>
td{
	width:100px;
}
tr{
	text-align: left;
}
</style>

<div class="row">
	<div class="span2">
		<h4><?= $service_name?></h4>
	</div>
	<div class="span3 pull-right" style="text-alight:right">
		<?php 
			if($service->authorization==TRUE)
		      echo("Authorization <span class='label label-success'>Enabled </span> <a href='" . base_url('services/toggle_auth/' . $service_name) . "'>  [Disable]</a>");
		  	else
		  	   echo("Authorization <span class='label label-important'>Disabled </span> <a href='" . base_url('services/toggle_auth/' . $service_name) . "'>  [Enable]</a>");
		?>		
	</div>
</div>

<hr>

<table>
	<tr>
		<th></th>
		<th>Name</th> 
		<th>Max Len</th> 
		<th>Type</th> 
		<th>Default</th> 
		<th>Primary Key</th> 
	</tr> 
	<?php 
	foreach($fields_data as $field) : ?>
		<tr >
			<td> <i class="icon-edit icon-white"></i> <a class="delete-me" href="<?= base_url('services/remove_field/' . $service_name . "/" . $field->name);?>"> <i class="icon-trash icon-white"></i> </a> </td>
			<td><?=  $field->name?></td> 
			<td><?=  $field->max_length?></td> 
			<td><?=  $field->type?></td> 
			<td><?=  $field->default?></td> 
			<td><?=  $field->primary_key?></td> 
		</tr>
	<? endforeach ?>
</table>
<hr>

<a href="<?= base_url('services/add_fields/' . $service_name);?>" class="btn btn-success" > Add Field </a>

<script type="text/javascript">
//Delete service from system warning
$(document).ready(function () {
	$('.delete-me').on('click', function(e){
		e.preventDefault();
		var targetUrl = $(this).attr("href");
		var answer = confirm("Delete this field?  All database entried will be deleted");
		if(answer)
			window.location.href = targetUrl;
		else
			return;
	});
});
</script>