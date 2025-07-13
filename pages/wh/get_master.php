<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
    header("location:../../index.php");
}
$user = $_SESSION['username'];
$master_name = $_POST['master_name'];

$query2 = mysql_query("select id,kode_rak,nama_rak,kapasitas,unit, IF(cancel != 'N','Non Aktif','Aktif') status,user_input, tgl_input from m_rak");

$table = '<thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Rak</th>
                        <th>Nama Rak</th>
                        <th>Kapasitas</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>';

$table .= '<tbody>';
$no = 1;
while ($data2 = mysql_fetch_array($query2)) {
    $kode_rak = $data2[kode_rak];
    $tgl_input = $data2[tgl_input];

    if ($tgl_input != '') {
        $tgl_input = fd_view($data2[tgl_input]);
    } else {
        $tgl_input = '-';
    }
    $table .= '<tr>
                    <td >' . $no . '</td>
                    <td >'.$data2[kode_rak].'</td>
                    <td >'.$data2[nama_rak].'</td>
                    <td >'.$data2[kapasitas].'</td>
                    <td >'.$data2[unit]. '</td>
                    <td >' . $data2[status] . '</td>
                    <td >' . $data2[user_input] . '</td>
                    <td >' . $tgl_input . '</td>
            </tr>';
    $table .= '</tbody>';

    $no++;
}


$query3 = mysql_query("select kode_unit,nama_unit,IF(cancel != 'N','Non Aktif','Aktif') status,user_input, tgl_input from m_unit");

$table2 = '<thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Tanggal Input</th>
                    </tr>
                </thead>';

$table2 .= '<tbody>';
$no2 = 1;
while ($data3 = mysql_fetch_array($query3)) {
    $kode_unit = $data3[kode_unit];
    $dateinput = $data3[tgl_input];

    if ($dateinput != '') {
        $dateinput = fd_view($data3[tgl_input]);
    } else {
        $dateinput = '-';
    }
    $table2 .= '<tr>
                    <td >' . $no2 . '</td>
                    <td >' . $data3[kode_unit] . '</td>
                    <td >' . $data3[nama_unit] . '</td>
                    <td >' . $data3[status] . '</td>
                    <td >' . $data3[user_input] . '</td>
                    <td >' . $dateinput . '</td>
            </tr>';
    $table2 .= '</tbody>';

    $no2++;
}

if($master_name == 'RAK'){
 echo $table; 
} elseif ($master_name == 'UNIT') {
    echo $table4;
} else{
    echo '';  
}



// echo '<div id="txt_sub" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Subtotal: '.number_format($sub,2).'</h7></div>';
// echo '<div id="txt_tax" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h7>Tax: '.number_format($tax,2).'</h7></div>';
// echo '<div id="txt_total" class="modal-body col-6" style="padding: 0.5rem; margin-left: 65%;"><h6>Total: '.number_format($total,2).'</h6></div>';
?>