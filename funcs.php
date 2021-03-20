<?php

//require 'connect.php'; 
require 'deijkstra.php';


function getGraphs($connect) {
    //графы
    $graphs = pg_query($connect, query: "SELECT * FROM graph");
    $graphsList = [];
    while ($graph = pg_fetch_assoc($graphs)) {
        $graphsList[] = $graph;
    }

    echo json_encode($graphsList);
}

function getGraph($connect, $id) {
    //граф
    $graph = pg_query($connect, query: "SELECT * FROM graph where id=".$id);
    if (pg_num_rows($graph)==0) {
        http_response_code(response_code:404);
        $res = [
            "status" => false,
            "message" => "Graph not found"
        ];
        echo json_encode($res);
    } else {
        $graph = pg_fetch_assoc($graph);
        echo json_encode($graph);
    }
}

function getVertices($connect) {
    //вершины
    $vertices = pg_query($connect, query: "SELECT * FROM vertex");
    $verticesList = [];
    while ($vertice = pg_fetch_assoc($vertices)) {
        $verticesList[] = $vertice;
    }

    echo json_encode($verticesList);
}

function getVertex($connect,$id) {
    //вершина
    $vertex = pg_query($connect, query: "SELECT * FROM vertex where id=".$id);
    if (pg_num_rows($vertex)==0) {
        http_response_code(response_code:404);
        $res = [
            "status" => false,
            "message" => "Vertex not found"
        ];
        echo json_encode($res);
    } else {
        $vertex = pg_fetch_assoc($vertex);
        echo json_encode($vertex);
    }
}

function getEdges($connect) {
    //связи
    $edges = pg_query($connect, query: "SELECT * FROM edge");
    $edgesList = [];
    while ($edge = pg_fetch_assoc($edges)) {
        $edgesList[] = $edge;
    }

    echo json_encode($edgesList);
}

function getEdge($connect,$id) {
    //связь
    $edge = pg_query($connect, query: "SELECT * FROM edge where id=".$id);
    if (pg_num_rows($edge)==0) {
        http_response_code(response_code:404);
        $res = [
            "status" => false,
            "message" => "Edge not found"
        ];
        echo json_encode($res);
    } else {
        $edge = pg_fetch_assoc($edge);
        echo json_encode($edge);
    }
}


//создание графа
function createGraph ($connect,$data){
    /*
    //сброс id для правильного порядка нумерации, т.к.
    //в случае постоянного добавления и удаления вершин
    //счетчик все еще идет
    //и порядок нумерации может быть нарушен
    $sql = pg_query($connect, "SELECT id FROM graph ORDER BY id DESC LIMIT 1");
    $last_id = pg_fetch_result($sql,0);
    pg_query($connect, query: "ALTER SEQUENCE graph_id_seq RESTART WITH ".$last_id+1);
    */
    $graph = $data['graph'];
    pg_query($connect, "INSERT INTO graph (name) VALUES ('$graph')");

    //http код, отвечающий за добавление данных
    http_response_code(response_code:201);
    $res = [
        "status" => true,
        "created_graph" => $graph
    ];

    print_r(json_encode($res));
}


//удаление графа
function deleteGraph ($connect, $id){ 
    pg_query($connect, query: "DELETE FROM graph WHERE id=".$id);
    
    //http-код, отвечающий за удаление данных
    http_response_code(response_code:410);
    $res = [
        "status" => true,
        "deleted_graph_id" => $id
    ];

    print_r(json_encode($res));
}


//добавление вершины
function addVertex ($connect,$data){
    $graph_id = $data['graph_id'];
    $coordinate = $data['coordinate'];

    pg_query($connect, "INSERT INTO vertex (graph_id,coordinate) VALUES ('$graph_id','$coordinate')");

    $sql = pg_query($connect, "SELECT id FROM vertex ORDER BY id DESC LIMIT 1");
    $added_vertex_id = pg_fetch_result($sql,0);

    http_response_code(response_code:201);
    $res = [
        "status" => true,
        "added_vertex_id" => $added_vertex_id
    ];

    print_r(json_encode($res));
}


//удаление вершины
function deleteVertex ($connect, $id){ 
    pg_query($connect, query: "DELETE FROM vertex WHERE id=".$id);
    
    //http-код, отвечающий за удаление данных
    http_response_code(response_code:410);
    $res = [
        "status" => true,
        "deleted_vertex_id" => $id
    ];

    print_r(json_encode($res));
}


//связывание вершин
function addEdge ($connect, $data) {
    $vertex_id1 = $data['vertex_id1'];
    $vertex_id2 = $data['vertex_id2'];
    $weight = $data['weight'];
    
    $sql1 = pg_query($connect,"SELECT graph_id FROM vertex WHERE id=".$vertex_id1);
    $sql2 = pg_query($connect,"SELECT graph_id FROM vertex WHERE id=".$vertex_id2);
    $graph_of_ver1 = pg_fetch_result($sql1,0);
    $graph_of_ver2 = pg_fetch_result($sql2,0);

    if ($graph_of_ver1 != $graph_of_ver2) 
    {
        http_response_code(response_code:403);
        $res = [
            "status" => false,
            "message" => "You cannot make edges between vertices of different graphs"
        ];

        echo json_encode($res);
    } 
    else {
        pg_query($connect, "INSERT INTO edge (vertex_id1,vertex_id2,weight) VALUES ('$vertex_id1','$vertex_id2','$weight')");

        $sql = pg_query($connect, "SELECT id FROM edge ORDER BY id DESC LIMIT 1");
        $added_edge_id = pg_fetch_result($sql,0);

        http_response_code(response_code:201);
        $res = [
            "status" => true,
            "added_edge_id" => $added_edge_id
        ];

        print_r(json_encode($res));
    }
}


//изменение веса ребра
function updateWeight($connect,$id,$weight) {
    pg_query($connect, "UPDATE edge SET weight=".$weight." WHERE id=".$id);

    http_response_code(response_code:200);
    $res = [
        "status" => true,
        "updated_edge_id" => $id
    ];

    print_r(json_encode($res));
}

function shortPath($connect,$begin,$end) {
    
    $edges = pg_query($connect, query: "SELECT vertex_id1,vertex_id2,weight FROM edge");

    $ar1=array();
    $ar2=array();
    while($row=pg_fetch_assoc($edges)) {
        $i = $row['vertex_id1'];
        array_push($ar1,$i);
        $j = $row['vertex_id2'];
        array_push($ar2,$j);

        $arr[$i][$j]=$row['weight'];
    }

    //найдем такие vertex_id2, из которых нет путей, 
    //то есть те значения, которые есть в столбце vertex_id2,
    //но нет в vertex_id1
    $empty_vals = array_unique(array_diff($ar2,$ar1));
    //и заполним их пустыми строками
    $emp = array_fill_keys($empty_vals,"");

    //затем присоединим их к нашему массиву
    $arr = $arr + $emp;

    //и найдем короткий путь между вершинами
    $algorithm = new \Algorithm\Deijkstra($arr);
    $path = $algorithm->shortestPaths($begin, $end); 
    
    print_r(json_encode($path));
}

?>