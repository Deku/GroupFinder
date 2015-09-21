<div class="user-list-item" id="members_{member_id}">
    <div class="user-card">
        <div class="user-picture">
            <a href="{profile_url}">
                <img src="{img_src}" class="img-responsive" alt="{name}" />
            </a>
        </div>
        <div class="clearfix user-info">
            <div class="user-name">
                <a class="user-name" href="{profile_url}">{name} {leader_icon}</a>
            </div>
            <ul>
                <li><span class="user-title">{title}</span></li>
                <li><span class="user-role">Rol: {role_name}</span></li>
                {remove_button}
            </ul>
        </div>
        <div id="member_u_{member_id}">
        </div>
    </div>
</div>