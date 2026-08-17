<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body = get_json_body();
$id   = $body['id'] ?? null;
if (!$id) json_error('Subject ID required', 400);
$db   = get_db();
$stmt = $db->prepare("DELETE FROM subjectdata WHERE id = :id");
$stmt->execute([':id' => $id]);
json_response(null, 'Subject deleted successfully');
