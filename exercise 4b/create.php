<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$lang = $percent =  "";
 $lang_err = $percent_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    
    $input_lang = trim($_POST["lang"]);
    if(empty($input_lang)){
        $lang_err = "Please enter a Language.";
    } else{
        $lang = $input_lang;
    }
    
    // Validate address
    $input_percent = trim($_POST["percent"]);
    if(empty($input_percent)){
        $percent_err = "Please enter a Percentage.";     
    } else{
        $percent = $input_percent;
    }
    
    
    // Check input errors before inserting in database
    if(empty($lang_err) && empty($percent_err) ){
        // Prepare an insert statement
        $sql = "INSERT INTO lang_stats ( `Programming Language`, `Percentage (YoY Change)`) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_lang, $param_percent);
            
            // Set parameters
        
            $param_lang = $lang;
            $param_percent = $percent;
           
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        body{
            background-color:rgb(235, 253, 255)
        }
        label{
            font-size:18px;
            font-weight:600;
        }
        .heading{
            font-size:40px;
             color: rgb(0, 0, 43);
        }
        .box{
            background-color: #d8f6ff;
            padding: 30px;
            margin-top: 10%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 heading">Create Record</h2>
                   <div class="box"> 
                      <p>Please fill this form and submit to add language stats record to the database.</p>
                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                       
                        <div class="form-group">
                            <label>Programming Language</label>
                            <input type="text" name="lang" class="form-control <?php echo (!empty($lang_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lang; ?>">
                            <span class="invalid-feedback"><?php echo $lang_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Percentage (YoY Change)</label>
                            <textarea name="percent" class="form-control <?php echo (!empty($percent_err)) ? 'is-invalid' : ''; ?>"><?php echo $percent; ?></textarea>
                            <span class="invalid-feedback"><?php echo $percent_err;?></span>
                        </div>
                       
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                      </form>
                   </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>