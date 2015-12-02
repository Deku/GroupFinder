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
            <div id="feedback" class="row"></div>
            
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
<script type="text/babel" src="<?= base_url(); ?>js/groupfinder/ui/comments.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/groupfinder/gui/profile-view.js"></script>