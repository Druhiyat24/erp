<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$rs=mysql_fetch_array(mysql_query("select * from userpassword where username='$user' "));
$akses = $rs['approval'];
$kode_mkt = $rs['kode_mkt'];
$jabatan = $rs['Groupp'];
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$jenis_company = flookup("jenis_company","mastercompany","company!=''");
?>
<script>
  function App_RN()
  { var strn = document.getElementsByClassName("cboapprn");
    var ntrn = document.getElementsByClassName("txtnotesrn");
    var idrn = document.getElementsByClassName("txtidrn");
    for (var i = 0; i < strn.length; i++) 
    { if (strn[i].value !== '')
      { jQuery.ajax
        ({  url: 'ajax_app.php?modeajax=RN',
            method: 'POST',
            data: {rnnya: idrn[i].value, stnya: strn[i].value, ntnya: ntrn[i].value},
            dataType: 'json',
            error: 
            function (request, status, error) 
            { if (request.responseText !== '') { alert(request.responseText); } 
            },
        });
      }
    }
    alert('Data Saved');
    window.location.reload();
  };

  function choose_req(id_h)
  { 
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_app.php?modeajax=view_list_rak_loc_trx_new',
        data: {id_h: id_h},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_req").html(html);
      $("#detail_header").html(id_h); 
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

</script>
<?php 
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
# END COPAS VALIDASI
# COPAS ADD
?>
<?php if ($mod=="2") {?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Costing</h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Costing #</th>
        <th>Costing Date</th>
        <th>Buyer</th>
        <?php if($jenis_company=="VENDOR LG") { ?>
          <th>Model</th>
        <?php } else { ?>
          <th>Style #</th>
        <?php } ?>
        <th>Delv. Date</th>
        <th>Total Cost</th>
        <th>Created By</th>
        <th width='10%'>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        if ($nm_company=="PT. Bintang Mandiri Hanafindo") {$fld_gr="";} else {$fld_gr="and kode_mkt='$kode_mkt'";}
		$sql = "select a.id,cost_no,cost_date,supplier,styleno,
          deldate,fullname from act_costing a inner join act_costing_mat acm on a.id=acm.id_act_cost 
          inner join mastersupplier f on 
          a.id_buyer=f.id_supplier inner join userpassword up on a.username=up.username 
          where status IN('CONFIRM','SAMPLE','BOOKING') and app1='W' and cost_date >= '2023-01-01' group by a.cost_no";

        $query = mysql_query($sql); 
		  
		  
        if (!$query) {die(mysql_error());}
        $no = 1; 
		
        while($rs = mysql_fetch_array($query))
        { $id=$data['id'];
          $tot_mat = flookup("sum((price*cons)+((price*cons)*allowance/100))","act_costing_mat","id_act_cost='$rs[id]'");
          $tot_mfg = flookup("sum((price*cons)+((price*cons)*allowance/100))","act_costing_mfg","id_act_cost='$rs[id]'");
          $tot_oth = flookup("sum(price)","act_costing_oth","id_act_cost='$rs[id]'");
          $tot_cost = $tot_mat + $tot_mfg + $tot_oth;
          $tot_cost = fn($tot_cost,2);
          echo "<tr>";
            echo "<td>$no</td>"; 
            echo "
              <td>$rs[cost_no]</td>
              <td>".fd_view($rs['cost_date'])."</td>
              <td>$rs[supplier]</td>
              <td>$rs[styleno]</td>
              <td>".fd_view($rs['deldate'])."</td>
              <td>$tot_cost</td>
              <td>$rs[fullname]</td>
              ";
            echo "
            <td>
              <a class='btn btn-primary btn-s' href='../marketting/pdfCost.php?id=$rs[id]'
                data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
              <a class='btn btn-info btn-s' href='app_act.php?mod=$mod&trx=CS&id=$rs[id]'
                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
<?php if ($mod=="3") {?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Purchase Request</h3>
  </div>
  <div class="box-body">
    <table id="examplefix2" class="" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Buyer</th>
        <th>Job Order #</th>
        <th>Job Order Date</th>
        <th>Style #</th>
        <th>Created By</th>
        <th>Delivery Garment</th>
        <th>Balance Days</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.id,a.jo_no,a.jo_date,fullname,msup.supplier,ac.styleno,sod.deldate_det AS first_garment_delv,bld.balance_days
          from jo  a inner join bom_jo_item s on a.id=s.id_jo
          inner join jo_det jod on a.id=jod.id_jo
          inner join so on jod.id_so=so.id 
          inner join so_det sod on sod.id_so =so.id
          inner join (select id_so,deldate_det AS first_garment_delv, current_date() 
		  ,datediff(deldate_det, current_date()) balance_days 
		  from so_det GROUP BY id_so) bld ON bld.id_so=so.id
			 inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username where a.app='W' and so.so_date >= '2024-01-01' 
          group by a.jo_no ORDER BY first_garment_delv desc"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[jo_no]</td>";
            echo "<td>".fd_view($data['jo_date'])."</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[fullname]</td>";
            echo "<td>$data[first_garment_delv]</td>";
            echo "<td>$data[balance_days]</td>";
            echo "
            <td>
              <a class='btn btn-primary btn-s' href='../marketting/pdfBOM.php?id=$data[id]'
                data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
              <a class='btn btn-info btn-s' href='app_act.php?mod=$mod&trx=JO&id=$data[id]'
                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>

<?php if ($mod=="4_draft") {
	?>
<link rel="stylesheet" href="./css/overlay.css"> 

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src="js/ApprovalDraftPoPage.js"></script>
<div id="myOverlay">
    <div class="col-md-3 col-sm-offset-6" style="padding-top:400px">
		<img src="./img/loading.gif"></img>
    </div>
</div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Draft Purchase Order</h3>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Draft PO #</th>
        <th>Rev</th>
        <th>Tgl. Draft</th>
        <th>Supplier</th>
        <th>P.Terms</th>
        <th>Notes</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $sql="select a.*,ms.supplier,mp.nama_pterms, mp.days_pterms from po_header_draft a
inner join (select id_po_draft,count(id),count(case cancel when 'Y' then 1 else null end) from po_item_draft 
where id_po_draft is not null
group by id_po_draft
having count(id) > count(case cancel when 'Y' then 1 else null end)
) b on a.id = b.id_po_draft
inner join mastersupplier ms on a.id_supplier = ms.id_Supplier
inner join masterpterms mp on a.id_terms = mp.id
where a.app = 'W' and draftdate >= '2024-01-01'
order by draftdate desc

          ";
        $query = mysql_query($sql); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[draftno]</td>";
            echo "<td>$data[revise]</td>";
            echo "<td>".fd_view($data['draftdate'])."</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[nama_pterms]</td>";
            echo "<td>$data[notes]</td>";
            if($data['jenis']=="N") {$nmpdf="pdfPOG_Draft";} else {$nmpdf="pdfPO_Draft";}
            echo "
            <td>
              <a class='btn btn-primary btn-s' href='../pur/$nmpdf.php?id=$data[id]'
                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
              <a class='btn btn-info btn-s' onclick='send_draft_po({$data['id']})' href='#'
                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>



  </div>
</div>



<?php } ?>

<?php if ($mod=="4") {?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Purchase Order</h3>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>PO #</th>
        <th>Rev</th>
        <th>PO Date</th>
        <th>Supplier</th>
        <th>P.Terms</th>
        <th>Buyer</th>
        <th>WS #</th>
        <th>Style #</th>
        <th>Delv Garment</th>
        <th>Balance Days</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
    //     $sql="select tmppoit.kpno,tmppoit.styleno,a.revise,a.jenis,a.app,a.id,pono,podate,supplier,
    //       nama_pterms,tmppoit.buyer,tmppoit.deldate_det AS first_garment_delv,tmppoit.balance_days from po_header a inner join 
    //       mastersupplier s on a.id_supplier=s.id_supplier inner join 
    //       masterpterms d on a.id_terms=d.id
    //       inner join 
    //       (select ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer,sod.deldate_det,bld.balance_days from po_item poit 
    //       inner join jo_det jod on jod.id_jo=poit.id_jo 
    //       inner join so on jod.id_so=so.id
    //       inner join so_det sod on sod.id_so =so.id
    //       inner join (select id_so,deldate_det AS first_garment_delv, current_date() 
		  // ,datediff(deldate_det, current_date()) balance_days 
    //       from so_det GROUP BY id_so) bld ON bld.id_so=so.id
    //       inner join act_costing ac on so.id_cost=ac.id 
    //       inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
    //       where poit.cancel='N' 
    //       group by poit.id_po) tmppoit 
    //       on tmppoit.id_po=a.id where a.app='W' and jenis in ('M','P')
    //       union all 
    //       select '','',a.revise,a.jenis,a.app,a.id,pono,podate,supplier,
    //       nama_pterms,tmppoit.buyer,tmppoit.deldate_det,tmppoit.balance_days from po_header a inner join 
    //       mastersupplier s on a.id_supplier=s.id_supplier inner join 
    //       masterpterms d on a.id_terms=d.id
    //       LEFT join 
    //       (select poit.id_jo,poit.id_po,'' buyer,sod.deldate_det,bld.balance_days, poit.cancel from po_item poit 
    //       inner join reqnon_header reqnonh on reqnonh.id=poit.id_jo 
    //       inner join jo_det jod on jod.id_jo=poit.id_jo 
    //       inner join so on jod.id_so=so.id
    //       INNER JOIN so_det sod on sod.id_so =so.id
    //       LEFT JOIN (select id_so,deldate_det AS first_garment_delv, current_date() 
			 // ,datediff(deldate_det, current_date()) balance_days 
			 // from so_det GROUP BY id_so) bld ON bld.id_so=so.id
    //       where poit.cancel='N' 
    //       group by poit.id_po) tmppoit 
    //       on tmppoit.id_po=a.id where a.app='W' and jenis='N' and cancel = 'N' ORDER BY podate desc
    //       ";

        $sql="select a.id,a.jenis, a.pono,a.revise,a.podate,ms.supplier,d.terms_pterms nama_pterms,ac.buyer, ac.kpno, ac.styleno, ac.deldate first_garment_delv, datediff(deldate, current_date()) balance_days  
from po_header a 
inner join po_item s on a.id=s.id_po
inner join mastersupplier ms on a.id_supplier = ms.id_supplier
inner join masterpterms d on a.id_terms=d.id
left join jo_det jd on s.id_jo = jd.id_jo
left join so on so.id = jd.id_so
left join (select ac.*,supplier as buyer from act_costing ac inner join mastersupplier ms on ac.id_buyer = ms.id_supplier) ac on so.id_cost = ac.id
where s.cancel='N' and app='W' and a.podate >= '2024-01-01'
group by pono
order by a.podate desc
          ";          
        $query = mysql_query($sql); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[pono]</td>";
            echo "<td>$data[revise]</td>";
            echo "<td>".fd_view($data['podate'])."</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[nama_pterms]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[first_garment_delv]</td>";
            echo "<td>$data[balance_days]</td>";
            if($data['jenis']=="N") {$nmpdf="pdfPOG";} else {$nmpdf="pdfPO";}
            echo "
            <td>
              <a class='btn btn-primary btn-s' href='../pur/$nmpdf.php?id=$data[id]'
                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
              <a class='btn btn-info btn-s' href='app_act.php?mod=$mod&trx=PO&id=$data[id]'
                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
<?php if ($mod=="5") {?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Genereal Request</h3>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>Req #</th>
        <th>Req Date</th>
        <th>Description Goods</th>
        <th>Notes</th>
        <th>Created By</th>
        <th>Created Date</th>
        <th>Approve By</th>
        <th>Approve By2</th>
        <th>Status</th>
        <th>Notes</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.*,GROUP_CONCAT(DISTINCT mi.itemdesc) list_item from 
          reqnon_header a inner join reqnon_item s on a.id=s.id_reqno 
          inner join masteritem mi on s.id_item=mi.id_item where s.cancel='N' and 
          (((a.app='W' or a.app='R') and a.app_by='$user') or ((a.app2='W' or a.app2='R') and a.app_by2='$user')) group by reqno"); 
        $no = 1;
        while($data = mysql_fetch_array($query))
        { 
          echo "<tr>";
            echo '
            <td>'.$data["reqno"].'</td>
            <td>'.fd_view($data["reqdate"]).'</td>
            <td>'.$data["list_item"].'</td>
            <td>'.$data["notes"].'</td>
            <td>'.$data["username"].'</td>
            <td>'.fd_view_dt($data["dateinput"]).'</td>
            <td>'.$data['app_by']." (".$data["app"].')'.'</td>
            <td>'.$data['app_by2']." (".$data["app2"].')'.'</td>';
            echo "
            <td>
              <select name='cboapprn$data[id]' id='cboapprn$data[id]' class='cboapprn'>";
                $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Jen_App'";
                IsiCombo($sql,'','Status');
              echo "
              </select>
            </td>
            <td>
              <input type='text' size='20' name='txtnotesrn$data[id]' id='txtnotesrn$data[id]' class='txtnotesrn'>
              <input type='hidden' name='txtidrn$data[id]' id='txtidrn$data[id]' class='txtidrn' value='$data[id]'>
            </td>";
            echo "
              <td>
                <button type='button' class='fa fa-search' data-toggle='modal' data-toggle='tooltip' title='Detail'                  
                data-target='#myReq' onclick='choose_req($data[id])'></button>
              </td>               
            <td>
              <a href='../others/pdfReq.php?id=$data[id]'
                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
            </td>           
            <td></td>";
            // <td>
            //   <a href='app_act.php?mod=$mod&trx=RN&id=$data[id]'
            //     data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            // </td>";
          echo "</tr>";
          $no++;// menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
    <br>
    <button class='btn btn-primary' onclick='App_RN()'>Simpan</button>
  </div>
</div>

<div class="modal fade" id="myReq"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">   
<!--       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div> -->
      <div class="modal-body" style="overflow-y:auto; position: relative;
  overflow-y: auto;
  min-height: 200px !important;
  max-height: 600px !important;">
<!--   <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>    -->
        <div id='detail_req'></div>    
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>      
    </div>
  </div>
</div>

<?php } ?>
<?php if ($mod=="6") {?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Permintaan TK</h3>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>Diajukan Oleh</th>
        <th>NIK</th>
        <th>Department</th>
        <th>Bagian</th>
        <th>Rencana Kebutuhan</th>
        <th>Bagian</th>
        <th>Tanggal</th>
        <th>Jumlah</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Approval</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $cricek="(setuju1='$user' and setuju1_app='W')";
        $cricek=$cricek." or (setuju2='$user' and setuju2_app='W')";
        $cricek=$cricek." or (setuju3='$user' and setuju3_app='W')";
        $cricek=$cricek." or (ketahui='$user' and ketahui_app='W')";
        $query = mysql_query("select * from 
          form_tenaga_kerja where $cricek "); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $approve=$data["setuju1"]." (".$data["setuju1_app"].")";
          $approve=$approve." ".$data["setuju2"]." (".$data["setuju2_app"].")";
          $approve=$approve." ".$data["setuju3"]." (".$data["setuju3_app"].")";
          $approve=$approve." ".$data["ketahui"]." (".$data["ketahui_app"].")";
          echo "<tr>";
            echo '
            <td>'.$data["do_nama"].'</td>
            <td>'.$data["do_nik"].'</td>
            <td>'.$data["do_department"].'</td>
            <td>'.$data["do_bagian"].'</td>
            <td>'.$data["rk_department"].'</td>
            <td>'.$data["rk_bagian"].'</td>
            <td>'.$data["rk_tanggal"].'</td>
            <td>'.$data["rk_jumlah"].'</td>
            <td>'.$data["dateinput"].'</td>
            <td>'.$data["username"].'</td>
            <td>'.$approve.'</td>';
            echo "
            <td>
              <a class='btn btn-primary btn-s' href='../hr/pdfPTK.php?id=$data[id_tk]'
                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
              <a class='btn btn-info btn-s' href='app_act.php?mod=$mod&trx=PTK&id=$data[id_tk]'
                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>