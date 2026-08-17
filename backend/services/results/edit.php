<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin', 'faculty']);
$body   = get_json_body();
$id     = $body['id'] ?? null;
$marks  = $body['marks'] ?? null;
$grades = $body['Grades'] ?? null;
if (!$id) json_error('Result ID required', 400);
$db   = get_db();
$stmt = $db->prepare("UPDATE resultdata SET marks=:marks, Grades=:grades WHERE id=:id");
$stmt->execute([':marks' => $marks, ':grades' => $grades, ':id' => $id]);
json_response(null, 'Result updated successfully');
