<?php
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

// Koneksi khusus HRIS — hanya aktif di halaman ini
$server_hris   = "10.10.5.111";
$user_hris     = "root";
$pass_hris     = "95*76s^SAl8a";
$database_hris = "hris_nag";
$conn_hris = mysqli_connect($server_hris, $user_hris, $pass_hris, $database_hris);
if (!$conn_hris) {
    // Gagal konek HRIS tidak mengganggu halaman — cukup catat di variabel
    $conn_hris = null;
}

# START CEK HAK AKSES KEMBALI
$akses = flookup("user_account","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$st_company = flookup("status_company","mastercompany","company!=''");

# COPAS EDIT
$username = "";
$FullName = "";
$Password = "";
$nik = "";
$Groupp = "";
$kode_mkt = "";
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
  function validasi()
  { var username = document.form.txtusername.value;
    var FullName = document.form.txtFullName.value;
    var Password = document.form.txtPassword.value;
    var nik = document.form.txtnik.value;
    var Groupp = document.form.txtGroupp.value;
    if (username == '') { document.form.txtusername.focus(); swal({ title: 'User Name Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (FullName == '') { document.form.txtFullName.focus(); swal({ title: 'Full Name Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (Password == '') { document.form.txtPassword.focus(); swal({ title: 'Password Tidak Boleh Kosong', $img_alert }); valid = false;}
    else if (Groupp == '') { document.form.txtGroupp.focus(); swal({ title: 'Title Tidak Boleh Kosong', $img_alert }); valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>";
# END COPAS VALIDASI
# COPAS ADD

// Bangun options NIK dari HRIS
$hris_options = '<option value="">-- Pilih NIK --</option>';
if ($conn_hris) {
    $res_emp = mysqli_query($conn_hris, "SELECT a.nik, a.employee_name, b.no_cc, b.cc_name, b.kode_mkt
        FROM employee_atribut a
        INNER JOIN b_master_cc b ON b.no_cc = a.sub_dept_id
        WHERE a.status_aktif = 'AKTIF'
        ORDER BY a.employee_name");
    if ($res_emp) {
        while ($emp = mysqli_fetch_assoc($res_emp)) {
            $hris_options .= "<option value='".htmlspecialchars($emp['nik'], ENT_QUOTES)."'"
                ." data-name='".htmlspecialchars($emp['employee_name'], ENT_QUOTES)."'"
                ." data-nocc='".htmlspecialchars($emp['no_cc'], ENT_QUOTES)."'"
                ." data-kodemkt='".htmlspecialchars($emp['kode_mkt'], ENT_QUOTES)."'"
                ." data-ccname='".htmlspecialchars($emp['cc_name'], ENT_QUOTES)."'>"
                .htmlspecialchars($emp['nik'])." - ".htmlspecialchars($emp['employee_name'])
                ."</option>";
        }
    }
}

// Bangun options Cost Center dari HRIS
$cc_options = '<option value="">-- Pilih Cost Center --</option>';
if ($conn_hris) {
    $res_cc = mysqli_query($conn_hris, "select no_cc, CONCAT(no_cc,' - ',cc_name) cc_name, kode_mkt from b_master_cc where status = 'active' order by no_cc");
    if ($res_cc) {
        while ($cc = mysqli_fetch_assoc($res_cc)) {
            $cc_options .= "<option value='".htmlspecialchars($cc['no_cc'], ENT_QUOTES)."'"
                ." data-kodemkt='".htmlspecialchars($cc['kode_mkt'], ENT_QUOTES)."'>"
                .htmlspecialchars($cc['cc_name'])."</option>";
        }
    }
}
?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_setting.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>User Name *</label>
            <input type='text' class='form-control' id='txtusername' name='txtusername' placeholder='Masukkan User Name' value='<?php echo $username;?>'>
          </div>
          <div class='form-group'>
            <label>Full Name *</label>
            <input type='text' class='form-control' id='txtFullName' name='txtFullName' placeholder='Masukkan Full Name' value='<?php echo $FullName;?>'>
          </div>
          <div class='form-group'>
            <label>Password *</label>
            <input type='password' class='form-control' name='txtPassword' placeholder='Masukkan Password' value='<?php echo $Password;?>'>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>NIK</label>
            <select class='form-control select2' style='width:100%;' id='txtnik' name='txtnik'>
              <?php echo $hris_options; ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Cost Center</label>
            <select class='form-control select2' style='width:100%;' id='txtnocc' name='txtnoccenter'>
              <?php echo $cc_options; ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Title *</label>
            <select class='form-control select2' style='width: 100%;' name='txtGroupp'>
              <?php
                $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Jabatan_UA'";
                IsiCombo($sql,$Groupp,'Pilih Title');
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Group Marketing / Department</label>
            <input type='text' class='form-control' id='txtkode_mkt' name='txtkode_mkt' readonly placeholder='Terisi otomatis dari NIK / Cost Center' value='<?php echo $kode_mkt;?>'>
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
function onNikChange() {
    var sel     = document.getElementById('txtnik');
    var opt     = sel.options[sel.selectedIndex];
    var nocc    = opt.getAttribute('data-nocc')    || '';
    var kodemkt = opt.getAttribute('data-kodemkt') || '';
    var name    = opt.getAttribute('data-name')    || '';

    // Kunci cost center sesuai NIK, user tidak bisa ubah manual
    $('#txtnocc').val(nocc).prop('disabled', true).trigger('change');
    document.getElementById('txtkode_mkt').value = kodemkt;

    if (name !== '') {
        swal({
            title: 'Gunakan nama employee?',
            text: 'Isi otomatis Username dan Full Name dari "' + name + '"?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3c8dbc',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }, function(isConfirm) {
            if (isConfirm) {
                document.getElementById('txtFullName').value = name;
                document.getElementById('txtusername').value = name.toLowerCase();
            }
        });
    }
}

// Pakai window.load agar jQuery dari index.php sudah siap + select2 sudah init
window.addEventListener('load', function() {
    $('#txtnik').select2({ allowClear: true, placeholder: '-- Pilih NIK --', width: '100%' });
    $('#txtnocc').select2({ allowClear: true, placeholder: '-- Pilih Cost Center --', width: '100%' });

    $('#txtnik').on('select2:select', onNikChange);

    // Pilih cost center manual (tanpa NIK) -> isi otomatis Group Marketing / Department
    $('#txtnocc').on('select2:select', function(e) {
        var kodemkt = e.params.data.element.getAttribute('data-kodemkt') || '';
        document.getElementById('txtkode_mkt').value = kodemkt;
    });

    // Deteksi NIK dikosongkan via event change (lebih reliable dari select2:clear)
    $('#txtnik').on('change', function() {
        if (!$(this).val()) {
            $('#txtnocc').prop('disabled', false);
            $('#txtnocc').val('').trigger('change');
            document.getElementById('txtkode_mkt').value = '';
        }
    });
});
</script>
<?php
# END COPAS ADD
?>
<style>
#tbl-user thead tr th {
  background: #3c8dbc;
  color: #fff;
  font-size: 12px;
  font-weight: 600;
  padding: 10px 12px;
  border: none;
  white-space: nowrap;
}
#tbl-user tbody tr td {
  font-size: 12px;
  padding: 8px 12px;
  vertical-align: middle;
  border-bottom: 1px solid #f0f0f0;
}
#tbl-user tbody tr:hover td {
  background-color: #f5f9ff;
}
#tbl-user .tr-inactive td {
  background-color: #fff3f3;
  color: #c0392b;
  font-weight: 600;
}
#tbl-user .tr-inactive:hover td {
  background-color: #ffe0e0;
}
#tbl-user .badge-aktif {
  background: #00a65a; color:#fff;
  padding: 2px 8px; border-radius: 10px; font-size: 11px;
}
#tbl-user .badge-nonaktif {
  background: #dd4b39; color:#fff;
  padding: 2px 8px; border-radius: 10px; font-size: 11px;
}
#tbl-user .act-btn {
  display: inline-block;
  width: 28px; height: 28px; line-height: 28px;
  text-align: center; border-radius: 4px;
  font-size: 13px; margin: 1px;
}
#tbl-user .act-btn:hover { opacity: 0.8; }
#tbl-user .btn-access { background: #3c8dbc; color: #fff; }
#tbl-user .btn-active  { background: #00a65a; color: #fff; }
#tbl-user .btn-inactive{ background: #f39c12; color: #fff; }
#tbl-user .btn-reset   { background: #605ca8; color: #fff; }
</style>

<div class="box" style="margin-top:18px;">
  <div class="box-header" style="background:#f7f7f7; border-bottom:2px solid #3c8dbc; padding:12px 16px;">
    <h3 class="box-title" style="font-size:14px; font-weight:700; color:#3c8dbc;">
      <i class="fa fa-users" style="margin-right:6px;"></i>List User
    </h3>
  </div>
  <div class="box-body" style="padding:0;">
    <table id="tbl-user" class="display responsive" style="width:100%">
      <thead>
        <tr>
          <th>User Name</th>
          <th>Full Name</th>
          <th>NIK</th>
          <th>Title</th>
          <th>Group / Dept</th>
          <th>Status</th>
          <th style="text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql    = "select * from userpassword order by FullName asc";
        $result = mysql_query($sql);
        while ($data = mysql_fetch_array($result)) {
            $isAktif  = ($data['aktif'] == "Y");
            $trClass  = $isAktif ? '' : 'tr-inactive';
            $badgeSt  = $isAktif
                ? "<span class='badge-aktif'>Aktif</span>"
                : "<span class='badge-nonaktif'>Non Aktif</span>";

            $btnToggle = $isAktif
                ? "<a href='../setting/?mod=inactive&id=$data[username]' class='act-btn btn-inactive' data-toggle='tooltip' title='Non Aktifkan' onclick=\"return confirm('Non aktifkan user $data[username]?')\"><i class='fa fa-ban'></i></a>"
                : "<a href='../setting/?mod=active&id=$data[username]'   class='act-btn btn-active'  data-toggle='tooltip' title='Aktifkan'><i class='fa fa-check'></i></a>";

            echo "
            <tr class='$trClass'>
              <td><strong>$data[username]</strong></td>
              <td>$data[FullName]</td>
              <td>$data[nik]</td>
              <td>$data[Groupp]</td>
              <td>$data[kode_mkt]</td>
              <td>$badgeSt</td>
              <td style='text-align:center; white-space:nowrap;'>
                <a href='../setting/?mod=2&id=$data[username]' class='act-btn btn-access' data-toggle='tooltip' title='Hak Akses'><i class='fa fa-key'></i></a>
                $btnToggle
                <a href='../setting/?mod=reset&id=$data[username]' class='act-btn btn-reset' data-toggle='tooltip' title='Reset Password' onclick=\"return confirm('Reset password $data[username] ke 123?')\"><i class='fa fa-refresh'></i></a>
              </td>
            </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script>
window.addEventListener('load', function() {
    $('#tbl-user').DataTable({
        "pageLength"  : 10,
        "lengthMenu"  : [10, 25, 50],
        "ordering"    : true,
        "searching"   : true,
        "info"        : true,
        "language": {
            "search"      : "Cari:",
            "lengthMenu"  : "Tampilkan _MENU_ data",
            "info"        : "Menampilkan _START_ - _END_ dari _TOTAL_ user",
            "paginate": {
                "previous": "&laquo;",
                "next"    : "&raquo;"
            },
            "zeroRecords" : "Tidak ada data"
        }
    });
});
</script>