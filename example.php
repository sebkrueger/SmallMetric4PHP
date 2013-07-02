<?php
/**
 * SmallMetric4PHP - Performance-Timer
 *
 * Copyright (c) 2013 Sebastian Krüger
 *
 * @package SmallMetric4PHP
 * @file example.php
 */

// Include the Class
require_once 'SmallMetric4PHP/SmallMetric4PHP.class.php';

?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Example page to show the awesomeness of the PHP Small Metric class</title>
</head>
<body>

<p>Start up Class an run the script!</p>
<?php

// Init an object Start() is implied with init
$sm4php = new \SmallMetric4PHP\SmallMetric4PHP('My Example Datarun');

// Here some action
$dummy="";
for($counter=0;$counter<2370;$counter++) {
    // Use some CPU and alloc some Memory
    $dummy .= "onetwothree+";
}
// Simple Sleep some time (nearly half a second!)
 usleep(500000);


// Stop the lap time
$sm4php->Fixpoint('First Stop');

// Do some more action here
for($counter=0;$counter<4420;$counter++) {
    // Use some CPU and alloc some Memory
    $dummy .= "onetwothree+";
}
// Simple Sleep some more time zzzzz...
usleep(350000);

// Stop the whole action from here one we have time to print the results
$sm4php->Endpoint('Last Stop');

// Show what we track on our way .....
echo $sm4php->PrintResult();

?>
<p>End of the Example Page!</p>
</body>
</html>