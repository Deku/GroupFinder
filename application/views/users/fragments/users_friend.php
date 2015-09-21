<div id="friends_{relation_id}" class="user-list-item">
    <div class="user-card">
        <div class="user-picture">
            <a href="{profile_url}">
                <img src="{img_src}" class="img-responsive" alt="{name}" />
            </a>
        </div>
        <div class="clearfix user-info">
            <div class="user-name">
                <a class="user-name" href="{profile_url}">{name}</a>
            </div>
            <ul>
                <li><span class="user-title">{title}</span></li>
                <li><span><i class="fa fa-home"></i> {country_name}</span></li>
                <li><span>Amigos desde el {confirmation_time}</span></li>
                <li class="padding-top-xs">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-msg" data-name="{name}" data-ref="{user_id}"><i class="fa fa-envelope"></i> Enviar mensaje</button>
                    <button type="button" class="btn remove_friend" data-ref="{relation_id}" data-u="friend_u_{user_id}">Eliminar de mis amigos</a>
                </li>
            </ul>
        </div>
        <div id="friend_u_{user_id}">
        </div>
    </div>
</div>
