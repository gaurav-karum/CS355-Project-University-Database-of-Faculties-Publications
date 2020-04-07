$(document).ready(function () {
  $.ajax({
    url: "http://localhost:8001/pstatusdata.php",
    method: "GET",
    success: function (data) {
      console.log(data);
      var Status = [];
      var Contributions = [];

      for (var i in data) {
        Status.push(data[i].Status);
        Contributions.push(data[i].Contributions);
      }
      var chartdata = {
        labels: Status,
        datasets: [
          {
            label: 'Contributions',
            backgroundColor: '#968c97',
            borderColor: 'rgba(200, 200, 200, 0.75)',
            hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
            hoverBorderColor: 'rgba(200, 200, 200, 1)',
            barThickness: 'flex',
            maxBarThickness: 50,
            minBarLength: 30,
            barPercentage: 1,
            categoryPercentage: 1,
            yAxisID: 'first-y-axis',
            data: Contributions
          }
        ]
      };

      var ctx = $("#mycanvas");

      var barGraph = new Chart(ctx, {
        type: 'bar',
        data: chartdata,
        options: {
          layout: {
            padding: {
              left: 50,
              right: 50,
              top: 25,
              bottom: 25
            }
          },
          legend: {
            display: true,
            labels: {
              fontColor: 'rgb(125, 99, 132)'
            }
          },
          title: {
            display: true,
            text: 'Your Projects',
            position: 'top',
            fontSize: 18,
            fontColor: '#666',
            fontStyle: 'bold',
            padding: 10
          },
          scales: {
            yAxes: [{
              id: 'first-y-axis',
              type: 'linear',
              ticks: {
                min: 0
              }
            }],

          }
          
        }
      });
},
  error: function (data) {
    console.log(data);
  }
  });
});