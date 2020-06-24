<?php
 
function sanitizeInput($input)
{
    // Trim Data
    $input = trim($input);
    // secure for sql injection
    // $input = htmlspecialchars($input,ENT_QUOTES,'UTF-8');
    // // // to prevent SQL Injection
    // $input = mysqli_real_escape_string(mysqli,$input);
    // remove

    return $input;
}
