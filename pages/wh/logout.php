<?php
session_start();
include ("../../include/conn.php");
include ("fungsi.php");
$user=$_SESSION['username'];
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
insert_log("XBerhasil Logout",$user);
if ($mode=="GB" OR $mode=="KB" OR $mode=="PLB")
{ $sql="update mastercompany set status_company='$mode'";
  insert_log($sql,"training");
}
else if ($mode=="KT")
{ $sql="update mastercompany set status_company='KITE'";
  insert_log($sql,"training");
}
else if ($mode=="MW")
{ $sql="update mastercompany set status_company='MULTI_WHS'";
  insert_log($sql,"training");
}
else if ($mode=="ELG")
{ $sql="update mastercompany set jenis_company='VENDOR LG',logo_company='Z'";
  insert_log($sql,"training");
}
else if ($mode=="EGM")
{ $sql="update mastercompany set jenis_company='',logo_company='Z'";
  insert_log($sql,"training");
}
session_destroy();
echo "<script>window.location.href='../../';</script>";
?>
