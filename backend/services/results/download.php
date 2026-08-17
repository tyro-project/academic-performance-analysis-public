<?php
require_once __DIR__ . '/../../bootstrap.php';
method_required('GET');
require_auth(['admin', 'faculty', 'department', 'student']);

$studentid = $_GET['student_id'] ?? null;
if (!$studentid) json_error('Student ID required', 400);

$db   = get_db();
$stmt = $db->prepare("SELECT rd.*, sd.StudentName, sub.SubjectName, cd.ClassName
    FROM resultdata rd
    JOIN studentdata sd ON rd.StudentId = sd.StudentId
    JOIN subjectdata sub ON rd.SubjectId = sub.id
    JOIN classdata cd ON rd.ClassId = cd.id
    WHERE rd.StudentId = :sid ORDER BY rd.Semester ASC");
$stmt->execute([':sid' => $studentid]);
$results = $stmt->fetchAll();

if (empty($results)) json_error('No results found', 404);

$student_name = $results[0]['StudentName'];
$class_name   = $results[0]['ClassName'];

// Generate PDF with TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Tyro Academic');
$pdf->SetTitle('Result - ' . $student_name);
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Academic Performance Report', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'Student: ' . $student_name, 0, 1);
$pdf->Cell(0, 8, 'Class: ' . $class_name, 0, 1);
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(70, 8, 'Subject', 1, 0, 'C');
$pdf->Cell(40, 8, 'Marks', 1, 0, 'C');
$pdf->Cell(40, 8, 'Grade', 1, 0, 'C');
$pdf->Cell(40, 8, 'Semester', 1, 1, 'C');

// Table rows
$pdf->SetFont('helvetica', '', 10);
foreach ($results as $r) {
    $pdf->Cell(70, 7, $r['SubjectName'], 1);
    $pdf->Cell(40, 7, $r['marks'], 1, 0, 'C');
    $pdf->Cell(40, 7, $r['Grades'], 1, 0, 'C');
    $pdf->Cell(40, 7, $r['Semester'], 1, 1, 'C');
}

// Stream PDF — override Content-Type set in bootstrap
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="result_' . $studentid . '.pdf"');
$pdf->Output('result_' . $studentid . '.pdf', 'D');
exit;
