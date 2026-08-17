<?php
require_once __DIR__ . '/../../bootstrap.php';
$db     = get_db();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    require_auth(['admin', 'department', 'faculty']);
    $stmt = $db->query("SELECT scd.*, sd.SubjectName, cd.ClassName 
        FROM subjectcombinationdata scd
        JOIN subjectdata sd ON scd.SubjectId = sd.id
        JOIN classdata cd ON scd.ClassId = cd.id
        ORDER BY sd.SubjectName ASC");
    json_response($stmt->fetchAll(), 'Subject combinations retrieved');
}

if ($method === 'POST') {
    require_auth(['admin']);
    $body   = get_json_body();
    $action = $body['action'] ?? 'create';
    if ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM subjectcombinationdata WHERE id = :id");
        $stmt->execute([':id' => $body['id']]);
        json_response(null, 'Combination deleted');
    }
    $stmt = $db->prepare("INSERT INTO subjectcombinationdata (SubjectId, ClassId) VALUES (:sid, :cid)");
    $stmt->execute([':sid' => $body['SubjectId'], ':cid' => $body['ClassId']]);
    json_response(['id' => $db->lastInsertId()], 'Combination created', 201);
}

json_error('Method not allowed', 405);
