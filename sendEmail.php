<!--source: https://harryherskowitz.medium.com/html-form-submit-with-google-recaptcha-vanilla-js-and-php-a88bbea591fd-->
<?php
// Replace this with your own email address
$siteOwnersEmail = 'contact@galanlaura.com';

if ($_POST) {
    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    // Check Name
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
    }
    // Check Email
    if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
        $error['email'] = "Please enter a valid email address.";
    }
    // Check Message
    if (strlen($contact_message) < 15) {
        $error['message'] = "Please enter your message. It should have at least 15 characters.";
    }
    // Subject
    if ($subject == '') {
        $subject = "Contact Form Submission";
    }

    // Set Message
    $message = "Email from: " . $name . "<br />";
    $message .= "Email address: " . $email . "<br />";
    $message .= "Message: <br />";
    $message .= $contact_message;

    // Set From: header
    $from =  $name . " <" . $email . ">";

    // Email Headers
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // reCAPTCHA validation
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

        // Google secret API
        $secretAPIkey = '6Ld78eokAAAAAPYkQVrb52i4yhVV8Zn2ce7vDGqp';

        // reCAPTCHA response verification
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $_POST['g-recaptcha-response']);

        // Decode JSON data
        $response = json_decode($verifyResponse);

        if ($response->success) {

            if (!$error) {

                ini_set("sendmail_from", $siteOwnersEmail); // for windows server
                $mail = mail($siteOwnersEmail, $subject, $message, $headers);

                if ($mail) {
                    echo "OK";
                } else {
                    echo "Something went wrong. Please try again.";
                }
            } # end if - no validation error

            else {

                $response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
                $response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
                $response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;

                echo $response;
            } # end if - there was a validation error
        }
    }
}