<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'faculty', 'department', 'student']);
$id = $_GET['id'] ?? null;
if (!$id) json_error('Subject ID required', 400);
$db   = get_db();
$stmt = $db->prepare("SELECT * FROM subjectdata WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$row  = $stmt->fetch();
if (!$row) json_error('Subject not found', 404);
json_response($row, 'Subject retrieved');
