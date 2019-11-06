<?php

class LatticePaths
{

    private $grid;

    private function show_grid()
    {
        echo '<table>';
        foreach ($this->grid as $height => $arrW) {
            echo '<tr>';
            foreach ($arrW as $width => $paths) {
                echo '<td>';
                foreach ($paths as $key => $v) {
                    echo "$key<br>";
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    private function create_grid(int $width, int $height): array
    {
        $resultGrid = [];

        for ($i = 1; $i <= $height; $i++) {
            for ($j = 1; $j <= $width; $j++) {
                if ($i < $height) {
                    $nextI = $i + 1;

                    $resultGrid[$i][$j]["$nextI:$j"] = true;
                }
                if ($j < $width) {
                    $nextJ = $j + 1;

                    $resultGrid[$i][$j]["$i:$nextJ"] = true;
                }
                if ($i >= $height && $j >= $width) {
                    $resultGrid[$i][$j] = ['EMPTY'];
                }
            }
        }

        return $resultGrid;
    }

    private function find_count_paths(SplQueue $queue): int
    {
        $countPaths = 0;

        if ($queue->isEmpty()) {
            return $countPaths;
        }

        foreach ($queue->top() as $key => $val) {
            if ($val === true) {
                $point       = $queue->pop();
                preg_match('/^(\d+):(\d+)$/', $key, $matches);
                $point[$key] = false;
                $queue->push($point);
                break;
            }
            if ($val === 'EMPTY') {
                $countPaths++;
            }
        }
//        print_r($this->show_grid());
//        print_r($queue->top());

        if ($matches) {
            $queue->push($this->grid[$matches[1]][$matches[2]]);
        } else {
            $queue->pop();
        }

        return $countPaths + $this->find_count_paths($queue);
    }

    public function init($width, $height)
    {
        $this->grid = $this->create_grid($width, $height);
        print_r($this->show_grid());
        $queue      = new SplQueue();
        $queue->push($this->grid[1][1]);
        return $this->find_count_paths($queue);
    }

}

echo '<pre>';
print_r((new LatticePaths())->init(20, 20));
