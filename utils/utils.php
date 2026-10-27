<?php
function formatJoueurs($min, $max){
    if($max == 1){
        $result = "Solo";
        return $result;
    }elseif($min == $max){
        $result = "{$min} joueurs";
        return $result;
    }else{
        $result = "{$min} à {$max} joueurs";
        return $result;
    }
}
function categorieDuree($minutes){
    if($minutes < 30){
        $result = "Partie rapide";
        return $result;
    }elseif($minutes >= 30 && $minutes <= 90){
        $result = "Partie moyenne";
        return $result;
    }else{
        $result = "Partie longue";
        return $result;
    }
}
?>