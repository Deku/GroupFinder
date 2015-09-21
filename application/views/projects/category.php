<section id="category-overview">
    <div class="container">
        <?php if (isset($category_name)) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="title-dropdown">
                        Categoría:
                        <?php echo form_dropdown('cat-navigator', $categories, $this->uri->segment(3), array('class' => 'title-dropdown-nav')); ?>
                    </div>
                    <?php if (isset($projects)) { ?>
                        <ul class="projects-list">
                            <?php foreach ($projects as $project) { ?>
                                <li>
                                    <img src="<?= $project->picture ?>" alt="<?= $project->title ?>"/>
                                    <div class="project-item-info">
                                        <span class="title"><?= $project->title ?></span>
                                        <ul>
                                            <li>Estado: <?= $project->status_name ?></li>
                                            <li><em><?= $project->summary ?></em></li>
                                            <li><?= anchor('projects/view/' . $project->project_id, '<i class="fa fa-eye"></i> Ver', array('class' => 'btn btn-primary pull-right')); ?></li>
                                        </ul>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <h2>No existen proyectos para la categoría actual.</h2>
                    <?php } ?>
                        <script src="<?php echo base_url(); ?>js/groupfinder/gui/explore-category.js"></script>
                </div>
            </div>
        <?php } else { ?>
            <h1>Ups! No hemos encontrado esa categoría</h1>
            <?php echo anchor('portal/home', 'Volver al inicio'); ?>
        <?php } ?>
    </div>
</section>