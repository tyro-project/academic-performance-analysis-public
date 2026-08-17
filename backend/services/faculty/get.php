<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'department']);
$id = $_GET['id'] ?? null;
if (!$id) json_error('Faculty ID required', 400);
$db   = get_db();
$stmt = $db->prepare("SELECT * FROM facultydata WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$row  = $stmt->fetch();
if (!$row) json_error('Faculty not found', 404);
json_response($row, 'Faculty retrieved');
