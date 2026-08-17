<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body    = get_json_body();
$id      = $body['id'] ?? null;
$name    = trim($body['FacultyName'] ?? '');
$code    = trim($body['FacultyCode'] ?? '');
$email   = trim($body['FacultyEmail'] ?? '');
$contact = trim($body['contact'] ?? '');
if (!$id || empty($name)) json_error('ID and Name are required', 400);
$db   = get_db();
$stmt = $db->prepare("UPDATE facultydata SET FacultyName=:name, FacultyCode=:code, FacultyEmail=:email, contact=:contact WHERE id=:id");
$stmt->execute([':name' => $name, ':code' => $code, ':email' => $email, ':contact' => $contact, ':id' => $id]);
json_response(null, 'Faculty updated successfully');
