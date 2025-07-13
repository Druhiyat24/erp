<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";
$id_supplier=$_POST['txtid_supplier'];
$id_terms=$_POST['txtid_terms'];
$podate=$_POST['txtpodate'];

# COPAS EDIT
# END COPAS EDIT
?>
<!--COPAS VALIDASI BUANG ELSE di IF pertama-->
<script type='text/javascript'>
  function validasi()
  { var id_jo = document.form.txtJOItem.value;
    var pilih = 0;
    var currkos = 0;
    var qtykos = 0;
    var chks = document.form.getElementsByClassName('chkclass');
    var currs = document.form.getElementsByClassName('currclass');
    var qtys = document.form.getElementsByClassName('qtyclass');
    for (var i = 0; i < chks.length; i++) 
    { if (chks[i].checked) 
      { pilih = pilih + 1;
        if (currs[i].value == '')
        { currkos = currkos + 1; }
        if (qtys[i].value == '')
        { qtykos = qtykos + 1; }
      }
    }
    if (id_jo == '') 
    { swal({ title: 'Job Order # Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (pilih == 0)
    { swal({ title: 'Tidak Ada Data Yang Dipilih', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (currkos > 0)
    { swal({ title: 'Currency Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (qtykos > 0)
    { swal({ title: 'Qty Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else {valid = true;}
    return valid;
    exit;
  }
</script>
<!--END COPAS VALIDASI-->
<script type="text/javascript">
  function getJO()
  { var id_jo = document.form.txtJOItem.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=view_list_jo',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_po_it.php?mod=$mod&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Job Order # *</label>";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' onchange='getJO()'>";
              $sql = "select a.id isi,jo_no tampil from 
                jo a inner join bom_jo_item s on a.id=s.id_jo 
                where id_supplier='$id_supplier' group by jo_no";
              IsiCombo($sql,'','Pilih Job Order #');
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>