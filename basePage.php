<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
        require_once __DIR__ . "/vendor/autoload.php";
        require_once __DIR__ . "/target/config.php";
        require_once __DIR__ . "/siteConstants.php";
        
        // Load the main content
        require_once __DIR__ . $PAGE_CONTENT;
        ?>
    </body>
</html>

