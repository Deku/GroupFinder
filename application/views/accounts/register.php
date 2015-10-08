<section>
    <div class="section-padding">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h2>Regístrate en Group Finder</h2>
            </div>
        </div>
        
        <div class="row">
            <?php
                if (isset($errorMsg)) { echo $errorMsg; }
                echo validation_errors('<div class="alert alert-error">', '</div>');
                echo form_open('auth/do-register');
            ?>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="username" id="username" maxlength="20" class="form-control" value="<?php echo set_value('username'); ?>" placeholder="Nombre de usuario" required />
                        <?php
                            echo form_error('username', '<span class="danger">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="password" id="password" maxlength="10" class="form-control" placeholder="Contraseña (8 a 10 caracteres)" required />
                        <?php
                            echo form_error('password', '<span class="danger">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="v-password" id="v-password" maxlength="10" class="form-control" placeholder="Verificación de contraseña" required />
                        <?php
                            echo form_error('v-password', '<span class="danger">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name'); ?>" placeholder="Nombre completo" required />
                        <?php
                            echo form_error('name', '<span class="danger">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="Correo electrónico" required />
                        <?php
                            echo form_error('email', '<span class="danger">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="title" id="title" class="form-control" maxlength="100" value="<?php echo set_value('title'); ?>" placeholder="Título profesional, dejar en blanco si no posee" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                            echo form_dropdown(array('name' => 'country', 'id' => 'country', 'class' => 'form-control', 'title' => 'País', 'required'), isset($countries) ? $countries : 'País');
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="date" name="birthday" id="birthday" type="date" class="form-control" value="<?php echo set_value('birthday'); ?>" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <?php
                        echo form_submit(array('class' => 'btn btn-info btn-wide center-block', 'value' => 'Regístrate'));
                    ?>
                </div>
            </form>
        </div>
        
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <br/>
                <p>Al registrarte, aceptas las Condiciones de Servicio y la Política de Privacidad, incluyendo el Uso de Cookies.</p>
            </div>
        </div>
    </div>
</section>