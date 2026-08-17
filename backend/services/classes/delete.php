<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body = get_json_body();
$id   = $body['id'] ?? null;
if (!$id) json_error('Class ID required', 400);
$db   = get_db();
$stmt = $db->prepare("DELETE FROM classdata WHERE id = :id");
$stmt->execute([':id' => $id]);
json_response(null, 'Class deleted successfully');
