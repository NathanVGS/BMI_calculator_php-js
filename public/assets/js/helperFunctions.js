function getLabelsFrom(dataToRender){
    let labels = [];
    dataToRender.forEach(entry => {
        //TODO convert dates to other format here?
        const date = new Date(entry.date)
        console.log()
        labels.push(`${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`)
    })

    return labels;
}

function getDataFrom(dataToRender){
    let data = [];
    dataToRender.forEach(entry => {
        data.push(calculateBMI(parseFloat(window.userHeight), parseFloat(entry.weight)));
    })

    return data;
}

export function calculateBMI(height, weight){
    const heightM = height/100;
    return Math.round(100 * weight/(heightM * heightM)) / 100;
}

export function renderChart(dataToRender){

    console.log(dataToRender);
    let labels = getLabelsFrom(dataToRender);
    let data = getDataFrom(dataToRender);

    const ctx = document.getElementById('chart').getContext('2d');
    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'BMI values',
                fill: false,
                data: data,
                backgroundColor: [
                    'blue'
                    /*'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'*/
                ],
                borderColor: [
                    'black'
                    /*'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'*/
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                }]
            },
            //responsive: true, //TODO make responsive?
            //TODO fill chart with data stored on window object?
        }
    });
}

