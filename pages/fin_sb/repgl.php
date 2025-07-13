<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode = $_GET['mode'];
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
}
else
{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
  if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
}
  
$titlenya="Laporan Purchase Order";

if ($excel=="N") 
{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
	echo "<div class='box-body'>";
		echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to);
		if ($excel=="N") 
		{ echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&dest=xls'>Save To Excel</a></br>"; }
	echo "</div>";	
echo "</div>";
echo "<div class='box'>";
	echo "<div class='box-body'>";
		if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="border='1'";}
		$sqlcoa="select * from mastercoa order by id_coa";
		$qcoa = mysql_query($sqlcoa);
    while($dcoa = mysql_fetch_array($qcoa))
    {	echo "
  		<div class='panel panel-default'>
  			<div class='panel-heading'>$dcoa[id_coa] $dcoa[nm_coa]</div>
      </div>";
      echo "<table id='customers' $tbl_border style='font-size: 12px; width: 100%;'>";
				echo "<thead>";
					echo "
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>No. Reff</th>
						<th>Keterangan</th>
						<th>Curr</th>
						<th style='text-align:right;'>Debet</th>
						<th style='text-align:right;'>Kredit</th>
					</tr>";
				echo "</thead>";
				echo "<tbody>";
				$sqldatatable = "
						SELECT  
              begbal.debit begbal_debit
              ,begbal.credit begbal_credit
            FROM 
	            mastercoa mc
	          LEFT JOIN
	            (	SELECT fd.id_coa,fd.curr,fh.date_journal,SUM(fd.debit) debit,SUM(fd.credit) credit FROM fin_journal_h fh
	              	LEFT JOIN fin_journal_d fd ON fh.id_journal = fd.id_journal WHERE fh.date_journal < '$from' 
	              	and fd.id_coa='$dcoa[id_coa]'
	              	GROUP BY fd.curr
	            ) begbal ON mc.id_coa = begbal.id_coa 
						WHERE 1=1
              AND mc.id_coa = '$dcoa[id_coa]'
            ORDER BY 
              mc.id_coa,begbal.date_journal";
	  		#echo $sqldatatable;
				$query = mysql_query($sqldatatable);
	      $no = 1;
	      $tmut_d = 0;
	      $tmut_k = 0; 
	      $tbeg_d = 0;
	      $tbeg_k = 0; 
	      while($data = mysql_fetch_array($query))
	      {	echo "
	  			<tr>
	  				<td>$no</td>";
	  				if($no==1)
	  				{	echo "
	  						<td>-</td>
			  				<td>Begining Balance</td>
			  				<td>-</td>
			  				<td>-</td>
			  				<td align='right'>".fn($data['begbal_debit'],2)."</td>
	  						<td align='right'>".fn($data['begbal_credit'],2)."</td>
	  						";
	  					$tbeg_d = $tbeg_d + $data['begbal_debit'];
	  					$tbeg_k = $tbeg_k + $data['begbal_credit'];
			  		}
	  				else
	  				{	echo "
	  						<td>".fd_view($data["date_journal"])."</td>
			  				<td>$data[reff_doc]</td>
			  				<td>$data[description]</td>
			  				<td>$data[curr]</td>
			  				<td align='right'>".fn($data['mutation_debit'],2)."</td>
	  						<td align='right'>".fn($data['mutation_credit'],2)."</td>
			  				";
	  					$tmut_d = $tmut_d + $data['mutation_debit'];
	  					$tmut_k = $tmut_k + $data['mutation_credit']; 
	  				}
	  				echo "
	  			</tr>";
	    		$no++;
	      }
	      $sqldatatablemut = "
						SELECT  
              mutation.date_journal,mutation.reff_doc,mutation.description,mutation.curr
              ,mutation.debit mutation_debit
              ,mutation.credit mutation_credit
            FROM 
	            mastercoa mc
	          LEFT JOIN
              (	SELECT fd.id_coa,fd.curr,fh.date_journal,fh.reff_doc,fd.description,
              		SUM(fd.debit) debit,SUM(fd.credit) credit FROM fin_journal_h fh
              		LEFT JOIN fin_journal_d fd ON fh.id_journal = fd.id_journal WHERE fh.date_journal >= '$from' 
              		AND fh.date_journal <= '$to' and fd.id_coa='$dcoa[id_coa]' 
                  GROUP BY fd.curr,fh.date_journal,fh.id_journal
              ) mutation ON mc.id_coa = mutation.id_coa
            WHERE 1=1
              AND mc.id_coa = '$dcoa[id_coa]'
            ORDER BY 
              mc.id_coa,mutation.date_journal";
	  		#echo $sqldatatablemut;
      	$querymut = mysql_query($sqldatatablemut);
	      while($data = mysql_fetch_array($querymut))
	      {	echo "
	  			<tr>
	  				<td>$no</td>";
	  				if($no==1)
	  				{	echo "
	  						<td>-</td>
			  				<td>Begining Balance</td>
			  				<td>-</td>
			  				<td>-</td>
			  				<td align='right'>".fn($data['begbal_debit'],2)."</td>
	  						<td align='right'>".fn($data['begbal_credit'],2)."</td>
	  						";
	  					$tbeg_d = $tbeg_d + $data['begbal_debit'];
	  					$tbeg_k = $tbeg_k + $data['begbal_credit'];
			  		}
	  				else
	  				{	echo "
	  						<td>".fd_view($data["date_journal"])."</td>
			  				<td>$data[reff_doc]</td>
			  				<td>$data[description]</td>
			  				<td>$data[curr]</td>
			  				<td align='right'>".fn($data['mutation_debit'],2)."</td>
	  						<td align='right'>".fn($data['mutation_credit'],2)."</td>
			  				";
	  					$tmut_d = $tmut_d + $data['mutation_debit'];
	  					$tmut_k = $tmut_k + $data['mutation_credit']; 
	  				}
	  				echo "
	  			</tr>";
	    		$no++;
	      }
	      echo "
	      <tr>
					<td>-</td>
					<td>-</td>
  				<td>Total Mutation</td>
  				<td>-</td>
  				<td>-</td>
  				<td align='right'>".fn($tmut_d,2)."</td>
					<td align='right'>".fn($tmut_k,2)."</td>
				</tr>
				<tr>
					<td>-</td>
					<td>-</td>
  				<td>Ending Balance</td>
  				<td>-</td>
  				<td>-</td>
  				<td align='right'>".fn($tbeg_d+$tmut_d,2)."</td>
					<td align='right'>".fn($tbeg_k+$tmut_k,2)."</td>
				</tr>";
				echo "</tbody>";
			echo "</table>";
    	echo "<br>";
    }
  echo "</div>";
echo "</div>";
?>