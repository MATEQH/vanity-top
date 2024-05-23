<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

// Require the database configuration file
require 'database.php';

// Get the page number from the URL query paramater or set it to -1 if not provided
$page = isset($_GET['page']) ? (int)$_GET['page'] : -1;
// Set the number of items per page
$perPage = 10;
// Calculate the number of items to skip based on the page number
$skip = ($page - 1) * $perPage;

// Set te response headers to allow cross-origin request and specify the content type to JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Check if the 'date' parameter is set in the URL query
if (isset($_GET['date'])) {
    // Get the value if the 'date' parameter
    $date = $_GET['date'];

    // If the date is 'global', retrieve global player kill statistics
    if ($date == 'global') {
        // If page is set to -1, return the total player count
        if ($page == -1) {
            // Count the total number of players in the database
            $totalPlayers = $playersCollection->countDocuments();

            // Create the response array with the total player count
            $response = [
                'date' => 'global',
                'count' => $totalPlayers
            ];
            // Encode the response array as JSON and output it
            echo json_encode($response);
        } else {
            // If page is not -1, retrieve player data for the specified page
            // Query the database to retrieve player data, sorted by global kills, paginated
            $cursor = $playersCollection->find(
                [],
                [
                    'projection' => ['name' => 1, 'globalKills' => 1],
                    'sort' => ['globalKills' => -1],
                    'skip' => $skip,
                    'limit' => $perPage
                ]
            );
            
            // Initialize an empty array to store the result
            $result = [];
            
            // Iterate over the cursor and format the result data
            foreach ($cursor as $document) {
                if (isset($document['name'])) {
                    $result[] = ['uuid' => $document['_id'], 'name' => $document['name'], 'kills' => $document['globalKills']];
                } else {
                    $result[] = ['uuid' => $document['_id'], 'name' => $document['_id'], 'kills' => $document['globalKills']];
                }
            }
            
            // Encode the result array as JSON and output it
            echo json_encode($result);
        }
    } else {
        // If the date is not 'global', retrieve monthly player kill statistics
        // Query the kills collection to retrieve kill data for the specified date
        $document = $killsCollection->findOne(['_id' => $date]);

        // If no document is found for the specified date, return
        if (!$document) return;

        // Extract the players data from the document
        $players = iterator_to_array($document['players']);

        // If page is set to -1, return the total player count for the specified date
        if ($page == -1) {
            // Count the total number of players for the specified date
            $totalPlayers = count($players);

            // Create the response array with the total player count
            $response = [
                'date' => $date,
                'count' => $totalPlayers
            ];
            // Encode the response array as JSON and output it
            echo json_encode($response);
        } else {
            // If page is not -1, retrieve player data for the specified page
            // Sort the players data by kills in descending order
            arsort($players);

            // Slice the sorted player data to get the subset for the specified page
            $stats = array_slice($players, $skip, $perPage);

            // Extract the UUIDs of the players for the specified page
            $uuids = [];
            foreach ($stats as $uuid => $kills) {
                array_push($uuids, $uuid);
            }

            // Query the players collection to retrieve player names for the UUIDs
            $cursor = $playersCollection->find(
                ['_id' => ['$in' => $uuids]],
                ['projection' => ['_id' => 1, 'name' => 1]]
            );

            // Create a mapping of UUIDs to player names
            $names = [];
            foreach ($cursor as $document) {
                $names[$document['_id']] = $document['name'];
            }

            // Initialize an empty array to store the formatted result data
            $results = [];
            foreach ($uuids as $uuid) {
                $results[] = [
                    'uuid' => $uuid,
                    'kills' => $stats[$uuid],
                    'name' => isset($names[$uuid]) ? $names[$uuid] : null
                ];
            }
            
            // Encode the result array as JSON and output it
            echo json_encode($results);
        }
    }
} else {
    $results = ['global'];

    $cursor = $killsCollection->find([], ['projection' => ['_id' => 1], 'sort' => ['_id' => -1]]);

    foreach ($cursor as $document) {
        $results[] = $document['_id'];
    }

    echo json_encode($results);
}

?>
