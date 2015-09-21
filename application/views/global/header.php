<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Tus ideas tienen el derecho de surgir, ven y encuentra a tu equipo!">
        <meta name="author" content="Jose Gonzalez">
        <title><?php echo $view_title; ?> | Group Finder</title>
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/animate.min.css" rel="stylesheet"> 
        <link href="<?php echo base_url(); ?>css/lightbox.css" rel="stylesheet"> 
        <link href="<?php echo base_url(); ?>css/main.css?<?php echo time(); ?>" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/responsive.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/fs/component.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/hoverEffects.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/sweetalert.css" rel="stylesheet">

        <!--[if lt IE 9]>
                <script src="js/html5shiv.js"></script>
                <script src="js/respond.min.js"></script>
        <![endif]-->       
        <link rel="shortcut icon" href="<?php echo base_url(); ?>images/ico/favicon.ico">

        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.3.min.js"></script>

        <script>
            // GLOBAL DEFINES
            var SITE_URL = "<?php echo site_url(); ?>";
            var BASE_URL = "<?php echo base_url(); ?>";
        </script>
    </head><!--/head-->

    <body>
        <header id="header">      
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 overflow">
                        <div class="social-icons pull-right">
                            <ul class="nav nav-pills">
                                <li><a href=""><i class="fa fa-facebook"></i></a></li>
                                <li><a href=""><i class="fa fa-twitter"></i></a></li>
                                <li><a href=""><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="navbar navbar-inverse" role="banner">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <a class="navbar-brand" href="<?php echo site_url(); ?>">
                            <h1><img src="<?php echo base_url(); ?>images/logo.png" alt="logo"></h1>
                        </a>

                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="active"><a href="<?php echo site_url(); ?>">Inicio</a></li>
                            <li><?php echo anchor('projects/explore', 'Explorar'); ?></li>

                            <?php if (!$this->session->loggedIn) { ?>
                                <li><?php echo anchor('auth/login', 'Ingresar'); ?></li>
                                <li><?php echo anchor('auth/register', 'Registrarse'); ?></li>
                            <?php } else { ?>

                                <li><?php echo anchor('users/friends', 'Amigos'); ?></li>
                                <li class="dropdown profile-nav">
                                    <img src="<?php echo $this->session->img_small; ?>" class="img-circle no-margin" alt=":)"/>
                                    <?php echo anchor('users/u/' . $this->session->user_id, $this->session->user_realname); ?>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><?php echo anchor('users/settings', '<i class="fa fa-user"></i>Editar perfil'); ?></li></li>
                                <li><?php echo anchor('projects/mine', '<i class="fa fa-lightbulb-o"></i>Proyectos'); ?></li>
                                <li><?php echo anchor('auth/logout', '<i class="fa fa-sign-out"></i>Cerrar sesión') ?></li>
                            </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <?php if ($count_unread_messages > 0) { ?>
                                        <span class="label label-danger unread_messages"><?= $count_unread_messages ?></span>
                                    <?php } ?>
                                </a>
                                <ul class="dropdown-menu messages-menu">
                                    <?php if ($count_unread_messages > 0) { ?>
                                        <li class="drop-header">Tienes <?= $count_unread_messages ?> mensaje<?php echo $count_unread_messages != 1 ? 's' : ''; ?> sin leer</li>
                                        <li>
                                            <div>
                                                <ul class="menu unread-conversations scrollbar-dark">
                                                    <?php
                                                    if (isset($unread_conversations)) {
                                                        foreach ($unread_conversations as $conv) {
                                                            echo $conv;
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="drop-footer"><?php echo anchor('conversations/all', 'Ver todas las conversaciones'); ?></li>
                                    <?php } else { ?>
                                        <li class="drop-header">No tienes mensajes sin leer</li>
                                        <li class="drop-footer"><?php echo anchor('conversations/all', 'Ver todas las conversaciones'); ?></li>
                                    <?php }
                            }?>
                            </ul>
                        </li>                
                        </ul>
                    </div>
                    <div class="search">
                        <form role="form">
                            <i class="fa fa-search"></i>
                            <div class="field-toggle">
                                <input name="search" id="search" type="text" class="search-form" placeholder="Ingresa una palabra..." autocomplete="off"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <!--/#header-->

        <main id="main">