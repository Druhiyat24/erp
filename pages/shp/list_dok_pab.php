<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("update_dok_pab","userpassword","username='$user'");
if ($akses=="0") 
  { echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod=$_GET['mod'];
if(isset($_GET['trx'])) { $jen_trx=$_GET['trx']; } else { $jen_trx=""; }
if(isset($_GET['dtrx'])) { $tgl_trx=$_GET['dtrx']; } else { $tgl_trx=""; }
if (isset($_GET['dtrx2'])) { $tgl_trx2 = $_GET['dtrx2']; }else{ $tgl_trx2 = ''; }
if($mod=="2L")
  { if($jen_trx=="") 
{ $jen_trx=$_POST['txtjte'];
$tgl_trx=fd($_POST['txtdate']);
$tgl_trx2=fd($_POST['txtdate2']);
}
}
else
  { $trx_no=$_GET['noid']; 
if($jen_trx=="Pemasukan")
  {$trx_int=flookup("bpbno_int","bpb","bpbno='$trx_no'");}
else
  {$trx_int=flookup("bppbno_int","bppb","bppbno='$trx_no'");}
}
$mod2="2U";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
$gen_no_int=$rscomp["gen_nomor_int"];
$logo_company=$rscomp["logo_company"];

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var jdok = document.form.txtjenis_dok.value;
    var noaju = document.form.txtnomor_aju.value;
    var tgaju = document.form.txttanggal_aju.value;
    var nopen = document.form.txtnomor_daftar.value;
    var tgpen = document.form.txttanggal_daftar.value;

    if (jdok == '') { alert('Jenis Dokumen Kosong'); valid = false;}
    else if (noaju == '') { alert('Nomor Aju Kosong'); valid = false;}
    else if (noaju != '-' && tgaju == '') { alert('Tanggal Aju Kosong'); valid = false;}
    else if (nopen == '') { alert('Nomor Daftar Kosong'); valid = false;}
    else if (nopen != '-' && tgpen == '') { alert('Tanggal Daftar Kosong'); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
if($mod=="2U") {
  ?>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' enctype='multipart/form-data' action='s_upd_dok_pab.php?mod=<?php echo $mod; ?>&trx=<?php echo $jen_trx; ?>&dtrx=<?php echo $tgl_trx; ?>&dtrx2=<?php echo $tgl_trx2; ?>' onsubmit='return validasi()'>
          <div class='col-md-3'>              
            <?php if($gen_no_int=="Y") { ?>
              <div class='row'>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Nomor Transaksi *</label>
                    <input type='text' class='form-control' name='txttrx_no' readonly value='<?php echo $trx_no;?>' >
                  </div>
                </div>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>.</label>
                    <input type='text' class='form-control' name='txttrx_int' readonly value='<?php echo $trx_int;?>' >
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <div class='form-group'>
                <label>Nomor Transaksi *</label>
                <input type='text' class='form-control' name='txttrx_no' readonly value='<?php echo $trx_no;?>' >
              </div>
            <?php } ?>
            <?php 
            if(substr($trx_no, 0,2)=="SJ") 
              { $tbl="bppb"; $fld="bppbno"; $fld2="bppbdate"; $jtrx="Status KB Out"; } 
            else 
              { $tbl="bpb"; $fld="bpbno"; $fld2="bpbdate"; $jtrx="Status KB In"; }
            $sql="select *,$fld2 trx_date,supplier,sum(qty) totqty,sum(qty*price) totval from $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier 
            where $fld='$trx_no' group by $fld";
          #echo $sql;
            $rs=mysql_fetch_array(mysql_query($sql));
            $trx_date=fd_view($rs['trx_date']);
            $supplier=$rs['supplier'];
            $invno=$rs['invno'];
            $jenis_dok=$rs['jenis_dok'];
            $nomor_aju=$rs['nomor_aju'];
            $tanggal_aju=fd_view($rs['tanggal_aju']);
            $nomor_daftar=$rs['bcno'];
            $tanggal_daftar=fd_view($rs['bcdate']);
            $nomor_fp=$rs['no_fp'];
            $totqty=fn($rs['totqty'],3);
            $totval=fn($rs['totval'],3);
            $tanggal_fp=fd_view($rs['tgl_fp']);
            $sqlbm="select * from detail_bm where jenis_dok='$jenis_dok' and bcno='$nomor_daftar'
            and bcdate='".fd($tanggal_daftar)."'";
            $rsbm=mysql_fetch_array(mysql_query($sqlbm));
            $bm=$rsbm['bm'];
            $ppn=$rsbm['ppn'];
            $pph=$rsbm['pph'];
            $bmtp=$rsbm['bmtp'];
            ?>        
            <div class='form-group'>
              <label>Tgl. Transaksi *</label>
              <input type='text' class='form-control' name='txttrx_date' readonly value='<?php echo $trx_date;?>' >
            </div>        
            <div class='form-group'>
              <label>Supplier *</label>
              <input type='text' class='form-control' name='txtsupplier' readonly value='<?php echo $supplier;?>' >
            </div>        
            <div class='form-group'>
              <label>Nomor SJ/Inv</label>
              <input type='text' class='form-control' name='txtinvno' value='<?php echo $invno;?>' >
            </div>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
            <p>
            </div>
            <div class='col-md-3'>          
              <div class='form-group'>
                <label>Jenis Dokumen</label>
                <select class='form-control select2' style='width: 100%;' name='txtjenis_dok'>
                  <?php 
                  $sql = "select nama_pilihan isi, nama_pilihan tampil from masterpilihan where kode_pilihan='$jtrx'";
                  IsiCombo($sql,$jenis_dok,'Pilih Jenis Dokumen');
                  ?>
                </select>
              </div>        
              <div class='row'>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Nomor Pengajuan</label>
                    <input type='text' class='form-control' name='txtnomor_aju' placeholder='Masukkan Nomor Pengajuan' value='<?php echo $nomor_aju;?>' >
                  </div>
                </div>
                <div class='col-md-6'>        
                  <div class='form-group'>
                    <label>Tgl. Pengajuan *</label>
                    <input type='text' class='form-control' name='txttanggal_aju' id='datepicker1' placeholder='Masukkan Tgl. Pengajuan' value='<?php echo $tanggal_aju;?>' >
                  </div>
                </div>
              </div>
              <div class='row'>
                <div class='col-md-6'>        
                  <div class='form-group'>
                    <label>Nomor Daftar</label>
                    <input type='text' class='form-control' name='txtnomor_daftar' placeholder='Masukkan Nomor Daftar' value='<?php echo $nomor_daftar;?>' >
                  </div>
                </div>        
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Tgl. Daftar</label>
                    <input type='text' class='form-control' name='txttanggal_daftar' id='datepicker2' placeholder='Masukkan Tgl. Daftar' value='<?php echo $tanggal_daftar;?>' >
                  </div>
                </div>
              </div>
              <div class='row'>
                <div class='col-md-6'>        
                  <div class='form-group'>
                    <label>Nomor FP</label>
                    <input type='text' class='form-control' name='txtnomor_fp' placeholder='Masukkan Nomor FP' value='<?php echo $nomor_fp;?>' >
                  </div>
                </div>        
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Tgl. FP</label>
                    <input type='text' class='form-control' name='txttanggal_fp' id='datepicker3' placeholder='Masukkan Tgl. FP' value='<?php echo $tanggal_fp;?>' >
                  </div>
                </div>
                <div class='col-md-6'>        
                  <div class='form-group'>
                    <label>Total Qty</label>
                    <input type='text' readonly class='form-control' name='txttotqty' value='<?php echo $totqty;?>' >
                  </div>
                </div>
                <div class='col-md-6'>        
                  <div class='form-group'>
                    <label>Total Value</label>
                    <input type='text' readonly class='form-control' name='txttotval' value='<?php echo $totval;?>' >
                  </div>
                </div>
              </div>
            </div>        
            <?php if($jenis_dok=="BC 2.3") { ?> 
              <div class='col-md-3'>                               
                <div class='form-group'>
                  <label>Bea Masuk</label>
                  <input type='text' class='form-control' name='txtbm' placeholder='Masukkan Bea Masuk' value='<?php echo $bm;?>' >
                </div> 
                <div class='form-group'>
                  <label>BMTP</label>
                  <input type='text' class='form-control' name='txtbmtp' placeholder='Masukkan BMTP' value='<?php echo $bmtp;?>' >
                </div>                 
                <div class='form-group'>
                  <label>PPN</label>
                  <input type='text' class='form-control' name='txtppn' placeholder='Masukkan PPN' value='<?php echo $ppn;?>' >
                </div>
                <div class='form-group'>
                  <label>PPH</label>
                  <input type='text' class='form-control' name='txtpph' placeholder='Masukkan PPH' value='<?php echo $pph;?>' >
                </div> 
              </div>
            <?php } ?> 
            <div class='form-group'>
              <label for='exampleInputFile'>Attach File</label>
              <input type='file' name='txtfile' accept='.pdf'>
            </div>
          </form> 
          <div class='col-md-12'>
            <table id="examplefix" class="display responsive" style="width:100%">
              <thead>
                <tr>
                  <?php if($logo_company=="Z") { ?>
                    <th>JO #</th>
                    <th>Part #</th>
                  <?php } else { ?>
                    <th>ID</th>
                    <th>WS #</th>
                    <th>Style #</th>
                  <?php } ?>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Qty</th>
                  <th>Satuan</th>
                  <th>Curr</th>
                  <th>Price Ori</th>
                  <th>Ket</th>
                  <?php if ($jen_trx=="Pemasukan") { ?>
                    <th>Tipe PO</th>  
                  <?php } ?>  
                  <th>Value</th>
                  <th>Price BC Per Unit</th>
                  <th>Konfirmasi</th>
                </tr>
              </thead>
              <tbody>
                <?php
              # QUERY TABLE
                if (substr($trx_no,0,2)!="SJ") 
                  { $tbl="bpb"; $fldtrx="bpbno"; $trxdate="bpbdate";
                if (substr($trx_no,0,2)=="FG")
                  { $tblmst="masterstyle"; }
                else
                  { $tblmst="masteritem"; }
                $tipe = "left join (select pono, tipe_com from po_header ph 
                inner join po_header_draft phd on phd.id = ph.id_draft) tipe on trx.pono = tipe.pono";
                $tipe_tampil = ",tipe_com";
              } 
              else 
                { $tbl="bppb"; $fldtrx="bppbno"; $trxdate="bppbdate";
              if (substr($trx_no,3,2)=="FG")
                { $tblmst="masterstyle"; }
              else
                { $tblmst="masteritem"; }
              $tipe = "";
              $tipe_tampil = "";              
            }
            if($tblmst=="masterstyle") { $flddesc="mi.itemname"; } else { $flddesc="mi.itemdesc"; }
            $sql="select trx.id,$fldtrx,$trxdate,trx.jenis_dok,trx.id line_id,mi.goods_code,concat($flddesc,' ',ifnull(mi.color,''),' ',ifnull(mi.size,'')) item,
            trx.qty,trx.unit,trx.curr,price price_ori,ifnull(price_bc,trx.price) price,tmpjo.kpno,tmpjo.jo_no,tmpjo.styleno,trx.remark $tipe_tampil from $tbl trx inner join $tblmst mi on trx.id_item=mi.id_item 
            left join 
            (select jo.jo_no,jod.id_jo,ac.kpno,ac.styleno from jo_det jod inner join so on jod.id_so=so.id 
            inner join act_costing ac on so.id_cost=ac.id 
            inner join jo on jod.id_jo=jo.id 
            group by jod.id_jo) 
            tmpjo on tmpjo.id_jo=trx.id_jo
            $tipe
            where trx.$fldtrx='$trx_no' ";
              #echo $sql;
            $query = mysql_query($sql);
            $i = 0;
            while($data = mysql_fetch_array($query))
              { $id = $data['line_id'];
            echo "<tr>";
            if($logo_company=="Z")
            {
              echo "
              <td>$data[jo_no]</td>";
            }
            else
            {
              echo "
              <td>$data[id]</td>
              <td>$data[kpno]</td>";
            }
            echo "
            <td>$data[styleno]</td>
            <td>$data[goods_code]</td>
            <td>$data[item]</td>
            <td>$data[qty]</td>
            <td>$data[unit]</td>
            <td>$data[curr]</td>
            <td>".fn($data['price_ori'],3)."</td>
            <td>$data[remark]</td>";
            if($jen_trx == "Pemasukan")
            {
              echo "<td>$data[tipe_com]</td>";
            }  


            $totnilnya = $data['qty'] * $data['price'];
            $harga = number_format($data['price'],3);
            $iddata    = $data['id'];
            $dtrx    = $data[$trxdate];
            $noid    = $data[$fldtrx];

            if($data['jenis_dok']=="BC 2.6.2" or $data['jenis_dok']=="BC 2.6.1")
              { echo "<td><input type ='text' size='6' name ='totnil[$id]' value='$totnilnya'></td>"; }
            else
              { echo "<td>".fn($data['qty']*$data['price'],3)."</td>"; }
            echo "<td><form method = 'post' action='update_harga.php?iddata=$iddata&dtrx=$dtrx&noid=$noid&jen_trx=$jen_trx&tbl=$tbl'; ><input type = 'text' size='6'  name = 'editharga' value='$harga'></td>";
            echo "<td><button type='submit' name='update' class='btn btn-primary'>Update</button></form></td>";
            echo "</tr>";
            $i++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<?php } elseif($mod=="2U_new") {
  ?>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form' enctype='multipart/form-data' action='s_upd_dok_pab.php?mod=<?php echo $mod; ?>&trx=<?php echo $jen_trx; ?>
        &dtrx=<?php echo $tgl_trx; ?>&dtrx2=<?php echo $tgl_trx2; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>    

          <?php if($gen_no_int=="Y") { ?>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Nomor Transaksi *</label>
                  <input type='text' class='form-control' name='txttrx_no' readonly value='<?php echo $trx_no;?>' >
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>.</label>
                  <input type='text' class='form-control' name='txttrx_int' readonly value='<?php echo $trx_int;?>' >
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class='form-group'>
              <label>Nomor Transaksi *</label>
              <input type='text' class='form-control' name='txttrx_no' readonly value='<?php echo $trx_no;?>' >
            </div>
          <?php } ?>
          <?php 
          if(substr($trx_no, 0,2)=="SJ") 
            { $tbl="bppb"; $fld="bppbno"; $fld2="bppbdate"; $jtrx="Status KB Out"; } 
          else 
            { $tbl="bpb"; $fld="bpbno"; $fld2="bpbdate"; $jtrx="Status KB In"; }
          $sql="select *,$fld2 trx_date,supplier,sum(qty) totqty,sum(qty*price) totval from $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier 
          where $fld='$trx_no' group by $fld";
          // echo $sql;
          $rs=mysql_fetch_array(mysql_query($sql));
          $trx_date=fd_view($rs['trx_date']);
          $supplier=$rs['supplier'];
          $invno=$rs['invno'];
          $jenis_dok=$rs['jenis_dok'];
          $nomor_aju=$rs['nomor_aju'];
          $tanggal_aju=fd_view($rs['tanggal_aju']);
          $nomor_daftar=$rs['bcno'];
          $tanggal_daftar=fd_view($rs['bcdate']);
          $nomor_fp=$rs['no_fp'];
          $totqty=fn($rs['totqty'],3);
          $totval=fn($rs['totval'],3);
          $tanggal_fp=fd_view($rs['tgl_fp']);
          $sqlbm="select * from detail_bm where jenis_dok='$jenis_dok' and bcno='$nomor_daftar'
          and bcdate='".fd($tanggal_daftar)."'";
          $rsbm=mysql_fetch_array(mysql_query($sqlbm));
          $bm=$rsbm['bm'];
          $ppn=$rsbm['ppn'];
          $pph=$rsbm['pph'];
          $bmtp=$rsbm['bmtp'];
          ?>        
          <div class='form-group'>
            <label>Tgl. Transaksi *</label>
            <input type='text' class='form-control' name='txttrx_date' readonly value='<?php echo $trx_date;?>' >
          </div>        
          <div class='form-group'>
            <label>Supplier *</label>
            <input type='text' class='form-control' name='txtsupplier' readonly value='<?php echo $supplier;?>' >
          </div>        
          <div class='form-group'>
            <label>Nomor SJ/Inv</label>
            <input type='text' class='form-control' name='txtinvno' value='<?php echo $invno;?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          <p>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Jenis Dokumen</label>
              <select class='form-control select2' style='width: 100%;' name='txtjenis_dok' id='jenis_dok'>
                <?php 
                $sql = "SELECT nama_pilihan isi, nama_pilihan tampil FROM masterpilihan WHERE kode_pilihan='$jtrx'";
                IsiCombo($sql, $jenis_dok, 'Pilih Jenis Dokumen');
                ?>
              </select>
            </div>        

            <div class='form-group'>
              <label>Nomor Daftar</label>
              <select class='form-control select2' style='width: 100%;' name='txtnomor_daftar' id='nomor_daftar'>
                <option value='<?php echo $nomor_daftar;?>'><?php echo $nomor_daftar;?></option>
              </select>
            </div>

            <div class='row'>
              <div class='col-md-12'>
                <div class='form-group'>
                  <label>Tgl. Daftar</label>
                  <input type='text' class='form-control' name='txttanggal_daftar' placeholder='Masukkan Tgl. Daftar' value='<?php echo $tanggal_daftar;?>' readonly>
                    <!-- <label>Tgl. Pengajuan *</label>
                      <input type='text' class='form-control' name='txttanggal_aju' placeholder='Masukkan Tgl. Pengajuan' value='<?php echo $tanggal_aju;?>' readonly> -->
                    </div>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label>Nomor Pengajuan</label>
                      <input type='text' class='form-control' name='txtnomor_aju' id="txtnomor_aju" placeholder='Masukkan Nomor Pengajuan' value='<?php echo $nomor_aju;?>' readonly>
                    </div>
                  </div>
                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label>Tgl. Pengajuan *</label>
                      <input type='text' class='form-control' name='txttanggal_aju' placeholder='Masukkan Tgl. Pengajuan' value='<?php echo $tanggal_aju;?>' readonly>
                    </div>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-6'>        
                    <div class='form-group'>
                      <label>Nomor FP</label>
                      <input type='text' class='form-control' name='txtnomor_fp' placeholder='Masukkan Nomor FP' value='<?php echo $nomor_fp;?>' >
                    </div>
                  </div>        
                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label>Tgl. FP</label>
                      <input type='text' class='form-control' name='txttanggal_fp' id='datepicker3' placeholder='Masukkan Tgl. FP' value='<?php echo $tanggal_fp;?>' >
                    </div>
                  </div>
                  <div class='col-md-6'>        
                    <div class='form-group'>
                      <label>Total Qty</label>
                      <input type='text' readonly class='form-control' name='txttotqty' value='<?php echo $totqty;?>' >
                    </div>
                  </div>
                  <div class='col-md-6'>        
                    <div class='form-group'>
                      <label>Total Value</label>
                      <input type='text' readonly class='form-control' name='txttotval' value='<?php echo $totval;?>' >
                    </div>
                  </div>
                </div>
              </div>        
              <?php if($jenis_dok=="BC 2.3") { ?> 
                <div class='col-md-3'>                               
                  <div class='form-group'>
                    <label>Bea Masuk</label>
                    <input type='text' class='form-control' name='txtbm' placeholder='Masukkan Bea Masuk' value='<?php echo $bm;?>' >
                  </div> 
                  <div class='form-group'>
                    <label>BMTP</label>
                    <input type='text' class='form-control' name='txtbmtp' placeholder='Masukkan BMTP' value='<?php echo $bmtp;?>' >
                  </div>                 
                  <div class='form-group'>
                    <label>PPN</label>
                    <input type='text' class='form-control' name='txtppn' placeholder='Masukkan PPN' value='<?php echo $ppn;?>' >
                  </div>
                  <div class='form-group'>
                    <label>PPH</label>
                    <input type='text' class='form-control' name='txtpph' placeholder='Masukkan PPH' value='<?php echo $pph;?>' >
                  </div> 
                </div>
              <?php } ?> 
              <div class='form-group'>
                <label for='exampleInputFile'>Attach File</label>
                <input type='file' name='txtfile' accept='.pdf'>
              </div>
            </form> 
            <div class='col-md-12'>
              <table id="examplefix" class="display responsive" style="width:100%; font-size: 13px;">
              <thead>
                <tr>
                  <?php if($logo_company=="Z") { ?>
                    <th>JO #</th>
                    <th>Part #</th>
                  <?php } else { ?>
                    <th>ID</th>
                    <th>WS #</th>
                    <th>Style #</th>
                  <?php } ?>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Qty</th>
                  <th>Satuan</th>
                  <th>Curr</th>
                  <th>Price Ori</th>
                  <th>Ket</th>
                  <?php if ($jen_trx=="Pemasukan") { ?>
                    <th>Tipe PO</th>  
                  <?php } ?>  
                  <th>Value</th>
                  <th>Price BC Per Unit</th>
                  <th>Currency</th>
                  <th>Unit BC</th>
                  <th>Qty BC</th>
                  <th>Konfirmasi</th>
                </tr>
              </thead>
              <tbody>
                <?php
      // LOGIKA QUERY
                if (substr($trx_no,0,2)!="SJ") {
                  $tbl="bpb"; $fldtrx="bpbno"; $trxdate="bpbdate";
                  $tblmst = (substr($trx_no,0,2)=="FG") ? "masterstyle" : "masteritem";
                  $tipe = "LEFT JOIN (SELECT pono, tipe_com FROM po_header ph INNER JOIN po_header_draft phd ON phd.id = ph.id_draft) tipe ON trx.pono = tipe.pono";
                  $tipe_tampil = ",tipe_com";
                } else {
                  $tbl="bppb"; $fldtrx="bppbno"; $trxdate="bppbdate";
                  $tblmst = (substr($trx_no,3,2)=="FG") ? "masterstyle" : "masteritem";
                  $tipe = ""; $tipe_tampil = "";
                }

                $flddesc = ($tblmst=="masterstyle") ? "mi.itemname" : "mi.itemdesc";

                $sql = "SELECT trx.id, $fldtrx, $trxdate, trx.jenis_dok, trx.id line_id, mi.goods_code, 
                CONCAT($flddesc, ' ', IFNULL(mi.color,''), ' ', IFNULL(mi.size,'')) item,
                trx.qty, trx.unit, trx.curr, price price_ori, IFNULL(price_bc, trx.price) price, 
                tmpjo.kpno, tmpjo.jo_no, tmpjo.styleno, trx.remark, trx.satuan_bc, trx.qty_bc, 
                COALESCE(trx.curr_bc, trx.curr) curr_bc $tipe_tampil 
                FROM $tbl trx 
                INNER JOIN $tblmst mi ON trx.id_item = mi.id_item 
                LEFT JOIN (
                  SELECT jo.jo_no, jod.id_jo, ac.kpno, ac.styleno 
                  FROM jo_det jod 
                  INNER JOIN so ON jod.id_so = so.id 
                  INNER JOIN act_costing ac ON so.id_cost = ac.id 
                  INNER JOIN jo ON jod.id_jo = jo.id 
                  GROUP BY jod.id_jo
                  ) tmpjo ON tmpjo.id_jo = trx.id_jo 
                $tipe 
                WHERE trx.$fldtrx = '$trx_no'";

                $query = mysql_query($sql);
                while($data = mysql_fetch_array($query)) {
                  $id = $data['line_id'];
                  $harga = number_format($data['price'], 4);
                  $qty_bc = isset($data['qty_bc']) && $data['qty_bc'] !== null ? number_format($data['qty_bc'], 4) : '0.0000';
                  $satuan_bc = !empty($data['satuan_bc']) ? $data['satuan_bc'] : '';
                  $curr_bc = !empty($data['curr_bc']) ? $data['curr_bc'] : '';
                  $iddata = $data['id'];
                  $dtrx = $data[$trxdate];
                  $noid = $data[$fldtrx];
                  $totnilnya = $data['qty'] * $data['price'];

                  echo "<tr><form method='post' action='update_harga.php?iddata=$iddata&dtrx=$dtrx&noid=$noid&jen_trx=$jen_trx&tbl=$tbl'>";

                  if($logo_company=="Z") {
                    echo "<td>{$data['jo_no']}</td><td>{$data['kpno']}</td>";
                  } else {
                    echo "<td>{$data['id']}</td><td>{$data['kpno']}</td><td>{$data['styleno']}</td>";
                  }

                  echo "
                  <td>{$data['goods_code']}</td>
                  <td>{$data['item']}</td>
                  <td>{$data['qty']}</td>
                  <td>{$data['unit']}</td>
                  <td>{$data['curr']}</td>
                  <td>".fn($data['price_ori'],3)."</td>
                  <td>{$data['remark']}</td>";

                  if($jen_trx == "Pemasukan") {
                    echo "<td>{$data['tipe_com']}</td>";
                  }

                  if($data['jenis_dok']=="BC 2.6.2" || $data['jenis_dok']=="BC 2.6.1") {
                    echo "<td><input type='text' class='form-control' size='8' name='totnil[$id]' value='$totnilnya'></td>";
                  } else {
                    echo "<td>".fn($totnilnya,3)."</td>";
                  }

                  echo "
                  <td><input type='text' class='form-control' size='8' name='editharga' value='$harga'></td>

                  <td>
                  <select class='form-control select2' name='curr_bc'>
                  <option value='' ".(empty($curr_bc) ? 'selected' : '').">Pilih</option>";
                  $qCurr = mysql_query("SELECT DISTINCT nama_pilihan FROM whs_master_pilihan WHERE type_pilihan = 'currency'");
                  while ($row = mysql_fetch_array($qCurr)) {
                    $curr = htmlspecialchars($row['nama_pilihan']);
                    $selected = ($curr == $curr_bc) ? "selected" : "";
                    echo "<option value='$curr' $selected>$curr</option>";
                  }
                  echo "</select>
                  </td>

                  <td>
                  <select class='form-control select2' name='satuan_bc'>
                  <option value='' ".(empty($satuan_bc) ? 'selected' : '').">Pilih</option>";
                  $qSat = mysql_query("SELECT DISTINCT satuan_ceisa FROM mapping_satuan_ceisa ORDER BY satuan_ceisa ASC");
                  while ($row2 = mysql_fetch_array($qSat)) {
                    $satuan = htmlspecialchars($row2['satuan_ceisa']);
                    $selected = ($satuan == $satuan_bc) ? "selected" : "";
                    echo "<option value='$satuan' $selected>$satuan</option>";
                  }
                  echo "</select>
                  </td>

                  <td><input type='text' class='form-control' min='0' size='8' name='qty_bc' value='$qty_bc'></td>
                  <td><button type='submit' name='update' class='btn btn-primary'>Update</button></td>
                  </form></tr>";
                }
                ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
     $(document).ready(function() {

    // var jenisDokElement = document.getElementById('jenis_dok').value;
    // var jenisDok = jenisDokElement ? jenisDokElement.value : null;
    //   jenisDok = jenisDok.substring(3); // Menghilangkan 3 karakter pertama
    // jenisDok = jenisDok.replace(/\./g, ''); // Menghapus semua titik (.)
    // // alert(jenisDok)
    // $.ajax({
    //     url: 'getNomorPengajuan.php', // Script untuk mengambil nomor pengajuan berdasarkan jenis dokumen
    //     method: 'GET',
    //     data: { jenis_dok: jenisDok },
    //     success: function(response) {
    //       console.log(response);
    //       $('#nomor_aju').html(response); // Update pilihan nomor pengajuan
    //     }
    //   });
  });
</script>

<?php } else { ?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">List <?php echo $jen_trx; ?></h3>
    </div>
    <div class="box-body">
      <table id="examplefix" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>Nomor Trans</th>
            <?php if ($jen_trx=="Pemasukan") { ?>
              <th>PO #</th>
            <?php } ?>
            <th>Tanggal Trans</th>
            <th>Pemasok</th>
            <th>No. Invoice</th>
            <th>Jenis BC</th>
            <th>No. Daftar</th>
            <th>Tgl. Daftar</th>
            <th>No. Aju</th>
            <th>Tgl. Aju</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
        # QUERY TABLE
          if ($jen_trx=="Pemasukan") 
            { $tbl="bpb"; $fldtgl="bpbdate"; $fldtrxpar="bpbno"; $fldtrx="if(bpbno_int!='',bpbno_int,bpbno)"; } 
          else 
            { $tbl="bppb"; $fldtgl="bppbdate"; $fldtrxpar="bppbno"; $fldtrx="if(bppbno_int!='',bppbno_int,bppbno)"; }
          $sql="SELECT a.*,supplier,$fldtgl trx_date,$fldtrx trx_no,$fldtrxpar trx_no_par 
          FROM $tbl a inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          where $fldtgl BETWEEN '$tgl_trx' and '$tgl_trx2'
          GROUP BY $fldtrxpar order by $fldtgl desc";
        // echo $sql;
          $query = mysql_query($sql);
          while($data = mysql_fetch_array($query))
            { echo "<tr>";
          echo "
          <td>$data[trx_no]</td>";
          if ($jen_trx=="Pemasukan") 
          { 
            echo "
            <td>$data[pono]</td>";
          }
          echo "
          <td>".fd_view($data['trx_date'])."</td>
          <td>$data[supplier]</td>
          <td>$data[invno]</td>
          <td>$data[jenis_dok]</td>
          <td>$data[bcno]</td>
          <td>$data[bcdate]</td>
          <td>$data[nomor_aju]</td>
          <td>$data[tanggal_aju]</td>
          
          <td>
          <a href='?mod=2U_new&trx=$jen_trx&dtrx=$tgl_trx&dtrx2=$tgl_trx2&noid=$data[trx_no_par]'
          data-toggle='tooltip' title='edit'><i class='fa fa-pencil-square-o text-success' aria-hidden='true'></i>
          </a>
          </td>
          <td>
          <a href='show_pdf.php?trx=$jen_trx&id=$data[trx_no_par]'
          data-toggle='tooltip' title='Attach File'><i class='fa fa-paperclip'></i>
          </a>
          </td>"; 
          echo "</tr>";
        }

        // <td>
        //   <a href='?mod=$mod2&trx=$jen_trx&dtrx=$tgl_trx&dtrx2=$tgl_trx2&noid=$data[trx_no_par]'
        //   data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
        //   </a>
        //   </td>
        ?>
      </tbody>
    </table> 
  </div>
</div>
<?php } 
# END COPAS ADD
?>