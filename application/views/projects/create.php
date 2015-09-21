<section id="project-create" style="min-height: 600px;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="fs-form-wrap" id="fs-form-wrap">
                    <div class="fs-title">
                        <h1>Publicar un proyecto: Primeros pasos</h1>
                    </div>
                    <?php
                    if (isset($error)) {
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    echo validation_errors('<div class="alert alert-error">', '</div>');
                    $config = array(
                        'id' => 'project-create-form',
                        'name' => 'project-create-form',
                        'class' => 'fs-form fs-form-full',
                        'autocomplete' => 'off'
                    );
                    echo form_open('projects/processCreate', $config);
                    ?>
                    <ol class="fs-fields">
                        <li class="overview-hide">
                            <label class="fs-field-label fs-anim-upper" data-info="Los consejos serán desplegados de esta forma">Antes de continuar...</label>
                            <p class="fs-anim-lower">A continuación prepararemos los aspectos básicos de tu proyecto mediante simples preguntas. Si tienes alguna duda, consulta el ícono <i class="fa fa-info-circle"></i></p>
                        </li>
                        <li>
                            <label class="fs-field-label fs-anim-upper" for="q-title" data-info="Trata de que el nombre de tu proyecto sea representativo, es decir, que te de la idea general de qué se trata">¿Qu&eacute; nombre recibir&aacute; tu proyecto?</label>
                            <input class="fs-anim-lower" id="q-title" name="q-title" type="text" placeholder="Nombre súper cool" required />
                        </li>
                        <li>
                            <label class="fs-field-label fs-anim-upper" for="q-category" data-info="Seleccionar la categor&iacute;a correcta facilitar&aacute; que las personas encuentren tu proyecto">¿A qu&eacute; categoría corresponde?</label>
                            <div class="fs-radio-group fs-radio-custom clearfix fs-anim-lower">
                                <?php foreach ($categories as $category) { ?>
                                    <span>
                                        <input id="q-category-<?php echo $category->category_id; ?>" name="q-category" type="radio" value="<?php echo $category->category_id; ?>"/>
                                        <label for="q-category-<?php echo $category->category_id; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $category->description; ?>"><?php echo $category->name; ?></label>
                                    </span>
                                <?php } ?>
                            </div>
                        </li>
                        <li>
                            <label class="fs-field-label fs-anim-upper" for="q-problem" data-info="Al explicar el problema indica cuál es su importancia">¿Qué problema detectaste?</label>
                            <div class="fs-anim-lower">
                                <textarea id="q-problem" name="q-problem" required></textarea>
                            </div>
                        </li>
                        <li>
                            <label class="fs-field-label fs-anim-upper" for="q-solution">¿Cuál es la solución que propones?</label>
                            <div class="fs-anim-lower">
                                <textarea id="q-solution" name="q-solution" required></textarea>
                            </div>
                        </li>
                        <li>
                            <label class="fs-field-label fs-anim-upper" for="q-target" data-info="¿Quienes serán los usuarios del producto/servicio que deseas crear?">¿A quién está dirigido tu proyecto?</label>
                            <textarea class="fs-anim-lower" id="q-target" name="q-target" required></textarea>
                        </li>
                        <li>
                            <label class="fs-field-label fs-anim-upper" for="q-due-date" data-info="Mientras más tiempo pase, mayor es la posibilidad de que alguien que hayas reclutado pierda el interés en tu proyecto">¿Cuál será la fecha límite para buscar personas y financiamiento?</label>
                            <input type="date" class="fs-anim-lower" id="q-due-date" name="q-due-date"/>
                        </li>
                    </ol>
                    <button class="fs-submit" type="submit">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        var formWrap = document.getElementById('fs-form-wrap');

        new FForm(formWrap, {
            onReview: function () {
                $(document.body).addClass('overview'); // for demo purposes only
            }
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
</script>
<script src="<?php echo base_url(); ?>js/groupfinder/gui/project-create.js"></script>