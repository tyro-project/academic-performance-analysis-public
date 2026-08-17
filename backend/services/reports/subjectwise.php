<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'department', 'faculty']);
$db   = get_db();
$stmt = $db->query("SELECT sub.SubjectName,
    COUNT(rd.id) as total_results,
    SUM(CASE WHEN rd.Grades != 'U' THEN 1 ELSE 0 END) as passed,
    SUM(CASE WHEN rd.Grades = 'U' THEN 1 ELSE 0 END) as failed
    FROM resultdata rd
    JOIN subjectdata sub ON rd.SubjectId = sub.id
    GROUP BY sub.id, sub.SubjectName ORDER BY sub.SubjectName");
json_response($stmt->fetchAll(), 'Subjectwise report retrieved');
