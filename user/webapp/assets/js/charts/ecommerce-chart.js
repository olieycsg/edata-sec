!(function (NioApp) {
    "use strict";

    // visitor chart
    let visitorChart = {
        labels : ["M", "T", "W", "T", "F", "S", "S"],
        legend: false,
        yAxis: false,
        ticksColor: '#9CA3AF',
        ticksFontSize: 11,
        ticksFontWeight: 100,
        gridColorX: NioApp.Colors.white,
        gridColorY: NioApp.Colors.white,
        gridBorderColorX: NioApp.Colors.white,
        gridBorderColorY: NioApp.Colors.white,
        datasets: [
            {
                borderRadius: 10,
                borderWidth: 1,
                color: 'transparent',
                background: NioApp.hexRGB(NioApp.Colors.info,.3),
                hoverBackgroundColor: NioApp.Colors.info,
                label: "Visitors",
                data: [600, 680, 470, 770, 570, 810, 670]
            },
        ]
    };

    // activity Chart
    let activityChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        lineTension:.4,
        yAxis:  false,
        xAxis:  false,
        datasets : [
            {
              label : "Activity",
              color: NioApp.Colors.success,
              backgroundGradient: NioApp.Colors.success,
              pointBackgroundColor: NioApp.Colors.success,
              borderWidth: 2,
              pointBorderColor: 'transparent',
              pointHoverBackgroundColor: NioApp.Colors.success,
              data: [120, 160, 95, 105, 98, 99, 167, 140, 155, 267, 237, 250]
            }
        ]
    };

    // total Profit Chart
    let totalProfitChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        stacked: true,
        ticksColor: '#9CA3AF',
        ticksFontSize: 11,
        ticksFontWeight: 100,
        maxTicksLimit: 20,
        ticksValue: 'k',
        gridBorderDash: [8, 4],
        gridColorX: NioApp.Colors.white,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.white,
        gridBorderColorY: NioApp.Colors.white,
        datasets: [
          {
            borderRadius: {topLeft: 50, topRight: 50, bottomLeft: 50, bottomRight: 50},
            color: NioApp.Colors.primary,
            background: NioApp.Colors.primary,
            border: 1,
            label: "Total Income",
            data: [120, 160, 95, 105, 98, 99, 167, 140, 155, 267, 237, 250]
            
          },
          {
            borderRadius: {topLeft: 50, topRight: 50, bottomLeft: 50, bottomRight: 50},
            color: NioApp.Colors.success,
            background: NioApp.Colors.success,
            border: 1,
            label: "Total Profit",
            data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95]
          },
          {
            borderRadius: {topLeft: 50, topRight: 50, bottomLeft: 50, bottomRight: 50},
            color: NioApp.Colors.gray300,
            background: NioApp.Colors.gray300,
            border: 1,
            label: "Total Expense",
            data: [75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125]
          }
        ]
    }

    // total revenue Chart
    let totalRevenueChart = {
      labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      legend: false,
      lineTension:.4,
      yAxis:  false,
      xAxis:  false,
      datasets : [
          {
            label : "Total",
            color: NioApp.Colors.primary,
            backgroundColor: 'transparent',
            borderWidth: 4,
            pointBorderColor: 'transparent',
            pointBackgroundColor: 'transparent',
            pointHoverBackgroundColor: NioApp.Colors.primary,
            borderCapStyle: 'round',
            data: [12, 40, 13, 130, 70, 210]
          }
      ]
    };

    // sales analytics Chart
    let salesAnalyticsChart = {
      labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      legend: false,
      lineTension:.4,
      ticksValue: 'k',
      ticksColor: '#9CA3AF',
      ticksFontSize: 11,
      ticksFontWeight: 100,
      gridBorderDash: [8, 4],
      maxTicksLimit: 10,
      xAxis: false,
      gridColorX: NioApp.Colors.white,
      gridColorY: NioApp.Colors.gray100,
      gridBorderColorX: NioApp.Colors.white,
      gridBorderColorY: NioApp.Colors.white,
      datasets : [
        {
          borderWidth: 3,
          color: NioApp.Colors.yellow,
          backgroundGradient: NioApp.Colors.yellow,
          pointBorderColor: 'transparent',
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: NioApp.Colors.yellow,
          label: "Total Sales",
          data: [40, 60, 30, 65, 60, 95, 90, 100, 96, 120, 105, 134]
        },
        {
            borderWidth: 2,
            pointBorderColor: 'transparent',
            pointBackgroundColor: 'transparent',
            color: NioApp.Colors.danger,
            pointHoverBackgroundColor: NioApp.Colors.danger,
            label: "Total Orders",
            borderDash: [8,4],
            data: [70, 44, 49, 78, 39, 49, 39, 38, 59, 80, 56, 101]
        },
      ]
    };

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
        bar: function(selector,data) {
            let elm = document.querySelectorAll(selector);
            elm.forEach(item => {
                let chartId = item.id;
                let setData = (typeof data === 'undefined') ? eval(chartId) : data;
                let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
                let chartStacked = (typeof setData.stacked === 'undefined') ? false : setData.stacked;
                let chartxAxis = (typeof setData.xAxis === 'undefined') ? true : setData.xAxis;
                let chartyAxis = (typeof setData.yAxis === 'undefined') ? true : setData.yAxis;
                let chartTicksColor = setData.ticksColor;
                let chartTicksFontSize = setData.ticksFontSize;
                let chartTicksFontWeight = setData.ticksFontWeight;
                let chartMaxTicksLimit = setData.maxTicksLimit;
                let chartTicksValue = (typeof setData.ticksValue === 'undefined') ? false : setData.ticksValue;

                let chartGridColorX = setData.gridColorX;
                let chartGridColorY = setData.gridColorY;
                let chartGridBorderColorX = setData.gridBorderColorX;
                let chartGridBorderColorY = setData.gridBorderColorY;
                let chartGridBorderDash = setData.gridBorderDash;

                let chartBarPercentage = (typeof setData.barPercentage === 'undefined') ? 0.1 : setData.barPercentage;
                let chartCategoryPercentage = (typeof setData.categoryPercentage === 'undefined') ? 0.1 : setData.categoryPercentage;
                let chartBarThickness = (typeof setData.barThickness === 'undefined') ? 10 : setData.barThickness;

                let chartData = [];
                

                for (let i = 0; i < setData.datasets.length; i++) {
                    chartData.push({
                        label: setData.datasets[i].label,
                        backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
                        borderWidth: (typeof setData.datasets[i].border === 'undefined') ? 1 : setData.datasets[i].border,
                        borderColor: setData.datasets[i].color,
                        data: setData.datasets[i].data,
                        borderRadius: (typeof setData.datasets[i].borderRadius === 'undefined') ? 0 : setData.datasets[i].borderRadius,
                        borderSkipped: false,
                        hoverBackgroundColor: setData.datasets[i].hoverBackgroundColor
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
                        categoryPercentage: chartCategoryPercentage,
                        barPercentage: chartBarPercentage,
                        barThickness: chartBarThickness,
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
                        scales: {
                            x: {
                                stacked: chartStacked,
                                display: chartxAxis,
                                grid: {
                                    color: chartGridColorX,
                                    borderColor: chartGridBorderColorX,
                                    borderDash: chartGridBorderDash,
                                },
                                ticks: { 
                                    color: chartTicksColor, 
                                    beginAtZero: true,
                                    maxTicksLimit: chartMaxTicksLimit,
                                    font: {
                                        size: chartTicksFontSize,
                                        weight: chartTicksFontWeight
                                    }
                                }
                            },
                            y: {
                                stacked: chartStacked,
                                display: chartyAxis,
                                grid: {
                                    color: chartGridColorY,
                                    borderColor: chartGridBorderColorY,
                                    borderDash: chartGridBorderDash,
                                },
                                ticks: { 
                                    // Include a ticks value in the ticks
                                    callback: function(value, index, ticks) {
                                        return value + chartTicksValue;
                                    },
                                    color: chartTicksColor, 
                                    beginAtZero: true,
                                    maxTicksLimit: chartMaxTicksLimit,
                                    font: {
                                        size: chartTicksFontSize,
                                        weight: chartTicksFontWeight
                                    }
                                }
                            }
                        }
                    }
                });
            });
        },
        line: function(selector,data) {
                let elm = document.querySelectorAll(selector);
                elm.forEach(item => {
                let chartId = item.id;
                let setData = (typeof data === 'undefined') ? eval(chartId) : data;
                let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
                let chartxAxis = (typeof setData.xAxis === 'undefined') ? true : setData.xAxis;
                let chartyAxis = (typeof setData.yAxis === 'undefined') ? true : setData.yAxis;
                let chartTicksColor = setData.ticksColor;
                let chartTicksFontSize = setData.ticksFontSize;
                let chartTicksFontWeight = setData.ticksFontWeight;
                let chartMaxTicksLimit = setData.maxTicksLimit;
                let chartTicksValue = (typeof setData.ticksValue === 'undefined') ? false : setData.ticksValue;

                let chartGridColorX = setData.gridColorX;
                let chartGridColorY = setData.gridColorY;
                let chartGridBorderColorX = setData.gridBorderColorX;
                let chartGridBorderColorY = setData.gridBorderColorY;
                let chartGridBorderDash = setData.gridBorderDash;

                let canvas = document.getElementById(chartId).getContext("2d");
                let chartData = [];
                
                for (let i = 0; i < setData.datasets.length; i++) {
                    let backgroundColor = (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background
                    chartData.push({
                        label: setData.datasets[i].label,
                        tension:setData.lineTension,
                        backgroundColor: setData.datasets[i].backgroundGradient ? getGradient(setData.datasets[i].backgroundGradient) : backgroundColor,
                        borderWidth: setData.datasets[i].borderWidth,
                        borderColor: setData.datasets[i].color,
                        pointBorderColor: setData.datasets[i].pointBorderColor,
                        pointBackgroundColor: setData.datasets[i].pointBackgroundColor,
                        pointHoverBackgroundColor: setData.datasets[i].pointHoverBackgroundColor,
                        pointHoverBorderColor: setData.datasets[i].pointHoverBackgroundColor,
                        pointBorderWidth: setData.datasets[i].pointBorderWidth,
                        pointHoverRadius: setData.datasets[i].pointHoverRadius,
                        pointHoverBorderWidth: setData.datasets[i].pointBorderWidth,
                        pointRadius: setData.datasets[i].pointRadius,
                        pointHitRadius: setData.datasets[i].pointHitRadius,
                        fill: true,
                        data: setData.datasets[i].data,
                        borderDash: setData.datasets[i].borderDash,
                        borderCapStyle: setData.datasets[i].borderCapStyle
                    });
                } 

                function getGradient(color) {
                    let gradient = canvas.createLinearGradient(0, 0, 0, canvas.canvas.clientHeight);
                    gradient.addColorStop(0, `${NioApp.hexRGB(color,0)}`);
                    gradient.addColorStop(0.5, `${NioApp.hexRGB(color,.25)}`);
                    gradient.addColorStop(1, `${NioApp.hexRGB(color,.1)}`);
                    return gradient;
                }

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
                        scales:{
                            y: {
                                display: chartyAxis,
                                grid: {
                                    color: chartGridColorY,
                                    borderColor: chartGridBorderColorY,
                                    borderDash: chartGridBorderDash,
                                },
                                ticks: {
                                    // Include a ticks value in the ticks
                                    callback: function(value, index, ticks) {
                                        return value + chartTicksValue;
                                    },
                                    color: chartTicksColor, 
                                    beginAtZero: true,
                                    maxTicksLimit: chartMaxTicksLimit,
                                    font: {
                                        size: chartTicksFontSize,
                                        weight: chartTicksFontWeight
                                    }
                                }
                            },
                            x: {
                                display: chartxAxis,
                                grid: {
                                    color: chartGridColorX,
                                    borderColor: chartGridBorderColorX,
                                    borderDash: chartGridBorderDash,
                                },
                                ticks: {
                                    color: chartTicksColor, 
                                    beginAtZero: true,
                                    maxTicksLimit: chartMaxTicksLimit,
                                    font: {
                                        size: chartTicksFontSize,
                                        weight: chartTicksFontWeight
                                    }
                                }
                            }
                        }
                    }
                });
            });
        },
    };

    NioApp.Chart.init = function () {
        NioApp.Chart.bar('[data-nk-chart="bar"]');
        NioApp.Chart.line('[data-nk-chart="line"]');
    }
  
  NioApp.winLoad(NioApp.Chart.init);

})(NioApp);
  