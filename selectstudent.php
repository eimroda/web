<?php
    session_start();
    if ($_SESSION['user_id'] == null){
        header("location: index.php");
    }

    if (isset($_POST['school_id'])){
        if (!$_POST['school_id'] == null){
            $school_id = $_POST["school_id"];
            $url = $_SESSION['main_url']."studentregistration.php";
            $data = array("schoolId"=>$school_id);
            $params[] = $data;
            $json = json_encode(($params));
            $operation = "getStudentDetailsBySchoolId";
        
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
        
            if (trim($response) == "0"){
                echo '<div class="alert alert-danger">';
                echo '<strong>Invalid Student ID!</strong> Please edit your entry and try again.';
                echo '</div>';
            }else{
                $_SESSION["student_json"] = json_decode($response, true);
                if($_POST['page-to-call'] == "display-subjects"){
                    header("Location: studentsubjects.php");
                }else{
                    header("Location: main.php");
                }
            }
        }
    }

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type='text/javascript' src='http://code.jquery.com/jquery-1.10.1.js'></script>
    <script type="text/javascript">
        $(window).load(function(){
            $(function(){
                $("#btn-open-modal").click(function(){
                    $('#myModal').modal('show'); 
                });

                $("#btn-save-password").click(function(){
                    var currentPassword = $("#input-current-password").val();
                    var newPassword1 = $("#new-password-1").val();
                    var newPassword2 = $("#new-password-2").val();
                    var userId = $("#user-id").val();

                    if (validPassword(currentPassword,newPassword1,newPassword2)){
                        var operation = "changePassword";
                        const myMap = new Map([
                            ['userId',userId],
                            ['newPassword', newPassword1]
                        ]);
                        const obj = Object.fromEntries(myMap);
                        json = JSON.stringify(obj);

                        $.ajax({
                            url: "<?php echo $_SESSION['main_url'];?>users.php",
                            type: 'POST',  // http method
                            data: { operation:operation,
                                json:json },  // data to submit
                            success: function (data, status, xhr) {
                                alert("Password has been successfully changed!");
                                $('#myModal').modal('hide'); 
                            },
                            error: function (jqXhr, textStatus, errorMessage) {
                                    alert('Error : ' + errorMessage);
                            }
                        });
                    }
                });
                //display grade
                $("#btn-display-grade").click(function(){
                    $("#page-to-call").val("display-subjects");
                    document.getElementById("student-form").target="_blank";
                    document.getElementById("student-form").submit();
                });
        
                function validPassword(current, new1, new2){
                    var returnValue = true;
                    if (current != $("#current-password").val()){
                        returnValue = false;
                        alert("Inputted current password is not correct.");
                    }else if(new1 != new2){
                        returnValue = false;
                        alert("Inputted new passwords don't match!");
                    }else if(new1 == "" ){
                        returnValue = false;
                        alert("You must input new password!")
                    }
                    return returnValue;

                }
            });
        });
        
    </script>
    <title>Evaluation System</title>
</head>
<body>
    <div class="container mt-2">
        <form class="row g-1" id="student-form" method="post">
            <input type="hidden" id="current-password" value="<?php echo $_SESSION['user_password']?>">
            <input type="hidden" id="user-id" value="<?php echo $_SESSION['user_id']?>">
            <input type="hidden" id="page-to-call" name="page-to-call" value="">
            <h2>Select Student</h2>
            <div class="row">
                <div class="col-md-4">
                    <label for="school_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" name="school_id" id="school_id" required autofocus>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                <button class="btn btn-primary" type="submit" name="submit">Evaluate</button>
                <button class="btn btn-primary" id="btn-display-grade">Display Subjects Taken</button>
                </div>
            </div>
        </form>
        <br/><br/>
        <div class="col-md-3">
             <button class="btn btn-outline-primary btn-sm" type="button" id="btn-open-modal">Change Password</button>
        </div>

    </div>

    <div class="modal myModal" tabindex="-1" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frm-change-password">
                <div class="row">
                        <div class="col md-6">Current Password</div>
                        <div class="col md-6">
                            <input type="text" class="form-control" id="input-current-password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col md-6">New Password</div>
                        <div class="col md-6">
                            <input type="text" class="form-control" id="new-password-1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col md-6">Repeat Password</div>
                        <div class="col md-6">
                            <input type="text" class="form-control" id="new-password-2">
                        </div>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn-save-password">Save</button>
            </div>
            </div>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>  
</body>
</html>

