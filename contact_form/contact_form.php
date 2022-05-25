<?php
// PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// let's do the sending

if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
    //your site secret key
    $secret = '6LdAqO0fAAAAAA02UBCCCkCBVD1Utk7Svyvr3fE1';
    //get verify response data

    $c = curl_init('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    $verifyResponse = curl_exec($c);

    $responseData = json_decode($verifyResponse);
    if($responseData->success):

        try
        {
            require 'PHPMailer/Exception.php';
            require 'PHPMailer/PHPMailer.php';
            require 'PHPMailer/SMTP.php';
            
            $mail = new PHPMailer(true);
            
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                   // Enable verbose debug output
            $mail->isSMTP();                                           // Send using SMTP
            $mail->Host       = 'smtp.zoho.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
            $mail->Username   = 'mail@deninasrullah.my.id';          // SMTP username
            $mail->Password   = 'P45$w0R|)zm|<u';                       // SMTP password
            $mail->SMTPSecure = 'ssl';                                 // 'ssl' or 'tls' or Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 465;                                   // TCP port to connect to. For Example: 25 - Simple SMTP. 465 - SSL SMTP. 587 - TLS SMTP.

            //From
            $mail->setFrom('mail@deninasrullah.my.id', 'Mailer');    // Add your hosting account email or server admin email
            //Recipient
            $mail->addAddress('deninasrullah@gmail.com', 'Deni Nasrullah');     // Add a recipient (your email). Add your name
            //ReplyTo
            $mail->addReplyTo($_POST['email'], $_POST['name']);        // Do not change this line

            // Content
            $mail->isHTML(true);                                       // Set email format to HTML
            $mail->Subject = 'New message from contact form';          // Email subject. You can change this text
            
            $fields = array('name' => 'Name', 'email' => 'Email', 'message' => 'Message'); // array variable name => Text to appear in the email

            $emailText = nl2br("You have new message from Contact Form\n");

            foreach ($_POST as $key => $value) {
                if (isset($fields[$key])) {
                    $emailText .= nl2br("$fields[$key]: $value\n");
                }
            }
            
            $mail->Body    = $emailText;

            $mail->send();
            $okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!'; // You can change this text (message)
            $responseArray = array('type' => 'success', 'message' => $okMessage);
        } catch (Exception $e) {
            $errorMessage = "There was an error while submitting the form. Please try again later. Mailer Error: {$mail->ErrorInfo}"; // You can change this text (message)
            $responseArray = array('type' => 'danger', 'message' => $errorMessage);
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $encoded = json_encode($responseArray);

            header('Content-Type: application/json');

            echo $encoded;
        }
        else {
            echo $responseArray['message'];
        }

    else:
        $errorMessage = 'Robot verification failed, please try again.';
        $responseArray = array('type' => 'danger', 'message' => $errorMessage);
        $encoded = json_encode($responseArray);

            header('Content-Type: application/json');

            echo $encoded;
    endif;
else:
    $errorMessage = 'Please click on the reCAPTCHA box.';
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
    $encoded = json_encode($responseArray);

            header('Content-Type: application/json');

            echo $encoded;
endif;

