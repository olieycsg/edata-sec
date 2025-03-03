!(function (NioApp) {
    "use strict";

    // earnings chart
    let earningsChart = {
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
        barThickness: 6,
        datasets: [
            {
                borderRadius: 10,
                borderWidth: 1,
                color: NioApp.Colors.info,
                background: NioApp.Colors.info,
                hoverBackgroundColor: NioApp.Colors.info,
                label: "Earnings",
                data: [700, 780, 570, 870, 670, 910, 770]
            },
        ]
    };

    // total clients Chart
    let totalClientsChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        legend: false,
        lineTension:.4,
        yAxis:  false,
        xAxis:  false,
        maxTicksLimit:4,
        datasets : [
            {
              label : "Total Clients",
              color: NioApp.Colors.success,
              backgroundGradient: NioApp.Colors.success,
              borderWidth: 4,
              pointBorderColor: 'transparent',
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: NioApp.Colors.success,
              borderCapStyle: "round",
              data: [38, 40, 23, 80, 70, 110]
            }
        ]
    };

    // project overview Chart
    let projectsOverviewChart = {
        labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        stacked: false,
        ticksColor: '#9CA3AF',
        ticksFontSize: 11,
        ticksFontWeight: 100,
        ticksValue: 'k',
        gridBorderDash: [8, 4],
        gridColorX: NioApp.Colors.white,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.white,
        gridBorderColorY: NioApp.Colors.white,
        datasets: [
          {
            color: NioApp.Colors.primary,
            background: NioApp.Colors.primary,
            border: 1,
            label: "Total Income",
            data: [120, 160, 95, 105, 98, 99, 167, 140, 155, 267, 237, 250],
            order: 1
          },
          {
            color: NioApp.Colors.warning,
            background: NioApp.Colors.warning,
            border: 2,
            label: "Total Profit",
            data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95],
            type: 'line',
            order: 0
          }
        ]
    };

    // avg earnings Chart
    let avgEarningsChart = {
        labels : ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan","13 Jan", "14 Jan", "15 Jan"],
        legend: false,
        lineTension:.4,
        ticksValue: 'k',
        ticksColor: '#9CA3AF',
        ticksFontSize: 11,
        ticksFontWeight: 100,
        gridBorderDash: [8, 4],
        xAxis: false,
        gridColorX: NioApp.Colors.gray100,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.gray100,
        gridBorderColorY: NioApp.Colors.white,
        datasets : [
            {
              label : "Avg Earnings",
              color: NioApp.Colors.success,
              backgroundGradient: NioApp.Colors.success,
              pointBackgroundColor: 'transparent',
              borderWidth: 2,
              pointBorderColor: 'transparent',
              pointHoverBackgroundColor: NioApp.Colors.success,
              data: [55, 105, 100, 115, 150, 145, 190, 150, 200, 190, 230, 200, 256, 234, 300]
            }
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
                        hoverBackgroundColor: setData.datasets[i].hoverBackgroundColor,
                        type: setData.datasets[i].type,
                        order: setData.datasets[i].order
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
        }
    };

    NioApp.Chart.init = function () {
        NioApp.Chart.bar('[data-nk-chart="bar"]');
        NioApp.Chart.line('[data-nk-chart="line"]');
    }
  
  NioApp.winLoad(NioApp.Chart.init);

})(NioApp);
  