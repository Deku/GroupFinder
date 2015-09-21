<section id="profile">
    <div class="section-padding">
        <?php 
        if (!$error)
        { ?>
            <div id="info" class="row">
                <!-- Foto de perfil -->
                <div class="col-xs-12 col-md-4">
                    <div class="profile-photo" style="background-image: url(<?php echo $profile->img_src; ?>)"></div>
                    <ul class="profile-info">
                        <li><h2><?php echo $profile->name; ?></h2></li>
                        <li><span><i class="fa fa-location-arrow"></i> Miembro de <?php echo $profile->country_name; ?></span></li>
                        <li><span><i class="fa fa-clock-o"></i> Se unió el <?php echo date('d M Y', strtotime($profile->created)); ?></span></li>
                       
                        <?php if ($this->session->user_id && $this->session->user_id == $this->uri->segment(3)) { ?>
                            <li><?php echo anchor('users/settings', '<i class="fa fa-pencil"></i> Editar mi perfil', array('class' => 'btn btn-default btn-wide')); ?></li>
                        <?php } else { ?>
                            <li><button type="button" class="btn btn-primary" id="send_friend_request" data-ref="<?= $this->uri->segment(3); ?>"><i class="fa fa-plus"></i> Enviar solicitud de amistad</button></li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- Info -->
                <div class="col-xs-12 col-md-8">
                    <div class="info-section">
                        <h3>Acerca de mí</h3>
                        <p><?php echo !empty($profile->about) ? $profile->about : '<p class="text-muted">Este usuario no ha escrito una descripción.</p>' ?></p>
                    </div>
                    <div class="info-section">
                        <h3>Título profesional</h3>
                        <p><?php echo !empty($profile->title) ? $profile->title : '<p class="text-muted">No registrado</p>' ?></p>
                    </div>
                </div>
            </div>
            <div id="feedback" class="row">
                <h2 id="comments-title"></h2>
                <hr>
                <div id="comments"></div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        load_comments(<?php echo '0, ' . $this->uri->segment(3) . ', ' . ($this->session->loggedIn ? 'true' : 'false'); ?>);
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
                        'data-t' => 0,
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
                                    <input type="hidden" name="origin" id="origin" value="0" />
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
            
        <?php } else { ?>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <h2>Usuario no encontrado</h2>
                    <?php echo anchor('portal/home', 'Volver al inicio'); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>js/groupfinder/app/comments.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/groupfinder/gui/profile-view.js"></script>