<?php
require_once('config.php');
require_once('includes/top.php');
require_once('includes/db.php');
require_once('includes/functions.php');

// create global db object
$db = new Db(Config::DB_HOST, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);

require_once('fragments/header.php');
require_once('pages/' . $page . '.php');
require_once('fragments/footer.php');
