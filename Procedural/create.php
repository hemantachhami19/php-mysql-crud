<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$description = "" ;
$description_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["description"]);
    if(empty($input_name)){
        $name_err = "Cannot add empty task";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid description.";
    } else{
        $name = $input_name;
    }

    // Validate salary
    $input_status = trim($_POST["salary"]);
    if(empty($input_status)){
        $input_status = "Please enter the valid status.";
    } elseif(!$bool = filter_var($input_status, FILTER_VALIDATE_BOOLEAN)){
        $status_err = "Please enter a positive integer value.";
    } else{
        $status = $input_status;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($status_err) && empty($salary_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tasks (description, status) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters$param_address,
                mysqli_stmt_bind_param($stmt, "ss", $param_name,  $param_salary);

            // Set parameters
            $param_name = $name;
            $param_status = $status;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
