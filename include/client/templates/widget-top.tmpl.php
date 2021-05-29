<?php

/**
 * @file
 */

$BUTTONS = isset($BUTTONS) ? $BUTTONS : TRUE;
?>
<div class="widget-top">

        <?php
        if ($cfg->isKnowledgebaseEnabled()
            && ($faqs = FAQ::getFeatured()->select_related('category')->limit(5))
            && $faqs->all()) { ?>
            <section class="featured-qns">
                <h4 class="popular-header"><?php echo __('Popular Questions'); ?></h4>
                <p class="featured-tag-line">Browse through our helpful Articles, Tutorials, and Resources</p>
                <div class="jumbotron">
                    <div class="row">
                        <?php foreach ($faqs as $F) { ?>
                        <div class="col-12 col-sm-6 qns">

                            <a class="" href="<?php echo ROOT_PATH; ?>kb/faq.php?id=<?php echo urlencode($F->getId()); ?>">
                                <i class="fas fa-file-alt"></i> <?php echo $F->getLocalQuestion(); ?>
                            </a>

                        </div>
                        <?php } ?>
                     </div>
                </div>

            </section>
            <?php
        }
        ?></div>
</div>
