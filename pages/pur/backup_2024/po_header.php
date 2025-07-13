<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}

# START CEK HAK AKSES KEMBALI
# END CEK HAK AKSES KEMBALI

// $rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
// $st_company = $rscomp["status_company"];
// $logo_company = $rscomp["logo_company"];

?>
<script type="text/javascript">
</script>
<?php if ($mod == "po_header") {

  $thn_skrg = date("Y");
  $jenis = 'PO';

  if (isset($_POST['submit_filter'])) {
    $thn_skrg = $_POST['thn_skrg'];
    $jenis = $_POST['cbo_po'];
  } else if (isset($_POST['submit_excel'])) {
    $from = date('Y-m-d', strtotime($_POST['frdate']));
    $to = date('Y-m-d', strtotime($_POST['kedate']));
    $tglf = fd($_POST['frdate']);
    $perf = date('d M Y', strtotime($tglf));
    $tglt = fd($_POST['kedate']);
    $pert = date('d M Y', strtotime($tglt));
    echo "<script>
  window.open ('index.php?mod=packing_sum_exc&from=$from&to=$to&buyer=$buyer&dest=xls', '_blank');
    </script>";
  }
?>

  <div class="box">
    <div class="box-header">
      <a href="?mod=draft_po_bW_form" class="btn btn-primary" target="_blank"><i class="fa fa-plus"></i>&nbsp;New</a>
    </div>
    <div class='row'>
      <form action="" method="post">
        <div class="box-header">
          <div class='col-md-2'>
            <label>Tahun : </label>
            <select class='form-control select2' style='width: 100%;' name='thn_skrg' id='thn_skrg'>
              <?php
              $sql = "select distinct(YEAR(podate))isi, year(podate) as tampil from po_header where YEAR(podate)>= '2019'
              ";
              IsiCombo($sql, $thn_skrg, '');
              ?>
            </select>
          </div>
          <div class='col-md-2'>
            <label>Jenis PO : </label>
            <select class='form-control select2' style='width: 100%;' name='cbo_po' id='cbo_po'>
              <?php
              $sql = "SELECT 'DRAFT' as isi, 'DRAFT' as tampil
              UNION ALL
              SELECT 'PO' as isi, 'PO' as tampil
              ";
              IsiCombo($sql, $jenis, '');
              ?>
            </select>
          </div>
<!--           <div class='col-md-2'>
            <label>Status : </label>
            <select class='form-control select2' style='width: 100%;' name='cbo_stat' id='cbo_stat'>
              <?php
              $sql = "
              SELECT '' as isi, 'All' as tampil
              UNION ALL
              SELECT 'A' as isi, 'Approved' as tampil
              UNION ALL
              SELECT 'W' as isi, 'Waiting' as tampil
              UNION ALL
              SELECT 'C' as isi, 'Cancel' as tampil              
              ";
              IsiCombo($sql, $cbo_stat, '');
              ?>
            </select>
          </div>          --> 
          <div class='col-md-4'>
            <div style="padding:4px">
              <br>
              <button type='submit' name='submit_filter' class='btn btn-primary'>Tampilkan</button>
              <!-- <button type='submit' name='submit_excel' class='btn btn-success'>Export Excel</button> -->
            </div>
          </div>
        </div>
    </div>

    <div class="box-body">

      <?php if ($jenis == 'PO') { ?>

        <table id="po_table" border="1" class="table table-bordered table-striped table-responsive" style="width: 100%;font-size:11px;">
          <thead>
            <tr>
              <th style='width: 5%;'>No</th>
              <th style='width: 7%;'>No PO</th>
              <th style='width: 7%;'>No Draft</th>
              <th style='width: 8%;'>Tgl PO</th>
              <th style='width: 10%;'>Supplier</th>
              <th style='width: 10%;'>Notes</th>
              <th style='width: 10%;'>WS</th>
              <th style='width: 10%;'>Style</th>
              <th style='width: 15%;'>Buyer</th>
              <th style='width: 5%;'>Dibuat</th>
              <th style='width: 5%;'>Jenis</th>
              <th style='width: 20%;'>Act</th>
              <th style='width: 5%;'>P Terms</th>
              <th style='width: 5%;'>Status</th>
              <th style='width: 5%;'>App by</th>
              <th style='width: 5%;'>Tgl. App</th>
            </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE

            $query = mysql_query("select 
            po.id,
            po.pono, 
            po.podate, 
            po.supplier, 
            po.nama_pterms,
            group_concat(distinct(jd.kpno)) ws, 
            group_concat(distinct(styleno)) style,
            group_concat(distinct(buyer)) buyer,
            po.notes,
            po.app,
            po.app_by,
            if (po.app = 'W','Waiting','Approved') stat_po,
            po.app_date,
            po.draftno,
            po.username createby,
            if(po.jenis = 'M','Material','Printing') jenis
            from
            (select ph.*,pi.id_jo, pi.curr, ms.supplier,mp.nama_pterms,phd.draftno  from po_header ph
            inner join po_item pi on ph.id = pi.id_po
            inner join mastersupplier ms on ph.id_supplier = ms.id_supplier
            inner join masterpterms mp on ph.id_terms = mp.id
            left join (select id id_draft, draftno from po_header_draft phd group by id) phd on ph.id_draft = phd.id_draft
            where year(ph.podate) = '$thn_skrg' and jenis != 'N' 
            group by id_po, id_jo
            ) po
            inner join 
            (select mb.supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
            inner join so on jd.id_so = so.id
            inner join act_costing ac on so.id_cost = ac.id
            inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
            where jd.cancel = 'N'
            group by id_cost
            order by id_jo asc
            ) jd on po.id_jo = jd.id_jo
            group by po.id
            order by po.podate desc");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              $ws = $data['ws'];
              $ws_fix = str_replace(',', '<br />', $ws);
              $style = $data['style'];
              $style_fix = str_replace(',', '<br />', $style);
              $tgl_po = fd_view($data[podate]);
              $app = $data['app'];

              $app = $data['app'];
              if ($app == 'W') {
                $fontcol = 'black';
              } else {
                $fontcol = 'green';
              }

              if ($app == 'A' && $jenis == 'PO') {
                $action = "
                <a href='?mod=3z&id=$data[id]'
							data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>                
                <a href='pdfPO.php?id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";
              } else if ($app == 'W' && $jenis == 'PO') {
                $action = "
                <a href='?mod=3e&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
                </a>
                <a href='?mod=33x&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-pencil-square'></i>  
                </a>              
                <a href='pdfPO_Draft.php?id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
              </a>";
              }



              echo "<tr style = 'color: $fontcol;'>";
              echo "
            <td>$no</td>";
              echo "
            <td>$data[pono]</td>
            <td>$data[draftno]</td>
            <td style='width: 8%;'>$tgl_po</td>
            <td>$data[supplier]</td>
            <td>$data[notes]</td>
            <td style='max-width: 10%;overflow:hidden;word-wrap: break-word;'>$ws_fix</td>
            <td style='max-width: 10%;overflow:hidden;word-wrap: break-word;'>$style_fix</td>

            <td>$data[buyer]</td> 
            <td>$data[createby]</td>
            <td>$data[jenis]</td>  
            <td  style='width: 20%'>$action</td>                     
            <td>$data[nama_pterms]</td>
            <td>$data[stat_po]</td>
            <td>$data[app_by]</td>
            <td>$data[app_date]</td>
            ";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
        </table>
      <?php } ?>


      <?php if ($jenis == 'DRAFT') { ?>

        <table id="po_table" class="table table-bordered table-striped table-responsive" style="width: 100%;font-size:11px;">
          <thead>
            <tr>
              <th style='width: 5%;'>No</th>
              <th style='width: 7%;'>No Draft</th>
              <th style='width: 8%;'>Tgl Draft</th>
              <th style='width: 10%;'>Supplier</th>
              <th style='width: 10%;'>Notes</th>
              <th style='width: 10%;'>WS</th>
              <th style='width: 10%;'>Style</th>
              <th style='width: 15%;'>Buyer</th>
              <th style='width: 5%;'>Dibuat</th>
              <th style='width: 5%;'>Jenis</th>
              <th style='width: 10%;'>Act</th>
              <th style='width: 10%;'>P Terms</th>
              <th style='width: 5%;'>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE

            $query = mysql_query("select 
            pod.id,  
            pod.draftno, 
            pod.draftdate, 
            pod.supplier, 
            pod.nama_pterms,
            group_concat(distinct(jd.kpno)) ws, 
            group_concat(distinct(styleno)) style,
            group_concat(distinct(buyer)) buyer,
            pod.notes,
            pod.app,
						if (pod.app = 'W','Waiting','Approved') stat_pod,
            pod.app_by,
						pod.app_date,
						pod.username createby,
						if(pod.jenis = 'M','Material','Printing') jenis 
            from
            (select phd.*,pid.id_jo, pid.curr,ms.supplier,mp.nama_pterms from po_header_draft	phd	
inner join po_item_draft pid on phd.id = pid.id_po_draft		
inner join mastersupplier ms on phd.id_supplier = ms.id_supplier
inner join masterpterms mp on phd.id_terms = mp.id
where year(phd.draftdate) >= '$thn_skrg' and jenis != 'N' and app = 'W'
group by id_jo, id_po_draft
            ) pod
            inner join 
            (select mb.supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
            inner join so on jd.id_so = so.id
            inner join act_costing ac on so.id_cost = ac.id
            inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
            where jd.cancel = 'N'
            group by id_cost
            order by id_jo asc
            ) jd on pod.id_jo = jd.id_jo
            group by pod.id
            order by pod.draftdate desc");
            $no = 1;
            while ($data = mysql_fetch_array($query)) {
              $ws = $data['ws'];
              $ws_fix = str_replace(',', '<br />', $ws);
              $style = $data['style'];
              $style_fix = str_replace(',', '<br />', $style);
              $tgl_draft = fd_view($data[draftdate]);

              $app = $data['app'];
              if ($app == 'W') {
                $fontcol = 'black';
              } else {
                $fontcol = 'green';
              }

              $action = "
                <a href='?mod=draft_po_bW_form&id=$data[id]'
                data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
              </a>                
              <a href='?mod=33z&id=$data[id]'
		
							data-toggle='tooltip' title='View'><i class='fa fa-pencil-square'></i>
		
						</a>
            <a href='pdfPO_Draft.php?id=$data[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>";




              echo "<tr style = 'color: $fontcol;'>";
              echo "
    <td>$no</td>";
              echo "
    <td>$data[draftno]</td>
    <td style='width: 8%;'>$tgl_draft</td>
    <td>$data[supplier]</td>
    <td>$data[notes]</td>
    <td style='max-width: 10%;overflow:hidden;word-wrap: break-word;'>$ws_fix</td>
    <td style='max-width: 10%;overflow:hidden;word-wrap: break-word;'>$style_fix</td>
    <td>$data[buyer]</td> 
    <td>$data[createby]</td> 
    <td>$data[jenis]</td> 
    <td style='width: 10%;'>$action</td>                      
    <td>$data[nama_pterms]</td>
    <td>$data[stat_pod]</td>
    ";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
        </table>
      <?php } ?>



      </form>
    </div>
  </div>
<?php }
?>