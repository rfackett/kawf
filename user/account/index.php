<?php

$uuser= new ForumUser;

if (preg_match("/^\/([0-9]+)\.phtml$/", $PATH_INFO, $regs)) {
    $uuser->find_by_aid((int)$regs[1]);
} else if(empty($PATH_INFO) || $PATH_INFO =="/") {
    $uuser->find_by_cookie();
    Header("Location: http://$SERVER_NAME$SCRIPT_NAME/$uuser->aid.phtml");
    exit;
} else {
    err_not_found("Unknown path");
}

if(!$uuser->valid()) {
    err_not_found("Unknown user");
}

$sql = "select * from f_upostcount where aid = $uuser->aid\n";
$result = mysql_query($sql) or sql_error($sql);
$active=0;
$deleted=0;
$offtopic=0;

if(mysql_num_rows($result)) {
    while($index = mysql_fetch_array($result)) {
	if($index['status'] == "Active") $active+=(int)$index['count'];
	if($index['status'] == "Deleted") $deleted+=(int)$index['count'];
	if($index['status'] == "OffTopic") $offtopic+=(int)$index['count'];
    }
}
?>

<html>
<head>
<title>AudiW0rld.com: Account Information for <?php echo "$uuser->name" ?></title>
<style type="text/css">
<!--
body { font-family: verdana, arial, geneva; font-size: smaller }
td { font-family: verdana, arial, geneva; font-size: smaller }
-->
</style>
</head>

<body bgcolor="#ffffff">

<?php /* echo "$PATH_INFO $regs[1]\n" */ ?>

<h1>Account information</h1>

<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td bgcolor="#99999">
<table width="100%" cellpadding="3" cellspacing="1" border="0">

<tr bgcolor="#D0D0D0">
<td>aid</td>
<td>Name</td>
<td>Shortname</td>
<td>Status</td>
<td>Date of Creation</td>
<!-- <td>E-Mail</td> -->
<td>Total posts</td>
<?php
if($deleted) echo "<td>deleted</td>\n";
if($offtopic) echo "<td>offtopic</td>\n";
?>
</tr>

<?php
  $bgcolor = ($count % 2) ? "#F7F7F7" : "#ECECFF";
  echo "<tr bgcolor=\"$bgcolor\">\n";
  echo "<td>" . $uuser->aid . "</td>\n";
  echo "<td>" . $uuser->name . "</td>\n";
  echo "<td>" . $uuser->shortname . "</td>\n";
  echo "<td>" . $uuser->status . "</td>\n";
  echo "<td>" . $uuser->createdate . "</td>\n";
  /* echo "<td>" . $uuser->email . "</td>\n"; */
  echo "<td>" . ($active+$deleted+$offtopic) . "</td>\n";
  if($deleted) echo "<td>" . $deleted . "</td>\n";
  if($offtopic) echo "<td>" . $offtopic . "</td>\n";
  echo "</tr>\n";
  $count++;
?>

</table></td></tr>
</table>

<h2>Signature</h2>
<?php
echo "<p>\n" . $uuser->signature . "\n</p>\n";
?>
</body>
</html>
