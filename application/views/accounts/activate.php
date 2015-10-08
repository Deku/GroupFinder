<section>
    <div class="section-padding">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <p class="bg-info">
                    <?php 
                    if ($this->session->pending)
                    {
                        if ($this->session->emailSent)
                        {
                           ?>
                           Se ha enviado un código de activación al correo <strong><?php echo $this->session->user_email; ?></strong>. El correo
                        debería ser recibido de inmediato, de no ser así, revisa tu carpeta de correo
                        no deseado, <?php echo anchor('auth/resend', 'reenvía tu código'); ?> 
                        o prueba solicitando un <?php echo anchor('auth/newcode', 'nuevo código de activación'); ?>.
                           <?php
                        }
                        else
                        {
                            ?>
                            Hemos tenido un problema enviando el código de activación a <?php echo $this->session->user_email; ?>.
                          <?php  
                        }
                    }
                    else
                    {
                        ?>
                            Esta cuenta ya se ha activado.
                        <?php
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php
        if ($this->session->pending)
        { ?>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <?php 
                if (isset($errorMsg)) { echo $errorMsg; }
                echo validation_errors();
                echo form_open('auth/do-activate'); ?>
                    <div class="col-xs-12 col-md-8">
                        <div class="form-group">
                            <input type="text" id="activationCode" name="activationCode" class="form-control" placeholder="XXXXXXXXXXXX" required />
                            <label>Ingrese el código de activación recibido</label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <input type="submit" class="btn btn-primary" value="Enviar" />
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
</section>