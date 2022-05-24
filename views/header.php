<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Youtube-dl WebUI</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" media="screen">
		<link rel="stylesheet" href="./css/custom.css">
		<link rel="Shortcut Icon" href="./favicon_144.png" type="image/x-icon">
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light px-2">
			<div class="container-fluid">
				<a class="navbar-brand" href="./">Youtube-dl WebUI</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
					<?php
						if($session->is_logged_in() && isset($file))
					{
						echo '						<li class="nav-item"><a class="nav-link" href="./"><span class="glyphicon glyphicon-cloud-download"></span> Download</a></li>';
						echo '						<li class="nav-item"><a class="nav-link" href="./info.php"><span class="glyphicon glyphicon-info-sign"></span> JSON Info</a></li>';
						// List of files
						$filesCount = count($file->listFiles());
						if ($filesCount < 1) {
							echo '					<li class="nav-item"><a class="nav-link" href="./list.php"><span class="glyphicon glyphicon-list"></span> List of files</a></li>';
						} else {
							echo '					<li class="nav-item"><a class="nav-link" href="./list.php"><span class="glyphicon glyphicon-list"></span> <b>List of files</b> ('.($filesCount).')</a></li>';
						}
						unset($filesCount);
						
						// Logs
						if ($file->is_log_enabled()) {
							$filesCount = $file->countLogs();
							if ($filesCount < 1) {
								echo '						<li class="nav-item"><a class="nav-link" href="./logs.php"><span class="glyphicon glyphicon-book"></span> Logs</a></li>';
							} else {
								echo '						<li class="nav-item"><a class="nav-link" href="./logs.php"><span class="glyphicon glyphicon-book"></span> <b>Logs</b> ('.($filesCount).')</a></li>';
							}
							unset($filesCount);
						}
					?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
							<?php if(Downloader::background_jobs() > 0) echo "<b>"; ?>Background downloads : <?php echo Downloader::background_jobs()." / ".Downloader::max_background_jobs(); if(Downloader::background_jobs() > 0) echo "</b>"; ?> <span class="caret"></span></a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<?php
								if(Downloader::get_current_background_jobs() != null)
								{
									foreach(Downloader::get_current_background_jobs() as $key)
									{
										echo "								<a class=\"dropdown-item\" href=\"#\" title=\"".htmlspecialchars($key['cmd'])."\">Elapsed time : ".$key['time']."</a>";
									}

									echo "<hr class=\"dropdown-divider\">";
									echo "<a class=\"dropdown-item\" href=\"./index.php?kill=all\">Kill all downloads</a>";
								}
								else
								{
									echo "a class=\"dropdown-item\">No jobs !</a>";
								}

							?>
						</li>
					<?php
						}
					?>
				</div>
				<ul class="navbar-nav mr-auto justify-content-end">
					<?php
						if($session->is_logged_in())
						{
							echo '					<li class="nav-item"><a class="nav-link" href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
						}
					?>
				</ul>
			</div>
		</div>
