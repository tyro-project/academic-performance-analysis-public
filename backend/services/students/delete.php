<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');
require_auth(['admin']);

$body = get_json_body();
$id   = $body['StudentId'] ?? null;
if (!$id) json_error('Student ID required', 400);

$db   = get_db();
$stmt = $db->prepare("DELETE FROM studentdata WHERE StudentId = :id");
$stmt->execute([':id' => $id]);

json_response(null, 'Student deleted successfully');
