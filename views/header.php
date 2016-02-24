<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Youtube-dl WebUI</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" media="screen">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="./css/custom.css">
	</head>
	<body>
		<div class="navbar navbar-default">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="">Youtube-dl WebUI</a>
			</div>
			<div class="navbar-collapse collapse navbar-responsive-collapse">
				<ul class="nav navbar-nav">
					<?php
						if($session->is_logged_in() && isset($file))
					{
						echo '					<li><a href="./">Download</a></li>';
						// List of files
						$filesCount = count($file->listFiles());
						if ($filesCount < 1) {
							echo '					<li><a href="./list.php">List of files</a></li>';
						} else {
							echo '					<li><a href="./list.php"><b>List of files</b> ('.($filesCount).')</a></li>';
						}
						unset($filesCount);
						
						// Logs
						if ($file->is_log_enabled()) {
							$filesCount = count($file->listLogs());
							if ($filesCount < 1) {
								echo '					<li><a href="./logs.php">Logs</a></li>';
							} else {
								echo '					<li><a href="./logs.php"><b>Logs</b> ('.($filesCount).')</a></li>';
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
							echo "<li><a href=\"./logout.php\">Logout</a></li>";
						}
					?>
				</ul>
			</div>
		</div>
