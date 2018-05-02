$(function() {

    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010 Q1',
            ph: 5.6
        }, {
            period: '2010 Q2',
            ph: 7.4
        }, {
            period: '2010 Q3',
            ph: 6.2
        }, {
            period: '2010 Q4',
            ph: 6.5
        }, {
            period: '2011 Q1',
            ph: 6.5
        }, {
            period: '2011 Q2',
            ph: 6.4
        }, {
            period: '2011 Q3',
            ph: 12.8
        }, {
            period: '2011 Q4',
            ph: 3.4
        }, {
            period: '2012 Q1',
            ph: 6.0
        }, {
            period: '2012 Q2',
            ph: 6.5
        }],
        xkey: 'period',
        ykeys: ['ph'],
        labels: ['pH'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });

    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Download Sales",
            value: 12
        }, {
            label: "In-Store Sales",
            value: 30
        }, {
            label: "Mail-Order Sales",
            value: 20
        }],
        resize: true
    });

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90
        }, {
            y: '2007',
            a: 75,
            b: 65
        }, {
            y: '2008',
            a: 50,
            b: 40
        }, {
            y: '2009',
            a: 75,
            b: 65
        }, {
            y: '2010',
            a: 50,
            b: 40
        }, {
            y: '2011',
            a: 75,
            b: 65
        }, {
            y: '2012',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        hideHover: 'auto',
        resize: true
    });
    
});
