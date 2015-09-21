<section>
    <div class="container">
        <div class="row">
            <h1>Explora los proyectos que buscan tu ayuda</h1>
        </div>
        <div class="row">
            <ul id="categories">
                
                <?php if (isset($categories)) {
                            foreach ($categories as $category) { ?>
                    <li>
                        <article>
                            <a href="category/<?php echo $category->category_id; ?>">
                                <figure>
                                    <img src="<?php echo base_url(); ?>/images/categories/default.jpg"
                                </figure>
                                <h2><?php echo $category->name; ?></h2>
                                <span class="pull-right"><i class="fa fa-rocket"></i> <?php echo $category->count; ?></span>
                            </a>
                        </article>
                    </li>
                <?php       }
                    } ?>
                
            </ul>
        </div>
    </div>
</section>