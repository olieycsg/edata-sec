!(function (NioApp) {
    "use strict";
  
    // sessions chart
    let sessionChart = {
        labels : ["1st May","2nd May","3rd May","4th May","5th May","6th May","7th May","8th May","9th May","10th May","11th May","12th May","13th May","14th May","15th May"],
        legend: false,
        xAxis:  false,
        barThickness: 6,
        ticksColor: '#787c9e',
        ticksFontSize: 11,
        ticksFontWeight: 300,
        gridBorderDash: [8, 4],
        gridColorX: NioApp.Colors.gray100,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.gray100,
        gridBorderColorY: NioApp.Colors.gray100,
        datasets: [
            {
                borderRadius: 2,
                borderWidth: 1,
                color: NioApp.Colors.primary,
                background: NioApp.Colors.primary,
                label: " Session",
                data: [25, 35, 55, 20, 30, 17, 12, 35, 23, 52, 45, 30, 10, 50, 45]
            },
        ]
    };
    // session avg chart
    let sessionChartAvg = {
        labels : ["1st May","2nd May","3rd May","4th May","5th May","6th May","7th May","8th May","9th May","10th May","11th May","12th May","13th May","14th May","15th May","16th May","17th May","18th May","19th May","20th May","21th May","22th May","23th May","24th May","25th May","26th May","27th May","28th May","29th May","30th May"],
        legend: false,
        lineTension:.4,
        yAxis:  false,
        xAxis:  false,
        datasets : [
            {
              label : "Avg.Sessions",
              color: NioApp.Colors.success,
              borderWidth: 2,
              backgroundGradient: NioApp.Colors.success,
              pointBorderColor: 'transparent',
              pointBackgroundColor: 'transparent',
              pointHoverBackgroundColor: NioApp.Colors.success,
              data: [120, 140, 140, 130, 128, 130, 145, 140, 132, 135, 145, 155, 165, 180, 185, 178, 166, 160, 165, 160, 155, 170, 190, 205, 220, 236, 250, 240, 235, 240]
          }
        ]
    };

    // Bounce rate chart
    let bounceRateChart = {
        labels : ["1st May","2nd May","3rd May","4th May","5th May","6th May","7th May"],
        legend: false,
        lineTension: 0,
        yAxis:  false,
        xAxis:  false,
        maxTicksLimit:4,
        datasets : [
            {
                label : "This Month",
                borderWidth: 2,
                color : NioApp.Colors.primary,
                backgroundGradient: NioApp.Colors.primary,
                pointBackgroundColor: NioApp.Colors.primary,
                pointBorderColor: NioApp.Colors.white,
                data: [120, 130, 110, 105, 115, 108, 118]
            }
        ]
    };
    
    // goal completions chart
    let goalCompletions = {
        labels : ["1st May","2nd May","3rd May","4th May","5th May","6th May","7th May","8th May","9th May","10th May","11th May","12th May","13th May","14th May","15th May","16th May","17th May","18th May","19th May","20th May","21th May","22th May","23th May","24th May","25th May","26th May","27th May","28th May","29th May","30th May"],
        legend: false,
        yAxis:  false,
        xAxis:  false,
        barThickness: 4,
        datasets: [
          {
            borderRadius: 2,
            borderWidth: 1,
            barPercentage : .7,
            color: NioApp.Colors.info,
            background: NioApp.Colors.info,
            label: "data",
            data: [36, 41, 49, 72, 39, 49, 39, 38, 59, 80, 62, 33, 62, 60, 114, 98, 54, 39,72, 39, 49, 39, 38, 59, 80, 62, 33, 62, 60, 114]
          }
        ]
    };
    // audience overview chart
    let audienceOverviewChart = {
        labels : ["Jan", "Feb", "Mar", "April", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        legend: false,
        lineTension: .4,
        ticksValue: 'k',
        ticksColor: '#787c9e',
        ticksFontSize: 11,
        ticksFontWeight: 300,
        gridBorderDash: [8, 4],
        gridColorX: NioApp.Colors.gray100,
        gridColorY: NioApp.Colors.gray100,
        gridBorderColorX: NioApp.Colors.gray100,
        gridBorderColorY: NioApp.Colors.gray100,
        datasets: [
            {
                borderWidth: 2,
                color: NioApp.Colors.warning,
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
                pointHoverBackgroundColor: NioApp.Colors.warning,
                label: "New visits",
                data: [28, 60, 30, 65, 60, 95, 90, 100, 96, 120, 105, 134]
            },
            {
                borderWidth: 2,
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
                color: NioApp.Colors.info,
                pointHoverBackgroundColor: NioApp.Colors.info,
                label: "Unique visits",
                data: [70, 44, 49, 78, 39, 49, 39, 38, 59, 80, 56, 44]
            },
        ]
    };

    // Sessions Device
    let sessionsDevice = {
        labels : ["Mobile", "Tablet", "Desktop", "Others"],
        datasets : [{
            background: [NioApp.Colors.info, NioApp.Colors.yellow, NioApp.Colors.green, NioApp.Colors.purple],
            data: [140, 70, 110, 120],
            hoverOffset: 4
        }],
    };

    // svg world map
    let svgWorldMap = {
        colorMax: '#B1C7EF',
        colorMin: '#D4DEEF',
        colorNoData: '#F3F4F6',
        data: {
            data: {
              visitor: {
                name: 'Total Sessions',
                format: '{0}',
                thousandSeparator: ',',
                thresholdMax: 50000,
                thresholdMin: 1000,
              },
              change: {
                name: 'Changes This Week',
                format: '{0} %'
              }
            },
            applyData: 'visitor',
            values: {
                AF: {
                  visitor: 587,
                  change: 4.73,
                },
                AL: {
                  visitor: 4583,
                  change: 11.09,
                },
                DZ: {
                  visitor: 4293,
                  change: 10.01,
                },
                AO: {
                  visitor: 4408,
                  change: 26.49,
                },
                AG: {
                  visitor: 16702,
                  change: 3.26,
                },
                AR: {
                  visitor: 14467,
                  change: 13.83,
                },
                AM: {
                  visitor: 3861,
                  change: 9.22,
                },
                AU: {
                  visitor: 55707,
                  change: 7.39,
                },
                AT: {
                  visitor: 47290,
                  change: 5.72,
                },
                AZ: {
                  visitor: 4141,
                  change: 6.29,
                },
                BS: {
                  visitor: 31255,
                  change: 2.21,
                },
                BH: {
                  visitor: 24029,
                  change: 6.32,
                },
                BD: {
                  visitor: 1602,
                  change: 9.79,
                },
                BB: {
                  visitor: 17859,
                  change: 7.83,
                },
                BY: {
                  visitor: 5760,
                  change: 14.69,
                },
                BE: {
                  visitor: 43582,
                  change: 5.3,
                },
                BZ: {
                  visitor: 4806,
                  change: -0.64,
                },
                BJ: {
                  visitor: 830,
                  change: 4.92,
                },
                BT: {
                  visitor: 2903,
                  change: 8.01,
                },
                BO: {
                  visitor: 3353,
                  change: 7.3,
                },
                BA: {
                  visitor: 5149,
                  change: 7.04,
                },
                BW: {
                  visitor: 7877,
                  change: 8.34,
                },
                BR: {
                  visitor: 9895,
                  change: 13.74,
                },
                BN: {
                  visitor: 29712,
                  change: 10.31,
                },
                BG: {
                  visitor: 8064,
                  change: 7.58,
                },
                BF: {
                  visitor: 664,
                  change: 8.11,
                },
                BI: {
                  visitor: 312,
                  change: 4.85,
                },
                KH: {
                  visitor: 1389,
                  change: 8.76,
                },
                CM: {
                  visitor: 1401,
                  change: 2.94,
                },
                CA: {
                  visitor: 45077,
                  change: 6.27,
                },
                XK: {
                  visitor: 3880,
                  change: 6.23,
                },
                CV: {
                  visitor: 3237,
                  change: 4.91,
                },
                CF: {
                  visitor: 387,
                  change: 7.65,
                },
                TD: {
                  visitor: 810,
                  change: -0.71,
                },
                CL: {
                  visitor: 15070,
                  change: 9.66,
                },
                CN: {
                  visitor: 8643,
                  change: 6.5,
                },
                CO: {
                  visitor: 6273,
                  change: 9.21,
                },
                KM: {
                  visitor: 788,
                  change: 3.09,
                },
                CG: {
                  visitor: 1958,
                  change: 6.66,
                },
                CR: {
                  visitor: 11685,
                  change: -0.77,
                },
                HR: {
                  visitor: 13138,
                  change: 6.7,
                },
                CY: {
                  visitor: 24976,
                  change: 5.65,
                },
                CZ: {
                  visitor: 20152,
                  change: 8.9,
                },
                CD: {
                  visitor: 478,
                  change: 2.32,
                },
                DK: {
                  visitor: 56444,
                  change: 4.97,
                },
                DJ: {
                  visitor: 1989,
                  change: 4.52,
                },
                DM: {
                  visitor: 7921,
                  change: -3.63,
                },
                DO: {
                  visitor: 7375,
                  change: 3.67,
                },
                EC: {
                  visitor: 6098,
                  change: 2.21,
                },
                EG: {
                  visitor: 2501,
                  change: -32.17,
                },
                SV: {
                  visitor: 4400,
                  change: 4.06,
                },
                GQ: {
                  visitor: 12727,
                  change: 2.65,
                },
                ER: {
                  visitor: 980,
                  change: 13.88,
                },
                EE: {
                  visitor: 19840,
                  change: 11.47,
                },
                ET: {
                  visitor: 873,
                  change: 8.82,
                },
                FM: {
                  visitor: 3200,
                  change: 1.45,
                },
                FJ: {
                  visitor: 5740,
                  change: 7.44,
                },
                FI: {
                  visitor: 46017,
                  change: 5.75,
                },
                FR: {
                  visitor: 39869,
                  change: 4.35,
                },
                GA: {
                  visitor: 7972,
                  change: 6.96,
                },
                GM: {
                  visitor: 480,
                  change: 1.45,
                },
                GE: {
                  visitor: 4099,
                  change: 5.86,
                },
                DE: {
                  visitor: 44550,
                  change: 5.44,
                },
                GH: {
                  visitor: 1663,
                  change: 7.18,
                },
                GR: {
                  visitor: 18637,
                  change: 4.26,
                },
                GD: {
                  visitor: 10360,
                  change: 5.1,
                },
                GT: {
                  visitor: 4472,
                  change: 7.84,
                },
                GN: {
                  visitor: 749,
                  change: 11.88,
                },
                GW: {
                  visitor: 794,
                  change: 13.43,
                },
                GY: {
                  visitor: 4710,
                  change: 3.24,
                },
                HT: {
                  visitor: 784,
                  change: 3.96,
                },
                HN: {
                  visitor: 2766,
                  change: 4.66,
                },
                HK: {
                  visitor: 46109,
                  change: 6.01,
                },
                HU: {
                  visitor: 15531,
                  change: 3.16,
                },
                IS: {
                  visitor: 70332,
                  change: 16.46,
                },
                IN: {
                  visitor: 1983,
                  change: 13.35,
                },
                ID: {
                  visitor: 3876,
                  change: 7.53,
                },
                IR: {
                  visitor: 5305,
                  change: 5.53,
                },
                IQ: {
                  visitor: 5088,
                  change: 12.24,
                },
                IE: {
                  visitor: 70638,
                  change: 9.02,
                },
                IL: {
                  visitor: 40258,
                  change: 8.24,
                },
                IT: {
                  visitor: 31984,
                  change: 4.31,
                },
                CI: {
                  visitor: 1617,
                  change: 8.14,
                },
                JM: {
                  visitor: 5048,
                  change: 2.0,
                },
                JP: {
                  visitor: 38440,
                  change: -1.4,
                },
                JO: {
                  visitor: 5678,
                  change: 2.31,
                },
                KZ: {
                  visitor: 8841,
                  change: 18.57,
                },
                KE: {
                  visitor: 1702,
                  change: 9.66,
                },
                KI: {
                  visitor: 1721,
                  change: 6.42,
                },
                KW: {
                  visitor: 26005,
                  change: 5.6,
                },
                KG: {
                  visitor: 1144,
                  change: 7.19,
                },
                LA: {
                  visitor: 2543,
                  change: 5.2,
                },
                LV: {
                  visitor: 15547,
                  change: 10.98,
                },
                LB: {
                  visitor: 11408,
                  change: 2.73,
                },
                LS: {
                  visitor: 1425,
                  change: 12.94,
                },
                LR: {
                  visitor: 729,
                  change: -2.12,
                },
                LY: {
                  visitor: 4859,
                  change: 67.32,
                },
                LT: {
                  visitor: 16730,
                  change: 12.14,
                },
                LU: {
                  visitor: 105803,
                  change: 3.94,
                },
                MO: {
                  visitor: 77451,
                  change: 10.1,
                },
                MK: {
                  visitor: 5474,
                  change: 5.51,
                },
                MG: {
                  visitor: 448,
                  change: 11.5,
                },
                MW: {
                  visitor: 324,
                  change: 10.17,
                },
                MY: {
                  visitor: 9813,
                  change: 4.68,
                },
                MV: {
                  visitor: 12527,
                  change: 5.17,
                },
                ML: {
                  visitor: 811,
                  change: 5.62,
                },
                MT: {
                  visitor: 27250,
                  change: 8.83,
                },
                MH: {
                  visitor: 3625,
                  change: 8.21,
                },
                MR: {
                  visitor: 1318,
                  change: 5.15,
                },
                MU: {
                  visitor: 9794,
                  change: 1.88,
                },
                MX: {
                  visitor: 9304,
                  change: 5.64,
                },
                MD: {
                  visitor: 2280,
                  change: 19.51,
                },
                MN: {
                  visitor: 3640,
                  change: -0.75,
                },
                ME: {
                  visitor: 7647,
                  change: 8.8,
                },
                MA: {
                  visitor: 3151,
                  change: 4.89,
                },
                MZ: {
                  visitor: 429,
                  change: 9.5,
                },
                MM: {
                  visitor: 1264,
                  change: 4.41,
                },
                NA: {
                  visitor: 5413,
                  change: 14.96,
                },
                NR: {
                  visitor: 8575,
                  change: 9.6,
                },
                NP: {
                  visitor: 834,
                  change: 14.45,
                },
                NL: {
                  visitor: 48346,
                  change: 5.89,
                },
                NZ: {
                  visitor: 41593,
                  change: 6.51,
                },
                NI: {
                  visitor: 2207,
                  change: 2.57,
                },
                NE: {
                  visitor: 440,
                  change: 6.29,
                },
                NG: {
                  visitor: 1994,
                  change: -9.68,
                },
                NO: {
                  visitor: 74941,
                  change: 6.07,
                },
                OM: {
                  visitor: 17973,
                  change: 0.64,
                },
                PK: {
                  visitor: 1541,
                  change: 6.95,
                },
                PW: {
                  visitor: 17096,
                  change: 0.27,
                },
                PA: {
                  visitor: 15089,
                  change: 5.35,
                },
                PG: {
                  visitor: 2861,
                  change: 2.51,
                },
                PY: {
                  visitor: 4260,
                  change: 6.47,
                },
                PE: {
                  visitor: 6199,
                  change: 8.93,
                },
                PH: {
                  visitor: 2976,
                  change: 0.78,
                },
                PL: {
                  visitor: 13822,
                  change: 11.37,
                },
                PT: {
                  visitor: 21161,
                  change: 3.68,
                },
                PR: {
                  visitor: 30488,
                  change: -0.98,
                },
                QA: {
                  visitor: 60804,
                  change: 4.39,
                },
                RO: {
                  visitor: 10757,
                  change: 13.18,
                },
                RU: {
                  visitor: 10608,
                  change: 19.19,
                },
                RW: {
                  visitor: 772,
                  change: 5.2,
                },
                KN: {
                  visitor: 16296,
                  change: 1.9,
                },
                LC: {
                  visitor: 9607,
                  change: 1.99,
                },
                VC: {
                  visitor: 7270,
                  change: 3.99,
                },
                WS: {
                  visitor: 4253,
                  change: 5.93,
                },
                SM: {
                  visitor: 47406,
                  change: 3.8,
                },
                ST: {
                  visitor: 1785,
                  change: 5.7,
                },
                SA: {
                  visitor: 21120,
                  change: 3.95,
                },
                SN: {
                  visitor: 1038,
                  change: 8.73,
                },
                RS: {
                  visitor: 5899,
                  change: 8.71,
                },
                SC: {
                  visitor: 15686,
                  change: 2.97,
                },
                SL: {
                  visitor: 492,
                  change: -5.88,
                },
                SG: {
                  visitor: 57713,
                  change: 4.48,
                },
                SK: {
                  visitor: 17664,
                  change: 6.73,
                },
                SI: {
                  visitor: 23654,
                  change: 9.17,
                },
                SB: {
                  visitor: 2081,
                  change: 1.18,
                },
                SO: {
                  visitor: 486,
                  change: 6.38,
                },
                ZA: {
                  visitor: 6180,
                  change: 16.25,
                },
                KR: {
                  visitor: 29891,
                  change: 8.56,
                },
                SS: {
                  visitor: 228,
                  change: -18.73,
                },
                ES: {
                  visitor: 28359,
                  change: 6.31,
                },
                LK: {
                  visitor: 4085,
                  change: 6.95,
                },
                SD: {
                  visitor: 1428,
                  change: -1.91,
                },
                SR: {
                  visitor: 5746,
                  change: 0.76,
                },
                SZ: {
                  visitor: 3915,
                  change: 15.19,
                },
                SE: {
                  visitor: 53218,
                  change: 3.39,
                },
                CH: {
                  visitor: 80591,
                  change: 0.35,
                },
                TW: {
                  visitor: 24577,
                  change: 9.03,
                },
                TJ: {
                  visitor: 824,
                  change: 2.54,
                },
                TZ: {
                  visitor: 1034,
                  change: 5.58,
                },
                TH: {
                  visitor: 6591,
                  change: 10.39,
                },
                TL: {
                  visitor: 2104,
                  change: 1.19,
                },
                TG: {
                  visitor: 611,
                  change: 4.24,
                },
                TO: {
                  visitor: 4177,
                  change: 5.56,
                },
                TT: {
                  visitor: 15769,
                  change: -3.46,
                },
                TN: {
                  visitor: 3496,
                  change: -5.24,
                },
                TR: {
                  visitor: 10512,
                  change: -2.82,
                },
                TM: {
                  visitor: 6643,
                  change: 3.59,
                },
                TV: {
                  visitor: 3638,
                  change: 2.24,
                },
                UG: {
                  visitor: 699,
                  change: 1.04,
                },
                UA: {
                  visitor: 2583,
                  change: 17.46,
                },
                AE: {
                  visitor: 37226,
                  change: 5.21,
                },
                GB: {
                  visitor: 39735,
                  change: -1.96,
                },
                US: {
                  visitor: 59501,
                  change: 3.37,
                },
                UY: {
                  visitor: 16722,
                  change: 11.02,
                },
                UZ: {
                  visitor: 1491,
                  change: -28.81,
                },
                VU: {
                  visitor: 3094,
                  change: 6.51,
                },
                VE: {
                  visitor: 6684,
                  change: -12.16,
                },
                VN: {
                  visitor: 2354,
                  change: 8.37,
                },
                YE: {
                  visitor: 551,
                  change: -23.23,
                },
                ZM: {
                  visitor: 1480,
                  change: 18.11,
                },
                ZW: {
                  visitor: 1176,
                  change: 5.78,
                }
            }
        },
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
                      borderDash: setData.datasets[i].borderDash
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
        doughnut: function(selector,data) {
            let elm = document.querySelectorAll(selector);
            elm.forEach(item => {
            let chartId = item.id;
            let setData = (typeof data === 'undefined') ? eval(chartId) : data;
            let chartLegend = (typeof setData.legend === 'undefined') ? false : setData.legend;
            let chartCutout = setData.cutout;
            let chartData = [];
        
            for (let i = 0; i < setData.datasets.length; i++) {
                chartData.push({
                    label: setData.datasets[i].label,
                    backgroundColor: (typeof setData.datasets[i].background === 'undefined') ? 'transparent' : setData.datasets[i].background,
                    borderColor: setData.datasets[i].color,
                    data: setData.datasets[i].data,
                    hoverOffset: setData.datasets[i].hoverOffset
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
                    cutout: chartCutout,
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
        svgmap: function(selector,data) {
            let elm = document.querySelectorAll(selector);
            elm.forEach(item => {
                let itemId = item.id;
                let setData = (typeof data === 'undefined') ? eval(itemId) : data;
                let map = new svgMap({
                    targetElementID: itemId,
                    colorMax: setData.colorMax,
                    colorMin: setData.colorMin,
                    colorNoData: setData.colorNoData,
                    data: setData.data
                });
            });
        },
    };

    NioApp.Chart.init = function () {
        NioApp.Chart.bar('[data-nk-chart="bar"]');
        NioApp.Chart.line('[data-nk-chart="line"]');
        NioApp.Chart.doughnut('[data-nk-chart="doughnut"]');
        NioApp.Chart.svgmap('.js-svgmap');
    }
  
  NioApp.winLoad(NioApp.Chart.init);

})(NioApp);
  