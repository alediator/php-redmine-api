<?php

/**
 * @file
 * This file loads your time spent on Redmine for one day using the PHP api
 *
 * Usage: php auto_redmine.php -u=https://host -a=API_KEY -t=issue_id -h=hours -d=YYYY-MM-DD -p=project_id
 */

require_once 'lib/autoload.php';

// basic configuration
$issue_id = -1;
$host = 'https://';
$api_key = '';
$checkSSL = FALSE;
$activity = 9;
$hours = 9;
$date = '';
$project_id = '';


print("\nGetting options...");

// Read options
$short_opts = "u:a:t:c:p:s::e::h::d::";
$options = getopt($short_opts);

// Read options if exists: -u url
if (isset($options['u']) && $options['u'] != '') {
  $host = $options['u'];
  print("\nHost url is " . $host);
}

// Read options if exists: -a api_key
if (isset($options['a']) && $options['a'] != '') {
  $api_key = $options['a'];
  print("\nAPI key is " . $api_key);
}

// Read options if exists: -t task_id
if (isset($options['t']) && $options['t'] != '') {
  $issue_id = $options['t'];
  print("\nTask is " . $issue_id);
}

// Read options if exists: -c activity
if (isset($options['c']) && $options['c'] != '') {
  $activity = $options['c'];
}

// Read options if exists: -p activity
if (isset($options['p']) && $options['p'] != '') {
  $project_id = $options['p'];
}

// Read options if exists: -s start
if (isset($options['s']) && $options['s'] != '') {
  $start = $options['s'];
}

// Read options if exists: -e end
if (isset($options['e']) && $options['e'] != '') {
  $end = $options['e'];
}

// Read options if exists: -h hours
if (isset($options['h']) && $options['h'] != '') {
  $hours = $options['h'];
  print("\nHours are " . $hours);
}

// Read options if exists: -d date
if (isset($options['d']) && $options['d'] != '') {
  $date = $options['d'];
  print("\nDate is " . $date);
}

// check if host and API key are available
if(!isset($host) || !isset($api_key) || $issue_id == -1){
  print("\nCannot create Redmine client, please give me host, api key and task by '-h=host -a=API_KEY -t=task_id");
  return;
}

// ----------------------------
// Instanciate a redmine client
// --> with ApiKey
$client = new Redmine\Client($host, $api_key);

// ----------------------------
// [OPTIONAL] if you want to check
// the servers' SSL certificate on Curl call
$client->setCheckSslCertificate($checkSSL);

print("\nWorking...");

print("\nWill save " . $hours . " spent on " .  $date . " for #" . $issue_id);

// create the time entry
$message = $client->time_entry->create([
  'issue_id' => $issue_id,
  'project_id' => $project_id,
    'spent_on' => $date,
    'hours' => $hours,
    'activity_id' => $activity,
    //'comments' => NULL,
]);

print("\n" . $message);

print("\nDone!\n");