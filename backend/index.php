<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

require 'database.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : -1;
$perPage = 10;
$skip = ($page - 1) * $perPage;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    if ($date == 'global') {
        if ($page == -1) {
            $totalPlayers = $playersCollection->countDocuments();
            $response = [
                'date' => 'global',
                'count' => $totalPlayers
            ];
            echo json_encode($response);
        } else {
            $cursor = $playersCollection->find(
                [],
                [
                    'projection' => ['name' => 1, 'globalKills' => 1],
                    'sort' => ['globalKills' => -1],
                    'skip' => $skip,
                    'limit' => $perPage
                ]
            );
            
            $result = [];
            
            foreach ($cursor as $document) {
                if (isset($document['name'])) {
                    $result[] = ['uuid' => $document['_id'], 'name' => $document['name'], 'kills' => $document['globalKills']];
                } else {
                    $result[] = ['uuid' => $document['_id'], 'name' => $document['_id'], 'kills' => $document['globalKills']];
                }
            }
            
            echo json_encode($result);
        }
    } else {
        $document = $killsCollection->findOne(['_id' => $date]);

        if (!$document) return;

        $players = iterator_to_array($document['players']);

        if ($page == -1) {
            $totalPlayers = count($players);

            $response = [
                'date' => $date,
                'count' => $totalPlayers
            ];
            echo json_encode($response);
        } else {
            arsort($players);

            $stats = array_slice($players, $skip, $perPage);

            $uuids = [];
            foreach ($stats as $uuid => $kills) {
                array_push($uuids, $uuid);
            }

            $cursor = $playersCollection->find(
                ['_id' => ['$in' => $uuids]],
                ['projection' => ['_id' => 1, 'name' => 1]]
            );

            $names = [];
            foreach ($cursor as $document) {
                $names[$document['_id']] = $document['name'];
            }

            $results = [];
            foreach ($uuids as $uuid) {
                $results[] = [
                    'uuid' => $uuid,
                    'kills' => $stats[$uuid],
                    'name' => isset($names[$uuid]) ? $names[$uuid] : null
                ];
            }
            
            echo json_encode($results);
        }
    }
}

?>
