<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$lang = $percent= "";
 $lang_err = $percent_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    

    // Validate address address
    $input_lang = trim($_POST["lang"]);
    if(empty($input_lang)){
        $lang_err = "Please enter a Programming Language.";     
    } else{
        $lang = $input_lang;
    }
    
    // Validate salary
    $input_percent = trim($_POST["percent"]);
    if(empty($input_percent)){
        $percent_err = "Please enter a Percentage.";     
    } else{
        $percent = $input_percent;
    }
    
    // Check input errors before inserting in database
    if(empty($lang_err) && empty($percent_err)){
        // Prepare an update statement
        $sql = "UPDATE lang_stats SET `Programming Language`=?,`Percentage (YoY Change)`=? WHERE Ranking=? ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_lang, $param_percent,$param_id);
            
            // Set parameters
            
            $param_lang = $lang;
            $param_percent = $percent;

            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM lang_stats WHERE Ranking = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                   
                   $lang = $row["Programming Language"];
                   $percent = $row["Percentage (YoY Change)"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5 heading">Update Record</h2>
                    <div class="box"> 
                      <p>Please edit the input values and submit to update the record.</p>
                      <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                       
                        <div class="form-group">
                            <label>Programming Language</label>
                            <textarea name="lang" class="form-control <?php echo (!empty($lang_err)) ? 'is-invalid' : ''; ?>"><?php echo $lang; ?></textarea>
                            <span class="invalid-feedback"><?php echo $lang_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Percentage (YoY)</label>
                            <input type="text" name="percent" class="form-control <?php echo (!empty($percent_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $percent; ?>">
                            <span class="invalid-feedback"><?php echo $percent_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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