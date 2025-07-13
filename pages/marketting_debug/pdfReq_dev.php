<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
    public static $sketch_patch = 'upload_files/costing/';
}

$xid=$_GET['id'];
$query = mysql_query("SELECT sod.*,ms.supplier
                FROM 
                request_sample_dev sod INNER JOIN mastersupplier ms on sod.id_buyer=ms.id_supplier
                
            WHERE id=$xid "); 
$data = mysql_fetch_array($query);
$id_jenis=$data['id_jenis'];
$id_jenis = explode("|" , $id_jenis);

$PAT="";
$PATR="";
$GRA="";
$PRO="";
$STY="";
$SAL="";
$SET="";
$APP="";
$APPR="";
$PRE="";
$GRM="";

for($i=0;$i< count($id_jenis);$i++)
{
	if ($id_jenis[$i]=="PAT")
	{
		$PAT="checked='checked'";
	}
	else if ($id_jenis[$i]=="PAT-R")
	{
		$PATR="checked='checked'";
	}
	else if ($id_jenis[$i]=="GRA")
	{
		$GRA="checked='checked'";
	}
	else if ($id_jenis[$i]=="PRO")
	{
		$PRO="checked='checked'";
	}
	else if ($id_jenis[$i]=="STY")
	{
		$STY="checked='checked'";
	}
	else if ($id_jenis[$i]=="SAL")
	{
		$SAL="checked='checked'";
	}
	else if ($id_jenis[$i]=="SET")
	{
		$SET="checked='checked'";
	}
	else if ($id_jenis[$i]=="APP")
	{
		$APP="checked='checked'";
	}
	else if ($id_jenis[$i]=="APP-R")
	{
		$APPR="checked='checked'";
	}
	else if ($id_jenis[$i]=="PRE")
	{
		$PRE="checked='checked'";
	}
	else if ($id_jenis[$i]=="GRM")
	{
		$GRM="checked='checked'";
	}
	
}



$id_lampiran=$data['id_lampiran'];
$id_lampiran = explode("|" , $id_lampiran);

$ORG="";
$SPAT="";
$COMT="";

for($j=0;$j< count($id_lampiran);$j++)
{
	if ($id_lampiran[$j]=="ORG")
	{
		$ORG="checked='checked'";
	}
	else if ($id_lampiran[$j]=="SPAT")
	{
		$SPAT="checked='checked'";
	}
	else if ($id_lampiran[$j]=="COMT")
	{
		$COMT="checked='checked'";
	}
}					  

$id_acc=$data['id_acc'];
$id_acc = explode("|" , $id_acc);					  

$acc1="";
$acc2="";
$acc3="";
$acc4="";
$acc5="";
$acc6="";
$acc7="";
$acc8="";
					  
for($k=0;$k< count($id_acc);$k++)
{
	if ($id_acc[$k]=="ACC-01")
	{
		$acc1="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-02")
	{
		$acc2="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-03")
	{
		$acc3="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-04")
	{
		$acc4="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-05")
	{
		$acc5="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-06")
	{
		$acc6="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-07")
	{
		$acc7="checked='checked'";
	}
	else if ($id_acc[$k]=="ACC-08")
	{
		$acc8="checked='checked'";
	}
}

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            html {
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }
            html, body{
                font-family: sans-serif;
                width: 100%;
                height: 100%;
                margin:0;
                padding:0;
            }
            table {
                border-spacing: 0;
                border-collapse: collapse;
                margin:0;
                padding:0;
            }
            td,
            th {
                padding: 2px;
                margin:0;
            }
            .table {
                border-collapse: collapse !important;
                width: 100%;
				font-size: 12px;
            }
            .table td{
                background-color: #fff;
                font-weight: 
            }
            .table th {
                background-color: #fff;
                font-weight: normal;
            }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #ddd !important;
            }
            .text-right {
                text-align: right !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-left{
                text-align: left !important;
            }
        </style>
        <title>Worksheet</title>
    </head>
    <body>
    <table class="table" style="border-bottom: 2px solid #000000; margin-bottom:5px;">
        <tr>
            <td><img src="<?=Assets::$logo?>" width="100px" height="70px"> </td>
            <td style="text-align: right;vertical-align: bottom;font-size: 16px;">SIGNAL BIT</td>
        </tr>
    </table>
    <p align="center"><h3>PERMINTAAN PEMBUATAN PATRUN / SAMPLE</h3></p>
    <table class="table table-condensed" >
        <tr>
            <th colspan="4">&nbsp;</th>
        </tr>
        <tr>
            <th class="text-left" style="width: 300px" rowspan="6">Jenis Permintaan: 
			<br><input type="checkbox" <?php echo $PAT;?>> Patrun
			<br><input type="checkbox" <?php echo $PATR;?>> Patrun Revisi
			<br><input type="checkbox" <?php echo $GRA;?>> Grading
			<br><input type="checkbox" <?php echo $PRO;?>> Proto Sample
			<br><input type="checkbox" <?php echo $STY;?>> Style / Advertising / Photo Sample
			<br><input type="checkbox" <?php echo $SAL;?>> Salesman Sample
			<br><input type="checkbox" <?php echo $SET;?>> Size Set Sample
			<br><input type="checkbox" <?php echo $APP;?>> Approval Sample
			<br><input type="checkbox" <?php echo $APPR;?>> Revisi Approval Sample
			<br><input type="checkbox" <?php echo $PRE;?>> Pre Prduction Sample
			<br><input type="checkbox" <?php echo $GRM;?>> Garment Test Sample</th>
            <th class="text-right" style="width: 20px">No</th>
			<th class="text-left" style="width: 50px">: <?php echo $data['no_req'];?></th>
        </tr>
		<tr>
        
            <th class="text-right" style="width: 200px">Tanggal</th>
			<th class="text-left" style="width: 200px">: <?php echo $data['req_date'];?></th>
        </tr>
		<tr>
            
            <th class="text-right" style="width: 200px">Buyer</th>
			<th class="text-left" style="width: 200px">: <?php echo $data['supplier'];?></th>
        </tr>
		<tr>
          
            <th class="text-right" style="width: 200px">Style No</th>
			<th class="text-left" style="width: 200px">: <?php echo $data['id_item'];?></th>
        </tr>
		<tr>
      
            <th class="text-right" style="width: 200px">Order</th>
			<th class="text-left" style="width: 200px">: <?php echo $data['xorder'];?></th>
        </tr>
		<tr>
         
            <th class="text-right" style="width: 200px">Follow Up</th>
			<th class="text-left" style="width: 200px">: <?php echo $data['username'];?></th>
        </tr>
    </table>
	<br>
   

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="50%" rowspan="3">Jenis Kain</th>
                    <th width="25%" rowspan="3">Warna</th>
					<th width="25%" colspan="7">Qty Sample</th>
					<th width="25%" rowspan="3">Jumlah</th>
                </tr>
				<tr>
                    <th width="50%" colspan="7">Qty</th>
                </tr>
				<?php

				
							
								
								
				?>
				<tr>
                    <th width="5%" >S</th>
					<th width="5%" >M</th>
					<th width="5%" >L</th>
					<th width="5%" >XL</th>
					<th width="5%" >XXL</th>
					<th width="5%" >XXXL</th>
					<th width="5%" ></th>
					
                </tr>
                </thead>
                <tbody>
				<?php
				$QTY="";
				
				$xquery = mysql_query("SELECT 
									   *
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and cancel!='Y' group by kain"); 
						while($xdata = mysql_fetch_array($xquery))
						{
						$vkain=$xdata['kain'];
				?>				
                     <tr>
                      
                        <td class="text-center"><?php echo $xdata['kain'];?></td>
             			<td class="text-center"><?php echo $xdata['color'];?></td>
						<td class="text-center"><?php 
						$sxquery = mysql_query("SELECT 
									   sum(qty) as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and size='S' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						?></td>
						
						<td class="text-center"><?php 
						$sxquery = mysql_query("SELECT 
									   sum(qty) as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and size='M' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						
						?></td>
						
						<td class="text-center"><?php 
												$sxquery = mysql_query("SELECT 
									   sum(qty) as qty 
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and size='L' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						?></td>
						
						<td class="text-center"><?php 
						$sxquery = mysql_query("SELECT 
									   sum(qty) as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and size='XL' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						?></td>
						
						<td class="text-center"><?php 
						$sxquery = mysql_query("SELECT 
									   sum(qty) as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and size='XXL' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						?></td>
						
						<td class="text-center"><?php 
						$sxquery = mysql_query("SELECT 
									   sum(qty) as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and size='XXXL' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						?></td>
						<td class="text-center"><?php ?></td>
						<td class="text-center"><?php 
						$sxquery = mysql_query("SELECT 
									   sum(qty) as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and kain='$vkain' and cancel!='Y' "); 
						$xdatan = mysql_fetch_array($sxquery);
							echo $xdatan['qty'];
						
						?></td>
					</tr>
					<?php
						}
					?>
					<tr>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-center"></td>
                        <td class="text-center"></td>
             			<td class="text-center"></td>
						<td class="text-center">Total</td>
						<td class="text-center"><?php  $xxquery = mysql_query("SELECT 
									   sum(qty)as qty
										FROM 
										request_det_dev 
										
									WHERE id_req=$xid and cancel!='Y' "); 
						$xxdata = mysql_fetch_array($xxquery);
							echo $xxdata['qty'];
						?></td>
					</tr>
                </tbody>
            </table>
			<br><br>
			
	  <table>
                <thead>
                <tr>
                    <th style="width: 200px" class="text-left" >Lampiran :</th>
                    <th width="25%" style="border: 1px solid #ddd" colspan="2">Permintaan Selesai (Tgl)</th>
					<th width="25%" style="border: 1px solid #ddd" colspan="2">Selesai (Tgl)</th>
					
                </tr>
                </thead>
                <tbody>
                     <tr>
                        <td width="25%" class="text-left" rowspan="2">
						<input type="checkbox" <?php echo $ORG ;?>> Original Sample
						<br><input type="checkbox" <?php echo $SPAT;?>> Size Spec / Patrun
						<br><input type="checkbox" <?php echo $COMT;?>> Comment Buyer</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">Patrun</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">Sample</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">Patrun</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">Sample</td>
					</tr>
					<tr>
                      
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><?php echo $data['date_perselpat'];?></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><?php echo $data['date_perselsam'];?></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><?php echo $data['date_selpat'];?></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><?php echo $data['date_selsam'];?></td>
					</tr>
					<tr>
					<td colspan="5"><br></td>
					</tr>
					 <tr>
                      
                        <td width="25%" class="text-left">Accessories</td>
						<td width="25%" class="text-center"></td>
						<td width="25%" colspan="2" style="border: 1px solid #ddd"  class="text-center">Check Patrun (Tgl)</td>
						<td width="25%" class="text-center"></td>
					</tr>
					<tr>
						<td width="25%" class="text-left" rowspan="3">	
							<input type="checkbox" <?php echo $acc1;?>> Elastic
						<br><input type="checkbox" <?php echo $acc2;?>> Kancing
						<br><input type="checkbox" <?php echo $acc3;?>> Resleting
						<br><input type="checkbox" <?php echo $acc4;?>> Vetter Band
						<br><input type="checkbox" <?php echo $acc5;?>> Kain Beras
						<br><input type="checkbox" <?php echo $acc6;?>> Talikur
						<br><input type="checkbox" <?php echo $acc7;?>> Label
						<br><input type="checkbox" <?php echo $acc8;?>> Lain-Lain</td>
						<td width="25%" class="text-center"></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center">Masuk</td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center">Keluar</td>
						<td width="25%"  class="text-center"></td>
					</tr>
					
					<tr>
                      
						
						<td width="25%" class="text-center"></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><?php echo $data['date_cekpat'];?></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><?php echo $data['date_selcekpat'];?></td>
						<td width="25%"  class="text-center"></td>
					</tr>
					<tr>
						<td width="25%" class="text-center"></td>
						<td width="25%" class="text-center"><br><br><br><br></td>
						<td width="25%" class="text-center"></td>
						<td width="25%"  class="text-center"></td>
					</tr>					
					<tr>
                      
                        <td width="25%" class="text-left" rowspan="2">Folow Up</td>
						<td width="25%" class="text-center"><br><br>Kepada Bagian</td>
						<td width="25%" class="text-center"></td>
						<td width="25%" class="text-center"></td>
						<td width="25%" class="text-center"></td>
					</tr>
					<tr>
                      
						<td width="25%"  class="text-center"><br><br></td>
						<td width="25%"  class="text-center"></td>
						<td width="25%"  class="text-center"></td>
						<td width="25%"  class="text-center"></td>
					</tr>
					 <tr>
                      
                        <td width="25%" class="text-left" rowspan="2">
															<br>
															<br>
															<?php echo $data['username'];?></td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">POLA</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">SAMPLE</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">DEVELOPMENT</td>
						<td width="25%" style="border: 1px solid #ddd"  class="text-center">MGR. PROD</td>
					</tr>
					<tr>
                      
						<td width="25%" style="border: 1px solid #ddd" class="text-center"><br><br></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"></td>
						<td width="25%" style="border: 1px solid #ddd" class="text-center"></td>
					</tr>
                </tbody>
       </table>
	   
	 
	   
	
    </body>
    </html>
<?php
$html = ob_get_clean();
include("../../mpdf57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
?>