<?php
include('includes/appInfo.php');
include('includes/functions.php');
$conn = db_conn();
$date = date("Y-m-d");
$query = "SELECT * FROM AccountLockouts WHERE Date LIKE '$date%' ORDER BY Date DESC";
$result = mysqli_query($conn, $query);	

$query2 = "SELECT 
User, 
COUNT(User)
FROM
AccountLockouts WHERE DATE LIKE '$date%'
GROUP BY User
HAVING COUNT(User) > 1 ORDER BY COUNT(*) DESC";
$result2 = mysqli_query($conn, $query2);

$query3 = "SELECT Count(*) FROM AccountLockouts WHERE Date LIKE '$date%' ORDER BY Date DESC";
$result3 = mysqli_query($conn,$query3);

$query4 = "SELECT 
Office, 
COUNT(Office)
FROM
AccountLockouts WHERE DATE LIKE '$date%'
GROUP BY Office
HAVING COUNT(Office) > 1 ORDER BY COUNT(*) DESC";
$result4 = mysqli_query($conn, $query4);

$query5 = "SELECT 
Title, 
COUNT(Title)
FROM
AccountLockouts WHERE DATE LIKE '$date%'
GROUP BY Title
HAVING COUNT(Title) > 1 ORDER BY COUNT(*) DESC";
$result5 = mysqli_query($conn, $query5);

$query6 = "SELECT 
Caller, 
COUNT(Caller)
FROM
AccountLockouts WHERE DATE LIKE '$date%'
GROUP BY Caller
HAVING COUNT(Caller) > 1 ORDER BY COUNT(*) DESC";
$result6 = mysqli_query($conn, $query6);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Account Lockouts Report</title>
<link rel="stylesheet" href="systems.css" type="text/css" />
</head>
<body>

<div id="masthead">
  <?php
  include("includes/masthead.html");
  ?>
</div>

<div id="content">
  <div class="feature"> 
    <table width="85%"> 
	<tr><th class="spec3" colspan="8">Account Lockouts for <?php echo date("m/d/Y");  ?> </br>Lockouts expire after 30 mins</th></tr>
	<tr><th class="spec">Time</th><th class="spec">User</th><th class="spec">Title</th><th class="spec">Computer</th><th class="spec">Office</th><th class="spec">Pw Last Set</th><th class="spec">AD Path</th></tr>
 <?php
	while($row = mysqli_fetch_assoc($result)) {
	echo "<tr><td class=\"spec2\">" .$row["Time"]. "</td><td class=\"spec2\">" .$row["User"]. "</td><td class=\"spec2\">" .$row["Title"]. "</td><td class=\"spec2\">" .$row["Caller"]. "</td><td class=\"spec2\">" .$row["Office"]. "</td><td class=\"spec2\">" .$row["PwdLastSet"]. "</td><td class=\"spec2\">" .$row["ADLocation"]. "</td></tr>";
	}
    ?>
 </table>
  </div>
</div>
<div id="navBar">
  <div id="search">
  <b>most lockouts</b>
  </div>
<div id="headlines">
<ul>
</br>
<?php 
while ($row = mysqli_fetch_array($result2)) {

  echo "<center>$row[0] - $row[1]</br></center>";

}
echo "</br>";
while ($row = mysqli_fetch_array($result4)) {

  echo "<center>$row[0] - $row[1]</br></center>";

}
echo "</br>";
while ($row = mysqli_fetch_array($result5)) {

  echo "<center>$row[0] - $row[1]</br></center>";

}
echo "</br>";
while ($row = mysqli_fetch_array($result6)) {

  echo "<center>$row[0] - $row[1]</br></center>";

}

?>
</ul>    

  </div>
</div>
<div id="siteInfo">sysMan v<?php echo $_SESSION['version'];?>&nbsp;<?php echo  $_SESSION['copyright'];?></div>
<br />
</body>
</html>