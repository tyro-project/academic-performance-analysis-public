<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'department', 'faculty']);
$db      = get_db();
$classid = $_GET['class_id'] ?? null;
$sql     = "SELECT cd.ClassName,
    COUNT(rd.id) as total_results,
    SUM(CASE WHEN rd.Grades IN ('O','A+','A') THEN 1 ELSE 0 END) as passed,
    SUM(CASE WHEN rd.Grades = 'U' THEN 1 ELSE 0 END) as failed,
    AVG(rd.Grades) as avg_grade
    FROM resultdata rd
    JOIN classdata cd ON rd.ClassId = cd.id
    WHERE 1=1";
$params = [];
if ($classid) {
    $sql .= " AND rd.ClassId = :class_id";
    $params[':class_id'] = $classid;
}
$sql .= " GROUP BY cd.id, cd.ClassName ORDER BY cd.ClassName";
$stmt = $db->prepare($sql);
$stmt->execute($params);
json_response($stmt->fetchAll(), 'Classwise report retrieved');
