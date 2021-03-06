<?php 
$contents = "
<a class='btn btn-success' href='#/".$lc_singular."/create'><i class='icon-plus icon-white'></i></a>  

<div class='input-prepend pull-right form-search' style='text-align:right'>
  <span class='add-on'><i class='icon-search'></i></span>
 <input type='text' class='input-medium ' ng-model='query'  ng-change='search()' placeholder='Search'>
</div>

<br/><br/>

<table class='table table-striped table-condensed'>  
	<tr> 
		<th ><input type='checkbox'></th> ";

        
        foreach($fields as $field)
        {
            $contents .= "<th><a ng-click=\"sort_by('" . $field->name . "')\"> " . $field->name . "</a></th>";
        }
        
        $contents .= "		
	</tr>  
	<tfoot>
        <td colspan='6'>
            <div class='pagination pull-right'>
                <ul>
                    <li ng-class='{disabled: currentPage == 0}'>
                        <a href ng-click='prevPage()'>« Prev</a>
                    </li>
                    <li ng-repeat='n in range(pagedItems.length)'
                        ng-class='{active: n == currentPage}'
                    ng-click='setPage()'>
                        <a href ng-bind='n + 1'>1</a>
                    </li>
                    <li ng-class='{disabled: currentPage == pagedItems.length - 1}'>
                        <a href ng-click='nextPage()'>Next »</a>
                    </li>
                </ul>
            </div>
        </td>
    </tfoot>

	<tr ng-repeat='item in pagedItems[currentPage] | orderBy:sortingOrder:reverse'>  
        <td>
            <input type='checkbox'>
            <a ng-click=view(item) ><i class='icon-edit'></i></a>
            <a ng-click=delete(item,\$index) ><i class='icon-trash'></i></a> 
        </td>";
         foreach($fields as $field)
        {
            $contents .= "<td>{{item." . $field->name . "}}</td>  ";
        }        
        $contents .= "  
		
	</tr>    
</table>  




</div>
<!--
<form>
<input type='text' ng-model='searchText'>
<button class='btn btn-primary' ng-click='put()'>Put</button>
</form>
-->
";

echo $contents;
?>