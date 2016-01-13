<?php
//Various functions

function cleanse($formdata)
{
    $formdata = trim($formdata);
    $formdata = stripslashes($formdata);
    return $formdata;
}

function htmlchars($formdata)
{
    $formdata = htmlspecialchars($formdata, ENT_QUOTES, 'UTF-8');
    return $formdata;
}

