<?php
require_once __DIR__ . '/../../bootstrap.php';
$db = get_db();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    require_auth(['admin', 'department', 'faculty']);
    $stmt = $db->query("SELECT fcd.*, fd.FacultyName, cd.ClassName, sd.SubjectName 
        FROM facultycombinationdata fcd
        JOIN facultydata fd ON fcd.FacultyId = fd.id
        JOIN classdata cd ON fcd.ClassId = cd.id
        JOIN subjectdata sd ON fcd.SubjectId = sd.id
        ORDER BY fd.FacultyName ASC");
    json_response($stmt->fetchAll(), 'Combinations retrieved');
}

if ($method === 'POST') {
    require_auth(['admin']);
    $body      = get_json_body();
    $action    = $body['action'] ?? 'create';
    if ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM facultycombinationdata WHERE id = :id");
        $stmt->execute([':id' => $body['id']]);
        json_response(null, 'Combination deleted');
    }
    $stmt = $db->prepare("INSERT INTO facultycombinationdata (FacultyId, ClassId, SubjectId) VALUES (:fid, :cid, :sid)");
    $stmt->execute([':fid' => $body['FacultyId'], ':cid' => $body['ClassId'], ':sid' => $body['SubjectId']]);
    json_response(['id' => $db->lastInsertId()], 'Combination created', 201);
}

json_error('Method not allowed', 405);
