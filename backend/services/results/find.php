<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
$db        = get_db();
$studentid = $_GET['student_id'] ?? null;
$semester  = $_GET['semester'] ?? null;
if (!$studentid) json_error('Student ID required', 400);
$sql    = "SELECT rd.*, sd.StudentName, sub.SubjectName, cd.ClassName
    FROM resultdata rd
    JOIN studentdata sd ON rd.StudentId = sd.StudentId
    JOIN subjectdata sub ON rd.SubjectId = sub.id
    JOIN classdata cd ON rd.ClassId = cd.id
    WHERE rd.StudentId = :sid";
$params = [':sid' => $studentid];
if ($semester) {
    $sql .= " AND rd.Semester = :semester";
    $params[':semester'] = $semester;
}
$stmt = $db->prepare($sql);
$stmt->execute($params);
json_response($stmt->fetchAll(), 'Results retrieved');
