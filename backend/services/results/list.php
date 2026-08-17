<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'faculty', 'department']);
$db      = get_db();
$classid = $_GET['class_id'] ?? null;
$sql     = "SELECT rd.*, sd.StudentName, sub.SubjectName, cd.ClassName
    FROM resultdata rd
    JOIN studentdata sd ON rd.StudentId = sd.StudentId
    JOIN subjectdata sub ON rd.SubjectId = sub.id
    JOIN classdata cd ON rd.ClassId = cd.id
    WHERE 1=1";
$params = [];
if ($classid) {
    $sql .= " AND rd.ClassId = :class_id";
    $params[':class_id'] = $classid;
}
$sql .= " ORDER BY sd.StudentName ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
json_response($stmt->fetchAll(), 'Results retrieved');
