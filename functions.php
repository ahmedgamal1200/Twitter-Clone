<?php

// To check if username already exist or not 

function checkItem($column, $table, $value)
{
    global $db;

    $stmt = $db->prepare ("SELECT COUNT(*) FROM $table WHERE $column = ?");
    $stmt->execute([$value]);

    return $stmt->fetchColumn() > 0 ? true : false;
}