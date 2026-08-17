<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('POST');
require_auth(['admin']);

$body = get_json_body();
$name    = trim($body['StudentName'] ?? '');
$rollid  = trim($body['RollId'] ?? '');
$email   = trim($body['studentEmail'] ?? '');
$contact = trim($body['contact'] ?? '');
$dob     = trim($body['DOB'] ?? '');
$classid = trim($body['ClassId'] ?? '');

if (empty($name) || empty($rollid) || empty($classid)) {
    json_error('Name, Roll ID and Class are required', 400);
}

$db   = get_db();
$stmt = $db->prepare("INSERT INTO studentdata 
    (StudentName, RollId, studentEmail, contact, DOB, ClassId) 
    VALUES (:name, :rollid, :email, :contact, :dob, :classid)");
$stmt->execute([
    ':name'    => $name,
    ':rollid'  => $rollid,
    ':email'   => $email,
    ':contact' => $contact,
    ':dob'     => $dob,
    ':classid' => $classid,
]);

json_response(['id' => $db->lastInsertId()], 'Student created successfully', 201);
