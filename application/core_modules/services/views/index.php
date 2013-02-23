<div class="container">
	<div class="row">
		<div class="span12">
			<h4>Installed Services</h4>
			<p>
				<a href="services/new_service" class="btn btn-success"> <i class="icon-plus icon-white"></i></a>
			</p>
			<div class="row">
				<table class="span3 table "> 
					<tr style="text-align:left">
						<th>Service Name</th>
						<th style="text-align:right"></th>
					</tr>
					<?php foreach($services as $service) : ?>
					<tr>
						<td><a class="white" href="<?= base_url('services/service/' . $service->name)?>"><?= ucfirst($service->name);?></a></td>
						<td class="span1 pull-right" style="text-align:right"> <a class="delete-me" href="<?=base_url('services/remove_service/' . $service->name);?>"><i class="icon-trash icon-white"></i></a></td>
					</tr>
				<?php endforeach ?>		
			</table>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
//Delete service from system warning
$(document).ready(function () {
	$('.delete-me').on('click', function(e){
		e.preventDefault();
		var targetUrl = $(this).attr("href");
		var answer = confirm("Delete this Service?  All data will be deleted, including MVC folders and database table");
		if(answer)
			window.location.href = targetUrl;
		else
			return;
	});
});
</script>
