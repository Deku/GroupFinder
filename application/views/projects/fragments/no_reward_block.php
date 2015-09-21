<li class="reward reward-available" id="reward-{reward_id}">
    <div class="reward-info">
        <h2>
            No seleccionar una recompensa
        </h2>
        <div class="clearfix">
            <button type="button" class="btn btn-primary pull-right reward__button-select" data-reward-id="#reward-{reward_id}">Seleccionar</button>
        </div>
        <p>
            {description}
        </p>
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