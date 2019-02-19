<?php

function SumTime($time,$type){
    $ftime = $time;
    if($type == 0){
        $int = floor($time);
        $float = $time - $int;
        if($float == 0){
            $ftime = $int;
        }elseif($float <= 0.5){
            $ftime = $int + 0.5;
        }else{
            $ftime = $int + 1;
        }
        if($ftime < 1){
            $ftime = 1;
        }
    }else{
        $int = floor($time);
        $float = $time - $int;
        if($float < 0.5){
            $ftime = $int;
        }else{
            $ftime = $int + 0.5;
        }
    }
    return $ftime;
}

?>
