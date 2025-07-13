$( document ).ready(function() {
	
});



function create_separator($angka,$id){
	$ang_ka_ = $angka.replace(/,/g, '').toString();
	return new Promise(function (resolve, reject) {
		if(isNaN($ang_ka_)){
						$res  ={
							angka :'NAN',
							id    : $id,
							message : 'NO'
						} 
			resolve($res);
		}else{
			$.ajax("webservices/create_separator.php",
				{
					type: "POST",
					data :{code:'1', angka:$ang_ka_,id:$id},
					success: function (data) {
						data= jQuery.parseJSON(data)
						$res  ={
							angka :data.records,
							id    : data.id,
							message : 'OK'
						} 
						console.log(data.records);
						resolve($res);
					},
					error: function (data) {
						alert("Error req");
					}
				});			
			
			
			
		}
	})
	};

function remove_separator($angka){
	console.log($angka);
	$ang_ka_ = $angka.replace(/,/g, '').toString();
		return parseFloat($ang_ka_);
	};	
