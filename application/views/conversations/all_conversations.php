<section>
    <div class="section-padding">
        <div class="container">
            <div class="box">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="title-center">
                            <h2>Conversaciones</h2>
                            <?php echo anchor('users/pending', 'Conversaciones archivadas'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-8 col-md-offset-2">
                        <ul class="requests-holder">
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
    });
</script>