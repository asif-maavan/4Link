$(document).ready(function () {
//    var btns = document.getElementById('btn-group');
//
//    btns.onclick = function (e) {
//
//        if (e.target.tagName === 'BUTTON') {
//            options.vAxis.format = e.target.id === 'none' ? '' : e.target.id;
//            chart.draw(data, google.charts.Bar.convertOptions(options));
//        }
//    }
    var total = getTotal(graphData);
    $('#mrc').html(total.mrc);
    $('#flp').html(total.flp);


});

google.charts.load('current', {'packages': ['bar']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
//    var data = google.visualization.arrayToDataTable([
//        ['Year', 'Sales', 'Expenses', 'Profit'],
//        ['2014', 1000, 400, 200],
//        ['2015', 1170, 460, 250],
//        ['2016', 660, 1120, 300],
//        ['2017', 1030, 540, 350]
//    ]);
    var data = google.visualization.arrayToDataTable(graphData);

    var options = {
        chart: {
            title: 'Total MRC & Total FLP',
            titleTextStyle: {
                color: '#FF0000',
                bold: true,
            },
            subtitle: 'Sales Executive',
        },
        bars: 'vertical',
        vAxis: {format: 'short', gridlines: {color: "#89d1ce"}},
        height: 400,
        colors: ['#59b3e8', '#0a2c74', '#7570b3']
    };

    var total = getTotal(graphData);
    if (graphData.length > 1 && total.mrc>0 && total.flp) {
        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }else{
        $("#chart_div").css("min-height", "190px");
        $('#chart_div').html('<h3>No Data to display yet</h3>');
    }

}

function getTotal(graphData) {
    var mrc = 0;
    var flp = 0;
    for (var i = 1; i < graphData.length; i++) {
        mrc += graphData[i][1];
        flp += graphData[i][2];
    }

    return {mrc: mrc, flp: flp};
}