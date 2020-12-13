<?php

include 'functies.php';

$conn = maakConnectie();

$arrBoek = maakArray($conn);

$idCurrentBook = NULL;
if(isset($_GET['idCurrentBook'])){
	$idCurrentBook = $_GET['idCurrentBook'];
}

$actie = NULL;

if(isset($_GET['actie'])){
	$actie = $_GET['actie'];
	$_GET['actie'] = NULL;
}

if($idCurrentBook != NULL && $actie=="updateBook"){
	$sql = "UPDATE tblboek SET 
	titel = '{$_GET['titel']}', 
	ISBN = '{$_GET['ISBN']}', 
	paginas = '{$_GET['paginas']}', 
	druk = '{$_GET['druk']}',
	uitgegeven =' {$_GET['uitgegeven']}'	
	WHERE id = $idCurrentBook";
	if ($conn->query($sql) === TRUE) {
	  $arrBoek = maakArray($conn);
	} else {
	  echo "Error updating record: " . $conn->error;
	}
}elseif(isset($_GET['titel']) && $actie=="newBook"){
	$sql = "INSERT INTO tblboek (titel, ISBN, paginas, druk, uitgegeven)
VALUES ('{$_GET['titel']}', '{$_GET['ISBN']}', '{$_GET['paginas']}', '{$_GET['druk']}', '{$_GET['uitgegeven']}')";

	if ($conn->query($sql) === TRUE) {
	  $idCurrentBook = $conn->insert_id;
	  $arrBoek = maakArray($conn);
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

    <title>Boek</title>
  </head>
  <body>
	<form method="GET">
		<input type="hidden" name="actie" value="">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>BOEK</h1>
				</div>
			</div>
			<hr>
			<?php print kiesBook($arrBoek,$idCurrentBook); ?>
			<?php print formBoek($arrBoek,$idCurrentBook); ?>
			<?php print buttonBar($idCurrentBook) ?>

		</div>
	
	</form>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>