<?php

require_once 'config.php';

//Khai báo các biến rỗng
$id =  $name = $address = $class = "";
$name_err = $address_err = $class_err = "";

// Lấy dữ liệu từ form
if($_SERVER["REQUEST_METHOD"] == "POST") {
//Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var(trim($_POST["name"]), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
        $name_err = 'Please enter valid name.';
    } else {
        $name = $input_name;
    }

//Validate address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = 'Please enter a address.';
    } else {
        $address = $input_address;
    }

//Validate salary
    $input_class = trim($_POST["class"]);
    if (empty($input_class)) {
        $class_err = "Please enter a class.";
    } elseif (!ctype_digit($input_class)) {
        $class_err = 'Please enter a positive integer value';
    } else {
        $class = $input_class;
    }

//Kiểm tra lỗi sau khi nhập vào database
    if (empty($name_err) && empty($address_err) && empty($class_err)) {
        //Câu lệnh prepare insert
        $sql = "INSERT INTO employees(id,name, address, class) VALUES (?, ?, ?,?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            $param_id = $id;
            $param_name = $name;
            $param_address = $address;
            $param_class = $class;
            mysqli_stmt_bind_param($stmt, "isss", $param_id ,$param_name, $param_address, $param_class);

            //Truyền vào các kiểu dữ liệu


            if (mysqli_stmt_execute($stmt)) {
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Create Record</h2>
                </div>
                <p>Please fill in this form and submit to add employee record to database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                        <span class="help-block"><?php echo $address_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($class_err)) ? 'has-error' : ''; ?>">
                        <label>Salary</label>
                        <input type="text" name="salary" class="form-control" value="<?php echo $class; ?>">
                        <span class="help-block"><?php echo $class_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-primary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>