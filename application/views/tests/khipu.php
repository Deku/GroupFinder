<section>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <?= anchor('tests/khipuCheckCobrador', 'Consultar estado de un cobrador'); ?><br>
                <?= anchor('tests/khipuListaBancos', 'Obtener listado de bancos'); ?><br>
                <?= anchor('tests/khipuBotonPago', 'Creando un cobro'); ?><br>
            </div>
            <div class="col-xs-12 col-md-8">
                <?php 
                    if (isset($result) && !empty($result)) {
                        echo $result;
                    }
                ?>
            </div>
        </div>
    </div>
</section>