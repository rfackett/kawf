<?php
if (!isset($page))
  $page = $furlroot;

if (!isset($user))
  Header("Location: $page");

/* Open up the SQL database first */
sql_open_readwrite();

$sql = "insert into tracking (tid, aid) values ('" . addslashes($tid) . "', '" . addslashes($user['aid']) . "')";
mysql_db_query("forum_" . addslashes($shortname), $sql) or sql_error($sql);

Header("Location: $page");
?>
