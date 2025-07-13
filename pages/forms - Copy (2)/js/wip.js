$( document ).ready(function() {

});


function konfirmasi($konfirmasi,$idjo,$bpb,$code,$journal){
	if($journal == 'FALSE'){
		UpdateKonfirmasi($idjo,$konfirmasi);
	}else{
		GetDataJournal($konfirmasi,$idjo,$bpb,$code,$journal);
	}
}
function GetDataJournal($konfirmasi,$idjo,$bpb,$code,$journal){
	CallServicesJournal($konfirmasi,$idjo,$bpb,$code,$journal).then(function($responnya) {
		console.log($responnya);
		if($responnya.message == '1'){
			UpdateKonfirmasi($idjo,$konfirmasi);
			alert("Data Berhasil Dikonfirmasi");
		}else{
			alert("Ada Error!");
		}
	});	
}
function UpdateKonfirmasi($konfirmasi,$idjo,$bpb,$code,$journal){
	CallServicesUpdateKonfirmasi($konfirmasi,$idjo,$bpb,$code,$journal).then(function($responnya) {
		console.log($responnya);
		if($responnya.message == '1'){
			alert("Data Berhasil Dikonfirmasi");
			location.reload();
		}else{
			alert("Ada Error!");
		}
	});		
}

function CallServicesJournal($konfirmasi,$idjo,$bpb,$code,$journal){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/CreateJournalMakloon.php",
        data: { code: '1', bpb: $bpb,journal:$journal,type_journal:$journal},     // multiple data sent using ajax
        success: function (response) {
            d = JSON.parse(response);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d);
            }
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });
	});		
	
}
function CallServicesUpdateKonfirmasi($konfirmasi,$idjo,$bpb,$code,$journal){
	console.log($journal);
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/UpdateKonfirmasiWip.php",
        data: { code: '1', idjo: $konfirmasi,nilai_konfirmasi:$idjo},      // multiple data sent using ajax
        success: function (response) {
            d = JSON.parse(response);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d);
            }
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });
	});		
	
}
	