<?php

if (!function_exists('dd')) {//@todo переименовать в helpers
    function dd(...$params)
    {
        var_dump(...$params);
        die;
    }
}