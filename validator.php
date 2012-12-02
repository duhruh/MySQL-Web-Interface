<?php
include('dbengine.php');
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

echo $username."\n<br />";
echo $password."<br />";
//need to make sure they are correct if not redirect back to login.
$URL = 'localhost';
$DATABASE = 'project5';

$_SESSION['username'] = $username;
$_SESSION['url'] = $URL;
$_SESSION['password'] = $password;
$_SESSION['database'] = $DATABASE;//Database_Factory::create(1);
//$_SESSION['errors'] = $_SESSION['database']->connect($URL,$username,$password,$DATABASE);
//echo $_SESSION['database']->thing();
header('Location: sql.php');
/*$results = $_SESSION['database']->run_query('select * from shipments');
$field_count = $results->field_count;
$fields = $results->fetch_fields();
echo '<table border=1><tr>';
foreach($fields as $field){
    echo '<td>'.$field->name.'</td>';
}
echo '</tr>';
while($row = $results->fetch_row()){
    echo '<tr>';
    for($i = 0; $i < $field_count; $i++){
        echo '<td>'.$row[$i]."</td>";
    }
    echo '</tr>';
}
echo '</table>';*/
//echo $_SESSION['database']->run_query('select * from shipments')->fetch_row()[0][0];
//header('Location: sql.php');
?>
