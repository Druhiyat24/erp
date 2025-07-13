<?php
require_once '../../include/conn.php';

/**
 * Buat Chart of Account untuk Supplier
 * Jika berhasil akan me-return id coa
 * Jika Nama supplier sudah digunakan atau terjadi error database akan me-return FALSE
 *
 * @param string $nm_supplier
 * @return mixed
 */
function create_supplier_coa($nm_supplier)
{
	global $con_new;
	// TODO: buat parameter ini menjadi dinamis berdasarkan master coa config
    $segment = '2101';
    $segment_len = 4;
    $id_len = 3;
    $post_to = '2101000';

	// Check for existing name
    $q = mysqli_query($con_new, "
		SELECT 
			mcoa.*
		FROM 
			mastercoa mcoa
		WHERE 1=1
			AND id_coa LIKE	'$segment%'
			AND nm_coa = '$nm_supplier'
	");

    // Name already exists
    if($q->num_rows){
    	return false;
	}

	// Get current max id
    $r = mysqli_fetch_object(mysqli_query($con_new, "
    	SELECT MAX(id_coa) max_coa 
		FROM mastercoa
		WHERE 1=1
		AND id_coa LIKE	'$segment%'
		AND id_coa <> '2101999'
	"));

    if(is_null($r)){
    	// Belum ada id coa, mulai dari post_to ditambah 1
        $id_coa = (int) substr($post_to, $segment_len,$id_len) + 1;
	}else{
    	// Ambil coa terakhir dan ditambahkan 1
    	$id_coa = (int) substr($r->max_coa, $segment_len,$id_len) + 1;
	}
	// Format ke dalam bentuk string dengan padding '0', dan ditambahkan dengan segment
    $id_coa = $segment . str_pad($id_coa, $id_len, '0', STR_PAD_LEFT);

    // Prepare data untuk insert
	$data = array(
        'id_coa' => $id_coa,
		'nm_coa' => $nm_supplier,
		'fg_posting' => '1',
		'fg_mapping' => '1',
		'fg_active' => '1',
		'post_to' => $post_to,
	);

	// Insert data ke database
    mysqli_query($con_new, "
		INSERT INTO mastercoa 
		  (id_coa, nm_coa, fg_posting, fg_mapping, fg_active, post_to)
		VALUES (
		  '{$data['id_coa']}',
		  '{$data['nm_coa']}',
		  '{$data['fg_posting']}',
		  '{$data['fg_mapping']}',
		  '{$data['fg_active']}',
		  '{$data['post_to']}'
		);
        ");

    if(mysqli_affected_rows($con_new)){
        return $id_coa;
	}else{
    	return false;
	}
};