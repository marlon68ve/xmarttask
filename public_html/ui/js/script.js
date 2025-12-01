$(document).ready(function() {
	$('#taskForm').on('submit', function(e) {
        e.preventDefault();
        var formData1 = $('#taskForm').serialize();
	// Get the value of the time input
	var currentTime = document.getElementById("timepicker1").value;
	// Parse the time value to extract the hours
	var hours = parseInt(currentTime.split(':')[0]);
	// Determine the prefix based on the hour
	var prefix = hours >= 12 ? 'pm' : 'am';
	var minutes = currentTime.substring(3, 5);
  		// Convert to 12-hour format
  		if (hours > 12) {
    	    	    hours -= 12;
  		} else if (hours === 0) {
    	     	    hours = 12;
  		}
  		// Add leading zero to minutes and seconds if needed
  		if (hours < 10) {
    	    	    hours = '0' + hours;
  		}
  		//if (seconds < 10) {
    	    	//    seconds = '0' + seconds;
  		//}
  		// Create the formatted time string
  		//var TaskRegTime = hours + ':' + minutes + ':' + seconds + ' ' + prefix;
		var TaskRegTime = hours + ':' + minutes + ' ' + prefix;
  		//var currentDate = new Date();
		var currentDate = $('#datepicker6').datepicker('getDate');
  		var day = currentDate.getDate();
  		var month = currentDate.getMonth() + 1; // Months are zero-based
  		var year = currentDate.getFullYear();
  		// Add leading zero to day and month if needed
  		if (day < 10) {
    	    	    day = '0' + day;
  		}
  		if (month < 10) {
    	    	    month = '0' + month;
  		}
  		// Create the formatted date string
  		var TaskDate = day + '/' + month + '/' + year;
  		
  		//let EntityNum = $('#Entity_num').val();
  		
		// Concatenate the variables with the serialized form data
		//var formData = formData1 + '&task_time=' + TaskRegTime + '&task_date=' + TaskDate + '&entity_num=' + EntityNum;
		var formData = formData1 + '&task_time=' + TaskRegTime + '&task_date=' + TaskDate;
        $.ajax({
            url: $('base').attr('href') + 'newtask',
            type: 'POST',
            data: formData,
            dataType: 'json',
	        success: function(response) {
	            let source = response.source;
	            let entitynum = response.entitynum;
	            if (source === 'listtasks') {
		            window.location.href = $('base').attr('href') + 'task/listtasks';
	            }else{
		            window.location.href = $('base').attr('href') + 'task/persontask/' + entitynum;	                
	            }
            },
      	    error: function(xhr, textStatus, errorThrown) {
               	console.log('AJAX request failed: ' + errorThrown);
               	alert('No fue posible agendar la tarea. Intentelo mas tarde.');
       	    }
	});

    });

	$('#ListTaskForm').on('submit', function(e) {
        e.preventDefault();
        var formData1 = $('#ListTaskForm').serialize();
	// Get the value of the time input
	var currentTime = document.getElementById("timepicker1").value;
	// Parse the time value to extract the hours
	var hours = parseInt(currentTime.split(':')[0]);
	// Determine the prefix based on the hour
	var prefix = hours >= 12 ? 'pm' : 'am';
	var minutes = currentTime.substring(3, 5);
  		// Convert to 12-hour format
  		if (hours > 12) {
    	    	    hours -= 12;
  		} else if (hours === 0) {
    	     	    hours = 12;
  		}
  		// Add leading zero to minutes and seconds if needed
  		if (hours < 10) {
    	    	    hours = '0' + hours;
  		}
		var TaskRegTime = hours + ':' + minutes + ' ' + prefix;
		var TaskDate = $('#TaskDate').val();
		var formData = formData1 + '&task_time=' + TaskRegTime + '&task_date=' + TaskDate;
        $.ajax({
            url: $('base').attr('href') + 'newtask',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
			var alertType = response.alertType;
                var message = response.message;
			var nTaskDate = TaskDate.replace(/\//g, '-');
                if (message === 'la cita fue creada') {
		    	window.location.href = $('base').attr('href') + 'task/listtask/' + nTaskDate + '/' + message + '/' + alertType;
                } else {
			window.location.href = $('base').attr('href') + 'task/listtask/' + nTaskDate + '/' + message + '/' + alertType;
                }
            },
      	    error: function(xhr, textStatus, errorThrown) {
               	console.log('AJAX request failed: ' + errorThrown);
               	alert('No se pudo agendar la cita. Intentelo mas tarde.');
       	    }
		});
    });
    
    $('#NoteForm').on('submit', function(e) {
        e.preventDefault(); // Prevent actual form submission
        let formData = $(this).serialize(); // Serialize form data
        $.ajax({
            url: $('base').attr('href') + 'note/create',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
			    //let alertType = response.alertType;
                //let message = response.message;
			    //var nTaskDate = TaskDate.replace(/\//g, '-');
			    let entity_num = response.entity_num;
			    let task_num = response.task_num;
			    let type_entity = response.type_entity;
			    $('#NoteTaskModal').modal('hide'); // Hide modal
                if (response.success) {
		    	    window.location.href = $('base').attr('href') + 'task/edit/' + entity_num + '/' + task_num + '/' + type_entity;
                } else {
			        window.location.href = $('base').attr('href') + 'task/edit/' + entity_num + '/' + task_num + '/' + type_entity;
                }
                },
            error: function() {
                // Handle error
                alert('Error saving note');
            }
        });
    });  
    
    $('#EditNoteForm').on('submit', function(e) {
        e.preventDefault(); // Prevent actual form submission
        let formData = $(this).serialize(); // Serialize form data
        $.ajax({
            url: $('base').attr('href') + 'note/edit',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
			    //let alertType = response.alertType;
                //let message = response.message;
			    //var nTaskDate = TaskDate.replace(/\//g, '-');
			    let entity_num = response.entity_num;
			    let task_num = response.task_num;
			    let type_entity = response.type_entity;			    
			    $('#EditNoteModal').modal('hide'); // Hide modal
                if (response.success) {
		    	    //window.location.href = $('base').attr('href') + 'task/edit/' + personnum + '/' + tasknum;
		    	    window.location.href = $('base').attr('href') + 'task/edit/' + entity_num + '/' + task_num + '/' + type_entity;
                } else {
			        //window.location.href = $('base').attr('href') + 'task/edit/' + personnum + '/' + tasknum;
			        window.location.href = $('base').attr('href') + 'task/edit/' + entity_num + '/' + task_num + '/' + type_entity;
                }
                },
            error: function() {
                // Handle error
                alert('Error saving note');
            }
        });
    });     
    
	$('#fromListTaskForm').on('submit', function(e) {
        e.preventDefault();
        var formData1 = $('#fromListTaskForm').serialize();
	    var currentTime = document.getElementById("timepicker1").value;
	    var hours = parseInt(currentTime.split(':')[0]);
	    var prefix = hours >= 12 ? 'pm' : 'am';
	    var minutes = currentTime.substring(3, 5);
  		if (hours > 12) {
    	    	    hours -= 12;
  		} else if (hours === 0) {
    	     	    hours = 12;
  		}
  		if (hours < 10) {
    	    	    hours = '0' + hours;
  		}
		var TaskRegTime = hours + ':' + minutes + ' ' + prefix;
		var currentDate = $('#datepicker6').datepicker('getDate');
  		var day = currentDate.getDate();
  		var month = currentDate.getMonth() + 1; // Months are zero-based
  		var year = currentDate.getFullYear();
  		if (day < 10) {
    	    	    day = '0' + day;
  		}
  		if (month < 10) {
    	    	    month = '0' + month;
  		}
  		var csrfToken = $('#session_csrf').val();
  		var TaskDate = day + '/' + month + '/' + year;
		
		//let person_num = $('#person_num').val();
		let person_phone = $('#person_phone').val();
		//let csrfToken = $('#session_csrf1').val();
		//let formData = '&task_time=' + TaskRegTime + '&task_date=' + TaskDate + '&person_num=' + person_num + '&person_phone=' + person_phone + '&csrfToken=' + csrfToken;
		let formData = formData1 + '&task_time=' + TaskRegTime + '&task_date=' + TaskDate + '&person_phone=' + person_phone + '&csrfToken=' + csrfToken;
        $.ajax({
            url: $('base').attr('href') + 'newtask',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log(response); // Debug response
			    var alertType = response.alertType;
                var message = response.message;
			    var nTaskDate = TaskDate.replace(/\//g, '-');
                if (message === 'la cita fue creada') {
		    	    window.location.href = $('base').attr('href') + 'task/listtask/' + nTaskDate + '/' + message + '/' + alertType;
                } else {
			        window.location.href = $('base').attr('href') + 'task/listtask/' + nTaskDate + '/' + message + '/' + alertType;
                }
            },
      	    error: function(xhr, textStatus, errorThrown) {
               	console.log('AJAX request failed: ' + errorThrown);
               	alert('No se pudo agendar la cita. Intentelo mas tarde.');
       	    }
	});

    });    
});
