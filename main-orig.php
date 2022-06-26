<?php
    session_start();
    if ($_SESSION['user_id'] == null){
        header("location: index.php");
    }

    //get the json string passed for the student record
    $json = $_SESSION['student_json'];
    $student_id = $json[0]['stud_id'];
    $student_name = $json[0]['stud_name'];
    $school_id = $json[0]['stud_schoolId'];
    $curriculum_id = $json[0]['stud_curriculumId'];
    $curriculum_name = $json[0]['cur_name'];
    $year_level_json = get_student_year_level($student_id, $curriculum_id);
    $year_level_id = $year_level_json['yrLvl_id']; 
    $year_level_name = $year_level_json['yrLvl_name']; 
    $major_id = $json[0]['stud_majorId'];
    $course_id = $json[0]['stud_courseId'];
    $semester_id="";
    $sy_id = "";
    $contact_number = $json[0]['stud_contact'];

    //check if the calling page is itself
    if(isset($_POST['year_level'])){
        $year_level_id = $_POST['year_level'];
        // $semester_id = $_POST['semester-id'];
        // $semester_name = $_POST['semester-name'];
        $major_id = $_POST['major'];
    }
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script type='text/javascript' src='http://code.jquery.com/jquery-1.10.1.js'></script>
    <script type='text/javascript'>
        $(window).load(function(){
            $(function () {
                $("table#tblsubjects").on("click", ".btn-remove", function () {
                    //remove the row/subject
                    $(this).closest('tr').remove();
                    
                    //update total units
                    var TotalValue = 0;
                    $("tr #units").each(function(index,value){
                        currentRow = parseFloat($(this).text());
                        TotalValue += currentRow
                    });
                    document.getElementById('total').innerHTML = TotalValue;
                });
            });
            
            $(function () {
                $("table#tblsubjects").on("click", ".btn-print", function () {
                    //convert table to array
                    let m_array = Array.from(document.querySelectorAll('tbody tr')).map(
                    (tr) => {
                        return Array.from(tr.children).map(
                        (cell) => cell.textContent.trim()
                        );
                    });
                    s = "";
                    n = m_array.length - 4; //there are 4 extr rows in the table
                    arr = [];
                    for(i=0;i<n;i++){
                        if(i==0){s = m_array[i][0];
                        }else{s = s + "," + m_array[i][0];}
                        arr.push(m_array[i])
                    }
                    const myJSON = JSON.stringify(arr);
                    $("#finalsubjects").val(myJSON);
                    targetPage = "printpdf.php";
                    document.getElementById("evaluation-form").action = targetPage;
                    document.getElementById("evaluation-form").submit();

                });
            });

            $(function () {
                $("div#evaluate-div").on("click", ".btn-evaluate", function () {
                    thisPage = "main.php";
                    document.getElementById("evaluation-form").action = thisPage;
                    document.getElementById("evaluation-form").target = "_self";
                    document.getElementById("evaluation-form").submit();
                    //alert("ambot!");
                });
            });

            $(function(){
                $("#btn-display-subjects").click(function(){
                    thisPage = "studentsubjects.php";
                    document.getElementById("evaluation-form").action = thisPage;
                    document.getElementById("evaluation-form").target = "_blank";
                    document.getElementById("evaluation-form").submit();
                });
            });
        });

    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#btn-update-contact-number").click(function(){
                var contact_number = $("#contact-number").val();
                var student_id = $("#student-id").val();
                var operation = "updateStudentContactNumber";
                const mymap = new Map([
                    ['id',student_id],
                    ['contactNo', contact_number]
                ]);
                const obj = Object.fromEntries(mymap);
                json = JSON.stringify(obj);
                $.ajax({
                    url:"<?php echo $_SESSION['main_url'];?>studentregistration.php",
                    method:'POST',
                    data:{
                        operation:operation,
                        json:json
                    },
                    success:function(data){
                        alert("Contact Number has been updated!");
                    }
                });
            });

            $("#btn-update-major").click(function(){
                var major_id = $("#major").val();
                var student_id = $("#student-id").val();
                var operation = "updateStudentMajor";
                const myMap = new Map([
                    ['id',student_id],
                    ['majorId', major_id]
                ]);
                const obj = Object.fromEntries(myMap);
                json = JSON.stringify(obj);
                $.ajax({
                    url:"<?php echo $_SESSION['main_url'];?>studentregistration.php",
                    method:'POST',
                    data:{
                        operation:operation,
                        json:json
                    },
                    success:function(data){
                        alert("Major/Track has been updated!");
                    }
                });
            });

        });
    </script>
    <title>Evaluation System</title>
</head>
<body>
    <div class="container mt-2">
        <div class="col-md-12" >
            <form class="row g-1" target="_blank" method="post" name="evaluation-form" id="evaluation-form">
                <input type="hidden" name="curriculum_id" value="<?php echo $curriculum_id; ?>">
                <input type="hidden" name="finalsubjects" id="finalsubjects" value="">
                <input type="hidden" name="student-id" id="student-id" value="<?php echo $student_id;?>">
                <input type="hidden" name="student-name" id="student-name" value="<?php echo $student_name;?>">
                <h2>Evaluation System</h2>
                <div class="col-md-3">
                    <label for="school_year" class="form-label text-primary">School Year</label>
                    <?php
                        $json = get_lookup_tables();
                        $sy = $json[0]['school_year'];
                        $sy_id = $sy[0]['sy_id'];
                        $sy_name = $sy[0]['sy_name'];
                        $_SESSION["sy_name"] = $sy_name;

                        echo "<input type='hidden' name='school-year' id='school-year' value='$sy_name'>";
                        echo "<label class='form-control' id='lbl-school-year'>$sy_name</label>";
                    ?>
                </div>
                <div class="col-md-3">
                    <label for="semester" class="form-label text-primary" id="hehehe">Semester</label>
                    <?php
                        $sem = $json[0]['semester'];
                        //get the top which is the current sem
                        $semester_id = $sem[0]['sem_id'];
                        $semester_name = $sem[0]['sem_semester'];    
                        $_SESSION['sem_name'] = $semester_name;    
                        echo "<input type='hidden' name='semester-name' id='semester-name' value='$semester_name'>";
                        echo "<input type='hidden' name='semester-id' id='semester-id' value='$semester_id'>";
                        echo "<label class='form-control' id='lbl-semester-name'>$semester_name</label>";
                    ?>
                </div>
                <div class="col-md-6">
                    <label for="course" class="form-label text-primary">Course</label>
                    <?php
                        $course = $json[0]['course'];
                        $course_id = $course[0]['crs_id'];
                        $course_name = $course[0]['crs_name'];
                        echo "<input type='hidden' name='course-name' id='course-name' value='$course_name'>";
                        echo "<label class='form-control' id='lbl-course-name'>$course_name</label>";
                    ?>
                </div>
                <div class="col-md-3">
                    <label for="school_id" class="form-label text-primary">Student ID</label>
                    <label class="form-control" name="school_id" id="school_id"><?php echo $school_id;?></label>
                </div>
                <div class="col-md-3">
                    <label for="student_name" class="form-label text-primary">Student Name</label>
                    <label class="form-control" name="student_name" id="student_name"><?php echo $student_name; ?></label>
                </div>
                <div class="col-md-3">
                    <label for="year_level_name" class="form-label text-primary">Year Level</label>
                    <!-- <label class="form-control" name="year_level_name" id="year_level_name"><?php echo $year_level_name; ?></label> -->
                    <select class="form-select md-form" name="year_level" id="year_level">
                        <?php
                            $yr = $json[0]['year_level'];
                            foreach ($yr as $key => $value) {
                                $sel="";
                                if ($value['yrLvl_id'] == $year_level_id){$sel = "selected";}
                                echo "<option value='".$value['yrLvl_id']."' $sel>".$value['yrLvl_name']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="curriculum_name" class="form-label text-primary">Curriculum</label>
                    <label class="form-control" name="curriculum_name" id="curriculum_name"><?php echo $curriculum_name ; ?></label>
                </div>
                <div class="col-md-6">
                    <label for="major" class="form-label text-primary">Major/Track</label>
                    <select class="form-select md-form" name="major" id="major">
                        <?php
                            $major_json = get_all_majors($course_id);
                            foreach ($major_json as $row) {
                                $sel="";
                                if ($row['maj_id'] == $major_id){$sel="selected";}
                                echo "<option value='".$row['maj_id']."' ".$sel.">".$row['maj_name']."</option>";
                            }?>
                    </select>
                </div>
                <div class="col-md-3 mt-3" id="evaluate-div">
                    <br/>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-update-major" >Update</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-display-subjects">Display Subjects</button>
                </div>
                <div class="col-md-3 mt-3" id="evaluate-div">
                    <br/>
                    <button type="button" class="btn btn-success col-12 btn-evaluate" >Evaluate</button>
                </div>
                <div class="col md-12">
                    <br/>
                    <table class="table table-striped" id="tblsubjects">
                        <thead class="thead-dark">
                            <tr>
                                <th class="table-dark">Code</th>
                                <th class="table-dark">Subject Title</th>
                                <th class="table-dark">Units</th> <!-- class="table-info" -->
                                <th class="table-dark"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //get evaluation
                            $json_evaluation = evaluate($student_id,$course_id,$major_id,$curriculum_id, $sy_id, $semester_id, $year_level_id);
                            $total_units = 0;
                            foreach ($json_evaluation as $key => $value) {
                                echo "<tr>";
                                echo "<td style='display:none'>".$value['subjectId']."</td>";
                                echo "<td>".$value['subjectCode']."</td>";
                                echo "<td>".$value['subjectTitle']."</td>";
                                echo "<td id='units'>".$value['units']."</td>";
                                echo "<td>";
					            echo '<button title="" type="button" class="btn btn-outline-danger btn-sm btn-remove">Remove</button>';
				                echo "</td>";
                                echo "</tr>";
                                $total_units += $value['units'];
                            }
                            echo "<tr>";
                            echo "<td>&nbsp;</td>";
                            echo "<td class='text-end fw-bold'>Total Units</td>";
                            echo "<td class='fw-bold' id='total'>$total_units</td>";
                            echo "</tr>";
                            ?>
                            <tr>
                                <td colspan="2">
                                    <label for="remarks">Remarks</label><br/>
                                    <textarea class="form-control" name="remarks" ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="contact-number">Contact Number</label><br/>
                                    <input type="text" class="form-control" name="contact-number" id="contact-number" value="<?php echo $contact_number;?>"></input>
                                </td>
                                <td>
                                    <br/>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-update-contact-number">Update</button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <button type="button" class="btn btn-primary btn-print" name="btn-print">Print</button>
                                    <a href="selectstudent.php">Evaluate Another Student</a>
                                </td>        
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="col md-12">
                    <div class="col md-6" id="printdiv">
                        <button type="button" class="btn btn-primary print">Print</button>
                    </div>
                </div> -->



            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>  
</body>
</html>

<?php
    function evaluate($student_id, $course_id, $major_id, $curriculum_id, $sy_id, $sem_id, $yl_id){
        $url = $_SESSION['main_url']."evaluation.php";
        $data = array("studId"=>$student_id,
                    "courseId"=>$course_id,
                    "majorId"=>$major_id,
                    "curriculumId"=>$curriculum_id,
                    "syId"=>$sy_id,
                    "semesterId"=>$sem_id,
                    "yearLevelId"=>$yl_id);
        $params[] = $data;
        $json = json_encode(($params));
        $operation = "evaluate";
    
        $post_data = array("operation"=>$operation, "json"=>$json);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function get_lookup_tables(){
        $url = $_SESSION['main_url']."commons.php";
        $data = array("courseId"=>$_SESSION["user_course_id"]);
        $params[] = $data;
        $json = json_encode(($params));
        $operation = "getLookUpTablesForEvaluation";
    
        $post_data = array("operation"=>$operation, "json"=>$json);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function get_student_year_level($student_id, $curriculum_id){
        $url = $_SESSION['main_url']."evaluation.php";
        $data = array("studId"=>$student_id, "curId"=>$curriculum_id);
        $params[] = $data;
        $json = json_encode(($params));
        $operation = "getStudentYearLevel";
    
        $post_data = array("operation"=>$operation, "json"=>$json);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    function get_all_majors($course_id){
        $url = $_SESSION['main_url']."coursemajor.php";
        $data = array("courseId"=>$course_id);
        $params[] = $data;
        $json = json_encode(($params));
        $operation = "getAllMajorsByCourse";
    
        $post_data = array("operation"=>$operation, "json"=>$json);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);

    }
?>