<?php echo form_open($this->uri->uri_string()); ?>
<div class="row">
  <div class="span12">
    <h4>Service Settings</h4>
    <table >
      <tr><th style="text-align:left; ">Service Name</th> </tr> 
      <p>Use a short lower case name with no spaces.  This will be the name of the MVC module, db table name, and resource url segment. Example:  books, tools, cars, lap_times</p>
      <tr><td><input type="text" id="service_name" name="service_name" class="span2"> </td> </tr>
    </table>
  </div>
</div>
<h4>Primary Key</h4>

<div class="row" >
  <table class="fields span12">
    <tbody style="padding:10px;">
      <tr style="text-align:left;padding:5px;"  >
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
        <td> <input recname="primary_name" type="text" id="primary_name" name="primary_name" value="id"/> </td>
        <td>
          <?php
            $db_field_types = array(
              'PRIMARY'       => 'PRIMARY',
              'NONE'        => 'NONE', 
            );
          ?>
          <?php echo form_dropdown("primary_key", $db_field_types, form_error("db_field_type").'</span>', 'recname="primary_key" class="select" id="primary_key" readonly'); ?>
        </td>
        <td>
          <?php
            $auto_inc = array(
              'TRUE'       => 'TRUE',
              'FALSE'        => 'FALSE',   
            );
          ?>
          <?php echo form_dropdown("primary_auto_inc", $auto_inc, form_error("db_field_type").'</span>', 'recname="primary_auto_inc" class="select" id="aprimary_auto_inc" readonly'); ?>
        </td>
        <td>
          <input type="text" recname="primary_length" class="" id="primary_length" name="primary_length" value="5"/>
        </td>
        <td>
          <input readonly type="text" recname="primary_default" id="primary_default" name="primary_default" />
        </td>
        <td>
          <?php
          $db_field_types = array(
            'FALSE'        => 'FALSE',   
            'TRUE'       => 'TRUE',
            );
          ?>
          <?php echo form_dropdown("primary_null", $db_field_types, form_error("type").'</span>', 'recname="primary_null" id="primary_null" class="select" readonly'); ?>
        </td>

        <td>
          <?php
            $db_field_types = array(
              'INT'           => 'INT',
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
            <?php echo form_dropdown("primary_type", $db_field_types, form_error("type").'</span>', 'recname="primary_type" class="select" id="primary_type" readonly'); ?>
          </td>
        </tr>
      </div>
    </tbody>
  </table>
</div>

<h4>Fields</h4>

<fieldset id="itemDetails">
  <div class="row" >
    <table class="fields span12">
      <tbody style="padding:10px;">
        <tr style="text-align:left;padding:0px;">
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
          <td> <input  recname="name" type="text" id="name" name="name[]" /> </td>
          <td>
            <?php
            $db_field_types = array(
              'NONE'        => 'NONE',   
              'FOREIGN'       => 'FOREIGN',
              );
              ?>
              <?php echo form_dropdown("key[]", $db_field_types, form_error("db_field_type").'</span>', 'recname="key" class="select" id="null"'); ?>
            </td>
            <td>
              <?php
              $auto_inc = array(
                'FALSE'        => 'FALSE',   
                'TRUE'       => 'TRUE',

                );
                ?>
                <?php echo form_dropdown("auto_inc[]", $auto_inc, form_error("db_field_type").'</span>', 'recname="auto_inc" class="select" id="auto_inc"'); ?>
              </td>
              <td>
                <input type="text" recname="length" class="" id="length" name="length[]" value="30" />
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
                  <?php echo form_dropdown("null[]", $db_field_types, form_error("db_field_type").'</span>', 'recname="null" id="null" class="select"'); ?>
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
              <?php echo form_dropdown("type[]", $db_field_types, form_error("type").'</span>', 'recname="type" class="select" id="type"'); ?>
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
  <div class="span1">
    <input type="submit" name="submit" class="btn" value="Create" />            
  </div>
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