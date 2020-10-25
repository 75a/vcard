<?php
    $contentJSON = file_get_contents("content/content.json");
    $content = json_decode($contentJSON, true);
    $webContent = $content['webcontent'];

    $lang = $content['defaultLanguage'];
    $jsStorage = true;

    if ($_GET['lang'] !== "") {
        if (array_key_exists($_GET['lang'],$content['languages'])){
            $lang = $_GET['lang'];
            $jsStorage = false;
        }
    }

    $bodyData = '';
    if ($jsStorage){
        $bodyData = 'data-loadfromstorage';
    }


?>
<!doctype html>
<html lang="<?=$lang?>">

      <head>
            <meta charset="utf-8">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="<?= $webContent['metaDescription'][$lang] ?>">

            <title><?= $webContent['metaTitle'][$lang] ?></title>

            <link rel="stylesheet" href="style/main.css">
            <link rel=”canonical” href="<?= $content['canonical'] ?>">

            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">

            <meta name="msapplication-TileColor" content="#0c1727">
            <meta name="theme-color" content="#181b2d">   
         
            <script defer src="src/index.js"></script>
      </head>
      <body <?=$bodyData?>>
            <div id="overlay"></div>
            <div class="vcard-container">
                  <nav class="nav-languages">
                        <ul class="nav-languages-list">
                        <?php
                            foreach($content['languages'] as $language => $active) {
                                $langClass =  "lang-switch color-dark";
                                if ($active) {
                                    if ($language == $lang) {
                                        $langClass = "lang-switch color-def";
                                    }
                                ?>
                                    <li>
                                        <a class="<?=$langClass?>"
                                           data-type='lang'
                                           data-language="<?=$language?>"
                                           href="<?=$content['canonical']?>/<?=$language?>">
                                            <?=$language?>
                                        </a>
                                    </li>
                                <?php
                                 }
                            }
                        ?>
                        </ul>
                  </nav>
                  <main class="vcard-wrapper">
                        <aside class="vcard-aside">
                              <div class="vcard-avatar-wrapper">
                                    <img class="vcard-avatar" src="<?= $content['avatarSrc'] ?>"  alt="Avatar image">
                              </div>
                              <div class="social-icon-wrapper">
                                    <a href="<?= $content['githubUrl'] ?>" target="_blank">
                                        <img class="social-icon" src="images/icons/github.svg" alt="Github">
                                    </a>

                                    <a href="<?= $content['linkedInUrl'] ?>" target="_blank">
                                        <img class="social-icon" src="images/icons/linkedin.svg" alt="LinkedIn">
                                    </a>
                              </div>
                        </aside>
                        <article>
                              <h1 class="vcard-header vcard-header-top color-def">
                                    <span id="name">
                                        <?= $webContent['name'][$lang] ?>
                                    </span>
                              </h1>
                              <p class="vcard-header vcard-header-description color-gray">
                                    <span id="shortdesc">
                                          <?= $webContent['shortdesc'][$lang] ?>
                                    </span>
                              </p>
                              <p class="vcard-content color-def">
                                  <span id="longdesc">
                                        <?= $webContent['longdesc'][$lang] ?>
                                  </span>
                              </p>
                        </article>
                  </main>
                  <footer>
                        <address class="vcard-email">
                              <a id="email" class="color-dark" href="#">
                                    <?= $webContent['botSafeEmail'][$lang]?>
                              </a>
                        </address>
                  </footer>
            </div>
      </body>
      
</html>