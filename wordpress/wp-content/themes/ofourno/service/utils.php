<?php

function get_value_from_key($array, $key) {
    if (array_key_exists($key, $array)) {
        return (string) $array[$key];
    }
    return null;
}

function get_meal_type_by_time($current_hour) {
    if ($current_hour >= 0 && $current_hour <= 9) {
            $meal_type = 'breakfast';
        } elseif ($current_hour >= 9 && $current_hour <= 13) {
            $meal_type = 'lunch';
        } elseif ($current_hour >= 13 && $current_hour <= 17) {
            $meal_type = 'snack';
        } else {
            $meal_type = 'dinner';
        }

    return $meal_type;
}