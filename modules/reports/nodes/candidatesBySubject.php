<?php
$courses = Courses::find_by_multiple_uid($_POST['coursesSelected']);

$pdf->SetAutoPageBreak(false);

foreach ($courses AS $course) {
	$confirmed = Students::find_all_confirmed_by_course($course->uid);
	$studentArray = "";


$pdf->SetFont("Times", 'B', 18);

foreach ($confirmed AS $student) {
	if (isset($student->photograph) && $student->photograph != "" && !is_null($student->photograph)) {
		if (file_exists("uploads/" . $student->photograph)) {
			$photoURL = "uploads/" . $student->photograph;
		} else {
			$photoURL = "img/no_user_photo.png";
		}
	} else {
		$photoURL = "img/no_user_photo.png";
	}
	
	$studentArray[] = array(
		'photograph' => $photoURL,
		'surname' => $student->surname,
		'forenames' => $student->forenames,
		'course' => $student->course_code
	);
}

$sets = array_chunk($studentArray, 3);

$pdf->SetFont("Times", 'B', 18);
$pdf->Cell(0, 10, $course->displayName(), 0, 1);
$pdf->Cell(0, 10, "", 0, 1);

$pdf->SetFont("Times", '', 14);
/*
foreach ($confirmed AS $student) {
	$pdf->Image($student->imageURL(),null,null,30);
	$pdf->Cell(90, 5, $student->fullDisplayName(), 0, 1);
}
*/
$rowCounter = 0;

foreach ($sets as $set) {
	if (file_exists($set[0]['photograph'])) {
		$pdf->Cell(65, 45, $pdf->Image($set[0]['photograph'], $pdf->GetX()+ 15, $pdf->GetY(), 45, 45), 0, 0, 'C', false );
	} else {
		$pdf->Cell(65, 45, "", 1, 0, 'C', false);
	}
	if (file_exists($set[1]['photograph'])) {
		$pdf->Cell(65, 45, $pdf->Image($set[1]['photograph'], $pdf->GetX()+ 15, $pdf->GetY(), 45, 45), 0, 0, 'C', false );
	} else {
		$pdf->Cell(65, 45, "", 1, 0, 'C', false);
	}
	if (file_exists($set[2]['photograph'])) {
		$pdf->Cell(65, 45, $pdf->Image($set[2]['photograph'], $pdf->GetX()+ 15, $pdf->GetY(), 45, 45), 0, 1, 'C', false );
	} else {
		$pdf->Cell(65, 45, "", 1, 1, 'C', false);
	}
	
	$pdf->Cell(65, 5, $set[0]['surname'] . ", " . $set[0]['forenames'], 0, 0, 'C', false);
	$pdf->Cell(65, 5, $set[1]['surname'] . ", " . $set[1]['forenames'], 0, 0, 'C', false);
	$pdf->Cell(65, 5, $set[2]['surname'] . ", " . $set[2]['forenames'], 0, 1, 'C', false);
	$pdf->Ln();
	$pdf->Cell(65, 5, $set[0]['photograph'], 0, 0, 'C', false);
	$pdf->Cell(65, 5, $set[1]['photograph'], 0, 0, 'C', false);
	$pdf->Cell(65, 5, $set[2]['photograph'], 0, 1, 'C', false);
	$pdf->Ln();
	
	$rowCounter = $rowCounter + 1;
	
	if ($rowCounter >= '4') {
		$pdf->AddPage();
		$rowCounter = 0;
	}
}


$pdf->AddPage();

} // end foreach on $courses
?>