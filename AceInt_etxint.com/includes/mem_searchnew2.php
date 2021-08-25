<?php
    /**
     * Copyright 2005 Zervaas Enterprises (www.zervaas.com.au)
     *
     * Licensed under the Apache License, Version 2.0 (the "License");
     * you may not use this file except in compliance with the License.
     * You may obtain a copy of the License at
     *
     *     http://www.apache.org/licenses/LICENSE-2.0
     *
     * Unless required by applicable law or agreed to in writing, software
     * distributed under the License is distributed on an "AS IS" BASIS,
     * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
     * See the License for the specific language governing permissions and
     * limitations under the License.
     */

    require_once('/home/etxint/admin.etxint.com/includes/modules/GoogleSuggestCloneJax.class.php');

    $ajax = new GoogleSuggestCloneJax();
    $ajax->handleRequest();

    $q = isset($_GET['q']) ? $_GET['q'] : '';

    $ret = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>AjaxAC Sample Application: GoogleSuggestCloneJax</title>
        <?= $ajax->loadJsCore(true) ?>

        <link rel="StyleSheet" type="text/css" href="googlesuggestclone.css" />
    </head>
    <body>
        <h1>GoogleSuggestCloneJax</h1>

        <?php if (strlen($q) > 0) { ?>
            <h3>
                Search submitted: <strong><?= htmlSpecialChars($q) ?></strong>
            </h3>
        <?php } ?>
        <p>
            The GoogleSuggestCloneJax is a clone of Google's search tool,
            <a href="http://www.google.com/webhp?complete=1&hl=en">Google Suggest</a>.
        </p>
        <p>
            Our example doesn't do real-time searches of the entered keywords, but rather,
            uses a pre-existing list of data to fetch the info from. If you're not sure
            what will trigger the results, try just typing 'a', or 'b', or 'php'.
        </p>

        <ul>
            <li><a href="index.phps">Source code for this file</a></li>
            <li><a href="GoogleSuggestCloneJax.class.phps">Source code for GoogleSuggestCloneJax application</a></li>
            <li><a href="data.sql">Server-side search data</a></li>
            <li><a href="index.php/jsapp">Generated application JS code</a></li>
        </ul>

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

