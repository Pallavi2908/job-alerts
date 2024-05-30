<?php
    use PHPMailer\PHPMailer\PHPMailer;
    include'web_scraping.php';
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    // this is the main task now: to send email
    function jobalert($jobs){
        $mail = new PHPMailer(true);

        try{
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth=true;
            $mail->Username = getenv('SMTP_USERNAME'); //sender email/username
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom('rero55644@gmail.com', 'Mercari Engineering Job Alert!!!');
            $mail->addAddress('pallavii.sinha029@gmail.com'); //insert your email ID!
            $mail->isHTML(true);
            $mail->Subject = 'New hiring';
            $mail_content = '<h1>Recent hiring</h1>';
            foreach($jobs as $job){
                $mail_content .= '<p><strong>' . $job['title'] . '</strong><br>';
                $mail_content .= 'Link: <a href="' . $job['link'] . '">' . $job['link'] . '</a><br>';
                $mail_content .= 'Location: ' . $job['location'] . '</p>';
            }
            $mail->Body = $mail_content;
            $mail->send();
            //Mail sent!
            echo 'check inbox';

        }catch(Exception $e){
            echo 'SORRY! THERE HAS BEEN AN ERROR:', $mail->ErrorInfo;
        }
    }

    //extracting job detail from jobs.json
    // Read jobs from JSON file and send email
if (file_exists('jobs.json')) {
    $jobs = json_decode(file_get_contents('jobs.json'), true);
    if (!empty($jobs)) {
        jobalert($jobs);
    } else {
        echo 'No jobs found to send.';
    }
} else {
    echo 'Jobs file not found.';
}


?>