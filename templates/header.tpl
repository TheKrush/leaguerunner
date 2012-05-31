{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{$app_name} : {$title}</title>
	{include file="components/css.tpl"}
	{include file="components/javascript.tpl"}
	<link rel="shortcut icon" href="{$base_url}/themes/{$site_theme}/logo.ico" />
</head>
<body class="one-sidebar">
	<div id="page-wrapper">
		<div id="header-wrapper">
			<div id="header">
				<div id="branding-wrapper">
					<div class="branding">
						<div class="logo">
							<a href="/" title="{$site_name}"><img src="{$base_url}/themes/{$site_theme}/logo.png" alt="{$site_name}" /></a>
						</div> <!-- end logo -->
						<div class="name-slogan-wrapper">
							<h1 class="site-name"><a href="/" title="{$site_name}">{$site_name}</a></h1>
							<span class="site-slogan">{$site_slogan}</span>
						</div> <!-- end site-name + site-slogan wrapper -->
					</div>
				</div> <!-- end branding wrapper -->
				<div id="authorize">
					<ul>
					{if $session_valid}
						<li class="first">Logged in as <a href="{lr_url path='person/view/`$session_userid`'}">{$session_fullname}</a></li>
						<li class="last"><a href="{lr_url path=logout}">Logout</a></li>
					{else}
						<li class="first"><a href="{lr_url path=login}">Login</a></li>
						<li class="last"><a href="{lr_url path='person/create'}">Register</a></li>
					{/if}
					</ul>
				</div> <!-- end authorize -->
			</div> <!-- end header -->
		</div> <!-- end header wrapper -->

		<div id="container-wrapper">
		<div id="container-outer">
			<div class="menu-wrapper">
			<div class="menu-outer">
				<div class="menu-inner">
				<div class="menu-left"></div>
				<div id="superfish">
					<div class="region region-superfish-menu">
						<div id="block-menu-menu-superfish" class="block block-menu">
							<div class="content">
							<ul class="menu">
								<li class="first leaf"><a href="/" title="{$site_name}">{$site_name}</a></li>
								<li class="last leaf"><a href="{$base_url}" title="Leaguerunner" class="active">Leaguerunner</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div> <!-- end menu / superfish -->
			<div class="menu-right"></div>
		</div>
	</div>
</div>
				<div id="container-inner">
					<div id="content-wrapper" class="clearfix">
						<div id="main-content">