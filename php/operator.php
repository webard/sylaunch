<?php
function is_fqdn($fqdn)
{
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $fqdn) //valid chars check
        && preg_match("/^.{1,253}$/", $fqdn) //overall length check
        && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $fqdn)); //length of each label
}

$avaialblePhpVersions = [
    '7.2'
];

$actions = [
    'create' => function () use ($avaialblePhpVersions) {
        if (empty($_GET['domain']) || !is_fqdn($_GET['domain'])) {
            die('No or invalid domain');
        }
        $domain = $_GET['domain'];

        if (empty($_GET['phpver']) || !in_array($_GET['phpver'], $avaialblePhpVersions)) {
            die('No or invalid PHP version');
        }
        $phpVersion = $_GET['phpver'];

        $user = preg_replace('/^[a-zA-Z0-9\-]*/', '', str_replace('.', '-', $domain));

        exec('bash /root/sylaunch/action.sh create "' . $domain . '" "' . $user . '" "' . $phpVersion . '" "/var/www/' . $domain . '"', $output);

        return $output;
    }

];

if (empty($_GET['action']) || !isset($actions[$_GET['action']]) || !is_callable($actions[$_GET['action']])) {
    die('No or invalid action');
}

echo $actions[$_GET['action']]();
