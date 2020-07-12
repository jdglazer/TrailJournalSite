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
        require_once __DIR__ . "/vendor/autoload.php";
        require_once __DIR__ . "/generated-conf/config.php";
        use DataAccess\DataAccessObjects\Trail;
        $hikingDays = new Trail();
        $hikingDays->setName("Pacific Crest National Scenic Trail");
        $hikingDays->setDirections("LON");
        $hikingDays->setLength(2665.0);
        $hikingDays->save();
        
        // put your code here
        echo("count trails: ");
        ?>
    </body>
</html>
