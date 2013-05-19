<?php 
$contents = "
<h1>Project {{" . $lc_singular . ".id}}</h1>
<div class='row'>
<table class='table table-striped span3'> ";

foreach($fields as $field)
{
	$contents .= "<tr>
	<td> $field->name</td>  
	<td>{{" . $lc_singular . "." . $field->name . "}}</td>
	</tr>";
}

$contents .= "		

</table>
</div>
";

echo $contents;
?>