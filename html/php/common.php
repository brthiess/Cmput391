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

/**
 * @param array1
 * @param array2
 * @return true if two arrays contain the same elements.
 */
function arraySetCompare(array $array1, array $array2){
    // Compare size first. If not the same return false.
    if(count($array1) != count($array2)){
        return False;
    }

    // See if an element in $array1 doesn't exist in $array2.
    foreach ($array1 as $a){
        $aExistInArray2 = False;
        foreach($array2 as $b){
            if($a == $b){
                $aExistInArray2 = True;
                break;
            }
        }
        
        // If element $a in $array1 doesn't exist in $array2, return false.
        if(!$aExistInArray2){
            return False;
        }
    }

    return True;
}

/**
 * @param str1
 * @param str2
 * @return edit distance of str1 and str2.
 */
function editDistance($str1, $str2){    
    $out = NULL;
    exec(dirname(__FILE__)."/EditDistance/editDistance ".$str1." ".$str2, $out);
    return $out[0];
}

?>