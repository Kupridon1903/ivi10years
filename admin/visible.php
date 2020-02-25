<?php

require_once "../src/DB.php";
require_once "../src/Config.php";

use MiniUpload\DB;

$id = $_REQUEST['id'];

$db = new DB();

$db->setVisible($id);