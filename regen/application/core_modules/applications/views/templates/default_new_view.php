<?php 
$contents = "
<form>
";
       
        foreach($fields as $field)
        {
            $contents .= "<label>". $field->name ."</label>
            <input ng-model='set.". $field->name ."' autofocus type='text'> <br/>";
        }
        
        $contents .= "		

<button ng-click=create(set) class='btn ' >Create</button>

</form>
";

echo $contents;
?>