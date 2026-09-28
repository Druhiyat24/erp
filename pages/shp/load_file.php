<?php
include "../../include/conn.php";

session_start();

$id = $_POST['id_h'];

$q = mysqli_query($conn_li,"SELECT * FROM memo_file WHERE id_h = '$id' AND status != 'CANCEL'");

/* Nama fisik file yang dipakai di URL (upload/<...>). Upload baru sudah disimpan
   dalam bentuk rawurlencode() (lihat upload_file.php), jadi dipakai apa adanya.
   Upload lama (sebelum perbaikan) masih nama asli apa adanya, jadi perlu
   di-rawurlencode() dulu supaya jadi URL yang valid. */
if (!function_exists('memo_file_url_name')) {
  function memo_file_url_name($file_name)
  {
    if (preg_match('/^[A-Za-z0-9\-_.~%]*$/', $file_name)) { return $file_name; }
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
