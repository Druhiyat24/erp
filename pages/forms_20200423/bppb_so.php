<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$titlenya="Barang Jadi";
$mod=$_GET['mod'];
$mode="FG";
$akses = flookup("mnuBPPBFG_SO","userpassword","username='$user'");
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
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var bppbdate = document.form.txtbppbdate.value;
    var id_supplier = document.form.txtid_supplier.value;
    var invno = document.form.txtinvno.value;
    var jenis_dok = document.form.txtjenis_dok.value;
   // var nomor_aju = document.form.txtnomor_aju.value;
    var tanggal_aju = document.form.txttanggal_aju.value;
    //var bcno = document.form.txtbcno.value;
   // var bcdate = document.form.txtbcdate.value;
    var no_fp = document.form.txtno_fp.value;
    var tgl_fp = document.form.txttgl_fp.value;
    var qtyo = document.form.getElementsByClassName('qtyclass');
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
   // else if (jenis_dok !== 'INHOUSE' && nomor_aju == '') { document.form.txtnomor_aju.focus(); swal({ title: 'Nomor Pengajuan Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (jenis_dok !== 'INHOUSE' && tanggal_aju == '') { document.form.txttanggal_aju.focus(); swal({ title: 'Tgl. Pengajuan Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
  //  else if (jenis_dok !== 'INHOUSE' && bcno == '') { document.form.txtbcno.focus(); swal({ title: 'Nomor Pendaftaran Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
  //  else if (jenis_dok !== 'INHOUSE' && bcdate == '') { document.form.txtbcdate.focus(); swal({ title: 'Tgl. Pendaftaran Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (jenis_dok !== 'INHOUSE' && no_fp == '') { document.form.txtno_fp.focus(); swal({ title: 'Nomor Faktur Pajak Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (jenis_dok !== 'INHOUSE' && tgl_fp == '') { document.form.txttgl_fp.focus(); swal({ title: 'Tgl. Faktur Pajak Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (nodata == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?> }); valid = false;}
    else if (Number(dataover) > 0) { swal({ title: 'Qty Sudah Tidak Mencukupi', <?php echo $img_alert; ?> }); valid = false;}
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
        url: 'ajax_bppb_so.php?modeajax=view_list_jo',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?php if($mod=="32") {?>
<form method='post' name='form' action='s_bppb_so.php?mod=<?php echo $mod; ?>' 
  onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>BKB #</label>
            <input type='text' readonly class='form-control' name='txtbppbno' value='<?php echo $bppbno;?>' >
          </div>        
          <div class='form-group'>
            <label>BKB Date *</label>
            <input type='text' id='datepicker1' class='form-control' name='txtbppbdate' placeholder='Masukkan bppb Date' value='<?php echo $bppbdate;?>' >
          </div>          
          <div class='form-group'>
            <label>Sent To *</label>
            <select class='form-control select2' style='width: 100%;' name='txtid_supplier'>
              <?php 
                $sql = "select id_supplier isi,supplier tampil from mastersupplier where tipe_sup in ('C','S') order by supplier";
                IsiCombo($sql,$id_supplier,'Pilih Sent To');
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
                  act_costing order by kpno";
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
          <div class='form-group'>
            <label>Tujuan</label>
            <select class='form-control select2' style='width: 100%;' name='txttujuan' disabled>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='TUJUAN_IN'";
                IsiCombo($sql,$tujuan,'Pilih Tujuan');
              ?>
            </select>
          </div>          
          <div class='form-group'>
            <label>Sub Tujuan</label>
            <select class='form-control select2' style='width: 100%;' name='txtsubtujuan' disabled>
              <?php 
                $sql = "select nama_pilihan isi,nama_pilihan tampil 
                  from masterpilihan where kode_pilihan='TUJUAN_SUB_IN'";
                IsiCombo($sql,$subtujuan,'Pilih Sub Tujuan');
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Nomor Pengajuan</label>
            <input type='text' class='form-control' name='txtnomor_aju' readonly placeholder='Masukkan Nomor Pengajuan' value='<?php echo $nomor_aju;?>' >
          </div>        
          <div class='form-group'>
            <label>Tgl. Pengajuan</label>
            <input type='text' id='datepicker2' class='form-control' readonly name='txttanggal_aju' placeholder='Masukkan Tgl. Pengajuan' value='<?php echo $tanggal_aju;?>' >
          </div>        
          <div class='form-group'>
            <label>Nomor Pendaftaran</label>
            <input type='text' class='form-control' name='txtbcno' readonly placeholder='Masukkan Nomor Pendaftaran' value='<?php echo $bcno;?>' >
          </div>        
          <div class='form-group'>
            <label>Tgl. Pendaftaran</label>
            <input type='text' id='datepicker3' class='form-control' readonly name='txtbcdate' placeholder='Masukkan Tgl. Pendaftaran' value='<?php echo $bcdate;?>' >
          </div>        
        </div>
        <div class='col-md-3'>        
          <div class='form-group'>
            <label>Nomor Faktur Pajak *</label>
            <input type='text' class='form-control' name='txtno_fp' placeholder='Masukkan Nomor Faktur Pajak' value='<?php echo $no_fp;?>' >
          </div>
          <div class='form-group'>
            <label>Tgl. Faktur Pajak *</label>
            <input type='text' id='datepicker4' class='form-control' name='txttgl_fp' 
              placeholder='Masukkan Tgl. Faktur Pajak' value='<?php echo $tgl_fp;?>' >
          </div>
          <div class='form-group'>
            <label>Nomor KK</label>
            <input type='text' class='form-control' name='txtkkbc' placeholder='Masukkan KK' value='<?php echo $kkbc;?>' >
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
<?php } else if ($mod=="32v") {
# END COPAS ADD
?>
<div class="box">
  <?php 
  $fldnyacri=" mid(bppbno,4,2)='FG' "; $mod2=32;
  ?>
  <div class="box-header">
    <h3 class="box-title">List Pengeluaran <?PHP echo $titlenya; ?></h3>
    <a href='../forms/?mod=<?php echo $mod2; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
        <tr>
          <th>Nomor BKB</th>
          <th>Tanggal BKB</th>
          <th>Sent To</th>
          <th>Style #</th>
          <th>WS #</th>
          <th>SO #</th>
          <th>No. Invoice</th>
          <th>No. Dokumen</th>
          <th>Jenis BC</th>
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
        $sql="SELECT a.*,if(bppbno_int!='',bppbno_int,bppbno) trx_no,s.goods_code,$fld_desc itemdesc,supplier,
          tmpinv.invnya,ac.styleno actstyle,ac.kpno wsno,so.so_no   
          FROM bppb a inner join $tbl_mst s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join so_det sod on a.id_so_det=sod.id 
          inner join so on sod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          left join 
          (select a.id_so_det,group_concat(s.invno) invnya from invoice_detail a inner join invoice_header s on a.id_inv=s.id group by id_so_det) tmpinv 
          on tmpinv.id_so_det=a.id_so_det  
          where $fldnyacri and a.id_so_det!='' 
          GROUP BY a.bppbno ASC order by bppbdate desc /* limit 1000 */";
        #echo $sql;
        $query = mysql_query($sql);
        while($data = mysql_fetch_array($query))
        { 
          $statuscfm = "Confirmed By ".$data['confirm_by']." (".fd_view_dt($data['confirm_date']).")";
          $createby=$data['username']." ".fd_view_dt($data['dateinput']);
          echo "<tr>";
          echo "
            <td>$data[trx_no]</td>
            <td>$data[bppbdate]</td>
            <td>$data[supplier]</td>
            <td>$data[actstyle]</td>
            <td>$data[wsno]</td>
            <td>$data[so_no]</td>
            <td>$data[invnya]</td>
            <td>$data[bcno]</td>
            <td>$data[jenis_dok]</td>
            <td>$createby</td>
            <td>$statuscfm</td>
            <td>
              <a href='?mod=32e&mode=$mode&noid=$data[bppbno]'
                data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
              </a>
            </td>
            <td> 
              <a href='cetaksj.php?mode=Out&noid=$data[bppbno]' 
                data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>
              </a>
            </td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>