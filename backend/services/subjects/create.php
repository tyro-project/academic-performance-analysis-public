<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body = get_json_body();
$name = trim($body['SubjectName'] ?? '');
$code = trim($body['SubjectCode'] ?? '');
if (empty($name)) json_error('Subject name is required', 400);
$db   = get_db();
$stmt = $db->prepare("INSERT INTO subjectdata (SubjectName, SubjectCode) VALUES (:name, :code)");
$stmt->execute([':name' => $name, ':code' => $code]);
json_response(['id' => $db->lastInsertId()], 'Subject created successfully', 201);
