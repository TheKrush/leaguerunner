{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{$app_name} : {$title}</title>
	{include file="components/css.tpl"}
	{include file="components/javascript.tpl"}
	<link rel="shortcut icon" href="{$base_url}/themes/{$site_theme}/logo.ico" />
</head>
<body class="html front not-logged-in one-sidebar sidebar-first page-node" >
	<div id="skip-link">
		<a href="#main-content" class="element-invisible element-focusable">Skip to main content</a>
	</div>
	<!-- page -->
	<div id="page">
		<!-- header -->
		<div id="header" class="clearfix">
			<div id="logo-title">
				<!-- logo -->
				<a href="/" title="Home"> <img src="http://activesocialsports.com/sites/default/files/logo.png" alt="Home" id="logo" /> </a>
				<!-- /logo -->
			</div>
			<div id="name-and-slogan">
				<!-- name and sloagan -->
				<!-- site-name -->
				<h1 class='site-name'> <a href="/" title="Home"> Active Social Sports </a> </h1>
				<!-- /site-name -->
				<!-- /name and sloagan -->
			</div>
			<!-- navigation main/secondary menu -->
			<div id="primarymenu">
				<!-- main menu -->
				<ul class="links primary-links">
				<li class="menu-218 first"><a href="/" class="active">Drupal</a></li>
				<li class="menu-310 last active"><a href="/leaguerunner" title="The league management site.">Leaguerunner</a></li>
				</ul>
				<!-- /main menu -->
				<!-- secondary menu -->
				<ul class="links secondary-links inline">
					{if $session_valid}
						<li class="menu-2 first"><a href="{lr_url path='person/view/'}{$session_userid}">My account</a></li>
						<li class="menu-15 last">><a href="{lr_url path=logout}">Logout</a></li>
					{else}
						<li class="menu-2 first"><a href="{lr_url path=login}">Login</a></li>
						<li class="menu-15 last"><a href="{lr_url path='person/create'}">Register</a></li>
					{/if}
				</ul>
				<!-- secondary menu -->
			</div>
			<!-- /navigation main/secondary menu -->
		</div>
		<!-- /header -->
		<!-- middle-container -->
		<div id="middlecontainer">
			<!-- sidebar-left -->
			<div id="sidebar-left">
				<div class="region region-sidebar-first">
					<div id="block-system-navigation" class="block block-system contextual-links-region block-menu">
						<h2 class="title">Navigation</h2>
						<div class="content">
							{$menu}
							<ul class="menu">
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- /sidebar-left -->
			<!-- main -->
			<div id="main">
				<!-- sequeeze -->
				<div id="squeeze">
					<!-- sequeeze-content -->
					<div id="squeeze-content">
						<!-- inner-content -->
						<div id="inner-content">
							<!-- content -->
							<div class="region region-content">
