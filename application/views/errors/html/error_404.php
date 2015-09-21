<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>No encontrado :( | Group Finder</title>
    <link href="<?= base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>css/font-awesome.min.css" rel="stylesheet"> 
    <link href="<?= base_url(); ?>css/main.css" rel="stylesheet">
    <link href="<?= base_url(); ?>css/responsive.css" rel="stylesheet">

    <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="<?= base_url(); ?>images/ico/favicon.ico">
</head><!--/head-->

<body>
    <section id="error-page">
        <div class="error-page-inner">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <div class="bg-404">
                                <div class="error-image">                                
                                    <img class="img-responsive" src="<?= base_url(); ?>images/404.png" alt="">  
                                </div>
                            </div>
                            <h2><?php echo $heading; ?></h2>
                            <p><?php echo $message; ?></p>
                            <a href="<?= site_url(); ?>" class="btn btn-error">VOLVER AL INICIO</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/wow.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>