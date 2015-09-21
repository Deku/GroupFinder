<section id="donate">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php if ($reward_available) : ?>
                    <h2>Informaci&oacute;n de la donaci&oacute;n</h2>
                    <p class="padding-top-sm text-justify">Tu donaci&oacute;n ser&aacute; almacenada en una cuenta de Group Finder mientras el proyecto siga en la etapa de recaudaci&oacute;n.
                    En caso de que el proyecto no logre alcanzar la meta antes de la fecha l&iacute;mite, tu aporte ser&aacute; reembolsado utilizando la 
                    informaci&oacute;n entregada en tu perfil.</p>
                    <p class="padding-bottom">
                        <strong>
                            Es muy importante que configures tu cuenta bancaria personal para reembolsos.
                        </strong>
                         <?= anchor('users/settings', 'Configurar'); ?>
                    </p>

                    <?= form_open('payments/checkout', array('class' => 'padding-bottom')); ?>
                        <h2>Tus datos</h2>
                        <input type="hidden" name="project-id" value="<?= $project_id ?>"/>
                        <input type="hidden" name="pledge-amount" value="<?= $pledge_amount ?>" />
                        <input type="hidden" name="reward-id" value="<?= isset($reward) ? $reward->reward_id : 0 ?>" />

                        <div class="form-group">
                            <label for="bank-acc-name" class="control-label">Nombre y apellido:</label>
                            <input type="text" name="backer-name" class="form-control" value="<?= $this->session->user_realname ?>" />
                            <span class="help-block">requerido</span>
                        </div>

                        <div class="form-group">
                            <label for="bank-acc-email" class="control-label">Correo electr&oacute;nico:</label>
                            <input type="text" name="backer-email" class="form-control" value="<?= $this->session->user_email ?>" />
                            <span class="help-block">requerido</span>
                        </div>
                        
                        <h2 class="padding-top-sm">M&eacute;todo de pago</h2>
                        
                        <div class="padding-top-sm">
                            <label class="radio-inline">
                                <input type="radio" name="payment-method" value="khipu" required>
                                <img src="<?= base_url(); ?>/images/khipu_logo.png" class="img-responsive">
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="payment-method" value="paypal" required>
                                <img src="<?= base_url(); ?>/images/paypal_logo.png" class="img-responsive">
                            </label>
                        </div>

                        <div class="clearfix">
                            <div class="pull-right">
                                <button class="btn btn-primary">Proceder al pago</button>
                            </div>
                        </div>
                    </form>
                    
                    <h2></h2>
                <?php else : ?>
                    <div class="text-center">
                        <h2>Lo sentimos :(</h2>
                        <p>La recompensa que elegiste se encuentra agotada.</p>
                    </div>
                <?php endif; ?>    
            </div>
            <div class="col-md-4">
                <div class="white-box">
                    <h3>Tu aporte</h3>
                    <span>CLP $<?= $pledge_amount ?></span>
                </div>

                <div class="white-box">
                    <h3>Recompensa seleccionada</h3>
                    <?php if (!isset($reward)) : ?>
                        <p><strong>Sin recompensa</strong></p>
                    <?php else : ?>
                        <p class="text-success"><strong><i class="fa fa-trophy"></i> Aporta <?= $reward->min_amount ?> o m&aacute;s</strong></p>
                        <p><?= $reward->description ?></p>
                        <div class="row">
                            <div class="col-md-12">
                                <span><strong>Stock: </strong></span>
                                <span><?= $reward->limit == 0 ? 'Ilimitado' : $reward->limit ?></span>
                            </div>
                            <div class="col-sm-12">
                                <span><strong>Tipo de env&iacute;o: </strong></span>
                                <span><?= $reward->delivery_type_text ?></span>
                            </div>
                            <div class="col-sm-12">
                                <span><strong>Entrega aproximada: </strong></span>
                                <span><?= isset($reward->delivery_date) ? strftime('%B/%Y', strtotime($reward->delivery_date)) : '' ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
               
            </div>
        </div>
    </div>
</section>
