<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body = get_json_body();
$id   = $body['id'] ?? null;
$name = trim($body['SubjectName'] ?? '');
$code = trim($body['SubjectCode'] ?? '');
if (!$id || empty($name)) json_error('ID and Subject name are required', 400);
$db   = get_db();
$stmt = $db->prepare("UPDATE subjectdata SET SubjectName=:name, SubjectCode=:code WHERE id=:id");
$stmt->execute([':name' => $name, ':code' => $code, ':id' => $id]);
json_response(null, 'Subject updated successfully');
