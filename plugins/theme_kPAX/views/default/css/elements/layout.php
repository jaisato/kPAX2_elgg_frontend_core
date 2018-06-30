/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 */

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
/* the width is on the page rather than topbar to handle small viewports */
.elgg-page-default {
	min-width: 800px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	//min-height: 65px;
  height: 150px;
}
.elgg-page-default .elgg-page-navbar > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	height: auto;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	max-width: 990px;
	margin: 0 auto;
	//padding: 5px 0;
  padding: 10px 15px;
	//border-top: 1px solid #DEDEDE;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #444 url(<?php echo elgg_get_site_url(); ?>mod/theme_kPAX/graphics/topbar.png) repeat-x bottom left;
  border-bottom: 1px solid #000;
	position: relative;
	z-index: 9000;
}
.elgg-page-topbar > .elgg-inner {
  padding: 8px 0px 6px 0px;
  width: 990px;
  margin: auto;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	//top: 32px;
  top: 40px;
	right: 20px;
	max-width: 500px;
	z-index: 2000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
  position: relative;
	background: #000 url(<?php echo elgg_get_site_url(); ?>mod/theme_kPAX/graphics/header.png) repeat-x top left;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}

/***** PAGE NAVBAR ******/
/*
.elgg-page-navbar {
	padding: 0 20px;
	position: relative;
	background: #4787B8;
}
.elgg-page-navbar > .elgg-inner {
	position: relative;
}
.elg-page-navbar {
  padding: 0px 20px;
  position: relative;
  background: #4787B8;
}
*/
/***** PAGE BODY LAYOUT ******/
.elgg-page-body {
	padding: 0 20px;
}

.elgg-layout {
	min-height: 360px;
}
.elgg-layout-widgets > .elgg-widgets {
	float: right;
}
.elgg-layout-error {
  margin-top: 20px;
}
.elgg-sidebar {
	position: relative;
	//padding: 32px 0 20px 30px;
  padding: 20px 15px;
	float: right;
	//width: 21.212121%;
  width: 200px;
	margin: 0;
	border-left: 1px solid #EBEBEB;//Posa la vora a la separació de menus
}
.elgg-sidebar-alt {
	position: relative;
	//padding: 32px 30px 20px 0;
  padding: 20px 15px;
	float: left;
	//width: 16.161616%;
	width: 150px;
  //margin: 0 30px 0 0;
  margin: 0;
	border-right: 1px solid #EBEBEB;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	//padding: 12px 0 10px 0;
  padding: 15px;
}
.elgg-main > .elgg-head {
	//padding-bottom: 5px;
	//border-bottom: 1px solid #EBEBEB;
	margin-bottom: 10px;
}
.elgg-layout-one-sidebar .elgg-main {
	float: left;
	width: 72.525252%;
}
.elgg-layout-one-sidebar {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/theme_kPAX/graphics/sidebar.png) repeat-y right top;
}
//Això descol·loca el menu lateral
/*.elgg-layout-two-sidebar .elgg-main {
	float: left;
	width: 50.101010%;
}*/
.elgg-layout-two-sidebar {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/theme_kPAX/graphics/sidebar-double.png) repeat-y right top;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
}
.elgg-page-footer {
	color: #aaa;
	text-shadow: 0px 1px 1px #000;
  min-height: 100px;
}
.elgg-page-footer a {
	color: #888;
  text-decoration: none;
}
.elgg-page-footer a:hover {
	color: #eee;
}

.elgg-page-footer > .elgg-inner {
	height: 65px;
	background: #222 url(<?php echo elgg_get_site_url(); ?>mod/theme_kPAX/graphics/footer.png) top right no-repeat;
}

/**** Afegits Cesar ***/

.coslogin {
	width: 300px;
	float: right;
}

.content {
	width: 550px;
	float: left;
	text-align: right;
}

.content h2 {
	font-size: 54px;
	padding-top: 20px;
}

.content p {
	font-size: 24px;
}
