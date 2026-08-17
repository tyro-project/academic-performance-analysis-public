<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin']);
$body    = get_json_body();
$name    = trim($body['FacultyName'] ?? '');
$code    = trim($body['FacultyCode'] ?? '');
$email   = trim($body['FacultyEmail'] ?? '');
$contact = trim($body['contact'] ?? '');
if (empty($name) || empty($code)) json_error('Name and Faculty Code are required', 400);
$db   = get_db();
$stmt = $db->prepare("INSERT INTO facultydata (FacultyName, FacultyCode, FacultyEmail, contact) VALUES (:name, :code, :email, :contact)");
$stmt->execute([':name' => $name, ':code' => $code, ':email' => $email, ':contact' => $contact]);
json_response(['id' => $db->lastInsertId()], 'Faculty created successfully', 201);
