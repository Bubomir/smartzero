<?php
/**
 * Grab google fonts json and save it
 *
 * @package Essential
 * @since Essential 1.0
 */

$cachefile = 'fonts.json';
$cachetime = 60 * 60;
// Serve from the cache if it is younger than $cachetime
if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
    include($cachefile);
    exit;
}
ob_start(); // Start the output buffer

/* The code to get the google web fonts list goes here */
$ch = curl_init('http://essential.prospekt-solutions.com/fonts.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$content = curl_exec($ch);
echo $content;
curl_close($ch);

// Cache the output to a file
$fp = fopen($cachefile, 'w');
fwrite($fp, ob_get_contents());
fclose($fp);
ob_end_flush(); // Send the output to the browser