<?php
    // Quick and dirty PHP code example for minesweeper board generation.
    $boardx = 9;
    $boardy = 9;
    $boardmines = 23;

    $board = array();
    for ($y = 0; $y < $boardy; $y++)
    {
        $row = array();

        for ($x = 0; $x < $boardx; $x++)  $row[] = 0;

        $board[] = $row;
    }

    $used = $board;

    $sboard = $board;
    $sused = $board;

    $startx = mt_rand(0, $boardx - 1);
    $starty = mt_rand(0, $boardy - 1);

//$startx = 8;
//$starty = 8;

    $board[$starty][$startx] = -1;

    function GetTotalMines(&$board)
    {
        global $boardx, $boardy;

        $num = 0;

        for ($y = 0; $y < $boardy; $y++)
        {
            for ($x = 0; $x < $boardx; $x++)
            {
                if ($board[$y][$x] > 0)  $num++;
            }
        }

        return $num;
    }

    function GetMaxMinesAllowed(&$board)
    {
        global $boardx, $boardy;

        $num = 0;

        for ($y = 0; $y < $boardy; $y++)
        {
            for ($x = 0; $x < $boardx; $x++)
            {
                if ($board[$y][$x] == 0)  $num++;
            }
        }

        return $num;
    }

    function PlaceRandomMines(&$board, $numleft)
    {
        global $boardx, $boardy;

        $num = GetMaxMinesAllowed($board);

        if ($numleft > $num)  $numleft = $num;

        while ($numleft)
        {
            do
            {
                $posx = mt_rand(0, $boardx - 1);
                $posy = mt_rand(0, $boardy - 1);
            } while ($board[$posy][$posx] != 0);

            $board[$posy][$posx] = 1;

            $numleft--;
        }
    }

    function ClearPoint(&$board, $posx, $posy)
    {
        global $boardx, $boardy;

        $num = 0;

        for ($y = $posy - 1; $y < $posy + 2; $y++)
        {
            if ($y > -1 && $y < $boardy)
            {
                for ($x = $posx - 1; $x < $posx + 2; $x++)
                {
                    if ($x > -1 && $x < $boardx)
                    {
                        if ($board[$y][$x] > 0)  $num++;

                        if ($board[$y][$x] < 2)  $board[$y][$x] = -1;
                    }
                }
            }
        }

        PlaceRandomMines($board, $num);
    }

    function GetNumMinesPoint(&$board, $x, $y)
    {
        global $boardx, $boardy;

        $num = 0;

        if ($x > 0 && $y > 0 && $board[$y - 1][$x - 1] > 0)  $num++;
        if ($y > 0 && $board[$y - 1][$x] > 0)  $num++;
        if ($x < $boardx - 1 && $y > 0 && $board[$y - 1][$x + 1] > 0)  $num++;

        if ($x > 0 && $board[$y][$x - 1] > 0)  $num++;
        if ($x < $boardx - 1 && $board[$y][$x + 1] > 0)  $num++;

        if ($x > 0 && $y < $boardy - 1 && $board[$y + 1][$x - 1] > 0)  $num++;
        if ($y < $boardy - 1 && $board[$y + 1][$x] > 0)  $num++;
        if ($x < $boardx - 1 && $y < $boardy - 1 && $board[$y + 1][$x + 1] > 0)  $num++;

        return $num;
    }

    function OpenBoardPosition(&$points, &$board, &$dispboard, $x, $y)
    {
        global $boardx, $boardy;

        $dispboard[$y][$x] = ($board[$y][$x] > 0 ? $board[$y][$x] : -1);

        $points[] = array($x, $y);

        if (!GetNumMinesPoint($board, $x, $y))
        {
            if ($x > 0 && $y > 0 && $dispboard[$y - 1][$x - 1] == 0)  OpenBoardPosition($points, $board, $dispboard, $x - 1, $y - 1);
            if ($y > 0 && $dispboard[$y - 1][$x] == 0)  OpenBoardPosition($points, $board, $dispboard, $x, $y - 1);
            if ($x < $boardx - 1 && $y > 0 && $dispboard[$y - 1][$x + 1] == 0)  OpenBoardPosition($points, $board, $dispboard, $x + 1, $y - 1);

            if ($x > 0 && $dispboard[$y][$x - 1] == 0)  OpenBoardPosition($points, $board, $dispboard, $x - 1, $y);
            if ($x < $boardx - 1 && $dispboard[$y][$x + 1] == 0)  OpenBoardPosition($points, $board, $dispboard, $x + 1, $y);

            if ($x > 0 && $y < $boardy - 1 && $dispboard[$y + 1][$x - 1] == 0)  OpenBoardPosition($points, $board, $dispboard, $x - 1, $y + 1);
            if ($y < $boardy - 1 && $dispboard[$y + 1][$x] == 0)  OpenBoardPosition($points, $board, $dispboard, $x, $y + 1);
            if ($x < $boardx - 1 && $y < $boardy - 1 && $dispboard[$y + 1][$x + 1] == 0)  OpenBoardPosition($points, $board, $dispboard, $x + 1, $y + 1);
        }
    }

    function GetMinesAtPoint(&$board, $x, $y)
    {
        global $boardx, $boardy;

        $points = array();

        if ($x > 0 && $y > 0 && $board[$y - 1][$x - 1] > 0)  $points[] = array($x - 1, $y - 1);
        if ($y > 0 && $board[$y - 1][$x] > 0)  $points[] = array($x, $y - 1);
        if ($x < $boardx - 1 && $y > 0 && $board[$y - 1][$x + 1] > 0)  $points[] = array($x + 1, $y - 1);

        if ($x > 0 && $board[$y][$x - 1] > 0)  $points[] = array($x - 1, $y );
        if ($x < $boardx - 1 && $board[$y][$x + 1] > 0)  $points[] = array($x + 1, $y);

        if ($x > 0 && $y < $boardy - 1 && $board[$y + 1][$x - 1] > 0)  $points[] = array($x - 1, $y + 1);
        if ($y < $boardy - 1 && $board[$y + 1][$x] > 0)  $points[] = array($x, $y + 1);
        if ($x < $boardx - 1 && $y < $boardy - 1 && $board[$y + 1][$x + 1] > 0)  $points[] = array($x + 1, $y + 1);

        return $points;
    }

    function GetAvailablePoints(&$board, $x, $y)
    {
        global $boardx, $boardy;

        $points = array();

        if ($x > 0 && $y > 0 && $board[$y - 1][$x - 1] == 0)  $points[] = array($x - 1, $y - 1);
        if ($y > 0 && $board[$y - 1][$x] == 0)  $points[] = array($x, $y - 1);
        if ($x < $boardx - 1 && $y > 0 && $board[$y - 1][$x + 1] == 0)  $points[] = array($x + 1, $y - 1);

        if ($x > 0 && $board[$y][$x - 1] == 0)  $points[] = array($x - 1, $y);
        if ($x < $boardx - 1 && $board[$y][$x + 1] == 0)  $points[] = array($x + 1, $y);

        if ($x > 0 && $y < $boardy - 1 && $board[$y + 1][$x - 1] == 0)  $points[] = array($x - 1, $y + 1);
        if ($y < $boardy - 1 && $board[$y + 1][$x] == 0)  $points[] = array($x, $y + 1);
        if ($x < $boardx - 1 && $y < $boardy - 1 && $board[$y + 1][$x + 1] == 0)  $points[] = array($x + 1, $y + 1);

        return $points;
    }

    function DumpBoard($board)
    {
        global $boardx, $boardy;

        for ($y = 0; $y < $boardy; $y++)
        {
            for ($x = 0; $x < $boardx; $x++)
            {
                if ($board[$y][$x] > 0)  echo "* ";
                else
                {
                    $num = GetNumMinesPoint($board, $x, $y);

                    if ($num)  echo $num . " ";
                    else  echo ". ";
                }
            }

            echo "    ";

            for ($x = 0; $x < $boardx; $x++)
            {
                if ($board[$y][$x] == -1)  echo "x ";
                else if ($board[$y][$x] == 0)  echo ". ";
                else if ($board[$y][$x] == 1)  echo "* ";
                else if ($board[$y][$x] == 2)  echo "! ";
                else  echo "? ";
            }

            echo "\n";
        }

        echo "\n";
    }

    // Initial setup.
echo "Hi: 1\n";
    ClearPoint($board, $startx, $starty);
echo "Hi: 2\n";
    PlaceRandomMines($board, $boardmines);
echo "Hi: 3\n";

/*
// Start at 2, 2.
$board = array(
    array(1, 0, 0, 1, 1, 1, 1, 0, 1),
    array(1, -1, -1, -1, 0, 1, 0, 1, 1),
    array(0, -1, -1, -1, 0, 1, 0, 0, 1),
    array(0, -1, -1, -1, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 1, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 1, 1, 0, 0),
    array(1, 0, 1, 1, 1, 1, 0, 0, 0),
    array(1, 0, 0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 1, 0, 1, 1),
);

// Start at 2, 2.
$board = array(
    array(1,  0,  0,  0, 1, 1, 0, 0, 0),
    array(0, -1, -1, -1, 0, 0, 0, 1, 0),
    array(1, -1, -1, -1, 0, 1, 0, 0, 0),
    array(0, -1, -1, -1, 0, 0, 0, 1, 1),
    array(0,  0,  1,  0, 0, 0, 1, 0, 0),
    array(0,  1,  0,  1, 0, 0, 0, 0, 0),
    array(1,  0,  1,  1, 0, 0, 1, 1, 1),
    array(0,  0,  0,  0, 0, 1, 0, 0, 0),
    array(0,  1,  0,  0, 1, 0, 0, 1, 1),
);

// Start at 8, 8.
$board = array(
    array(0, 0, 0, 0, 0, 1, 0, 1, 0),
    array(0, 0, 1, 0, 0, 0, 1, 1, 0),
    array(0, 0, 0, 1, 0, 1, 0, 0, 0),
    array(0, 0, 0, 0, 0, 1, 0, 0, 1),
    array(0, 0, 0, 1, 0, 1, 0, 0, 0),
    array(0, 0, 1, 0, 1, 1, 1, 0, 1),
    array(0, 0, 0, 0, 1, 0, 0, 0, 1),
    array(0, 0, 1, 0, 0, 0, 1, -1, -1),
    array(0, 0, 1, 0, 1, 0, 1, -1, -1),
);
*/

    // Attempt to solve.
    $solver = array();
    $minesleft = GetTotalMines($board);
echo "Hi: 4\n";
    $spacesleft = $boardx * $boardy;
    OpenBoardPosition($solver, $board, $sboard, $startx, $starty);
echo "Hi: 5\n";

    foreach ($solver as $num => $point)
    {
        $sboard[$point[1]][$point[0]] = -1;
        $board[$point[1]][$point[0]] = -1;
    }

    while (count($solver))
    {
        $spacesleft2 = $spacesleft;
        $numpoints = count($solver);

        // Find exact matches.
        foreach ($solver as $num => $point)
        {
//echo $point[0] . ", " . $point[1] . ":  ";
            $mines = GetMinesAtPoint($board, $point[0], $point[1]);
            $smines = GetMinesAtPoint($sboard, $point[0], $point[1]);
            $savail = GetAvailablePoints($sboard, $point[0], $point[1]);

            if (count($mines) == count($smines))
            {
//echo "Path 1\n";
                // Clear the remaining spaces.
                foreach ($savail as $point2)
                {
                    $sboard[$point2[1]][$point2[0]] = -1;
                    $board[$point2[1]][$point2[0]] = -1;

                    $spacesleft--;

                    $solver[] = $point2;
                }

                unset($solver[$num]);

                $sboard[$point[1]][$point[0]] = -1;
                $board[$point[1]][$point[0]] = -1;

                $spacesleft--;
            }
            else if (count($mines) == count($smines) + count($savail))
            {
//echo "Path 2\n";
                // Fill in the remaining spaces with mines.
                foreach ($savail as $point2)
                {
                    $sboard[$point2[1]][$point2[0]] = 1;
                    $board[$point2[1]][$point2[0]] = 2;

                    $spacesleft--;
                }

                unset($solver[$num]);

                $sboard[$point[1]][$point[0]] = -1;
                $board[$point[1]][$point[0]] = -1;

                $spacesleft--;
            }
            else if (count($mines) - count($smines) == 2 && count($savail) == 3)
            {
//echo "Path 3\n";
                // Try vertical 1 2 1.
                $found = false;
                if ($point[1] > 0 && $point[1] < $boardy - 1 && $board[$point[1] - 1][$point[0]] <= 0 && $board[$point[1] + 1][$point[0]] <= 0)
                {
//echo "Path 3a\n";
                    $mines2 = GetMinesAtPoint($board, $point[0], $point[1] - 1);
                    $smines2 = GetMinesAtPoint($sboard, $point[0], $point[1] - 1);
                    $mines3 = GetMinesAtPoint($board, $point[0], $point[1] + 1);
                    $smines3 = GetMinesAtPoint($sboard, $point[0], $point[1] + 1);

                    if (count($mines2) - count($smines2) == 1 && count($mines3) - count($smines3) == 1)
                    {
                        foreach ($savail as $point2)
                        {
                            if ($point2[1] == $point[1])
                            {
                                $sboard[$point2[1]][$point2[0]] = -1;
                                $board[$point2[1]][$point2[0]] = -1;

                                $solver[] = $point2;
                            }
                            else
                            {
                                $sboard[$point2[1]][$point2[0]] = 1;
                                $board[$point2[1]][$point2[0]] = 2;
                            }

                            $spacesleft--;
                        }

                        unset($solver[$num]);

                        $sboard[$point[1]][$point[0]] = -1;
                        $board[$point[1]][$point[0]] = -1;

                        $spacesleft--;

                        $found = true;
                    }
                }

                // Try horizontal 1 2 1.
                if (!$found && $point[0] > 0 && $point[0] < $boardx - 1 && $board[$point[1]][$point[0] - 1] <= 0 && $board[$point[1]][$point[0] + 1] <= 0)
                {
//echo "Path 3b\n";
                    $mines2 = GetMinesAtPoint($board, $point[0] - 1, $point[1]);
                    $smines2 = GetMinesAtPoint($sboard, $point[0] - 1, $point[1]);
                    $mines3 = GetMinesAtPoint($board, $point[0] + 1, $point[1]);
                    $smines3 = GetMinesAtPoint($sboard, $point[0] + 1, $point[1]);

                    if (count($mines2) - count($smines2) == 1 && count($mines3) - count($smines3) == 1)
                    {
                        foreach ($savail as $point2)
                        {
                            if ($point2[0] == $point[0])
                            {
                                $sboard[$point2[1]][$point2[0]] = -1;
                                $board[$point2[1]][$point2[0]] = -1;

                                $solver[] = $point2;
                            }
                            else
                            {
                                $sboard[$point2[1]][$point2[0]] = 1;
                                $board[$point2[1]][$point2[0]] = 2;
                            }

                            $spacesleft--;
                        }

                        unset($solver[$num]);

                        $sboard[$point[1]][$point[0]] = -1;
                        $board[$point[1]][$point[0]] = -1;

                        $spacesleft--;
                    }
                }
            }
            else if (count($mines) - count($smines) == 1 && count($savail) == 3)
            {
//echo "Path 4\n";
                // Determine directionality.
                if ($savail[0][0] == $savail[1][0] && $savail[0][0] == $savail[2][0])
                {
//echo "Path 4a\n";
                    // Vertical up.
                    if ($point[1] > 0 && $board[$point[1] - 1][$point[0]] <= 0)
                    {
                        $mines2 = GetMinesAtPoint($board, $point[0], $point[1] - 1);
                        $smines2 = GetMinesAtPoint($sboard, $point[0], $point[1] - 1);
                        $savail2 = GetAvailablePoints($sboard, $point[0], $point[1] - 1);

                        if (count($mines2) - count($smines2) == 1 && count($savail2) == 2)
                        {
                            $x = $savail[0][0];
                            $y = max($savail[0][1], $savail[1][1], $savail[2][1]);

                            $sboard[$y][$x] = -1;
                            $board[$y][$x] = -1;

                            $solver[] = array($x, $y);
                        }
                    }

                    if ($point[1] < $boardy - 1 && $board[$point[1] + 1][$point[0]] <= 0)
                    {
                        $mines2 = GetMinesAtPoint($board, $point[0], $point[1] + 1);
                        $smines2 = GetMinesAtPoint($sboard, $point[0], $point[1] + 1);
                        $savail2 = GetAvailablePoints($sboard, $point[0], $point[1] + 1);

                        if (count($mines2) - count($smines2) == 1 && count($savail2) == 2)
                        {
                            $x = $savail[0][0];
                            $y = min($savail[0][1], $savail[1][1], $savail[2][1]);

                            $sboard[$y][$x] = -1;
                            $board[$y][$x] = -1;

                            $solver[] = array($x, $y);
                        }
                    }
                }
                else if ($savail[0][1] == $savail[1][1] && $savail[0][1] == $savail[2][1])
                {
//echo "Path 4b\n";
                    // Horizontal left.
                    if ($point[0] > 0 && $board[$point[1]][$point[0] - 1] <= 0)
                    {
                        $mines2 = GetMinesAtPoint($board, $point[0] - 1, $point[1]);
                        $smines2 = GetMinesAtPoint($sboard, $point[0] - 1, $point[1]);
                        $savail2 = GetAvailablePoints($sboard, $point[0] - 1, $point[1]);

                        if (count($mines2) - count($smines2) == 1 && count($savail2) == 2)
                        {
                            $x = max($savail[0][0], $savail[1][0], $savail[2][0]);
                            $y = $savail[0][1];

                            $sboard[$y][$x] = -1;
                            $board[$y][$x] = -1;

                            $solver[] = array($x, $y);
                        }
                    }

                    // Horizontal right.
                    if ($point[0] < $boardx - 1 && $board[$point[1]][$point[0] + 1] <= 0)
                    {
                        $mines2 = GetMinesAtPoint($board, $point[0] + 1, $point[1]);
                        $smines2 = GetMinesAtPoint($sboard, $point[0] + 1, $point[1]);
                        $savail2 = GetAvailablePoints($sboard, $point[0] + 1, $point[1]);

                        if (count($mines2) - count($smines2) == 1 && count($savail2) == 2)
                        {
                            $x = min($savail[0][0], $savail[1][0], $savail[2][0]);
                            $y = $savail[0][1];

                            $sboard[$y][$x] = -1;
                            $board[$y][$x] = -1;

                            $solver[] = array($x, $y);
                        }
                    }
                }
            }
            else if (count($mines) - count($smines) == 1 && count($savail) == 2)
            {
//echo "Path 5\n";
                // Determine directionality.
                $point2 = false;
                if ($savail[0][1] == $savail[1][1] && ($point[0] == $savail[0][0] || $point[0] == $savail[1][0]))
                {
                    // Horizontal left.
                    if ($point[0] - 1 == $savail[0][0] || $point[0] - 1 == $savail[1][0])  $point2 = array($point[0] - 1, $point[1]);

                    // Horizontal right.
                    if ($point[0] + 1 == $savail[0][0] || $point[0] + 1 == $savail[1][0])  $point2 = array($point[0] + 1, $point[1]);
                }
                else if ($savail[0][0] == $savail[1][0] && ($point[1] == $savail[0][1] || $point[1] == $savail[1][1]))
                {
                    // Vertical up.
                    if ($point[1] - 1 == $savail[0][1] || $point[1] - 1 == $savail[1][1])  $point2 = array($point[0], $point[1] - 1);

                    // Vertical down.
                    if ($point[1] + 1 == $savail[0][1] || $point[1] + 1 == $savail[1][1])  $point2 = array($point[0], $point[1] + 1);
                }

                if ($point2 !== false)
                {
//echo "Path 5a\n";
                    $mines2 = GetMinesAtPoint($board, $point2[0], $point2[1]);
                    $smines2 = GetMinesAtPoint($sboard, $point2[0], $point2[1]);
                    $savail2 = GetAvailablePoints($sboard, $point2[0], $point2[1]);

                    if (count($mines2) - count($smines2) == 1)
                    {
                        foreach ($savail2 as $point2)
                        {
                            if (($point2[0] == $savail[0][0] && $point2[1] == $savail[0][1]) || ($point2[0] == $savail[1][0] && $point2[1] == $savail[1][1]))  continue;

                            $sboard[$point2[1]][$point2[0]] = -1;
                            $board[$point2[1]][$point2[0]] = -1;

                            $solver[] = $point2;
                        }
                    }
                }
            }
        }

        if ($spacesleft2 == $spacesleft && count($solver) == $numpoints)
        {
//echo "Path FAILED\n";
            $minnum = false;
            $total = 0;
            $spaces = 0;
            foreach ($solver as $num => $point)
            {
                $mines = GetMinesAtPoint($board, $point[0], $point[1]);
                $smines = GetMinesAtPoint($sboard, $point[0], $point[1]);
                $savail = GetAvailablePoints($sboard, $point[0], $point[1]);

                if ($minnum === false || $total > count($mines2) - count($smines2) || ($total == count($mines2) - count($smines2) && $spaces > count($savail)))
                {
                    $minnum = $num;
                    $total = count($mines2) - count($smines2);
                    $spaces = count($savail);
                }
            }

            if ($minnum !== false)  ClearPoint($board, $solver[$minnum][0], $solver[$minnum][1]);
            else
            {
//echo "No more options.\n";
                break;
            }
        }
    }
var_dump($solver);

    // Fill awkward positions with mines.
    for ($y = 0; $y < $boardy; $y++)
    {
        for ($x = 0; $x < $boardx; $x++)
        {
            if ($board[$y][$x] == 0)
            {
                $board[$y][$x] = 1;

                $minesleft++;
            }
            else if ($board[$y][$x] == -1)
            {
                $maxmines = 0;
                if ($x > 0 && $y > 0)  $maxmines++;
                if ($y > 0)  $maxmines++;
                if ($x < $boardx - 1 && $y > 0)  $maxmines++;

                if ($x > 0)  $maxmines++;
                if ($x < $boardx - 1)  $maxmines++;

                if ($x > 0 && $y < $boardy - 1)  $maxmines++;
                if ($y < $boardy - 1)  $maxmines++;
                if ($x < $boardx - 1 && $y < $boardy - 1)  $maxmines++;

                $mines = GetMinesAtPoint($board, $x, $y);

                if (count($mines) == $maxmines)
                {
                    $board[$y][$x] = 1;

                    $minesleft++;
                }
            }
        }
    }


DumpBoard($board);
DumpBoard($sboard);
var_dump($minesleft);
echo $startx . ", " . $starty . "\n";
var_dump($board[$starty][$startx]);
?>
