<?php
	define('BRANCH', 'master');
	function isEnabled($func) {
	    return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
	}
	if (!isEnabled('shell_exec')) {
	    echo "shell_exec is disabled";
		exit();
	}
	// The commands
	$commands = array(
		'echo $PWD',
		'whoami',
		'git remote show origin',
		'git checkout '.BRANCH,
		'git reset --hard HEAD',
		'git reset --hard origin/'.BRANCH,
		'git pull --rebase',
		'git log -1 --format="%at" | xargs -I{} date -d @{}',
		'git status',
		'git show --summary',	
		//'git submodule sync',
		//'git submodule update',
		//'git submodule status',
	);
?>
<?php
	// Run the commands for output
	$i = 1;
	if((php_sapi_name() == "cli") || defined('STDIN')){
		$mask = "|%-3s|%-35s|%-40.s|\n";
		printf($mask, 'NO', 'GIT COMMANDS','COMMAND RESULT');
		printf($mask, '--', '===================================','========================================');
		foreach($commands AS $command){
			// Run it
			$tmp = shell_exec($command);
			printf($mask, " ".$i, $command, $tmp);
			printf($mask, '---', '-----------------------------------','---------------------------------------');
			$i++;
		}
	}
	else{
		$table ="<table border='1' cellspacing='15' cellpadding='08'>
			<thead>
			  <tr>
			    <td>SR. NO</td>
			    <td>GIT COMMANDS</td>
			    <td>COMMAND RESULT</td>
			  </tr>
			</thead>
			<tbody>";
			foreach($commands AS $command){
				// Run it
				$tmp = shell_exec($command);
				$table .= "<tr>
						    <td align='center'>".$i."</td>
						    <td style='width:20%' class='commands'>".$command."</td>
						    <td style='width:60%'>".htmlentities(trim($tmp))."</td>
					  	</tr>";
					  	$i++;
			}
			$table .= "</tbody>";
	?>
	<!DOCTYPE HTML>
	<html lang="en-US">
		<head>
			<meta charset="UTF-8">
			<title>GIT DEPLOYMENT SCRIPT</title>
			<style type="text/css">
				.commands{ color: #6BE234 }
				table{ border: dashed 1px #545350;border-collapse:collapse;border-spacing: 1px;display: table;}
				tr{ border: dashed 1px #545350}
				pre{font-weight: 500}
			</style>
		</head>
		<body style="background-color: #232b2d; color: #d2d9d9;font-size:17px;word-wrap:break-word;">
<pre>
	<center>            
	 Git Deployment Script v0.3
	 |t|r|u|e|l|i|n|e| |l|a|b|s|
	<?php echo $table; ?>
	</center>
</pre>
		</body>
	</html>
	<?php
	}
?>
