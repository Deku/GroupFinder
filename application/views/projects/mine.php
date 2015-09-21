<section id="my-projects">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h2 class="page-header">
                    Mis proyectos
                </h2>
                <?php
                    if (isset($error)) { echo '<div class="alert alert-error">' . $error . '</div>'; }
                    if (isset($projects)) {
                ?>
                    <?= anchor('projects/create', 'Publicar un proyecto', array('class' => 'btn btn-primary pull-right')); ?>
                    <div class='grid'>
                        <?php foreach ($projects as $project) { ?>
                                
                        <figure class="effect-zoe wow scaleIn animated">
                            <h2><?= $project->title ?></h2>
                            <img src="<?= $project->picture ?>" alt="<?= $project->title ?>"/>
                            <figcaption>
                                <p class="icon-links">
                                    <?php echo anchor('projects/view/' . $project->project_id, '<i class="fa fa-eye"></i> Ver'); ?>
                                    <?php echo anchor('projects/edit/' . $project->project_id, '<i class="fa fa-pencil"></i> Editar'); ?>
                                </p>
                            </figcaption>
                        </figure>
                
                        <?php } ?>
                    </div>
                <?php } else { ?>
                <h2>Al parecer no has publicado ningún proyecto</h2>
                <br>
                <?php 
                        echo anchor('projects/create', 'Publicar uno ahora', array('class' => 'btn btn-primary'));
                    }
                ?>
            </div>
        </div>
    </div>
</section>