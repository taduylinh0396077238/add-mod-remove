<?php
// Include config file
require_once 'config.php';
// define variables and initialize with empty values
$name = $address = $class = "";
$name_err = $address_err = $class_err ="";
// processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err="Please enter a name.";
    }elseif(!filter_var(trim($_POST["name"]),FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s]+$/")))){
        $name_err="Please enter a valid name.";
    }else{
        $name=$input_name;
    }
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";
    }else{
        $address=$input_address;
    }
    // Validate class
    $input_class = trim($_POST["class"]);
    if(empty($input_class)){
        $class_err = "Please enter the class amount.";
    }else{
        $class=$input_class;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($class_err)){
        // Prepare an insert statement
        $sql = "UPDATE students SET name = ?, address = ?, class = ? WHERE id = ?";

        if($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_address, $param_class, $param_id);
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_class = $class;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully.Redirect to landing page
                header("location:index.php");
                exit();
            } else {
                echo "Something went wrong.Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);
        // Prepareaselect statement
        $sql = " SELECT * FROM students WHERE id=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            // Set parameters
            $param_id = $id;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    /*Fetch result row as an associative array.Since the result set
                    contains only one row,we don't need to use while loop*/
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $class = $row["class"];
                } else {
                    // URL doesn't contain valid id.Redirect to error page
                    header("location : error.php");
                    exit();
                }
            } else {
                echo "Oops!Something went wrong.Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
        // close connection
        mysqli_close($link);
    }else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location : error.php");
        exit();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Record</title>
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Update Record</h2>
                </div>
                <p>Please fill the form and submit to add the employee to database</p>
                <form action="<?php echo htmlspecialchars( basename($_SERVER["REQUEST_URI"])) ;?>" method="post">
                    <div class="form-group <?php echo(!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name ;?>">
                        <span class="help-block"><?php echo $name_err;?></span>
                    </div>

                    <div class="form-group <?php echo(!empty($address_err)) ? 'has-error' : ''; ?>">
                        <label>Address</label>
                        <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                        <span class="help-block"><?php echo $address_err;?></span>
                    </div>

                    <div class="form-group <?php echo(!empty($class_err)) ? 'has-error' : ''; ?>">
                        <label>Class</label>
                        <input type="text" name="class" class="form-control" value="<?php echo $class; ?>">
                        <span class="help-block"><?php echo $class_err;?></span>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default"> Cancel </a>
                </form>

            </div>
        </div>
    </div>
</div>
</body>
</html>