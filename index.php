<?php
    if (isset($_POST['submit_login'])){
        $username = $_POST["username"];
        $user_password = $_POST["password"];
        
        $main_url = "http://localhost/evaluation/webservices/evaluationphp/webservices/";
        $url = $main_url."users.php";
        $data = array("userName"=>$username, "password"=>$user_password);
        $params[] = $data;
        $json = json_encode(($params));
        $operation = "login";
    
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
            echo '<strong>Login Failed!</strong> Invalid username or password.';
            echo '</div>';
        }else{
            $arr = json_decode($response, true);
            $user_id = $arr[0]['usr_id'];

            session_start();
            $_SESSION["user_id"] = $arr[0]['usr_id'];
            $_SESSION["user_username"] = $arr[0]['usr_username'];
            $_SESSION["user_password"] = $arr[0]['usr_password'];
            $_SESSION["user_full_name"] = $arr[0]['usr_fullName'];
            $_SESSION["user_course_id"] = $arr[0]['usr_courseId'];
            $_SESSION['main_url'] = $main_url;
            $_SESSION["user_level"] = $arr[0]['usr_userLevel'];
            $_SESSION["course_name"] = $arr[0]['crs_name'];
            $_SESSION["dept_name"] = $arr[0]['dept_name'];
            $_SESSION["dept_dean"] = $arr[0]['dept_dean'];

            //get the department name, course name and dean's name
            

            header("Location: selectstudent.php");
        }
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
        <div id="form-row" class="row justify-content-center align-items-center">
            <div class="col-md-6 border border-primary rounded p-2" >
                <form class="row g-3" method="post">
                    <h2>Evaluation System</h2>
                    <div class="col-md-12">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                    <div class="col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary" name="submit_login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>  
</body>
</html>
