<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = "";
$mod = $_GET['mod'];
if(!isset($jenis_company)) { $jenis_company=flookup("jenis_company","mastercompany","company!=''"); }
if (isset($_GET['frexc'])) 
{ $excel="Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
{ $excel="N"; }
if ($excel=="Y")
{ $from=date('Y-m-d',strtotime($_GET['frexc']));
  $to=date('Y-m-d',strtotime($_GET['toexc']));
  $buyer=$_GET['buyexc'];
  $style=$_GET['styexc'];
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
  if (isset($_POST['txtid_buyer'])) { $buyer=$_POST['txtid_buyer']; } else { $buyer=""; }
  if (isset($_POST['txtstyle'])) { $style=$_POST['txtstyle']; } else { $style=""; }
}
  
$titlenya="Laporan Purchase Order";

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

if($excel=="N") { 
?>
<div class="modal fade" id="myDet"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail</h4>
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_mat'></div>    
      </div>
    </div>
  </div>
</div>
<script>
  function det_mat(trxnya,id_jo,mat)
  { 
    var html = $.ajax
    ({  type: "POST",
        url: '../forms/ajax_bpb_jo_po.php?modeajax=view_list_det_mon_ord',
        data: {trxnya: trxnya, mat: mat,id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_mat").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 5
            
          }
      });
    });
  };
</script>

<?php } 
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if($buyer=="") {$ketbuyer=" Buyer All"; } else {$ketbuyer=" Buyer ".flookup("supplier","mastersupplier","id_supplier='$buyer'"); }
    if($style=="") {$ketstyle=" Style All"; } else {$ketstyle=" Style $style"; }
    echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to)." ".$ketbuyer." ".$ketstyle;
    if ($excel=="N") 
    { echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&buyexc=$buyer&styexc=$style&dest=xls'>Save To Excel</a></br>"; }
  echo "</div>";  
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border style='font-size:11px; width: 100%;'>";
      echo "<thead>";
        echo "
        <tr>
          <th>No</th>
          <th>WS #</th>
          <th>Style </th>
          <th>Item </th>
          <th>1st Gmt Delv</th>
          <th>PR (Fabric)</th>
          <th>PR (Accs Sew)</th>
          <th>PR (Accs Pac)</th>
          <th>PO (Fabric)</th>
          <th>PO (Accs Sew)</th>
          <th>PO (Accs Pac)</th>
          <th>Target (H-50 Garment Delivery)</th>
          <th>Balance Days</th>
        </tr>";
      echo "</thead>";
      echo "<tbody>";
      if ($jenis_company=="VENDOR LG") 
      { $sql_join=" inner join masteritem mct on acm.id_item=mct.id_item "; 
        $fld="mct.itemdesc nama_contents";
      }
      else
      { $sql_join="inner join mastercontents mct on acm.id_item=mct.id ";
        $fld="mct.nama_contents";
      }
      if($buyer=="") { $sqlbuy=""; } else { $sqlbuy=" and ac.id_buyer='$buyer'"; }
      if($style=="") { $sqlsty=""; } else { $sqlsty=" and ac.styleno='$style'"; }
      $sqldatatable = "select jo.app_date pr_date,jo.id id_jo,jo.jo_date,so.so_date,ac.id,ac.cost_date,ac.app1_date,ac.kpno,ac.styleno,mi.itemdesc,mb.supplier buyer,
        jo.jo_no,tqty_so qty_order,ac.ga_cost,ac.vat,ac.deal_allow deal,ac.curr,ac.cfm_price,
        sod.min_del,dlv.min_deldate_det AS h50_deldate,bld.balance_days,upcs.fullname usercs,upappcs.fullname appbycs,upso.fullname userso
        ,upjo.fullname userjo          
        from jo 
        inner join jo_det jod on jo.id=jod.id_jo 
        INNER JOIN masteritem mi ON jo.id=mi.id_gen
inner join (select id_so,sum(qty) tqty_so,min(deldate_det) min_del from so_det where cancel='N' group by id_so) sod on jod.id_so=sod.id_so
inner join (select id_so,DATE_SUB(deldate_det, INTERVAL 50 DAY)min_deldate_det from so_det where cancel='N' group by id_so) dlv on jod.id_so=dlv.id_so
inner join (select id_so,deldate_det, current_date() 
      ,datediff(deldate_det, current_date()) balance_days 
      from so_det GROUP BY id_so) bld ON jod.id_so=bld.id_so
        inner join so on so.id=sod.id_so 
        inner join act_costing ac on so.id_cost=ac.id 
        left join userpassword upcs on ac.username=upcs.username 
        left join userpassword upappcs on ac.app1_by=upappcs.username  
        left join userpassword upso on so.username=upso.username  
        left join userpassword upjo on jo.username=upjo.username  
        inner join mastersupplier mb on ac.id_buyer=mb.id_supplier
        ORDER BY sod.min_del asc
         ";
      #and ac.kpno='DWT/1019/104' untuk trial
      #echo $sqldatatable;
      $query = mysql_query($sqldatatable);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { $cost_date = new DateTime($data['cost_date']);
        $id_item = $data['id'];
        $firstdel = new DateTime($data['min_del']);
        $duecost = $firstdel->diff($cost_date)->format("%a");
        echo "
        <tr>
          <td>$no</td>
          <td>$data[kpno]</td>
          <td>$data[styleno]</td>
          <td>$data[buyer]</td>
          <td>".fd_view($data['min_del'])."</td>
          ";

          $appcost = new DateTime($data['app1_date']);
          $ageappcost = $cost_date->diff($appcost)->format("%a");
          $dueappcost = $firstdel->diff($appcost)->format("%a");
          echo "";

          $so_date = new DateTime($data['so_date']);
          $ageso = $appcost->diff($so_date)->format("%a");
          if($ageso==0 and $appcost!=$so_date) { $ageso=1; }
          $dueso = $firstdel->diff($so_date)->format("%a");
          echo "";
          
          $jo_date = new DateTime($data['jo_date']);
          $agejo = $so_date->diff($jo_date)->format("%a");
          if($agejo==0 and $so_date!=$jo_date) { $agejo=1; }
          $duejo = $firstdel->diff($jo_date)->format("%a");
          echo "";
          
          $sql = "select max(a.dateinput) max_date,s.fullname userbom  
            from bom_jo_item a left join userpassword s on a.username=s.username 
            where id_jo='$data[id_jo]' and cancel='N' group by id_jo";
          $rs=mysql_fetch_array(mysql_query($sql));
          $pr_date = new DateTime($rs['max_date']);
          $pr_date_app = new DateTime($data['pr_date']);
          $agepr = $jo_date->diff($pr_date)->format("%a");
          if($agepr==0 and $jo_date!=$pr_date) { $agepr=1; }
          $duepr = $firstdel->diff($pr_date)->format("%a");
          echo "";

          $sql = "select count(distinct bom.id_item) tpr,a.nama_group   
            from bom_jo_item bom INNER JOIN masterdesc j on bom.id_item=j.id
            INNER JOIN mastercolor i on i.id=j.id_color
            INNER JOIN masterweight h on h.id=i.id_weight
            INNER JOIN masterlength g on g.id=h.id_length
            INNER JOIN masterwidth f on f.id=g.id_width
            INNER JOIN mastercontents e on e.id=f.id_contents
            INNER JOIN mastertype2 d on d.id=e.id_type
            INNER JOIN mastersubgroup s on s.id=d.id_sub_group
            INNER JOIN mastergroup a on a.id=s.id_group
            where bom.id_jo='$data[id_jo]' and bom.cancel='N' and bom.status='M' 
            group by bom.id_jo,a.nama_group ";
          $rs=mysql_query($sql);
          $tpr_fab=0;
          $tpr_sew=0;
          $tpr_pac=0;
          while($datapr = mysql_fetch_array($rs))
          { if($datapr['nama_group']=="FABRIC")
            { $tpr_fab = $datapr['tpr']; }
            else if($datapr['nama_group']=="ACCESORIES SEWING")
            { $tpr_sew = $datapr['tpr']; }
            else if($datapr['nama_group']=="ACCESORIES PACKING")
            { $tpr_pac = $datapr['tpr']; }
          }
          $sql = "select count(distinct bom.id_item) tpr,a.nama_group   
            from bom_jo_item bom INNER JOIN masterdesc j on bom.id_item=j.id
            INNER JOIN mastercolor i on i.id=j.id_color
            INNER JOIN masterweight h on h.id=i.id_weight
            INNER JOIN masterlength g on g.id=h.id_length
            INNER JOIN masterwidth f on f.id=g.id_width
            INNER JOIN mastercontents e on e.id=f.id_contents
            INNER JOIN mastertype2 d on d.id=e.id_type
            INNER JOIN mastersubgroup s on s.id=d.id_sub_group
            INNER JOIN mastergroup a on a.id=s.id_group
            where bom.id_jo='$data[id_jo]' and bom.cancel='N' and bom.status='M' and ifnull(bom.id_supplier,'')!=''  
            group by bom.id_jo,a.nama_group ";
          $rs=mysql_query($sql);
          $tprs_fab=0;
          $tprs_sew=0;
          $tprs_pac=0;
          while($datapr = mysql_fetch_array($rs))
          { if($datapr['nama_group']=="FABRIC")
            { $tprs_fab = $datapr['tpr']; }
            else if($datapr['nama_group']=="ACCESORIES SEWING")
            { $tprs_sew = $datapr['tpr']; }
            else if($datapr['nama_group']=="ACCESORIES PACKING")
            { $tprs_pac = $datapr['tpr']; }
          }
          $col_fab = "style='background-color:lightgray;'";
          $col_sew = "style='background-color:cornflowerblue;'";
          $col_pac = "style='background-color:mediumspringgreen;'";
          if($excel=="N")
          { $cridet = $data['id_jo'];
            echo "
            <td $col_fab><a href='#' style='color: black;' data-toggle='modal' 
              data-target='#myDet' onclick='det_mat(1,$cridet,1)'>".$tprs_fab.' of '.$tpr_fab."</a></td>
            <td $col_sew><a href='#' style='color: black;' data-toggle='modal' 
              data-target='#myDet' onclick='det_mat(1,$cridet,2)'>".$tprs_sew.' of '.$tpr_sew."</a></td>
            <td $col_pac><a href='#' style='color: black;' data-toggle='modal' 
              data-target='#myDet' onclick='det_mat(1,$cridet,3)'>".$tprs_pac.' of '.$tpr_pac."</a></td>";
          }
          else
          { echo "
            <td $col_fab>".$tprs_fab.' of '.$tpr_fab."</td>
            <td $col_sew>".$tprs_sew.' of '.$tpr_sew."</td>
            <td $col_pac>".$tprs_pac.' of '.$tpr_pac."</td>";
          }
          $sql = "select count(distinct a.id_gen) tdata,1 tpr,max(podate) max_date,max(app_date) max_app_date,
            max(eta) max_eta,max(etd) max_etd,matclass nama_group   
            from po_item a inner join po_header s on a.id_po=s.id 
            inner join masteritem mi on a.id_gen=mi.id_gen   
            where id_jo='$data[id_jo]' and cancel='N' and jenis='M' 
            group by id_jo,mi.matclass ";
          $rs=mysql_query($sql);
          $tpo_fab=0;
          $tpo_sew=0;
          $tpo_pac=0;
          $eta_fab="";
          $eta_sew="";
          $eta_pac="";
          $etd_fab="";
          $etd_sew="";
          $etd_pac="";
          $duefab="";
          $duesew="";
          $duepac="";
          while($datapr = mysql_fetch_array($rs))
          { if($datapr['nama_group']=="FABRIC")
            { $tpo_fab = $datapr['tdata']; 
              if($tpo_fab==$tpr_fab) 
              { $eta_fab = $datapr['max_eta']; 
                $etd_fab = $datapr['max_etd'];
                $eta_fab2 = new DateTime($eta_fab);
                $duefab = $firstdel->diff($eta_fab2)->format("%a");
              } 
              else 
              { $eta_fab = ""; 
                $etd_fab = "";
                $duefab = "";
              }
            }
            else if($datapr['nama_group']=="ACCESORIES SEWING")
            { $tpo_sew = $datapr['tdata']; 
              if($tpo_sew==$tpr_sew) 
              { $eta_sew = $datapr['max_eta']; 
                $etd_sew = $datapr['max_etd'];
                $eta_sew2 = new DateTime($eta_sew);
                $duesew = $firstdel->diff($eta_sew2)->format("%a");
              } 
              else 
              { $eta_sew = "";
                $etd_sew = "";
                $duesew = "";
              }
            }
            else if($datapr['nama_group']=="ACCESORIES PACKING")
            { $tpo_pac = $datapr['tdata']; 
              if($tpo_pac==$tpr_pac) 
              { $eta_pac = $datapr['max_eta'];
                $etd_pac = $datapr['max_etd'];
                $eta_pac2 = new DateTime($eta_pac);
                $duepac = $firstdel->diff($eta_pac2)->format("%a");
              } 
              else 
              { $eta_pac = "";
                $etd_pac = "";
                $duepac = "";
              }
            }
          }
          if($excel=="N")
          { echo "
            <td $col_fab><a href='#' style='color: black;' data-toggle='modal' 
              data-target='#myDet' onclick='det_mat(2,$cridet,1)'>".$tpo_fab.' of '.$tpr_fab."</a>
            </td>";
          }
          else
          { echo "<td $col_fab>".$tpo_fab.' of '.$tpr_fab."</td>"; }
          echo "";
          if($excel=="N")
          { echo "
            <td $col_fab><a href='#' style='color: black;' data-toggle='modal' 
              data-target='#myDet' onclick='det_mat(2,$cridet,2)'>".$tpo_sew.' of '.$tpr_sew."</a>
            </td>";
          }
          else
          { echo "<td $col_sew>".$tpo_sew.' of '.$tpr_sew."</td>"; }
          echo "";
          if($excel=="N")
          { echo "
            <td $col_fab><a href='#' style='color: black;' data-toggle='modal' 
              data-target='#myDet' onclick='det_mat(2,$cridet,3)'>".$tpo_pac.' of '.$tpr_pac."</a>
            </td>";
          }
          else
          { echo "<td $col_pac>".$tpo_pac.' of '.$tpr_pac."</td>"; }
          echo "          
          <td>".fd_view($data['h50_deldate'])."</td>";
        echo "<td>$data[balance_days]</td>
        </tr>";
        $no++;
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>