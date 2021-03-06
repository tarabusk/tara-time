
function TaraTime_UpdateTime(cnty_heure, cnty_min, widget_id, time_format, date_format) {
    var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"),
	ampm = " AM",
	now_serveur = new Date(),
	hours_serveur = now_serveur.getHours(),
	minutes_serveur = now_serveur.getMinutes(),	
	seconds = now_serveur.getSeconds(),	
	$date = jQuery("#" + widget_id + " .tara-date"),
	$time = jQuery("#" + widget_id + " .tara-time");
	//alert ("zz : "+widget_id);
	if (cnty_heure!=''){	
	  diff_secondes = (cnty_heure*60*60+cnty_min*60+seconds) - (hours_serveur*60*60+minutes_serveur*60+seconds);
	}
	var now_ctry=new Date();
    now_ctry.setSeconds  (now_serveur.getHours()+diff_secondes);
	hours = now_ctry.getHours();
	minutes = now_ctry.getMinutes();	
	
    //Date
    if (date_format != "none") {
	var currentTime = new Date(),
	    year = currentTime.getFullYear(),
	    month = currentTime.getMonth(),
	    day = currentTime.getDate();
	    
	if (date_format == "long") {
	    $date.text(months[month] + " " + day + ", " + year);
	}
	else if (date_format == "medium") {
	    $date.text(months[month].substring(0, 3) + " " + day + " " + year);
	}
	else if (date_format == "short") {
	    $date.text((month + 1) + "/" + day + "/" + year);
	}
	else if (date_format == "european") {
	    $date.text(day + "/" + (month + 1) + "/" + year);
	}
    }	
    
    //Time
    if (time_format != "none") {
	if (hours >= 12) {
	    ampm = " PM";
	}
		
	if (minutes <= 9) {
	    minutes = "0" + minutes;
	}
	   
	if (seconds <= 9) {
	    seconds = "0" + seconds;
	}
	    
	if ((time_format == "12-hour") || (time_format == "12-hour-seconds")) {
	    if (hours > 12) {
		hours = hours - 12;
	    }
	    
	    if (hours == 0) {
		hours = 12;
	    }
	    
	    if (time_format == "12-hour-seconds") {
		$time.text(hours + ":" + minutes + ":" + seconds + ampm);
	    }
	    else {
		$time.text(hours + ":" + minutes + ampm);
	    }
	}
	else if (time_format == "24-hour-seconds") {
	    $time.text(hours + ":" + minutes  + ":" + seconds);
	}
	else {
	    $time.text(hours + ":" + minutes);
	}
    }
	
    //Update clock every second.
    if ((date_format != "none") || (time_format != "none")) {
		setTimeout(function() {
			TaraTime_UpdateTime('', '', widget_id, time_format, date_format);
		}, 1000);
    }
}