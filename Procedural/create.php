<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$description = "";
$description_err = "";

$status = "";
$status_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate task description
    $input_description = trim($_POST["description"]);
    if (empty($input_description)) {
        $description_err = "Cannot add empty task";
    } elseif (!filter_var($input_description, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $description_err = "Please enter a valid description.";
    } else {
        $description = $input_description;
    }

    //Validate status
    $input_status = trim($_POST["status"]);
    if(!isset($input_status)){
        $status_err = "Enter the validate status";
    }else{
        $status = filter_var($input_status, FILTER_VALIDATE_BOOLEAN);
        if(is_null($status)){
            $status_err = "Enter the valid status";
        }
    }

    // Check input errors before inserting in database
    if (empty($description_err) && empty($status_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tasks (description, status) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters$param_address,
            mysqli_stmt_bind_param($stmt, "si", $param_name, $param_status);

            // Set parameters
            $param_name = $description;
            $param_status = intval($status);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
