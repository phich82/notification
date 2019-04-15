<?php

require_once "../classes/Comet.php";
 
$comet = new NovComet();
$publish = filter_input(INPUT_GET, 'published', FILTER_SANITIZE_STRING);

$result = null;
if ($publish != '') {
    $result = $comet->publish($publish);
} else {
    if (isset($_GET['subscribed'])) {
        //var_dump($_GET['subscribed']);
        foreach (filter_var_array($_GET['subscribed'], FILTER_SANITIZE_NUMBER_INT) as $k => $v) {
            $comet->setVar($k, $v);
        }
        $result = $comet->run();
    }  
}
echo $result;