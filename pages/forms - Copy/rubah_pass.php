<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";

  echo "var old_pass = document.form.txtoldpass.value;";
  echo "var new_pass = document.form.txtnewpass.value;";
  
  echo "if (old_pass == '') { alert('Password lama tidak boleh kosong'); document.form.txtoldpass.focus();valid = false;}";
  echo "else if (new_pass == '') { alert('Password baru tidak boleh kosong'); document.form.txtnewpass.focus();valid = false;}";
  echo "else valid = true;";
  echo "return valid;";
  echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='rubah_pass_act.php' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Password Lama *</label>";
            echo "<input type='password' class='form-control' placeholder='Masukkan Password Lama' name='txtoldpass'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Password Baru *</label>";
            echo "<input type='password' class='form-control' placeholder='Masukkan Password Baru' name='txtnewpass'>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Rubah</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>