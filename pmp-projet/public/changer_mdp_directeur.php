<?php
// filepath: c:\xampp\htdocs\pmp-projet\public\changer_mdp_directeur.php
session_start();
require_once '../backend/db.php';
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$info = '';
$error = '';
$show_code_form = false;
$email = $_POST['email'] ?? '';

// Étape 1 : Envoi du code
if (isset($_POST['send_code'])) {
    // Vérifier que l'email existe dans la table directeurs
    $stmt = $conn->prepare("SELECT * FROM directeurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $code = rand(100000,999999);
        $_SESSION['code_mdp'] = $code;
        $_SESSION['email_mdp'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'elmoubarikhafsa@gmail.com';
            $mail->Password = 'rlws febx ltaa xuzq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('elmoubarikhafsa@gmail.com', 'PMP Projet');
            $mail->addAddress($email);

            $mail->Subject = 'Code de changement de mot de passe';
            $mail->Body    = "Bonjour,\n\nVotre code confidentiel pour changer le mot de passe est : $code\n\nMerci.";

            $mail->send();
            $info = "Un code confidentiel a été envoyé à votre adresse email.";
            $show_code_form = true;
        } catch (Exception $e) {
            $error = "Erreur lors de l'envoi du code : " . $mail->ErrorInfo;
        }
    } else {
        $error = "Cet email n'est pas reconnu comme directeur.";
    }
}

// Étape 2 : Vérification du code
if (isset($_POST['verify_code'])) {
    $input_code = $_POST['input_code'] ?? '';
    if ($input_code == ($_SESSION['code_mdp'] ?? '')) {
        header("Location: changer_mdp_directeur_code.php");
        exit;
    } else {
        $error = "Code incorrect. Veuillez réessayer.";
        $show_code_form = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Changer le mot de passe directeur</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Recevoir le code de changement</h2>
  <?php if ($info): ?>
    <div class="msg" style="color:var(--main-green);background:#eafaf1;"><?php echo htmlspecialchars($info); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="msg" style="color:#e74c3c;background:#fbeaea;"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if ($show_code_form): ?>
    <form method="post">
      <input type="text" name="input_code" placeholder="Entrez le code reçu par email" required>
      <button type="submit" name="verify_code" class="btn-panel">Valider le code</button>
    </form>
  <?php else: ?>
    <form method="post">
      <div style="margin-bottom:1em;">
        <label>Email du directeur :</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
      </div>
      <button type="submit" name="send_code" class="btn-panel">Recevoir le code</button>
    </form>
  <?php endif; ?>

  <a href="directeur_login.php" class="btn-panel">Retour</a>
</div>
<?php include 'footer.php'; ?>
</body>
</html>