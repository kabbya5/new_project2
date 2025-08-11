<?php

if (!function_exists('generate_random_key')) {
    function generate_random_key($length = 10)
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $time = (string) time(); // e.g. 1691009872

        // Shuffle letters and time
        $pool = str_shuffle($letters . $time);

        // Cut to required length
        return substr($pool, 0, $length);
    }
}
