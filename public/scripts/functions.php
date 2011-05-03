<?php


function email_check($email) {
  if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i",
    $email))
    return false;
  return true;
}

?>
