<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: json/application');

//подключаемся к бд
require 'connect.php';
require 'funcs.php';

//методы - get, post, delete etc
$method = $_SERVER['REQUEST_METHOD'];

$q=$_GET['q'];
$params=array_pad(explode('/',$q), 2, null);
$type=$params[0];
$id=$params[1];


switch ($method){
    case 'GET':
        switch($type){
            case 'graphs':
                if(isset($id)) getGraph($connect,$id);
                else getGraphs($connect);
                break;
            case 'vertices':
                if(isset($id)) getVertex($connect,$id);
                else getVertices($connect);
                break;
            case 'edges':
                if(isset($id)) getEdge($connect,$id);
                else getEdges($connect);
                break;
            case 'deijkstra': 
                if(isset($id)) {
                    $path=explode('-',$id);
                    $begin=(int)$path[0];
                    $end=(int)$path[1];
                    shortPath($connect,$begin,$end);
                }
                break;
        }
        break;
    case 'POST':
        switch($type){
            case 'graphs':
                createGraph($connect,$_POST);
                break;
            case 'vertices':
                addVertex($connect,$_POST);
                break;
            case 'edges':
                addEdge($connect,$_POST);
                break;
        }
        break;
    case 'DELETE':
        switch($type) {
            case 'graphs':
                if(isset($id)) deleteGraph($connect,$id);
                break;
            case 'vertices':
                if(isset($id)) deleteVertex($connect,$id);
                break;
        }
        break;
    case 'PATCH':
        switch($type) {
            case 'edges':
                $data=file_get_contents('php://input');
                $data=json_decode($data);
                $data=$data->weight;
                if(isset($id)) updateWeight($connect,$id,$data);
        }
        break;
}


?>