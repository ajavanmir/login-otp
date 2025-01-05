<?php
/*
Copyright amir javanmir
Released on: january 5, 2025
*/
function check($field){
    $field = htmlspecialchars(stripslashes(trim($field)));
    $field = !empty($field)? $field : null;
    return $field;
}
function hasError($value){   
    return !empty($value);
}
?>