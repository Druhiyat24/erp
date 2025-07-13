<?php
$dir = "/upload_files/";
$filename = "backup" . date("YmdHis") . ".sql.gz";

$db_host = "localhost";
$db_username = "root";
$db_password = "gadis030311";
$db_database = "test_bc";

$cmd = "mysqldump -h {$db_host} -u {$db_username} --password={$db_password} {$db_database} | gzip > {$dir}{$filename}";
echo $cmd;

?>