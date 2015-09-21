<section>
    <div class="section-padding">
        <div class="container">
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="title-center">
                        <h3 class="page-header">Solicitudes pendientes</h3>
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