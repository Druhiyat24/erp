<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



# START CEK HAK AKSES KEMBALI

$titlenya="Barang Jadi";

$mod=$_GET['mod'];

$mode="FG";

$akses = flookup("mnuBPPBFG_SO","userpassword","username='$user'");

$akses_date = flookup("original_date","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI

# COPAS EDIT

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

  $st_company=$rscomp['status_company'];

  $jenis_company=$rscomp['jenis_company'];

$trans_no="";

if ($trans_no=="")

{ $bppbno = "";

  $bppbdate = date('d M Y');

  $id_supplier = "";

  $buyer = "";

  $invno = "";

  $jenis_dok = "";

  $tujuan = "";

  $subtujuan = "";

  $nomor_aju = "";

  $tanggal_aju = "";

  $bcno = "";

  $bcdate = "";

  $no_fp = "";

  $tgl_fp = "";

  $kkbc = "";

}

else

{ $query = mysql_query("SELECT * FROM bppb where id_item='$trans_no' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $bppbno = $data['bppbno'];

  $bppbdate = $data['bppbdate'];

  $id_supplier = $data['id_supplier'];

  $buyer = $data['buyer'];

  $invno = $data['invno'];

  $jenis_dok = $data['jenis_dok'];

  $tujuan = $data['tujuan'];

  $subtujuan = $data['subtujuan'];

  $nomor_aju = $data['nomor_aju'];

  $tanggal_aju = $data['tanggal_aju'];

  $bcno = $data['bcno'];

  $bcdate = $data['bcdate'];

  $no_fp = $data['no_fp'];

  $tgl_fp = $data['tgl_fp'];

  $kkbc = $data['nomor_kk_bc'];

}

$frdate=date("d M Y");
$kedate=date("d M Y");

$tglf=date("d M Y");
$tglt=date("d M Y");

$dtf=date("d M Y");
$dtt=date("d M Y");

$perf=date("d M Y");
$pert=date("d M Y");

if (isset($_POST['submit']))
{
  $excel="N";
  $tglf = fd($_POST['frdate']);
  $perf = date('d M Y', strtotime($tglf));
  $tglt = fd($_POST['kedate']);
  $pert = date('d M Y', strtotime($tglt));

}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

?>

<script type='text/javascript'>

  function validasi()

  { var bppbdate = document.form.txtbppbdate.value;

    var id_supplier = document.form.txtid_supplier.value;

    var buyer = document.form.txtbuyer.value;

    var invno = document.form.txtinvno.value;

    var jenis_dok = document.form.txtjenis_dok.value;

    var gradenya = document.form.txtgrade.value;    

    var qtyo = document.form.getElementsByClassName('form-control sisaclass');

    var balo = document.form.getElementsByClassName('sisaclass');

    var nodata = 0;

    var dataover = 0;

    for (var i = 0; i < qtyo.length; i++) 

    { if (Number(qtyo[i].value) > 0)

      { nodata = nodata + 1; break; }

    }

    for (var i = 0; i < qtyo.length; i++) 

    { if (Number(qtyo[i].value) > 0 && Number(qtyo[i].value) > Number(balo[i].value))

      { dataover = dataover + 1; break; }

    }
    

    if (bppbdate == '') { document.form.txtbppbdate.focus(); swal({ title: 'bppb Date Tidak Boleh Kosong', $img_alert }); valid = false;}

    else if (id_supplier == '') { document.form.txtid_supplier.focus(); swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (invno == '') { document.form.txtinvno.focus(); swal({ title: 'Invoice # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (jenis_dok == '') { document.form.txtjenis_dok.focus(); swal({ title: 'Type Document Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}

    else if (nodata == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?> }); valid = false;}

    // else if (Number(dataover) > 0) { swal({ title: 'Qty Sudah Tidak Mencukupi', <?php echo $img_alert; ?> }); valid = false;}

    else valid = true;

    return valid;

    exit;

  }

</script>

<?php

# END COPAS VALIDASI

# COPAS ADD

?>

<script type="text/javascript">

  function getJO()

  { var id_jo = $('#cboJO').val();

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_bppb_so_new.php?modeajax=view_list_jo_new',

        data: {id_jo: id_jo},

        async: false

    }).responseText;

    if(html)

    {  

        $("#detail_item").html(html);

    }

    $(document).ready(function() {
      var table = $('#examplefix1').DataTable
      ({paging: false,
        ordering: true,
        info: false,
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        columnDefs: [
            { width: '20%', targets: 0 }
        ],      
      });
    }); 
  };

  function startCalcBpb(){
intervalBpb = setInterval('findTotalBpb()',1);}

function findTotalBpb(){
    var arr = document.getElementsByClassName('form-control sisaclass');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('total_qty_chk').value = tot;
}

function stopCalcBpb(){
clearInterval(intervalBpb);}

</script>


<script type="text/javascript">
 
  function getListBuyer(cri_item)

  { var nama_buyer = document.form.txtbuyer.value;

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_bppb_so_new.php?modeajax=cari_list_sent',

        data: {nama_buyer: nama_buyer},

        async: false

    }).responseText;

    if(html)

    { $("#my_sup").html(html); }

  };  
</script>

<?php if($mod=="321") {?>

<form method='post' name='form' action='s_bppb_so_new.php?mod=<?php echo $mod; ?>' 

  onsubmit='return validasi()'>

  <div class='box'>

    <div class='box-body'>

      <div class='row'>

        <div class='col-md-3'>              

          <div class='form-group'>

            <label>BPPB #</label>

            <input type='text' readonly class='form-control' name='txtbppbno' value='<?php echo $bppbno;?>' >

          </div>        

          <div class='form-group'>

            <label>BPPB Date *</label>

            <input type='text' id='datepicker1' class='form-control' name='txtbppbdate' placeholder='Masukkan bppb Date' value='<?php echo $bppbdate;?>' >

          </div>   

          <div class='form-group'>

            <label>Buyer *</label>

            <select class='form-control select2' style='width: 100%;' name='txtbuyer' id='txtbuyer'  onchange='getListBuyer(this.value)' required>

              <?php 

                $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";

                IsiCombo($sql,$buyer,'Pilih Buyer');

              ?>

            </select>

          </div>                 

           

          <div class='form-group'>

            <?php if($jenis_company=="VENDOR LG") { ?>

              <label>JO # *</label>

            <?php } else { ?>

              <label>WS # *</label>

            <?php } ?>

            <select class='form-control select2' multiple='multiple' style='width: 100%;' 

              name='txtid_jo' id='cboJO' onchange='getJO()'>

              <?php 

                if($jenis_company=="VENDOR LG")

                { $sql = "select ac.id isi,concat(jo_no,'|',product_group) tampil from 

                  act_costing ac inner join masterproduct mp on ac.id_product=mp.id 

                  inner join so on ac.id=so.id_cost 

                  inner join jo_det jod on jod.id_so=so.id 

                  inner join jo on jod.id_jo=jo.id 

                  order by jod.id_jo";

                } 

                else

                { $sql = "select id isi,concat(kpno,'|',styleno) tampil from 

                  act_costing where cost_date >= '2022-01-01' order by kpno";

                } 

                IsiCombo($sql,'','');

              ?>

            </select>

          </div>        

        </div>

        <div class='col-md-3'>          

          <div class='form-group'>

            <label>Invoice # *</label>

            <input type='text' class='form-control' name='txtinvno' placeholder='Masukkan Invoice #' value='<?php echo $invno;?>' >

          </div>

          <div class='form-group'>

            <label>Type Document *</label>

            <select class='form-control select2' style='width: 100%;' name='txtjenis_dok'>

              <?php 

                if ($st_company=="KITE") 

                { $status_kb_cri="Status KITE Out"; }

                else if ($st_company=="PLB") 

                { $status_kb_cri="Status PLB Out"; }

                else

                { $status_kb_cri="Status KB Out"; }

                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

                      kode_pilihan='$status_kb_cri' order by nama_pilihan";

                IsiCombo($sql,$jenis_dok,'Pilih Type Document');

              ?>

            </select>

          </div>          


<!--           <div class='form-group'>

            <label>Sent To *</label>

                    <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' onchange='check_customer("N",this.value,"INV")'

                      onchange='getListData(this.value)'>

                    </select>

          </div> -->

          <div class='form-group'>

            <label>Sent To *</label>

            <select class='form-control select2' id="my_sup" onchange='check_customer("N",this.value,"INV")'  style='width: 100%;' name='txtid_supplier'>

              <?php 

                $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";

                IsiCombo($sql,$id_supplier,'Pilih Sent To');

              ?>

            </select>

          </div> 


          <div class='form-group'>

                <label>Grade *</label>

                <select class='form-control select2' style='width: 100%;' name='txtgrade' required>

                  <?php 

                    $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

                          kode_pilihan='GRADE_FG' order by nama_pilihan";

                    IsiCombo($sql,'','Pilih Grade');

                  ?>

                </select>

          </div>                     

        </div>

        <div class='box-body'>

          <div id='detail_item'></div>

        </div>

        <div class='col-md-3'>

          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>

        </div>  

      </div>

    </div>

  </div>

</form>

<?php } else if ($mod=="32z") {

# END COPAS ADD

?>

<div class="box">

  <?php 

  $fldnyacri=" mid(bppbno,4,2)='FG' "; $mod2=321;

  ?>

  <div class="box-header">

    <h3 class="box-title">List Pengeluaran <?PHP echo $titlenya; ?></h3>

    <a href='../forms/?mod=<?php echo $mod2; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>

      <i class='fa fa-plus'></i> New

    </a>

  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-2'>                            
        <label>From Date (BPPB) : </label>
        <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf;?>' >
             
      </div>
      <div class='col-md-2'>
        <label>To Date (BPPB) : </label>
        <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert;?>' >
      </div> 
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>

   </div>
    </form>
  </div>   

  <div class="box-body">

    <table id="examplefix3" class="display responsive" style="width:100%">

      <thead>

        <tr>

          <th width="15%">Nomor BPPB</th>

          <th width="15%">Tanggal BPPB</th>

          <?php if($akses_date=="1") { ?>

          <th>Original BPPB Date</th>

          <?php } ?>   
          <th width="20%">Sent To</th>

          <th width="10%">WS #</th> <!--16Des19-->
          <th width="10%">Style</th> <!--16Des19-->

          <th width="10%">No. Invoice</th>

          <th width="10%">No. Dokumen</th>
          <th width="10%">Tanggal Dokumen</th> <!--16Des19-->
          <th width="10%">Jenis BC</th>

          <th>Created By</th>
      
          <th>Status</th>

          <th></th>

          <th></th>

        </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        $tbl_mst="masterstyle"; $fld_desc="s.itemname";

        $sql="SELECT a.*,ac.kpno,s.styleno,s.goods_code,$fld_desc itemdesc,supplier , a.last_date_bppb,
          if(ms.area='F' or ms.area='LINE','INTERNAL','EXTERNAL') out_to,a.confirm    
          FROM bppb a inner join $tbl_mst s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join so_det sod on a.id_so_det=sod.id 
          inner join so on sod.id_so=so.id 
          left join jo_det jod on so.id=jod.id_so 
          left join jo on jod.id_jo=jo.id  
          inner join act_costing ac on so.id_cost=ac.id
          where $fldnyacri and a.id_so_det!='' and bppbdate >= '$tglf' and bppbdate <= '$tglt'
          GROUP BY a.bppbno ASC order by bppbdate desc ";
        #echo $sql;

        $query = mysql_query($sql);

        while($data = mysql_fetch_array($query))

        { 
            if($data['cancel']=="Y")
            {
              $fontcol="style='color:red;'";
            }
            else
            {
              $fontcol="";
            }     
      echo "<tr $fontcol>";

          echo "

            <td>$data[bppbno_int]</td>

            <td>".fd_view($data[bppbdate])."</td>";
      if($akses_date=="1") {
            echo "<td>".fd_view($data[last_date_bppb])."</td>";
      }
      echo "
            <td>$data[supplier]</td>
            <td>$data[kpno]</td>
            <td>$data[styleno]</td>

            <td>$data[invno]</td>

            <td>$data[bcno]</td>
            <td>".fd_view($data[bcdate])."</td>

            <td>$data[jenis_dok]</td>

            <td>$data[username] ($data[dateinput])</td>";
      
      if($data['confirm']=='N') {
        
            if($data['cancel']=="Y")
            {
            echo " <td></td>
      <td>-</td>";
            }
            else
            {
            echo " <td></td>
      <td>

              <a href='?mod=321ed&mode=$mode&noid=$data[bppbno]'

                data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>

              </a>
            </td>";
            }           
        
        
 } else { echo "<td>Confirmed by $data[confirm_by] $data[confirm_date]</td><td></td>"; } 
            if(
                ($data['out_to']=="INTERNAL") or 
                ($data['out_to']=="EXTERNAL" and $data['confirm']=="Y")
              )
            {
        
                    if($data['cancel']=="Y")
            {
              echo "
              <td> 
          Cancelled
              </td>";
            }
            else
            {
              echo "
              <td> 
                <a href='cetaksj_fg.php?mode=Out&noid=$data[bppbno]' 
                  data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>
                </a>
              </td>";
            } 

            }
            else
            {
              echo "
              <td></td>";
            }
          echo "</tr>";

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } else if ($mod=="321ed") {  
  
  $id_bppb=$_GET['noid']; 

  $querybppb_h = mysql_query("SELECT bppb.*, ms.supplier from bppb 
  inner join mastersupplier ms on bppb.id_supplier = ms.id_supplier 
  where bppbno = '$id_bppb' limit 1");
  $databppb_h    = mysql_fetch_array($querybppb_h);
  $bppbno_h  =$databppb_h['bppbno_int'];
  $bppbdate_h  =fd_view($databppb_h['bppbdate']);
  $buyer_h  =$databppb_h['id_buyer'];
  $invno_h  =$databppb_h['invno'];
  $jenis_dok_h  =$databppb_h['jenis_dok'];
  $id_supplier_h  =$databppb_h['id_supplier'];
  $grade_h  =$databppb_h['grade'];

  $querytotal = mysql_query("SELECT sum(qty) total from bppb where bppbno = '$id_bppb'");
  $datatotal = mysql_fetch_array($querytotal);
  $totalinput =$datatotal['total'];
   
  ?>

  <form method='post' name='form' action='upd_bppb_so_new.php?mod=update&id_bppb=<?php echo $id_bppb; ?>'>

<div class='box'>

  <div class='box-body'>

    <div class='row'>

      <div class='col-md-3'>              

        <div class='form-group'>

          <label>BPPB #</label>

          <input type='text' readonly class='form-control' name='txtbppbno' value='<?php echo $bppbno_h;?>' >

        </div>        

        <div class='form-group'>

          <label>BPPB Date *</label>

          <input type='text' id='datepicker1' class='form-control' name='txtbppbdate' placeholder='Masukkan bppb Date' value='<?php echo $bppbdate_h;?>' >

        </div>   

        <div class='form-group'>

          <label>Buyer *</label>

          <select class='form-control select2' style='width: 100%;' name='txtbuyer' id='txtbuyer'  onchange='getListBuyer(this.value)' required>

            <?php 

              $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";

              IsiCombo($sql,$buyer_h,'Pilih Buyer');

            ?>

          </select>

        </div>                        

      </div>

      <div class='col-md-3'>          

        <div class='form-group'>

          <label>Invoice # *</label>

          <input type='text' class='form-control' name='txtinvno' placeholder='Masukkan Invoice #' value='<?php echo $invno_h;?>' >

        </div>

        <div class='form-group'>

          <label>Type Document *</label>

          <select class='form-control select2' style='width: 100%;' name='txtjenis_dok'>

            <?php 

              if ($st_company=="KITE") 

              { $status_kb_cri="Status KITE Out"; }

              else if ($st_company=="PLB") 

              { $status_kb_cri="Status PLB Out"; }

              else

              { $status_kb_cri="Status KB Out"; }

              $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

                    kode_pilihan='$status_kb_cri' order by nama_pilihan";

              IsiCombo($sql,$jenis_dok_h,'Pilih Type Document');

            ?>

          </select>

        </div>          


<!--           <div class='form-group'>

          <label>Sent To *</label>

                  <select class='form-control select2' style='width: 100%;' name='cbosupp' id='cbosupp' onchange='check_customer("N",this.value,"INV")'

                    onchange='getListData(this.value)'>

                  </select>

        </div> -->

        <div class='form-group'>

          <label>Sent To *</label>

          <select class='form-control select2' id="my_sup" onchange='check_customer("N",this.value,"INV")'  style='width: 100%;' name='txtid_supplier'>

            <?php 

              $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup='C' order by supplier";

              IsiCombo($sql,$id_supplier_h,'Pilih Sent To');

            ?>

          </select>

        </div> 


        <div class='form-group'>

              <label>Grade *</label>

              <select class='form-control select2' style='width: 100%;' name='txtgrade' required>

                <?php 

                  $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

                        kode_pilihan='GRADE_FG' order by nama_pilihan";

                  IsiCombo($sql,$grade_h,'Pilih Grade');

                ?>

              </select>

        </div>                     

      </div>

      <div class='box-body'>

        <div id='detail_item'>
        <table id="example1" class="table table-striped" style="width:100%">
    <thead>
<tr>
                <th colspan="10" style="text-align:right">Total Input:</th>
                <td> <input type = 'text' size='2' value = '<?php echo $totalinput; ?>' disabled readonly> </td>
                <td></td>
                <td></td>
</tr>
    <tr>
      <th>SO New #</th>
      <th>WS #</th>
      <th>Product</th>
      <th>Product Desc</th>
      <th>Buyer PO</th>
      <?php if($jenis_company!="VENDOR LG") { ?>
        <th>Dest</th>
        <th>Color</th>
        <th>Size</th>
        <th>Reff No</th>
        <th>SKU</th>
      <?php } ?>
      <th>Qty BKB</th>
      <th>Curr</th>
      <th>Price</th>
    </tr>
    </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $tblnya="bppb"; $tblnya2="bpb";
      $sql="select so.so_no,ac.kpno,mp.product_group,mp.product_item,buyerno,dest,color,size,reff_no,sku,bppb.qty,bppb.curr,bppb.price from bppb 
      inner join so_det sod on bppb.id_so_det = sod.id
      inner join so on sod.id_so = so.id
      inner join act_costing ac on so.id_cost=ac.id 
      inner join masterproduct mp on ac.id_product=mp.id 
      where bppb.bppbno = '$id_bppb'";
      #echo $sql;
      $query = mysql_query($sql); 
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { 
		//echo "<script>var jsn ={bkb_qty :0}  <script>";
		
		if($jenis_company=="VENDOR LG") { $defpx=$data['fob']; } else { $defpx=$data['price']; }
        echo "<tr>";
          $id=$data['id'];
          echo "
          <td>$data[so_no]</td>
          <td>$data[kpno]</td>
          <td>$data[product_group]</td>
          <td>$data[product_item]</td>
          <td>$data[buyerno]</td>";
           echo "
            <td>$data[dest]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>
            <td>$data[reff_no]</td>
            <td>$data[sku]</td>
            <td>$data[qty]</td>
            <td>$data[curr]</td>
            <td>$data[price]</td>"; 
        echo "</tr>";
        $no++; // menambah nilai nomor urut
      }
      ?>
    </tbody>
  </table>



        </div>

      </div>

      <div class='col-md-3'>

        <button type='submit' name='submit' class='btn btn-primary'>Update</button>

      </div>  

    </div>

  </div>

</div>

</form>


  <?php }?>
