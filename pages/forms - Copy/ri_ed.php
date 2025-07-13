<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



# START CEK HAK AKSES KEMBALI

$mod=$_GET['mod'];

$mode=$_GET['mode'];

$akses = flookup("bpb_po","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

$jenis_company = flookup("jenis_company","mastercompany","company!=''");

# END CEK HAK AKSES KEMBALI

# COPAS EDIT

$id=$_GET['noid'];

$rs=mysql_fetch_array(mysql_query("select * from bpb where bpbno='$id'"));

if($rs['bpbno_int']<>"")
{
  $vbpbno=$rs['bpbno_int'];
}
else
{
  $vbpbno=$rs['bpbno'];
}
$bpbno=$rs['bpbno'];
$bpbdate=fd_view($rs['bpbdate']);

$id_supplier=$rs['id_supplier'];

$id_sec=$rs['id_sec'];

$pono=$rs['pono'];

$jam_masuk=$rs['jam_masuk'];

$read=" readonly ";

$nomor_mobil=$rs['nomor_mobil'];

$invno=$rs['invno'];

$kkbc=$rs['nomor_kk_bc'];

$status_kb=$rs['jenis_dok'];

$bcaju=$rs['nomor_aju'];

$tglaju=fd_view($rs['tanggal_aju']);

$bcno=$rs['bcno'];

$bcdate=fd_view($rs['bcdate']);

$no_fp=$rs['no_fp'];

$tglfp=fd_view($rs['tgl_fp']);



# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

?>

<script type='text/javascript'>

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

<form method='post' name='form' action='s_ri_ed.php?mod=<?php echo $mod;?>&mode=<?php echo $mode;?>' onsubmit='return validasi()'>

  <div class='box'>

    <div class='box-body'>

      <div class='row'>

        <div class='col-md-3'>

          <?php

          echo "<div class='form-group'>";

            echo "<label>$caption[2]</label>";

            echo "<input type='text' class='form-control' name='txtbpbno' placeholder='$cmas $caption[2]' readonly value='$vbpbno'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>$caption[3] *</label>";

            echo "<input type='text' class='form-control' name='txtbpbdate' id='datepicker2' placeholder='$cmas $caption[3]' value='$bpbdate'>";

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

          echo "<div class='form-group'>";

            echo "<label>Nomor KK BC</label>";

            echo "<input type='text' class='form-control' name='txtkkbc' value='$kkbc'>";

          echo "</div>";

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

          echo "

            <div class='form-group'>

              <label>Nomor Aju</label>

              <input type='text' class='form-control' name='txtbcaju' placeholder='Masukan Nomor Aju' readonly value='$bcaju'>

            </div>

            <div class='form-group'>

              <label>Tanggal Aju</label>

              <input type='text' class='form-control' id='datepicker3' name='txttglaju' readonly placeholder='Masukan Tanggal Aju' value='$tglaju'>

            </div>";

          ?>

          </div>

          <div class='col-md-3'>

            <?php

            echo "

              <div class='form-group'>

                <label>$c42 *</label>

                <input type='text' class='form-control' name='txtbcno' readonly placeholder='$cmas $c42' value='$bcno'>

              </div>

              <div class='form-group'>

                <label>$c43 *</label>

                <input type='text' class='form-control' id='datepicker1' name='txtbcdate' readonly placeholder='$cmas $c43' value='$bcdate'>

              </div>";

            ?>

          </div>

          <div class='col-md-3'>

            <?php

            echo "

              <div class='form-group'>

                <label>Nomor Faktur Pajak</label>

                <input type='text' class='form-control' name='txtno_fp' placeholder='Masukan Nomor Faktur Pajak' value='$no_fp'>

              </div>

              <div class='form-group'>

                <label>Tanggal Faktur Pajak</label>

                <input type='text' class='form-control' id='datepicker3' name='txttglfp' placeholder='Masukan Tanggal Faktur Pajak' value='$tglfp'>

              </div>";

            ?>

            </div>

      </div>

      <div class='row'>

        <div class='col-md-12'>

          <table id="examplefix3" class="display responsive" style="width:100%">

            <thead>

              <tr>

                <?php if($jenis_company=="VENDOR LG") { ?>

                  <th>JO #</th> 

                <?php } else { ?>

                  <th>Nomor WS</th>

                  <?php if($mode=="FG" and $mod!="551e" and $mod!="20e") { ?>

                    <th>PO Buyer</th>

                    <th>Size</th>

                    <th>Color</th>

                    <th>Dest</th>

                    <th>Delv. Date</th>

                  <?php } ?>

                <?php } ?>

                <th>Kode <?php echo $mode; ?></th>

                <th>Deskripsi</th>

                <th>Satuan</th>

                <?php if($mod=="20e") { ?>

                  <th>Qty RI</th>

                <?php } else { ?>

                  <th>Qty BPB</th>

                <?php } ?>

                <th>Qty Reject</th>

                <th>Nomor Rak</th>

                <th>Berat Bersih</th>

                <th>Berat Kotor</th>

                <th>Keterangan</th>

              </tr>

            </thead>

            <tbody>

              <?php

              $ponya=$bpbno;

              $jenispo=flookup("jenis","po_header","pono='$ponya'");

              if ($jenis_company=="VENDOR LG" or $jenispo=="N")

              { $sql_join=" s.id_gen=d.id_item "; }

              else

              { $sql_join=" s.id_gen=d.id_gen "; }

              if($mode=="FG" and $mod!="551e" and $mod!="20e") 

              { $tblmst="masterstyle"; $flddesc="d.itemname"; 

                $sql="select so.buyerno po_buyer,sod.color,sod.size,sod.deldate_det sod_deldate,dest sod_dest,a.nomor_rak,a.berat_bersih,a.berat_kotor,a.remark,

                a.id line_item,d.id_item,ac.kpno,d.goods_code,$flddesc itemdesc,a.qty,a.unit, 

                a.id_jo,a.price,a.curr,0 qty_bpb,a.qty qty_bpb2,jo.jo_no from  

                bpb a inner join $tblmst d on a.id_item=d.id_item inner join so_det sod on a.id_so_det=sod.id 

                inner join so on sod.id_so=so.id inner join jo_det jod on so.id=jod.id_so 

                left join jo on jod.id_jo=jo.id 

                inner join act_costing ac on so.id_cost=ac.id 

                where a.bpbno='$ponya' order by a.id ";

              } 

              else if($mode=="FG" and ($mod=="551e" or $mod=="20e")) 

              { $tblmst="masterstyle"; $flddesc="d.itemname"; 

                $sql="select a.nomor_rak,a.berat_bersih,a.berat_kotor,a.remark,

                a.id line_item,d.id_item,d.kpno,d.goods_code,$flddesc itemdesc,a.qty,a.unit, 

                a.id_jo,a.price,a.curr,0 qty_bpb,a.qty qty_bpb2,jo.jo_no,sod.size,sod.color from  

                bpb a inner join $tblmst d on a.id_item=d.id_item left join so_det sod on a.id_so_det=sod.id 

                left join so on sod.id_so=so.id left join jo_det jod on so.id=jod.id_so 

                left join jo on jod.id_jo=jo.id

                left join act_costing ac on so.id_cost=ac.id 

                where a.bpbno='$ponya' order by a.id ";

              } 

              else if($mode=="Mesin" and $mod=="553e") 

              { $tblmst="masteritem"; $flddesc="d.itemdesc"; 

                $sql="select a.nomor_rak,a.berat_bersih,a.berat_kotor,a.remark,

                a.id line_item,d.id_item,ac.kpno,d.goods_code,$flddesc itemdesc,a.qty,a.unit, 

                a.id_jo,a.price,a.curr,0 qty_bpb,a.qty qty_bpb2,jo.jo_no from  

                bpb a inner join $tblmst d on a.id_item=d.id_item left join so_det sod on a.id_so_det=sod.id 

                left join so on sod.id_so=so.id left join jo_det jod on so.id=jod.id_so 

                left join jo on jod.id_jo=jo.id

                left join act_costing ac on so.id_cost=ac.id 

                where a.bpbno='$ponya' order by a.id ";

                #echo $sql;

              } 

              else 

              { $tblmst="masteritem"; $flddesc="d.itemdesc"; 

                $sql="select a.nomor_rak,a.berat_bersih,a.berat_kotor,a.remark,

                a.id line_item,d.id_item,ac.kpno,d.goods_code,$flddesc itemdesc,a.qty,a.unit, 

                a.id_jo,a.price,a.curr,0 qty_bpb,a.qty qty_bpb2,jo.jo_no,a.qty_reject from  

                bpb a inner join $tblmst d on a.id_item=d.id_item left join jo_det jod on a.id_jo=jod.id_jo

                left join so on jod.id_so=so.id

                left join jo on jod.id_jo=jo.id

                left join act_costing ac on so.id_cost=ac.id 

                where a.bpbno='$ponya' order by a.id ";

              }

              #echo $sql;

              $i=1;

              $query=mysql_query($sql);

              while($data=mysql_fetch_array($query))

              { $id=$data['line_item'].":".$data['line_item'];

                $qtybal=$data['qty'] - $data['qty_bpb'];

                $qtybpbn2=$data['qty_bpb2'];

                echo "

                  <tr>";

                    if($jenis_company=="VENDOR LG") 

                    { echo "<td>$data[jo_no]</td>"; }

                    else

                    { echo "<td>$data[kpno]</td>"; 

                      if($mode=="FG" and $mod!="551e" and $mod!="20e")

                      { echo "

                        <td>$data[po_buyer]</td>

                        <td>$data[size]</td>

                        <td>$data[color]</td>

                        <td>$data[sod_dest]</td>

                        <td>".fd_view($data['sod_deldate'])."</td>";

                      }

                    }

                    echo "

                    <td>$data[goods_code]</td>

                    <td>$data[itemdesc]</td>

                    <td><input type ='text' size='4' name ='unitpo[$id]' value='$data[unit]' id='unitpo$i' readonly></td>

                    <td><input type ='text' size='4' name ='qtybpb[$id]' class='qtyclass' id='qtybpb$i' value='$qtybpbn2'>

                        <input type ='hidden' name ='pricepo[$id]' value='$data[price]' id='pricepo$i'>

                        <input type ='hidden' name ='currpo[$id]' value='$data[curr]' id='currpo$i'>

                        <input type ='hidden' value='$data[id_jo]' name ='id_jo[$id]' id='id_jo$i'>

                    </td>

                    <td><input type ='text' size='4' name ='qtyreject[$id]' id='qtyreject$i' class='qtyreject' value='$data[qty_reject]'>

                    </td>

                    <td><input type ='text' size='4' name ='nomrak[$id]' class='nomrakclass' id='nomrak$i' value='$data[nomor_rak]'></td>

                    <td><input type ='text' size='4' name ='beratb[$id]' class='beratbclass' id='beratb$i' value='$data[berat_bersih]'></td>

                    <td><input type ='text' size='4' name ='beratk[$id]' class='beratkclass' id='beratk$i' value='$data[berat_kotor]'></td>

                    <td><input type ='text' size='15' name ='ket[$id]' class='ketclass' id='ket$i' value='$data[remark]'></td>

                  </tr>";

                $i++;

              };

              ?>

            </tbody>

          </table>

        </div>

        <div class='col-md-3'>

          <button type='submit' name='submit' class='btn btn-primary'><?php echo $csim;?></button>

        </div>

      </div>

    </div>

  </div>

</form>

<?php

# END COPAS ADD

?>