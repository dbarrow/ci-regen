<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Re:Gen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url("css/bootstrap.css")?>" rel="stylesheet">
    <link href="<?php echo base_url("css/style.css")?>" rel="stylesheet">
   <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
   <script src="<?php echo base_url("js/addremove.jquery.js")?>"></script>
      <script src="<?php echo base_url("js/bootstrap.js")?>"></script>


    <style>
      body {
        padding-top: 60px;; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>  
        <link href="<?php echo base_url("css/bootstrap-responsive.css"); ?>" rel="stylesheet">


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>


<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" style="padding:7px 0px 0px 20px;width:150px;"
            href="<?php echo base_url('services');?>"><img style="padding:0px 0px 0px 0px;width:148px;" 
            src="<?php echo base_url("img/regen.png"); ?>" >
          </a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li > <a href="<?=base_url('services');?>">Services</a> </li>
              <li > <a href="<?=base_url('applications');?>">Applications</a> </li>
            </ul>
             <ul class="nav pull-right">
              <li > <a href="<?=base_url('auth/logout');?>">Logout</a> </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>



    <div class="container">
      <?php echo $main_content;?> 
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

  </body>
</html>
