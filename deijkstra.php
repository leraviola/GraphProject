<?php

namespace Algorithm;

class Deijkstra
{
    // Граф, в котором $graph[node1][node2]=weight 
    protected $graph;

    // Расстояния от исходного узла до каждого другого узла 
    protected $distance;

    // Предыдущий узел(ы) на пути к текущему узлу 
    protected $previous;

    // Узлы, которые еще не были обработаны 
    protected $queue;

    public function __construct($graph)
    {
        $this->graph = $graph;
    }

    /*
    Обработка следующей (то есть ближайшей) записи в очереди
    $exclude - список исключаемых узлов для нахождения следующих кратчайших путей
    */
    protected function processNextNodeInQueue(array $exclude)
    {
        // Обработка ближайшей вершины
        $closest = array_search(min($this->queue), $this->queue);
        if (!empty($this->graph[$closest]) && !in_array($closest, $exclude)) {
            foreach ($this->graph[$closest] as $neighbor => $cost) {
                if (isset($this->distance[$neighbor])) {
                    if ($this->distance[$closest] + $cost < $this->distance[$neighbor]) {
                        // Был найден более короткий путь
                        $this->distance[$neighbor] = $this->distance[$closest] + $cost;
                        $this->previous[$neighbor] = array($closest);
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    } elseif ($this->distance[$closest] + $cost === $this->distance[$neighbor]) {
                        // Был найден такой же короткий путь
                        $this->previous[$neighbor][] = $closest;
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    }
                }
            }
        }
        unset($this->queue[$closest]);
    }

    /*
    Извлекаем ВСЕ пути от $source до $target в виде массивов узлов.
    $target - начальный узел (идем в обратном направлении)
    Возвращается один или несколько кратчайших путей, каждый из которых представлен списком узлов
    */
    protected function extractPaths($target)
    {
        $paths = array(array($target));

        for ($key = 0; isset($paths[$key]); ++$key) {
            $path = $paths[$key];

            if (!empty($this->previous[$path[0]])) {
                foreach ($this->previous[$path[0]] as $previous) {
                    $copy = $path;
                    array_unshift($copy, $previous);
                    $paths[] = $copy;
                }
                unset($paths[$key]);
            }
        }

        return array_values($paths);
    }

    /*
    Вычисляем КРАТЧАЙШИЙ путь в графе от $source до $target.
    $source - Начальный узел
    $target - Последний узел
    $exclude - Список исключаемых узлов - для нахождения следующих кратчайших путей
    Возвращается 0 или больше кратчайших путей, представленных списком узлов
    */
    public function shortestPaths($source, $target, array $exclude = array())
    {
        // Кратчайшее расстояние до всех вершин начинается с бесконечности ...
        $this->distance = array_fill_keys(array_keys($this->graph), INF);
        // ...за исключением начальной вершины
        $this->distance[$source] = 0;

        // Ранее посещенные узлы
        $this->previous = array_fill_keys(array_keys($this->graph), array());

        // Обрабатываем все узлы по порядку
        $this->queue = array($source => 0);
        while (!empty($this->queue)) {
            $this->processNextNodeInQueue($exclude);
        }

        if ($source === $target) {
            // Путь, равный самой вершине
            return array(array($source));
        } elseif (empty($this->previous[$target])) {
            // Нет пути между $source и $target
            return array();
        } else {
            // 1 или больше путей были найдены между $source и $target 
            return $this->extractPaths($target);
        }
    }

}