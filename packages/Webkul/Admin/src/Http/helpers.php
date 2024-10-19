<?php

if (! function_exists('bouncer')) {
    function bouncer()
    {
        return app()->make('bouncer');
    }
}

if (! function_exists('getColorForRating')) {
    function getColorForRating($rating)
    {
        if ($rating === 0) return '#000000'; // Black for 0
        if ($rating <= 3) return '#006400'; // Dark Green for 1-3
        if ($rating <= 6) return '#FFA500'; // Orange for 4-6
        if ($rating <= 9) return '#FF4500'; // Red-Orange for 7-9
        return '#FF0000'; // Bright Red for 10
    }
}
