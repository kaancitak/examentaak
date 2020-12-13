<?php
    function maakConnectie(){
        //connectie met databank
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "kaan_examentaak";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn -> connect_error) {
            die("Connection failed: " . $conn -> connect_error);
        }
        return $conn;
	}

    //Array van dier maken
    function maakArray($conn){
        //data selecteren
        $sql = "SELECT * FROM dieren";
        $result = $conn->query($sql);
        $arrDier = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $arrDier[$row["ID"]]['naam'] = $row["naam"];
                $sqlEigenaar = "SELECT 
                eigenaars.ID as id_eigenaar,
                eigenaars.naam as naam_eigenaar
                FROM dier_eigenaar
                INNER JOIN eigenaars 
                ON dier_eigenaar.eigenaar_ID=eigenaars.ID
                WHERE dier_eigenaar.dier_ID =".$row["ID"];

                $sqlAandoening = "SELECT 
                aandoening.ID as id_aandoening,
                aandoening.aandoening as aandoening,
                aandoening.beschrijving_aandoening as beschrijving
                FROM dier_aandoening
                INNER JOIN aandoening
                ON dier_aandoening.aandoening_ID = aandoening.ID
                WHERE dier_aandoening.dier_ID =".$row["ID"];
				
                $sqlBehandeling = "SELECT
                behandelingen.ID as id_behandeling,
				behandelingen.datum as datum_behandeling,
                behandelingen.behandeling as behandeling
                FROM aandoening
                INNER JOIN behandelingen
                ON aandoening.ID=behandelingen.aandoening_ID 
                WHERE behandelingen.dier_ID =".$row["ID"];
                
                $rstEigenaar = $conn->query($sqlEigenaar);
                if ($rstEigenaar->num_rows > 0) {
                    while($rowEigenaar = $rstEigenaar->fetch_assoc()) {
                        $arrDier[$row["ID"]]['eigenaars'][$rowEigenaar["id_eigenaar"]] = array(
                            "naam_eigenaar" => $rowEigenaar["naam_eigenaar"]);
                    }
                }
                $rstAandoening = $conn->query($sqlAandoening);
                if ($rstAandoening->num_rows > 0) {
                    while($rowAandoening = $rstAandoening->fetch_assoc()) {
                        $arrDier[$row["ID"]]['aandoeningen'][$rowAandoening["id_aandoening"]] = array(
                            "aandoening" => $rowAandoening["aandoening"],
                            "beschrijving" => $rowAandoening["beschrijving"]);
                    }
                }

                $rstBehandeling = $conn->query($sqlBehandeling);
                if ($rstBehandeling->num_rows > 0) {
                    while($rowBehandeling = $rstBehandeling->fetch_assoc()) {
                        $arrDier[$row["ID"]]['behandelingen'][$rowBehandeling["id_behandeling"]] = array(
                            "datum_behandeling" => $rowBehandeling["datum_behandeling"],
                            "behandeling" => $rowBehandeling["behandeling"]);
                    }
                }
            }
            
        } else {
            echo "0 results";
        }
        return $arrDier;

    }

    //Dropdown om mijn dier te selecteren
    function kiesDier($arrDier,$idCurrentDier){
        $returnString = "<div class='row'>
                <div class='col-12'>
                    <div class='form-group'>
                        <label for='idCurrentDier'>Kies een dier</label>
                        <select class='form-control' id='idCurrentDier' name='idCurrentDier' onchange='this.form.submit()'>
                            <option value=''>---NIEUW DIER---</option>";
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
	
	    //Dromdown met de consult van mijn dier
    function formDier($arrDier,$idCurrentDier){
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
                </div>
                <div class='col-12'>
                    <h2>Eigenaars</h2>
                </div>
                <div class='col-6'>
                    <div class='form-group'>";
                    foreach ($arrDier[$idCurrentDier]['eigenaars'] as $key => $value) {
                        $returnString .= "<label for='naam_eigenaar'>naam</label>
                        <input type='text' class='form-control' id='eigenaar' name='eigenaar' value='{$value['naam_eigenaar']}'>";
                    };
                $returnString .= "</div>
                </div>
                <div class='col-12'>
                    <h2>Aandoeningen</h2>
                </div>
                <div class='col-6'>
                    <div class='form-group'>";
                    foreach ($arrDier[$idCurrentDier]['aandoeningen'] as $key => $value) {
                        $returnString .= "<label for='aandoening'>aandoening</label>
                        <input type='text' class='form-control' id='aandoening' name='aandoening' value='{$value['aandoening']}'>
                        <label for='beschrijving'>beschrijving</label>
                        <input type='text' class='form-control' id='beschrijving' name='beschrijving' value='{$value['beschrijving']}'>";
                    };
                    $returnString .= "</div>
                </div>
                <div class='col-12'>
                    <h2>Behandelingen</h2>
                </div>
                <div class='col-10'>
                    <div class='form-group'>";
                    foreach ($arrDier[$idCurrentDier]['behandelingen'] as $key => $value) {
                        $returnString .= "<label for='datum_behandeling'>datum</label>
                        <input type='text' class='form-control' id='datum' name='datum' value='{$value['datum_behandeling']}'>
                        <label for='behandeling'>behandeling</label>
                        <input type='text' class='form-control' id='behandeling' name='behandeling' value='{$value['behandeling']}'>";
                    };
                    $returnString .= "</div>
                </div>
            </div><hr>";
            
        }
		else{
            $returnString = PHP_EOL . "<div class='row'>
                <div class='col-12'>
                    <h2>Dier</h2>
                </div>
                <div class='col-6'>
                    <div class='form-group'>
                        <label for='naam'>naam</label>
                        <input type='text' class='form-control' id='naam' name='naam' value=''>
                    </div>
                </div>
            <div class='col-12'>
                <h2>Eigenaar</h2>
            </div>
                <div class='col-6'>
                    <div class='form-group'>
					<label for='naam_eigenaar'>naam</label>
                        <input type='text' class='form-control' id='eigenaar' name='eigenaar' value=''>
						</div>
                </div>
            <div class='col-12'>
                <h2>Aandoeningen</h2>
            </div>
                <div class='col-6'>
                    <div class='form-group'>
                    <label for='aandoening'>aandoening</label>
                        <input type='text' class='form-control' id='aandoening' name='aandoening' value=''>
                        <label for='beschrijving'>beschrijving</label>
                        <input type='text' class='form-control' id='beschrijving' name='beschrijving' value=''>
                    </div>
                </div>
            <div class='col-12'>
                <h2>Behandeling</h2>
            </div>
                <div class='col-10'>
                    <div class='form-group'>
					<label for='datum_behandeling'>datum</label>
                        <input type='text' class='form-control' id='datum' name='datum' value=''>
                        <label for='behandeling'>behandeling</label>
                        <input type='text' class='form-control' id='behandeling' name='behandeling' value=''>
                   </div>
                </div><hr>";
        }
        return $returnString;
    }

    //Maak de knoppen onderaan het formulier
    function buttonBar($idCurrentDier){
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

?>