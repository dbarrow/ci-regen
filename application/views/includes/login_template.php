<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in &middot; Twitter Bootstrap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url("css/bootstrap.css")?>" rel="stylesheet">
    <link href="<?php echo base_url("css/bootstrap-responsive.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("css/style.css")?>" rel="stylesheet">

    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #333;
      }      
    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>
    <div class="container">
      <div class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
       <?php echo $main_content?>
      </div> <!-- /form-signin -->
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
  </body>
</html>
