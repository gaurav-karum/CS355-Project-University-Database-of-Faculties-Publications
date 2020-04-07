$(document).ready(function () {
  $.ajax({
    url: "http://localhost:8001/jcontdata.php",
    method: "GET",
    success: function (data) {
      console.log(data);
      var Year = [];
      var Contributions = [];
     

      for (var i in data) {
        Year.push("Year " + data[i].Year);
        Contributions.push(data[i].Contributions);
      }
      var chartdata = {
        labels: Year,
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
          title: {
            display: true,
            text: 'Your Journal Publications Over the Year',
            position: 'top',
            fontSize: 18,
            fontColor: '#666',
            fontStyle: 'bold',
            padding: 10
          },
          scales: {
            yAxes: [{
              ticks: {
                min: 0,
                max: 10,
                maxTicksLimit: 10,
                suggestedMax: 10,
                suggestedMin: 0,
                stepSize: 1
              },
              id: 'first-y-axis',
              type: 'linear'
              
            }],
            legend: false,
            tooltips: false,
            elements: {
              rectangle: {
                backgroundColor: colorize.bind(null, false),
                borderColor: colorize.bind(null, true),
                borderWidth: 2
              }
            }

          }

        }
      });

      setInterval(function(){
        barGraph.options.scales.yAxes[0].ticks.min=0;
        barGraph.options.scales.yAxes[0].ticks.max=10;
        barGraph.options.scales.yAxes[0].ticks.stepSize=1;
        barGraph.render(); 
        //myLiveChart.render(); //not working as well
      }, 1000);
   

    },
    error: function (data) {
      console.log(data);
    }
  });
});