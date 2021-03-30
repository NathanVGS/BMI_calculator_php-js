import { calculateBMI, renderChart } from './helperFunctions.js';
import {getUserData, resetHeight, resetWeight, setUserHeight, storeWeightforDate} from './requests.js';

if (window.location.hash && window.location.hash === '#_=_') {
    if (window.history && history.pushState) {
        window.history.pushState("", document.title, window.location.pathname);
    } else {
        // Prevent scrolling by storing the page's current scroll offset
        var scroll = {
            top: document.body.scrollTop,
            left: document.body.scrollLeft
        };
        window.location.hash = '';
        // Restore the scroll offset
        document.body.scrollTop = scroll.top;
        document.body.scrollLeft = scroll.left;
    }
}

document.getElementById('welcome').innerText = 'Welcome ' + userName;
if(window.lastWeight){
    document.getElementById('last-user-weight').innerText = window.lastWeight + 'kg';
    document.getElementById('last-update').innerText = `On: ${lastUpdate.getDate()}/${lastUpdate.getMonth() + 1}/${lastUpdate.getFullYear()}`
} else {
    document.getElementById('last-user-weight').innerText = 'You have not stored any weight yet';
    document.getElementById('canvas-container').style.display = 'none';
}
if(window.userHeight){
    document.getElementById('user-height').innerText = window.userHeight + 'cm';
    document.getElementById('height').value = window.userHeight;

} else {
    document.getElementById('user-height').innerText = 'Please let us know your height first!';
    document.getElementById('canvas-container').style.display = 'none';
}


//resizes without this
const container = document.getElementById('canvas-container');
let width = container.offsetWidth || null;
document.getElementById('chart').style.width = width;

document.getElementById('submitWeight').addEventListener('click', (e) => {
    e.preventDefault();
    let weight = document.getElementById('weight').value;
    let date = document.getElementById('date').value;

    if(!date){
        date = new Date().toDateString();
    }

    storeWeightforDate(weight, date).then(res => {
        location.reload();
    });
})

document.getElementById('saveHeight').addEventListener('click', (e) => {
    e.preventDefault();
    let height = document.getElementById('height').value;

    setUserHeight(height).then(res => {
        location.reload();
    });
})

document.getElementById('updateHeight').addEventListener('click', (e) => {
    e.preventDefault();
    let height = document.getElementById('height').value;

    setUserHeight(height).then(res => {
        location.reload();
    });
})

document.getElementById('reset-button').addEventListener('click', e => {
    let toReset = document.getElementById('reset').value;
    console.log(toReset)
    switch (toReset){
        case 'weight': resetWeight();
            break;
        case 'height': resetHeight();
            break;
    }
})

getUserData().then(res => renderChart(res)).catch(err => console.error(err));
