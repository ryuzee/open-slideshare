<?php
$source = $this->fetch('content');
$js_source = "";
$lines = explode("\n", $source);
foreach($lines as $line) {
    $line = convert_line($line);
    if($line != false) {
        $js_source .= $line;
    }
}
$packer = new Tholu\Packer\Packer($js_source, 'Normal', true, false);
$packed_js = $packer->pack();
echo $packed_js;

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
