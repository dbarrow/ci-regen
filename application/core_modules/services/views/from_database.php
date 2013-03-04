<?php echo form_open($this->uri->uri_string()); ?>               
<?php echo form_dropdown("table", $tables, '', 'id="table" class="select"'); ?>

<div class="row" >
	<div class="span1">
		<input type="submit" name="submit" class="btn" value="Create" />            
	</div>
</div>

<?php echo form_close(); ?>


