export async function storeWeightforDate(weight, date = new Date()) {
    let data = new FormData();
    data.append('weight', weight);
    data.append('date', date);

    let response = await axios.post('api/', data)

    console.log(response.data);
    return response.data;
}

export async function setUserHeight(height){
    let data = new FormData();
    data.append('height', height);

    let response = await axios.post('api/', data)

    return response.data;
}

export async function getUserData(){
    let response = await axios.get('api/')

    return response.data;
}

export async function resetWeight(){
    let data = new FormData();
    data.append('reset', 'weight');
    await axios.post('api/', data)
}
export async function resetHeight(){
    let data = new FormData();
    data.append('reset', 'height');
    await axios.post('api/', data)
}