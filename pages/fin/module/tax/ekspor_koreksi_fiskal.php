<html>
<head>
    <title>Export Data List koreksi fiskal</title>
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
    header("Content-Disposition: attachment; filename=koreksi fiskal.xls");
    ?>

        <h4>LIST KOREKSI FISKAL <br/></h4>
    
    <table style="width:100%;font-size:10px;" border="1" >
        <tr>
            <th style="text-align: center;vertical-align: middle;">No</th>
            <th style="text-align: center;vertical-align: middle;">Doc Number</th>
            <th style="text-align: center;vertical-align: middle;">Date</th>
            <th style="text-align: center;vertical-align: middle;">Type</th>
            <th style="text-align: center;vertical-align: middle;">COA</th>
            <th style="text-align: center;vertical-align: middle;">COA Name</th>
            <th style="text-align: center;vertical-align: middle;">Value</th>
            <th style="text-align: center;vertical-align: middle;">Description</th>
            <th style="text-align: center;vertical-align: middle;">Created User</th>
        </tr>
        <?php 
        // koneksi database
        include '../../conn/conn.php';
        // $nama_supp=$_GET['nama_supp'];
        // $where =$_GET['where'];
        $where =$_GET['where'];
        // menampilkan data pegawai

        $sql = mysqli_query($conn2,"select no_dok,tgl_dok,type_value,no_coa,nama_coa,CONCAT(no_coa,' ',nama_coa) coa , IF(type_value = 'Negatif',val_min,val_plus) amount,upper(deskripsi) deskripsi,status,CONCAT(created_by, ' (', created_date,')') created_user from sb_journal_fiscal $where");

        $no = 1;

        while($row = mysqli_fetch_array($sql)){
            $type_value = isset($row['type_value']) ? $row['type_value'] : null;

            if ($type_value == 'Negatif') {
                $amount = number_format($row['amount'] * -1,2);
            }else{
                $amount = number_format($row['amount'],2);
            }

            echo '<tr style="font-size:12px;text-align:left;">
            <td style="text-align:center;">'.$no++.'</td>
            <td style="" value = "'.$row['no_dok'].'">'.$row['no_dok'].'</td>
            <td style="" value = "'.$row['tgl_dok'].'">'.date("d-M-Y",strtotime($row['tgl_dok'])).'</td>
            <td style="" value = "'.$row['type_value'].'">'.$row['type_value'].'</td>
            <td style="" value = "'.$row['no_coa'].'">'.$row['no_coa'].'</td>
            <td style="" value = "'.$row['nama_coa'].'">'.$row['nama_coa'].'</td>
            <td style=" text-align : right;" value="'.$row['amount'].'">'.$amount.'</td>
            <td style="" value = "'.$row['deskripsi'].'">'.$row['deskripsi'].'</td>
            <td style="" value = "'.$row['created_user'].'">'.$row['created_user'].'</td>
            ';

            ?>
            <?php 

        }
        ?>
    </table>

</body>
</html>




