<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération et nettoyage
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);
    $date = date("d/m/Y H:i");

    // Validation simple
    if (empty($nom) || empty($email) || empty($message)) {
        die("Tous les champs sont obligatoires.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email invalide.");
    }

    // =========================
    // 1. ENREGISTREMENT CSV
    // =========================

    $file = "messages.csv";

    // Création du fichier avec entête si nécessaire
    if (!file_exists($file)) {
        $header = ["Date", "Nom", "Email", "Message"];
        $fp = fopen($file, "w");
        fputcsv($fp, $header, ";");
        fclose($fp);
    }

    // Ajout de la ligne
    $data = [$date, $nom, $email, $message];
    $fp = fopen($file, "a");
    fputcsv($fp, $data, ";");
    fclose($fp);


    // =========================
    // 2. ENVOI EMAIL
    // =========================

    $to = "lalie-rose.secherre@vinci-facilities.com"; // destinataire
    $subject = "Nouveau message du site";

    $headers = "From: lalie-rose.secherre@vinci-facilities.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Nouveau message reçu :

Nom : $nom
Email : $email

Message :
$message";

    mail($to, $subject, $body, $headers);


    // =========================
    // 3. REDIRECTION
    // =========================

    header("Location: messageok.html");
    exit();
}
?>