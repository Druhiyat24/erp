$(document ).ready(function() {
    generateTableGroup(); 

    $("#modeView").on("change", function () {
        let val = $(this).val();

        hideAll();

        if (val == "group") {
            $("#wrapGroup").show();
            generateTableGroup();
        } 
        else if (val == "subgroup") {
            $("#wrapSubGroup").show();
            generateTableSubGroup();
        }
        else if (val == "type") {
            $("#wrapType").show();
            generateTableType();
        }
        else if (val == "contents") {
            $("#wrapContents").show();
            generateTableContents();
        }
        else if (val == "width") {
            $("#wrapWidth").show();
            generateTableWidth();
        }
        else if (val == "length") {
            $("#wrapLength").show();
            generateTableLength();
        }
        else if (val == "weight") {
            $("#wrapWeight").show();
            generateTableWeight();
        }
        else if (val == "color") {
            $("#wrapColor").show();
            generateTableColor();
        }
        else if (val == "description") {
            $("#wrapDescription").show();
            generateTableDescription();
        }
    });

    let isManualMode = false;

    const urutan = [
        'group',
        'sub_group',
        'type',
        'contents',
        'width',
        'length',
        'weight',
        'color',
        'description'
    ];

    // =========================
    // MODE BERURUTAN (SELECT)
    // =========================
    function toggleAll() {
        if (isManualMode) return;

        urutan.forEach((key, index) => {

            // skip kalau checkbox sedang aktif (mode manual)
            if ($('#chk_' + key).is(':checked')) return;

            let val = $('[name="cbo_' + key + '"]').val();

            // default hide dulu
            $('[name="chk_' + key + '"]').hide();

            // khusus pertama (group)
            if (index === 0) {
                if (!val) $('[name="chk_' + key + '"]').show();
                return;
            }

            // cek previous
            let prevKey = urutan[index - 1];
            let prevVal = $('[name="cbo_' + prevKey + '"]').val();

            if (prevVal && !val) {
                $('[name="chk_' + key + '"]').show();
            }
        });
    }

    // =========================
    // MODE MANUAL (CHECKBOX)
    // =========================
    function handleCheckbox(index) {
        let current = urutan[index];
        let isChecked = $('#chk_' + current).is(':checked');

        isManualMode = true; //

        // reset select biar tidak bentrok
        if (isChecked) {
            $('[name="cbo_' + current + '"]').val(null).trigger('change.select2');
        }

        // toggle select current
        $('#select_' + current).toggle(!isChecked);

        for (let i = index; i < urutan.length; i++) {
            let key = urutan[i];

            // tampilkan master dari current ke bawah
            $('#master_' + key).toggle(isChecked);

            if (i > index) {
                // hide kolom bawah kalau manual aktif
                $('#col_' + key).toggle(!isChecked);
            }
        }

        // kalau di-uncheck → balik ke mode normal
        if (!isChecked) {
            isManualMode = false; 
            toggleAll();
        }
    }

    // =========================
    // EVENT BINDING
    // =========================

    // checkbox
    urutan.forEach((key, index) => {
        $('#chk_' + key).on('change', function () {
            handleCheckbox(index);
        });
    });

    // select (support select2 juga)
    $('select[name^="cbo_"]').on('change select2:select select2:clear', function () {
        toggleAll();
    });

    // =========================
    // INIT AWAL
    // =========================
    toggleAll();

    $("#cbo_group").on("change", function () {
        var id_group = $(this).val();

        resetAfter(0); 

        if (id_group != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'subgroup',
                    id_group: id_group
                },
                success: function (data) {
                    $("#cbo_sub_group").html(data);
                    $("#cbo_sub_group").trigger("change");
                }
            });
        } else {
            $("#cbo_sub_group").html('<option value="">Pilih Sub Group</option>');
        }
    });

    $("#cbo_sub_group").on("change", function () {
        var id_sub_group = $(this).val();

        resetAfter(1); 

        if (id_sub_group != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'type',
                    id_sub_group: id_sub_group
                },
                success: function (data) {
                    $("#cbo_type").html(data);
                    $("#cbo_type").trigger("change");
                }
            });
        } else {
            $("#cbo_type").html('<option value="">Pilih Type</option>');
        }
    });

    $("#cbo_type").on("change", function () {
        var id_type = $(this).val();

        resetAfter(2); 

        if (id_type != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'contents',
                    id_type: id_type
                },
                success: function (data) {
                    $("#cbo_contents").html(data);
                    $("#cbo_contents").trigger("change");
                }
            });
        } else {
            $("#cbo_contents").html('<option value="">Pilih Contents</option>');
        }
    });

    $("#cbo_contents").on("change", function () {
        var id_contents = $(this).val();

        resetAfter(3); 

        if (id_contents != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'width',
                    id_contents: id_contents
                },
                success: function (data) {
                    $("#cbo_width").html(data);
                    $("#cbo_width").trigger("change");
                }
            });
        } else {
            $("#cbo_width").html('<option value="">Pilih Width</option>');
        }
    });

    $("#cbo_width").on("change", function () {
        var id_width = $(this).val();

        resetAfter(4); 

        if (id_width != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'length',
                    id_width: id_width
                },
                success: function (data) {
                    $("#cbo_length").html(data);
                    $("#cbo_length").trigger("change");
                }
            });
        } else {
            $("#cbo_length").html('<option value="">Pilih Length</option>');
        }
    });

    $("#cbo_length").on("change", function () {
        var id_length = $(this).val();

        resetAfter(5); 

        if (id_length != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'weight',
                    id_length: id_length
                },
                success: function (data) {
                    $("#cbo_weight").html(data);
                    $("#cbo_weight").trigger("change");
                }
            });
        } else {
            $("#cbo_weight").html('<option value="">Pilih Weight</option>');
        }
    });

    $("#cbo_weight").on("change", function () {
        var id_weight = $(this).val();

        resetAfter(6); 

        if (id_weight != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'color',
                    id_weight: id_weight
                },
                success: function (data) {
                    $("#cbo_color").html(data);
                    $("#cbo_color").trigger("change");
                }
            });
        } else {
            $("#cbo_color").html('<option value="">Pilih Color</option>');
        }
    });

    $("#cbo_color").on("change", function () {
        var id_color = $(this).val();

        resetAfter(7); 

        if (id_color != "") {
            $.ajax({
                url: 'webservices/getDataMasterall.php',
                type: "POST",
                data: {
                    type: 'description',
                    id_color: id_color
                },
                success: function (data) {
                    $("#cbo_description").html(data);
                    $("#cbo_description").trigger("change");
                }
            });
        } else {
            $("#cbo_description").html('<option value="">Pilih Description</option>');
        }
    });

    function resetAfter(index) {
        for (let i = index + 1; i < urutan.length; i++) {
            let key = urutan[i];

            // reset select
           let label = key
                .replace(/_/g, ' ')
                .replace(/\b\w/g, function (l) { return l.toUpperCase(); });

            $('[name="cbo_' + key + '"]')
                .html('<option value="">Pilih ' + label + '</option>')
                .val(null)
                .trigger('change.select2');

            // reset checkbox
            $('#chk_' + key).prop('checked', false);

            // reset UI
            $('#select_' + key).show();
            $('#master_' + key).hide();
            $('#col_' + key).show();
        }
    }

    let isSubmitting = false;

    $("#form_master").off("submit").on("submit", function (e) {
        e.preventDefault();

        if (isSubmitting) return; 
        isSubmitting = true;

        let formData = new FormData(this);

        $.ajax({
            url: "s_master_all.php", 
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (res) {

                window.scrollTo(0, 0);

                var box = $("#notifBox");

                box.removeClass("success-box error-box");

                if (res.status === "ok") {
                    box.addClass("success-box");
                    reloadCurrentTable();
                    refreshFullChain();
                } else {
                    box.addClass("error-box");
                }

                box.html(res.message).fadeIn();

                setTimeout(function () {
                    box.fadeOut();
                }, 5000);
            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                isSubmitting = false;
            }
        });
    });
}); 

function refreshFullChain() {

    let group       = $("#cbo_group").val();
    let sub_group   = $("#cbo_sub_group").val();
    let type        = $("#cbo_type").val();
    let contents    = $("#cbo_contents").val();
    let width       = $("#cbo_width").val();
    let length      = $("#cbo_length").val();
    let weight      = $("#cbo_weight").val();
    let color       = $("#cbo_color").val();
    let description = $("#cbo_description").val();

    if (!group) return;

    // =========================
    // GROUP → SUB GROUP
    // =========================
    $.post('webservices/getDataMasterall.php', {
        type: 'subgroup',
        id_group: group
    }, function (data) {

        $("#cbo_sub_group").html(data).val(sub_group);

        if (!sub_group) return;

        // =========================
        // SUB GROUP → TYPE
        // =========================
        $.post('webservices/getDataMasterall.php', {
            type: 'type',
            id_sub_group: sub_group
        }, function (data2) {

            $("#cbo_type").html(data2).val(type);

            if (!type) return;

            // =========================
            // TYPE → CONTENTS
            // =========================
            $.post('webservices/getDataMasterall.php', {
                type: 'contents',
                id_type: type
            }, function (data3) {

                $("#cbo_contents").html(data3).val(contents);

                if (!contents) return;

                // =========================
                // CONTENTS → WIDTH
                // =========================
                $.post('webservices/getDataMasterall.php', {
                    type: 'width',
                    id_contents: contents
                }, function (data4) {

                    $("#cbo_width").html(data4).val(width);

                    if (!width) return;

                    // =========================
                    // WIDTH → LENGTH
                    // =========================
                    $.post('webservices/getDataMasterall.php', {
                        type: 'length',
                        id_width: width
                    }, function (data5) {

                        $("#cbo_length").html(data5).val(length);

                        if (!length) return;

                        // =========================
                        // LENGTH → WEIGHT
                        // =========================
                        $.post('webservices/getDataMasterall.php', {
                            type: 'weight',
                            id_length: length
                        }, function (data6) {

                            $("#cbo_weight").html(data6).val(weight);

                            if (!weight) return;

                            // =========================
                            // WEIGHT → COLOR
                            // =========================
                            $.post('webservices/getDataMasterall.php', {
                                type: 'color',
                                id_weight: weight
                            }, function (data7) {

                                $("#cbo_color").html(data7).val(color);

                                if (!color) return;

                                // =========================
                                // COLOR → DESCRIPTION
                                // =========================
                                $.post('webservices/getDataMasterall.php', {
                                    type: 'description',
                                    id_color: color
                                }, function (data8) {

                                    $("#cbo_description").html(data8).val(description);

                                });

                            });

                        });

                    });

                });

            });

        });

    });
}

function reloadCurrentTable() {
    let val = $("#modeView").val();

    if (val == "group") {
        $('#mgroup').DataTable().ajax.reload(null, false);
    } 
    else if (val == "subgroup") {
        $('#msubgroup').DataTable().ajax.reload(null, false);
    }
    else if (val == "type") {
        $('#mtype').DataTable().ajax.reload(null, false);
    }
    else if (val == "contents") {
        $('#mcontent').DataTable().ajax.reload(null, false);
    }
    else if (val == "width") {
        $('#mwidth').DataTable().ajax.reload(null, false);
    }
    else if (val == "length") {
        $('#mlength').DataTable().ajax.reload(null, false);
    }
    else if (val == "weight") {
        $('#mweight').DataTable().ajax.reload(null, false);
    }
    else if (val == "color") {
        $('#mcolor').DataTable().ajax.reload(null, false);
    }
    else if (val == "description") {
        $('#listmdesc').DataTable().ajax.reload(null, false);
    }
}

function generateTableGroup() {
    table = $('#mgroup').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ajax': {
            'url': 'webservices/getListMasterGroup.php',
        },
        'columns': [
            {
                "data": "kode_group",
            },
            {
                "data": "nama_group",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=3&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}


function generateTableSubGroup() {
    table = $('#msubgroup').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ajax': {
            'url': 'webservices/getListMasterSubGroup.php',
        },
        'columns': [
            {
                "data": "nama_group",
            },
            {
                "data": "kode_sub_group",
            },
            {
                "data": "nama_sub_group",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=4&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableType() {
    table = $('#mtype').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ajax': {
            'url': 'webservices/getListMasterType.php',
        },
        'columns': [
            {
                "data": "tampil",
            },
            {
                "data": "kode_type",
            },
            {
                "data": "nama_type",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=5&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableContents() {
    table = $('#mcontent').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ajax': {
            'url': 'webservices/getListMasterContent.php',
        },
        'columns': [
            {
                "data": "id",
            },
            {
                "data": "tampil",
            },
            {
                "data": "kode_contents",
            },
            {
                "data": "nama_contents",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=6&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableWidth() {
    table = $('#mwidth').DataTable({
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ordering': true,
        'order': [[1, 'desc']],
        'ajax': {
            'url': 'webservices/getListMasterWidth.php',
        },
        'columns': [
            {
                "data": "tampil",
            },
            {
                "data": "kode_width",
            },
            {
                "data": "nama_width",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=7&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableLength() {
    table = $('#mlength').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ordering': true,
        'order': [[1, 'desc']],
        'ajax': {
            'url': 'webservices/getListMasterLength.php',
        },
        'columns': [
            {
                "data": "tampil",
            },
            {
                "data": "kode_length",
            },
            {
                "data": "nama_length",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=8&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableWeight() {
    table = $('#mweight').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ordering': true,
        'order': [[1, 'desc']],
        'ajax': {
            'url': 'webservices/getListMasterWeight.php',
        },
        'columns': [
            {
                "data": "tampil",
            },
            {
                "data": "kode_weight",
            },
            {
                "data": "nama_weight",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=9&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableColor() {
    table = $('#mcolor').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ajax': {
            'url': 'webservices/getListMasterColor.php',
        },
        'columns': [
            {
                "data": "id",
            },
            {
                "data": "id_contents",
            },
            {
                "data": "tampil",
            },
            {
                "data": "kode_color",
            },
            {
                "data": "nama_color",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=10&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function generateTableDescription() {
    table = $('#listmdesc').DataTable({
        'processing': true,
        'serverSide': true,
        'destroy': true,
        'serverMethod': 'post',
        'autoWidth': false,
        'ajax': {
            'url': 'webservices/getListMasterDesc.php',
        },
        'columns': [
            {
                "data": "id_contents",
            },
            {
                "data": "tampil",
            },
            {
                "data": "kode_desc",
            },
            {
                "data": "nama_desc",
            },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <a href="../master/?mod=11&mode=&id=${row.id}" 
                        target="_blank"
                        data-toggle="tooltip" title="Edit">
                        <i class="fa fa-pencil"></i>
                        </a>
                    `;
                }
            }
        ],
    });
}

function hideAll() {
    $("#wrapGroup").hide();
    $("#wrapSubGroup").hide();
    $("#wrapType").hide();
    $("#wrapContents").hide();
    $("#wrapWidth").hide();
    $("#wrapLength").hide();
    $("#wrapWeight").hide();
    $("#wrapColor").hide();
    $("#wrapDescription").hide();
}