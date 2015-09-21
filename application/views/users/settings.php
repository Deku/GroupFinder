<section id="edit-profile">
    <div class="section-padding">
        <div class="row">
            <div class="col-md-3 col-sm-5" role="tabpanel">
                <div class="sidebar">
                    <div class="sidebar-item categories">
                        <h3>Configuraci&oacute;n</h3>
                        <!-- Nav tabs -->
                        <ul class="nav navbar-stacked" role="tablist">
                          <li role="presentation" class="active">
                              <a href="#personal-data" aria-controls="personal-data" role="tab" data-toggle="tab">Perfil</a>
                          </li>
                          <li role="presentation">
                              <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Foto de perfil</a>
                          </li>
                          <li role="presentation">
                              <a href="#security" aria-controls="security" role="tab" data-toggle="tab">Seguridad</a>
                          </li>
                          <li role="presentation">
                              <a href="#bank" aria-controls="bank" role="tab" data-toggle="tab">Cuenta bancaria</a>
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-xs-12 col-md-8">
                 <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Personal data -->
                    <div role="tabpanel" class="tab-pane active fade in" id="personal-data">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Datos del perfil</h3>
                            </div>
                            <div class="panel-body">
                                <?php echo form_open('users/editProfile', array('id' => 'edit-profile-form')); ?>
                                    <div id="edit-status"></div>
                                    <div class="form-group">
                                        <label for="editName">Nombre:</label>
                                        <input type="text" id="editName" name="editName" class="form-control" value="<?php echo $this->session->user_realname; ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="editTitle">T&iacute;tulo profesional:</label>
                                        <input type="text" id="editTitle" name="editTitle" class="form-control" value="<?php echo $this->session->user_title; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="editBirthday">Fecha de nacimiento:</label>
                                        <input type="date" id="editBirthday" name="editBirthday" class="form-control" value="<?php echo $this->session->user_birthday; ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="editCountry">Pa&iacute;s:</label>
                                        <?php
                                            $opts = array(
                                                'name' => 'editCountry',
                                                'id' => 'editCountry',
                                                'class' => 'form-control',
                                                'title' => 'Pa&iacute;s',
                                                'required' => 'true'
                                            );
                                            echo form_dropdown($opts, $countries, $this->session->user_country_id);
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="editAbout">Acerca de m&iacute;:</label>
                                        <textarea id="editAbout" name="editAbout" class="form-control" rows="3" ><?php echo $this->session->user_about; ?></textarea>
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Guardar" />
                                </form>
                            </div>
                        </div>
                    </div><!-- /Personal-data -->
                    
                    <!-- Profile photo -->
                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Foto de perfil</h3>
                            </div>
                            <div class="panel-body row">
                                <div class="col-xs-12 col-md-4">
                                    <h3>Vista previa</h3>
                                    <img id="image-preview" class="picture-preview" src="<?php echo base_url(); ?>images/preview.jpg" />
                                </div>
                                <div class="col-xs-12 col-md-8">
                                    <h3>Actualiza tu imagen</h3>
                                    <div role="tabpanel" id="choices">
                                        <ul class="nav nav-pills nav-justified" role="tablist">
                                            <li role="presentation" class="active"><a href="#upload-photo" aria-controls="upload-photo" role="tab" data-toggle="tab" data-url="<?php echo base_url(); ?>images/preview.jpg"><i class="fa fa-upload"></i> Desde tu equipo</a></li>
                                            <li role="presentation"><a href="#gravatar" aria-controls="gravatar" role="tab" data-toggle="tab"  data-url="<?php echo 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->session->user_email))) . '?d=mm&s=297'; ?>"><i class="fa fa-external-link"></i> Desde Gravatar</a></li>
                                        </ul>
                                    </div>
                                    <div class="tab-content" class="padding-top-sm">
                                        <div role="tabpanel" class="tab-pane active fade in padding-top-sm" id="upload-photo">
                                            <div id="upload-status"></div>
                                            <?php echo form_open_multipart('pictures/upload', array('id' => 'profile-upload-form', 'name' => 'profile-upload-form')); ?>
                                                <button id="btn-upload-from-desktop" href="#" class="btn btn-default">Subir imagen</button>
                                                <input type="submit" id="upload" class="btn btn-primary" value="Guardar"/>
                                                <input type="file" style="visibility: hidden;" id="image-file" name="image-file" accept="image/*" required/>
                                                <input type="hidden" name="source" value="<?php echo sha1('profile-edit'); ?>" />
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane active fade padding-top-sm" id="gravatar">
                                            <div id="gravatar-status"></div>
                                            <?php echo anchor('pictures/useGravatar', 'Usar Gravatar', array('id' => 'profile-use-gravatar', 'class' => 'btn btn-default')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /Profile-photo -->
                    
                    <!-- Security -->
                    <div role="tabpanel" class="tab-pane fade" id="security">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">&Uacute;ltimo ingreso</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="last-login">&Uacute;ltima vez que ingresaste:</label>
                                    <input type="text" id="last-login" class="form-control" disabled="true" value="<?php echo $this->session->user_last_login_time; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="last-ip">Direcci&oacute;n desde la cual ingresaste:</label>
                                    <input type="text" id="last-ip" class="form-control" disabled="true" value="<?php echo $this->session->user_last_login_ip; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Cambiar contrase&ntilde;a</h3>
                            </div>
                            <div class="panel-body">
                                <?php echo form_open('auth/changePassword', array('id' => 'change-pass-form')); ?>
                                    <div id="changepass-status"></div>
                                    <div class="form-group">
                                        <label for="old-password">Contrase&ntilde;a actual:</label>
                                        <input type="password" id="old-password" name="old-password" class="form-control" required="true"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="new-password">Contrase&ntilde;a nueva:</label>
                                        <input type="password" id="new-password" name="new-password" class="form-control" required="true"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="v-new-password">Repita la nueva contrase&ntilde;a:</label>
                                        <input type="password" id="v-new-password" name="v-new-password" class="form-control" required="true"/>
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Cambiar contrase&ntilde;a" />
                                </form>
                            </div><!-- /panel-body -->
                        </div><!-- /panel -->
                    </div><!-- /Security -->
                    
                    <!-- Bank -->
                    <div role="tabpanel" class="tab-pane fade" id="bank">
                        <div class="panel panel-info">
                            <div class="panel-heading" role="tab" id="panel-bank-account-heading">
                                <h4 class="panel-title">Cuenta bancaria</h4>
                            </div>

                            <div class="panel-body">
                                <?= form_open('', array('id' => 'form-bank-data')); ?>
                                    <div class="form-group">
                                        <label for="bank-id" class="control-label">Banco:</label>
                                        <?= form_dropdown('bank-id', $banks, $user_bank->bank_id, array('id' => 'bank-id', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="bank-acc-type" class="control-label">Tipo de cuenta:</label>
                                        <?= form_dropdown('bank-acc-type', $bank_acc_types, $user_bank->bank_acc_type, array('id' => 'bank-acc-type', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="bank-acc-number" class="control-label">N&uacute;mero de cuenta:</label>
                                        <input type="text" id="bank-acc-number" name="bank-acc-number" class="form-control" value="<?= $user_bank->bank_acc_number ?>" />
                                        <span class="help-block">Ingrese sin guiones ni puntos</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="bank-acc-name" class="control-label">Nombre y apellido:</label>
                                        <input type="text" id="bank-acc-name" name="bank-acc-name" class="form-control" value="<?= $this->session->user_realname ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="bank-acc-rut" class="control-label">RUT:</label>
                                        <input type="text" id="bank-acc-rut" name="bank-acc-rut" class="form-control" value="<?= $user_bank->bank_acc_rut ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label for="bank-acc-email" class="control-label">Correo electr&oacute;nico:</label>
                                        <input type="text" id="bank-acc-email" name="bank-acc-email" class="form-control" value="<?= $this->session->user_email ?>" />
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
                    </div><!-- /Security -->
                </div><!-- /tab-content -->
            </div><!-- /col-md-8 -->
        </div><!-- /row -->
    </div><!-- /box -->
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>js/groupfinder/gui/edit-profile.js"></script>