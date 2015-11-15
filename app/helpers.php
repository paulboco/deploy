<?php

if (!function_exists('d')) {
    /**
     * Dump Variable
     *
     * @return void
     */
    function d()
    {
        array_map(function ($x) {
            echo '<pre>';
            var_dump($x);
            echo '</pre>';
        }, func_get_args());
    }
}

if (!function_exists('dd')) {
    /**
     * Dump Variable and Die
     *
     * @return void
     * @codeCoverageIgnore
     */
    function dd()
    {
        array_map(function ($x) {
            echo '<pre>';
            var_dump($x);
            echo '</pre>';
        }, func_get_args());
        die();
    }
}
