<?php 
session_start(); 

if(isset($_POST['query'])){
   if(preg_match('/[[:alnum:]]/',$_POST['sql'])){
       $_SESSION['query'] = $_POST['sql'];
   }else{
       $_SESSION['query'] = 'select * from shipments';
   }
redirect('results.php');
}else if(isset($_POST['update'])){
    if(preg_match('/[[:alnum:]]/',$_POST['sql'])){
        $_SESSION['update'] = $_POST['sql'];
        redirect('results.php');
    }

}else if(isset($_POST['logout'])){
    session_destroy();
    header('Location:index.php');
}
function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
        <script>
            $("#logout").click(function() {
                  //destroy session and redirect
            });
            function showHide(){
                $('#sql').show();
                $('#results').hide();
            }
            window.onload =showHide;
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
                <form method="post" action="sql.php">
                    <input type='submit' value='Logout' id='logout' name='logout'/>
                </form>
            </div><!--END OF L-MIDDLE-->
            <div id='r-middle' style='float: right;'>
                <div id='sql'>
                    <h2>Enter Query</h2>
                    <p> Please enter a valid query or update statement. You may also just press "Submit Query" to select all parts from the database.</p>
                    <form method="post" action="sql.php">
                        <textarea rows='20' cols='70' id='sql' name='sql'></textarea>
                        <table>
                            <tr>
                                <td><input type='submit' value='Submit Query' id='query' name='query'/></td>
                                <td><input type='submit' value='Submit Update' id='update'name='update'/></td>
                                <td><input type='reset' value='Reset Window' /></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id='results'></div>
            </div> <!--END OF R-MIDDLE-->
            <div id='footer' style='clear:both;'>
                <hr />
            </div><!--END OF FOOTER-->
            </div> <!--END OF CENTER-->
        </div> <!--END OF MIDDLE-->


    </body>
</html>
