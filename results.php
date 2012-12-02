<?php
session_start();
include('dbengine.php');

$database = Database_Factory::create(1);
$database->connect($_SESSION['url'],$_SESSION['username'],$_SESSION['password'],$_SESSION['database']);
$results_string;

if(isset($_SESSION['query'])){
    $_SESSION['results'] = $database->run_query($_SESSION['query']);
    if(is_string($_SESSION['results'])){
        $results_string .= "<h2 style='color:red;'>".$_SESSION['results']."</h2>";
    }else{
        $results = $_SESSION['results'];
        $field_count = $results->field_count;
        $fields = $results->fetch_fields();

        $results_string .= '<table border=1><tr style="color:green;">';
        foreach($fields as $field){
            $results_string .= '<td>'.$field->name.'</td>';
        }
        $results_string .= '</tr>';
        while($row = $results->fetch_row()){
            $results_string .= '<tr>';
            for($i = 0; $i < $field_count; $i++){
                $results_string .= '<td>'.$row[$i]."</td>";
            }
            $results_string .= '</tr>';
        }
        $results_string .= '</table>';
    }
    unset($_SESSION['query']);
}else if(isset($_SESSION['update'])){
    if(preg_match('/^insert into shipments/',strtolower($_SESSION['update'])) || preg_match('/^update shipments/',strtolower($_SESSION['update']))){ 
        $supplier_snum;
        $updateSuppliers = false;    

       $first = strpos($_SESSION['update'],"(")+1;
        $last = strpos($_SESSION['update'],")");

 
        $temp = substr($_SESSION['update'],$first,$last);
        $temp = preg_replace('/\)/', '',$temp);
        $temp = preg_replace('/\;/','',$temp);
        $temp = preg_replace('/\'/','',$temp);
        $temp = preg_replace("/\s+/","",$temp);
        $values = explode(",",$temp);
        foreach($values as $val){
            if($val >= 100){
                $updateSuppliers = true;
            }else if(preg_match('/^S/',$val)){
                $supplier_snum = $val;
            }
        }
    }


    $_SESSION['results'] = $database->run_update($_SESSION['update']);
    if(is_numeric($_SESSION['results'])){
        $results_string .= '<h2 style="color:green">'.$_SESSION['results'].' number of rows have been successfully updated!</h2>';
        if($updateSuppliers){
            $snums = $database->run_query("select DISTINCT(suppliers.snum) from suppliers join shipments on suppliers.snum = shipments.snum and shipments.quantity >= 100");
            $csl_snums .= "'".$supplier_snum."'";
            while($row = $snums->fetch_row()){
                for($i = 0; $i < $snums->field_count; $i++){
                    $csl_snums .= ",'".$row[0]."'";
                }
            }
            $blah = $database->run_update("UPDATE suppliers set status = (status+ 5) where snum IN (".$csl_snums.")");
            $results_string .='<br /><h2 style="color:green"> Business Logic Dectected! '.$blah.'</h2>';
        }
    }else{
        $results_string .= '<h2 style="color:red">'.$_SESSION['results'].'</h2>';
    }
    unset($_SESSION['update']);
}else if(isset($_POST['logout'])){
    session_destroy();
    header('Location:index.php');
}
?>
<!DOCTYPE>
<html>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
function goBack()
  {
  window.history.back()
  }
</script>
</head>
<body>
    <header style='text-align:center;'>
        <h1>CNT 4714 - Suppliers, Parts, Jobs, Shipments Database CLient</h1>
    </header>
<div id='middle' style='width: auto;height: 1000px; '>
    <div id='center' style='width: 1000px;margin-left: auto;margin-right: auto;'>
        <hr />
        <div id='l-middle' style='float: left; text-align:center;'>
            <b>Welcome back</b><br />
            <?php echo $_SESSION['username']; ?><br />
            <form action="results.php" method="post">
                <input type='submit' value='Logout' id='logout' name='logout'/>
            </form>
        </div><!--END OF L-MIDDLE-->
        <div id='inner_center' style='width:500px;margin-left: auto; margin-right:auto;'>
            <div id='sql_results'>
                <?php echo $results_string?>
                <a href='sql.php'> Back to SQL </a>
            </div><!--END OF SQL RESULTS-->
        </div><!--END OF INNER CENTER-->
        <div id='r-middle' style='float: right;'>
        </div> <!--END OF R-MIDDLE-->
        <div id='footer' style='clear:both;'>
            <hr />
        </div><!--END OF FOOTER-->
    </div> <!--END OF CENTER-->
</div> <!--END OF MIDDLE-->
</body>
</html>
