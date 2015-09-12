<?php
if(isset($error)) {
    $result_array['error'] = $error;
} else {
    $result_array['transcripts'] = $transcripts;
}
echo json_encode($result_array);
