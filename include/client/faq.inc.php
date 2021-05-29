<?php

/**
 * @file
 */

if (!defined('OSTCLIENTINC') || !$faq || !$faq->isPublished()) {
  die('Access Denied');
}

$category = $faq->getCategory();

?>
<div class="row">
    <div class="col-12 col-sm-9">

        <h1><?php echo __('Frequently Asked Question'); ?></h1>
        <div id="breadcrumbs" style="padding-top:2px;">
            <a href="index.php"><?php echo __('All Categories'); ?></a>
            &raquo; <a href="faq.php?cid=<?php echo $category->getId(); ?>"><?php
            echo $category->getFullName(); ?></a>
        </div>

        <div class="faq-content">
            <div class="article-title flush-left">
                <?php echo $faq->getLocalQuestion() ?>
            </div>
            <div class="faded"><?php echo sprintf(__('Last Updated %s'),
                    Format::relativeTime(Misc::db2gmtime($faq->getUpdateDate()))); ?></div>
            <br/>
            <div class="thread-body bleed">
                <?php echo $faq->getLocalAnswerWithImages(); ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-3">
        <div class="sidebar">

            <div class="content"><?php
            if ($attachments = $faq->getLocalAttachments()->all()) { ?>
                    <section>
                        <strong><?php echo __('Attachments'); ?>:</strong>
                        <?php foreach ($attachments as $att) { ?>
                            <div>
                                <a href="<?php echo $att->file->getDownloadUrl(['id' => $att->getId()]);
                                ?>" class="no-pjax">
                                    <i class="icon-file"></i>
                                    <?php echo Format::htmlchars($att->getFilename()); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </section>
            <?php }
            if ($faq->getHelpTopics()->count()) { ?>
                    <section>
                        <strong><?php echo __('Help Topics'); ?></strong>
                        <?php foreach ($faq->getHelpTopics() as $T) { ?>
                            <div><?php echo $T->topic->getFullName(); ?></div>
                        <?php } ?>
                    </section>
            <?php }
            ?></div>

            <div class="card">
                <div class="card-body ">
                    <div class="text-center">
                        <h6 class="featured-header">Still Have Questions? We can help!</h6>
                    </div>
                    <br>
                    <div class=" ">
                        <a href="/open.php" class="pull-right btn btn-primary">Open a New Ticket</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
