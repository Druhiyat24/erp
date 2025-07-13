<?PHP
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['bahasa'])) { $bahasa=$_SESSION['bahasa']; } else { $bahasa="Indonesia"; }
if ($bahasa=="Korea")
{ include "../forms/ko.php"; }
else
{ include "../forms/id.php"; }
?>
<div class='box'>

</div>