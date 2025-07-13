<html>
<head>
    <title>Export Data List Journal </title>
</head>
<body>
    <style type="text/css">
    body{
        font-family: sans-serif;
    }
    table{
        margin: 20px auto;
        border-collapse: collapse;
    }
    table th,
    table td{
        border: 1px solid #3c3c3c;
        padding: 3px 8px;
 
    }
    a{
        background: blue;
        color: #fff;
        padding: 8px 10px;
        text-decoration: none;
        border-radius: 2px;
    }
    </style>
 
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=master-chart-of-account.xls");
    $nama_ctg2 =strtolower($_GET['nama_ctg2']);
    $nama_ctg5 =strtolower($_GET['nama_ctg5']);
    $Status =strtolower($_GET['Status']);
     ?>
 
    <table style="width:100%;font-size:10px;" border="1" >
        <tr>
        <td colspan="5" style="font-size: 18px;">PT NIRWANA ALABARE GARMENT</td>
    </tr>
    <tr>
        <td colspan="5" style="font-size: 18px;">LAPORAN ABSENSI KARYAWAN</td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="5" style="font-size: 14px;">STAFF / NON STAFF </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td rowspan="2">TANGGAL</td>
        <td rowspan="2">HARI</td>
        <td rowspan="2">NIK</td>
        <td rowspan="2">NO. ABSEN</td>
        <td rowspan="2">NAMA KARYAWAN</td>
        <td rowspan="2">STAFF/NON STAFF</td>
        <td rowspan="2">DEPARTMENT</td>
        <td rowspan="2">KERJA/LIBUR</td>
        <td colspan="4">JADWAL KERJA</td>
        <td colspan="4">ABSEN</td>
        <td colspan="4">IJIN KELUAR SEMENTARA</td>
        <td colspan="4">POTONGAN MENIT</td>
        <td rowspan="2">STATUS ABSEN</td>
        <td rowspan="2">ALASAN ABSEN</td>
    </tr>
    <tr>
        <td>IN</td>
        <td>OUT</td>
        <td>DURASI ISTIRAHAT</td>
        <td>DURASI KERJA</td>
        <td>IN</td>
        <td>OUT</td>
        <td>EFEKTIF KERJA</td>
        <td>DARI</td>
        <td>SAMPAI</td>
        <td>TOTAL</td>
        <td>DT</td>
        <td>PC</td>
        <td>Total</td>
    </tr>
        <?php 
        // koneksi database
        include '../../conn/conn.php';
        // $nama_supp=$_GET['nama_supp'];
        // $where =$_GET['where'];
        $nama_ctg2 =$_GET['nama_ctg2'];
        $nama_ctg5 =$_GET['nama_ctg5'];
        $Status =$_GET['Status'];
        $where =$_GET['where'];
        // menampilkan data pegawai
  
        $sql = mysqli_query($conn3,"SELECT
    master_data_absen_kehadiran.tanggal_berjalan,
    master_data_absen_kehadiran.kode_hari,
    master_data_absen_kehadiran.nama_hari,
    employee_atribut.nik,
    employee_atribut.enroll_id,
    employee_atribut.employee_name,
    employee_atribut.status_staff,
    employee_atribut.department_name,
    master_data_absen_kehadiran.mulai_jam_kerja,
    master_data_absen_kehadiran.akhir_jam_kerja,
    master_data_absen_kehadiran.absen_masuk_kerja,
    master_data_absen_kehadiran.absen_pulang_kerja,
    master_data_absen_kehadiran.jumlah_absen_menit_kerja,
    master_data_absen_kehadiran.permits_dari_pukul,
    master_data_absen_kehadiran.permits_sampai_pukul,
    master_data_absen_kehadiran.total_menit_permits,
    master_data_absen_kehadiran.jumlah_menit_absen_dt,
    master_data_absen_kehadiran.jumlah_menit_absen_pc,
    master_data_absen_kehadiran.jumlah_menit_absen_dtpc,
    master_data_absen_kehadiran.status_absen,
    master_data_absen_kehadiran.absen_alasan,
    master_data_absen_kehadiran.catatan_hrd,
    master_data_absen_kehadiran.mulai_jam_lembur,
    master_data_absen_kehadiran.akhir_jam_lembur,
    substr( master_data_absen_kehadiran.jumlah_jam_lembur_approved, 1, 5 ) jumlah_jam_lembur_approved,
    substr( master_data_absen_kehadiran.jumlah_jam_istirahat_lembur, 1, 5 ) jumlah_jam_istirahat_lembur,
    rekap_perhitungan_lembur.nomor_form_lembur,
    rekap_perhitungan_lembur.final_mulai_jam_lembur,
    rekap_perhitungan_lembur.final_selesai_jam_lembur,
    rekap_perhitungan_lembur.final_total_jam_lembur,
    rekap_perhitungan_lembur.final_jam_istirahat_lembur,
    rekap_perhitungan_lembur.final_total_menit_lembur,
    rekap_perhitungan_lembur.final_jam_lembur_roundown,
    rekap_perhitungan_lembur.final_menit_lembur_roundown,
    rekap_perhitungan_lembur.lembur_1,
    rekap_perhitungan_lembur.lembur_2,
    rekap_perhitungan_lembur.lembur_3,
    rekap_perhitungan_lembur.lembur_4,
    rekap_perhitungan_lembur.total_lembur_1234 
FROM
    master_data_absen_kehadiran
    LEFT JOIN employee_atribut ON master_data_absen_kehadiran.enroll_id = employee_atribut.enroll_id
    LEFT JOIN department_all ON employee_atribut.sub_dept_id = department_all.sub_dept_id
    LEFT JOIN rekap_perhitungan_lembur ON master_data_absen_kehadiran.tanggal_berjalan = rekap_perhitungan_lembur.tanggal_berjalan 
    AND master_data_absen_kehadiran.enroll_id = rekap_perhitungan_lembur.enroll_id 
WHERE
    substr( master_data_absen_kehadiran.tanggal_berjalan, 1, 10 ) BETWEEN '2024-01-29' 
    AND '2024-02-27' 
ORDER BY
    employee_atribut.employee_name ASC,
    master_data_absen_kehadiran.tanggal_berjalan ASC");

        $no = 1;
        $interval = "";
        $minutes = "";
        $jumlah_menit_kerja ="";
        $kerjalibur = '';
        $jumlah_menit_istirahat = '';

        while($row = mysqli_fetch_array($sql)){
            // $idndirdebit = isset($row['idndirdebit']) ? $row['idndirdebit'] : "NA";
            // $engdirdebit = isset($row['engdirdebit']) ? $row['engdirdebit'] : "NA";
            // $idndircredit = isset($row['idndircredit']) ? $row['idndircredit'] : "NA";
            // $engdircredit = isset($row['engdircredit']) ? $row['engdircredit'] : "NA";
            // $idnindir = isset($row['idnindir']) ? $row['idnindir'] : "NA";
            // $engindir = isset($row['engindir']) ? $row['engindir'] : "NA";

            $interval = date_diff(date_create(substr($row['mulai_jam_kerja'], 0, 5)), date_create(substr($row['akhir_jam_kerja'], 0, 5)));
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
            $jumlah_menit_kerja = $minutes;
            // if ($row['jumlah_menit_istirahat'] == "") {
                $jumlah_menit_istirahat = 60;
            // } else {
            //     $jumlah_menit_istirahat = $row['jumlah_menit_istirahat'];
            // }
            $kerjalibur = "KERJA";
            if(($row['mulai_jam_kerja'] == null) || ($row['status_absen'] == "LN" || $row['status_absen'] == "CG" || $row['status_absen'] == "CM" || $row['status_absen'] == "CT" ||$row['status_absen'] == "L") || (($row['status_absen'] == "LP" ) && ($row['absen_masuk_kerja']==null) && ($row['absen_pulang_kerja']==null))) {
                $kerjalibur = "LIBUR";
            } else if(($row['kode_hari']=='6' || $row['kode_hari']=='5' ) && ($row['mulai_jam_kerja']!=null)){
                $kerjalibur = "KERJA";
            } else {
                if(($row['absen_masuk_kerja'] <> null) || ($row['absen_masuk_kerja'] <> "") || ($row['absen_pulang_kerja'] <> null) || ($row['absen_pulang_kerja'] <> "")) {
                    $kerjalibur = "KERJA";
                    switch ($row['kode_hari']) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = 30;
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = 30;
                            break;
                    }
                }
            }

        echo "<tr>
            <td>".$row['tanggal_berjalan']." </td>
            <td>".$row['nama_hari']." </td>
            <td>".$row['nik']." </td>
            <td>".$row['enroll_id']." </td>
            <td>".$row['employee_name']." </td>
            <td>".$row['status_staff']." </td>
            <td>".$row['department_name']." </td>
            <td>".$kerjalibur." </td>
            <td>".substr($row['mulai_jam_kerja'], 0, 5)." </td>
            <td>".substr($row['akhir_jam_kerja'], 0, 5)." </td>
            <td>".$jumlah_menit_istirahat." </td>
            <td>".$jumlah_menit_kerja." </td>
            <td>".substr($row['absen_masuk_kerja'], 0, 5)." </td>
            <td>".substr($row['absen_pulang_kerja'], 0, 5)." </td>
            <td>".$row['jumlah_absen_menit_kerja']." </td>
            <td>".substr($row['permits_dari_pukul'], 0, 5)." </td>
            <td>".substr($row['permits_sampai_pukul'], 0, 5)." </td>
            <td>".$row['total_menit_permits']." </td>
            <td>".$row['jumlah_menit_absen_dt']." </td>
            <td>".$row['jumlah_menit_absen_pc']." </td>
            <td>".$row['jumlah_menit_absen_dtpc']." </td>
            <td>".$row['status_absen']." </td>
            <td>".$row['absen_alasan']." </td>
            <td>".$row['catatan_hrd']." </td>
            <td>".""." </td>
            <td>".$row['nomor_form_lembur']." </td>
            <td>".$row['final_mulai_jam_lembur']." </td>
            <td>".$row['final_selesai_jam_lembur']." </td>
            <td>".$row['final_total_jam_lembur']." </td>
            <td>".$row['final_jam_istirahat_lembur']." </td>
            <td>".str_pad($row['final_jam_lembur_roundown'],2,"0",STR_PAD_LEFT).":".str_pad($row['final_menit_lembur_roundown'],2,"0",STR_PAD_LEFT)." </td>
            <td>".$row['lembur_1']." </td>
            <td>".$row['lembur_2']." </td>
            <td>".$row['lembur_3']." </td>
            <td>".$row['lembur_4']." </td>
            <td>".$row['total_lembur_1234']." </td>
            <td>".$row['mulai_jam_lembur']." </td>
            <td>".$row['akhir_jam_lembur']." </td>
            <td>".$row['jumlah_jam_lembur_approved']." </td>
            <td>".$row['jumlah_jam_istirahat_lembur']." </td>
        </tr>
             ";
         
        ?>
        <?php 
        
    }
        ?>
    </table>

</body>
</html>




