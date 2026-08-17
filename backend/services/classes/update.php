<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body    = get_json_body();
$id      = $body['id'] ?? null;
$name    = trim($body['ClassName'] ?? '');
$numeric = trim($body['ClassNameNumeric'] ?? '');
$section = trim($body['Section'] ?? '');
if (!$id || empty($name)) json_error('ID and Class name are required', 400);
$db   = get_db();
$stmt = $db->prepare("UPDATE classdata SET ClassName=:name, ClassNameNumeric=:numeric, Section=:section WHERE id=:id");
$stmt->execute([':name' => $name, ':numeric' => $numeric, ':section' => $section, ':id' => $id]);
json_response(null, 'Class updated successfully');
