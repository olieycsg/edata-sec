!(function (NioApp) {
    "use strict";

    // total revenue chart
    let totalRevenueChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        lineTension:.4,
        yAxis:  false,
        xAxis:  false,
        datasets : [
            {
              label : "Revenue",
              color: NioApp.Colors.primary,
              backgroundGradient: NioApp.Colors.primary,
              borderWidth: 2,
              pointBorderColor: 'transparent',
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: NioApp.Colors.primary,
              data: [420, 460, 295, 505, 288, 699, 667, 740, 655, 967, 837, 1450]
          }
        ]
    };

    // total revenue chart
    let estimatedChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        lineTension:.4,
        yAxis:  false,
        xAxis:  false,
        datasets : [
            {
              label : "Estimated",
              color: NioApp.Colors.yellow,
              backgroundGradient: NioApp.Colors.yellow,
              borderWidth: 2,
              pointBorderColor: 'transparent',
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: NioApp.Colors.yellow,
              data: [420, 460, 295, 305, 288, 599, 667, 740, 655, 967, 837, 1450]
          }
        ]
    };

    // marketplace Chart
    let marketplaceChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        lineTension:.4,
        ticksValue: 'k',
        ticksColor: '#9CA3AF',
        ticksFontSize: 11,
        ticksFontWeight: 100,
        gridBorderDash: [8, 4],
        gridColorX: NioApp.Colors.white,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.gray100,
        gridBorderColorY: NioApp.Colors.white,
        datasets : [
            {
                borderWidth: 2,
                color: NioApp.Colors.yellow,
                backgroundGradient: NioApp.Colors.yellow,
                pointBorderColor: NioApp.Colors.white,
                pointBackgroundColor: NioApp.Colors.yellow,
                pointHoverBackgroundColor: NioApp.Colors.yellow,
                label: "Total Atwork",
                data: [0, 15, 10, 28, 20, 38, 30, 22, 30, 12, 38, 30]
            },
            {
                borderWidth: 2,
                backgroundGradient: NioApp.Colors.success,
                pointBorderColor: NioApp.Colors.white,
                pointBackgroundColor: NioApp.Colors.success,
                color: NioApp.Colors.success,
                pointHoverBackgroundColor: NioApp.Colors.success,
                label: "Total Auction",
                data: [30, 24, 49, 36, 40, 49, 39, 38, 59, 50, 56, 66]
            },
            {
                borderWidth: 2,
                pointBorderColor: NioApp.Colors.white,
                pointBackgroundColor: NioApp.Colors.pink,
                color: NioApp.Colors.pink,
                pointHoverBackgroundColor: NioApp.Colors.pink,
                label: "Total Artist",
                borderDash: [8,4],
                data: [44, 34, 39, 68, 29, 61, 10, 48, 45, 70, 46, 55]
            },
        ]
    };

    // popularity chart
    let popularityChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        barPercentage : 0.7,
        categoryPercentage: 0.6,
        ticksValue: 'k',
        ticksColor: '#9CA3AF',
        ticksFontSize: 11,
        ticksFontWeight: 100,
        gridBorderDash: [8, 4],
        gridColorX: NioApp.Colors.white,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.gray100,
        gridBorderColorY: NioApp.Colors.white,
        datasets: [
          {
            borderRadius: 10,
            borderWidth: 1,
            color: NioApp.Colors.success,
            background: NioApp.Colors.success,
            border: 1,
            label: "Like",
            data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95]
          },
          {
            borderRadius: 10,
            borderWidth: 1,
            color: NioApp.Colors.info,
            background: NioApp.Colors.info,
            border: 1,
            label: "Share",
            data: [75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125]
          }
        ]
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

                let chartBarPercentage = (typeof setData.barPercentage === 'undefined') ? 0.6 : setData.barPercentage;
                let chartCategoryPercentage = (typeof setData.categoryPercentage === 'undefined') ? 0.7 : setData.categoryPercentage;

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
                        hoverBackgroundColor: setData.datasets[i].hoverBackgroundColor,
                        type: setData.datasets[i].type,
                        order: setData.datasets[i].order,
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
                        layout: {
                            padding: 0
                        },
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
        }
    };

    NioApp.Chart.init = function () {
        NioApp.Chart.bar('[data-nk-chart="bar"]');
        NioApp.Chart.line('[data-nk-chart="line"]');
    }
  
  NioApp.winLoad(NioApp.Chart.init);

})(NioApp);
  