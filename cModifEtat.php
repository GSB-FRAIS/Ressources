<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gsb_frais";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$id = $_POST['id'];
$lgMois = $_POST['mois']; 

$sql = "update fichefrais set idEtat = 'VA' where idVisiteur = '" .$id. "' and mois = '".$lgMois."'";
if ($conn->query($sql) === TRUE) {
    echo "Note de frais Validée";
} else {
    echo "Erreur lors de la validation de la note de frais " . $conn->error;
}

$conn->close();
?>