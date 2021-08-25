<?php

    require_once('includes/modules/GoogleSuggestCloneJax.class.php');

    $ajax = new GoogleSuggestCloneJax();
    $ajax->handleRequest();

    $q = isset($_GET['q']) ? $_GET['q'] : '';

    $ret = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>x</title>
        <?= $ajax->loadJsCore(true) ?>

        <link rel="StyleSheet" type="text/css" href="googlesuggest.css" />
    </head>
    <body>

        <?php if (strlen($q) > 0) { ?>
            <h3>
                Search submitted: <strong><?= htmlSpecialChars($q) ?></strong>
            </h3>
        <?php } ?>


       <form method="get" id="f">
            <input type="text" name="q" id="fq" autocomplete="off" />
            <input type="submit" value="Search" id="fs" />
            <div id="search-results"></div>
        </form>

        <?= $ajax->attachWidgets(array('query'   => 'fq',
                                       'results' => 'search-results')) ?>

        <?= $ajax->loadJsApp(true) ?>

    </body>
</html>
