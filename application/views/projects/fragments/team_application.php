<li id="{application_id}" class="project-application">
    <div class="user-picture">
        <a href="{profile_url}">
            <img src="{img_src}" class="img-circle" alt="{name}" />
        </a>
    </div>
    <div class="clearfix user-info">
        <div class="pull-left">
            <ul>
                <li><a class="user-name" href="{profile_url}">{name}</a><time class="timeago" datetime="{application_time}"></time></li>
                <li><span class="user-title">{title}</span></li>
            </ul>
            <div id="app_info_{application_id}">
                <span>Ha postulado a un puesto como <strong>{role_name}</strong>: </span>
                <p class="user-message"><em>{message}</em></p>
            </div>
            <div id="app_notif_{application_id}"></div>
        </div>
    </div>
    <div class="response-buttons">
        <button class="btn btn-primary accept_application" type="submit" data-rq="{application_id}" data-u="app_notif_{application_id}">Confirmar</button>
        <button class="btn btn-default reject_application" type="submit" data-rq="{application_id}" data-u="app_notif_{application_id}">Descartar</button>
    </div>
</li>