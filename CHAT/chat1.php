<?php
require_once "phpfreechat-1.7/src/phpfreechat.class.php";
$chat_params["serverid"] = md5(__FILE__);
$chat = new phpFreeChat($chat_params);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>phpFreeChat demo</title>
</head>
<body>
  <?php $chat->printChat(); ?>
</body>
</html>