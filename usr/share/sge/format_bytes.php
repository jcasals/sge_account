<?php 
define("KILO", 1024);
define("MEGA", KILO * 1024);
define("GIGA", MEGA * 1024);
define("TERA", GIGA * 1024);

function format_bytes($bytes) {
    if ($bytes < KILO) {
        return $bytes . ' B';
    }
    if ($bytes < MEGA) {
        return round($bytes / KILO, 2) . ' KB';
    }
    if ($bytes < GIGA) {
        return round($bytes / MEGA, 2) . ' MB';
    }
    if ($bytes < TERA) {
        return round($bytes / GIGA, 2) . ' GB';
    }
    return round($bytes / TERA, 2) . ' TB';
}
?>
