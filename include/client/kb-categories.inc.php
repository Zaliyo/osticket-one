<?php

/**
 * @file
 */
?>
<div class="row">

        <?php
        $categories = Category::objects()
          ->exclude(Q::any([
            'ispublic' => Category::VISIBILITY_PRIVATE,
            Q::all([
              'faqs__ispublished' => FAQ::VISIBILITY_PRIVATE,
              'children__ispublic' => Category::VISIBILITY_PRIVATE,
              'children__faqs__ispublished' => FAQ::VISIBILITY_PRIVATE,
            ]),
          ]))
            // ->annotate(array('faq_count'=>SqlAggregate::COUNT('faqs__ispublished')));
          ->annotate([
            'faq_count' => SqlAggregate::COUNT(
                SqlCase::N()
                  ->when([
                    'faqs__ispublished__gt' => FAQ::VISIBILITY_PRIVATE,
                  ], 1)
                  ->otherwise(NULL)
            ),
          ])
          ->annotate([
            'children_faq_count' => SqlAggregate::COUNT(
                SqlCase::N()
                  ->when([
                    'children__faqs__ispublished__gt' => FAQ::VISIBILITY_PRIVATE,
                  ], 1)
                  ->otherwise(NULL)
            ),
          ]);

        // ->filter(array('faq_count__gt' => 0));
        if ($categories->exists(TRUE)) { ?>
            <h1 class="text-center"><?php echo __('Click on the category to browse FAQs.'); ?></h1>

            <ul id="kb" class="col-sm-12">
                <?php
                foreach ($categories as $C) {
                  ?>

                        <?php
                        // Don't show subcategories with parents.
                        if (($p = $C->parent)
                        && ($categories->findFirst([
                          'category_id' => $p->getId(),
                        ]))) {
                          continue;
                        }

                        $count = $C->faq_count + $C->children_faq_count;
                        ?>
                    <div class="card mb-3">
                        <div class="row no-gutters">

                            <div class="col-md-12">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo sprintf('<a href="faq.php?cid=%d">%s %s</a>',
                                            $C->getId(), Format::htmlchars($C->getLocalName()),
                                            $count ? "({$count})" : ''
                                        ); ?>
                                    </h5>
                                    <div class="card-text">

                                        <?php
                                        if (($subs = $C->getPublicSubCategories())) {
                                          echo '<div>';
                                          foreach ($subs as $c) {
                                            echo sprintf('<div> 
                            <i class="fas fa-file-alt"></i> <a href="faq.php?cid=%d">%s (%d)</a></div>',
                                                  $c->getId(),
                                                  $c->getLocalName(),
                                                  $c->faq_count
                                              );
                                          }
                                          echo '</div>';
                                        }

                                        foreach ($C->faqs
                                          ->exclude(['ispublished' => FAQ::VISIBILITY_PRIVATE])
                                          ->limit(5) as $F) { ?>
                                            <div class="popular-faq">
                                                <i class="fas fa-file-alt"></i>  <a href="faq.php?id=<?php echo $F->getId(); ?>">
                                                    <?php echo $F->getLocalQuestion() ?: $F->getQuestion(); ?>
                                                </a></div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                <?php } ?>
            </ul>
            <?php
        }
        else {
          echo __('NO FAQs found');
        }
        ?>


</div>
