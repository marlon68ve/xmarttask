$(document).ready(function () {
 /*   $(window).on('beforeunload', function() {
      $('#UserModal').modal('hide'); // Hide the modal
    });*/

    // Busqueda dinamica en una tabla.
    $(".search").keyup(function () {
        var searchTerm = $(".search").val();
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('");

        $.extend($.expr[':'], {'containsi': function (elem, i, match, array) {
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
            });
        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function (e) {
            $(this).attr('visible', 'false');
        });
        $(".results tbody tr:containsi('" + searchSplit + "')").each(function (e) {
            $(this).attr('visible', 'true');
        });
        var jobCount = $('.results tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' pacientes');
        if (jobCount === '0') {$('.no-result').show();
        } else {$('.no-result').hide(); }
    });
    
    // Verifica si valor ingresado en un input box existe en BD, al abandonar el input box
    $('#person_phone').blur(function() {
        //e.preventDefault();
        var formData = $('#creaperson').serialize();
        var person_phoneValue = $('#person_phone').val();
        var csrfToken = $('#session_csrf').val();
        formData += '&csrfToken=' + csrfToken;
        $.ajax({
            url: $('base').attr('href') + 'verifyperson',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                var message = response.message;
                var csrf = response.csrf;
                let person_num = response.person_num;                
                let person_phone = response.person_phone;
                let person_name = response.person_name;
                let person_lname = response.person_lname;
                let person_dob = response.person_dob;
                let person_gender = response.person_gender;
                let person_maritals = response.person_maritals;
                let person_addr = response.person_addr;
                   $('#session_csrf').val(csrf);
                //if (response.mensaje === 'persona existe') {
                if (response.exists) {
                    //$("#alerta").removeClass("alert-success").hide();

		    	    //window.location.href = $('base').attr('href') + 'person/addperson/' + person_phone;
                   
                    $("#alerta").removeClass('hide');
                    $("#alerta").addClass("alert alert-danger").fadeIn();                    
                    $("#alerta").html(message);
                    document.getElementById("person_num").value = person_num;                    
                    document.getElementById("nperson_phone").value = person_phone;
                    document.getElementById("person_name").value = person_name;
                    document.getElementById("person_lname").value = person_lname;
                    document.getElementById("person_dob").value = person_dob;
                    document.getElementById("person_gender").value = person_gender;
                    document.getElementById("person_maritals").value = person_maritals;
                    document.getElementById("person_addr").value = person_addr; 
                    $('#btncreateperson').prop('disabled', false);
                    $('#person_name').prop('disabled', true);
                    $('#person_lname').prop('disabled', true);
                    $('#person_dob').prop('disabled', true);
                    $('#person_gender').prop('disabled', true);
                    $('#person_maritals').prop('disabled', true);
                    $('#person_addr').prop('disabled', true);
                    
                } else {
		    	    //window.location.href = $('base').attr('href') + 'person/addperson/' + person_phone;                    
                    //$("#alerta").removeClass("alert-danger").hide();
                    document.getElementById("nperson_phone").value = '';
                    document.getElementById("person_name").value = '';
                    document.getElementById("person_lname").value = '';
                    document.getElementById("person_dob").value = '';
                    document.getElementById("person_gender").value = '';
                    document.getElementById("person_maritals").value = '';
                    document.getElementById("person_addr").value = '';                     
                    $("#alerta").removeClass('hide');
                    $("#alerta").addClass("alert alert-success").fadeIn();
                    $("#alerta").html(message);
                    $('#btncreateperson').prop('disabled', false);
                }
                setTimeout(function() {
                    $("#alerta").alert('close');
                }, 6000);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('AJAX request failed: ' + errorThrown);
                $('#responseMessage').html('No se pudo agendar la cita. Intentelo mas tarde.');
            }
        });
    });

    // Verifica si valor ingresado en un input box existe en BD, al pulsar el boton
    $('#btnFindPerson').click(function() {
        //e.preventDefault();
        let phoneNumber = $('#searchInput').val();
        let datepicker_date = $('#date').val();  // obtener la fecha que indica el datepicker, esta en el input hidden id='date'
        $.ajax({
            url: $('base').attr('href') + 'checkPersonExists',
            type: 'POST',
            data: {
                phone: phoneNumber,
                date: datepicker_date
                },
            dataType: 'json',
            success: function(response) {
                let message = response.message;
                console.log(message);
                if (response.exists) {
                    let p_name = document.getElementById("p_name");
                    $('#person_name').val(response.person_name);
                    $('#person_lname').val(response.person_lname);
                    $('#person_age').val(response.person_age);
                    $('#person_addr').val(response.person_addr);
                    $('#person_phone').val(response.person_phone);
                    $('#person_num').val(response.person_num);
                    $('#session_csrf1').val(response.csrf);                    
                    $('#dp_date').val($('#date').val());
                    $('#TaskDate').val($('#date').val());
                    //$("#alerta").removeClass("alert-danger").hide();
                    //$("#alerta").addClass("alert alert-success").fadeIn();                    
                    //$('#alerta').html(message);
                    modal = new bootstrap.Modal(document.getElementById('person-modal'));
                    modal.show();
                } else {
                    $(".alert").removeClass("alert-success").hide();
                    $(".alert").addClass("alert alert-danger").fadeIn();                    
                    $('.alert').html(message);                    
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking user existence:', error);
            }
        });
    });
 
        // CHARTS del homepage
$.ajax({
    url: '/task/taskData',
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        // Check if data is valid
        if (response && response.labels && response.counts) {
            const total = response.counts.reduce((sum, count) => sum + count, 0);

            // Define colors based on task status
            const statusColors = {
                'Completed': '#4caf50',   // Green
                'In Progress': '#ffeb3b', // Yellow
                'Delayed': '#f44336',     // Red
                'Not Started': '#2196F3'  // Blue
            };

            // Map labels to predefined colors
            const backgroundColors = response.labels.map(label => statusColors[label] || '#ccc'); // Default gray if missing

            // Initialize the chart
            const ctx4 = document.getElementById('taskChart').getContext('2d');
            new Chart(ctx4, {
                type: 'pie',
                data: {
                    labels: response.labels, // Task types
                    datasets: [{
                        data: response.counts, // Counts
                        backgroundColor: backgroundColors, // Use predefined colors
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value) => {
                                // Calculate the percentage
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${percentage}%`; // Display percentage
                            },
                            font: {
                                weight: 'bold',
                                size: 14,
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // Register the DataLabels plugin
            });

        } else {
            console.error('Invalid response data:', response);
        }
    },
    error: function (xhr, status, error) {
        console.error('Failed to fetch task data:', error);
    }
});


$.ajax({
   url: '/task/taskDistribution',
   method: 'GET',
   dataType: 'json',
   success: function (response) {
      if (response && response.labels && response.counts) {
         const total = response.counts.reduce((sum, count) => sum + count, 0);
         const ctx = document.getElementById('taskDistributionChart').getContext('2d');
         
         // Ensure minimum height for the chart container
         let dynamicHeight = Math.max(50 * response.labels.length, 150);
         document.getElementById('taskDistributionChart').parentElement.style.height = `${dynamicHeight}px`;

         // Generate dynamic colors based on the number of labels
         const generateColors = (num) => {
            return Array.from({ length: num }, (_, index) => `hsl(${index * (360 / num)}, 70%, 50%)`);
         };
         const backgroundColors = generateColors(response.labels.length);

         // Create the chart
         new Chart(ctx, {
            type: 'bar',
            data: {
               labels: response.labels, // Task Types
               datasets: [{
                  label: response.projectType + ' Task Distribution',
                  data: response.counts,
                  backgroundColor: backgroundColors,
               }]
            },
            options: {
               indexAxis: 'y', // Makes the bar chart horizontal
               responsive: true,
               maintainAspectRatio: false, // Allows chart to expand vertically
               plugins: {
                  legend: { display: true, position: 'top' },
                  title: { display: true, text: response.projectType + ' Task Distribution' },
                  datalabels: {
                     color: '#fff',
                     formatter: (value) => ((value / total) * 100).toFixed(1) + '%',
                     font: { weight: 'bold', size: 8 } // Adjusted font size for smaller charts
                  }
               },
               scales: {
                  x: { beginAtZero: true },
                  y: {
                     ticks: { font: { size: response.labels.length > 12 ? 10 : 12 } } // Reduce font size for larger datasets
                  }
               }
            }
         });
      } else {
         console.error('Invalid response data:', response);
      }
   },
   error: function (xhr, status, error) {
      console.error('Failed to fetch task distribution data:', error);
   }
});


let userNotesChart; // Store chart instance globally

$(document).ready(function () {
    fetchUserNotesChart();
});

function fetchUserNotesChart() {
    $.ajax({
        url: '/task/userNotesCount',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response && response.labels && response.activeNotes && response.doneNotes) {
                const maxLabels = 16;
                let labels = response.labels.slice(0, maxLabels);
                let activeNotes = response.activeNotes.slice(0, maxLabels);
                let doneNotes = response.doneNotes.slice(0, maxLabels);
                const total = activeNotes.map((val, i) => val + doneNotes[i]);
                const canvas = document.getElementById('userNotesChart');
                const ctx = canvas.getContext('2d');

                // Ensure minimum height
                let dynamicHeight = Math.max(50 * labels.length, 150);
                if (labels.length < 3) {
                    dynamicHeight = 200;
                }
                canvas.parentElement.style.height = `${dynamicHeight}px`;

                // Destroy previous chart instance
                if (window.userNotesChart instanceof Chart) {
                    window.userNotesChart.destroy();
                }

                // Create new stacked bar chart
                window.userNotesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Active Actions',
                                data: activeNotes,
                                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            },
                            {
                                label: 'Done Actions',
                                data: doneNotes,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                            title: { display: true, text: 'Tasks Actions (Active vs Done)' },
                        },
                        scales: {
                            x: { stacked: true, beginAtZero: true },
                            y: { stacked: true, ticks: { font: { size: labels.length > 12 ? 10 : 12 } } }
                        }
                    }
                });
            } else {
                console.error('Invalid response data:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch user notes count:', error);
        }
    });
}




let productivityChart; // Store chart instance globally

$(document).ready(function () {
    fetchProductivityChart();
});

function fetchProductivityChart() {
    $.ajax({
        url: '/task/teamProductivity',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response && response.labels && response.counts) {
                const maxLabels = 16;
                let labels = response.labels.slice(0, maxLabels);
                let counts = response.counts.slice(0, maxLabels);

                const total = counts.reduce((sum, count) => sum + count, 0);
                const canvas = document.getElementById('productivityChart');
                const ctx6 = canvas.getContext('2d');

                // Ensure minimum height
                let dynamicHeight = Math.max(50 * labels.length, 150);

                // Force a higher minimum height for small datasets
                if (labels.length < 3) {
                    dynamicHeight = 200; // This ensures a decent bar height even for small datasets
                }

                canvas.parentElement.style.height = `${dynamicHeight}px`;

                // If only one label, add a dummy invisible one
                if (labels.length === 1) {
                    labels.push(' ');
                    counts.push(0);
                }

                // Generate dynamic colors
                const generateColors = (num) => {
                    return Array.from({ length: num }, () => `hsl(${Math.floor(Math.random() * 360)}, 70%, 50%)`);
                };
                const backgroundColors = generateColors(labels.length);

                // Adjust font size dynamically
                const fontSize = labels.length > 12 ? 10 : 12;
                const dataLabelFontSize = labels.length > 12 ? 7 : 8;

                // Destroy previous chart instance
                if (window.productivityChart instanceof Chart) {
                    window.productivityChart.destroy();
                }

                // Create the new chart
                window.productivityChart = new Chart(ctx6, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Conpleted Tasks',                            
                            data: counts,
                            backgroundColor: backgroundColors,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false, position: 'top' },
                            title: { display: true, text: 'Completed Tasks' },
                            datalabels: {
                                color: '#fff',
                                formatter: (value) => ((value / total) * 100).toFixed(1) + '%',
                                font: { weight: 'bold', size: dataLabelFontSize }
                            }
                        },
                        scales: {
                            x: { beginAtZero: true },
                            y: {
                                ticks: { font: { size: fontSize } },
                                suggestedMin: 0,
                                suggestedMax: Math.max(...counts, 1)
                            }
                        }
                    }
                });
            } else {
                console.error('Invalid response data:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch task distribution data:', error);
        }
    });
}




function updateNoteStatus(noteId, newStatus) {
    $.ajax({
        url: '/note/updateStatus',
        method: 'POST',
        data: { note_num: noteId, status: newStatus },
        dataType: 'json',        
        success: function(response) {
            if (response.success) {
                let activeCount = parseInt($("#active-count").text(), 10);
                let doneCount = parseInt($("#done-count").text(), 10);

                // Find the note element
                let noteElement = $("#note-" + noteId);

                if (newStatus === 1) {
                    // Moving from Active to Done
                    $("#simple-tabpanel-1").append(noteElement);
                    $("#done-count").text(doneCount + 1);
                    $("#active-count").text(activeCount - 1);
                } else {
                    // Moving from Done to Active
                    $("#simple-tabpanel-0").append(noteElement);
                    $("#active-count").text(activeCount + 1);
                    $("#done-count").text(doneCount - 1);
                }

                // Update checkbox state visually
                noteElement.find("input[type=checkbox]").prop("checked", newStatus === 1);
            } else {
                console.error("Failed to update note status");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error updating note status:", error);
        }
    });
}

// Event Listener for Checkboxes
$(document).on("change", ".note-checkbox", function() {
    let noteId = $(this).data("note-id");
    let newStatus = $(this).is(":checked") ? 1 : 0;
    updateNoteStatus(noteId, newStatus);
});






   // $('#btncreateperson').prop('disabled', true);
    $('#btnconsulta').prop('disabled', true);
    $('#guardar').prop('disabled', true);
    $('#btncreateperson').prop('disabled', true);
   // $("#alerta").hide();


let modalValue = $('#modal').val();
let nPersonPhone = $('#nPersonPhone').val();
let PersonPhoneValue = $('#PersonPhone').val();
const input2 = document.querySelector('#ListCitaForm .fromlisttask');

if (PersonPhoneValue != '' || input2 != '' || nPersonPhone != '') {

    let modal = '';
    if (modalValue === 'fromlisttask') {
        modal = new bootstrap.Modal(document.getElementById('ListTaskModal'));
        modal.show();
    } else if (modalValue === 'newPersonModal') {
        modal = new bootstrap.Modal(document.getElementById('newPersonModal'));
        modal.show();
    } else if (modalValue === 'fromlistper') {
        let nmodal = new bootstrap.Modal(document.getElementById('TodayTaskModal'));
        nmodal.show();
    }
}


if (modalValue === 'tasksummary') {
    //alert('entro');
    var cmodal = new bootstrap.Modal(document.getElementById('TaskSummaryModal'));
    cmodal.show();
}

if (modalValue === 'fromlistusr') {
    cmodal = new bootstrap.Modal(document.getElementById('UserModal'));
    cmodal.show();
}

if (modalValue === 'fromUpdateProject') {
    //alert('entro');
    cmodal = new bootstrap.Modal(document.getElementById('UserModal'));
    cmodal.show();
}

if (modalValue === 'taskproject') {
    //alert('entro');
    cmodal = new bootstrap.Modal(document.getElementById('CreateTaskModal'));
    cmodal.show();
}
if (modalValue === 'editnote') {
    //alert('entro');
    cmodal = new bootstrap.Modal(document.getElementById('EditNoteModal'));
    cmodal.show();
}

});
// ********************************************************************************
    $('#my-time-input').timepicker();

//  Este es el que aparece en pantalla de pc/laptop
$('#datepick2').datepicker();
   $('#datepick2').on('changeDate', function(e) {
        // `e` here contains the extra attributes
	var vTaskDate = $('#datepick2').datepicker('getDate');
        var TaskDate = ("0" + vTaskDate.getDate()).slice(-2) + "-" + ("0" + (vTaskDate.getMonth() + 1)).slice(-2) + "-" + vTaskDate.getFullYear();
        window.document.location='task/listtask/' + TaskDate;
    });

    // Initialize datepicker component. Este es el que se asocia al input type='text' id='date' que se usa en pantalla de smartphone
    $('.datepicker1').datepicker({
      todayHighlight: true,
      autoclose: true // Automatically close datepicker after selecting a date
    });

    // When a date is selected, update the alert message
    $('.datepicker1').on('changeDate', function(e) {
        // `e` here contains the extra attributes
	var vTaskDate = $('#datepicker1').datepicker('getDate');
        var TaskDate = ("0" + vTaskDate.getDate()).slice(-2) + "-" + ("0" + (vTaskDate.getMonth() + 1)).slice(-2) + "-" + vTaskDate.getFullYear();
        window.document.location='task/listtask/' + TaskDate;
    });

    // Initialize datepicker component. Este es el que se asocia al input type='text' id='date' que se usa en pantalla de smartphone
    $('.datepicker6').datepicker({
      todayHighlight: true,
      autoclose: true // Automatically close datepicker after selecting a date
    });

    // Initialize datepicker component
    $('.datepicker3').datepicker({
      autoclose: true // Automatically close datepicker after selecting a date
    });

    // Initialize datepicker component
    $('.datepicker4').datepicker({
      autoclose: true // Automatically close datepicker after selecting a date
    });


$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

// NOTIFICATION on the fly

// COUNTDOWN TIMER
// Set the date we're counting down to
//var countDownDate = new Date("Jan 5, 2030 15:37:25").getTime();
// Update the count down every 1 second
var x = setInterval(function() {
// El elemento demo esta ubicado en el archivo nav.htm. Por alguna razon la notificacion lo usa
// como referencia para desplegar el Bootstrap Toast de notificacion
 document.getElementById("demo").innerHTML = " "; 
// ********************************
// Fetch the base URL from the <base> tag
        // Notifications
    fetch('/notification/fetch1', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Check if data is valid
            if (data && data.message && data.created_at) {
                if (data.notif_found == 1) {
                    displayNotification(data.message);

                    // Optionally mark notifications as read after displaying
                    markNotificationsAsRead();
                }
            } else {
                console.error('Invalid response data:', data);
            }
        })
        .catch(error => {
            console.error('Failed to fetch notifications:', error);
        });

//************************  
}, 6000);  // revisa notificaciones cada 10 minutos

// Function to mark notifications as read
function markNotificationsAsRead() {
    fetch('/notification/mark_as_read', { // NOTA: le puse el 1 al final para inhabilitarlo. Quitarlo para que vuleva a funcionar
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
        })
        .catch(error => {
            console.error('Failed to mark notifications as read:', error);
        });
}
   // Invite user to the project
 function displayNotification(message) {
    // Check if a toast container already exists, otherwise create one
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    // Create the toast
    const notification = document.createElement('div');
    notification.className = 'toast align-items-center text-white bg-danger border-0';
    notification.role = 'alert';
    notification.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    // Append the toast to the container
    toastContainer.appendChild(notification);
    // Initialize and show the toast
    const toast = new bootstrap.Toast(notification);
    toast.show();
    // Optionally, remove the toast after it's hidden
    notification.addEventListener('hidden.bs.toast', () => {
        notification.remove();
    });
}


// End NOTIFICATION on the fly



// CONFETTI EFFECT Wait for the DOM to fully load
// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", function () {
    const homeModal = document.getElementById("homeModal");
    const closeButton = homeModal?.querySelector("#close-btn");

    // Check if the homeModal exists in the DOM
    if (homeModal) {
        // Show the modal when the page loads
        homeModal.classList.add("show");

        // Trigger confetti effect when the modal is displayed
        launchConfetti();

        // Close the modal when the "Close" button is clicked
        closeButton.addEventListener("click", function () {
            homeModal.classList.remove("show");
        });
    }

    // Function to launch confetti
    function launchConfetti() {
        const duration = 5 * 1000; // Confetti duration (5 seconds)
        const end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 3,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
            });
            confetti({
                particleCount: 3,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        })();
    }
    
});