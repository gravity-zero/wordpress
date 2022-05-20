<?php

/**
 * @param $table_name
 * @param $args @object of @array
 * @param $charset
 * @return void
 */
function create_table_if_not_exist($table_name, $args, $charset)
{
    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (";
    foreach($args as $key => $args_array)
    {
        if($key !== "primary"){
            $sql .= $key ." " . implode(" ", $args_array) . ", \r\n";
        }else{
            $sql .= "PRIMARY KEY " . implode(" ", $args_array) ."\r\n";
        }
    }
    $sql .= ") {$charset}";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}