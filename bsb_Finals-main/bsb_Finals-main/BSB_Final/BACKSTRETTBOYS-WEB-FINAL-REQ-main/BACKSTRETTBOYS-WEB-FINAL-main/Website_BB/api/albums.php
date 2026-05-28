<?php
/**
 * Albums API - CRUD operations for albums (Movies equivalent)
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/database.php';
include_once '../models/Album.php';

$database = new Database();
$db = $database->getConnection();
$album = new Album($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($action === 'getById' && isset($_GET['id'])) {
                // Get single album with tracks
                $albumData = $album->getById($_GET['id']);
                if ($albumData) {
                    $tracks = $album->getTracks($_GET['id'])->fetchAll(PDO::FETCH_ASSOC);
                    $albumData['tracks'] = $tracks;
                    http_response_code(200);
                    echo json_encode($albumData);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Album not found']);
                }
            } elseif ($action === 'getTracks' && isset($_GET['album_id'])) {
                // Get album tracks only
                $stmt = $album->getTracks($_GET['album_id']);
                $tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode(['success' => true, 'data' => $tracks]);
            } else {
                // Get all albums
                $stmt = $album->getAll();
                $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'data' => $albums,
                    'count' => count($albums)
                ]);
            }
            break;

        case 'POST':
            if ($action === 'delete' && isset($_GET['id'])) {
                // Delete album
                if ($album->delete($_GET['id'])) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Album deleted successfully']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Failed to delete album']);
                }
            } elseif ($action === 'addTrack' && isset($_GET['album_id'])) {
                // Add track to album
                $data = json_decode(file_get_contents("php://input"), true);
                
                if (!empty($data['title'])) {
                    if ($album->addTrack($_GET['album_id'], $data)) {
                        http_response_code(201);
                        echo json_encode(['success' => true, 'message' => 'Track added successfully']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Failed to add track']);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid data. Track title is required.']);
                }
            } else {
                // Create new album
                $data = json_decode(file_get_contents("php://input"), true);
                
                if (!empty($data['title']) && !empty($data['release_year'])) {
                    if ($album->create($data)) {
                        http_response_code(201);
                        echo json_encode(['success' => true, 'message' => 'Album created successfully']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Failed to create album']);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid data. Title and release year are required.']);
                }
            }
            break;

        case 'PUT':
            if (isset($_GET['id'])) {
                if ($action === 'updateTrack' && isset($_GET['track_id'])) {
                    // Update track
                    $data = json_decode(file_get_contents("php://input"), true);
                    
                    if (!empty($data['title'])) {
                        if ($album->updateTrack($_GET['track_id'], $data)) {
                            http_response_code(200);
                            echo json_encode(['success' => true, 'message' => 'Track updated successfully']);
                        } else {
                            http_response_code(500);
                            echo json_encode(['success' => false, 'message' => 'Failed to update track']);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Invalid data. Track title is required.']);
                    }
                } else {
                    // Update album
                    $data = json_decode(file_get_contents("php://input"), true);
                    
                    if (!empty($data['title']) && !empty($data['release_year'])) {
                        if ($album->update($_GET['id'], $data)) {
                            http_response_code(200);
                            echo json_encode(['success' => true, 'message' => 'Album updated successfully']);
                        } else {
                            http_response_code(500);
                            echo json_encode(['success' => false, 'message' => 'Failed to update album']);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Invalid data. Title and release year are required.']);
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Album ID is required']);
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                if ($action === 'deleteTrack' && isset($_GET['track_id'])) {
                    // Delete track
                    if ($album->deleteTrack($_GET['track_id'])) {
                        http_response_code(200);
                        echo json_encode(['success' => true, 'message' => 'Track deleted successfully']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Failed to delete track']);
                    }
                } else {
                    // Delete album
                    if ($album->delete($_GET['id'])) {
                        http_response_code(200);
                        echo json_encode(['success' => true, 'message' => 'Album deleted successfully']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Failed to delete album']);
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Album ID is required']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
