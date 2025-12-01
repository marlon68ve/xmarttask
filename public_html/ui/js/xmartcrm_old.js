$(document).ready(function () {
    $(":file").filestyle();

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
                   $('#session_csrf').val(csrf);
                //if (response.mensaje === 'persona existe') {
                if (response.exists) {
                    $("#alerta").removeClass("alert-success").hide();
                    $("#alerta").addClass("alert alert-danger").fadeIn();                    
                    $('#alerta').html(message);
                    $('#btncreateperson').prop('disabled', true);
                } else {
                    $("#alerta").removeClass("alert-danger").hide();
                    $("#alerta").addClass("alert alert-success").fadeIn();                    
                    $('#alerta').html(message);                    
                    $('#btncreateperson').prop('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('AJAX request failed: ' + errorThrown);
                $('#responseMessage').html('No se pudo agendar la cita. Intentelo mas tarde.');
            }
        });
    });


   
   // $('#btncreateperson').prop('disabled', true);
    $('#btnconsulta').prop('disabled', true);
    $('#guardar').prop('disabled', true);
    $('#btncreateperson').prop('disabled', true);
    $("#alerta").hide();
    
    
});

    $('#my-time-input').timepicker();

const btnnew = document.getElementById('nuevo');
const accionInput = document.getElementById('accion');
const fechconsultaInput = document.getElementById('hfechcita');
const fechcitaInput = document.getElementById('FechCita');
      // Reset event listener
      document.querySelector('form').addEventListener('reset', function() {
        setTimeout(function() {
          $('#guardar').prop('disabled', false);
          $('#MotivoConsulta').prop('disabled', false);
          $('#AntecedentePersonal').prop('disabled', false);
          $('#AntecedenteFamiliar').prop('disabled', false);
          fechcitaInput.value = fechconsultaInput.value;
          accionInput.value = 'nuevo';
        }, 0);
      });

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


    var table = document.getElementById('mytable');
    //const select = document.getElementById('CondicionCita');
    for(var i = 1; i < table.rows.length; i++)
    {
       /* table.rows[i].onclick = function(()
       {
       // $('#btnconsulta').prop('disabled', false);
           //rIndex = this.rowIndex;
           $('#btnupdatetask').prop('disabled', false);
           $('#TaskStatus').prop('disabled', false);
           document.getElementById('task_time').value = this.cells[1].innerHTML;
           document.getElementById("myTaskNum").value = this.cells[6].innerHTML;
            document.getElementById("person_name").value = this.cells[3].innerHTML;
           document.getElementById("person_lname").value = this.cells[4].innerHTML;
           const status = this.cells[5].innerHTML;
           document.getElementById("TaskStatus").value = status;
           document.getElementById('TaskNum').value = this.cells[6].innerHTML;
        };  */
    }



$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});


var hiddenInput = document.getElementById("modal");
var modalValue = hiddenInput.value;
var nPersonPhone = $('#nPersonPhone').val();
var PersonPhoneValue = $('#PersonPhone').val();
const input2 = document.querySelector('#ListCitaForm .fromlisttask');

if (PersonPhoneValue != '' || input2 != '' || nPersonPhone != '') {
    var modal = '';
    if (modalValue === 'fromlisttask') {
        modal = new bootstrap.Modal(document.getElementById('ListTaskModal'));
        modal.show();
    } else if (modalValue === 'newPersonModal') {
        modal = new bootstrap.Modal(document.getElementById('newPersonModal'));
        modal.show();
    } else if (modalValue === 'fromlistper') {
        hiddenInput.value = "";
        var nmodal = new bootstrap.Modal(document.getElementById('TodayTaskModal'));
        nmodal.show();
    }
}

if (modalValue === 'tasksummary') {
    //alert('entro');
    var mmodal = new bootstrap.Modal(document.getElementById('TaskSummaryModal'));
    mmodal.show();
}



// Get all elements with class "auto-close"
const autoCloseElements = document.querySelectorAll(".auto-close");

// Define a function to handle the fading and sliding animation
function fadeAndSlide(element) {
  const fadeDuration = 500;
  const slideDuration = 500;
  
  // Step 1: Fade out the element
  let opacity = 1;
  const fadeInterval = setInterval(function () {
    if (opacity > 0) {
      opacity -= 0.1;
      element.style.opacity = opacity;
    } else {
      clearInterval(fadeInterval);
      // Step 2: Slide up the element
      let height = element.offsetHeight;
      const slideInterval = setInterval(function () {
        if (height > 0) {
          height -= 10;
          element.style.height = height + "px";
        } else {
          clearInterval(slideInterval);
          // Step 3: Remove the element from the DOM
          element.parentNode.removeChild(element);
        }
      }, slideDuration / 10);
    }
  }, fadeDuration / 10);
}

// Set a timeout to execute the animation after 5000 milliseconds (5 seconds)
setTimeout(function () {
  autoCloseElements.forEach(function (element) {
    fadeAndSlide(element);
  });
}, 5000);