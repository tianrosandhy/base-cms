<?php
function copy_directory($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                copy_directory($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}


function rename_recursive($start_dir, $changeTo='Blank', $debug = true) {
    $str = "";
    $files = array();
    if (is_dir($start_dir)) {
        $fh = opendir($start_dir);
        while (($file = readdir($fh)) !== false) {
            // skip hidden files and dirs and recursing if necessary
            if (strpos($file, '.')=== 0) continue;

            $filepath = $start_dir . '/' . $file;
            if ( is_dir($filepath) ) {
                $newname = change_name($filepath, $changeTo);
                $str.= "From $filepath\nTo $newname\n";
                rename($filepath, $newname);
                rename_recursive($newname);
            } else {
                $newname = change_name($filepath, $changeTo);
                $str.= "From $filepath\nTo $newname\n";
                rename($filepath, $newname);
            }
        }
        closedir($fh);
    }
    if ($debug) {
        echo $str;
    }
}

function change_name( $filename , $changeTo) {
    $filename_raw = $filename;
    $special_chars = array("?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    $filename = str_replace('Blank', $changeTo, $filename);
    return $filename;
}