

<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

include 'fungsi.php';



ob_start();

?>

<style>

.td1{
    border:1px solid black;
    border-top: none;
    border-bottom: none;
    font-family: Helvetica, Arial, sans-serif;
}

td {
    font-family: Helvetica, Arial, sans-serif;
}

tr {
    font-family: Helvetica, Arial, sans-serif;
}

</style>

<?php

$quenya = "Select * from mastercompany Where company!='' ";

$strsql = mysql_query($quenya);

$rs = mysql_fetch_array($strsql);

  $nm_company = $rs['company'];

  $add_company = $rs['alamat1'];

  $add2_company = $rs['alamat2'];

  $kota_comp = $rs['kota'];

  $add3_company = "Kec. ".$rs['kec'].' '.$rs['kota'].' '.$rs['propinsi'].' '.$rs['kodepos'];

  $add4_company = "Telp. ".$rs['telp'];

  if ($rs['logo_company']=="Y")

  { $logo = "<img src='../../include/logo.jpg' width='50'>"; }

  else

  { $logo = ""; }



$bppbno=$_GET['noid'];

$mode=$_GET['mode'];

if ($mode=="In")

{ $rstot=mysql_fetch_array(mysql_query("select count(*) jdata,sum(qty) tqty from bpb where bpbno='$bppbno' 

    group by bpbno"));

  $jdata=$rstot['jdata'];

  $tqty=$rstot['tqty'];

}

else

{ $rstot=mysql_fetch_array(mysql_query("select count(*) jdata,sum(qty) tqty from bppb where bppbno='$bppbno' 

    group by bppbno"));

  $jdata=$rstot['jdata'];

  $tqty=$rstot['tqty']; 

}

if ($jdata>=1 and $jdata<8)

{ $space_head=52; }

else

{ $space_head=55; }

if (substr($bppbno,0,2)=="FG" OR substr($bppbno,3,2)=="FG")

{	

  // if($mode=="In")

  // { $sqlcek="select distinct a.id_so_det from bpb a left join masterstyle s on a.id_so_det=s.id_so_det 

  //     where a.bpbno='$bppbno' and s.goods_code is null "; }

  // else

  // { $sqlcek="select distinct a.id_so_det from bppb a left join masterstyle s on a.id_so_det=s.id_so_det 

  //     where a.bppbno='$bppbno' and s.goods_code is null "; }

  // $rscek=mysql_query($sqlcek);

  // while($data = mysql_fetch_array($rscek))

  // {

  //   cek_masterstyle($data['id_so_det']);

  // }

  $tbl_mst = "masterstyle"; 

	$fld_mst2 = "goods_code";

	if ($nm_company=="PT. Bangun Sarana Alloy")

  { $fld_mst = "concat(itemname,' Size ',size)"; }

  else if ($nm_company=="PT. Tun Hong")

  { $fld_mst = "concat('Style # ',s.styleno,' ',itemname,' Size ',size,' Warna ',color)"; }

  else

  { $fld_mst = "concat(goods_code,' - ',itemname)"; }

  $sql_join_fg = "so_det sod on sod.id=a.id_so_det 

    inner join so on so.id=sod.id_so  

    left join act_costing ac on so.id_cost=ac.id";

  $fld_add_fg3=",ac.styleno";

  $fld_add_fg2=",so.buyerno,sod.dest,sod.color,sod.size";

  $fld_add_fg31="

  <td align='center'>Style #</td>";

  $fld_add_fg="

  <td align='center'>PO #</td>

  <td align='center'>Dest</td>

  <td align='center'>Color</td>

  <td align='center'>Size</td>";

  $jml_fld = 10;

  $jml_fld_sp = 8;

}

else

{	$tbl_mst = "masteritem"; 

	$fld_mst2 = "goods_code";

	if ($nm_company=="PT. Bangun Sarana Alloy")

  { $fld_mst = "concat(goods_code,' ',itemdesc,' Size ',size)"; }

  else if ($nm_company=="PT. Tun Hong")

  { $sql="update bpb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bpbno='$bppbno' "; 

    insert_log($sql,"");

    $sql="update bppb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bppbno='$bppbno' "; 

    insert_log($sql,"");

    $fld_mst = "concat('Style # ',a.styleno,' ',itemdesc,' Size ',size,' Warna ',color)"; 

  }

  else

  { if(substr($bppbno,0,1)=="N")

    {

      $fld_mst = "concat(goods_code,' ',itemdesc)";

    }

    else

    {

      $fld_mst = "itemdesc";

    }  

  }

  $sql_join_fg = "(select id_jo,id_so from jo_det group by id_jo) jod on a.id_jo=jod.id_jo 

    left join so on jod.id_so=so.id 

    left join act_costing ac on so.id_cost=ac.id";

  $fld_add_fg3="";

  $fld_add_fg31="";

  $fld_add_fg="";

  $fld_add_fg2="";

  $jml_fld = 5;

  $jml_fld_sp = 3;

}

$logo = "";



if ($nm_company=="PT. Bangun Sarana Alloy")

{ $tglcetak=""; 

  $orientasi_kertas = "L"; # L = Landscape P = Portrait

  $ukuran_kertas=array(105,148.5); # Ukuran kertas W/Lebar x H/Tinggi

}

else

{ $tglcetak="Dicetak : ".date('Y-m-d H:i'); 

  $ukuran_kertas="A4"; 

  $orientasi_kertas = "P"; # L = Landscape P = Portrait

}

if ($mode=="In")

{ $head_cap = "Bukti Penerimaan Barang";

  $rsh=mysql_fetch_array(mysql_query("select group_concat(distinct ac.kpno) wsno,a.pono,

    a.invno,a.bpbdate from bpb a left join jo_det jod on a.id_jo=jod.id_jo 

    left join so on jod.id_so=so.id left join act_costing ac on so.id_cost=ac.id  

    where a.bpbno='$bppbno' limit 1"));

  $head_data = '

  <table width="100%" style="border:none; font-family: Helvetica, Arial, sans-serif;">

    <tr>

      <td width="5%"></td>

      <td></td>

      <td width="9%">SJ # / Inv #</td>

      <td> : '.$rsh['invno'].'</td>

    </tr>

    <tr>

      <td>No PO</td>

      <td> : '.$rsh['pono'].'</td>

      <td>Tgl. BPB</td>

      <td> : '.fd_view($rsh['bpbdate']).'</td>

    </tr>

  </table>';

  $quenya = "Select if(a.bpbno_int<>'',a.bpbno_int,a.bpbno) trans_no,

    a.bpbdate trans_date,a.*,s.supplier,s.alamat,s.alamat2,up.fullname,upcfm.fullname cfmfullname  

    from bpb a inner join mastersupplier s 

    on a.id_supplier=s.id_supplier 

    left join userpassword up on a.username=up.username 

    left join userpassword upcfm on a.confirm_by=upcfm.username 

    Where bpbno='$bppbno'";

}

else

{ $head_cap = "Surat Jalan";

  $head_data = '';

  $quenya = "Select if(a.bppbno_int<>'',a.bppbno_int,a.bppbno) trans_no,a.bppbdate trans_date,a.*,s.supplier,

    s.alamat,s.alamat2,up.fullname,upcfm.fullname cfmfullname from bppb a inner join mastersupplier s 

    on a.id_supplier=s.id_supplier 

    left join userpassword up on a.username=up.username 

    left join userpassword upcfm on a.confirm_by=upcfm.username  

    Where bppbno='$bppbno'";

}

$strsql = mysql_query($quenya);

$rs = mysql_fetch_array($strsql);

  $tglsj=$rs['trans_date'];

  $sentto=$rs['supplier'];

  $add_sentto=$rs['alamat'];

  $add2_sentto=$rs['alamat2'];

  $kota=$kota_comp.", ".fd_view($tglsj);

  $fullname=$rs['fullname'];

  $cfmfullname  =$rs['cfmfullname'];

  $dateinput=$rs['dateinput'];

  $cfmdateinput=$rs['confirm_date'];

if ($mode=="Out")

{	$ttdnya = 

'<div style=" margin-bottom: 2.54cm; border-collapse:collapse;  font-family: Helvetica, Arial, sans-serif;"> 
  <!-- <table style="font-size:10px;" border="1"> -->
  
    <tr>  
      <th style="font-size: 11px; width: 200px">Created By : </th>
      <th style="font-size: 11px; width: 200px">Checked By : </th>
      <th style="font-size: 11px; width: 200px">Approved By : </th>
  
    </tr>
    <tr>  
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp;</td>           
    </tr>   
    <tr>  
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp; </td>      
    </tr>   
    <tr>  
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp; </td>
    </tr>   
    <tr>  
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp; </td>
    </tr>   
    <tr style="border-bottom: none;"> 
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp;</td>
      <td class="td1">&nbsp; </td>
  
    </tr>
    <tr style="border-collapse: collapse; border-top: none;"> 
      <td style="font-size:10px;text-align:center;text-decoration:underline">(________________________) </td>
      <td style="font-size:10px;text-align:center">(________________________) </td>
      <td style="font-size:10px;text-align:center">(________________________) </td>
  
  
    </tr>       
    <tr>  
      <td style="text-align:center;font-size:11px"></td>
      <td style="text-align:center;font-size:11px">Kabag </td>
      <td style="text-align:center;font-size:11px">Direktur </td>
  
  
    </tr>';
}
//     if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG") 

//     { $ttdnya = $ttdnya.

//       '<td width="200px" style="margin-left:-5px;border:none;" align="left">

//         Approved By.

//       </td>

//       <td style="margin-left:-5px;border:none;" align="left">

//         Received By.

//       </td>'; 

//     }

//   	$ttdnya = $ttdnya.

//     '</tr>

//   	<tr>

//   		<td width="200px" style="margin-right:-5px;border:none;" align="left">

//         '.$fullname.'<br>'.$dateinput.' 

//       </td>';

//       if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG")

//       { $ttdnya = $ttdnya.'<td width="200px" style="margin-right:-5px;border:none;" align="left">

//           '.$cfmfullname.'<br>'.$cfmdateinput.' 

//         </td>';

//       }

//       $ttdnya = $ttdnya.'<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>

// 	  </tr>';

// }

// else

// {	$ttdnya = 

//   '<tr>

// 		<td width="300px" style="margin-right:-5px;border:none;" align="left">

// 			Created By.

// 		</td>';

//     if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG") 

//     { $ttdnya = $ttdnya.

//       '<td width="200px" style="margin-left:-5px;border:none;" align="left">

//         Approved By.

//       </td>

//       <td style="margin-left:-5px;border:none;" align="left">

//         Received By.

//       </td>'; 

//     }

//     $ttdnya = $ttdnya.

// 	'</tr>

// 	<tr>

// 		<td width="200px" style="margin-right:-5px;border:none;" align="left">

//       '.$fullname.'<br>'.$dateinput.' 

//     </td>';

//     if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG")

//     { $ttdnya = $ttdnya.'<td width="200px" style="margin-right:-5px;border:none;" align="left">

//         '.$cfmfullname.'<br>'.$cfmdateinput.' 

//       </td>';

//     }

//     $ttdnya = $ttdnya.'<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>

// 	</tr>';

// }

$footernya = 

	'<table cellpadding=0 cellspacing=0 style="border:none;">

		<tr>

			<td style="margin-right:-5px;border:none;font-size:8px;" align="left">

				Halaman: {PAGENO} / {nb}

			</td>

			<td style="margin-right:-5px;border:none;" align="left">

			'.$tglcetak.'

			</td>

		</tr>

	</table>';

?>
<br>
<br>
<br>
<br>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" 

  style="border-collapse: collapse; width:100%; font-size:10pt; font-family: Helvetica, Arial, sans-serif;">

	<thead>

		<tr class="head">

			<td align='center' height='20' style='font-size:12pt;'>No.</td>

			<td align='center' style='font-size:12pt;'>WS #</td>

      <?php echo $fld_add_fg31; ?>

      <td align='center' style='font-size:12pt;'>Nama Barang</td>

			<?php echo $fld_add_fg; ?>

      <td align='center' style='font-size:12pt;'>Jumlah</td>

			<td align='center' style='font-size:12pt;'>Satuan</td>

			<td align='center' style='font-size:12pt;'>Keterangan</td>

		</tr>

	</thead>

	<tbody>

		<?php

      if ($mode=="In")

      { tampil_data("select ac.kpno $fld_add_fg3 ,$fld_mst $fld_add_fg2 ,a.qty,a.unit,a.remark  

          from bpb a inner join $tbl_mst s on a.id_item=s.id_item 

          left join 

          $sql_join_fg 

          where bpbno='$bppbno'",$jml_fld);

        echo "

        <tr>

          <td colspan='$jml_fld_sp' align='right'>Total </td>

          <td align='right'>$tqty </td>

        </tr>

        ";

      }

      else

      { tampil_data("select ac.kpno $fld_add_fg3 ,$fld_mst $fld_add_fg2 ,a.qty,a.unit,a.remark  

          from bppb a inner join $tbl_mst s on a.id_item=s.id_item 

          left join 

          $sql_join_fg   

          where bppbno='$bppbno'",$jml_fld);

        echo "

        <tr>

          <td colspan='$jml_fld_sp' align='right'>Total </td>

          <td align='right'>$tqty </td>

        </tr>

        ";

      }

    ?>

	</tbody>

</table>
<br>
<table style="font-size:10px; border-collapse: collapse;" border="1" align="center">

	<?php 

	echo $ttdnya;

	?>

</table>


</div>

<?php

$header = '<table cellpadding=0 cellspacing=0 style="border:none;">

            <tr>

              <td style="border:none;" align="left">'.$logo.'</td>

              <td width="100%" style="border:none;"><h4>'.$nm_company.'</h4></td>

              <td width="100%" style="border:none; font-size:12pt;">'.$kota.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:12pt;">'.$add_company.'</td>

              <td width="100%" style="border:none; font-size:12pt;">'.$sentto.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:12pt;">'.$add2_company.'</td>

              <td width="100%" style="border:none; font-size:12pt;">'.$add_sentto.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:12pt;">'.$add3_company.'</td>

              <td width="100%" style="border:none; font-size:12pt;">'.$add2_sentto.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:12pt;">'.$add4_company.'</td>

            </tr>

          </table>

          <table width="100%" style="border:none;">

            <tr>

              <td align="center" style="border:none;"><h2>'.$head_cap.'</h2></td>

            </tr>

            <tr>

              <td align="center" style="border:none; font-size:14pt;">'.$rs['trans_no'].'</td>

            </tr>

          </table>
          

          '.$head_data.'';

$content = ob_get_clean();

$footer =  $footernya;           

try {

    # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan, Space_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

    $mpdf=new mPDF('utf-8', $ukuran_kertas, 9 ,'Arial', 5, 5, $space_head, 5, 5, 1, $orientasi_kertas);

    $mpdf->SetTitle("Laporan");

    $mpdf->setHTMLHeader($header);

    $mpdf->setHTMLFooter($footer);

    $mpdf->WriteHTML($content);

    $mpdf->Output("laporan.pdf","I");

} catch(Exception $e) {

    echo $e;

    exit;

}

?>