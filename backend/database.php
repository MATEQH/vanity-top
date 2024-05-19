<?php

require "/vendor/autoload.php";
require "config.php";

use MongoDB\Client as Mongo;

$client = new Mongo(MONGO_URI);
$database = $client->core;
$playersCollection = $database->players;
$killsCollection = $database->kills;

?>