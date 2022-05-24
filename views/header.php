<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Youtube-dl WebUI</title>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css" media="screen">
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">
		<link rel="stylesheet" href="./css/custom.css">
		<link rel="Shortcut Icon" href="./favicon_144.png" type="image/x-icon">
	</head>
	<body>
		<div class="navbar navbar-default">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="navbar-brand">Youtube-dl WebUI</div>
			</div>
			<div class="navbar-collapse collapse navbar-responsive-collapse">
				<ul class="nav navbar-nav">
					<?php
						if($session->is_logged_in() && isset($file))
					{
						echo '					<li><a href="./"><span class="glyphicon glyphicon-cloud-download"></span> Download</a></li>';
						echo '					<li><a href="./info.php"><span class="glyphicon glyphicon-info-sign"></span> JSON Info</a></li>';
						// List of files
						$filesCount = count($file->listFiles());
						if ($filesCount < 1) {
							echo '					<li><a href="./list.php"><span class="glyphicon glyphicon-list"></span> List of files</a></li>';
						} else {
							echo '					<li><a href="./list.php"><span class="glyphicon glyphicon-list"></span> <b>List of files</b> ('.($filesCount).')</a></li>';
						}
						unset($filesCount);
						
						// Logs
						if ($file->is_log_enabled()) {
							$filesCount = $file->countLogs();
							if ($filesCount < 1) {
								echo '					<li><a href="./logs.php"><span class="glyphicon glyphicon-book"></span> Logs</a></li>';
							} else {
								echo '					<li><a href="./logs.php"><span class="glyphicon glyphicon-book"></span> <b>Logs</b> ('.($filesCount).')</a></li>';
							}
							unset($filesCount);
						}
					?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<?php if(Downloader::background_jobs() > 0) echo "<b>"; ?>Background downloads : <?php echo Downloader::background_jobs()." / ".Downloader::max_background_jobs(); if(Downloader::background_jobs() > 0) echo "</b>"; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<?php
								if(Downloader::get_current_background_jobs() != null)
								{
									foreach(Downloader::get_current_background_jobs() as $key)
									{
										echo "<li><a  href=\"#\" title=\"".htmlspecialchars($key['cmd'])."\">Elapsed time : ".$key['time']."</a></li>";
									}

									echo "<li class=\"divider\"></li>";
									echo "<li><a href=\"./index.php?kill=all\">Kill all downloads</a></li>";
								}
								else
								{
									echo "<li><a>No jobs !</a></li>";
								}

							?>
						</ul>
					</li>
					<?php
						}
					?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php
						if($session->is_logged_in())
						{
							echo '<li><a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
						}
					?>
				</ul>
			</div>
		</div>
