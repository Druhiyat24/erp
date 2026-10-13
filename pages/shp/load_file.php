<?php
include "../../include/conn.php";

session_start();

$id = $_POST['id_h'];

$q = mysqli_query($conn_li,"SELECT * FROM memo_file WHERE id_h = '$id' AND status != 'CANCEL'");

/* Nama fisik file yang dipakai di URL (upload/<...>). Selalu di-rawurlencode():
   upload baru tersimpan di disk dengan '%' literal (mis. "a%20b.pdf"), jadi '%'
   harus jadi '%25' di URL; kalau tidak, Apache men-decode %20 jadi spasi dan 404. */
if (!function_exists('memo_file_url_name')) {
  function memo_file_url_name($file_name)
  {
    return rawurlencode($file_name);
  }
}

while($d = mysqli_fetch_array($q)){
    $url_name = memo_file_url_name($d['file_name']);
    $display_name = rawurldecode($d['file_name']);
    echo "<div>
            <a href='upload/$url_name' target='_blank'>
            $display_name
            </a>
          </div>";
}
?>
