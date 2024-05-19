<?php

// Include the Composer autoloader to load dependencies
require "/vendor/autoload.php";
// Include the database configuration file
require "config.php";

// Import necessary classes from the MongoDB PHP library
use MongoDB\Client as Mongo;

// Create a new instance of the MongoDB client using the connection URI from the config file
$client = new Mongo(MONGO_URI);
// Select the 'core' database from the MongoDB client
$database = $client->core;

// Select the 'players' and 'kills' collections from the 'core' database
$playersCollection = $database->players;
$killsCollection = $database->kills;

?>