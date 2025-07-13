<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$akses = flookup("bpb_po","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $jenis_company = $rscomp["jenis_company"];
  $link_to_security = $rscomp["link_to_security"];
  $whs_see_price = $rscomp["whs_see_price"];
  $whs_input_bc_dok = $rscomp['whs_input_bc_dok'];
  if($whs_see_price=="N")
  { $hidepx = "hidden"; }
  else
  { $hidepx = "text"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
$id=$_GET['noid'];
$rs=mysql_fetch_array(mysql_query("select * from bpb where bpbno='$id'"));
$bpbno=$rs['bpbno'];
$bpbno_int=$rs['bpbno_int'];
$cekcfm = flookup("bpbno","bpb","bpbno='$bpbno' and confirm='Y'");
if ($cekcfm!="") 
{ echo "<script>alert('BPB Sudah Terkonfirmasi'); window.location.href='../forms/?mod=26v&mode=Bahan_Baku';</script>"; }
$bpbdate=fd_view($rs['bpbdate']);
$id_supplier=$rs['id_supplier'];
$id_sec=$rs['id_sec'];
$pono=$rs['pono'];
$jam_masuk=$rs['jam_masuk'];
if($link_to_security=="Y")
{ $read=" readonly "; }
else
{ $read=" "; }
$nomor_mobil=$rs['nomor_mobil'];
$invno=$rs['invno'];
$status_kb=$rs['jenis_dok'];
$bcaju=$rs['nomor_aju'];
$tglaju=fd_view($rs['tanggal_aju']);
$bcno=$rs['bcno'];
$bcdate=fd_view($rs['bcdate']);
$no_fp=$rs['no_fp'];
$tglfp=fd_view($rs['tgl_fp']);
$txttujuan=$rs['tujuan'];

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  <?php if ($nm_company!="PT. Sinar Gaya Busana") { ?>
    function getTujuan(cri_item)
    {   var html = $.ajax
        ({  type: "POST",
            url: 'ajax.php?modeajax=cari_tujuan',
            data: "cri_item=" +cri_item,
            async: false
        }).responseText;
        if(html)
          { $("#cbotujuan").html(html); }
    }
  <?php } ?>
  function validasi()
  { var filenya = document.form.txtfile.value;
    if (filenya == '') { swal({ title: 'File Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
?>
<form method='post' name='form' action='s_bpb_po_ed.php?mod=<?php echo $mod;?>&mode=<?php echo $mode;?>' onsubmit='return validasi()'>
  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <div class='col-md-3'>
          <?php
          echo "<div class='form-group'>";
            echo "<label>$caption[2]</label>";
            echo "<input type='hidden' class='form-control' name='txtbpbno' readonly value='$bpbno'>";
            if($bpbno_int!="") { $vbpbno=$bpbno_int; } else { $vbpbno=$bpbno; }
            echo "<input type='text' class='form-control' name='txtbpbno2' readonly value='$vbpbno'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$caption[3] *</label>";
            echo "<input type='text' class='form-control' name='txtbpbdate' id='datepicker2' readonly placeholder='$cmas $caption[3]' value='$bpbdate'>";
          echo "</div>";
          ?>
        </div>
        <div class='col-md-3'>
          <?php
          echo "<div class='form-group'>";
            echo "<label>Supplier *</label>";
            echo "<select class='form-control select2' style='width: 100%;' id='cbosup' name='txtid_supplier'>";
            $sql="select id_supplier isi,supplier tampil from mastersupplier where 
              id_supplier='$id_supplier'";
            IsiCombo($sql,$id_supplier,'');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c40 *</label>";
            echo "<input type='text' id='txtpono' class='form-control' name='txtpono' 
              value='$pono' readonly>";
          echo "</div>";
          ?>
        </div>
        <div class='col-md-3'>
          <?php
          echo "<div class='form-group'>";
            echo "<label>$c36</label>";
            echo "<input type='text' class='form-control' name='txtjam_masuk' id='txtjam_masuk'  
              placeholder='$cmas $c36' value='$jam_masuk' $read>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c39</label>";
            echo "<input type='text' class='form-control' name='txtnomor_mobil' id='txtnomor_mobil' 
              placeholder='$cmas $c39' value='$nomor_mobil' $read>";
          echo "</div>";
          ?>
        </div>
        <div class='col-md-3'>
          <?php 
          echo "<div class='form-group'>";
            echo "<label>$c41 *</label>";
            echo "<input type='text' class='form-control' name='txtinvno' id='txtinvno' 
              placeholder='$cmas $c41' value='$invno' $read>";
          echo "</div>";
          if($link_to_security=="Y")
          { echo "<div class='form-group'>";
              echo "<label>Id Security *</label>";
              echo "<select class='form-control select2' style='width: 100%;' id='cbosec' name='txtid_sec'>";
              $sql = "select a.id isi,concat(nomor_sj,'|',supplier,'|',a.pono,'|',jenis_barang) tampil 
                from list_in_out a inner join mastersupplier s on a.id_supplier=s.id_supplier
                left join bpb d on a.id=d.id_sec where 
                a.id='$id_sec' ";
              IsiCombo($sql,$id_sec,$cpil.' Id Security');
              echo "</select>";
            echo "</div>";
          }
          ?>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-3'>
          <?php
            if ($st_company!="MULTI_WHS")
            { echo "<div class='form-group'>";
                echo "<label>$c46 *</label>";
                if ($st_company=="KITE") 
                { $status_kb_cri="Status KITE In"; }
                else
                { $status_kb_cri="Status KB In"; }
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                      kode_pilihan='$status_kb_cri' order by nama_pilihan";
                echo "<select class='form-control select2' style='width: 100%;' onchange='getTujuan(this.value)' name='txtstatus_kb'>";
                IsiCombo($sql,$status_kb,$cpil.' '.$c46);
                echo "</select>";
              echo "</div>";
              echo "<div class='form-group'>";
                echo "<label>$c47</label>";
                echo "<select class='form-control select2' style='width: 100%;' id='cbotujuan' name='txttujuan' disabled>";
                if ($bpbno!="")
                { $sql = "select nama_pilihan isi,nama_pilihan tampil 
                from masterpilihan where kode_pilihan='$status_kb'";
                IsiCombo($sql,trim($txttujuan),$cpil.' '.$c47);
                }
                echo "</select>";
              echo "</div>";
            }
          ?>
          </div>
          <div class='col-md-3'>
          <?php
          if($whs_input_bc_dok=="Y") { $fldupd=""; } else { $fldupd=" readonly "; }
          echo "
          <div class='row'>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Nomor Aju</label>
                <input type='text' class='form-control' $fldupd name='txtbcaju' placeholder='Masukan Nomor Aju' readonly value='$bcaju'>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>Tanggal Aju</label>
                <input type='text' class='form-control' $fldupd id='datepicker3' readonly name='txttglaju' readonly placeholder='Masukan Tanggal Aju' value='$tglaju'>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>$c42 *</label>
                <input type='text' class='form-control' $fldupd name='txtbcno' readonly placeholder='$cmas $c42' value='$bcno'>
              </div>
            </div>
            <div class='col-md-6'>
              <div class='form-group'>
                <label>$c43 *</label>
                <input type='text' class='form-control' $fldupd id='datepicker1' readonly name='txtbcdate' placeholder='$cmas $c43' value='$bcdate'>
              </div>
            </div>
          </div>";
          ?>
          </div>
          <div class='col-md-3'>
            <?php
            echo "
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Nomor Faktur Pajak</label>
                  <input type='text' class='form-control' name='txtno_fp' placeholder='Masukan Nomor Faktur Pajak' value='$no_fp'>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group'>
                  <label>Tanggal Faktur Pajak</label>
                  <input type='text' class='form-control' id='datepicker3' readonly name='txttglfp' placeholder='Masukan Tanggal Faktur Pajak' value='$tglfp'>
                </div>
              </div>
            </div>";
            ?>
            </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <table id="examplefix3" class="display responsive" style="width:100%;font-size:11px;">
            <thead>
              <tr>
                <?php if($jenis_company=="VENDOR LG") { ?>
                  <th>Nomor JO</th>
                <?php } else { ?>
                  <th>Nomor WS</th>
                <?php } ?> 
                <th>Kode Bahan Baku</th>
                <th>Deskripsi</th>
                <th>Qty PO</th>
                <th>Balance</th>
                <th>Satuan</th>
                <th>Qty BPB</th>
                <th>Nomor Rak</th>
                <th>Berat Bersih</th>
                <th>Berat Kotor</th>
                <th>Curr</th>
                <th>Price</th>
                <th>Keterangan</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $ponya=$pono;
              $jenispo=flookup("jenis","po_header","pono='$ponya'");
              if ($jenis_company=="VENDOR LG" or $jenispo=="N")
              { $sql_join=" s.id_gen=d.id_item "; }
              else
              { $sql_join=" s.id_gen=d.id_gen "; }
              if($jenispo=="N")
              { $sql="select tmpbpb2.nomor_rak,tmpbpb2.berat_bersih,tmpbpb2.berat_kotor,tmpbpb2.remark,
                s.id line_item,d.id_item,'' kpno,d.goods_code,d.itemdesc,s.qty,s.unit, 
                s.id_jo,tmpbpb2.price,tmpbpb2.curr,tmpbpb.qty_bpb,tmpbpb2.qty_bpb qty_bpb2,
                tmpbpb2.id_po_item,tmpbpb2.id_line from po_header a inner join po_item s on a.id=s.id_po 
                inner join masteritem d on $sql_join 
                left join 
                (select id_po_item,sum(qty) qty_bpb from bpb where pono='$ponya' and bpbno!='$bpbno' group by id_po_item) tmpbpb 
                on tmpbpb.id_po_item=s.id  
                inner join 
                (select curr,price,id_po_item,sum(qty) qty_bpb,nomor_rak,berat_bersih,berat_kotor,remark,id id_line from bpb 
                  where pono='$ponya' and bpbno='$bpbno' group by id_po_item) tmpbpb2 
                on tmpbpb2.id_po_item=s.id  
                where a.pono='$ponya' and s.cancel='N' 
                order by s.id ";
              }
              else
              { $sql="select jo.jo_no,tmpbpb2.nomor_rak,tmpbpb2.berat_bersih,tmpbpb2.berat_kotor,tmpbpb2.remark,
                s.id line_item,d.id_item,ac.kpno,d.goods_code,concat(d.itemdesc,' ',d.color,' ',d.size,' ',d.add_info) itemdesc,s.qty,s.unit, 
                s.id_jo,tmpbpb2.price,tmpbpb2.curr,tmpbpb.qty_bpb,tmpbpb2.qty_bpb qty_bpb2,
                tmpbpb2.id_po_item,tmpbpb2.id_line from po_header a inner join po_item s on a.id=s.id_po 
                inner join masteritem d on $sql_join inner join jo_det jod on s.id_jo=jod.id_jo 
                inner join jo on jod.id_jo=jo.id 
                inner join so on jod.id_so=so.id
                inner join act_costing ac on so.id_cost=ac.id 
                inner join 
                (select curr,price,id_po_item,sum(qty) qty_bpb,nomor_rak,berat_bersih,berat_kotor,remark,id id_line from bpb 
                  where pono='$ponya' and bpbno='$bpbno' group by id_po_item) tmpbpb2 
                on tmpbpb2.id_po_item=s.id  
                left join 
                (select id_po_item,sum(qty) qty_bpb from bpb where pono='$ponya' and bpbno!='$bpbno' group by id_po_item) tmpbpb 
                on tmpbpb.id_po_item=s.id  
                where a.pono='$ponya' and s.cancel='N' 
                group by s.id order by s.id";
              }
              #echo $sql;
              $i=1;
              $query=mysql_query($sql);
              while($data=mysql_fetch_array($query))
              { $id=$data['id_item'].":".$data['line_item'];
                $qtybal=$data['qty'] - $data['qty_bpb'];
                $cektemp=flookup("count(*)","bpb_roll_h","bpbno='$_GET[noid]' and id_jo='$data[id_jo]'");
                if($cektemp=="0") { $readtxt=""; } else { $readtxt=" readonly "; }
                $qtybpbn2=$data['qty_bpb2'];
                echo "
                <tr>";
                  if($jenis_company=="VENDOR LG")
                  { echo "<td>$data[jo_no]</td>"; }
                  else
                  { echo "<td>$data[kpno]</td>"; }
                  echo "
                  <td>$data[goods_code]</td>
                  <td>$data[itemdesc]</td>
                  <td>
                    <input type ='text' class='form-control' size='4' name ='qtypo[$id]' value='$data[qty]' 
                      id='qtypo$i' readonly>
                    <input type ='hidden' name ='idline[$id]' value='$data[id_po_item]' id='idline$i'>
                  </td>
                  <td>
                    <input type ='text' size='4' value='$qtybal' name ='qtybal[$id]' 
                      class='form-control qtybalclass' id='qtybal$i' readonly>
                  </td>
                  <td><input type ='text' class='form-control' size='4' name ='unitpo[$id]' value='$data[unit]' id='unitpo$i' readonly></td>
                  <td>
                    <input type ='text' size='6' $readtxt name ='qtybpb[$id]' 
                      class='form-control qtyclass' id='qtybpb$i' value='$qtybpbn2' readonly>
                    <input type ='hidden' value='$data[id_jo]' name ='id_jo[$id]' id='id_jo$i'>
                  </td>
                  <td>
                    <input type ='text' size='4' name ='nomrak[$id]' 
                      class='form-control nomrakclass' id='nomrak$i' value='$data[nomor_rak]'>
                  </td>
                  <td>
                    <input type ='text' size='4' name ='beratb[$id]' 
                      class='form-control beratbclass' id='beratb$i' value='$data[berat_bersih]'>
                  </td>
                  <td>
                    <input type ='text' size='4' name ='beratk[$id]' 
                      class='form-control beratkclass' id='beratk$i' value='$data[berat_kotor]'>
                  </td>
                  <td><input type ='$hidepx' class='form-control' size='4' name ='currpo[$id]' value='$data[curr]' id='currpo$i'></td>
                  <td><input type ='$hidepx' class='form-control' size='4' name ='pricepo[$id]' value='$data[price]' id='pricepo$i'></td>
                  <td><input type ='text' size='15' name ='ket[$id]' class='form-control ketclass' id='ket$i' value='$data[remark]'></td>
                  <td>
                    <a href='del_data.php?mod=$mod&mode=$mode&noid=$bpbno&id=$data[id_line]&pro=In'
                      data-toggle='tooltip' title='$chap'";?> 
                      onclick="return confirm('Apakah anda yakin akan menghapus ?')">
                      <?php echo "<i class='fa fa-trash-o'></i>
                    </a>
                  </td>
                </tr>";
                $i++;
              };
              ?>
            </tbody>
          </table>
        </div>
        <div class='col-md-3'>
          <button type='submit' name='submit' class='btn btn-primary'><?php echo $csim; ?></button>
        </div>
      </div>
    </div>
  </div>
</form>
<?php
# END COPAS ADD
?>