function drawSimpleBarChart(target_div, categories, series, line_series, unit, stacked, zoom) {

    Highcharts.setOptions({
        colors: ['#31a97c', '#91b53b', '#43d7cb', '#0e616b', '#ffde00']
    });

    var plotOptions = {};
    var stackLabels = {};
    if(stacked){
        plotOptions = {
            column: {
                stacking: 'normal',
                dataLabels: { enabled: false }
            }
        };

        stackLabels =  {
            enabled: true,
            style: {
                fontWeight: 'normal',
                color: '#999',
                fontSize: '13px'
            }
        }
    }

    var zoomType = 'none';
    if(zoom){
        var zoomType = 'x';
    }

    if (line_series !== undefined) {
        series.push({
            name: 'target',
            className: "setpoint",
            linkedTo: 'testo', // allows to hide the series name from the legend
            data: line_series,
            type: 'line',
            zIndex: 1,
            marker: {
                enabled: false,
                    states: {
                    hover: {
                        enabled: false
                    }
                }
            },
            dashStyle: 'longdash',
            lineWidth: 1,
            color: "#000",
            label: {enabled: false}
        });
    }

    var chart = Highcharts.chart(target_div, {
        chart: {
            zoomType: zoomType,
            type: 'column',
            style: {
               fontFamily: 'Catamaran',
            }
        },
        title: {text: null},
        subtitle: {text: null},
        credits: {enabled:false},
        exporting: {enabled:false},
        yAxis: {
            title: {text: null},
            gridLineDashStyle: 'longdash',
            labels: {
                style: {
                    color: "#999",
                    fontSize: '13px'
                },
                formatter: function () {
                    var label = this.value + " " + unit;
                    return label;
                }
            },
            stackLabels: stackLabels
        },
        xAxis: {
            categories: categories,
            crosshair: true,
            labels: {
                style: {
                    color: "#999",
                    fontSize: '13px'
                }
            },
            title: {text: null}
        },
        plotOptions: plotOptions,
        tooltip: {
            headerFormat: '<span>{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0"><b>{series.name}: </b></td>' +
                '<td style="padding:0 0 0 5px"> {point.y:.1f}' + unit + '</td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        legend: {
            itemStyle: {
                color: "#777",
                fontWeight: "500",
                fontSize: '13px'
            }
        },
        series: series
    });
    return chart;
}

function drawSimpleLineChart(target_div, categories, series, unit, zoom) {

    Highcharts.setOptions({
        colors: ['#31a97c', '#91b53b', '#43d7cb', '#0e616b', '#ffde00']
    });

    var zoomType = 'none';
    if(zoom){
        var zoomType = 'x';
    }

    var chart = Highcharts.chart(target_div, {
        chart: {
            zoomType: zoomType,
            type: 'line',
            style: {
               fontFamily: 'Catamaran',
            }
        },
        title: {text: null},
        subtitle: {text: null},
        credits: {enabled:false},
        exporting: {enabled:false},
        yAxis: {
            title: {text: null},
            gridLineDashStyle: 'longdash',
            labels: {
                style: {
                    color: "#999",
                    fontSize: '13px'
                },
                formatter: function () {
                    var label = this.value + " " + unit;
                    return label;
                }
            }
        },
        xAxis: {
            categories: categories,
            crosshair: true,
            labels: {
                style: {
                    color: "#999",
                    fontSize: '13px'
                }
            },
            title: {text: null}
        },
        plotOptions: {
            series: {
                marker: {
                    lineWidth: 2,
                    fillColor: series.color, // TODO!!!
                    symbol: "circle"
                }
            }
        },
        tooltip: {
            headerFormat: '<span>{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0"><b>{series.name}: </b></td>' +
                '<td style="padding:0 0 0 5px"> {point.y:.1f}' + unit + '</td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        legend: {
            itemStyle: {
                color: "#777",
                fontWeight: "500",
                fontSize: '13px'
            }
        },
        series: series
    });
    return chart;
}

function drawSimpleGaugeChart(target_div, colors, data, label) {

    var events = {};

    if(label){
        var grossLabel;
        function alignLabel() {
            var chart = this;

            if (grossLabel) {
                grossLabel.destroy();
            }

            const newX = chart.plotWidth / 2 + chart.plotLeft;
            const newY = chart.plotHeight / 2 + chart.plotTop;

            grossLabel = chart.renderer.text(data[0] + '%', newX, newY + 4)
            .attr({
                align: 'center',
            })
            .css({
                color: '#777',
                fontSize: '18px'
            }).add();
        }

        events = {
          load: alignLabel,
          redraw: alignLabel
        };
    }

    var chart = Highcharts.chart(target_div, {
        chart: {
            type: 'solidgauge',
            events: events,
            style: {
               fontFamily: 'Catamaran',
           },
            height: '100%'
        },
        title: {text: null},
        subtitle: {text: null},
        credits: {enabled:false},
        exporting: {enabled:false},
        tooltip: {enabled: false},
        pane: {
            startAngle: 0,
            endAngle: 360,
            background: [{
                outerRadius: '112%',
                innerRadius: '88%',
                backgroundColor: Highcharts.color(colors[0])
                    .setOpacity(0.3)
                    .get(),
                borderWidth: 0
            }, {
                outerRadius: '87%',
                innerRadius: '63%',
                backgroundColor: Highcharts.color(colors[1])
                    .setOpacity(0.3)
                    .get(),
                borderWidth: 0
            }, {
                outerRadius: '62%',
                innerRadius: '38%',
                backgroundColor: Highcharts.color(colors[2])
                    .setOpacity(0.3)
                    .get(),
                borderWidth: 0
            }]
        },
        yAxis: {
            min: 0,
            max: 100,
            lineWidth: 0,
            tickPositions: []
        },
        plotOptions: {
            series: {
                enableMouseTracking: false
            },
            solidgauge: {
                dataLabels: {enabled: false},
                linecap: 'round',
                stickyTracking: false,
                rounded: true
            }
        },
        series: [{
            data: [{
                color: colors[0],
                radius: '112%',
                innerRadius: '88%',
                y: data[0]
            }]
        }, {
            data: [{
                color: colors[1],
                radius: '87%',
                innerRadius: '63%',
                y: data[1]
            }]
        }, {
            data: [{
                color: colors[2],
                radius: '62%',
                innerRadius: '38%',
                y: data[2]
            }]
        }]
    });

    return chart;
}

function drawSimplePie(target_div, data){

    Highcharts.setOptions({
        colors: ['#31a97c', '#91b53b', '#43d7cb', '#0e616b', '#ffde00']
    });

    var chart = Highcharts.chart(target_div, {
        chart: {
            type: 'variablepie',
            style: {
               fontFamily: 'Catamaran',
           },
            height: '80%'
        },
        title: {text: null},
        subtitle: {text: null},
        credits: {enabled:false},
        exporting: {enabled:false},
        tooltip: {enabled: false},
        plotOptions: {
            series: {
                enableMouseTracking: false
            },
            variablepie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                }
            }
        },
        series: [{
            minPointSize: 30,
            maxPointSize: 30,
            innerSize: '60%',
            zMin: 0,
            data: data
        }]
    });

    return chart;
}

// TODO should accept a list of series
// TODO should optionally accept a taregt
function drawDateTimeBarChart(target_div, categories, series_list, line_series, name, stacked) {

    var plotOptions = {};
    var stackLabels = {};
    if(stacked){
        plotOptions = {
            column: {
                stacking: 'normal',
                dataLabels: { enabled: false }
            }
        };

        stackLabels =  {
            enabled: true,
            style: {
                fontWeight: 'normal',
                color: '#999',
                fontSize: '13px'
            },
            formatter: function () {
                return Math.round(this.total / 60000) + ' min';
            }
        }
    }

    var series = [];
    for (var i in series_list) {
        series.push(series_list[i]);
    }
    if (line_series !== undefined) {
        series.push({
            name: 'target',
            linkedTo: 'testo', // allows to hide the series name from the legend
            className: "setpoint",
            data: line_series,
            type: 'line',
            zIndex: 1,
            marker: {
                enabled: false,
                    states: {
                    hover: {
                        enabled: false
                    }
                }
            },
            dashStyle: 'longdash',
            lineWidth: 1,
            color: "#000",
            label: {enabled: false}
        });
    }
    var chart = Highcharts.chart(target_div, {
        chart: {
            type: 'column',
            zoomType: 'x',
            style: {
               fontFamily: 'Catamaran'
            }
        },
        title: {text: null},
        subtitle: {text: null},
        credits: {enabled:false},
        exporting: {enabled:false},
        xAxis: {
            categories: categories,
            labels: {
                style: {
                    color: "#999",
                    fontSize: '13px'
                }
            },
            // TODO fasce verticali per dividere lotti di periodi diversi giorni/settimane/mesi
            // plotBands: [{
            //     from: 1,
            //     to: 5,
            //     color: '#eaf4f0',
            //     label: {
            //         text: 'testo',
            //         style: {color: '#777'}
            //     }
            // }]
        },
        yAxis: {
            title: {text: null},
            type: 'datetime',
            labels: {
                formatter: function() {
                     return Math.round(this.value / 60000) + ' min';
                     // return Highcharts.dateFormat("%H:%M", this.value);
                },
                style: {
                    color: "#999",
                    fontSize: '13px'
                }
            },
            stackLabels: stackLabels,
        },
        plotOptions: plotOptions,
        tooltip: {
            crosshairs: true,
            shared: true,
            formatter: function () {
                return this.points.reduce(function (s, point) {
                    return s + '<br/><span style="color:' + point.color + '">\u25CF</span> ' + point.series.name + ': <b>' + Math.round(point.y / 60000) + ' min</b>';
                    // return s + '<br/><span style="color:' + point.color + '">\u25CF</span> ' + point.series.name + ': <b>' + Highcharts.dateFormat("%H:%M", point.y) + '</b>';
                }, '<b>' + name + ': ' + this.x + '</b>');
            },
        },
        legend: {
            itemStyle: {
                color: "#777",
                fontWeight: "500",
                fontSize: '13px'
            }
        },
        series:series
    });
}
