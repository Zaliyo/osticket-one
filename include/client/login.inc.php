<?php

/**
 * @file
 */

if (!defined('OSTCLIENTINC')) {
  die('Access Denied');
}

$email = Format::input($_POST['luser'] ?: $_GET['e']);
$passwd = Format::input($_POST['lpasswd'] ?: $_GET['t']);

$content = Page::lookupByType('banner-client');

if ($content) {
  list($title, $body) = $ost->replaceTemplateVariables(
        [$content->getLocalName(), $content->getLocalBody()]);
}
else {
  $title = __('Sign In');
  $body = __('To better serve you, we encourage our clients to register for an account and verify the email address we have on record.');
}

?>
<h1><?php echo Format::display($title); ?></h1>
<p><?php echo Format::display($body); ?></p>
<form action="login.php" method="post" id="">
    <?php csrf_token(); ?>
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="jumbotron login-box">
                    <strong><?php echo Format::htmlchars($errors['login']); ?></strong>
                    <div class=" form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="<?php echo __('Email or Username'); ?>"
                                   name="luser" id="username" value="<?php echo $email; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputPassword3" name="lpasswd"
                                   placeholder="<?php echo __('Password'); ?>">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-12">

                            <input class="btn btn-primary pull-right" type="submit" value="<?php echo __('Sign In'); ?>">
                        </div>
                    </div>
                </div>
        </div>


    <div class="col-md-6">
        <div class="jumbotron">
            <?php if ($suggest_pwreset) { ?>
                <a style="padding-top:4px;display:inline-block;"
                   href="pwreset.php"><?php echo __('Forgot My Password'); ?></a>
            <?php } ?>

            <?php

            $ext_bks = [];
            foreach (UserAuthenticationBackend::allRegistered() as $bk) {
              if ($bk instanceof ExternalAuthentication) {
                $ext_bks[] = $bk;
              }
            }

            if (count($ext_bks)) {
              foreach ($ext_bks as $bk) { ?>
                    <div class="external-auth"><?php $bk->renderExternalLink(); ?></div><?php
              }
            }
            if ($cfg && $cfg->isClientRegistrationEnabled()) {
              if (count($ext_bks)) {
                echo '<hr style="width:70%"/>';
              } ?>
                <div style="margin-bottom: 5px">
                    <i class="fas fa-chevron-right"></i>
                    <?php echo __('Not yet registered?'); ?> <a
                            href="account.php?do=create"><?php echo __('Create an account'); ?></a>
                </div>
            <?php } ?>
            <div> <i class="fas fa-chevron-right"></i>
                <b><?php echo __("I'm an agent"); ?></b> —
                <a href="<?php echo ROOT_PATH; ?>scp/"><?php echo __('Sign in here'); ?></a>
            </div>
        </div>
    </div>
    </div>

</form>
<br>

<div class="alert alert-primary" role="alert">

    <?php
    if ($cfg->getClientRegistrationMode() != 'disabled'
        || !$cfg->isClientLoginRequired()) {
      echo sprintf(__('If this is your first time contacting us or you\'ve lost the ticket number, please %s open a new ticket %s'),
            '<a href="open.php">', '</a>');
    } ?>


</div>
