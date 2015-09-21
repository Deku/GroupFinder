<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <div class="row">
                    <h2>Inicia sesión en Group Finder</h2>
                </div>
                <?php 
                    if (isset($errorMsg)) { echo '<div class="alert alert-danger">' . $errorMsg . '</div>'; }
                    echo validation_errors('<div class="alert alert-danger">', '</div>');
                    echo form_open('auth/processLogin'); ?>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control" value="<?php echo set_value('username'); ?>" placeholder="Usuario" required />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required />
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="keepLogged" id="keepLogged" />
                                    Mantenerme conectado
                                </label>
                            </div>
                        </div>
                        <div class="col-xs- col-md-6">
                            <input type="submit" class="btn btn-primary pull-right" value="Ingresar" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xs-12 col-md-6">
                <h3></h3>
            </div>
        </div>
    </div>
</section>