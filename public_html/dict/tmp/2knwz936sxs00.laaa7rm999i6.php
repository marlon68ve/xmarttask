        </div>  <!-- FIN PRIMERA fila de la pagina completa -->
    </div>
    <!-- FIN del contenedor principal de la pagina completa -->

    <!-- 
    <nav class="mobile-nav">
        <a href="page/homepage" class="bloc-icon">
            <img src="ui/img/home.svg" alt="">
        </a>
        <a href="#" class="bloc-icon">
            <img src="ressources/magnifying-glass.svg" alt="">
        </a>
        <a href="#" class="bloc-icon">
            <img src="ui/img/people.svg" alt="">
        </a>
        <a href="#" class="bloc-icon">
            <img src="ui/img/settings.svg" alt="">
        </a>
    </nav>
    -->

<!--  Conffeti Effect -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

  <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script> -->
   <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js" integrity="sha512-5pjEAV8mgR98bRTcqwZ3An0MYSOleV04mwwYj2yw+7PBhFVf/0KcE+NEox0XrFiU5+x5t5qidmo5MgBkDD9hEw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--  Generador de Graficas -->
<script src="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


<!--   <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.4/chartist.min.js" integrity="sha512-9rxMbTkN9JcgG5euudGbdIbhFZ7KGyAuVomdQDI9qXfPply9BJh0iqA7E/moLCatH2JD4xBGHwV6ezBkCpnjRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
-->   
   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>

   <script src="ui/js/xmartcrm.js?v=1.3"></script>
   <script src="ui/js/script.js?v=1.4"></script>
   <script src="ui/js/script1.js?v=1.2"></script>
    <!-- Esta linea permite modificar el texto del boton "Browse" del input type="image" -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js" integrity="sha512-HfRdzrvve5p31VKjxBhIaDhBqreRXt4SX3i3Iv7bhuoeJY47gJtFTRWKUpjk8RUkLtKZUhf87ONcKONAROhvIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script type="text/javascript">
/* function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
}

window.onload = timedRefresh(20000);

// ** Efectuar el play del sonido que esta en el layout **
  window.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('notification-sound').play();
  });

 */

const data = {
    labels: ['Active', 'Done'],
    datasets: [{
        data: [70, 30],
        backgroundColor: ['rgb(255, 205, 86)', 'rgb(54, 162, 235)'],
        hoverOffset: 4
    }]
};

const options = {
    plugins: {
        legend: {
            position: 'bottom'
        },
        title: {
            display: true,
            text: 'Status Distribution'
        },
        datalabels: {
            formatter: (value, ctx) => {
                const dataArr = ctx.chart.data.datasets[0].data;
                const total = dataArr.reduce((sum, data) => sum + data, 0);
                return ((value / total) * 100).toFixed(2) + '%';
            },
            color: '#fff',
            font: {
                weight: 'bold'
            }
        }
    }
};


  
  //Charts
  const ctx1 = document.getElementById('progressChart').getContext('2d');
  const ctx2 = document.getElementById('productivityChart').getContext('2d');

  new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: ['Completed', 'In Progress', 'Not Started'],
      datasets: [{
        data: [60, 30, 10],
        backgroundColor: ['#4caf50', '#ffeb3b', '#f44336'],
      }]
    }
  });

  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: ['Alice', 'Bob', 'Charlie'],
      datasets: [{
        label: 'Tasks Completed',
        data: [12, 19, 3],
        backgroundColor: '#2196f3',
      }]
    }
  });




// Initialize the Doughnut Chart
const ctx = document.getElementById('budget-chart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data,
    options,
    plugins: [ChartDataLabels]
});



if ('<?= ($taskFrequencies) ?>') {
    let taskFrequencies = "<?= ($taskFrequencies) ?>";
    let taskF = taskFrequencies.replace(/&quot;/g, '"');
    let taskFrequenciesObject;

    try {
        // Parse the JSON string into an object
        taskFrequenciesObject = JSON.parse(taskF);
        
        // Extract keys and values
        const labels = Object.keys(taskFrequenciesObject);
        const dataValues = Object.values(taskFrequenciesObject);

        // Create the chart
        const ctx = document.getElementById('task1-chart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut', // Specify Doughnut chart
            data: {
                labels: labels,
                datasets: [{
                    label: 'Task Frequencies',
                    data: dataValues,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                let value = tooltipItem.raw;
                                return `${tooltipItem.label}: ${value}`;
                            }
                        }
                    }
                },
                layout: {
                    padding: 10
                }
            }
        });
    } catch (e) {
        console.error("Failed to parse JSON string: ", e);
    }
}


  </script>
</body>
</html>