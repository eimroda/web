<?php
    session_start();
    if ($_SESSION['user_id'] == null){
        header("location: index.php");
    }
    if(isset($_POST['student-id'])){
        $student_id = $_POST['student-id'];
        $student_name = $_POST['student-name'];
    }else{
        $json = $_SESSION['student_json'];
        $student_id = $json[0]['stud_id'];
        $student_name = $json[0]['stud_name'];
    }


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Evaluation System</title>
  </head>
  <body>
    <div class="container mt-5">
        <h2><?php echo $student_name; ?></h2>
        <div class="row">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th class="table-dark">Code</th>
                        <th class="table-dark">Subject Title</th>
                        <th class="table-dark">Grade</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    $json_subjects = retrieveSubjects($student_id);
                    if ($json_subjects != "0"){
                        $current_sy = "";
                        foreach($json_subjects as $key => $value){
                            if ($current_sy != $value['sy_name']){
                                $current_sy = $value['sy_name'];
                                echo "<tr>";
                                echo "<td colspan='3'>$current_sy</td>";
                                echo "</tr>";

                                $current_sem = $value['sem_semester'];
                                echo "<tr>";
                                echo "<td colspan='3'>$current_sem</td>";
                                echo "</tr>";
                            }
                            if ($current_sem != $value['sem_semester']){
                                echo "<tr>";
                                echo "<td colspan='3'>$current_sem</td>";
                                echo "</tr>";
                            }

                            echo "<tr>";
                            echo "<td>".$value['subj_code']."</td>";
                            echo "<td>".$value['subj_title']."</td>";
                            echo "<td>".$value['studSubj_grade']."</td>";
                            echo "</tr>";
                        }
                    }
                  ?>  

                </tbody>
            </table>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>  
</body>
</html>

<?php
    function retrieveSubjects($student_id){
        $url = $_SESSION['main_url']."studentsubjectgrade.php";
        $data = array("studId"=>$student_id);
        $json = json_encode(($data));
        $operation = "getSubjectsByStudent";
    
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