<?php
$db = new mysqli("localhost", "root", "password", "icaldb");
$db->set_charset('utf8');
$events = array();
$sql = "SELECT id, startdate, finishdate, title, info,	startime, finitime,	place FROM events";
if ($result = $db->query($sql, MYSQLI_USE_RESULT)) {
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$events[] = $row;
	}
}

$lines = array();
$lines[] = 'BEGIN:VCALENDAR';
$lines[] = 'PRODID:-//My Events Calendar//';
$lines[] = 'VERSION:2.0';

foreach ($events as $e){
	$lines[] = 'BEGIN:VEVENT';
	$lines[] = "DTSTART:".date("Ymd\This\Z",strtotime($e['startdate'].' '.$e['startime']));
	$lines[] = "DTEND:".date("Ymd\This\Z",strtotime($e['finishdate'].' '.$e['finitime']));
	$lines[] = "UID:".$e['id']."@yoursite.com";
	$lines[] = 'URL:http://www.yoursite.com/event/'.$e['id'];
	$lines[] = 'DESCRIPTION:'.$e['info'];
	$lines[] = 'SUMMARY:'.$e['title'];
	$lines[] = 'LOCATION:'.$e['place'];
	$lines[] = 'END:VEVENT';
}
$lines[] = 'END:VCALENDAR';

header("Content-Type:text/calendar");
echo implode("\n",$lines);
