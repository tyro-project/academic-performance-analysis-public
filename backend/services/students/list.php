<?php
require_once __DIR__ . '/../../bootstrap.php';

method_required('GET');
require_auth(['admin', 'faculty', 'department']);

$db      = get_db();
$class   = $_GET['class_id'] ?? null;
$search  = $_GET['search'] ?? null;

$sql    = "SELECT * FROM studentdata WHERE 1=1";
$params = [];

if ($class) {
    $sql .= " AND ClassId = :class_id";
    $params[':class_id'] = $class;
}
if ($search) {
    $sql .= " AND (StudentName LIKE :search OR RollId LIKE :search2)";
    $params[':search']  = "%{$search}%";
    $params[':search2'] = "%{$search}%";
}
$sql .= " ORDER BY StudentName ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
json_response($stmt->fetchAll(), 'Students retrieved');
