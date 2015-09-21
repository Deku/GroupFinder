<section>
    <div class="section-padding">
        <div class="content">
            
            <div class="row">
                <div class="col-md-4 hidden-xs hidden-sm">
                    <h2>Otras conversaciones</h2>
                    <ul class="side-conversations scrollbar-light">
                        <?php
                            if (isset($conversations)) {
                                foreach ($conversations as $conversation) {
                                    echo $conversation;
                                }
                            } else {
                                echo '<li>No tienes conversaciones activas</li>';
                            }
                        ?>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div class="content">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <h2 class="pull-left">Mensajes</h2>
                                <button type="button" class="btn btn-default pull-right">Archivar conversación</button>
                                
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <ul class="conversation-messages scrollbar-light">
                                    <?php
                                        if (isset($messages)) {
                                            foreach ($messages as $message) {
                                                echo $message;
                                            }
                                        } else {
                                            echo '<li>No se han enviado mensajes en esta conversación</li>';
                                        }
                                    ?>
                                </ul>
                                <div class="chat-text-area">
                                    <div class="textarea">
                                        <textarea id="chat-input" placeholder="Escribe un mensaje..."></textarea>
                                    </div>
                                    <div class="controls clearfix">
                                        <span class="pull-right"><i id="send_status" class="fa"></i></span>
                                        <button id="btn_send_msg" type="button" class="btn btn-primary pull-right" data-ref="conversation-<?= $conversation_id ?>">Enviar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $("time.timeago").each(function() {
            $(this).html($.timeago($(this).attr("datetime")));
        });
        
        var conn = new ab.Session('ws://localhost:8080',
            function() {
                conn.subscribe('kittensCategory', function(topic, data) {
                    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                    console.log('New article published to category "' + topic + '" : ' + data.title);
                });
            },
            function() {
                console.warn('WebSocket connection closed');
            },
            {'skipSubprotocolCheck': true}
        );
    });
    
</script>