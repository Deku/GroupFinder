    <section id="home-slider">
        <div class="container">
            <div class="row">
                <div class="main-slider">
                    <div class="slide-text">
                        <h1>Deja de imaginar y comienza a realizar</h1>
                        <p>Tu emprendimiento est&aacute; m&aacute;s cerca de lo que imaginas. An&iacute;mate a crear tu proyecto o únete a aquel que te motive.</p>
                        <?= anchor('projects/create', 'CREA UN PROYECTO', array('class' => 'btn btn-call-to-action')); ?>
                        <?= anchor('projects/explore', 'COMIENZA TU B&Uacute;SQUEDA', array('class' => 'btn btn-common')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="preloader"><i class="fa fa-sun-o fa-spin"></i></div>
    </section>
    <!--/#home-slider-->

    <section id="featured">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="page-header">Proyectos recomendados</h2>
                </div>
                
                <?php foreach ($featured as $feat): ?>
                    <div class="col-sm-6 col-md-4 wow scaleIn">
                        <div class="thumbnail">
                            <img src="<?= $feat->picture ?>" class="img-responsive" alt="<?= $feat->title ?>">
                            <div class="caption">
                                <h4><strong><?= $feat->title ?></strong></h4>
                                <p><?= $feat->summary ?></p>
                                <div class="clearfix">
                                    <span class="pull-left"><i class="fa fa-leaf text-success"></i> <?= $feat->status_name?></span>
                                    <span class="pull-right"><i class="fa fa-clock-o text-info"></i> <?= $feat->limit_date ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
