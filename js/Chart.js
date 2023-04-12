class MyChart {
  constructor() {
    this.canvas = document.getElementById('saber-chart');
    this.ctx = this.canvas.getContext('2d');
    this.chart = null;
  }

  renderChart() {
    const data = {
      labels: labels,
      datasets: [{
        label: 'Metric Report',
        data: saberMetricsChartData,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    };

    const options = {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    };

    if (this.chart) {
      this.chart.destroy();
    }

    this.chart = new Chart(this.ctx, {
      type: 'bar',
      data: data,
      options: options
    });
  }
}

const myChart = new MyChart();
myChart.renderChart();
