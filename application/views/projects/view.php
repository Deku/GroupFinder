<section id="view-project">
    <div class="container">
     <?php if (isset($project)) { ?>
        <div class="row">
            <div class="col-md-12">
                <figure class="cover">
                    <img src="<?php echo $project->picture; ?>" alt="<?php echo $project->title; ?>"/>
                    <figcaption>
                        <h2><?php echo $project->title; ?></h2>
                        <span>por <?php echo anchor('users/u/' . $project->owner_id, $project->owner_name); ?></span>
                    </figcaption>
                </figure>
                <div class="corner-down-right clearfix">
                    <div class="pull-right">
                        <span class="inverse"><i class="fa fa-tag"></i> <?php echo anchor('projects/category/'.$project->category_id, $project->category, array('class' => 'text-white')); ?></span>
                    </div>
                </div>
                <?php if ($this->session->user_id == $project->owner_id) : ?>
                    <div class="corner-up-right clearfix">
                        <span class="pull-right">
                            <?= anchor('projects/edit/' . $project->project_id, '<i class="fa fa-pencil"></i> Editar', array('class' => 'btn btn-default')); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-12">
                <div role="tabpanel">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="active"><a href="#project-general" data-toggle="tab"><i class="fa fa-rocket"></i> Proyecto</a></li>
                        <li><a href="#project-team" data-toggle="tab"><i class="fa fa-users"></i> Equipo</a></li>
                        <li><a href="#project-resources" data-toggle="tab"><i class="fa fa-shopping-cart"></i> Costos</a></li>
                        <?php if ($project->funding_mode == FUNDING_MODE_COMMUNITY) : ?>
                            <li><a id="show-donations" href="#project-funding" data-toggle="tab"><i class="fa fa-money"></i> Donar</a></li>
                        <?php endif; ?>
                        <li><a href="#project-faq" data-toggle="tab"><i class="fa fa-question-circle"></i> F.A.Q.</a></li>
                        <li><a href="#project-comments" data-toggle="tab"><i class="fa fa-comments-o"></i> Comentarios</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="tab-content">

                    <!-- General -->
                    <div role="tabpanel" class="tab-pane active" id="project-general">
                        <div class="content">
                            <div class="col-xs-12 col-md-7">
                                <div>
                                    <h2 class="page-header"><i class="fa fa-comment-o"></i> Acerca del proyecto</h2>
                                    <?php echo isset($project->extra_info) ? base64_decode($project->extra_info) : 'No se ha entregado más informaci&oacute;n sobre el proyecto.'; ?>
                                </div>

                                <div class="padding-top-sm">
                                    <h2 class="page-header"><i class="fa fa-asterisk"></i> Problema</h2>
                                    <p class="justify"><?php echo $project->problem; ?></p>
                                </div>

                                <div class="padding-top-sm">
                                    <h2 class="page-header"><i class="fa fa-lightbulb-o"></i> Soluci&oacute;n</h2>
                                    <p class="justify"><?php echo $project->solution; ?></p>
                                </div>

                                <div class="padding-top-sm">
                                    <h2 class="page-header"><i class="fa fa-bullseye"></i> Grupo objetivo</h2>
                                    <p class="justify"><?php echo !empty($project->target_group) ? $project->target_group : 'No se ha indicado grupo objetivo'; ?></p>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-5">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">Autor</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="author-profile user-card">
                                            <div class="user-picture">
                                                <img src="<?php echo $project->owner_picture; ?>" class="img-circle" alt="<?php echo $project->owner_name; ?>" />
                                            </div>
                                            <div class="clearfix user-info">
                                                <div class="user-name">
                                                    <?php echo anchor('users/u/' . $project->owner_id, $project->owner_name); ?></h2>
                                                </div>
                                                <ul>
                                                    <?php if (isset($project->owner_title)) { echo '<li class="user-title">' . $project->owner_title . '</li>'; } ?>
                                                    <li>País: <?php echo $project->owner_country; ?></li>
                                                    <li>Miembro desde el <?php echo $project->owner_registered; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">Financiamiento</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <?php if ($project->funding_mode == FUNDING_MODE_PRIVATE) : ?>
                                                <div class="col-xs-12">
                                                    <div class="text-center">
                                                        <h3>Este proyecto ser&aacute; financiado con capital privado.</h3>
                                                    </div>
                                                </div>
                                            <?php elseif ($project->funding_mode == FUNDING_MODE_GOV) : ?>
                                                <div class="col-xs-12">
                                                    <div class="text-center">
                                                        <h3>Este proyecto ser&aacute; financiado con el capital aportado por
                                                        alguno de los programas del Estado.</h3>
                                                    </div>
                                                </div>
                                            <?php elseif ($project->funding_mode == FUNDING_MODE_COMMUNITY) : ?>
                                                <div class="col-xs-12">
                                                    <div class="text-center">
                                                        <p><strong>¡Este proyecto necesita tu apoyo!</strong></p>
                                                        <p>Patrocina este proyecto aportando con un poco de dinero. Cada granito
                                                         de arena cuenta.</p>
                                                        <p><strong>Meta:</strong> $<?= $project->funding_goal ?></p>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="560000" aria-valuemin="0" aria-valuemax="<?= $project->funding_goal ?>" style="width: <?= 560000 * 100 / $project->funding_goal ?>%">
                                                                $560.000 recaudados
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-success" onclick="$('#show-donations').trigger('click')">Ver opciones disponibles</a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:general -->

                    <!-- Equipo -->
                    <div role="tabpanel" class="tab-pane" id="project-team">
                        <div class="content">
                            <div class="col-xs-12 col-md-12">
                                <h2 class="page-header"><i class="fa fa-users"></i> Equipo</h2>
                                <div class="user-cards-holder">
                                    <?php
                                        if (!empty($team)) :
                                            foreach ($team as $member) :
                                    ?>
                                            <div class="user-list-item" id="members_<?= $member['member_id'] ?>">
                                                <div class="user-card">
                                                    <div class="user-picture">
                                                        <a href="<?= $member['profile_url'] ?>">
                                                            <img src="<?= $member['img_src'] ?>" class="img-responsive" alt="<?= $member['name'] ?>" />
                                                        </a>
                                                    </div>
                                                    <div class="clearfix user-info">
                                                        <div class="user-name">
                                                            <a class="user-name" href="<?= $member['profile_url'] ?>"><?= $member['name'] ?><?= (isset($member['leader_icon']) ? ' ' . $member['leader_icon'] : '') ?></a>
                                                        </div>
                                                        <ul>
                                                            <li><span class="user-title"><?= $member['title'] ?></span></li>
                                                            <li><span class="user-role">Rol: <?= $member['role_name'] ?></span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                            endforeach;
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <?php if (isset($roles) && !empty($roles)) : ?>
                                <div class="col-xs-12 col-md-12">
                                    <h2 class="page-header"><i class="fa fa-search"></i> Vacantes abiertas</h2>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="alert alert-info">
                                                Postulaciones abiertas hasta el <strong><?php echo $project->limit_date; ?></strong>
                                            </div>
                                            <p>Este proyecto busca personas para las siguientes vacantes:</p>
                                            <div class="vacants">
                                                <?php
                                                    foreach ($roles as $role) {
                                                        echo $role;
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:team -->


                    <!-- Costos -->
                    <div role="tabpanel" class="tab-pane" id="project-resources">
                        <div class="content">
                            <h2 class="page-header"><i class="fa fa-shopping-cart"></i> Costos</h2>
                            <div class="col-xs-12 col-md-12">
                                <?php
                                if (isset($resources_table) && !empty($resources_table)) {
                                    echo $resources_table;
                                } else {
                                    ?>
                                    <p>No se han definido los costos involucrados en este proyecto</p>
                                <?php } ?>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:resources -->

                    <?php if ($project->funding_mode == FUNDING_MODE_COMMUNITY) : ?>
                    <!-- Financiamiento -->
                    <div role="tabpanel" class="tab-pane" id="project-funding">
                        <div class="content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="text-center">
                                        <h2>¡Este proyecto necesita tu apoyo!</h2>
                                        <p>Patrocina este proyecto aportando con un poco de dinero. Cada granito
                                         de arena cuenta.</p>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <p><strong>Meta:</strong> $<?= $project->funding_goal ?></p>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="560000" aria-valuemin="0" aria-valuemax="<?= $project->funding_goal ?>" style="width: <?= 560000 * 100 / $project->funding_goal ?>%">
                                            $560.000 recaudados
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <?php if ($project->rewards_activated) : ?>
                                    <div class="col-md-12">
                                        <ul>
                                            <?php 
                                                if (isset($rewards) && !empty($rewards)) {
                                                    foreach ($rewards as $reward) {
                                                        echo $reward;
                                                    }
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                <?php else : ?>
                                    <div class="col-md-12 text-center">
                                        <h2>Aporte voluntario</h2>
                                        <p>Agradecemos tu entusiasmo por apoyar este proyecto. Por favor indica el monto que deseas aportar:</p>
                                    </div>
                                    <div class="col-md-6 col-md-offset-3">
                                        <?= form_open('projects/donate') ?>
                                            <div class="form-group">
                                                <input type="hidden" name="project-id" value="<?= $project->project_id ?>"/>
                                                <input type="number" class="form-control" name="backing[amount]" min="0" placeholder="CLP $ 0" required>
                                                <input name="backing[reward_id]" type="hidden" value="0">
                                            </div>
                                            <button type="submit" class="btn btn-success pull-right">Continuar</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div><!-- row -->
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:funding -->
                    <?php endif; ?>

                    <!-- Preguntas Frecuentes -->
                    <div role="tabpanel" class="tab-pane" id="project-faq">
                        <div class="content">
                            <h2 class="page-header"><i class="fa fa-question-circle"></i> Preguntas Frecuentes</h2>
                            <ul class="faq-list">
                            <?php
                                if (isset($faq)) :
                                    foreach ($faq as $f) :
                            ?>
                                    <li class="alert alert-info">
                                        <h4>P: <?= $f['question'] ?></h4>
                                        <p>R: <?= $f['answer'] ?></p>
                                    </li>
                            <?php
                                    endforeach;
                                endif;
                            ?>
                            </ul>
                        </div><!-- ./content -->
                    </div><!--  ./tabpanel:faq -->

                    <!-- Comentarios -->
                    <div role="tabpanel" class="tab-pane" id="project-comments">
                        <div id="feedback">
                            <h2 id="comments-title"></h2>
                            <hr>
                            <div id="comments"></div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    load_comments(<?php echo PROFILE_TYPE_PROJECT . ',' . $this->uri->segment(3) . ',' . ($this->session->loggedIn ? 'true' : 'false'); ?>);
                                });
                            </script>

                            <?php if ($this->session->loggedIn) { ?>
                                <div class="message_heading">
                                    <h4>Comentar</h4>
                                </div> 

                                <?php 
                                $attr = array(
                                    'id' => 'comment-form',
                                    'class' => 'contact-form',
                                    'data-t' => PROFILE_TYPE_PROJECT,
                                    'data-i' => $this->uri->segment(3),
                                    'data-l' => ($this->session->loggedIn ? 'true' : 'false')
                                );
                                echo form_open('comments/post', $attr); ?>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <img class="img-circle img-responsive" style="width:auto;height:130px;" src="<?php echo $this->session->img_large; ?>" />
                                        </div>
                                        <div class="col-sm-10">                        
                                            <div class="form-group">
                                                <input type="hidden" name="ref" id="ref" value="<?php echo $this->uri->segment(3); ?>" />
                                                <input type="hidden" name="origin" id="origin" value="<?php echo PROFILE_TYPE_PROJECT; ?>" />
                                                <textarea id="message" required class="form-control" rows="6"></textarea>
                                            </div>                        
                                            <div class="form-group">
                                                <button id="comment-submit" type="submit" class="btn btn-primary btn-lg pull-right" required="required">Enviar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                        <script type="text/javascript" src="<?php echo base_url(); ?>js/groupfinder/app/comments.js"></script>
                    </div>
                </div><!-- ./tab-content -->
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h2>Proyecto no encontrado</h2>
                <?php echo anchor('portal/home', 'Volver al inicio'); ?>
            </div>
        </div>
    <?php } ?>
    </div>
</section>


<div class="modal fade" id="modal-application" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2>Por favor confirma tu postulación a la vacante</h2>
                            <p>Puedes adjuntar un mensaje para el líder del proyecto</p>
                            <div id="application-notification"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <img src="<?php echo $this->session->img_medium; ?>" class="img-circle" alt=":)"/>
                        </div>
                        <div class="col-xs-12 col-md-9">
                            <div class="form-group">
                                <label class="control-label">Mensaje</label>
                                <textarea id="application-message" class="form-control"></textarea>
                                <input type="hidden" id="rid"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-confirm" class="save btn btn-primary">Confirmar</button>
                <button type="button" id="btn-cancel" class="save btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var pID = <?php echo $project->project_id; ?>;
    
    $(document).ready( function () {
        
        $("time.timeago").each(function() {
            $(this).html($.timeago($(this).attr("datetime")));
        });
    }); 
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>/js/groupfinder/gui/project-view.js"></script>