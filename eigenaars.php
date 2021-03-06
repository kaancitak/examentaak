<?php
    include 'functies.php';

    $conn = maakConnectie();

    $arrDier = maakArray1($conn);
	
	//Array van dier maken
    function maakArray1($conn){
        //data selecteren
        $sql = "SELECT * FROM eigenaars";
        $result = $conn->query($sql);
        $arrDier = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $arrDier[$row["ID"]]['naam'] = $row["naam"];
                $arrDier[$row["ID"]]['telefoonnummer'] = $row["telefoonnummer"];
                $arrDier[$row["ID"]]['email'] = $row["email"];
                $arrDier[$row["ID"]]['adres'] = $row["adres"];
            }    
        } else {
            echo "0 results";
        }
        return $arrDier;

    }
	//Dropdown om mijn eigenaar te selecteren
    function kiesDier1($arrDier,$idCurrentDier){
        $returnString = "<div class='row'>
                <div class='col-12'>
                    <div class='form-group'>
                        <label for='idCurrentDier'>Kies een eigenaar</label>
                        <select class='form-control' id='idCurrentDier' name='idCurrentDier' onchange='this.form.submit()'>
                            <option value=''>---NIEUW EIGENAAR---</option>";
        foreach($arrDier as $key => $value){
            $selected = NULL;
            if($key == $idCurrentDier){
                $selected = "SELECTED";
            }
              $returnString .="
                            <option value='$key' $selected >{$value['naam']}</option>";
        }
        $returnString .= "
                        </select>
                    </div>
                </div>
            </div>
            <hr>";
        return $returnString;
    }
	//Dromdown met de gegevens van mijn eigenaar
    function formDier1($arrDier,$idCurrentDier){
        $returnString = NULL;
        if($idCurrentDier != NULL){
            $returnString = PHP_EOL . "
            <div class='row'>
                <div class='col-12'>
                    <h2>Dier</h2>
                </div>
                <div class='col-6'>
                    <div class='form-group'>
                        <label for='naam'>naam</label>
                        <input type='text' class='form-control' id='naam' name='naam' value='{$arrDier[$idCurrentDier]['naam']}'>
                    </div>
                    <div class='form-group'>
                        <label for='telefoonnummer'>telefoonnummer</label>
                        <input type='date' class='form-control' id='telefoonnummer' name='telefoonnummer' value='{$arrDier[$idCurrentDier]['telefoonnummer']}'>
                    </div>
                </div>
                <div class='col-6'>
                    <div class='form-group'>
                        <label for='email'>email</label>
                        <input type='text' class='form-control' id='email' name='email' value='{$arrDier[$idCurrentDier]['email']}'>
                    </div>
                    <div class='form-group'>
                        <label for='adres'>adres</label>
                        <input type='text' class='form-control' id='adres' name='adres' value='{$arrDier[$idCurrentDier]['adres']}'>
                    </div>
                </div>
            </div><hr>";
            
        }else{
            $returnString = PHP_EOL . "<div class='row'>
                <div class='col-12'>
                    <h2>Dier</h2>
                </div>
                <div class='col-6'>
                    <div class='form-group'>
                        <label for='naam'>naam</label>
                        <input type='text' class='form-control' id='naam' name='naam' value=''>
                    </div>
                    <div class='form-group'>
                        <label for='telefoonnummer'>telefoonnummer</label>
                        <input type='date' class='form-control' id='telefoonnummer' name='telefoonnummer' value=''>
                    </div>
                </div>    
                <div class='col-6'>
                    <div class='form-group'>
                        <label for='email'>email</label>
                        <input type='text' class='form-control' id='email' name='email' value=''>
                    </div>
                    <div class='form-group'>
                        <label for='adres'>adres</label>
                        <input type='text' class='form-control' id='adres' name='adres' value=''>
                    </div>
                </div>
            </div><hr>";
        }
        return $returnString;
    }
	
	//Maak de knoppen onderaan het formulier
    function buttonBar1($idCurrentDier){
        $returnString = NULL;
        if($idCurrentDier==NULL){
            //Knoppen voor een nieuw dier
            $returnString .="
            <div class='row'>
                <div class='col-md-12 text-center'>
                    <div class='btn-group' role='group'>
                      <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='newDier'; this.form.submit()\"><i class='fa fa-plus'></i> Maak nieuw dier</button>
                      <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
                    </div>
                </div>
            </div>";
        }else{
            //Knoppen voor een bestaand dier
            $returnString .="
            <div class='row'>
                <div class='col-md-12 text-center'>
                    <div class='btn-group' role='group'>
                      <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='updateDier'; this.form.submit()\"><i class='fa fa-check'></i> Gegevens actualiseren</button>
                      <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
                    </div>
                </div>
            </div>";
        }
        return $returnString;
    }
	
	$arrDier = maakArray1($conn);

$idCurrentDier = NULL;
if(isset($_GET['idCurrentDier'])){
    $idCurrentDier = $_GET['idCurrentDier'];
}

$actie = NULL;

if(isset($_GET['actie'])){
    $actie = $_GET['actie'];
    $_GET['actie'] = NULL;
}

if($idCurrentDier != NULL && $actie=="updateDier"){
    $sql = "UPDATE eigenaars SET 
    naam = '{$_GET['naam']}', 
    telefoonnummer = '{$_GET['telefoonnummer']}', 
    email = '{$_GET['email']}', 
    adres = '{$_GET['adres']}'    
    WHERE ID = $idCurrentDier";
    if ($conn->query($sql) === TRUE) {
      $arrDier = maakArray1($conn);
    } else {
      echo "Error updating record: " . $conn->error;
    }
}elseif(isset($_GET['naam']) && $actie=="newDier"){
    $sql = "INSERT INTO eigenaars (naam, telefoonnummer, email, adres)
VALUES ('{$_GET['naam']}', '{$_GET['telefoonnummer']}', '{$_GET['email']}', '{$_GET['adres']}')";

    if ($conn->query($sql) === TRUE) {
      $idCurrentDier = $conn->insert_id;
      $arrDier = maakArray1($conn);
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!doctype html>
<html lang="nl">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <title>Eigenaar</title>
  </head>
  <body>
    <form method="GET">
        <input type="hidden" name="actie" value="">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>EIGENAAR</h1>
                </div>
            </div>
            <hr>
            <?php print kiesDier1($arrDier,$idCurrentDier); ?>
            <?php print formDier1($arrDier,$idCurrentDier); ?>
            <?php print buttonBar1($idCurrentDier) ?>
        </div>
    </form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

