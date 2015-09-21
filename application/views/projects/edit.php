<input type="hidden" id="pid" value="<?php echo $project->project_id; ?>"/>

<section id="edit-project">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <?php if ($project->status != PROJECT_STATUS_DEAD) : ?>
                    <div class="steps-progress">
                        <?php
                            $steps = array(
                                array(
                                    'label' => 1,
                                    'title' => 'Creaci&oacute;n',
                                ),
                                array(
                                    'label' => 2,
                                    'title' => 'Edici&oacute;n',
                                ),
                                array(
                                    'label' => 3,
                                    'title' => 'Crecimiento',
                                ),
                                array(
                                    'label' => 4,
                                    'title' => 'Ejecuci&oacute;n',
                                ),
                                array(
                                    'label' => 5,
                                    'title' => 'Finalizado',
                                    'status' => ''
                                )
                            );

                            for ($i = 0; $i < count($steps); $i++) :
                        ?>
                            <div class="circle <?= ($i < $project->status - 1 ? 'done' : ($i == $project->status - 1 ? 'active' : '' )) ?>">
                                <span class="step-label"><?= $steps[$i]['label'] ?></span>
                                <span class="step-title"><?= $steps[$i]['title'] ?></span>
                            </div>
                            <?php if ($i < (count($steps) - 1)) : ?>
                                <span class="bar <?= ($i < $project->status - 1 ? 'done' : '') ?>"></span>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <div class="clearfix">
                            <?php if ($project->status == PROJECT_STATUS_GROWING) : ?>
                                <button type="button" disabled class="btn pull-right">Siguiente etapa al llegar la fecha l&iacute;mite</button>
                            <?php else : ?>
                                <button class="pull-right btn btn-success" type="button" id="btn-next-step">Siguiente etapa <i class="fa fa-angle-right"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row flex">
            <div class="col-xs-3 col-md-3 outlined-right no-padding">
                <div class="sidebar" role="tabpanel">
                    <div class="sidebar-item categories">
                        <h3>Men&uacute;</h3>
                        <ul class="nav navbar-stacked" role="tablist">
                            <li class="active"><a href="#edit-welcome" data-toggle="tab">Inicio</a></li>
                            <li><a href="#edit-general" data-toggle="tab">General</a></li>
                            <li><a href="#edit-info-extra" data-toggle="tab">Informaci&oacute;n extra</a></li>
                            <li><a href="#edit-team" data-toggle="tab">Equipo</a></li>
                            <li><a href="#edit-resources" data-toggle="tab">Costos</a></li>
                            <li><a href="#edit-funding" data-toggle="tab">Financiamiento</a></li>
                            <li><a href="#edit-faq" data-toggle="tab">Preguntas Frecuentes</a></li>
                        </ul>
                        <p class="padding-top-sm">
                            <?php echo anchor('projects/view/' . $project->project_id, 'Ver vista previa &nbsp;&nbsp;<i class="fa fa-external-link"></i>', array('target' => '_blank')); ?>                            
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xs-9 col-md-9">
                <div class="tab-content">
                    <!-- Bienvenida -->
                    <div role="tabpanel" class="tab-pane fade in active" id="edit-welcome">
                        <div class="content">
                            <?php if ($project->status == PROJECT_STATUS_DEAD) : ?>
                                <h1 class="text-danger text-center"><i class="fa fa-times-circle-o"></i> Este proyecto est&aacute; abandonado.</h1>
                                <p class="text-danger text-center">No puede ser editado ni visualizado p&uacute;blicamente.</p>
                            <?php elseif ($project->status == PROJECT_STATUS_EDITING) : ?>
                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <h2 class="text-center">&iexcl;Bienvenido a tu proyecto!</h2>
                                        <p class="padding-top-sm">Tu proyecto a&uacute;n no est&aacute; disponible a la comunidad, ya que requiere algunos ajustes finales antes de su publicaci&oacute;n.</p>
                                        <p>Puedes tener una <?= anchor('projects/view/' . $project->project_id, 'vista previa de tu proyecto'); ?>, 
                                            o puedes compartir el siguiente link con tus amigos para que lo vean tambi&eacute;n.</p>
                                        <div class="well">
                                            <code id="code-preview" onclick="selectText('code-preview')"><?= site_url('projects/view/' . $project->project_id . '/' . $project->preview_hash) ?></code>
                                        </div>
                                        <span class="help-block">Click en la URL y luego Ctrl + C para copiar</span>
                                    </div>
                                </div>
                            <?php elseif ($project->status == PROJECT_STATUS_GROWING) : ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="text-center">Proyecto en crecimiento</h2>
                                        <p class="padding-top-sm">
                                            Tu proyecto ya ha sido publicado y est&aacute; en proceso de crecimiento.
                                        </p>
                                        <p>
                                            <strong>Quedan <?= round((((strtotime($project->limit_date) - time())/24)/60)/60) ?> d&iacute;as</strong>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- General -->
                    <div role="tabpanel" class="tab-pane fade in" id="edit-general">
                        <div class="content">
                            <div class="row">
                                <div class="text-center">
                                    <h2>Informaci&oacute;n General</h2>
                                    <span>La informaci&oacute;n basica sobre tu proyecto</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div id="save-general-notification"></div>
                                    <button type="button" class="btn btn-primary pull-right save_general_js">Guardar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-rocket"></i>&nbsp;&nbsp;T&iacute;tulo del proyecto:</strong></h4>
                                    <input type="text" id="info-title" name="info-title" class="form-control" value="<?php echo $project->title; ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-bookmark-o"></i>&nbsp;&nbsp;Categor&iacute;a:</strong></h4>
                                    <?php echo form_dropdown('category', $categories, $project->category_id, array('id' => 'info-category', 'class' => 'form-control')); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;Fecha l&iacute;mite:</strong></h4>
                                    <p>Para el reclutamiento y financiamiento</p>
                                    <input type="date" id="info-due-date" name="info-due-date" class="form-control" value="<?php echo date("Y-m-d", strtotime($project->limit_date)); ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-pencil"></i>&nbsp;&nbsp;Resumen:</strong></h4>
                                    <p>Describe tu proyecto en 200 caracteres. Este texto se mostrar&aacute; en la vista previa</p>
                                    <?php echo form_textarea(array('id' => 'info-summary', 'name' => 'info-summary', 'class' => 'form-control', 'rows' => 5, 'maxlength' => '200'), $project->summary); ?>
                                    <p class="text-muted text-right"><span id="info-summary-chars"><?= strlen($project->summary); ?></span> caracteres</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Problema:</strong></h4>
                                    <?php echo form_textarea(array('id' => 'info-problem', 'name' => 'info-problem', 'class' => 'form-control', 'rows' => 5), $project->problem); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-lightbulb-o"></i>&nbsp;&nbsp;Soluci&oacute;n:</strong></h4>
                                    <?php echo form_textarea(array('id' => 'info-solution', 'name' => 'info-solution', 'class' => 'form-control', 'rows' => 5), $project->solution); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <h4><strong><i class="fa fa-bullseye"></i>&nbsp;&nbsp;Grupo objetivo</strong></h4>
                                    <?php echo form_textarea(array('id' => 'info-target', 'name' => 'info-target', 'class' => 'form-control', 'rows' => 5), $project->target_group); ?>
                                </div>
                            </div>
                            <div class="row padding-top-sm">
                                <div class="col-xs-12 col-md-12">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h2 class="panel-title">Eliminar proyecto</h2>
                                        </div>
                                        <div class="panel-body">
                                            <p>Una vez eliminado el proyecto, no hay vuelta atr&aacute;s. Pi&eacute;nsalo bien antes de actuar.</p>
                                            <button type="button" class="btn btn-danger pull-right">Eliminar este proyecto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:general -->

                    <!-- Informacion extra -->
                    <div role="tabpanel" class="tab-pane fade in" id="edit-info-extra">
                        <div class="content">
                            <div class="row">
                                <div class="text-center">
                                    <h2>Informaci&oacute;n extra</h2>
                                    <span>Explica tu proyecto de la mejor forma posible (puedes utilizar multimedia)</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div id="save-extra-notification"></div>
                                    <button type="button" class="btn btn-primary pull-right save_extra_js">Guardar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <?php echo form_textarea(array('id' => 'extra-information', 'name' => 'extra-information', 'class' => 'form-control'), base64_decode($project->extra_info)); ?>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:about -->

                    <!-- Equipo -->
                    <div role="tabpanel" class="tab-pane fade in" id="edit-team">
                        <div class="content">
                            <div class="row">
                                <div class="text-center">
                                    <h2>Tu equipo</h2>
                                    <span>Vacantes, postulaciones e invitaciones para formar tu equipo</span>
                                </div>
                            </div>
                            <div class="row padding-top-sm">
                                <div class="col-xs-12 col-md-12">
                                    <div role="tabpanel">
                                        <ul class="nav nav-tabs nav-justified" role="tablist">
                                            <li class="active"><a href="#vacants-tab-team" data-toggle="tab">Tu equipo</a></li>
                                            <li><a href="#vacants-tab-applications" data-toggle="tab">Postulaciones</a></li>
                                            <li><a href="#vacants-tab-edit" data-toggle="tab">Editar roles</a></li>
                                        </ul>
                                    </div>
                                    <div class="tab-content padding-top-sm">
                                        <div role="tabpanel" class="tab-pane active" id="vacants-tab-team">

                                            <?php
                                            if (!empty($team)) {
                                                echo '<div class="user-cards-holder">';
                                                foreach ($team as $member) {
                                                    echo $member;
                                                }
                                                echo '</div>';
                                            } else {
                                                echo '<div class="alert alert-info">Tu equipo no tiene ning&uacute;n miembro.</div>';
                                            }
                                            ?>

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="vacants-tab-applications">
                                            <?php
                                            if (isset($applications) && !empty($applications)) {
                                                echo '<ul class="requests-holder">';
                                                foreach ($applications as $app) {
                                                    echo $app;
                                                }
                                                echo '</ul>';
                                            } else {
                                                echo '<div class="alert alert-info">No hay postulaciones nuevas</div>';
                                            }
                                            ?>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="vacants-tab-edit">
                                            <div class="padding-bottom">
                                                <?php
                                                if (!empty($vacants_table)) {
                                                    echo $vacants_table;
                                                }
                                                ?>
                                            </div>
                                            <div class="clearfix">
                                                <a class="btn-display-add-box pull-right" href="#" data-target="#form-add-role"><i class="fa fa-plus-circle"></i> Agregar</a>
                                            </div>
                                            <br>
                                            <form action="" method="post" id="form-add-role" class="form-horizontal well fadeInDown animated no-display">
                                                <h3>Agregar rol</h3>
                                                <div id="add-vacant-notification"></div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Rol:</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" id="add-vacant-role" placeholder="Nombre del rol. Ej: Dise&ntilde;ador, Programador, etc."/>
                                                    </div>

                                                    <label class="col-md-1 control-label">Cupos:</label>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" id="add-vacant-amount" placeholder="1"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Descripci&oacute;n del rol:</label>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" id="add-vacant-description" placeholder="&iquest;A qu&eacute; se dedicar&aacute; esta persona dentro del proyecto?"></textarea>
                                                    </div>
                                                </div>
                                                <button type="button" class="pull-right btn btn-primary" id="button-add-vacant">Guardar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:team -->


                    <!-- Costos -->
                    <div role="tabpanel" class="tab-pane fade in" id="edit-resources">
                        <div class="content">
                            <div class="row">
                                <div class="text-center">
                                    <h2>Costos</h2>
                                    <span>&iquest;Qu&eacute; necesitar&aacute;s y cu&aacute;les son los costos asociados?</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <?php
                                    if (isset($resources_table)) {
                                        echo $resources_table;
                                    } else {
                                        ?>
                                        <p>No se han definido los costos involucrados en este proyecto</p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="clearfix">
                                        <a class="btn-display-add-box pull-right" href="#" data-target="#form-add-resource"><i class="fa fa-plus-circle"></i> Agregar</a>
                                    </div>
                                    <br>
                                    <form action="" method="post" id="form-add-resource" class="form-horizontal well fadeInDown animated no-display">
                                        <h3>Agregar recurso</h3>
                                        <div id="add-vacant-notification"></div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Nombre del recurso:</label>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" id="add-resource-name" name="add-resource-name" />
                                            </div>

                                            <div class="col-md-2 checkbox">
                                                <label class="control-label">
                                                    Importante
                                                    <input id="add-resource-required" type="checkbox"/>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Cantidad</label>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" id="add-resource-amount" placeholder="0"/>
                                            </div>

                                            <label class="col-md-1 control-label">Costo</label>
                                            <div class="col-md-5">
                                                <input type="number" class="form-control" id="add-resource-cost" name="add-resource-cost" placeholder="$ 0.0"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Utilizaci&oacute;n:</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="add-resource-description" placeholder="&iquest;En qu&eacute; se utilizar&aacute; este recurso?"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <button type="button" class="pull-right btn btn-primary" id="button-add-resource">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:costs -->

                    <!-- Financiamiento -->
                    <div role="tabpanel" class="tab-pane fade in" id="edit-funding">
                        <div class="content">
                            <div class="row">
                                <div class="text-center">
                                    <h2>Financiamiento</h2>
                                    <span>Toda la configuraci&oacute;n sobre el financiamiento de tu proyecto.</span>
                                </div>
                            </div>
                            <div class="row padding-top-sm">
                                <div class="col-xs-12 col-md-9">
                                    <div class="title-dropdown">
                                        Financiamiento 
                                        <?= form_dropdown('funding-mode', $funding_modes, $project->funding_mode, array('id' => 'funding-mode', 'class' => 'title-dropdown-nav')); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    <div id="notification-funding-mode"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div id="funding-tab-content" class="tab-content">
                                        <!-- Financiamiento privado -->
                                        <div role="tabpanel" class="tab-pane fade in<?= ($project->funding_mode == FUNDING_MODE_PRIVATE ? ' active' : '') ?>" id="funding-private">
                                            <p>Has decidido financiar el proyecto con tus propios recursos.</p>
                                        </div>

                                        <!-- Financiamiento con ayuda del estado -->
                                        <div role="tabpanel" class="tab-pane fade in<?= ($project->funding_mode == FUNDING_MODE_GOV ? ' active' : '') ?>" id="funding-gov">
                                            <p>Has decidido financiar el proyecto postulando a alguno de los programas
                                                de financiamiento otorgados por el Estado.</p>
                                        </div>

                                        <!-- Financiamiento comunitario -->
                                        <div role="tabpanel" class="tab-pane fade in<?= ($project->funding_mode == FUNDING_MODE_COMMUNITY ? ' active' : '') ?>" id="funding-community">
                                            
                                            <h3 class="page-header">Meta</h3>
                                            <p class="justify">A continuaci&oacute;n deber&aacute;s indicar el monto total a recaudar y los datos de la cuenta bancaria a la
                                            que quieres que se transfiera el monto recaudado.</p>
                                            
                                            <div class="alert alert-warning">
                                                <h4>Importante:</h4>
                                                <ul style="list-style: inherit">
                                                    <li>Group Finder retendr&aacute; un 5% del total recaudado por concepto de comisi&oacute;n.</li>
                                                    <li>La transferencia de fondos se realizar&aacute; s&oacute;lo si se ha cumplido con la meta al llegar la fecha l&iacute;mite.</li>
                                                </ul>
                                            </div>
                                            <br>
                                            <form id="form-funding-goal" action="" method="POST">
                                                <div class="row">
                                                    <span class="col-md-4">Financiamiento requerido:</span>
                                                    <div class="col-md-6">
                                                        <input type="number" name="funding-goal" class="form-control" value="<?= $project->funding_goal ?>" required/>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <br>
                                            <div class="panel-group" id="panel-bank-account" role="tablist" aria-multiselectable="true">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading" role="tab" id="panel-bank-account-heading">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#pannel-bank-account" href="#panel-bank-account-body" aria-controls="panel-bank-account-body">
                                                                <i class="fa fa-cog"></i> Cuenta bancaria
                                                                <span class="pull-right">Ver</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="panel-bank-account-body" class="panel-collapse collapse" role="tabpanel" aria-labelledby="panel-bank-account-heading">
                                                        <div class="panel-body">
                                                            <?= form_open('', array('id' => 'form-bank-data')); ?>
                                                                <div class="form-group">
                                                                    <label for="bank-id" class="control-label">Banco:</label>
                                                                    <?= form_dropdown('bank-id', $banks, $project->bank_id, array('id' => 'bank-id', 'class' => 'form-control')); ?>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="bank-acc-type" class="control-label">Tipo de cuenta:</label>
                                                                    <?= form_dropdown('bank-acc-type', $bank_acc_types, $project->bank_acc_type, array('id' => 'bank-acc-type', 'class' => 'form-control')); ?>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="bank-acc-number" class="control-label">N&uacute;mero de cuenta:</label>
                                                                    <input type="text" id="bank-acc-number" name="bank-acc-number" class="form-control" value="<?= isset($project->bank_acc_number) ? $project->bank_acc_number : '' ?>" />
                                                                    <span class="help-block">Ingrese sin guiones ni puntos</span>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="bank-acc-name" class="control-label">Nombre y apellido:</label>
                                                                    <input type="text" id="bank-acc-name" name="bank-acc-name" class="form-control" value="<?= isset($project->bank_acc_name) ? $project->bank_acc_name : '' ?>" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="bank-acc-rut" class="control-label">RUT:</label>
                                                                    <input type="text" id="bank-acc-rut" name="bank-acc-rut" class="form-control" value="<?= isset($project->bank_acc_rut) ? $project->bank_acc_rut : '' ?>" />
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="bank-acc-email" class="control-label">Correo electr&oacute;nico:</label>
                                                                    <input type="text" id="bank-acc-email" name="bank-acc-email" class="form-control" value="<?= isset($project->bank_acc_email) ? $project->bank_acc_email : '' ?>" />
                                                                    <span class="help-block">(opcional)</span>
                                                                </div>

                                                                <div class="clearfix">
                                                                    <div class="pull-right">
                                                                        <button type="button" id="btn-funding-bank-save" class="btn btn-primary">Guardar cuenta</button>
                                                                        <div id="notification-funding-bank-acc"></div>
                                                                    </div>
                                                                </div>
                                                            <?= form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3 class="page-header">Recompensas</h3>
                                            <p>&iquest;Deseas entregar recompensas por las donaciones recibidas?</p>
                                            <label class="checkbox">
                                                <input type="checkbox" id="funding-rewards-activate" <?= $project->rewards_activated ? 'checked' : '' ?> />
                                                &nbsp;Habilitar recompensas
                                            </label>
                                            <div id="notification-funding-rewards"></div>
                                            
                                            <div class="fadeIn animated padding-top-sm" <?= $project->rewards_activated ? '' : 'style="display:none"' ?> id="funding-rewards">
                                                
                                                <table class="table table-responsive table-hover" id="table-funding-rewards" <?= (!isset($rewards) || empty($rewards)) ? 'style="display:none"' : '' ?>>
                                                    <thead>
                                                        <td>Descripci&oacute;n</td>
                                                        <td>Monto m&iacute;nimo</td>
                                                        <td>Stock</td>
                                                        <td>Env&iacute;o</td>
                                                        <td>Fecha env&iacute;o</td>
                                                        <td>Observaciones</td>
                                                        <td></td>
                                                    </thead>
                                                    <?php
                                                        foreach ($rewards as $reward) {
                                                            echo $reward;
                                                        }
                                                    ?>
                                                </table>
                                                
                                                <div class="clearfix">
                                                    <a class="btn-display-add-box pull-right" href="#" data-target="#form-add-rewards"><i class="fa fa-plus-circle"></i> Agregar</a>
                                                </div>
                                                <br>
                                                <form action="" method="post" id="form-add-rewards" class="form-horizontal well fadeInDown animated no-display">
                                                    <h3>Agregar recompensa</h3>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Descripci&oacute;n:</label>
                                                        <div class="col-md-9">
                                                            <textarea class="form-control" id="add-rewards-description" name="add-rewards-description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Monto m&iacute;nimo:</label>
                                                        <div class="col-md-9">
                                                            <input type="number" class="form-control" id="add-rewards-min" name="add-rewards-min" min="1" placeholder="CLP $ 0" value="1"/>
                                                            <span class="help-block">Valores en pesos chilenos (CLP)</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Stock l&iacute;mite:</label>
                                                        <div class="col-md-9">
                                                            <input type="number" class="form-control" id="add-rewards-min" name="add-rewards-limit" placeholder="0" value="0"/>
                                                            <span class="help-block">Ingrese "0" para stock ilimitado</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Env&iacute;o:</label>
                                                        <div class="col-md-3">
                                                            <select class="form-control" id="add-rewards-delivery" name="add-rewards-delivery">
                                                                <option value="<?= REWARD_DELIVERY_NONE ?>">No requiere env&iacute;o</option>
                                                                <option value="<?= REWARD_DELIVERY_RETIRE_ADDRESS ?>">Retiro en direcci&oacute;n espec&iacute;fica</option>
                                                                <option value="<?= REWARD_DELIVERY_SENT_BY_MAIL ?>">Despacho a domicilio</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <label class="col-md-2 control-label">Fecha env&iacute;o:</label>
                                                        <div class="col-md-4">
                                                            <input type="month" name="add-rewards-date" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Observaciones:</label>
                                                        <div class="col-md-9">
                                                            <textarea id="add-rewards-notes" name="add-rewards-notes" class="form-control"></textarea>
                                                            <div class="help-block"><span id="add-rewards-notes-chars">0</span>/200 caracteres</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="clearfix">
                                                        <button type="button" class="pull-right btn btn-primary" id="btn-add-rewards">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:funding -->

                    <!-- Preguntas Frecuentes -->
                    <div role="tabpanel" class="tab-pane fade in" id="edit-faq">
                        <div class="content">
                            <div class="row">
                                <div class="col-xs-12 col-md-12 text-center">
                                    <h2>Preguntas Frecuentes</h2>
                                    <span>Los usuarios tienen preguntas, y tu tienes respuestas</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <ul class="faq-list">
                                        <?php
                                        if (isset($faq)) :
                                            foreach ($faq as $f) :
                                                ?>
                                                <li class="alert alert-info">
                                                    <h4><strong>P: <?= $f['question'] ?></strong></h4>
                                                    <p>R: <?= $f['answer'] ?></p>
                                                </li>     
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="clearfix">
                                        <a class="btn-display-add-box pull-right" href="#" data-target="#form-add-faq"><i class="fa fa-plus-circle"></i> Agregar</a>
                                    </div>
                                    <br>
                                    <form action="" method="post" id="form-add-faq" class="form-horizontal well fadeInDown animated no-display">
                                        <h2>Agregar pregunta frecuente</h2>
                                        <div id="add-faq-notification"></div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Pregunta:</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="add-faq-question" placeholder="Pregunta" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Respuesta:</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="add-faq-answer" placeholder="Respuesta"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix">
                                            <button type="button" class="pull-right btn btn-primary" id="button-add-faq">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- ./content -->
                    </div><!-- ./tabpanel:faq -->
                </div><!-- ./tab-content -->
            </div>
        </div>
    </div><!-- ./container -->
</section>

<div class="modal fade" id="modal-role" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar rol</h4>
            </div>
            <div class="modal-body">
                <div id="modal-role-notification"></div>
                <div class="form-horizontal">
                    <input type="hidden" id="rid"/>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Rol</label>
                        <div class="col-md-10">
                            <input type="text" id="r" class="form-control" placeholder="Nombre del rol. Ej: DiseÃƒÆ’Ã‚Â±ador, Programador, Contador, etc."/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Cupos</label>
                        <div class="col-md-10">
                            <input type="number" id="a" class="form-control" placeholder="1"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Descripci&oacute;n del rol:</label>
                        <div class="col-md-10">
                            <textarea class="form-control" id="d" placeholder="&iquest;A qu&eacute; se dedicar&aacute; esta persona dentro del proyecto?"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="clearfix">
                    <a href="#" class="btn delete pull-left">Eliminar</a>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="save btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-costs" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar recurso</h4>
            </div>
            <div class="modal-body">
                <div id="edit-vacant-notification"></div>
                <div class="form-horizontal">
                    <input type="hidden" id="rid"/>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nombre:</label>
                        <div class="col-md-9">
                            <input type="text" id="modal-costs-name" class="form-control" placeholder="Nombre del recurso"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Costo:</label>
                        <div class="col-md-9">
                            <input type="number" id="modal-costs-cost" class="form-control" placeholder="1"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Cantidad:</label>
                        <div class="col-md-9">
                            <input type="number" id="modal-costs-amount" class="form-control" placeholder="1"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Justificaci&oacute;n:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="modal-costs-detail" placeholder="&iquest;En qu&eacute; se utilizar&aacute; este recurso?"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox col-md-9 col-md-offset-3">
                            <label class="control-label">
                                <input type="checkbox" id="modal-costs-required" /> Requerido
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="clearfix">
                    <a href="#" class="btn delete pull-left">Eliminar</a>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="save btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-rewards" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar recompensa</h4>
            </div>
            <div class="modal-body">
                <div id="edit-vacant-notification"></div>
                <div class="form-horizontal">
                    <input type="hidden" id="rwdid"/>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Descripci&oacute;n:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="modal-rewards-description" name="modal-rewards-description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Monto m&iacute;nimo:</label>
                        <div class="col-md-9">
                            <input type="number" class="form-control" id="modal-rewards-min" name="modal-rewards-min" placeholder="$0"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Env&iacute;o:</label>
                        <div class="col-md-9">
                            <select class="form-control" id="modal-rewards-delivery" name="modal-rewards-delivery">
                                <option value="<?= REWARD_DELIVERY_NONE ?>">No requiere env&iacute;o</option>
                                <option value="<?= REWARD_DELIVERY_RETIRE_ADDRESS ?>">Retiro en direcci&oacute;n espec&iacute;fica</option>
                                <option value="<?= REWARD_DELIVERY_SENT_BY_MAIL ?>">Despacho a domicilio</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Fecha env&iacute;o:</label>
                        <div class="col-md-9">
                            <input type="month" name="modal-rewards-date" class="form-control" value="2015-12"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Observaciones:</label>
                        <div class="col-md-9">
                            <textarea id="modal-rewards-notes" name="modal-rewards-notes" class="form-control"></textarea>
                            <div class="help-block"><span id="modal-rewards-notes-chars">0</span>/200 caracteres</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="clearfix">
                    <a href="#" class="btn delete pull-left">Eliminar</a>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="save btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>js/groupfinder/gui/project-edit.js"></script>
<script>
    var pID = <?php echo $project->project_id; ?>;

    $(document).ready(function () {

        tinymce.init({
            selector: "#extra-information",
            browser_spellcheck: true,
            height: '500px',
            menu: {
                edit: {title: 'Editar', items: 'undo redo | cut copy paste pastetext | selectall'},
                insert: {title: 'Insertar', items: 'link media | template hr'},
                format: {title: 'Formato', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
                table: {title: 'Tabla', items: 'inserttable tableprops deletetable | cell row column'},
                tools: {title: 'Herramientas', items: 'spellchecker'}
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media"
        });

        $("time.timeago").each(function () {
            $(this).html($.timeago($(this).attr("datetime")));
        });
    });
</script>
