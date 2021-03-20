/*async function deleteGraph(){

    const table = document.getElementById('table').value;
    const data = { table_name: table };

    let res = await fetch('http://myTask/deletegraph', {method:'DELETE', body: JSON.stringify(data)});

    console.log(res);
}


async function deleteVertice(){

    const table = document.getElementById('table2').value;
    const vertice = document.getElementById('vertice').value;
    const data = { table_name: table, id_delete: vertice };

    let res = await fetch('http://myTask/deletevertice', {method:'DELETE', body: JSON.stringify(data)});

    console.log(res);
}


async function getGraph(){
    const table = document.getElementById('table3').value;

    let res = await fetch('http://myTask/getgraph/'+table);
    let weights = await res.json();

    jsonString = JSON.stringify(weights);
    document.querySelector('.weight-list').innerHTML = '';
    document.querySelector('.weight-list').innerHTML += jsonString;

    console.log(res);
}


async function createGraph() {
    const table = document.getElementById('table4').value;
    let data = new FormData();
    data.append('table', table);

    let res = await fetch('http://myTask/creategraph', 
                          {method:'POST', body: data});

    console.log(res);
}


async function addWeight() {
    const table = document.getElementById('table5').value;
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const weight = document.getElementById('weight').value;

    let data = new FormData();
    data.append('table', table);
    data.append('from', from);
    data.append('to', to);
    data.append('weight', weight);

    let res = await fetch('http://myTask/addweight', 
                          {method:'POST', body: data});

    console.log(res);
}

$('p').click(function() {
    alert("Hello, world!");
})*/