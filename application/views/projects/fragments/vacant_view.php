<div class="vacant-item">
    <section>
        <h4>{role_name}</h4>
        <span>
            {vacants_used} / {vacants_amount}
            
        </span>
    </section>

    <section>
        <h5>Descripci&oacute;n:</h5>
        <p>{role_description}</p>
        <br>
            Postulantes: {postulantes}
    </section>
    
    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#modal-application" data-r="{role_name}" data-rid="{role_id}">Postular</button>
</div>