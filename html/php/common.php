<?php

/**
 * common.php
 * 
 * Here are functions/objects that is not big enough to deserve their
 * classes.
 */

/**
 * @param str string to be quoted.
 * @return string with quotes. E.g. "Hello" -> "'Hello'".
 */
function Q($str){
    return '\''.$str.'\'';
}

/**
 * @param array of entries.
 * @return strings with commas in between.
 */
function commaSeparatedString(array $entries){
    $rv = '';

    // Insert comma in between.
    foreach ($entries as $entry){
        $rv = $rv.''.$entry.', ';
    }

    // Remove the last comma if entries is not empty.
    return ((strlen($rv) > 0) ? substr($rv, 0, -2) : '');
}


?>