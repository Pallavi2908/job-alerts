<?php
//require 'vendor/sunra/php-simple-html-dom-parser/Src/Sunra/PhpSimple/simplehtmldom_1_5/simple_html_dom.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use function simplehtmldom_1_5\file_get_html;

echo "hello";
//writing function to find job
function findJob()
{
    $url = 'https://careers.mercari.com/search-jobs/'; //mercari tokyo jobs
    $html = file_get_html($url);
    $jobs = [];
    //searching for tech roles only
    $keywords = ['Software Engineer','Engineering Manager','Data Analyst','Site Reliability'];

    foreach($html->find('li.job-list_item') as $job){
        $maintitle= $job -> find('h4.job-list_title-text',0);
        $job_title= $maintitle ? $maintitle->plaintext: '';

        //flag to check if job title has keywrod
        $has_keywords = false;
        foreach($keywords as $keyword){
            if(stripos($job_title, $keyword) !== false){
                $has_keywords = true;
                break;
            }
        }
        if($has_keywords){
            $job_link = $job->find('a.job-list_title',0);
            $link= $job_link ? $job_link -> href: '';  //getting job link

            //getting locatoin
            $job_location = $job-> find('.job-list_location-name',0);
            $location = $job_location ? $job_location->plaintext : '';

            $jobs[] = [
                'title' => $job_title,
                'link' => $link,
                'location' => $location];
                
        }
    }
    return $jobs;
}

//Jobs will be saved in a json file called jobs.json
$jobs = findJob();
if (!empty($jobs)) {
    file_put_contents('jobs.json', json_encode($jobs));
}

?>
