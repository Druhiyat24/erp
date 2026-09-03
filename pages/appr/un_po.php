<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("approval_po","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var reason = document.form.txtreason.value;
    var pono = document.form.txtid_cost.value;
    if (pono == '') { swal({ title: 'PO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (reason == '') { document.form.txtreason.focus(); swal({ title: 'Reason Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' enctype='multipart/form-data' action='s_un_po.php?mod=$mod' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          ?>
          <div class='form-group'>
            <label>PO # *</label>
            <select class='form-control select2po' style='width: 100%;' name='txtid_cost' id='txtid_cost'>
              <option></option>
            </select>
          </div>
          <div class='form-group'>
            <label>Reason *</label>
            <textarea row="4" class='form-control' name='txtreason' placeholder="Masukan Reason"></textarea>
          </div>
          <?php
          echo "<button type='submit' name='submit' class='btn btn-primary'>Unapprove</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD

# Combo PO # server-side (Select2 AJAX)
$tampil_cost = "";
if (!empty($id_cost))
{ $cri_cost = explode(':', $id_cost);
  $rs_cost = mysql_fetch_array(mysql_query("select concat(a.pono,' - ',s.supplier) tampil
    from po_header a inner join mastersupplier s on a.id_supplier=s.id_supplier
    where a.id='".mysql_real_escape_string($cri_cost[0])."'"));
  if ($rs_cost) { $tampil_cost = $rs_cost['tampil']; }
}
?>
<script type='text/javascript'>
  function initComboPO()
  { if (window.jQuery && jQuery.fn.select2)
    { jQuery('#txtid_cost').select2(
      { placeholder: 'Pilih PO #',
        allowClear: true,
        minimumInputLength: 0,
        ajax:
        { url: 'ajax_po_list.php',
          dataType: 'json',
          delay: 250,
          data: function (params)
          { return { q: params.term || '', page: params.page || 1 }; },
          processResults: function (data, params)
          { params.page = params.page || 1;
            return { results: data.results, pagination: { more: data.pagination.more } };
          },
          cache: true
        }
      });
      <?php if ($id_cost != "") { ?>
      var pre = new Option(<?php echo json_encode($tampil_cost); ?>, <?php echo json_encode($id_cost); ?>, true, true);
      jQuery('#txtid_cost').append(pre).trigger('change');
      <?php } ?>
    }
    else
    { setTimeout(initComboPO, 100); }
  }
  initComboPO();
</script>
<?php
?>