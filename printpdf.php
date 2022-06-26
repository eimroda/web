<?php
    session_start();
    if ($_SESSION['user_id'] == null){
        header("location: index.php");
    }

    //get the json string passed for the student record
    $json = $_SESSION['student_json'];

    $student_name = $json[0]['stud_name'];
    $school_id = $json[0]['stud_schoolId'];
    $curriculum_name = $json[0]['cur_name'];
    //$year_level_name = $year_level_json['yrLvl_name']; 
    $major_id = $json[0]['stud_majorId'];
    $course_id = $json[0]['stud_courseId'];
    // $semester_id="";
    // $sy_id = "";
    $year_level_name=$_POST['year_level'];
    $contact_number = $_POST['contact-number'];
    $remarks = $_POST['remarks'];

    $course_name = $_SESSION['course_name'];
    $dept_name = $_SESSION['dept_name'];
    $dept_dean = $_SESSION['dept_dean'];
    $sy_name =  $_SESSION['sy_name'];
    $sem_name = $_SESSION['sem_name'];

    //draw the PDF
    ob_start();
    require("fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->cell(0, 5, 'CAGAYAN DE ORO COLLEGE', 0,1,'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(0, 5, 'PHINMA EDUCATION NETWORK', 0,1,'C');
    $pdf->cell(0, 5, 'Office of the Registrar', 0,1,'C');

    $pdf->SetFont("helvetica", "B", 10);
    $pdf->cell(0, 10, 'TEMPORARY REGISTRATION FORM', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    //$pdf->cell(0, 5, 'STUDENT INFORMATION', 1, 1, 'C');
    $pdf->cell(0, 8, 'STUDENT INFORMATION', 1, 1, 'C');
    $pdf->ln(3);

    #FIRST LINE
    $pdf->cell(20, 5, 'NAME', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(90, 5, $student_name, 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(5, 5, 'ID', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(30, 5, $school_id, 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(20, 5, 'YR LEVEL', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(20, 5, $year_level_name, 0, 1, 'L');

    #2ND LINE
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(20, 5, 'COLLEGE', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(90, 5, $dept_name, 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(20, 5, 'MAJOR', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(40, 5, $course_name, 0, 1, 'L');

    #3RD LINE
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(20, 5, 'TYPE', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(90, 5, 'OLD/CONTINUING ', 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(40, 5, 'CURRICULUM YEAR', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(40, 5, $_SESSION["cur_name"], 0, 1, 'L');

    #4TH LINE
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(20, 5, 'CONTACT', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(40, 5, $contact_number, 0, 0, 'L');

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(10, 5, 'SY', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(40, 5, $sy_name, 0, 0, 'L');

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(25, 5, 'SEMESTER', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(40, 5, $sem_name, 0, 1, 'L');
    $pdf->ln(3);

    #TABLE HEADER
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(17, 8, 'CODE', 1, 0, 'C');
    //$pdf->SetFont('helvetica', '', 10);
    $pdf->cell(88, 8, 'DESCRIPTIVE TITLE', 1, 0, 'C');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->cell(10, 8, 'UNITS', 1, 0, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(18, 8, 'SECTION ', 1, 0, 'C');
    //$pdf->SetFont('helvetica', '', 10);
    $pdf->cell(19, 8, 'TIME ', 1, 0, 'C');
    //$pdf->SetFont('helvetica', '', 10);
    $pdf->cell(19, 8, 'DAY ', 1, 0, 'C');
    //$pdf->SetFont('helvetica', '', 10);
    $pdf->cell(19, 8, 'ROOM ', 1, 1, 'C');

    $json_subjects = json_decode($_POST['finalsubjects'], true);
    $total_units = 0;
    foreach ($json_subjects as $key => $value) {
        $pdf->SetFont('helvetica', '', 10);
        $pdf->cell(17, 8, $value[1], 1, 0, 'L');
        //$top = $pdf->GetY();
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->cell(88, 8, $value[2], 1, 0, 'L');
        //$pdf->SetFont('helvetica', '', 10);
        //$pdf->SetY($top);
        //units
        $pdf->cell(10, 8, $value[3], 1, 0, 'C');
        //section
        $pdf->cell(18, 8, '', 1, 0, 'C'); //20
        //time
        $pdf->cell(19, 8, '', 1, 0, 'C'); //20
        //day
        $pdf->cell(19, 8, '', 1, 0, 'C'); //15
        //room
        $pdf->cell(19, 8, '', 1, 1, 'C'); //20
            
        $total_units += $value[3];
    }

    //TABLE FOOTER
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(105, 8, 'TOTAL UNITS ADVISED ', 1, 0, 'R');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(10, 8, $total_units, 1, 0, 'C');
    $pdf->cell(75, 8, '', 1, 1, 'L');

    $pdf->ln(10);
    #FOOTER
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(45, 5, '______________________', 0, 0, 'C');
    $pdf->cell(45, 5, '______________________', 0, 0, 'C');
    $pdf->cell(45, 5, '______________________', 0, 0, 'C');
    $pdf->cell(61, 5, '________________________', 0, 1, 'C');

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(45, 5, $_SESSION["user_full_name"], 0, 0, 'C');
    $pdf->cell(45, 5, $dept_dean, 0, 0, 'C');
    $pdf->cell(45, 5, '', 0, 0, 'C');
    $pdf->cell(61, 5, $student_name, 0, 1, 'C');

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(45, 5, 'Adviser/Evaluator', 0, 0, 'C');
    $pdf->cell(45, 5, 'College Dean', 0, 0, 'C');
    $pdf->cell(45, 5, 'Encoder', 0, 0, 'C');
    $pdf->cell(61, 5, 'Student', 0, 1, 'C');
    $pdf->ln(5);

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(49, 5, "Adviser's Remarks", 0, 1, 'L');
    $remarks = $remarks;
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0,5, $remarks, 0, 'L');
    $pdf->ln(5);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(0, 5, 'DATA PRIVACY STATEMENT', 1, 1, 'C');

    $privacy ="
    The school recognizes its responsibilities under the Republic Act No. 10173, also known as the Data Privacy Act of 2012, with respect to the data collected, recorded, organized, updated or modified, used, shared, retained, retrieved, consulted, consolidated, erased or destructed from students, employees, alumni, athletes, contractors, viitors, partners, and vendors.
        
    The personal dta obtained from the data subject of an individual is entered and stored within the company authorized information and communication system for the period allowed under applicable laws and regtulations, and will only be accessed by the authorized ersonnel. School has institured appropriate organizational, technical and physical security measures to ensure the protection of the collected personal data.
        
    Furthermore, the information collected shall be shared with and made available to the CHED and/or any other interested parties in order to pursue lawful purpose and legitimate interest and comply with legal mandate, and only be used for the purpose of operations and will never be disclosed to anyone without prior consent.
    ";
    $pdf->SetFont('helvetica', '', 8);
    $pdf->MultiCell(0, 3, $privacy, 0, 'L');
    $pdf->ln(5);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(70, 5, 'Conforme: __________________________', 0, 0, 'L');
    $pdf->cell(50, 5, '______________________', 0, 1, 'L');

    $pdf->cell(18, 5, '', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(50, 5, $student_name, 0, 0, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 5, 'DATE', 0, 1, 'R');

    $pdf->Output();
    ob_end_flush(); 
    ?>

