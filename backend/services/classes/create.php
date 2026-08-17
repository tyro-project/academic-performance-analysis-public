<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body    = get_json_body();
$name    = trim($body['ClassName'] ?? '');
$numeric = trim($body['ClassNameNumeric'] ?? '');
$section = trim($body['Section'] ?? '');
if (empty($name)) json_error('Class name is required', 400);
$db   = get_db();
$stmt = $db->prepare("INSERT INTO classdata (ClassName, ClassNameNumeric, Section) VALUES (:name, :numeric, :section)");
$stmt->execute([':name' => $name, ':numeric' => $numeric, ':section' => $section]);
json_response(['id' => $db->lastInsertId()], 'Class created successfully', 201);
