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