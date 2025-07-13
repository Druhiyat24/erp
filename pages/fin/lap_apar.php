<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_costing.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
$mode = $_GET['mod'];
if ($mod=="AR3")
{ $tbl="acc_rec"; $title="Laporan Account Receivable"; $cap_supcus="Customer"; $fldid="id_ar"; }
else if ($mod=="AP3")
{ $tbl="acc_pay"; $title="Laporan Account Payable"; $cap_supcus="Supplier"; $fldid="id_ap"; }

echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&dest=xls'>Save To Excel</a></br>"; }
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
      echo "<table id='examplefix' class='table table-bordered table-striped'>";
        echo "
        <thead>
          <tr>
            <th>No. Inv</th>
            <th>Tgl. Inv</th>
            <th>No. Faktur</th>
            <th>$cap_supcus</th>
            <th>Mata Uang</th>
            <th>Jumlah</th>
            <th>Tgl. Tanda Terima</th>
            <th>Tgl. Jatuh Tempo</th>
            <th>Jml Hari</th>
            <th>Tgl. Bayar</th>
            <th>Nama Bank</th>
          </tr>
        </thead>";

        echo "<tbody>";
          $today=date('Y-m-d');
          if ($mode=="AR" OR $mode=="AP")
          { $crinya="DATEDIFF(due_date,'$today') between 1 and 10 and pay_date='0000-00-00'"; }
          else if ($mode=="AR2" OR $mode=="AP2")
          { $crinya="DATEDIFF(due_date,'$today') < 0 and pay_date='0000-00-00'"; }
          else 
          { #$crinya="inv_date between '$from' and '$to'"; 
            $crinya="";
          }
          // $query = mysql_query("SELECT a.*,s.supplier,datediff(due_date,'$today') jml_hari
          //     ,datediff(pay_date,due_date) jml_hari_pay,concat(d.nama_bank,' ',no_rek) rek_bank 
          //     FROM $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier
          //     left join acc_masterbank d on a.pay_bank=d.id
          //     where $crinya");
          $query = mysql_query("SELECT a.*,s.supplier,datediff(due_date,'$today') jml_hari
              ,datediff(pay_date,due_date) jml_hari_pay,concat(d.nama_bank,' ',no_rek) rek_bank 
              FROM $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier
              left join masterbank d on a.pay_bank=d.id");
          while($data = mysql_fetch_array($query))
          {   $due_date=fd_view($data['due_date']);
              $inv_date=fd_view($data['inv_date']);
              $tt_date=fd_view($data['tt_date']);
              if ($data['pay_date']!="0000-00-00") { $pay_date=fd_view($data['pay_date']); } else { $pay_date=""; }
              $amt=fn($data['amount'],2);
              if ($pay_date!="")
              { $jml_hari=$data['jml_hari_pay']; }
              else
              { $jml_hari=$data['jml_hari']; }
              echo "<tr>";
                $modenya=substr($mode,0,2);
                if ($pay_date=="")
                { if($modenya=="AP")
                  {echo "<td><a href='../fin/?mod=2&mode=$modenya&noid=$data[$fldid]'>$data[bpbno] $data[inv_no]</a></td>";}
                  else
                  {echo "<td><a href='../fin/?mod=2&mode=$modenya&noid=$data[$fldid]'>$data[bppbno] $data[inv_no]</a></td>";} 
                }
                else
                { echo "<td>$data[inv_no]</td>"; }
              echo "
                <td>$inv_date</td>
                <td>$data[no_faktur]</td>
                <td>$data[supplier]</td>
                <td>$data[curr]</td>
                <td>$amt</td>
                <td>$tt_date</td>
                <td>$due_date</td>";
                if ($pay_date=="")
                { if ($jml_hari<0)
                  { echo "<td style='background-color: red;'>$jml_hari</td>"; }
                  else
                  { echo "<td>$jml_hari</td>"; }
                }
                if ($pay_date!="")
                { if ($jml_hari>0)
                  { echo "<td style='background-color: pink;'>$jml_hari</td>"; }
                  else
                  { echo "<td>$jml_hari</td>"; }
                }
              echo "
                <td>$pay_date</td>
                <td>$data[rek_bank]</td>
              </tr>";
          }
        echo "</tbody>";
      echo "</table>";
  echo "</div>";
echo "</div>";
?>  