<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'department', 'faculty', 'student']);
$db        = get_db();
$studentid = $_GET['student_id'] ?? null;
if (!$studentid) json_error('Student ID required', 400);
$stmt = $db->prepare("SELECT rd.Semester,
    COUNT(rd.id) as total_subjects,
    SUM(CASE WHEN rd.Grades != 'U' THEN 1 ELSE 0 END) as passed,
    SUM(CASE WHEN rd.Grades = 'U' THEN 1 ELSE 0 END) as failed
    FROM resultdata rd
    WHERE rd.StudentId = :sid
    GROUP BY rd.Semester ORDER BY rd.Semester");
$stmt->execute([':sid' => $studentid]);
json_response($stmt->fetchAll(), 'Studentwise report retrieved');
