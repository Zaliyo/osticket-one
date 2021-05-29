<?php

/*********************************************************************
 * @file
 * index.php
 *
 * Helpdesk landing page. Please customize it to fit your needs.
 *
 * Peter Rotich <peter@osticket.com>
 * Copyright (c)  2006-2013 osTicket
 * http://www.osticket.com
 *
 * Released under the GNU General Public License WITHOUT ANY WARRANTY.
 * See LICENSE.TXT for details.
 *
 * vim: expandtab sw=4 ts=4 sts=4:
 **********************************************************************/

require 'client.inc.php';

require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require CLIENTINC_DIR . 'header.inc.php';
?>


<div id="container">

    <div class="search-area container">
        <h3 class="featured-header">Quick Help Desk Link</h3>
        <p class="featured-tag-line">Browse through our helpful Articles, Tutorials, and Resources:</p>

    </div>


    <div class="card-deck" style="padding-bottom:30px; ">
        <div class="card text-center">
            <div class="card-body">
                <div class="card-icon"><i class="fas fa-file-alt"></i></div>
                <h5 class="card-title">Knowledge Base</h5>
                <p class="card-text">No coding skills required to create unique help-center. Customize your site in
                    real-time.</p>
                <a href="<?php echo ROOT_PATH; ?>kb/index.php" class="card-link">Explore Knowledge Base <i
                            class="fa fa-arrow-right hvr-icon"></i></a>
            </div>
        </div>

        <div class="card text-center">
            <div class="card-body">
                <div class="card-icon"><i class="fab fa-youtube"></i></div>
                <h5 class="card-title">Video Manuals</h5>
                <p class="card-text">No coding skills required to create unique help-center. Customize your site in
                    real-time.</p>
                <a href="<?php echo ROOT_PATH; ?>kb/index.php" class="card-link">Explore Video Manuals <i
                            class="fa fa-arrow-right hvr-icon"></i></a>
            </div>
        </div>

        <div class="card text-center">
            <div class="card-body">
                <div class="card-icon"><i class="far fa-comments"></i></div>
                <h5 class="card-title">Community Forums</h5>
                <p class="card-text">No coding skills required to create unique help-center. Customize your site in
                    real-time.</p>
                <a href="<?php echo ROOT_PATH; ?>kb/index.php" class="card-link">Explore Community Forums <i
                            class="fa fa-arrow-right hvr-icon"></i></a>
            </div>
        </div>

        <div class="card text-center">
            <div class="card-body">
                <div class="card-icon"><i class="far fa-envelope"></i></div>
                <h5 class="card-title">Contact Us</h5>
                <p class="card-text">No coding skills required to create unique help-center. Customize your site in
                    real-time.</p>
                <a href="<?php echo ROOT_PATH; ?>kb/index.php" class="card-link">Send us a Mail <i class="fa fa-arrow-right hvr-icon"></i></a>
            </div>
        </div>

    </div>
    <?php require CLIENTINC_DIR . 'templates/widget-top.tmpl.php'; ?>

    <div class="clear"></div>
    <div class="row">
        <?php if ($BUTTONS) { ?>

            <?php
            if ($cfg->getClientRegistrationMode() != 'disabled'
                || !$cfg->isClientLoginRequired()
            ) { ?>

            <?php } ?>

        <?php } ?>

        <div class="col-sm-12">
            <div class="card new-ticket text-center">
                <div class="card-body row">
                    <div class="col-sm-10">
                        <h4 class="featured-header">Still Have Questions? We can help!</h4>
                        <p class="featured-tag-line">Contact us and we’ll get back to you as soon as possible.</p>
                    </div>
                    <div class="col-sm-2">
                        <a href="open.php" class="pull-right btn btn-primary" class="blue button"><?php
                        echo __('Open a New Ticket'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="featured-block">
        <?php
        if ($cfg && $cfg->isKnowledgebaseEnabled()) {
          // FIXME: provide ability to feature or select random FAQs ??
          ?>

            <?php
            $cats = Category::getFeatured();
            if ($cats->all()) { ?>
            <h3 class="featured-header"><?php echo __('Featured Knowledge Base Articles'); ?></h3>
            <p class="featured-tag-line">If you have any question you can ask below or enter what you are looking
                for!</p>
                <?php
            }
            ?>
        <br/><br/>
        <div class="card-deck">
            <?php
            foreach ($cats as $C) { ?>
                <div class="card">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="180"
                         xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap"
                         preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#6c757d"></rect>
                        <text x="50%" y="50%" fill="#dee2e6" dy=".3em">
                            <?php echo $C->getName(); ?>
                        </text>
                    </svg>
                    <div class="card-body">
                        <?php foreach ($C->getTopArticles() as $F) { ?>
                            <h6 class="card-title">
                                <a href="<?php echo ROOT_PATH; ?>kb/faq.php?id=<?php echo $F->getId(); ?>">
                                    <i class="fas fa-file-alt"></i> <?php echo $F->getQuestion(); ?>
                                </a>
                            </h6>
<!--                            <p class="card-text">--><?php
// Echo $F->getTeaser(); ?><!--</p>-->
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
    </div>
            <?php
        }
        ?>
</div>
</div>

<?php require CLIENTINC_DIR . 'footer.inc.php';
