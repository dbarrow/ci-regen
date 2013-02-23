<h2>Welcome to Re:Gen</h2>
<div class="row">
  <div class="span12">
<p>
  This is the installer for Re:Gen.  The application is pretty easy to setup.  
  It is about the same routine as a regular Codeigniter installation.
  Below is a basic outline to get the application running. 
<p>

<ul >
  <li>Open the htaccess file in the root directory.  RewriteBase /[root_dir_here]. </li>
  <li>Create an empty MySQL database. May work with other database types, but have not tested.  
    You will have to edit [root]/application/core_modules/install/views/install.php 
    file and add more options to the 'driver' select input</li>
  <li>That should do it.  Fill out the form below and press install.  It should only take a second! </li>
</ul>   

<?php echo form_open(base_url('install/step1')) ?>

  <h2>Database Settings</h2>
    
    <div>
      <label for="hostname"> Host </label>
      <?php echo form_error('hostname', '<div class="error">', '</div>'); ?>

      <input <?php if(form_error('hostname')) echo "class='input-error'";?> type="text" name="hostname" id="hostname" value="<?php if(form_error('hostname')) echo set_value('hostname'); else echo  'localhost';?>"/>
    </div>
    
    <div>
      <label for="port">Port</label>
      <?php echo form_error('port', '<div class="error">', '</div>'); ?>

      <input <?php if(form_error('port')) echo "class='input-error'";?> type="text" name="port" id="port" value="<?php if(form_error('port')) echo set_value('port'); else echo  '3306';?>" class="span1"/>
    </div>
    
    <div>
      <label for="username">Username</label>
      <?php echo form_error('username', '<div class="error">', '</div>'); ?>
      <input <?php if(form_error('username')) echo "class='input-error'";?> type="text" name="username" id="username" value="<?php if(form_error('username')) echo set_value('username'); else echo  '';?>"/>
    </div>
    
    <div>
      <label for="password">Password</label>
      <?php echo form_error('password', '<div class="error">', '</div>'); ?>
      <input <?php if(form_error('password')) echo "class='input-error'";?> type="password" name="password" id="password" value=""/>
    </div>

    <div>
      <label for="driver">Driver</label>
      <select name="driver" id="driver">
        <option>mysqli</option>
        <option>mysql</option>
      </select>
    </div>

    <div>
      <label for="database"> Database Name</label>
      <?php echo form_error('database', '<div class="error">', '</div>'); ?>
      <input <?php if(form_error('database')) echo "class='input-error'";?> type="text" name="database" id="database"/>
    </div>   

    <h2>User Settings</h2>
  
    <div>
      <label for="name"> User Name </label>
      <?php echo form_error('user_user', '<div class="error">', '</div>'); ?>
      <input <?php if(form_error('user_user')) echo "class='input-error'";?> type="text" name="user_user" id="user_user" value="<?php echo set_value('user_user'); ?>"/>
    </div>

    <div>
      <label for="email"> User Email </label>
      <?php echo form_error('email', '<div class="error">', '</div>'); ?>
      <input <?php if(form_error('email')) echo "class='input-error'";?> type="text" name="email" id="email" value="<?php echo set_value('email'); ?>"/>
    </div>
    
    <div>
      <label for="pw"> Password </label>
      <?php echo form_error('user_password', '<div class="error">', '</div>'); ?>
      <input <?php if(form_error('user_password')) echo "class='input-error'";?> type="password" name="user_password" id="user_password" /> 
    </div>
    
    <div>
      <label for="pw_conf"> Confirm Password </label>
      <?php echo form_error('password_conf', '<div class="error">', '</div>'); ?> 
      <input <?php if(form_error('password_conf')) echo "class='input-error'";?> type="password" name="password_conf" id="password_conf" />
    </div>   
    
    <input class="btn btn-success" type="submit" name="submit" id="submit" value="Install"/> 

 <?php echo form_close(); ?>
</div>
</div>