!(function (NioApp) {
  "use strict";

  let lineChart = {
    labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
    legend: true,
    lineTension:.4,
    datasets : [
        {
          label : "Total Received",
          color: NioApp.Colors.primary,
          data: [75, 90, 110, 80, 125, 55, 95]
      },{
          label : "Total Send",
          color: NioApp.Colors.yellow,
          data: [80, 60, 80, 54, 105, 120, 82]
      }
    ]
  }

  let lineChartFilled = {
    labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    legend: true,
    lineTension:.4,
    datasets : [
        {
          label : "Total Received",
          color: NioApp.Colors.primary,
          background: NioApp.hexRGB(NioApp.Colors.primary,.2),
          data: [110, 80, 125, 65, 95, 75, 90, 110, 80, 125, 70, 95]
      }
    ]
  }

  let lineChartStraight = {
    labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    legend: true,
    lineTension:0,
    datasets : [
        {
          label : "Total Received",
          color: NioApp.Colors.primary,
          background: NioApp.hexRGB(NioApp.Colors.primary,.2),
          data: [110, 80, 125, 65, 95, 75, 90, 110, 80, 125, 70, 95]
      }
    ]
  }

  let barChart = {
    labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    legend: true,
    datasets: [
      {
          color: NioApp.Colors.primary,
          background: NioApp.hexRGB(NioApp.Colors.primary,.2),
          border: 1,
          label: "Bar data",
          data: [60, 49, 72, 90, 100, 60, 70, 90, 50, 80, 90, 60]
      },]
  }

  let barChartMultiple = {
    labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    legend: true,
    datasets: [
      {
          color: NioApp.Colors.primary,
          background: NioApp.Colors.primary,
          border: 1,
          label: "Income",
          data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95]
      },
      {
          color: NioApp.Colors.yellow,
          background: NioApp.Colors.yellow,
          border: 1,
          label: "Expense",
          data: [75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125]
      }
    ]
  }

  let barChartStacked = {
    labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    legend: true,
    stacked: true,
    datasets: [
      {
          color: NioApp.Colors.primary,
          background: NioApp.Colors.primary,
          border: 1,
          label: "Income",
          data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95]
      },
      {
          color: NioApp.Colors.yellow,
          background: NioApp.Colors.yellow,
          border: 1,
          label: "Expense",
          data: [75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125]
      }
    ]
  }

  let pieChart = {
    labels : ["Send", "Receive", "Withdraw"],
    datasets : [{
        background: [NioApp.Colors.orange, NioApp.Colors.blue, NioApp.Colors.green],
        data: [110, 80, 125]
    }],
  }

  let doughnutChart = {
    labels : ["Send", "Receive", "Withdraw"],
    datasets : [{
        background: [NioApp.Colors.orange, NioApp.Colors.blue, NioApp.Colors.green],
        data: [110, 80, 125]
    }],
  }

  let polarChart = {
    labels : ["Send", "Receive", "Withdraw"],
    datasets : [{
        background: [NioApp.Colors.orange, NioApp.Colors.blue, NioApp.Colors.green],
        data: [110, 80, 125]
    }],
  }

//chart 
NioApp.Chart = {
  tooltip : {
    rtl: NioApp.State.isRTL,
    textDirection: NioApp.State.isRTL ? 'rtl' : 'ltr',
    padding:12,
    boxWidth:10,
    boxHeight:10,
    boxPadding:6,
    backgroundColor: NioApp.Colors.gray100,
    titleColor: NioApp.Colors.gray900,
    bodyColor: NioApp.Colors.bodyColor,
  },
  legend : {
    rtl: NioApp.State.isRTL,
    position: 'top',
    labels: {
      boxWidth:12,
      boxHeight:12,
      fontColor: NioApp.Colors.bodyColor,
      padding:10,
    }
  },
  line : function(selector,data) {
    let elm = document.querySelectorAll(selector);
    elm.forEach(item => {
      let chartId = item.id;
      let setData = (typeof data === 'undefined') ? eval(chartId) : data;
      let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
      let chartData = [];
      for (let i = 0; i < setData.datasets.length; i++) {
        chartData.push({
            label: setData.datasets[i].label,
            tension:setData.lineTension,
            backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
            borderWidth:2,
            borderColor: setData.datasets[i].color,
            pointBorderColor: setData.datasets[i].color,
            pointBackgroundColor: NioApp.Colors.white,
            pointHoverBackgroundColor: NioApp.Colors.white,
            pointHoverBorderColor: setData.datasets[i].color,
            pointBorderWidth: 2,
            pointHoverRadius: 4,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
            pointHitRadius: 4,
            fill:true,
            data: setData.datasets[i].data
        });
      } 
  
      let canvas = document.getElementById(chartId).getContext("2d");
      let chart = new Chart(canvas, {
        type:'line',
        data:{ 
          labels : setData.labels,
          datasets : chartData
        },
        options: {
          plugins: {
            legend: {
              display: chartLegend,
              ...NioApp.Chart.legend,
            },
            tooltip: {
              enabled:true,
              ...NioApp.Chart.tooltip,
            },
          },
          interaction: {
            mode: 'nearest',
          },
          responsive: true,
          maintainAspectRatio:false,
        }
      });
    });
  },
  bar : function(selector,data) {
    let elm = document.querySelectorAll(selector);
    elm.forEach(item => {
      let chartId = item.id;
      let setData = (typeof data === 'undefined') ? eval(chartId) : data;
      let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
      let chartStacked = (typeof setData.stacked === 'undefined') ? false : setData.stacked;
      let chartData = [];
  
      for (let i = 0; i < setData.datasets.length; i++) {
        chartData.push({
            label: setData.datasets[i].label,
            backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
            borderWidth:(typeof setData.datasets[i].border === 'undefined') ? 1 : setData.datasets[i].border,
            borderColor: setData.datasets[i].color,
            data: setData.datasets[i].data,
        });
      } 
  
      let canvas = document.getElementById(chartId).getContext("2d");
      let chart = new Chart(canvas, {
        type:'bar',
        data:{ 
          labels : setData.labels,
          datasets : chartData
        },
        options: {
          plugins: {
            legend: {
              display: chartLegend,
              ...NioApp.Chart.legend,
            },
            tooltip: {
              enabled:true,
              ...NioApp.Chart.tooltip,
            },
          },
          interaction: {
            mode:chartStacked ? 'index' : 'nearest',
          },
          responsive: true,
          maintainAspectRatio:false,
          scales: {
            x: {
              stacked: chartStacked,
            },
            y: {
              stacked: chartStacked
            }
          }
        }
      });
    });
  },
  pie : function(selector,data) {
    let elm = document.querySelectorAll(selector);
    elm.forEach(item => {
      let chartId = item.id;
      let setData = (typeof data === 'undefined') ? eval(chartId) : data;
      let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
      let chartData = [];
  
      for (let i = 0; i < setData.datasets.length; i++) {
        chartData.push({
            label: setData.datasets[i].label,
            backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
            borderColor: setData.datasets[i].color,
            data: setData.datasets[i].data,
        });
      } 
  
      let canvas = document.getElementById(chartId).getContext("2d");
      let chart = new Chart(canvas, {
        type:'pie',
        data:{ 
          labels : setData.labels,
          datasets : chartData
        },
        options: {
          plugins: {
            legend: {
              display: chartLegend,
              ...NioApp.Chart.legend,
            },
            tooltip: {
              enabled:true,
              ...NioApp.Chart.tooltip,
            },
          },
          responsive: true,
          maintainAspectRatio:false,
        }
      });
    });
  },
  doughnut : function(selector,data) {
    let elm = document.querySelectorAll(selector);
    elm.forEach(item => {
      let chartId = item.id;
      let setData = (typeof data === 'undefined') ? eval(chartId) : data;
      let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
      let chartData = [];
  
      for (let i = 0; i < setData.datasets.length; i++) {
        chartData.push({
            label: setData.datasets[i].label,
            backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
            borderColor: setData.datasets[i].color,
            data: setData.datasets[i].data,
        });
      } 
  
      let canvas = document.getElementById(chartId).getContext("2d");
      let chart = new Chart(canvas, {
        type:'doughnut',
        data:{ 
          labels : setData.labels,
          datasets : chartData
        },
        options: {
          plugins: {
            legend: {
              display: chartLegend,
              ...NioApp.Chart.legend,
            },
            tooltip: {
              enabled:true,
              ...NioApp.Chart.tooltip,
            },
          },
          responsive: true,
          maintainAspectRatio:false,
        }
      });
    });
  },
  polar : function(selector,data) {
    let elm = document.querySelectorAll(selector);
    elm.forEach(item => {
      let chartId = item.id;
      let setData = (typeof data === 'undefined') ? eval(chartId) : data;
      let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
      let chartData = [];
  
      for (let i = 0; i < setData.datasets.length; i++) {
        chartData.push({
            label: setData.datasets[i].label,
            backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
            borderColor: setData.datasets[i].color,
            data: setData.datasets[i].data,
        });
      } 
  
      let canvas = document.getElementById(chartId).getContext("2d");
      let chart = new Chart(canvas, {
        type:'polarArea',
        data:{ 
          labels : setData.labels,
          datasets : chartData
        },
        options: {
          plugins: {
            legend: {
              display: chartLegend,
              ...NioApp.Chart.legend,
            },
            tooltip: {
              enabled:true,
              ...NioApp.Chart.tooltip,
            },
          },
          responsive: true,
          maintainAspectRatio:false,
        }
      });
    });
  },
}


NioApp.Chart.init = function () {
  NioApp.Chart.line('[data-nk-chart="line"]');
  NioApp.Chart.bar('[data-nk-chart="bar"]');
  NioApp.Chart.pie('[data-nk-chart="pie"]');
  NioApp.Chart.doughnut('[data-nk-chart="doughnut"]');
  NioApp.Chart.polar('[data-nk-chart="polar"]');
}

NioApp.winLoad(NioApp.Chart.init);


})(NioApp);
