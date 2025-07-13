<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}


$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "maintain_bpb") {

  if (isset($_POST['submit_excel'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $status = $_POST['status'];
    echo "<script>
    window.open ('?mod=exc_trans_bpb&from=$from&to=$to&nama_supp=$nama_supp&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $status = $_POST['status'];
  }

  ?>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>

          <div class='col-md-3'>
            <div class='form-group'>
              <label>Status #</label>
              <select class="form-control select2" name="status" id="status" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php if ($status == "ALL") { echo "selected"; } ?>>ALL</option>                                                 
                <?php
                $data2 ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $data2 = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                $sql = mysql_query("select DISTINCT id, nama_pilihan from whs_master_pilihan where type_pilihan = 'status_maintain' and status = 'Active' order by id ASC");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['nama_pilihan'];
                  $data2 = $row['nama_pilihan'];
                  if($row['nama_pilihan'] == $_POST['status']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>
          </div>


          <div class='col-md-2'>
            <div class='form-group'>
              <label>Dari *</label>
              <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required placeholder='Masukkan Dari Tanggal' value="<?php 
              $txtfrom = isset($_POST['txtfrom']) ? $_POST['txtfrom']: null;            
              if(!empty($_POST['txtfrom'])) {
                echo $_POST['txtfrom'];
              }
              else{
                echo date("d M Y");
              } ?>">
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Sampai *</label>
              <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required placeholder='Masukkan Dari Tanggal' value="<?php 
              $txtto = isset($_POST['txtto']) ? $_POST['txtto']: null;            
              if(!empty($_POST['txtto'])) {
                echo $_POST['txtto'];
              }
              else{
                echo date("d M Y");
              } ?>">
            </div>
          </div>

          <div class='col-md-5'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button>
              <!-- <button type='submit' name='submit_excel' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button> -->
              <a href='../doc_handover/?mod=create_maintain_bpb' class='btn btn-primary btn-s'>
                <i class='fa fa-plus'></i> New
              </a>
            </div>
          </div>
        </div>
      </div>

      <table id="examplefix" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th class="text-center">No Reverse</th>
            <th class="text-center">Tgl Reverse</th>
            <th class="text-center">User Create</th>
            <th class="text-center">Status</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          if($status == 'ALL' ){
            $where = "";
          }else{
            $where = "and status = '$status' ";  
          }

          $sql = "select id, no_maintain,tgl_maintain,status,CONCAT(created_by,' (',created_date,')') create_user, keterangan from maintain_bpb_h where tgl_maintain BETWEEN '$from' and '$to' ".$where." order by no_maintain asc";
          
          $query = mysql_query($sql);

          // echo $sql;

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_maintain = date('d M Y', strtotime($data[tgl_maintain]));
            
            echo "<tr>";
            echo "<td value= '".$data[no_maintain]."'>$data[no_maintain]</td>";
            echo "<td value= '".$tgl_maintain."'>$tgl_maintain</td>";
            echo "<td>$data[create_user]</td>";
            echo "<td>$data[status]</td>";
            echo "<td value= '".$data[keterangan]."'>$data[keterangan]</td>";
            if ($data[status] == 'CANCEL') {
              echo "<td><a id='showdet'><button type='button' class='btn btn-xs btn-info'><i class='fa fa-eye' aria-hidden='true' > Show</i></button></a>
             </td>";
           }elseif ($data[status] == 'APPROVED') {
             echo "<td><a href='http://10.10.5.60/ap/module/AP/pdf_maintain_bpb.php?doc_number=".$data[id]."' target='_blank'><button type='button' class='btn btn-xs btn-success'><i class='fa fa-print '> Print</i></button></a>
             <a id='showdet'><button type='button' class='btn btn-xs btn-info'><i class='fa fa-eye' aria-hidden='true' > Show</i></button></a>
             </td>";
           }else{

            echo "<td><a href='http://10.10.5.60/ap/module/AP/pdf_maintain_bpb.php?doc_number=".$data[id]."' target='_blank'><button type='button' class='btn btn-xs btn-success'><i class='fa fa-print '> Print</i></button></a>
            <a id='showdet'><button type='button' class='btn btn-xs btn-info'><i class='fa fa-eye' aria-hidden='true' > Show</i></button></a>
            <a id='cancel_maintain'><button type='button' class='btn btn-xs btn-danger cancel_maintain'><i class='fa fa-trash' aria-hidden='true' > Cancel</i></button></a>
            </td>";
          }
          echo "</tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</form>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-show" data-target="#modal-show" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="edit">DETAIL MAINTAIN BPB</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body">

        <div class="container-fluid">
          <div class="row mb-2">
            <div id="txt_notrf_show" class="col-md-6" style="font-size: 14px;"></div>
            <div id="txt_tgl_trf_show" class="col-md-6" style="font-size: 14px;"></div>
          </div>
          <div class="row mb-2">
            <div id="txt_ket_maintain_show" class="col-md-12" style="font-size: 14px;"></div>
          </div>
          <br>

          <!-- Table with horizontal scroll -->
          <div class="table-responsive" style="overflow-x: auto;">
            <table id="example2" class="table table-bordered table-striped" style="font-size: 12px; min-width: 1000px; text-align: center;">
              <thead class="thead-dark">
                <tr>
                  <th style="display: none;"><input type="checkbox" id="select_all"></th>
                  <th style="display: none;">No Document</th>
                  <th style="display: none;">Document Date</th>
                  <th style="width: 25%;">Supplier</th>
                  <th style="width: 15%;">No BBP</th>
                  <th style="width: 10%;">BPB Date</th>
                  <th style="width: 30%;">Keterangan</th>
                </tr>
              </thead>
              <tbody id="data_invoice_show">
                <!-- Rows will be injected here -->
              </tbody>
            </table>
          </div>

        </div> <!-- /.container-fluid -->

      </div> <!-- /.modal-body -->

    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->


<?php }
