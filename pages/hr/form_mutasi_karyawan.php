<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

if (isset($_GET['id'])) {$idnik=$_GET['id'];} else {$idnik="";}
//if ($id_dp!="")
//{ 
  // $sql="select * from hr_masteremployee where nik='$id_dp'";
  // $rs=mysql_fetch_array(mysql_query($sql));

//}else{
  // $nik                = "";
  // $nama               = "";
  // $work_information   = "";
  // $bagian             = "";
  // $department         = "";
  // $tanggal_PL         = "";
  // $chief_diajukan     = "";
  // $hr_diketahui       = "";
  // $manager_diketahui  = "";
  // $general_disetujui  = "";
  // $gm_pro_disetujui   = "";
  // $grading_upah       = "";
  // $file_uraian        = "";
  // $ket_mut_pro_dem    = "";
//}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Pengisian Mutasi</title>
    <style>
        table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 90%;
                margin-left : 5%;
                margin-right: 5%;
            }
        font{
             font-style:italic;
            }
        .tigapuluh{
            width: 30%;
            margin: 10px;
        }
        .tujuhpuluh{
            width: 70%;
            margin: 10px;
        }
        select{
            margin:10px; width:50%; height:40px; font-size:14px;"
        }
    </style>
</head>
<body>
<form action="mutasi_kon.php" method="POST">
   <table>
        <tr>
            <td style="
            border: 2px solid #000000;
            text-align: center;
            padding: 8px;"
            rowspan="4"
            ><img src="../../include/LogoNAG.png" style="width:190px;"></td>
            <td style="
            border: 2px solid #000000;
            text-align: center;
            padding: 8px;"
            rowspan="4"
            > 
                <p style="font-weight:bold;
                        font-size:30px;
                ">FORMULIR PENGAJUAN MUTASI, DEMOSI & PROMOSI</p>
            </td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    padding: 8px;">
                Kode Dok</td>
            <td style="text-align: left;
                    border-top: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='F.16.P.HR.P-01.F-01.01' id="" name="" placeholder="wajib diisi"/> </td>
        </tr>
            <td style="text-align: left;
                    padding: 8px;"> Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='-' name="revisi">  </td>
        </tr>
        <tr>
            <td style="text-align: left;
                    padding: 8px;">Tanggal Revisi </td>
            <td style="text-align: left;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='-' name="revisi_date" value="<?php echo date('Y-m-d');?>"> </td>
        </tr>
        <tr>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    padding: 8px;">Tanggal Berlaku</td>
        <td style="text-align: left;
                    border-bottom: 2px solid #000000;
                    border-right: 2px solid #000000;
                    padding: 8px;">: <input type="text" readonly value='30 September 2016' name="berlaku_date"> </td>
        <tr>
   </table> 
   <br/>
<!--
     <?php if ($work_information==" Mutasi") {echo "checked";} ?> 
       <?php if ($work_information=="Promosi") {echo "checked";} ?>
             <?php if ($work_information=="Demosi") {echo "checked";} ?>
-->

    <table style="margin-bottom:20px;">
        <tr>
            <td style="text-align:left;width:15%;"> 
            <span style="font-weight:bold;font-size:19px;">Jenis Pengajuan<br> (<font>Tandai Salah Satu</font>)&nbsp;&nbsp;&nbsp;:</span></td>
            <td style="text-align:left;width:30%;font-size:23px;">
                <span style="padding-left:1%;">
                <input type="radio" name="info" value="Mutasi" 
             
                style="margin-top:24px; margin-left:1px"> Mutasi
                <input type="radio" name="info" value="Promosi"
                
                style="margin-top:8px; margin-left:32px"> Promosi
                <input type="radio" name="info" value="Demosi"
            
                style="margin-top:8px; margin-left:32px"> Demosi
            </td>
            <td style="text-align:left; width:30%;">
            <!-- image here -->
            </td>
       </tr>
   </table>
 
    <table style="margin-bottom:20px;">
        <tr>
            <td style="text-align:left;width:17%;"> 
            <span style="font-weight:bold;font-size:19px;">Tanggal Pengajuan&nbsp;&nbsp;&nbsp;:</span></td>
            <td class="tujuhpuluh"> 
                <input type="date" value="" style="width:30%; padding:5px; margin: 5px; font-size:14px;" id="datepicker2" placeholder="wajib diisi" name="tanggal_PL">
            </td>
        </tr>
        <tr>
            <td style="text-align:left;width:17%;"> 
            <span style="font-weight:bold;font-size:19px;">Nama Karyawan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span></td>
            <td class="tujuhpuluh form-group"> 
              <select class='form-control select2' onchange='changeValue(this.value)'>
                    <option>Pilih Karyawan</option>
                    <?php
                      $query = mysql_query("SELECT * FROM hr_masteremployee ORDER BY nik ASC");
                    $jsArray = "var prdName = new Array();\n";
                    while ($row = mysql_fetch_assoc($query)){
                    
                    echo"<option value=$row[nik]>$row[nama]</option>";
                    
                    $jsArray .= "prdName['".$row['nik']."']={
                      nik:'".addslashes($row['nik'])."',
                      bagian:'".addslashes($row['bagian'])."',
                      dept:'".addslashes($row['department'])."'};\n";
                    }
                    ?>
                  
                </select>
           </td>
        </tr>
   </table>
  
   <table>
        <tr>
            <td colspan="2" style="border: 2px solid black; text-align:center; width:4%;"><p style="font-weight:bold;">Status Lama</p></td>
            <td colspan="2" style="border: 2px solid black; text-align:center; width:4%;"><p style="font-weight:bold;">Status Baru</p></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">NIK</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <input type="text" id="nik" style="width:80%; padding:5px;" readonly>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">NIK</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <input type="text" id="nik2" value="" style="width:80%; padding:5px;" name="nik" >
            </td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Bagian</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <input type="text" id="bagian" style="width:80%; padding:5px;" readonly>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Bagian</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <input type="text" id="bagian2" style="width:80%; padding:5px;" name="bagian" value="" >
            </td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Department</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <input type="text" id="dept" style="width:80%; padding:5px;" name="depthis" readonly>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Department</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <input type="text" id="dept2" style="width:80%; padding:5px;" name="department" value="">
            </td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Grading Upah</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:80%; padding:5px;" readonly></td>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Grading Upah</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;"><input type="text" style="width:80%; padding:5px;" 
              name="grading_upah" value=""></td>
        </tr>
        <tr>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Jenis/Uraian Pekerjaan (<font>Lampirkan Pada Kertas Terpisah Jika Diperlukan</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">
            <textarea style="width:80%; padding:12px;" name="" readonly></textarea>
            </td>
            <td style="border: 2px solid black; padding:5px; text-align:left; width:10%;">Jenis/Uraian Pekerjaan (<font>Lampirkan Pada Kertas Terpisah Jika Diperlukan</font>)</td>
            <td style="border: 2px solid black; padding:5px; text-align:center; width:15%;">  <textarea style="width:80%; padding:12px;" name="file_uraian"></textarea>
            </td>
       
        </tr>
       
   </table>
  
 <table style="margin-top:20px;border: solid black;">
        <tr>
            <td style="text-align:left;width:30%;"> 
            <span style="font-weight:bold;font-size:19px;margin-left: 10px;">Keterangan Mutasi/ Promosi / Demosi &nbsp;&nbsp;&nbsp;&nbsp;:</span></td>
            <td style="padding:7px;width:30%;font-size:23px;">
                <textarea rows="6"style="width:120%;" name="ket_mut_pro_dem"></textarea>
            </td>
            <td style="text-align:left; width:30%;">
            <!-- image here -->
            </td>
       </tr>
   </table>

        
      <table style="margin-top: 20px;">
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><p style="font-weight:bold;">Diajukan</td>
                <td colspan="2" style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><p style="font-weight:bold;">Diketahui</td>
                <td colspan="2" style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><p style="font-weight:bold;">Disetujui</td>
                
            </tr>
            <tr>
                <td style="border: 2px solid black;  text-align:center; height:80px;width:15px;"></td>
                <td style="border: 2px solid black;  text-align:center; height:80px;width:15px;"></td>
                <td style="border: 2px solid black;  text-align:center; height:80px;width:15px;"></td>
                <td style="border: 2px solid black;  text-align:center; height:80px;width:15px;"></td>
                <td style="border: 2px solid black;  text-align:center; height:80px;width:15px;"></td>

            </tr>
            <tr>
                <td style="border: 2px solid black; text-align:center; width:15px;"><p style="font-weight:bold;">Chief / Dept. Head</td>
                <td style="border: 2px solid black; text-align:center; width:15px;"><p style="font-weight:bold;">HR-GA</td>
                <td style="border: 2px solid black; text-align:center; width:15px;"><p style="font-weight:bold;">Manager Dept.</td>
                <td style="border: 2px solid black; text-align:center; width:15px;"><p style="font-weight:bold;">General Manager</td>
                <td style="border: 2px solid black; text-align:center; width:15px;"><p style="font-weight:bold;">GM Produksi</td>

            </tr>
            <tr>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><input type="text" style="font-size:14px; width:200px; padding:5px;" 
                  name="chief_diajukan" value="">
               </td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><input type="text" style="font-size:14px; width:200px; padding:5px;" 
                  name="hr_diketahui" value=""></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><input type="text" style="font-size:14px; width:200px; padding:5px;" 
                  name="manager_diketahui" value=""></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><input type="text" style="font-size:14px; width:200px; padding:5px;" 
                  name="general_disetujui" value=""></td>
                <td style="border: 2px solid black; padding:5px; text-align:center; width:15px;"><input type="text" style="font-size:14px; width:200px; padding:5px;" 
                  name="gm_pro_disetujui" value=""></td>
             
     
            </tr>
        </table>
   <input type="submit" value="kirim" style="width:90%; margin-left:5%; margin-right:5%; background-color:#3588c4;
    font-size:18px; font-weight:bold; color:#ffffff; margin-top:10px; padding:8px;
   "><!--onclick="return isReadytoApply()"--> 
   </form>
<!--    <link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
   <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css"> -->
</body>

<script type="text/javascript">
  <?php echo $jsArray;?>

function changeValue(val)
{
  document.getElementById('nik').value    = prdName[val].nik;
  document.getElementById('nik2').value     = prdName[val].nik;
  document.getElementById('bagian').value = prdName[val].bagian;
  document.getElementById('bagian2').value = prdName[val].bagian;
  document.getElementById('dept').value   = prdName[val].dept;
  document.getElementById('dept2').value   = prdName[val].dept;
};

</script>

<!-- <script type="text/javascript">
  function isReadytoApply(){
    var iddok = document.getElementById("id_dok").value;
    var posisi = document.getElementById("posisi").value;
    var referensi = document.getElementById("referensi").value;
    var fullname = document.getElementById("fullname").value;
    var nickname = document.getElementById("nickname").value;
    var info = document.getElementById("info").value;
    var isiinfo = document.getElementById("isiinfo").value;
    var gender = document.getElementById("gender").value;
    var ttl = document.getElementById("ttl").value;
    var warganegara = document.getElementById("warganegara").value;
    var alamattetap = document.getElementById("alamattetap").value;
    var alamatsementara = document.getElementById("alamatsementara").value;
    var agama = document.getElementById("agama").value;
    var ktp = document.getElementById("ktp").value;
    var goldar = document.getElementById("goldar").value;
    var marital = document.getElementById("marital").value;
    var nohp = document.getElementById("nohp").value;
    var email = document.getElementById("email").value;
    var ukuranbaju = document.getElementById("ukuranbaju").value;
    var checkcheck = document.getElementById("checkcheck");

    if(info == "lainnya"){
        info.value = "lainnya - "+isiinfo;
    }

    if (iddok == "")
    { alert("ID Dokumen Kosong"); 
      document.getElementById("id_dok").focus(); valid = false;
    }
    else if (posisi == "")
    { alert("Posisi Kosong"); 
      document.getElementById("posisi").focus(); valid = false; 
    }
    else if (fullname == "")
    { alert("Nama Lengkap Kosong"); 
      document.getElementById("fullname").focus(); valid = false;
    }
    else if (nickname == "")
    { alert("Nama Singkat Kosong"); 
      document.getElementById("nickname").focus(); valid = false;
    }
    else if (ttl == "")
    { alert("Tpt Tgl Lahir Kosong"); 
      document.getElementById("ttl").focus(); valid = false;
    }
    else if (warganegara == "")
    { alert("Warga Negara Kosong"); 
      document.getElementById("warganegara").focus(); valid = false;
    }
    else if (alamattetap == "")
    { alert("Alamat Tetap Kosong"); 
      document.getElementById("alamattetap").focus(); valid = false;  
    }
    else if (alamatsementara == "")
    { alert("Alamat Sementara Kosong"); 
      document.getElementById("alamatsementara").focus(); valid = false;
    }
    else if (ktp == "")
    { alert("No. KTP Kosong"); 
      document.getElementById("ktp").focus(); valid = false;
    }
    else if (nohp == "")
    { alert("No. HP Kosong"); 
      document.getElementById("nohp").focus(); valid = false;
    }
    else if (email == "")
    { alert("Email Kosong"); 
      document.getElementById("email").focus(); valid = false;
    }
    else if (ukuranbaju == "")
    { alert("Ukuran Baju Kosong"); 
      document.getElementById("ukuranbaju").focus(); valid = false;
    }
    else if (referensi == "")
    { alert("Referensi Kosong"); 
      document.getElementById("referensi").focus(); valid = false;
    }
    else if(!checkcheck.checked)
    { alert("check the checkbox if you fill the form with correct answer"); 
      document.getElementById("checkcheck").focus(); valid = false;
    }
    else if(gender == 0)
    { alert("choose your gender"); 
      document.getElementById("gender").focus(); valid = false;
    }
    else if(agama == "")
    { alert("please choose your religion"); 
      document.getElementById("agama").focus(); valid = false;
    }
    else if(goldar == "")
    { alert("choose your blood type");
      document.getElementById("goldar").focus(); valid = false;
    }
    else if(marital == "")
    { alert("choose your marital status");
      document.getElementById("marital").focus(); valid = false;
    }
    else
    { valid = true;
      document.getElementById("formutasi").action = "mutasi_kon.php?id=<?php echo $id;?>";
    }
    return valid;
    exit;
}   
</script> -->
</html>