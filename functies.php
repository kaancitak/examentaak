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

	

	
	//Array van boeken maken
	function maakArray($conn){
		//data selecteren
		$sql = "SELECT * FROM tblboek";
		$result = $conn->query($sql);
		$arrBoek = array();
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$arrBoek[$row["id"]]['titel'] = $row["titel"];
				$arrBoek[$row["id"]]['ISBN'] = $row["ISBN"];
				$arrBoek[$row["id"]]['paginas'] = $row["paginas"];
				$arrBoek[$row["id"]]['druk'] = $row["druk"];
				$arrBoek[$row["id"]]['uitgegeven'] = $row["uitgegeven"];
				$sqlEigenschap = "SELECT 
				tbleigenschap.id as idEigenschap,
				tbleigenschap.categorie as categorie,
				tbleigenschap.naam as naam
				FROM tblboekeigenschap
				INNER JOIN tbleigenschap 
				ON tblboekeigenschap.idEigenschap=tbleigenschap.id
				WHERE tblboekeigenschap.idBoek =".$row["id"];
				
				$rstEigenschap = $conn->query($sqlEigenschap);
				if ($rstEigenschap->num_rows > 0) {
					while($rowEigenschap = $rstEigenschap->fetch_assoc()) {
						$arrBoek[$row["id"]]['eigenschappen'][$rowEigenschap["idEigenschap"]] = array(
							"categorie" => $rowEigenschap["categorie"],
							"naam" => $rowEigenschap["naam"]);
					}
				}
			}
			
		} else {
			echo "0 results";
		}
		return $arrBoek;
	}

	//Dropdown om mijn boek te selecteren
	function kiesBook($arrBoek,$idCurrentBook){
		$returnString = "<div class='row'>
				<div class='col-12'>
					<div class='form-group'>
						<label for='idCurrentBook'>Kies een boek</label>
						<select class='form-control' id='idCurrentBook' name='idCurrentBook' onchange='this.form.submit()'>
							<option value=''>---NIEUW BOEK---</option>";
		foreach($arrBoek as $key => $value){
			$selected = NULL;
			if($key == $idCurrentBook){
				$selected = "SELECTED";
			}
			  $returnString .="
							<option value='$key' $selected >{$value['titel']}</option>";
		}
		$returnString .= "
						</select>
					</div>
				</div>
			</div>
			<hr>";
		return $returnString;
	}
	
	//Dromdown met de gegevens van mijn boek
	function formBoek($arrBoek,$idCurrentBook){
		$returnString = NULL;
		if($idCurrentBook != NULL){
			$returnString = PHP_EOL . "
			<div class='row'>
				<div class='col-12'>
					<h2>Kenmerken</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='titel'>titel</label>
						<input type='text' class='form-control' id='titel' name='titel' value='{$arrBoek[$idCurrentBook]['titel']}'>
					</div>
					<div class='form-group'>
						<label for='ISBN'>ISBN</label>
						<input type='text' class='form-control' id='ISBN' name='ISBN' value='{$arrBoek[$idCurrentBook]['ISBN']}'>
					</div>
					<div class='form-group'>
						<label for='paginas'>paginas</label>
						<input type='text' class='form-control' id='paginas' name='paginas' value='{$arrBoek[$idCurrentBook]['paginas']}'>
					</div>
				</div>	
				<div class='col-6'>
					<div class='form-group'>
						<label for='druk'>druk</label>
						<input type='text' class='form-control' id='druk' name='druk' value='{$arrBoek[$idCurrentBook]['druk']}'>
					</div>
					<div class='form-group'>
						<label for='uitgegeven'>uitgegeven op</label>
						<input type='date' class='form-control' id='uitgegeven' name='uitgegeven' value='{$arrBoek[$idCurrentBook]['uitgegeven']}'>
					</div>
				</div>
			</div>
			<div class='col-12'>
				<h2>Eigenschappen</h2>
			</div><hr>";
			
		}else{
			$returnString = PHP_EOL . "<div class='row'>
				<div class='col-12'>
					<h2>Kenmerken</h2>
				</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='titel'>titel</label>
						<input type='text' class='form-control' id='titel' name='titel' value=''>
					</div>
					<div class='form-group'>
						<label for='ISBN'>ISBN</label>
						<input type='text' class='form-control' id='ISBN' name='ISBN' value=''>
					</div>
					<div class='form-group'>
						<label for='paginas'>paginas</label>
						<input type='text' class='form-control' id='paginas' name='paginas' value=''>
					</div>
				</div>
				<div class='col-6'>
					<div class='form-group'>
						<label for='druk'>druk</label>
						<input type='text' class='form-control' id='druk' name='druk' value=''>
					</div>
					<div class='form-group'>
						<label for='uitgegeven'>uitgegeven op</label>
						<input type='date' class='form-control' id='uitgegeven' name='uitgegeven' value=''>
					</div>
				</div>
			</div>
			<div class='col-12'>
				<h2>Eigenschappen</h2>
			</div><hr>";
		}
		return $returnString;
	}
	//Maak de knoppen onderaan het formulier
	function buttonBar($idCurrentBook){
		$returnString = NULL;
		if($idCurrentBook==NULL){
			//Knoppen voor een nieuw boek
			$returnString .="
			<div class='row'>
				<div class='col-md-12 text-center'>
					<div class='btn-group' role='group'>
					  <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='newBook'; this.form.submit()\"><i class='fa fa-plus'></i> Maak nieuw boek</button>
					  <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
					</div>
				</div>
			</div>";
		}else{
			//Knoppen voor een bestaand boek
			$returnString .="
			<div class='row'>
				<div class='col-md-12 text-center'>
					<div class='btn-group' role='group'>
					  <button type='button' class='btn btn-success' onclick=\"this.form.actie.value='updateBook'; this.form.submit()\"><i class='fa fa-check'></i> Gegevens actualiseren</button>
					  <button type='button' class='btn btn-danger' onclick=\"this.form.actie.value=''; this.form.submit()\"><i class='fa fa-close'></i> Annuleren</button>
					</div>
				</div>
			</div>";
		}
		return $returnString;
	}

?> 