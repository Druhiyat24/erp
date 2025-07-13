function generateSelectTanggal(){
 var xDate = 31;
 var xBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
 //console.log(xBulan.length);
 for(x=1;x<=xDate;x++){
	  $('#content_tglday').append('<option value="'+x+'" >'+x+'</option>');
	  $('#content_tglday1').append('<option value="'+x+'" >'+x+'</option>');
	  $('#content_tglday2').append('<option value="'+x+'" >'+x+'</option>');
	  $('#content_tglday3').append('<option value="'+x+'" >'+x+'</option>');
	  $('#header_tglday').append('<option value="'+x+'" >'+x+'</option>');
	  $('#footer_tglday').append('<option value="'+x+'" >'+x+'</option>');
	  $('#footer_tglday2').append('<option value="'+x+'" >'+x+'</option>');
	  $('#footer_tglday3').append('<option value="'+x+'" >'+x+'</option>');
 }
 for(x=0;x<xBulan.length;x++){
	 $('#content_tglmonth').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	$('#content_tglmonth1').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	 $('#content_tglmonth2').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	 $('#content_tglmonth3').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	 $('#header_tglmonth').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	  $('#footer_tglmonth').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	  $('#footer_tglmonth2').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	  $('#footer_tglmonth3').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
 }
	startYear =  1960;
	finishYear =2050;
    var currentYear = new Date().getFullYear(),
	xYears = [];
    startYear = startYear;  
    while ( startYear <= finishYear ) {
        xYears.push(startYear++);
    }   
     xYears; 
 
 
 for(x=0;x<xYears.length;x++){
	$('#content_tglyears').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#content_tglyears1').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#content_years2').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#content_years3').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#header_tglyears').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#footer_tglyears').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#footer_tglyears2').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	$('#footer_tglyears3').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
 }
 
 

	
}

function getDay(){
date = new Date().getDate();
	switch (new Date().getDay()) {
  case 0:
    day = "Minggu";
	return day;
    break;
  case 1:
    day = "Senin";
	return day;
    break;
  case 2:
     day = "Selasa";
	 return day;
    break;
  case 3:
    day = "Rabu";
	return day;
    break;
  case 4:
    day = "Kamis";
	return day;
    break;
  case 5:
    day = "Jumat";
	return day;
    break;
  case 6:
    day = "Sabtu";
	return day;
}
}
function getMonth(data){
console.log(data);
	switch (data) {
  case 0:
    months = 0;
    break;
  case 1:
   months = 1;
    break;
  case 2:
        months = 2;
    break;
  case 3:
     months = 3;
    break;
  case 4:
      months = 4;
    break;
  case 5:
       months = 5;
    break;
  case 6:
       months = 6;
	      break;
  case 7:
       months = 7;
	      break;
  case 8:
       months = 8;
	      break;
  case 9:
       months = 9;
	      break;
  case 10:
       months = 11;
	      break;
  case 11:
       months = 11;
	      break;
   
}

years = new Date().getFullYear();
}



	
