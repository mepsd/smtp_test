<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMTP Email Tester</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        div {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            /* Added this line to include padding in the input width */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h2>SMTP Email Test Form</h2>
        <div>
            <label for="smtp_host">SMTP Host:</label>
            <input type="text" id="smtp_host" name="smtp_host" required value="<?php echo isset($_POST['smtp_host']) ? $_POST['smtp_host'] : ''; ?>">
        </div>
        <div>
            <label for="smtp_username">SMTP Username:</label>
            <input type="text" id="smtp_username" name="smtp_username" required value="<?php echo isset($_POST['smtp_username']) ? $_POST['smtp_username'] : ''; ?>">
        </div>
        <div>
            <label for="smtp_password">SMTP Password:</label>
            <input type="password" id="smtp_password" name="smtp_password" required>
        </div>
        <div>
            <label for="smtp_port">SMTP Port:</label>
            <input type="text" id="smtp_port" name="smtp_port" required value="<?php echo isset($_POST['smtp_port']) ? $_POST['smtp_port'] : ''; ?>">
        </div>
        <div>
            <label for="from_email">From Email:</label>
            <input type="email" id="from_email" name="from_email" required value="<?php echo isset($_POST['from_email']) ? $_POST['from_email'] : ''; ?>">
        </div>
        <div>
            <label for="to_email">To Email:</label>
            <input type="email" id="to_email" name="to_email" required value="<?php echo isset($_POST['to_email']) ? $_POST['to_email'] : ''; ?>">
        </div>
        <div>
            <label for="email_subject">Email Subject:</label>
            <input type="text" id="email_subject" name="email_subject" required value="<?php echo isset($_POST['email_subject']) ? $_POST['email_subject'] : ''; ?>">
        </div>
        <div>
            <label for="email_body">Email Body:</label>
            <textarea id="email_body" name="email_body" required>
            <?php echo isset($_POST['email_body']) ? $_POST['email_body'] : ''; ?>

            </textarea>
        </div>
        <div>
            <label for="encryption">Encryption:</label>
            <select id="encryption" name="encryption">
                <option value="tls" <?php echo isset($_POST['encryption']) && $_POST['encryption'] === 'tls' ? 'selected' : ''; ?>>TLS</option>
                <option value="ssl" <?php echo isset($_POST['encryption']) && $_POST['encryption'] === 'ssl' ? 'selected' : ''; ?>>SSL</option>
            </select>
        </div>
        <button type="submit">Send Email</button>

        <?php

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        require 'vendor/autoload.php'; // Adjust the path as needed if PHPMailer is installed manually or with Composer

        // check if this is a submit
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        // Create instance of PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Change to DEBUG_SERVER for detailed debug output
            $mail->isSMTP();
            $mail->Host       = $_POST['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_POST['smtp_username'];
            $mail->Password   = $_POST['smtp_password'];
            $mail->SMTPSecure = $_POST['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_POST['smtp_port'];
            // mail from name

            // Recipients
            $mail->setFrom($_POST['from_email'], $_POST['from_email']);
            $mail->addAddress($_POST['to_email'], 'Recipient Name'); // Recipient name is optional

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $_POST['email_subject'];
            $mail->Body    = $_POST['email_body'];
            $mail->AltBody = strip_tags($_POST['email_body']);

            $mail->send();
        ?>
            <p style="color: green; text-align: center;">Message has been sent</p>
        <?php
        } catch (Exception $e) {
        ?>
            <p style="color: red; text-align: center;"><?php echo $mail->ErrorInfo; ?></p>

        <?php
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


        ?>

    </form>

</body>

</html>