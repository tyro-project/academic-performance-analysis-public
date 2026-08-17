<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('POST');
require_auth(['admin', 'faculty']);
$body      = get_json_body();
$studentid = $body['StudentId'] ?? null;
$subjectid = $body['SubjectId'] ?? null;
$classid   = $body['ClassId'] ?? null;
$marks     = $body['marks'] ?? null;
$grades    = $body['Grades'] ?? null;
$semester  = $body['Semester'] ?? null;
if (!$studentid || !$subjectid || !$classid) {
    json_error('Student, Subject and Class are required', 400);
}
$db   = get_db();
$stmt = $db->prepare("INSERT INTO resultdata (StudentId, SubjectId, ClassId, marks, Grades, Semester) VALUES (:sid, :subid, :cid, :marks, :grades, :semester)");
$stmt->execute([':sid' => $studentid, ':subid' => $subjectid, ':cid' => $classid, ':marks' => $marks, ':grades' => $grades, ':semester' => $semester]);
json_response(['id' => $db->lastInsertId()], 'Result added successfully', 201);
