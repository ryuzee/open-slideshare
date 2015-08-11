<?php
$source = $this->fetch('content');
$lines = explode("\n", $source);
foreach($lines as $line) {
    $line = convert_line($line);
    if($line != false) {
        echo $line;
    }
}

function convert_line($line) {
    $line = trim($line);
    if ($line == "") {
        return false;
    }
    $line = addslashes($line);
    $line = 'document.write("' .$line .'\n");';
    return $line;
}
?>
