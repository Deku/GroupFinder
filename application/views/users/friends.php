<?php if (isset($requests) && !empty($requests)) : ?>
<section>
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="title-center">
                        <h2>Solicitudes pendientes</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <ul class="requests-holder">
                        <?php if (isset($requests) && !empty($requests)) {

                            foreach ($requests as $request) {
                                echo $request;
                            }
                        } else {
                            echo '<li>No tienes solicitudes pendientes</li>';
                        }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url(); ?>js/groupfinder/gui/friend-requests.js"></script>
<?php endif; ?>

<section>
    <div class="section-padding">
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="title-center">
                            <h2 class="page-header">Amigos</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <ul id="friends-holder">
                        <?php if (isset($friends)) {
                            foreach ($friends as $friend) {
                                echo $friend;
                            }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
</section>

<div class="modal fade" id="modal-msg" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enviar mensaje</h4>
            </div>
            <div class="modal-body">
                <div id="message-notification"></div>
                <div class="form-horizontal">
                    <input type="hidden" id="ref"/>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Para: </label>
                        <div class="col-md-10">
                            <span id="user-name"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Mensaje:</label>
                        <div class="col-md-10">
                            <textarea class="form-control" id="m"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="send_message" class="btn btn-primary">Enviar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>js/groupfinder/gui/friends.js"></script>