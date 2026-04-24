<?php
session_start();

if (empty($_SESSION['username'])) {
    header("location:../../index.php");
    exit;
}

# CEK HAK AKSES
$akses = flookup("generate_kode", "userpassword", "username='$user'");
if ($akses == "0") {
?>
    <script>
        alert('Akses tidak dijinkan');
        window.location.href = 'index.php?mod=77';
    </script>
<?php
    exit;
}

$nm_company = flookup("company", "mastercompany", "company!=''");

$id_sub_group = isset($_GET['id']) ? $_GET['id'] : "";
$mod = isset($_GET['mod']) ? $_GET['mod'] : "";

?>

<style>
#notifBox {
    display: none;
    padding: 12px;
    margin: 5px 15px 15px 15px;
    border-radius: 6px;
    font-size: 14px;
    line-height: 1.5;
}

.success-box {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error-box {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>


<div class="box">
    <div class="box-body">
        <div class="row">
            <div id="notifBox" style="display:none;"></div>

            <form id="form_master" action="javascript:void(0);" enctype="multipart/form-data">

                <!-- GROUP -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Group *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_group" id="chk_group" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_group" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_group" name="cbo_group">
                                    <?php IsiCombo('select id isi,nama_group tampil from mastergroup', '', 'Pilih Group'); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUB GROUP -->
                <div class="col-md-3" id="col_sub_group">
                    <div class="form-group">
                        <label>Sub Group *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_sub_group" id="chk_sub_group" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_sub_group" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_sub_group" name="cbo_sub_group">
                                    <option value="">Pilih Sub Group</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TYPE -->
                <div class="col-md-3" id="col_type">
                    <div class="form-group">
                        <label>Type *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_type" id="chk_type" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_type" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_type" name="cbo_type">
                                    <option value="">Pilih Type</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENT -->
                <div class="col-md-3" id="col_contents">
                    <div class="form-group">
                        <label>Contents *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_contents" id="chk_contents" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_contents" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_contents" name="cbo_contents">
                                    <option value="">Pilih Contents</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WIDTH -->
                <div class="col-md-3" id="col_width">
                    <div class="form-group">
                        <label>Width *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_width" id="chk_width" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_width" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_width" name="cbo_width">
                                    <option value="">Pilih Width</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LENGTH -->
                <div class="col-md-3" id="col_length">
                    <div class="form-group">
                        <label>Length *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_length" id="chk_length" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_length" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_length" name="cbo_length">
                                    <option value="">Pilih Length</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WEIGHT -->
                <div class="col-md-3" id="col_weight">
                    <div class="form-group">
                        <label>Weight *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_weight" id="chk_weight" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_weight" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_weight" name="cbo_weight">
                                    <option value="">Pilih Weight</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLOR -->
                <div class="col-md-3" id="col_color">
                    <div class="form-group">
                        <label>Color *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_color" id="chk_color" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_color" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_color" name="cbo_color">
                                    <option value="">Pilih Color</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="col-md-3" id="col_description">
                    <div class="form-group">
                        <label>Description *</label>
                        <div style="display:flex; align-items:center; gap:8px; min-height:34px;">
                            <input type="checkbox" name="chk_description" id="chk_description" value="1" style="margin:0; position:relative; top:1px;">

                            <div id="select_description" style="flex:1; min-width:0;">
                                <select class="form-control select2" style="width:100%;" id="cbo_description" name="cbo_description">
                                    <option value="">Pilih Description</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MASTER GROUP SECTION -->
                <div id="master_group" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER GROUP
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Group *</label>
                            <input type="text" class="form-control" name="txt_group_kode" placeholder="Masukkan Kode Group">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Group</label>
                            <input type="text" class="form-control" name="txt_group_desc" placeholder="Masukkan Deskripsi Group">
                        </div>
                    </div>
                </div>

                <!-- MASTER SUB GROUP SECTION -->
                <div id="master_sub_group" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER SUB GROUP
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Sub Group *</label>
                            <input type="text" class="form-control" name="txt_subgroup_kode" placeholder="Masukkan Kode Sub Group">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Sub Group</label>
                            <input type="text" class="form-control" name="txt_subgroup_desc" placeholder="Masukkan Deskripsi Sub Group">
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label>ID CoA (Debet)</label>
                            <select class="form-control select2" style="width: 100%;" name="txt_subgroup_id_coa_debet">
                                <?php 
                                    $sql = "SELECT id_coa isi, CONCAT(id_coa,' | ',nm_coa) tampil FROM mastercoa";
                                    IsiCombo($sql, $id_prev, 'Pilih CoA');
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ID CoA (Credit)</label>
                            <select class="form-control select2" style="width: 100%;" name="txt_subgroup_id_coa_credit">
                                <?php 
                                    $sql = "SELECT id_coa isi, CONCAT(id_coa,' | ',nm_coa) tampil FROM mastercoa";
                                    IsiCombo($sql, $id_prev, 'Pilih CoA');
                                ?>
                            </select>
                        </div>
                    </div> -->
                </div>

                <!-- MASTER TYPE SECTION -->
                <div id="master_type" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER TYPE
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Type *</label>
                            <input type="text" class="form-control" name="txt_type_kode" placeholder="Masukkan Kode Type">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Type</label>
                            <input type="text" class="form-control" name="txt_type_desc" placeholder="Masukkan Deskripsi Type">
                        </div>
                    </div>
                </div>

                <!-- MASTER CONTENTS SECTION -->
                <div id="master_contents" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER CONTENTS
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Contents *</label>
                            <input type="text" class="form-control" name="txt_contents_kode" placeholder="Masukkan Kode Contents">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Contents</label>
                            <input type="text" class="form-control" name="txt_contents_desc" placeholder="Masukkan Deskripsi Contents">
                        </div>
                    </div>
                </div>

                <!-- MASTER WIDTH SECTION -->
                <div id="master_width" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER WIDTH
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Width *</label>
                            <input type="text" class="form-control" name="txt_width_kode" placeholder="Masukkan Kode Width">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Width</label>
                            <input type="text" class="form-control" name="txt_width_desc" placeholder="Masukkan Deskripsi Width">
                        </div>
                    </div>
                </div>

                <!-- MASTER LENGTH SECTION -->
                <div id="master_length" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER LENGTH
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Length *</label>
                            <input type="text" class="form-control" name="txt_length_kode" placeholder="Masukkan Kode Length">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Length</label>
                            <input type="text" class="form-control" name="txt_length_desc" placeholder="Masukkan Deskripsi Length">
                        </div>
                    </div>
                </div>

                <!-- MASTER WEIGHT SECTION -->
                <div id="master_weight" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER WEIGHT
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Weight *</label>
                            <input type="text" class="form-control" name="txt_weight_kode" placeholder="Masukkan Kode Weight">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Weight</label>
                            <input type="text" class="form-control" name="txt_weight_desc" placeholder="Masukkan Deskripsi Weight">
                        </div>
                    </div>
                </div>

                <!-- MASTER COLOR SECTION -->
                <div id="master_color" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER COLOR
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Color *</label>
                            <input type="text" class="form-control" name="txt_color_kode" placeholder="Masukkan Kode Color">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi Color</label>
                            <input type="text" class="form-control" name="txt_color_desc" placeholder="Masukkan Deskripsi Color">
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label>Pantone Color</label>
                            <input type="text" class="form-control" name="txt_color_pantone" placeholder="Masukkan Pantone Color">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Image File</label>
                            <input type="file" class="form-control" name="txt_color_image">
                        </div>
                    </div> -->
                </div>

                <!-- MASTER DESCRIPTION SECTION -->
                <div id="master_description" style="display:none;">
                    <div class="col-md-12">
                        <hr>
                        <h4 style="margin-top:10px; margin-bottom:15px; font-weight:bold;">
                            MASTER DESCRIPTION
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Desc *</label>
                            <input type="text" class="form-control" name="txt_description_kode" placeholder="Masukkan Kode Desc">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <input type="text" class="form-control" name="txt_description_desc" placeholder="Masukkan Deskripsi">
                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="col-md-12">
                    <button type="submit" id="btn_save" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header">
        <div style="display:flex; align-items:center; gap:10px;">
            <h3 class="box-title" style="margin:0;">Data Master</h3>

            <select id="modeView" class="form-control select2" style="width:200px;">
                <option value="group">Group</option>
                <option value="subgroup">Sub Group</option>
                <option value="type">Type</option>
                <option value="contents">Contents</option>
                <option value="width">Width</option>
                <option value="length">Length</option>
                <option value="weight">Weight</option>
                <option value="color">Color</option>
                <option value="description">Description</option>
            </select>
        </div>
    </div>

    <div class="box-body" id="wrapGroup">
        <table id="mgroup" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Kode Group</th>
                    <th>Deskripsi Group</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
 
    <div class="box-body" id="wrapSubGroup" style="display:none;">
        <table id="msubgroup" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Group</th>
                    <th>Kode Sub Group</th>
                    <th>Deskripsi Sub Group</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapType" style="display:none;">
        <table id="mtype" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Sub Group</th>
                    <th>Material Type</th>
                    <th>Deskripsi Type</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapContents" style="display:none;">
        <table id="mcontent" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Sub Group</th>
                    <th>Kode Contents</th>
                    <th>Deskripsi Type</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapWidth" style="display:none;">
        <table id="mwidth" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Sub Group</th>
                    <th>Kode Width</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapLength" style="display:none;">
        <table id="mlength" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Sub Group</th>
                    <th>Kode Length</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapWeight" style="display:none;">
        <table id="mweight" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Sub Group</th>
                    <th>Kode Weight</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapColor" style="display:none;">
        <table id="mcolor" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Contents</th>
                    <th>Nama Sub Group</th>
                    <th>Kode Color</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="box-body" id="wrapDescription" style="display:none;">
        <table id="listmdesc" class="display responsive" style="width:100%">
            <thead>
                <tr>
                    <th>ID Contents</th>
                    <th>Nama Sub Group</th>
                    <th>Kode Desc</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="../../plugins/jQuery/jquery-1.7.1.min.js"></script>
<script src="js/Masterall.js"></script>