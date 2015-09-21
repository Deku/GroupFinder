<article class="media comment_section">
    <div class="pull-left post_comments hidden-xs hidden-sm">
        <a href="#">
            <img src="{img_src}" class="img-circle" alt="{name}" />
        </a>
    </div>
    <div class="media-body post_reply_comments">
        <div class="col-xs-12">
            <h3><a href="../../users/u/{user_id}">{name}</a> dijo:</h3>
            <p>{text}</p>
            <span class="text-muted"><i class="fa fa-clock-o"></i> <time class="timeago" datetime="{post_time}"></time></span>
        </div>
        <div class="col-xs-12"><a class="reply pull-right" href="#" onclick="reply(this)" data-ref="{name}">Responder</a></div>
    </div>
</article>