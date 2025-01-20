<?php

$path = storage_path("app/config.json");
if (file_exists($path)) {
    $config = json_decode(file_get_contents($path), true);
    return $config;
} else {
    return [];
}
