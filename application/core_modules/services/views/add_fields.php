<h4>Fields</h4>
<?php echo form_open($this->uri->uri_string(), 'class=""'); ?>

<fieldset id="itemDetails">
  <div class="row" >
    <table class="fields span12">
      <tbody style="padding:10px;">
        <tr  class="" style="text-align:left;padding:0px;"  >
          <th>Field Name</th>
          <th>Key</th>
          <th>Auto Inc</th>
          <th>Length</th>
          <th>Default</th>
          <th>Null</th>
          <th>Data Type</th>
        </tr> 
        
        <div class="customItem">
          <tr>
            <td> <input  recname="name" class=""  type="text" id="name" name="name[]" /> </td>            
            <td>
              <?php
              $db_field_types = array(
                'NONE'        => 'NONE',   
                'PRIMARY'       => 'PRIMARY',
                );
                ?>
                <?php echo form_dropdown("key[]", $db_field_types, form_error("db_field_type").'</span>', 'recname="key" class="select" id="null" '); ?>
              </td>
              <td>
              <?php
                $auto_inc = array(
                  'FALSE'        => 'FALSE',   
                  'TRUE'       => 'TRUE',
                );
              ?>
              <?php echo form_dropdown("auto_inc[]", $auto_inc, 'class="select" '. form_error("db_field_type").'</span>', 'recname="auto_inc" class="select" id="auto_inc" '); ?>
              </td>
              <td>
                <input type="text" recname="length" class="" id="length" name="length[]" value="30"/>
              </td>
              <td>
                <input type="text" recname="default" class=""  id="default" name="default[]" />
              </td>
              <td>
                <?php
                $db_field_types = array(
                  'TRUE'       => 'TRUE',
                  'FALSE'        => 'FALSE',   
                  );
                  ?>
                  <?php echo form_dropdown("null[]", $db_field_types, ' recname="db_field_type"'. form_error("db_field_type").'</span>', 'recname="null" id="null" class="select"'); ?>
              </td>
              <td>
                <?php
                  $db_field_types = array(
                  'VARCHAR'       => 'VARCHAR',
                  'BIGINT'        => 'BIGINT',
                  'BINARY'        => 'BINARY',
                  'BIT'           => 'BIT',
                  'BLOB'          => 'BLOB',
                  'BOOL'          => 'BOOL',
                  'CHAR'          => 'CHAR',
                  'DATE'          => 'DATE',
                  'DATETIME'      => 'DATETIME',
                  'DECIMAL'       => 'DECIMAL',
                  'DOUBLE'        => 'DOUBLE',
                  'ENUM'          => 'ENUM',
                  'FLOAT'         => 'FLOAT',
                  'INT'           => 'INT',
                  'LONGBLOB'      => 'LONGBLOB',
                  'LONGTEXT'      => 'LONGTEXT',
                  'MEDIUMBLOB'    => 'MEDIUMBLOB',
                  'MEDIUMINT'     => 'MEDIUMINT',
                  'MEDIUMTEXT'    => 'MEDIUMTEXT',
                  'SET'           => 'SET',
                  'SMALLINT'      => 'SMALLINT',
                  'TEXT'          => 'TEXT',
                  'TIME'          => 'TIME',
                  'TIMESTAMP'     => 'TIMESTAMP',
                  'TINYBLOB'      => 'TINYBLOB',
                  'TINYINT'       => 'TINYINT',
                  'TINYTEXT'      => 'TINYTEXT',
                  'VARBINARY'     => 'VARBINARY',
                  'YEAR'          => 'YEAR',
                  );
                ?>
                <?php echo form_dropdown("type[]", $db_field_types, 'class="select" recname="db_field_type"'. form_error("type").'</span>', 'recname="type" class="select" id="type" '); ?>
            </td>
          </tr>
        </div>
      </tbody>
    </table>
  </div>
</fieldset>

<hr>

<div style="clear:both;"></div>

<div class="row" >
  <div class="span1"><input type="submit" name="submit" class="btn" value="Create" /></div>
  <div class="span2 pull-right"><a href="#main" class="" style="float:right;">Back to top</a></div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">

$(function() {
  $(document).ready(function() {
    $("#itemDetails").EnableMultiField();
  });
});

</script>