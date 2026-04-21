<?php
include "../../include/conn.php";

session_start();

$id = $_POST['id_h'];

$q = mysqli_query($conn_li,"SELECT * FROM memo_file WHERE id_h = '$id' AND status != 'CANCEL'");

while($d = mysql_fetch_array($q)){
    echo "<div>
            <a href='upload/$d[file_name]' target='_blank'>
            $d[file_name]
            </a>
          </div>";
}
?>
