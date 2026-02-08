<?php
$usersjson = 'users.json';
$campaignjson = 'campaigns.json';
$assignmentsjson = 'assignments.json';

$userData = file_get_contents($usersjson); //read the whole file as one and store it in a variable
$users = json_decode($userData, true);

$campaignData = file_get_contents($campaignjson);
$campaigns = json_decode($campaignData, true);

$assignmentData = file_get_contents($assignmentsjson);
$assignments = json_decode($assignmentData, true);
$assignments = $assignments ?? [];

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case($method == 'GET' && preg_match('/\/api\/users\/[1-9]+\/campaigns/', $uri)): // GET CAMPAIGNS OF A USER
        header('Content-Type: application/json');
        $parts = explode('/', $uri);
        $userId = $parts[3];

        if(!isset($assignments[$userId])){
            echo json_encode([]);
            break;
        }

        $result = [];
        foreach ($assignments[$userId] as $cid) {
            if (isset($campaigns[$cid])) {
            $result[$cid] = $campaigns[$cid];
            }
        }

        echo json_encode($result, JSON_PRETTY_PRINT);
        break;

    case($method == 'GET' && preg_match('/\/api\/campaigns\/[1-9]+\/users/', $uri)): // GET LIST OF ALL USERS for SPECIFIC CAMPAIGN
        header('Content-Type: application/json');
        $parts = explode('/', $uri);
        $campaignId = $parts[3];

        
        $result = [];
        foreach ($assignments as $userId => $campaignList) {
            if (in_array($campaignId, $campaignList)) {
                $result[$userId] = $users[$userId];
            }
        }
        echo json_encode($result, JSON_PRETTY_PRINT);
        break;

   case ($method == 'GET' && $uri == '/api/users')://GET ALL
       header('Content-Type: application/json');
       echo json_encode($users, JSON_PRETTY_PRINT);
       break;
       
    case ($method == 'GET' && $uri == '/api/campaigns'):
        header('Content-Type: application/json');
        echo json_encode($campaigns, JSON_PRETTY_PRINT);
        break;

   case ($method == 'GET' && preg_match('/\/api\/users\/[1-9]+/', $uri))://GET ONE
       header('Content-Type: application/json');
       $id = basename($uri);
       if (!array_key_exists($id, $users)) {
           http_response_code(404);
           echo json_encode(['error' => 'user does not exist']);
           break;
       }
       $responseData = [$id => $users[$id]];
       echo json_encode($responseData, JSON_PRETTY_PRINT);
       break;

    case($method == 'GET' && preg_match('/\/api\/campaigns\/[1-9]+/', $uri)):
        header('Content-Type: application/json');
        $id = basename($uri);
        if (!array_key_exists($id, $campaigns)) {
           http_response_code(404);
           echo json_encode(['error' => 'campaign does not exist']);
           break;
        }
        echo json_encode([$id => $campaigns[$id]], JSON_PRETTY_PRINT);
        break;

   case ($method == 'POST' && $uri == '/api/users')://STORE(POST)
       header('Content-Type: application/json');
       $requestBody = json_decode(file_get_contents('php://input'), true);
       $name = $requestBody['name'] ?? null;;
       if (empty($name)) {
           http_response_code(404);
           echo json_encode(['error' => 'Please add name']);
           break;
       }
       $users[] = $name;
       $userData = json_encode($users, JSON_PRETTY_PRINT);
       file_put_contents($usersjson, $userData);
       echo json_encode(['message' => 'userData added successfully']);
       break;

    case ($method == 'POST' && $uri == '/api/campaigns'):
        header('Content-Type: application/json');
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $name = $requestBody['name'] ?? null;;
        if (empty($name)) {
           http_response_code(404);
           echo json_encode(['error' => 'Please add campaign name']);
           break;
       }
       $campaigns[] = $name;
       file_put_contents($campaignjson, json_encode($campaigns, JSON_PRETTY_PRINT));
       echo json_encode(['message' => 'campaign added successfully']);
       break;

   case ($method == 'PUT' && preg_match('/\/api\/users\/[1-9]+/', $uri))://UPDATE
       header('Content-Type: application/json');
       $id = basename($uri);
       if (!array_key_exists($id, $users)) {
           http_response_code(404);
           echo json_encode(['error' => 'userData does not exist']);
           break;
       }
       $requestBody = json_decode(file_get_contents('php://input'), true);
       $name = $requestBody['name'];
       if (empty($name)) {
           http_response_code(404);
           echo json_encode(['error' => 'Please add name of the userData']);
           break;
       }
       $users[$id] = $name;
       $userData = json_encode($users, JSON_PRETTY_PRINT);
       file_put_contents($usersjson, $userData);
       echo json_encode(['message' => 'userData updated successfully']);
       break;

    case ($method == 'PUT' && preg_match('/\/api\/campaigns\/[1-9]+/', $uri)):
       header('Content-Type: application/json');
       $id = basename($uri);
       if (!array_key_exists($id, $campaigns)) {
           http_response_code(404);
           echo json_encode(['error' => 'campaign does not exist']);
           break;
       }

       $requestBody = json_decode(file_get_contents('php://input'), true);
       $name = $requestBody['name'];
       if (empty($name)) {
           http_response_code(404);
           echo json_encode(['error' => 'Please add name of the campaign']);
           break;
       }

       $campaigns[$id] = $name;
       $campaign = json_encode($campaigns, JSON_PRETTY_PRINT);
       file_put_contents($campaignjson, $campaign);
       echo json_encode(['message' => 'campaign updated successfully']);
       break;

   case ($method == 'DELETE' && preg_match('/\/api\/users\/[1-9]+/', $uri))://DELETE
       header('Content-Type: application/json');
       $id = basename($uri);
       if (empty($users[$id])) {
           http_response_code(404);
           echo json_encode(['error' => 'userData does not exist']);
           break;
       }
       unset($users[$id]);
       $userData = json_encode($users, JSON_PRETTY_PRINT);
       file_put_contents($usersjson, $userData);
       echo json_encode(['message' => 'userData deleted successfully']);
       break;

    case ($method == 'DELETE' && preg_match('/\/api\/campaigns\/[1-9]+/', $uri)): //DELETE CAMPAIGN
       header('Content-Type: application/json');
       $id = basename($uri);
       if (empty($campaigns[$id])) {
           http_response_code(404);
           echo json_encode(['error' => 'campaign does not exist']);
           break;
       }
       unset($campaigns[$id]);
       $campaign = json_encode($campaigns, JSON_PRETTY_PRINT);
       file_put_contents($campaignjson, $campaign);
       echo json_encode(['message' => 'campaign deleted successfully']);
       break;

    case ($method == 'POST' && $uri == '/api/assign'): //ASSIGN USER TO CAMPAIGN
    header('Content-Type: application/json');
    $requestBody = json_decode(file_get_contents('php://input'), true);

    $userId = $requestBody['user_id'] ?? null;
    $campaignId = $requestBody['campaign_id'] ?? null;

        if (!isset($users[$userId]) || !isset($campaigns[$campaignId])) {
            http_response_code(404);
            echo json_encode(['error' => 'user or campaign does not exist']);
            break;
        }

        if (!isset($assignments[$userId])) {
            $assignments[$userId] = [];
        }

        if (!in_array($campaignId, $assignments[$userId])) {
            $assignments[$userId][] = $campaignId;
        }

    file_put_contents($assignmentsjson, json_encode($assignments, JSON_PRETTY_PRINT));
    echo json_encode(['message' => 'user assigned to campaign']);
    break;
        
    case($method == 'GET' && preg_match('/\/api\/assigned\/[1-9]+\/[1-9]+/', $uri)): //CHECK IF USER IS ASSIGNED TO CAMPAIGN BOOL
        header('Content-type: application/json');
        $parts = explode('/', $uri);
        $userId = $parts[3];
        $campaignId = $parts[4];

        $assigned = isset($assignments[$userId]) && in_array($campaignId, $assignments[$userId]);

        echo json_encode(['assigned' => $assigned]);
        break;
        
   default:
       http_response_code(404);
       echo json_encode(['error' => "We cannot find what you're looking for."]);
       break;
}
