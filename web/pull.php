<?php

$env = "prod";

/*$json = file_get_contents('php://input');

if(empty($json))
{
	$data = "NO POST DATA RECIEVED";
}
else
{
	$data = json_decode($json);
}*/

$data = "";

$log = shell_exec("git fetch && git reset --hard origin/master");

$shell = shell_exec('cd ../ && composer install && php app/console cache:clear --env=prod && php app/console doctrine:cache:clear-metadata && php app/console doctrine:cache:clear-result && php app/console doctrine:cache:clear-metadata && php app/console doctrine:generate:entities PSBAdminBundle');

$shell .= 'Logs chmod: '.shell_exec("chown -R www-data /var/www/planetsidebattles.org/{$prod}"); // Ensure theres no issues with cache etc
$shell .= 'Cache chmod: '.shell_exec("chmod -R 777 /var/www/planetsidebattles.org/html/app/cache");
$shell .= 'Logs chmod: '.shell_exec("chmod -R 777 /var/www/planetsidebattles.org/html/app/logs");

$to      = 'maelstrome26@gmail.com';
$subject = "Deployment Status: {$env}";
$message = "Code was deployed to {$env} on: ".date("d/m/Y H:i:s")."<br>
<br>
Git Log:
<br>
<pre>LOG: ".$log."</pre>
<br>
Composer log:
<br>
<pre>SHELL: ".$shell."</pre>
<pre>POST: ".$data."</pre>";
$headers = 'From: deployment@planetsidebattles.org' . "\r\n" .
    'Reply-To: deployment@planetsidebattles.org' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

echo $message;

mail($to, $subject, $message, $headers);