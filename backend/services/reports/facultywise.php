<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'department']);
$db   = get_db();
$stmt = $db->query("SELECT fd.FacultyName,
    COUNT(DISTINCT fcd.ClassId) as total_classes,
    COUNT(DISTINCT fcd.SubjectId) as total_subjects
    FROM facultydata fd
    LEFT JOIN facultycombinationdata fcd ON fd.id = fcd.FacultyId
    GROUP BY fd.id, fd.FacultyName ORDER BY fd.FacultyName");
json_response($stmt->fetchAll(), 'Facultywise report retrieved');
