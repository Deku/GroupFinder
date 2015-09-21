<li id="requests_{relation_id}">
    <div class="user-picture">
        <a href="{profile_url}">
            <img src="{img_src}" class="img-circle" alt="{name}" />
        </a>
    </div>
    <div class="clearfix user-info">
        <div class="pull-left">
            <ul>
                <li><a class="user-name" href="{profile_url}">{name}</a></li>
                <li><span class="user-title">{title}</span></li>
                <li><span class="user-title"><i class="fa fa-home"></i> {country_name}</span></li>
            </ul>
            <div id="req_info_{application_id}">
                <span>Desea ser tu amigo.</span>
            </div>
            <div id="req_notif_{relation_id}"></div>
        </div>
    </div>
    <div class="response-buttons">
        <button class="btn btn-primary accept_request" type="submit" data-rq="{relation_id}" data-u="req_notif_{relation_id}">Aceptar</button>
        <button class="btn btn-default reject_request" type="submit" data-rq="{relation_id}" data-u="req_notif_{relation_id}">Rechazar</button>
    </div>
</li>
