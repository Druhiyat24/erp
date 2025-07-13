<?php
    //include 'koneksi2.php';
    include '../../../include/conn.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form</title>
    <style >
        font{
             font-style:italic;
            }
        td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }
        th {
        border: 1px solid #dddddd;
        text-align: center;
        padding: 8px;
        }

        tr:nth-child(even) {
        background-color: #dddddd;
        }
    </style>

</head>
<body>
    <table style="font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 90%;
                margin-left : 5%;
                margin-right: 5%;">
        <tr>
            <td colspan="4" style="width:100%">
                <h1 style="color:#607699; text-align:center;">Silahkan Pilih Form</h1>
            </td>
        </tr>
        <tr>
            <td style="width:25%; text-align:center;"><form action="form_apply_karyawan.php"><input type="submit" value="Form Apply Karyawan" style="width:90%; background-color:#3588c4;
            font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;"></form>
            </td>
            <td style="width:25%; text-align:center;"><form action="form_document.php"><input type="submit" value="Form Dokumen" style="width:90%; background-color:#3588c4;
            font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;"></form>
            </td>
            <td style="width:25%; text-align:center;"><form action="form_interview.php"><input type="submit" value="Form Interview" style="width:90%; background-color:#3588c4;
            font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;"></form>
            </td>
            <td style="width:25%; text-align:center;"><form action="form_permintaantk.php"><input type="submit" value="Form Permintaan TK" style="width:90%; background-color:#3588c4;
            font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;"></form>
            </td>
    </tr>
    <tr>
            <td colspan="4" style="width:100%">
                <h3 style="color:#607699; text-align:center;">klik untuk melihat atau menyembunyikan data</h3>
            </td>
        </tr>
    <tr>
            <td style="width:25%; text-align:center;">
            <button id="btnshow" style="text-align:center; width:80%; textSize:15px;" onclick="hideShowTable1()">show/hide data form karyawan</button>
            </td>
            <td style="width:25%; text-align:center;">
            <button id="btnshow" style="text-align:center; width:80%; textSize:15px;" onclick="hideShowTable2()">show/hide data form dokumen</button>
            </td>
            <td style="width:25%; text-align:center;">
            <button id="btnshow" style="text-align:center; width:80%; textSize:15px;" onclick="hideShowTable3()">show/hide data form interview</button>
            </td>
            <td style="width:25%; text-align:center;">
            <button id="btnshow" style="text-align:center; width:80%; textSize:15px;" onclick="hideShowTable4()">show/hide data form permintaan tk</button>
            </td>
    </tr>
    </table>

    <table style="font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%; margin-top:15px; display:none;" id="formdokumen">
    <td colspan="21" style="width:100%">
        <h2 style="color:#607699; text-align:center;">Data Form Dokumen</h2>
    </td>        
    <?php
    echo '<tr>
            
            </tr>';
            while($row = $result2->fetch_assoc()) {
            echo'<tr>
            
            </tr>';
            }
        }else{
            echo '';
        }
    ?>
    </table>

    <table style="font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%; margin-top:15px; display:none; " id="forminterview">
        <td colspan="15" style="width:100%">
            <h2 style="color:#607699; text-align:center;">Data Form Interview</h2>
        </td>
        <?php
        if ($result3->num_rows > 0) {
        echo '<tr>
        
        </tr>';
        while($row = $result3->fetch_assoc()) {
            echo'<tr>
            
            </tr>';
        }}else{
            echo '';
        }
        ?>
        </table>
        <table style="font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%; margin-top:15px; display:none; " id="formpermintaantk" center>
        <td colspan="23" style="width:100%">
            <h2 style="color:#607699; text-align:center;">Data Form Permintaan Tenaga Kerja</h2>
        </td>
        <?php
        $sql4 = "SELECT * FROM form_tenaga_kerja";
        $result4 = $con_new->query($sql4);
        if ($result4->num_rows > 0) {
        echo '<tr>
        <th style="width:15%;">Status Permintaan</th>
        <th style="width:15%;">Tanggal Pengajuan</th>
        <th style="width:15%;">Nama Pengaju</th>
        <th style="width:15%;">NIK pengaju</th>
        <th style="width:15%;">Department Pengaju</th>
        <th style="width:15%;">Bagian Pengaju</th>
        <th style="width:15%;">kebutuhan department</th>
        <th style="width:15%;">kebutuhan bagian</th>
        <th style="width:15%;">tanggal</th>
        <th style="width:15%;">jumlah yg dibutuhkan</th>
        <th style="width:15%;">rencana jabatan</th>
        <th style="width:15%;">pendidikan minimal</th>
        <th style="width:15%;">jurusan</th>
        <th style="width:15%;">pengalaman kerja</th>
        <th style="width:15%;">uraian tugas</th>
        <th style="width:15%;">rencana gaji</th>
        <th style="width:15%;">rencana fasilitas</th>
        <th style="width:15%;">jangka waktu kontrak</th>
        <th style="width:15%;">keterangan lainnya</th>
        <th style="width:15%;">disetujui Chief.Dept.head/manager</th>
        <th style="width:15%;">diketahui HR-GA</th>
        <th style="width:15%;">disetujui GM Product</th>
        <th style="width:15%;">Disetujui GM Factory</th>
        </tr>';
        while($row = $result4->fetch_assoc()) {
            echo'<tr>
            <td style="width:15%;">'.$row["status_permintaan"].'</td>
            <td style="width:15%;">'.$row["do_tglaju"].'</td>
            <td style="width:15%;">'.$row["do_nama"].'</td>
            <td style="width:15%;">'.$row["do_nik"].'</td>
            <td style="width:15%;">'.$row["do_department"].'</td>
            <td style="width:15%;">'.$row["do_bagian"].'</td>
            <td style="width:15%;">'.$row["rk_department"].'</td>
            <td style="width:15%;">'.$row["rk_bagian"].'</td>
            <td style="width:15%;">'.$row["rk_tanggal"].'</td>
            <td style="width:15%;">'.$row["rk_jumlah"].'</td>
            <td style="width:15%;">'.$row["rencana_jabatan"].'</td>
            <td style="width:15%;">'.$row["pendidikanakhir"].'</td>
            <td style="width:15%;">'.$row["jurusan"].'</td>
            <td style="width:15%;">'.$row["pengalamankerja"].' '.$row["lamakerja_dlmthn"].'</td>
            <td style="width:15%;">'.$row["uraian_tugas"].'</td>
            <td style="width:15%;">'.$row["gaji"].'</td>
            <td style="width:15%;">'.$row["fasilitas"].'</td>
            <td style="width:15%;">'.$row["waktu_kontrak"].'</td>
            <td style="width:15%;">'.$row["keteranganlain"].'</td>
            <td style="width:15%;">'.$row["setuju1"].'</td>
            <td style="width:15%;">'.$row["setuju2"].'</td>
            <td style="width:15%;">'.$row["setuju3"].'</td>
            <td style="width:15%;">'.$row["ketahui"].'</td>
            </tr>';
        }
        }else{
            echo '';
        }
        ?>
        </table>
<?php include 'footer.php'?>