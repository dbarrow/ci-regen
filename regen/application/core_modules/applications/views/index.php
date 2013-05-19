Services

<div class="row">
	<table class="span3 table "> 
		<tr style="text-align:left">
			<th>Service Name</th>
			<th style="text-align:right"></th>
		</tr>
		<?php foreach($services as $service) : ?>
		<tr>
			<td><a class="white" href="<?= base_url('services/service/' . $service->name)?>"><?= ucfirst($service->name);?></a></td>
			<td class="span2 pull-right" style="text-align:right">  
				
				<a class="btn btn-primary" href="<?= base_url('applications/build_ng_module/' . $service->name);?>"> 
					Build Module
				</a>
			
		</td>
	</tr>
<?php endforeach ?>		
</table>
</div>