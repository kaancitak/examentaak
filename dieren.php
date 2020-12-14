<?php
    include 'functies.php';

    $conn = maakConnectie();

    $arrDier = maakArray($conn);
    //print_r($arrDier);

    
        if(isset($_GET['naam'])){
            $naam = $_GET['naam'];
        }else{
            $naam = "";
        }
        if(isset($_GET['datum'])){
            $datum = $_GET['datum'];
        }else{
            $datum = "";
        }
        if(isset($_GET['diersoort'])){
            $diersoort = $_GET['diersoort'];
        }else{
            $diersoort = "";
        }
        if(isset($_GET['ras'])){
            $ras = $_GET['ras'];
        }else{
            $ras = "";
        }

        $sql = "INSERT INTO dieren (naam,datum,diersoort,ras) VALUES ('$naam','$datum','$diersoort','$ras')";
        $result = $conn->query($sql);
    
?>