<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$id_cost=$_GET['id'];
$color=$_GET['color'];
$id_mark_entry=$_GET['url'];
//$id_panel=$_GET['id_panel'];
// echo "id_cost : ".$id_cost." <br>color : ".$color." <br>id_mark_entry : ".$id_mark_entry." <br>id_panel : ".$id_panel;
$q = "CALL spGetExcelMarkerEntry('{$id_cost}','{$color}','{$id_mark_entry}')"; //,'{$id_panel}')";
$q2 = "CALL spGetExcelMarkerEntry('{$id_cost}','{$color}','{$id_mark_entry}')"; //,'{$id_panel}')";

$qp = "CALL spGetExcelMarkerEntry('{$id_cost}','{$color}','{$id_mark_entry}')"; //,'{$id_panel}')";

// echo '<pre>',print_r($q2,1),'</pre>'; 

$spp = mysqli_query($conn_li,$qp);
$count_group_det=0;
if($spp->num_rows!==0){ //ambil array index size dan value qty 
  while ($stmtp = mysqli_fetch_assoc($spp)) {
    $count_group_det++;
    //print_r($count_group_det); die();
  }
}

$conn_li->close();

include '../../include/conn.php';
$sp = mysqli_query($conn_li,$q);
$conn_li->close();

while ($stmt = mysqli_fetch_assoc($sp)) {
include '../../include/conn.php';

$sp2 = mysqli_query($conn_li,$q2);
if($sp2->num_rows!==0){ //ambil array index size dan value qty 
  $stmt2 = mysqli_fetch_assoc($sp2);
  unset($stmt2["id"]);
  unset($stmt2["id_cost"]);
  unset($stmt2["id_group_det"]);
  unset($stmt2["styleno"]);
  unset($stmt2["nomor_WS"]);
  unset($stmt2["deldate"]);
  unset($stmt2["nama_panel"]);
  unset($stmt2["color"]);
  unset($stmt2["id_panel"]);
  unset($stmt2["gsm"]);
  unset($stmt2["width"]);
  unset($stmt2["b_cons_kg"]);
  unset($stmt2["buyer"]);
  unset($stmt2["unit_yds"]);
  unset($stmt2["unit_inch"]);
  unset($stmt2["pono"]);
  unset($stmt2["itemdesc"]);
  foreach ($stmt2 as $key => $value) {
    // if(strpos($key, "size") !== false){ //EXCLUDE SIZE BIAR BISA DITARIK
    //   unset($stmt2[$key]);
    // }
    if(strpos($key, "spread") !== false){
      unset($stmt2[$key]);
    }
    if(strpos($key, "ratio") !== false){
      unset($stmt2[$key]);
    }
    if(strpos($key, "kurang") !== false){
      unset($stmt2[$key]);
    }
  }

}

$conn_li->close();

include '../../include/conn.php';
$sp3 = mysqli_query($conn_li,$q2);
$rArr= array();
$arr=0;
if($sp3->num_rows!==0){ //ambil array index ratio dan value ratio 
  while ($stmt3 = mysqli_fetch_assoc($sp3)) {
    unset($stmt3["id"]);
    unset($stmt3["id_cost"]);
    unset($stmt3["id_group_det"]);
    unset($stmt3["styleno"]);
    unset($stmt3["nomor_WS"]);
    unset($stmt3["deldate"]);
    unset($stmt3["nama_panel"]);
    unset($stmt3["color"]);
    unset($stmt3["id_panel"]);
    unset($stmt3["gsm"]);
    unset($stmt3["width"]);
    unset($stmt3["b_cons_kg"]);
    unset($stmt3["buyer"]);
    unset($stmt3["unit_yds"]);
    unset($stmt3["unit_inch"]);
    unset($stmt3["pono"]);
    unset($stmt3["itemdesc"]);

    foreach ($stmt3 as $key => $value) {
      if(strpos($key, "size") !== false){
        unset($stmt3[$key]);
      }
      if(strpos($key, "spread") !== false){
        unset($stmt3[$key]);
      }
    // if(strpos($key, "ratio") !== false){ //EXCLUDE RATIO BIAR BISA DITARIK
    //   unset($stmt3[$key]);
    //}
    if(strpos($key, "ratio") !== false){ //PUSH MULTI ARRAY
      $rArr[$arr][]=$stmt3[$key];
    }
    if(strpos($key, "kurang") !== false){
      unset($stmt3[$key]);
    }
  }
  
  $arr++;
}

  // echo '<pre>',print_r($rArr,1),'</pre>';
}


$conn_li->close();

include '../../include/conn.php';
$sp4 = mysqli_query($conn_li,$q2);
$sArr= array();
$arrs=0;
if($sp4->num_rows!==0){ //ambil array index spreading dan value spreading 
  while ($stmt4 = mysqli_fetch_assoc($sp4))
  {
    unset($stmt4["id"]);
    unset($stmt4["id_cost"]);
    unset($stmt4["id_group_det"]);
    unset($stmt4["styleno"]);
    unset($stmt4["nomor_WS"]);
    unset($stmt4["deldate"]);
    unset($stmt4["nama_panel"]);
    unset($stmt4["color"]);
    unset($stmt4["id_panel"]);
    unset($stmt4["gsm"]);
    unset($stmt4["width"]);
    unset($stmt4["b_cons_kg"]);
    unset($stmt4["buyer"]);
    unset($stmt4["unit_yds"]);
    unset($stmt4["unit_inch"]);
    unset($stmt4["pono"]);
    unset($stmt4["itemdesc"]);
    foreach ($stmt4 as $key => $value) {
      if(strpos($key, "size") !== false){
        unset($stmt4[$key]);
      }
    // if(strpos($key, "spread") !== false){ //EXCLUDE SPREADING BIAR BISA DITARIK
    //   unset($stmt4[$key]);
    //}
    if(strpos($key, "spread") !== false){ //PUSH MULTI ARRAY
      $sArr[$arrs][]=$stmt4[$key];
    }
    if(strpos($key, "ratio") !== false){ 
      unset($stmt4[$key]);
    }
    if(strpos($key, "kurang") !== false){
      unset($stmt4[$key]);
    }
  }
  $arrs++;
}

// echo '<pre>',print_r($sArr,1),'</pre>';
}

$conn_li->close();

include '../../include/conn.php';
$sp5 = mysqli_query($conn_li,$q2);
$kArr= array();
$arrk=0;
if($sp5->num_rows!==0){ //ambil array index kurang dan value kurang 
  while($stmt5 = mysqli_fetch_assoc($sp5)) {
    unset($stmt5["id"]);
    unset($stmt5["id_cost"]);
    unset($stmt5["id_group_det"]);
    unset($stmt5["styleno"]);
    unset($stmt5["nomor_WS"]);
    unset($stmt5["deldate"]);
    unset($stmt5["nama_panel"]);
    unset($stmt5["color"]);
    unset($stmt5["id_panel"]);
    unset($stmt5["gsm"]);
    unset($stmt5["width"]);
    unset($stmt5["b_cons_kg"]);
    unset($stmt5["buyer"]);
    unset($stmt5["unit_yds"]);
    unset($stmt5["unit_inch"]);
    unset($stmt5["pono"]);
    unset($stmt5["itemdesc"]);
    foreach ($stmt5 as $key => $value) {
      if(strpos($key, "size") !== false){
        unset($stmt5[$key]);
      }
      if(strpos($key, "spread") !== false){ 
        unset($stmt5[$key]);
      }
      if(strpos($key, "ratio") !== false){ 
        unset($stmt5[$key]);
      }
    if(strpos($key, "kurang") !== false){ //PUSH MULTI ARRAY
      $kArr[$arrk][]=$stmt5[$key];
    }
    // if(strpos($key, "kurang") !== false){ //EXCLUDE KURANG BIAR BISA DITARIK
    //   unset($stmt5[$key]);
    // }
  }
  $arrk++;
}
  // echo '<pre>',print_r($kArr,1),'</pre>';
}
// die();


//$stmt = mysqli_fetch_assoc($sp);

// while ($stmt = mysqli_fetch_assoc($sp)) {

//   $data[] = $stmt;

//   //foreach ($stmt as $key['id_group_det'] => $value) {
    
//     //echo " id_group_det --> ".$stmt['id_group_det']."<br> id_panel --> ".$stmt['id_panel']."<br> itemdesc --> ".$stmt['itemdesc']."<br><br>";
  
//   //}
  
// }


// echo "<br>";
// echo "<table border='1'>";
// while ($row = mysqli_fetch_assoc($sp)) { // Important line !!! Check summary get row on array ..
//     echo "<tr>";
//     foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
//         echo "<td>" . $value . "</td>"; // I just did not use "htmlspecialchars()" function. 
//     }
//     echo "</tr>";
// }
// echo "</table>";

#VALUE
$no_ws = $stmt['nomor_WS'];
$deldate = $stmt['deldate'];
$styleno = $stmt['styleno'];
$panel = $stmt['nama_panel'];
$b_cons_kg = $stmt['b_cons_kg'];
$width = $stmt['width'];
$gsm = $stmt['gsm'];
$buyer = $stmt['buyer'];
$unit_yds = $stmt['unit_yds'];
$unit_inch = $stmt['unit_inch'];
$pono = $stmt['pono'];
$itemdesc = $stmt['itemdesc'];

$conn_li->close();



include '../../include/conn.php';
$sp1 = mysqli_query($conn_li,$q);


#VALUE
$no_ws = $stmt['nomor_WS'];
$deldate = $stmt['deldate'];
$styleno = $stmt['styleno'];
$panel = $stmt['nama_panel'];
$b_cons_kg = $stmt['b_cons_kg'];
$width = $stmt['width'];
$gsm = $stmt['gsm'];
$buyer = $stmt['buyer'];
$unit_yds = $stmt['unit_yds'];
$unit_inch = $stmt['unit_inch'];
$pono = $stmt['pono'];
$itemdesc = $stmt['itemdesc'];

$current_time = date("Ymd");
$today = date("d-M");
header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=MarkerEntry_".$current_time.".xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");

$td = "";

$td .= "<table border='1'>";

$td .= "<tbody>";

$td .= "<tr width='100px'>";
$td .= "<td style='vertical-align: middle; text-align: center; font-size: 12pt; font-weight: bold;' colspan='11' rowspan='3'>PT. NIRWANA ALABARE GARMENT</td>";
$td .= "<td colspan='7'>&nbsp;</td>";
$td .= "</tr>";

$td .= "<tr>";
$td .= "<td colspan='7'>&nbsp;</td>";
$td .= "</tr>";

$td .= "<tr style='font-size: 11pt;'>";
$td .= "<td class='panel_val' colspan='3' style='font-size: 14pt; font-weight: bold;' >". $panel ."</td></tr>";

$td .= "<tr>";
$td .= "<td style='background-color: powderblue; font-weight: bold; font-size: 10pt; text-align: center; border-bottom-style: double; border-width: 3px;' colspan='2'>SZ</td>";

$tdNumberAda=0;
if($sp2->num_rows!==0){
  foreach($stmt2 as $key=>$value) {
    $tdNumberAda++;
    $td .="<td style='color: blue; font-size: 11pt; font-weight: bold; text-align: center; border-bottom-style: double; border-width: 3px;'>".trim($key,'size_')."</td>";
  } 
  $tdNumberSisa=6-$tdNumberAda;
  for($i=0; $i < $tdNumberSisa; $i++){
    $td .="<td></td>";
  }
} else {
  $tdNumberSisa=6-$tdNumberAda;
  for($i=0; $i < $tdNumberSisa; $i++){
    $td .="<td style='background-color: d1d1d1'></td>";
  }
}

$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td class='date_val' colspan='2'>". $today ."</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "</tr>";

$td .= "<tr style='text-align: center; font-size: 11pt;'>";
$td .= "<td class='color_val' style='vertical-align: middle; background-color: powderblue; font-weight: bold; font-size: 10pt; text-align: center; border-bottom-style: double; border-width: 3px;' colspan='2' rowspan='3'>". $color ."</td>";

$tdNumberAda=0;
if($sp2->num_rows!==0){
  foreach($stmt2 as $key=>$value) {
    $tdNumberAda++;
    $td .="<td style='font-size: 11pt; font-weight: bold; text-align: center;'>".$value."</td>";
  } 
  $tdNumberSisa=6-$tdNumberAda;
  for($i=0; $i < $tdNumberSisa; $i++){
    $td .="<td></td>";
  }
} else {
  $tdNumberSisa=6-$tdNumberAda;
  for($i=0; $i < $tdNumberSisa; $i++){
    $td .="<td style='background-color: d1d1d1'></td>";
  }
}

$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td style='font-weight: bold;'>WS:</td>";
$td .= "<td style='font-weight: bold;' class='ws_val'>". $no_ws ."</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "</tr>";

$td .= "<tr>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";
$td .= "<td>&nbsp;</td>";

$td .= "<td style='font-weight: bold;'>DEL:</td>";
$td .= "<td style='font-weight: bold;' class='del_date_val'>". $deldate ."</td>";
$td .= "<td>&nbsp;</td>";
$td .= "</tr>";

$td .= "<tr>";

$_sum_qty = 0;
$tdNumberAda=0;
if($sp2->num_rows!==0){
  foreach($stmt2 as $key=>$value) {
    $tdNumberAda++;
    $td .="<td style='font-size: 11pt; font-weight: bold; text-align: center; border-bottom-style: double; border-width: 3px;'>100%</td>";
    $_sum_qty = $_sum_qty + $value; //total_qty
  } 
$tdNumberSisa=6-$tdNumberAda;
for($i=0; $i < $tdNumberSisa; $i++){
$td .="<td></td>";
}
} else {
$tdNumberSisa=6-$tdNumberAda;
  for($i=0; $i < $tdNumberSisa; $i++){
    $td .="<td style='background-color:d1d1d1'></td>";
  }
}

$td .="<td class='qty_main_total' style='font-weight: bold; text-align: center; vertical-align: middle; font-size: 12pt; background-color: powderblue;' colspan='2' rowspan='2'>". $_sum_qty ."</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="<td>&nbsp;</td>";
$td .="</tr>";

$td .="<tr>";
$td .="<td style='font-size: 11pt; font-weight: bold;' colspan='2'>QTY 100 total</td>";

$tdNumberAda=0;
if($sp2->num_rows!==0){
  foreach($stmt2 as $key=>$value) {
  $tdNumberAda++;
  $td .="<td style='font-size: 11pt; font-weight: bold; text-align: center;'>".$value."</td>";
  } 
  $tdNumberSisa=6-$tdNumberAda;
    for($i=0; $i < $tdNumberSisa; $i++){
      $td .="<td></td>";
    }
} else {
  $tdNumberSisa=6-$tdNumberAda;
    for($i=0; $i < $tdNumberSisa; $i++){
      $td .="<td style='background-color: d1d1d1'></td>";
    }
}

$td .="<td class='style_val' style='font-weight: bold; font-size: 12pt; text-align: left; vertical-align: middle; white-space: nowrap; background-color: dodgerblue;' colspan='8'>". $styleno ."</td>";
$td .="</tr>";

$td .="<tr style='font-size: 11pt;'>";
$td .="<td style='font-weight: bold;' colspan='2'>COLOUR</td>";
$td .="<td colspan='6'>&nbsp;</td>";
$td .="<td style='font-weight: bold;'>BUYER</td>";
$td .="<td colspan='2'>". $buyer ."</td>";
$td .="<td style='text-align: center; font-weight: bold;' colspan='3'>PRODUCTION CAD</td>";
$td .="<td style='text-align: center; font-weight: bold;' colspan='3'>CHIEF</td>";
$td .="<td style='text-align: center; font-weight: bold;'>MANAGER</td>";
$td .="</tr>";

$td .="<tr style='font-size: 11pt;'>";
$td .="<td style='font-weight: bold;'>STYLE</td>";
$td .="<td>". $styleno ."</td>";
$td .="<td style='font-weight: bold;'>#PO</td>";
$td .="<td class='val_po_no'>". $pono ."</td>";
$td .="<td colspan='4'>&nbsp;</td>";
$td .="<td style='font-weight: bold;'>GSM</td>";
$td .="<td colspan='2'>". $gsm ."</td>";
$td .="<td colspan='3' rowspan='2'>&nbsp;</td>";
$td .="<td colspan='3' rowspan='2'>&nbsp;</td>";
$td .="<td rowspan='2'>&nbsp;</td>";
$td .="</tr>";

$td .="<tr style='font-size: 11pt;'>";
$td .="<td style='font-weight: bold;'>FABRIC</td>";
$td .="<td style='font-weight: bold;' colspan='2'>". $itemdesc ."</td>";
$td .="<td style='text-align: center; font-weight: bold;'>SHELL</td>";
$td .="<td style='text-align: center; font-weight: bold;' colspan='4'>". $panel ."</td>";
$td .="<td style='text-align: center; font-weight: bold;'>WIDTH</td>";
$td .="<td class='val_width_main' colspan='2'>". $width ."</td>";
$td .="</tr>";

$td .="<tr style='font-size: 11pt;'>";
$td .="<td style='font-weight: bold;' colspan='2'>SIZE</td>";

$tdNumberAda=0;
if($sp2->num_rows!==0){
  foreach($stmt2 as $key=>$value) {
    $tdNumberAda++;
    $td .="<td style='color: blue; font-size: 11pt; font-weight: bold; text-align: center; border-bottom-style: double; border-width: 3px;'>".trim($key,'size_')."</td>";
  } 
  $tdNumberSisa=6-$tdNumberAda;
    for($i=0; $i < $tdNumberSisa; $i++){
      $td .="<td></td>";
    }
} else {
  $tdNumberSisa=6-$tdNumberAda;
    for($i=0; $i < $tdNumberSisa; $i++){
      $td .="<td style='background-color: d1d1d1'></td>";
    }
}

$td .="<td style='color: blue; font-weight: bold; text-align: center; vertical-align: middle;'>TOTAL</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' colspan='3'>LENGTH (MARKER)</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' rowspan='2'>LENGTH YARD</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' rowspan='2'>SPERD</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' rowspan='2'>HASIL</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' rowspan='2'>YDS</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' rowspan='2'>CON'S/YARD</td>";
$td .="<td style='font-weight: bold; text-align: center; vertical-align: middle;' rowspan='2'>CON'S/KG</td>";
$td .="</tr>";

$td .="<td style='font-weight: bold;' colspan='2'>QTY</td>";

#<!-- ----------------- PERULANGAN TR DETAIL DARISINI ----------- -->

$tdNumberAda=0;
  if($sp2->num_rows!==0){    

    foreach($stmt2 as $key=>$value) {
    $tdNumberAda++;

    $td .="<td style='font-size: 11pt; font-weight: bold; text-align: center;'>".$value."</td>";

    } 
  $tdNumberSisa=6-$tdNumberAda;
    for($i=0; $i < $tdNumberSisa; $i++){
      $td .="<td></td>";
    }
  } else {
  $tdNumberSisa=6-$tdNumberAda;
    for($i=0; $i < $tdNumberSisa; $i++){
      $td .="<td></td>";
    }
  }

$td .="<td style='color: blue; text-align: center; font-weight: bold; vertical-align: center;'>". $_sum_qty ."</td>";
$td .="<td style='font-weight: bold;' >YDS</td>";
$td .="<td colspan='2' style='text-align: center; vertical-align: center; font-weight: bold;'>INCH</td>";
$td .="</tr>";

$tdNumberAda=0; 

// -------------------------------------------------------------------- //
// sp = All , sp2 = Size , sp3 = Ratio , sp4 = Spread , sp5 = Kurang
// -------------------------------------------------------------------- //

$i = 0;
$j1 = 0;
$sumr = 0;
$sums = 0;
$sumb = 0;


while ($row = mysqli_fetch_assoc($sp1)) { 

$tdNumberAda++;  
  //print_r($row); die();
$td .="<tr>";
$td .="<td style='font-weight:bold' colspan='2'>RATIO ". $tdNumberAda ."</td>";
    for ($j=0; $j < count($rArr[$i]) ; $j++) { 
      $td .="<td style='background-color: yellow; text-align:center;'>" . $rArr[$i][$j] . "</td>"; //rasio
    }

    for ($m=0; $m < 6-count($rArr[$i]); $m++) {  
      $td .="<td></td>";//isi border cell yg kosong
    }
    for ($j=0; $j < count($rArr[$i]); $j++) { 
      $sumr = array_sum($rArr[$i]);
    }
$td .="<td style='font-weight: bold; text-align: center;'>". $sumr ."</td>"; //total row rasio
$td .="<td rowspan='3' style='font-weight: bold; text-align:center; vertical-align: middle;'>". $unit_yds ."</td>";
$td .="<td rowspan='3' colspan='2' style='font-weight: bold; text-align:center; vertical-align: middle;'>". $unit_inch ."</td>";

// rumus length yard from excel =SUM(Y14+2)/36+X14 <-- Y14=unit_yds , X14=unit_inch

$td .="<td rowspan='3' style='font-weight: bold; text-align:center; vertical-align: middle;'>". number_format((($unit_yds+2)/36+$unit_inch),4, '.', ',') ."</td>"; //length yard value
$td .="<td rowspan='3' style='font-weight: bold; text-align:center; vertical-align: middle;'>". $sArr[0][$i] ."</td>"; //sperd value

    for ($j=0; $j < count($sArr[$i]); $j++) { 
      $sums = array_sum($sArr[$i]);
    }
$td .="<td rowspan='3' style='font-weight: bold; text-align: center; vertical-align: middle;'>". $sums ."</td>";     //total row spread

$td .="<td rowspan='3' style='font-weight: bold; text-align:center; vertical-align: middle;'>". number_format(((($unit_yds+2)/36+$unit_inch)*$sArr[0][$i]),4, '.', ',') ."</td>"; //length yard * sperd value

 $td .="<td rowspan='3' style='font-weight: bold; text-align:center; vertical-align: middle;'>". number_format(($sums/((($unit_yds+2)/36+$unit_inch)*$sums)),4, '.', ',') ."</td>"; //sperd value / (length yard * sperd value)

 // ------------------------------------ RUMUS CON'S / KG --------------------------------------- //
 // dari Excel = SUM(AD14*0.9144)*(V11*0.0254)*(V10/AC14)/1000
 // AD14 = $sums (TOTAL ROW SPREAD)
 // V11 = $width (WIDTH)
 // V10 = $gsm (GSM)
 // AC14 = ((($unit_yds+2)/36+$unit_inch)*$sums) (LENGTH YARD * SPERD VALUE)
 // -------------------------------------------------------------------------------------- //

 $td .="<td rowspan='3' style='font-weight: bold; text-align:center; vertical-align: middle;'>". number_format((($sums*0.9144)*($width*0.0254)*($gsm/((($unit_yds+2)/36+$unit_inch)*$sums))/1000),4, '.', ',') ."</td>"; // Con's/KG value


 $td .="</tr>";    
 $td .="<tr>";
 $td .="<td style='font-weight:bold;'>SPERD</td><td style='text-align:center;'>" . $sArr[0][$i] . "</td>";

 for ($k=0; $k < count($sArr[$i]) ; $k++) { 
$td .="<td style='text-align:center;'>" . $sArr[$i][$k] . "</td>"; //spread

}         
for ($m=0; $m < 6-count($sArr[$i]); $m++) {     
      $td .="<td></td>";//isi border cell yg kosong
    }

    for ($j=0; $j < count($sArr[$i]); $j++) { 
      $sums = array_sum($sArr[$i]);
    }
$td .="<td style='font-weight: bold; text-align: center;'>". $sums ."</td>";     //total row spread

$td .="</tr>";
$td .="<tr>";
$td .="<td style='font-weight:bold;' colspan='2'>BALANCE</td>";

for ($l=0; $l < count($kArr[$i]) ; $l++) { 
$td .="<td style='text-align:center;'>" . $kArr[$i][$l] . "</td>"; //kurang
}

for ($m=0; $m < 6-count($kArr[$i]); $m++) {     
$td .="<td></td>";//isi border cell yg kosong
}

for ($j=0; $j < count($kArr[$i]); $j++) { 
$sumb = array_sum($kArr[$i]);
}
$td .="<td style='font-weight: bold; text-align: center;' >". $sumb ."</td>"; //total row kurang      

$td .="</tr>"; 


$i++; $j1++;
}
 // echo '<pre>',print_r($sums,1),'</pre>'; die();
 // echo '<pre>',print_r($sumTLengthYard,1),'</pre>'; die();

// $tdNumberSisa=31-$tdNumberAda;
// for($i=0; $i < $tdNumberSisa; $i++){
//   $td .="<td></td>";
// }
$td .= "<tr style='font-size: 11pt; font-weight: bold; text-align: center;'>";
$td .= "<td colspan='2' style='background-color: dodgerblue;' >TOTAL</td>";

$tArr = array();
$i = 0;
$totals = 0;
$tdNumberAda = 0;

$tArr = $sArr;
if($sp2->num_rows!==0){
foreach ($stmt2 as $key => $value) {
  $tdNumberAda++;

  for ($m=0; $m < count($tArr); $m++) { 
    $totals = array_sum(array_column($tArr, $i));
  }
  // echo '<pre>',print_r($totals,1),'</pre>'; 
  $td .="<td style='background-color: dodgerblue;'>". $totals ."</td>";

  $i++;
  

}
  $tdNumberSisa=6-$tdNumberAda;
  for($i=0; $i < $tdNumberSisa; $i++){
    $td .="<td style='background-color: dodgerblue;'></td>";
  } 
} else {
$tdNumberSisa=6-$tdNumberAda;
for($i=0; $i < $tdNumberSisa; $i++){
  $td .="<td style='background-color: dodgerblue;'></td>";
}
}

$sumTLengthYard = 0;      
$sumT = 0;
$sumTH = 0;
$sumTS = 0;
$sumTY = 0;
$sumTC = 0;
$sumTK = 0;
$sumZ = 0;
$sumX = 0;
$sumY = 0;
$i=0;
$tArr = $sArr;

foreach ($sArr as $key => $value) {

  $sumT = $sumT + array_sum($value);
  
  $i++;
}
$td .="<td>". $sumT ."</td>";
$td .="<td rowspan='2' style='background-color: dodgerblue;'></td>";
$td .="<td rowspan='2' colspan='2' style='background-color: dodgerblue;'></td>";
  // echo '<pre>',print_r($sumT,1),'</pre>'; 
for ($x=0; $x < $count_group_det ; $x++) { 
  $sumTLengthYard = $sumTLengthYard + (($unit_yds+2)/36+$unit_inch);
}
 // echo '<pre>',print_r($sumTLengthYard,1),'</pre>'; 


$td .="<td rowspan='2' style='vertical-align: middle; background-color: dodgerblue;'>". number_format($sumTLengthYard,4, '.', ',') ."</td>"; //TOTAL LENGTH YARD

for ($n=0; $n < $count_group_det; $n++) { 
  $sumTS = $sumTS + $sArr[0][$n];
}

  // echo '<pre>',print_r($sumTS,1),'</pre>'; 
$td .="<td rowspan='2' style='vertical-align: middle; background-color: dodgerblue'>". $sumTS ."</td>"; //TOTAL SPERD

foreach ($sArr as $key => $value) {
  $sumTH = $sumTH + array_sum($value);

}
$td .="<td rowspan='2' style='vertical-align: middle; background-color: dodgerblue'>". $sumTH ."</td>"; //TOTAL HASIL

for ($m=0; $m < $count_group_det; $m++) { 
  $sumTY = $sumTY + ((($unit_yds+2)/36+$unit_inch)*$sArr[0][$m]);
}
$td .="<td rowspan='2' style='vertical-align: middle; background-color:dodgerblue;'>". number_format($sumTY,4, '.', ',') ."</td>"; //TOTAL YDS
//-----------------------------------------------------------------------------------------------------
if ($sumTC >= 0) {
  $sumTC = ($sumTY/$sumTH); //TOTAL YDS / TOTAL HASIL
} else {
  $sumTC = 0;
}

if ($sumZ > 0) {
  $sumZ = (($sumTY/$sumTH)*1.030); //TOTAL YDS / TOTAL HASIL x CONS 3%
} else {
  $sumZ = 0;
}

if ($sumY > 0) {
    $sumY = ($sumTK*1.030); //TOTAL CONS / KG x CONS 3%
} else {
    $sumY = 0;
}

if ($sumX > 0) {
    $sumX = ($sumY*$_sum_qty);  
  } else {
    $sumX = 0;
  }  
  // echo '<pre>',print_r(($sumTY/$sumTH),1),'</pre>'; 
  
  
//-----------------------------------------------------------------------------------------------------
$td .="<td rowspan='2' style='vertical-align: middle; background-color: dodgerblue;'>". number_format($sumTC,4, '.', ',') ."</td>"; //TOTAL CONS / YRD


for ($m=0; $m < $count_group_det; $m++) { 
  $sumTK = $sumTK + (($sums*0.9144)*($width*0.0254)*($gsm/((($unit_yds+2)/36+$unit_inch)*$sums))/1000);
}
$td .="<td rowspan='2' style='vertical-align: middle; background-color: dodgerblue;'>". number_format($sumTK,4, '.', ',') ."</td>"; //TOTAL CONS / KG

$td .="</tr>";

$td .="<tr style='font-size: 11pt; font-weight: bold; text-align: center;'>";
$td .="<td colspan='2' style='background-color: dodgerblue;'>BALANCE</td>";

// $totals=0;
$Arr = array();
$tq = 0;
$i = 0;
$tk = 0;
$tdNumberAda=0;
if($sp2->num_rows!==0){
foreach ($stmt2 as $key => $value) {
$tdNumberAda++;
  for ($m=0; $m < count($tArr); $m++) { 
    $totals = array_sum(array_column($tArr, $i));
  }
  $tq = $totals - $value;
  
  $Arr[] = $tq;

  // echo '<pre>',print_r($tq,1),'</pre>'; 
  // echo '<pre>',print_r($totals,1),'</pre>'; 


  $td .="<td style='background-color: dodgerblue;'>". $Arr[$i] ."</td>"; //KURANG TOTAL (TOTAL-QTY)

  $i++;

}   
$tdNumberSisa=6-$tdNumberAda;
for($i=0; $i < $tdNumberSisa; $i++){
  $td .="<td style='background-color: dodgerblue;'></td>";
}
} else {
$tdNumberSisa=6-$tdNumberAda;
for($i=0; $i < $tdNumberSisa; $i++){
  $td .="<td style='background-color: dodgerblue;'></td>";
}
}

if($sp2->num_rows!==0){
for ($x=0; $x < count($stmt2); $x++) { 
  $tk = $tk + $Arr[$x];
  // echo '<pre>',print_r(count($stmt2),1),'</pre>'; 
}

$td .="<td>". $tk ."</td>";
}

$td .="</tr>";

$td .="<tr style='font-size: 11pt;'>";
$td .="<td style='text-align: center; font-weight: bold; vertical-align: center;' colspan='3'>CAD CONS BODY</td>";
$td .="<td style='text-align: center; font-weight: bold; vertical-align: center;' colspan='3'>B-CONS/KG</td>";
$td .="<td style='text-align: center; font-weight: bold; vertical-align: center;' colspan='3'>WIDTH</td>";
$td .="<td style='text-align: center; font-weight: bold; vertical-align: center;' colspan='2'>BALANCE</td>";
$td .="<td style='text-align: center; font-weight: bold; vertical-align: center;' colspan='2'>PERCENTAGE</td>";
$td .="<td colspan='2'></td>";
$td .="<td>CONS 3%</td>";


$td .="<td style='text-align: center; vertical-align: middle; background-color: powderblue;' rowspan='2'>".  number_format($sumZ,4, '.', ',') ."</td>"; //CONS YARD
$td .="<td style='text-align: center; vertical-align: middle; background-color: dodgerblue;' rowspan='2'>". number_format($sumY,4, '.', ',') ."</td>"; //CONS KG
$td .="</tr>";
$td .="<tr style='font-size: 11pt;'>";
$td .="<td colspan='3' rowspan='3'>&nbsp;</td>";
$td .="<td colspan='3' rowspan='3'>&nbsp;</td>";
$td .="<td colspan='3' rowspan='3'>&nbsp;</td>";
$td .="<td colspan='2' rowspan='3'>&nbsp;</td>";
$td .="<td colspan='2' rowspan='3'>&nbsp;</td>";
$td .="<td colspan='2'>&nbsp;</td>";

$td .="<td style='font-weight: bold;'>&nbsp;1.030</td>";
$td .="</tr>";

$td .="<tr style='font-size: 11pt;'>";
$td .="<td colspan='2'></td>";

$td .="</tr>";
$td .="<tr style='font-size: 11pt;'>";
$td .="<td colspan='4' style='text-align: right;'>&nbsp;KEBUTUHAN KAIN / KG</td>";
$td .="<td  style='text-align: center; vertical-align: center; background-color: dodgerblue;'>". number_format($sumX,4, '.', ',') ."</td>";
$td .="</tr>";

$td .="</tbody>";
$td .="</table>";

$td .="<br>";
$td .="<br>";
$td .="<br>";
$td .="<br>";


echo $td;

}
?>

