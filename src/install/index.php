<html>
<head></head>
<body>
<?
global $basedir, $webdir;

// remove /install
$dirname = dirname(__FILE__);
$basedir = substr($dirname, 0, strlen($dirname) - strlen("/install"));
// remove public_html and everything before it
$webdir = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen("/install/"));

echo("<font face=\"courier new\">\n");
echo("Base Directory: ".$basedir."<br />\n");
echo("&nbsp;Web Directory: ".$webdir."<br />\n");
echo("</font>\n");
echo("<hr />\n");

echo("<hr />\n");

createCONF();

chdir($basedir."/cgi-bin");
//echo(getcwd()."<hr>\n");

$install_script = $basedir."/cgi-bin/perl/bin/db-init-or-upgrade.pl";
$config_file = $basedir."/leaguerunner.conf";

echo("Installing League Runner...<br />\n");
// --

$command = "perl ".$install_script." --config=".$config_file." --action=install --clobber";
$output = array();
echo($command."<br>\n");
$result = exec($command, $output);
foreach($output as $line)
	echo($line."<br>\n");
echo("result: ".$result."<br>\n");

// --

echo("finished!<br />");
?>
</body>
</html>
<?
function createCONF()
{
  echo("Creating default CONF file...");
  global $basedir, $webdir;

  $addon_domaindir = "activesocialsports";
  $smartydir = "/home/lostm3/share/php/smarty3/libs";

  $db_host = "localhost";
  $db_name = "lostm3_ass_league";
  $db_user = "lostm3_assuser";
  $db_pass = "a55u53r";

  $timezone = date_default_timezone_get();

  $text = ("
; vim: set filetype=dosini :
;
; Leaguerunner configuration file.
;
; This is an example configuration file.  It should be copied to
; leaguerunner.conf in the src/ directory and modified for your database.
;
; Parsed by both parse_ini_file() in PHP, and Config::Tiny in Perl.

; Database configuration.
[database]
; dsn should be a PDO-compatible DSN
dsn = \"mysql:host=".$db_host.";dbname=".$db_name."\"
; username and password of mysql user
username = ".$db_user."
password = ".$db_pass."

; Various paths (system and URL) used by Leaguerunner
[paths]
; This should be the directory containing Leaguerunner.  Don't add a trailing slash.
; It's used to construct URLs, but please do not prefix it with http://hostname/
base_url = \"".$webdir."\"

; How to find files on the local system.  This should be the path to the
; directory containing Leaguerunner, so that files in data/ can be loaded, for
; example.
file_path = \"./\"

[smarty]
; Path to Smarty templating engine directory
smarty_path = \"".$smartydir."\"

; template_dir is where your templates live.  Should not be web-accessible
template_dir = \"".$basedir."/themes/templates\"
; compile_dir is where your compiled templates live.  Should not be web
; accessible, and must be writable by the webserver user.
compile_dir = \"".$basedir."/themes/templates_c\"
; cache_dir is where cached output will live.  Should not be web
; accessible, and must be writable by the webserver user.
cache_dir = \"".$basedir."/cache\"
; config_dir is where Smarty configuration files would live.  We do not use
; this feature, but a directory is required.
config_dir = \"".$basedir."/config\"

; Session defaults
[session]
; Session name. Set if necessary.
session_name = \"".$_SERVER['HTTP_HOST'].$webdir."\"

[localization]
; Set up time zone information.
; See http://ca3.php.net/timezones for a list of valid timezone names
local_tz = \"".$timezone."\"

; Difference, in minutes, from the local timezone to the server timezone.
; Value is negative if server is to your west, positive if it's east.
tz_adjust = 0
  ");

  $conf_file = $basedir."/leaguerunner.conf";

  $filename = fopen($conf_file, 'w');
  fwrite($filename, $text);
  fclose($filename);
  echo("finished!<br />");
}
?>