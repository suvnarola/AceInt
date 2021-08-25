<?php

if (! isset($HTTP_GET_VARS)) {
	$HTTP_GET_VARS = $_GET;
}
if (! isset($HTTP_POST_VARS)) {
	$HTTP_POST_VARS = $_POST;
}
if (! isset($HTTP_POST_FILES)) {
	$HTTP_POST_FILES = $_FILES;
}

?>