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
    header("Content-Disposition: attachment; filename=List bank in.xls");
    // $nama_supp =$_GET['nama_supp'];
    // $status =$_GET['status'];
    $start_date = date("d F Y",strtotime($_GET['start_date']));
    $end_date = date("d F Y",strtotime($_GET['end_date'])); ?>

   <!--  <center> -->
        <h4>DATA BANK IN <br/> PERIODE <?php echo $start_date; ?> - <?php echo $end_date; ?></h4>
    <!-- </center> -->
  <!--   STATUS: <?php echo $status; ?> -->
 
    <table style="width:100%;font-size:12px;" border="1" >
        <tr>
            <th style="text-align: center;vertical-align: middle;">No</th>
            <th style="text-align: center;vertical-align: middle;">No Bank In</th>
            <th style="text-align: center;vertical-align: middle;">Source</th>
            <th style="text-align: center;vertical-align: middle;">Date</th>
            <th style="text-align: center;vertical-align: middle;">Curreny</th>
            <th style="text-align: center;vertical-align: middle;">Amount</th>
            <th style="text-align: center;vertical-align: middle;">Outstanding</th>
            <th style="text-align: center;vertical-align: middle;">Status</th>
            <th style="text-align: center;vertical-align: middle;">Approve By</th>
            <th style="text-align: center;vertical-align: middle;">Approve Date</th>
            <!-- <th style="text-align: center;vertical-align: middle;">curr</th>
            <th style="text-align: center;vertical-align: middle;">Debit</th>
            <th style="text-align: center;vertical-align: middle;">Credit</th>
            <th style="text-align: center;vertical-align: middle;">Status</th>
            <th style="text-align: center;vertical-align: middle;">Remark</th>
            <th style="text-align: center;vertical-align: middle;">Create By</th>
            <th style="text-align: center;vertical-align: middle;">Create Date</th> -->
        </tr>
        <?php 
        // koneksi database
        include '../../conn/conn.php';
        // $nama_supp=$_GET['nama_supp'];
        $where =$_GET['where'];
        $start_date = date("Y-m-d",strtotime($_GET['start_date']));
        $end_date = date("Y-m-d",strtotime($_GET['end_date']));
        // menampilkan data pegawai
  

        $sql = mysqli_query($conn2,"select * from (select doc_num, customer, date, ref_data, akun, bank, curr, amount, outstanding, status,deskripsi,approve_by,approve_date from sb_bankin_arcollection $where group by doc_num order by id asc) a
            UNION
            select * from (select doc_num, customer, date, ref_data, akun, bank, curr, amount, outstanding, status,deskripsi,approve_by,approve_date from tbl_bankin_arcollection $where group by doc_num order by id asc) a where doc_num not like '%BCA6249%' and doc_num not like '%BCA6311%' and doc_num not like '%BCA2727%'");

        $no = 1;

        while($row = mysqli_fetch_array($sql)){
            $approve_date = isset($row['approve_date']) ? $row['approve_date'] : null;

            if ($approve_date == null) {
                $app_date = '-'; 
                $app_by = '-'; 
            }else{
                $app_date = date("d-M-Y",strtotime($approve_date));
                $app_by = $row['approve_by']; 
            }

        echo '<tr style="font-size:12px;text-align:center;">
            <td >'.$no++.'</td>
            <td style=" text-align : left" value="'.$row['doc_num'].'">'.$row['doc_num'].'</td>
            <td style=" text-align : left" value="'.$row['customer'].'">'.$row['customer'].'</td>
            <td style=" text-align : left" value="'.$row['date'].'">'.date("d-M-Y",strtotime($row['date'])).'</td>                                                                                             
            <td style=" text-align : left" value="'.$row['curr'].'">'.$row['curr'].'</td>
            <td style=" text-align : right" value="'.$row['amount'].'">'.number_format($row['amount'],2).'</td>
            <td style=" text-align : right" value="'.$row['outstanding'].'">'.number_format($row['outstanding'],2).'</td>
            <td style=" text-align : left" value="'.$row['status'].'">'.$row['status'].'</td>
            <td style=" text-align : left" value="'.$app_by.'">'.$app_by.'</td>
            <td style=" text-align : left" value="'.$app_date.'">'.$app_date.'</td>
             ';
         
        ?>
        <?php 
        
    }
        ?>
    </table>

</body>
</html>




