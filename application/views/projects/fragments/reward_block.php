<li class="reward reward-available" id="reward-{reward_id}">
    <div class="reward-info">
        <h2>
            Aportar ${min_amount} o más
        </h2>
        <p>
            <i class="fa fa-users text-info"></i>&nbsp;
            {backers_amount} patrocinadores
        </p>
        <div class="clearfix">
            <button type="button" class="btn btn-primary pull-right reward__button-select" data-reward-id="#reward-{reward_id}">Seleccionar</button>
        </div>
        <p>
            {description}
        </p>
        <div class="row">
            <div class="col-md-12">
                <span><strong>Stock:</strong></span>
                <span>{limit}</span>
            </div>
            <div class="col-sm-6">
                <span><strong>Tipo de env&iacute;o: </strong></span>
                <span>{delivery_type_text}</span>
            </div>
            <div class="col-sm-6">
                <span><strong>Entrega aproximada: </strong></span>
                <span><time data-format="MMM YYYY" datetime="{delivery_date}">{delivery_date}</time></span>
            </div>
        </div>
    </div>

    <form action="<?= site_url(); ?>/projects/donate" method="post">
        <div class="reward__checkout-form fadeInDown">
            <div class="form-group">
                <label>Monto de contribución</label>
                <input type="hidden" name="project-id" value="{project_id}"/>
                <input type="number" class="form-control" name="backing[amount]" min="{min_amount}" value="{min_amount}" required>
                <input name="backing[reward_id]" type="hidden" value="{reward_id}">
            </div>
            <button class="btn btn-success">
                <span class="btn-text">
                    Continuar
                </span>
            </button>
        </div>
    </form>
</li>