<?php

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/header.php";
include_once($path);
if (isset($_SESSION["can_authorize"]))
{
    if (!$_SESSION["can_authorize"])
    {
    echo "<br><br><br><h1>Sorry you cannot view this page</h1>";
    exit();
    }
}
else
{
    echo "<br><br><br><h1>Sorry you cannot view this page</h1>";
    exit();
}

//other wise show call P_Sel_Course_View();
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'/admin/functions.php');
$conn=open_conn();
$sql="call P_Sel_Course_View();";
$result = $conn->query($sql) or die($conn->error);
$irow=0;
$htm="";
$htm="<br><br><br><table cellpadding=\"8\" border=\"1\" style=\"border-collapse:collapse;cell-padding:5px;padding-top:50px;\" ><tr>";
while($row = $result->fetch_assoc()) {
    if ($irow==0) {
        foreach ($row as $key => $value) {
            $htm = $htm . "<td>" . $key . "</td>";
        }
    }
    $htm = $htm . "<tr>";
        foreach ($row as $key => $value) {
            $htm = $htm . "<td>" . $value . "</td>";
        }
        $htm = $htm . "</tr>";
    $irow=$irow+1;
    }
close_conn($result);
echo $htm."</tr></table>";


