<html>
<head>
    <title>Export Data COA </title>
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

    <center>
        <h4>DATA CHART OF ACCOUNT <br/></h4>
    </center>
 
    <table style="width:100%;font-size:10px;" border="1" >
        <tr>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">No</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">No COA</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">COA Name</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 1</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 2</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 3</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 4</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 5</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 6</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category 7</th>
            <th colspan="4" style="text-align: center;vertical-align: middle;">Category Cash Flow Direct</th>
            <th colspan="2" style="text-align: center;vertical-align: middle;">Category Cash Flow Indirect</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Status</th>
        </tr>
        <tr>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
            <th style="text-align: center;vertical-align: middle;">Debit (Indonesia)</th>
            <th style="text-align: center;vertical-align: middle;">Debit (English)</th>
            <th style="text-align: center;vertical-align: middle;">Credit (Indonesia)</th>
            <th style="text-align: center;vertical-align: middle;">Credit (English)</th>
            <th style="text-align: center;vertical-align: middle;">Indonesia</th>
            <th style="text-align: center;vertical-align: middle;">English</th>
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
  
        $sql = mysqli_query($conn2,"select * from mastercoa_sb $where");

        $no = 1;

        while($row = mysqli_fetch_array($sql)){
            $idndirdebit = isset($row['ind_debit_direct']) ? $row['ind_debit_direct'] : "NA";
            $engdirdebit = isset($row['eng_debit_direct']) ? $row['eng_debit_direct'] : "NA";
            $idndircredit = isset($row['ind_credit_direct']) ? $row['ind_credit_direct'] : "NA";
            $engdircredit = isset($row['eng_credit_direct']) ? $row['eng_credit_direct'] : "NA";
            $idnindir = isset($row['ind_indirect']) ? $row['ind_indirect'] : "NA";
            $engindir = isset($row['eng_indirect']) ? $row['eng_indirect'] : "NA";

        echo '<tr style="font-size:12px;text-align:center;">
            <td >'.$no++.'</td>
            <td value = "'.$row['no_coa'].'">'.$row['no_coa'].'</td>
            <td style = "text-align: left;" value = "'.$row['nama_coa'].'">'.$row['nama_coa'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori1'].'">'.$row['ind_categori1'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori1'].'">'.$row['eng_categori1'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori2'].'">'.$row['ind_categori2'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori2'].'">'.$row['eng_categori2'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori3'].'">'.$row['ind_categori3'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori3'].'">'.$row['eng_categori3'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori4'].'">'.$row['ind_categori4'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori4'].'">'.$row['eng_categori4'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori5'].'">'.$row['ind_categori5'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori5'].'">'.$row['eng_categori5'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori6'].'">'.$row['ind_categori6'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori6'].'">'.$row['eng_categori6'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_categori7'].'">'.$row['ind_categori7'].'</td>
            <td style = "text-align: left;" value = "'.$row['eng_categori7'].'">'.$row['eng_categori7'].'</td>
            <td style = "text-align: left;" value = "'.$row['ind_debit_direct'].'">'.$idndirdebit.'</td>
            <td style = "text-align: left;" value = "'.$row['eng_debit_direct'].'">'.$engdirdebit.'</td>
            <td style = "text-align: left;" value = "'.$row['ind_credit_direct'].'">'.$idndircredit.'</td>
            <td style = "text-align: left;" value = "'.$row['eng_credit_direct'].'">'.$engdircredit.'</td>
            <td style = "text-align: left;" value = "'.$row['ind_indirect'].'">'.$idnindir.'</td>
            <td style = "text-align: left;" value = "'.$row['eng_indirect'].'">'.$engindir.'</td>
            <td value = "'.$row['status'].'">'.$row['status'].'</td>
             ';
         
        ?>
        <?php 
        
    }
        ?>
    </table>

</body>
</html>




