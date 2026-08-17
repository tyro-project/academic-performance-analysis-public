<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');
require_auth(['admin']);

$body = get_json_body();
$id      = $body['StudentId'] ?? null;
$name    = trim($body['StudentName'] ?? '');
$rollid  = trim($body['RollId'] ?? '');
$email   = trim($body['studentEmail'] ?? '');
$contact = trim($body['contact'] ?? '');
$dob     = trim($body['DOB'] ?? '');
$classid = trim($body['ClassId'] ?? '');

if (!$id || empty($name) || empty($rollid)) {
    json_error('Student ID, Name and Roll ID are required', 400);
}

$db   = get_db();
$stmt = $db->prepare("UPDATE studentdata SET 
    StudentName = :name, RollId = :rollid, studentEmail = :email,
    contact = :contact, DOB = :dob, ClassId = :classid
    WHERE StudentId = :id");
$stmt->execute([
    ':name'    => $name,
    ':rollid'  => $rollid,
    ':email'   => $email,
    ':contact' => $contact,
    ':dob'     => $dob,
    ':classid' => $classid,
    ':id'      => $id,
]);

json_response(null, 'Student updated successfully');
