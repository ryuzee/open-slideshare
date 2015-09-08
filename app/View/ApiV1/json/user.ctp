<?php
if (isset($user)) {
    $a['User']['id'] = $user['User']['id'];
    $a['User']['user_name'] = $user['User']['display_name'];
    $a['User']['biography'] = $user['User']['biography'];
    $a['User']['created'] = $user['User']['created'];
    $user = $a;
} else {
    $user = array();
}

$result_array = array('user' => $user);
if (isset($error)) {
    $result_array['error'] = $error;
}
echo json_encode($result_array);
