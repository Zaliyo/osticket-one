<?php

/**
 * @file
 */

$title = ($cfg && is_object($cfg) && $cfg->getTitle())
    ? $cfg->getTitle() : 'osTicket :: ' . __('Support Ticket System');
$signin_url = ROOT_PATH . "login.php"
    . ($thisclient ? "?e=" . urlencode($thisclient->getEmail()) : "");
$signout_url = ROOT_PATH . "logout.php?auth=" . $ost->getLinkToken();

header("Content-Type: text/html; charset=UTF-8");
header("Content-Security-Policy: frame-ancestors " . $cfg->getAllowIframes() . ";");

if (($lang = Internationalization::getCurrentLanguage())) {
  $langs = array_unique([$lang, $cfg->getPrimaryLanguage()]);
  $langs = Internationalization::rfc1766($langs);
  header("Content-Language: " . implode(', ', $langs));
}
?>
<!DOCTYPE html>
<html<?php
if ($lang
    && ($info = Internationalization::getLanguageInfo($lang))
    && (@$info['direction'] == 'rtl')
) {
  echo ' dir="rtl" class="rtl"';
}
if ($lang) {
  echo ' lang="' . $lang . '"';
}

// Dropped IE Support Warning.
if (osTicket::is_ie()) {
  $ost->setWarning(__('osTicket no longer supports Internet Explorer.'));
}
?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo Format::htmlchars($title); ?></title>
    <meta name="description" content="customer support platform">
    <meta name="keywords" content="osTicket, Customer support system, support ticket system">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">


    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/osticket.css?cb6766e" media="screen"/>
    <link rel="stylesheet" href="<?php echo ASSETS_ONE_PATH; ?>css/theme.css?cb6766e" media="screen"/>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/print.css?cb6766e" media="print"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>scp/css/typeahead.css?cb6766e"
          media="screen"/>
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css?cb6766e"
          rel="stylesheet" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>css/jquery-ui-timepicker-addon.css?cb6766e" media="all"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/thread.css?cb6766e" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css?cb6766e" media="screen"/>

    <link rel="stylesheet" href="<?php echo ASSETS_ONE_PATH; ?>fontawesome/css/all.css" media="screen"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css?cb6766e"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css?cb6766e"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css?cb6766e"/>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="<?php echo ROOT_PATH ?>images/oscar-favicon-32x32.png" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo ROOT_PATH ?>images/oscar-favicon-16x16.png" sizes="16x16"/>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-3.5.1.min.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-ui-1.12.1.custom.min.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-ui-timepicker-addon.js?cb6766e"></script>
    <script src="<?php echo ROOT_PATH; ?>js/osticket.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/filedrop.field.js?cb6766e"></script>
    <script src="<?php echo ROOT_PATH; ?>scp/js/bootstrap-typeahead.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor.min.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-plugins.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/redactor-osticket.js?cb6766e"></script>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/select2.min.js?cb6766e"></script>
    <?php
    if ($ost && ($headers = $ost->getExtraHeaders())) {
      echo "\n\t" . implode("\n\t", $headers) . "\n";
    }

    // Offer alternate links for search engines.
    // @see https://support.google.com/webmasters/answer/189077?hl=en
    if (($all_langs = Internationalization::getConfiguredSystemLanguages())
        && (count($all_langs) > 1)
    ) {
      $langs = Internationalization::rfc1766(array_keys($all_langs));
      $qs = [];
      parse_str($_SERVER['QUERY_STRING'], $qs);
      foreach ($langs as $L) {
        $qs['lang'] = $L; ?>
            <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?<?php
            echo http_build_query($qs); ?>" hreflang="<?php echo $L; ?>"/>
            <?php
      } ?>
        <link rel="alternate" href="//<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
              hreflang="x-default"/>
        <?php
    }
    ?>
</head>
<body>
<?php
if ($nav) { ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #353b65 !important;">

    <div class="container">
        <a class="navbar-brand" href="#"></a>
        <ul>

        <span class="text-white">
            Welcome
        <?php
        if ($thisclient && is_object($thisclient) && $thisclient->isValid()
        && !$thisclient->isGuest()
        ) {
          echo Format::htmlchars($thisclient->getName());
          ?>
                            </span>
            <?php
        }
        elseif ($nav) {
          if ($cfg->getClientRegistrationMode() == 'public') { ?>
                    <?php echo __('Guest'); ?><?php
          }
        } ?>
            </li>
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">

            </ul>
            <ul class="nav pull-right">
                <?php
                if ($nav && ($navs = $nav->getNavLinks()) && is_array($navs)) {
                  foreach ($navs as $name => $nav) {
                    echo sprintf('<li class="nav-item"><a class="%s %s nav-link link-dark px-2" href="%s">%s</a></li>%s', $nav['active'] ? 'active' : '', $name, (ROOT_PATH . $nav['href']), $nav['desc'], "\n");
                  }
                } ?>
                <li class="nav-item">

                    <?php
                    if ($thisclient && is_object($thisclient) && $thisclient->isValid()
                    && !$thisclient->isGuest()
                    ) {

                      ?>

                </li>
                <li class="nav-item">
                    <a class="home nav-link link-dark px-2"
                       href="<?php echo ROOT_PATH; ?>profile.php"><?php echo __('Profile'); ?></a>
                </li>

                <li class="nav-item">
                    <a class="home nav-link link-dark px-2"
                       href="<?php echo $signout_url; ?>"><?php echo __('Sign Out'); ?></a>
                </li>
                      <?php
                    } ?>
                </li>


                <li class="nav-item">

                    <?php
                    if ($thisclient && is_object($thisclient) && $thisclient->isValid()
                    && !$thisclient->isGuest()
                    ) {

                      ?>

                </li>

                      <?php
                    }
                    elseif ($nav) {

                      if ($thisclient && $thisclient->isValid() && $thisclient->isGuest()) { ?>
                    <a class="active tickets nav-link link-dark px-2"
                       href="<?php echo $signout_url; ?>"><?php echo __('Sign Out'); ?></a><?php
                      }
                      elseif ($cfg->getClientRegistrationMode() != 'disabled') { ?>
                    <a class="home nav-link link-dark px-2"
                       href="<?php echo $signin_url; ?>"><?php echo __('Sign In'); ?></a>
                        <?php
                      }
                    } ?>
                </li>
            </ul>

            <?php
}
else { ?>
                <hr>
                <?php
} ?>

            </ul>

        </div>
    </div>
</nav>

<div id="container-fluid">
    <?php
    if ($ost->getError()) {
      echo sprintf('<div class="error_bar">%s</div>', $ost->getError());
    }
    elseif ($ost->getWarning()) {
      echo sprintf('<div class="warning_bar">%s</div>', $ost->getWarning());
    }
    elseif ($ost->getNotice()) {
      echo sprintf('<div class="notice_bar">%s</div>', $ost->getNotice());
    }
    ?>

    <div id="header">
        <div class="container">

            <a class="pull-left" id="logo" href="<?php echo ROOT_PATH; ?>index.php"
               title="<?php echo __('Support Center'); ?>">
                <span class="valign-helper"></span>
                <img src="<?php echo ROOT_PATH; ?>logo.php" border=0 alt="<?php
                echo $ost->getConfig()->getTitle(); ?>">
            </a>

        </div>
    </div>
    <div class="clear"></div>


    <?php

    if ($cfg && $cfg->isKnowledgebaseEnabled()) { ?>
        <div class="search-area-wrapper">
            <div class="search-area container">
                <h3 class="col-sm-12 search-header">Have a Question?</h3>
                <p class="col-sm-12 search-tag-line">If you have any question you can ask below or enter what you are
                    looking
                    for!</p>

                <form id="search-form" class="search-form clearfix" method="get" action="/kb/faq.php" autocomplete="off"
                      novalidate="novalidate">
                    <input class="search-term required" type="text" id="s" name="q"
                           placeholder="Type your search terms here" title="* Please enter a search term!"
                           autocomplete="off">
                    <input class="search-btn" type="submit" value="Search">
                    <div id="search-error-container"></div>
                </form>
            </div>
        </div>

    <?php } ?>
    <div class="clear"></div>

    <div id="container">

        <?php if ($errors['err']) { ?>
            <div class="alert alert-danger" role="alert" id=""><?php echo $errors['err']; ?></div>
        <?php }
        elseif ($msg) { ?>
            <div class="alert alert-success" role="alert" id="msg_notice"><?php echo $msg; ?></div>
        <?php }
        elseif ($warn) { ?>
            <div class="alert alert-warning" role="alert" id="msg_warning"><?php echo $warn; ?></div>
        <?php }
