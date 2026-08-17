<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('GET');
require_auth(['admin', 'faculty', 'department', 'student']);

$id = $_GET['id'] ?? null;
if (!$id) json_error('Student ID required', 400);

$db   = get_db();
$stmt = $db->prepare("SELECT * FROM studentdata WHERE StudentId = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$student = $stmt->fetch();

if (!$student) json_error('Student not found', 404);
json_response($student, 'Student retrieved');
