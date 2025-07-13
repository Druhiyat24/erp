

<?php
ob_start();
// Memuat PHPExcel
require_once '../../plugins/PHPExcel/Classes/PHPExcel.php';
include "../../include/conn.php";
include "fungsi.php";
ini_set('date.timezone', 'Asia/Jakarta');
session_start();

// Fungsi untuk memproses dan menampilkan data sheet
// function processSheet($sheetName, $sheetData) {
//     // Menampilkan nama sheet
//     echo "<h2>Sheet: " . htmlspecialchars($sheetName) . "</h2>";

//     // Menampilkan data sheet dalam bentuk tabel HTML
//     echo "<table border='1' cellpadding='5' cellspacing='0'>";
//     foreach ($sheetData as $rowIndex => $row) {
//         echo "<tr>";
//         foreach ($row as $cell) {
//             echo "<td>" . htmlspecialchars($cell) . "</td>";
//         }
//         echo "</tr>";
//     }
//     echo "</table><br><br>";
// }


// Fungsi untuk memproses dan memasukkan data ke dalam database
function detectColumnTypes($sheetData) {
    $columnTypes = [];
    $sampleData = array_slice($sheetData, 1, 10); // Ambil 10 baris pertama untuk sampel
    $numColumns = count($sheetData[0]);

    for ($colIndex = 0; $colIndex < $numColumns; $colIndex++) {
        $isInt = true;
        $isDecimal = true;

        foreach ($sampleData as $row) {
            if (isset($row[$colIndex])) {
                $cellValue = $row[$colIndex];

                // Jika ada angka dengan awalan 0, jadikan VARCHAR
                if (is_numeric($cellValue) && preg_match('/^0\d+/', $cellValue)) {
                    $isInt = false;
                    $isDecimal = false;
                    break;
                }

                // Jika bukan integer
                if (!is_numeric($cellValue) || strpos($cellValue, '.') !== false) {
                    $isInt = false;
                }

                // Jika bukan decimal
                if (!is_numeric($cellValue)) {
                    $isDecimal = false;
                }
            }
        }

        if ($isInt) {
            $columnTypes[] = 'INT';
        } elseif ($isDecimal) {
            $columnTypes[] = 'DECIMAL(10,2)';
        } else {
            $columnTypes[] = 'VARCHAR(255)';
        }
    }

    return $columnTypes;
}



function processSheetAndInsertData($sheetName, $sheetData, $conn_li) {
    // Membuat nama tabel yang sesuai dengan nama sheet
    $tableName = mysqli_real_escape_string($conn_li, $sheetName);
    $tableName = 'exim_' . strtolower($tableName); // Nama tabel dengan huruf kecil

    // Mengambil baris pertama sebagai header (judul kolom)
    $headers = $sheetData[0]; // Baris pertama dianggap sebagai header
    $headersEscaped = array_map(function($header) use ($conn_li) {
        $escapedHeader = mysqli_real_escape_string($conn_li, preg_replace('/[^a-zA-Z0-9_]/', '_', $header)); // Ubah karakter tidak valid menjadi _
        return strtolower($escapedHeader); // Ubah semua huruf menjadi huruf kecil
    }, $headers);

    // Membuat nama kolom unik untuk menghindari konflik
    $headersUnique = [];
    foreach ($headersEscaped as $header) {
        $uniqueHeader = $header;
        $i = 1;
        while (in_array($uniqueHeader, $headersUnique)) {
            $uniqueHeader = $header . '_' . $i;
            $i++;
        }
        $headersUnique[] = $uniqueHeader;
    }
    $headersEscaped = $headersUnique;

    // Cek apakah tabel sudah ada di database, jika belum buat tabel baru
    $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
    $result = mysqli_query($conn_li, $checkTableQuery);
    if (!$result) {
        return;
    }

    if (mysqli_num_rows($result) == 0) {
        // Deteksi tipe data untuk setiap kolom
        $columnTypes = detectColumnTypes($sheetData);

        // Membuat tabel baru dengan kolom sesuai header dan tipe data
        $columns = [];
        foreach ($headersEscaped as $index => $header) {
            $columns[] = "`$header` " . $columnTypes[$index];
        }
        $columns[] = "`no_dokumen` VARCHAR(255)";
        $columns[] = "`created_by` VARCHAR(255)";
        $columns[] = "`created_date` TIMESTAMP";
        $columns[] = "`status_update_sb` VARCHAR(50) DEFAULT ''"; // Mengubah nama kolom status menjadi status_update_sb
        $columnsDefinition = implode(", ", $columns);

        $createTableQuery = "CREATE TABLE `$tableName` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            $columnsDefinition
        )";

        if (!mysqli_query($conn_li, $createTableQuery)) {
            return;
        }
    }

    // Mendapatkan nomor dokumen untuk satu kali upload
    $currentMonthYear = date('my'); // Format mmyy
    $prefix = "UPL/EXIM/$currentMonthYear/";
    $runningNumber = 1; // Default jika tabel kosong
    $lastDocQuery = "SELECT no_dokumen FROM `$tableName` WHERE no_dokumen LIKE '$prefix%' ORDER BY id DESC LIMIT 1";
    $lastDocResult = mysqli_query($conn_li, $lastDocQuery);
    if ($lastDocResult && mysqli_num_rows($lastDocResult) > 0) {
        $lastDocRow = mysqli_fetch_assoc($lastDocResult);
        $lastNumber = intval(substr($lastDocRow['no_dokumen'], -5)); // Ambil 5 angka terakhir
        $runningNumber = $lastNumber + 1;
    }

    $noDokumen = $prefix . str_pad($runningNumber, 5, '0', STR_PAD_LEFT); // Format nomor dokumen
    $createdBy = isset($_SESSION['username']) ? $_SESSION['username'] : 'default_user';
    $createdDate = date('Y-m-d H:i:s');

    // Menyisipkan data ke dalam tabel
    $columnsList = implode(", ", array_map(function($header) {
        return "`$header`";
    }, $headersEscaped)) . ", `no_dokumen`, `created_by`, `created_date`, `status_update_sb`"; // Mengubah status menjadi status_update_sb

    $stmt = $conn_li->prepare("INSERT INTO `$tableName` ($columnsList) VALUES (" . str_repeat('?, ', count($headersEscaped)) . "?, ?, ?, ?)");

    foreach ($sheetData as $rowIndex => $row) {
        // Lewatkan baris pertama yang berisi header (judul)
        if ($rowIndex == 0) {
            continue;
        }

        // Menyiapkan data untuk kolom
        $values = array_map(function($cell) use ($conn_li) {
            return mysqli_real_escape_string($conn_li, $cell);
        }, $row);

        // Menambahkan nilai untuk kolom tambahan
        $values[] = $noDokumen;
        $values[] = $createdBy;
        $values[] = $createdDate;
        $values[] = 'active'; // Nilai default untuk kolom status_update_sb

        // Menjalankan query insert
        if (!$stmt->bind_param(str_repeat('s', count($values)), ...$values) || !$stmt->execute()) {
            // Handle error here
        }
    }

    $stmt->close();
}




if (isset($_FILES['file'])) {
    // Mendapatkan file yang di-upload
    $file = $_FILES['file'];

    // Mengecek apakah file yang di-upload valid
    if ($file['error'] == 0) {
        // Menyimpan file ke server
        $uploadDir = 'uploads/';
        $uploadedFile = $uploadDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $uploadedFile);

        // Menggunakan PHPExcel untuk membaca file Excel
        $objPHPExcel = PHPExcel_IOFactory::load($uploadedFile);

        // Iterasi melalui semua sheet di dalam file
        $sheetNames = $objPHPExcel->getSheetNames();
        foreach ($sheetNames as $sheetIndex => $sheetName) {
            // Mendapatkan data sheet
            $sheet = $objPHPExcel->getSheet($sheetIndex);
            $sheetData = $sheet->toArray();

            // Panggil fungsi untuk memproses setiap sheet
            // processSheet($sheetName, $sheetData);
            processSheetAndInsertData($sheetName, $sheetData, $conn_li);
        }
        ob_end_clean();
    echo json_encode(["status" => "success", "message" => "File berhasil diproses!"]);
} else {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat upload."]);
}
} else {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Tidak ada file yang di-upload."]);
}
?>
