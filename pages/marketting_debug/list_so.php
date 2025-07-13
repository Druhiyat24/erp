<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";
$from=$_POST['txtfrom'];
$to=$_POST['txtto'];
if (isset($_POST['txtid_buyer'])) {$id_buyer=$_POST['txtid_buyer'];} else {$id_buyer="";}
if (isset($_POST['txtstyle'])) {$styleno=$_POST['txtstyle'];} else {$styleno="";}
# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama

# END COPAS VALIDASI
?>
<div id="myPack" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih Packing Process</h4>
      </div>
      <div class="modal-body">
        <div id='detail_rak'></div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="save_pack()" data-dismiss="modal">Simpan</button>
      </div>
    </div>
  </div>
</div>
<div id="myRat" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Input Ratio Packing</h4>
      </div>
      <div class="modal-body">
        <div id='detail_ratio'></div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="save_rat()" data-dismiss="modal">Simpan</button>
      </div>
    </div>
  </div>
</div>
<script>
  function validasi()
  { 
  	var chkclass = document.getElementsByClassName('chkclass');
    var listpack = document.getElementsByClassName('list_pack');
    var packkos = 0;
    var listratio = document.getElementsByClassName('list_rat');
    var ratiokos = 0;
    var dipilih = 0;
    for (var i = 0; i < chkclass.length; i++) 
    { if (chkclass[i].checked)
      { dipilih = dipilih + 1;
        if (listpack[i].value != '')
        {
          packkos = 1;
        }
        if (listratio[i].value != '')
        {
          ratiokos = 1;
        }
      }
    }
    if (Number(dipilih) > 1) { swal({ title: 'Tidak Bisa Multiple', <?php echo $img_alert; ?> });valid = false; }
    else if (Number(packkos) == 0) { swal({ title: 'Proses Packing Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else if (Number(ratiokos) == 0) { swal({ title: 'Ratio Packing Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else {valid = true;}
    return valid;
    exit;
  };
  function choose_pack(id)
  { 
  	var html = $.ajax
    ({  type: "POST",
        url: '../forms/ajax_bpb_jo.php?modeajax=view_list_pack',
        data: {id: id},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_rak").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
  function choose_ratio(id)
  { var html = $.ajax
    ({  type: "POST",
        url: '../forms/ajax_bpb_jo.php?modeajax=view_list_ratio',
        data: {id: id},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_ratio").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplerat').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
  function calc_tot_ratio()
  {
  	var clrat = document.getElementsByClassName('txtratclass');
    var qsorat = document.getElementById('txtqtysorat').value;
    var qrat = 0;
    for (var i = 0; i < clrat.length; i++) 
	  { if (Number(clrat[i].value) > 0)
	    { 
	      qrat = qrat + Number(clrat[i].value);
	    }
	  }
  	$('#total_ratio').val(qrat);
    $('#total_karton').val(qsorat / qrat);
  };
  function save_pack()
  { var chkpack = document.getElementsByClassName('chkpackclass');
    var pilpack = document.getElementsByClassName('id_listclass');
    var pilpacksel = document.getElementsByClassName('list_pack');
    var cripack = document.getElementsByClassName('txtcriclass');
    var cripacknya = "";
    var pilpacknya = "";
    for (var i = 0; i < chkpack.length; i++) 
    { if (chkpack[i].checked)
      { cripacknya = cripack[i].value; 
        if (pilpacknya == '')
        { pilpacknya = cripack[i].value; }
        else
        { pilpacknya = pilpacknya + "X" + cripack[i].value; }
      }
    }
    var res = cripacknya.split("|");
    var rescri = res[1];
    for (var i = 0; i < pilpack.length; i++) 
    { if (pilpack[i].value == rescri)
      {
        pilpacksel[i].value = pilpacknya;
      }
    }
  };
  function save_rat()
  { var clrat = document.getElementsByClassName('txtratclass');
    var clsize = document.getElementsByClassName('txtsizeclass');
    var idrat = document.getElementsByClassName('txtratidclass');
    var ratnya = "";
    var crirat = "";
    var pilratsel = document.getElementsByClassName('list_rat');
    var pilpack = document.getElementsByClassName('id_listclass');
    for (var i = 0; i < clrat.length; i++) 
    { 
      crirat = idrat[i].value;
      if (ratnya == '')
      { ratnya = clrat[i].value + "=" + clsize[i].value; }
      else
      { ratnya = ratnya + "|" + clrat[i].value + "=" + clsize[i].value; }
    }
    var rescri = crirat;
    for (var i = 0; i < pilpack.length; i++) 
    { if (pilpack[i].value == rescri)
      {
        pilratsel[i].value = ratnya;
      }
    }
  };
</script>
<?php
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_ws.php?mod=12' onsubmit='return validasi()'>";
        echo "<div class='col-md-12'>";
          echo "<div class='form-group'>";
          ?>
          <table id="examplefix2" class="display responsive" style="width:100%">
            <thead>
            <tr>
              <?php if ($mod=="12v") { ?>
                <th>..</th>
              <?php } ?>
              <th>SO #</th>
              <th>Buyer PO</th>
              <th>WS #</th>
              <th>Cost #</th>
              <th>Buyer</th>
              <th>Product</th>
              <th>Item Name</th>
              <th>Style #</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Delv Date</th>
              <?php if ($mod=="12v") { ?>
                <th></th>
                <th></th>
              <?php } ?>
            </tr>
            </thead>
            <tbody>
              <?php
              # QUERY TABLE
              if ($mod=="12v")
              {$sql_add=" j.id_so is null and id_buyer='$id_buyer' 
                and styleno='$styleno'";
              }
              else
              {$sql_add="";}
              $sql2="select a.id,so_no,buyerno,kpno,
                cost_no,supplier,product_group,product_item,styleno,
                t_qty_so_det qty,a.unit,
                sod.deldate from so a inner join act_costing s on 
                a.id_cost=s.id inner join 
                (select id_so,max(deldate_det) deldate,sum(qty) t_qty_so_det from so_det 
                  where cancel='N' and deldate_det between '".fd($from)."' and '".fd($to)."'
                  group by id_so) sod 
                on a.id=sod.id_so
                inner join mastersupplier g on 
                s.id_buyer=g.id_supplier inner join masterproduct h 
                on s.id_product=h.id left join jo_det j on a.id=j.id_so and 'N'=j.cancel 
                where  
                $sql_add ";
              #echo $sql2;
              $query = mysql_query($sql2); 
              if (!$query) {die(mysql_error());}
              $no = 1; 
              while($data = mysql_fetch_array($query))
              { $id=$data['id'];
                echo "<tr>";
                  if ($mod=="12v")
                  {echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='chkclass' ></td>";} 
                  echo "<td>$data[so_no]</td>";
                  echo "<td>$data[buyerno]</td>";
                  echo "<td>$data[kpno]</td>";
                  echo "<td>$data[cost_no]</td>";
                  echo "<td>$data[supplier]</td>";
                  echo "<td>$data[product_group]</td>";
                  echo "<td>$data[product_item]</td>";
                  echo "<td>$data[styleno]</td>";
                  echo "<td>$data[qty]</td>";
                  echo "<td>$data[unit]</td>";
                  echo "<td>".fd_view($data['deldate'])."</td>";
                  if ($mod=="12v") 
                  { echo "
                    <td>
                      <input type ='hidden' size='4' name='list_pack[$id]' id='list_pack$id' 
                        class='list_pack'>
                      <input type ='hidden' size='4' name='id_list[$id]' id='id_list$id' 
                        class='id_listclass' value='$id'>
                      <input type ='hidden' size='4' name='list_rat[$id]' id='list_rat$id' 
                        class='list_rat'>
                      <button type='button' class='btn btn-primary' data-toggle='modal' 
                        data-target='#myPack' onclick='choose_pack($id)'>Packing</button>
                    </td>
                    <td>
                      <button type='button' class='btn btn-primary' data-toggle='modal' 
                        data-target='#myRat' onclick='choose_ratio($id)'>Ratio</button>
                    </td>";
                  }
                echo "</tr>";
                $no++; // menambah nilai nomor urut
              }
              ?>
            </tbody>
          </table>
          <?php    
          echo "</div>";
        echo "</div>";
        if ($mod=="12v")
        { echo "<div class='col-md-3'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
          echo "</div>";
        }
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>