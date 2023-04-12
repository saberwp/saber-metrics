class ReportChart {

  constructor(labels, data) {
    this.canvas = document.getElementById('saber-chart');
    this.ctx = this.canvas.getContext('2d');
    this.chart = null;
    this.labels = labels;
		this.data = data;
  }

  renderChart() {
    // If report already exists in canvas, destroy it.
    this.clearCanvas();

    const chartData = {
      labels: this.labels,
      datasets: [{
        label: 'Metric Report',
        data: this.data,
				backgroundColor: 'rgba(31, 41, 55, 0.2)',
		    borderColor: 'rgba(31, 41, 55, 1)',
        borderWidth: 1
      }]
    };

    const chartOptions = {
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
      data: chartData,
      options: chartOptions
    });
  }

  clearCanvas() {
    // Get the canvas element
    const ctx = document.getElementById('saber-chart').getContext('2d');

    // Check if a chart already exists on the canvas
    const existingChart = Chart.getChart(ctx);

    if (existingChart) {
      // Destroy the existing chart if it exists
      existingChart.destroy();
    }
  }
}
