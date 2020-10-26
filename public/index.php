<?php
    require_once('inc/class.LanguageMenuOption.php');
    require_once('inc/class.ContentInjector.php');

    LanguageMenuOption::setActiveClassName("lang-switch color-def");
    LanguageMenuOption::setInactiveClassName("lang-switch color-dark");

    $contentInjector = new ContentInjector();
    $contentInjector->loadJSONContents('content/content.json');

    $activeLanguage = $contentInjector->getDisplayLanguage();
    $contentInjector->setContentLanguage($activeLanguage);
?>
<!doctype html>
<html lang="<?=$contentInjector->getDisplayLanguage()?>">
      <head>
            <meta charset="utf-8">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="<?=$contentInjector->content('metaDescription')?>">

            <title><?=$contentInjector->content('metaTitle')?></title>

            <link rel="stylesheet" href="<?=$contentInjector->content('styleSrc')?>">
            <link rel=”canonical” href="<?=$contentInjector->content('canonical')?>">

            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">

            <meta name="msapplication-TileColor" content="#0c1727">
            <meta name="theme-color" content="#181b2d">   
         
            <script defer src="src/index.js"></script>
      </head>
      <body <?=$contentInjector->getBodyData()?>>
            <div id="overlay"></div>
            <div class="vcard-container">
                  <nav class="nav-languages">
                        <ul class="nav-languages-list">
                            <?php $contentInjector->renderLanguageMenuOptions() ?>
                        </ul>
                  </nav>
                  <main class="vcard-wrapper">
                        <aside class="vcard-aside">
                              <div class="vcard-avatar-wrapper">
                                    <img class="vcard-avatar" src="<?=$contentInjector->content('avatarSrc')?>"  alt="Avatar image">
                              </div>
                              <div class="social-icon-wrapper">
                                    <a href="<?=$contentInjector->content('githubUrl')?>" target="_blank">
                                        <img class="social-icon" src="images/icons/github.svg" alt="Github">
                                    </a>

                                    <a href="<?= $contentInjector->content('linkedInUrl') ?>" target="_blank">
                                        <img class="social-icon" src="images/icons/linkedin.svg" alt="LinkedIn">
                                    </a>
                              </div>
                        </aside>
                        <article>
                              <h1 class="vcard-header vcard-header-top color-def">
                                    <span id="name">
                                        <?=$contentInjector->content('name')?>
                                    </span>
                              </h1>
                              <p class="vcard-header vcard-header-description color-gray">
                                    <span id="shortdesc">
                                          <?=$contentInjector->content('shortdesc')?>
                                    </span>
                              </p>
                              <p class="vcard-content color-def">
                                  <span id="longdesc">
                                        <?=$contentInjector->content('longdesc')?>
                                  </span>
                              </p>
                        </article>
                  </main>
                  <footer>
                        <address class="vcard-email">
                              <a id="email" class="color-dark" href="#">
                                    <?=$contentInjector->content('botSafeEmail')?>
                              </a>
                        </address>
                  </footer>
            </div>
      </body>
</html>