<?php

$env = "dev";

$log = shell_exec("git fetch && git reset --hard origin/{$env}");

$shell = shell_exec('cd ../ && pwd && composer update');

$shell .= 'Cache chmod: '.shell_exec("chmod -R 777 /var/www/planetsidebattles.org/{$env}/app/cache");
$shell .= 'Logs chmod: '.shell_exec("chmod -R 777 /var/www/planetsidebattles.org/{$env}/app/logs");

echo "Env: {$env} <br>";

$to      = 'maelstrome26@gmail.com';
$subject = "Deployment Status: {$env}";
$message = "Code was deployed to {$env} on: ".date("d/m/Y H:i:s")."<br>
<br>
Git Log:
<br>
<pre>".$log."</pre>
<br>
Composer log:
<br>
<pre>".$shell."</pre>";
$headers = 'From: deployment@planetsidebattles.org' . "\r\n" .
    'Reply-To: deployment@planetsidebattles.org' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

echo $message;

mail($to, $subject, $message, $headers);