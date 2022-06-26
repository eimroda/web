<?php
    session_start();
    if ($_SESSION['user_id'] == null){
        header("location: index.php");
    }

    //get the json string passed for the student record
    $json = $_SESSION['my_json'];

    //print_r($json_subjects);

    $student_name = $json[0]['stud_name'];
    $school_id = $json[0]['stud_schoolId'];
    $curriculum_name = $json[0]['cur_name'];
    //$year_level_name = $year_level_json['yrLvl_name']; 
    $major_id = $json[0]['stud_majorId'];
    $course_id = $json[0]['stud_courseId'];
    $semester_id="";
    $sy_id = "";
    $year_level_name="";
    //check if the calling page is itself
    if(isset($_POST['year_level'])){
        $year_level_id = $_POST['year_level'];
        $semester_id = $_POST['semester'];
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
                $("div#print-div").on("click", ".btn-print", function () {
                    $('#print-div').hide();
                    window.print();
                    $('#print-div').show();
                });
            });
        });

    </script>

  </head>
  <body>
    <div class="container">
        <div class="col md-12">
            <h3 class="text-center">CAGAYAN DE ORO COLLEGE</h3>
            <p  class="text-center">PHINMA EDUCATION NETWORK<br/>Office of the Registrar</p>
            <!-- <p align="center">Office of the Registrar</p> -->
            <h5  class="text-center">TEMPORARY REGISTRATION FORM</h3>
        </div>
        <div class="col md-12 bg-dark"><p class="table-dark text-center fw-bold" >STUDENT INFORMATION</p></div>
        
        <div class="col md-12">
            <table >
                <tr>
                    <td style="width: 10%">NAME</td>
                    <td style="width: 40%" class="fw-bold"><?php echo $student_name;?></td>
                    <td style="width: 10%">ID</td>
                    <td style="width: 15%" class="fw-bold"><?php echo $school_id;?></td>
                    <td style="width: 10%">YR LEVEL</td>
                    <td style="width: 15%" class="fw-bold"><?php echo $year_level_name;?></td>
                </tr>
                <tr>
                    <td style="width: 10%">COLLEGE</td>
                    <td style="width: 40%" class="fw-bold" colspan="2">COLLEGE OF INFORMATION TECHNOLOGY</td>
                    <td style="width: 10%">MAJOR</td>
                    <td style="width: 15%" class="fw-bold" colspan="3">SYS DEV</td>
                </tr>
                <tr>
                    <td style="width: 10%">TYPE</td>
                    <td style="width: 40%" class="fw-bold" colspan="2">OLD/CONTINUING</td>
                    <td style="width: 10%">CURRICULUM</td>
                    <td style="width: 15%" class="fw-bold" colspan="3">2020-2021</td>
                </tr>
                <tr>
                    <td style="width: 10%">CONTACT</td>
                    <td style="width: 40%" class="fw-bold">09128765434</td>
                    <td style="width: 10%">SY</td>
                    <td style="width: 15%" class="fw-bold">2021-2022</td>
                    <td style="width: 10%">SEMESTER</td>
                    <td style="width: 15%" class="fw-bold">FIRST</td>
                </tr>

            </table>
        </div>
        <div class="col md-12">
            <br/>
            <table class="table table-borderless" id="tblsubjects">
                <thead class="thead-dark">
                    <tr>
                        <th>Code</th>
                        <th>Subject Title</th>
                        <th>Units</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $json_subjects = json_decode($_POST['finalsubjects'], true);
                        $total_units = 0;
                        foreach ($json_subjects as $key => $value) {
                            echo "<tr>";
                            echo "<td class='p-10'>".$value[1]."</td>";
                            echo "<td>".$value[2]."</td>";
                            echo "<td id='units'>".$value[3]."</td>";
                            echo "</tr>";
                            $total_units += $value[3];
                        }
                        echo "<tr>";
                        echo "<td>&nbsp;</td>";
                        echo "<td class='text-end fw-bold'>Total Units</td>";
                        echo "<td class='fw-bold' id='total'>$total_units</td>";
                        echo "</tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col" id="print-div">
            <button type="button" class="btn btn-primary btn-print">Print</button>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>  
</body>

<!-- <script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	document.loaded = function(){
		
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.close() },750)
	});
</script> -->

</html>
