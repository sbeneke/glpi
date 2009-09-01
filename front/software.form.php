<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------


$NEEDED_ITEMS = array('computer', 'contract', 'document', 'enterprise', 'group', 'infocom',
   'link', 'reservation', 'rulesengine', 'rule.softwarecategories', 'software', 'tracking',
   'user');

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");


if(!isset($_GET["id"])) $_GET["id"] = "";
if(!isset($_GET["sort"])) $_GET["sort"] = "";
if(!isset($_GET["order"])) $_GET["order"] = "";
if(!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$soft=new Software();
if (isset($_POST["add"]))
{
	$soft->check(-1,'w',$_POST);

	$newID=$soft->add($_POST);
	logEvent($newID, "software", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][20]." ".$_POST["name"].".");
	glpi_header($_SERVER['HTTP_REFERER']);
}
else if (isset($_POST["delete"]))
{
	$soft->check($_POST["id"],'w');

	if (!empty($_POST["withtemplate"]))
		$soft->delete($_POST,1);
	else $soft->delete($_POST);

	logEvent($_POST["id"], "software", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][22]);
	if(!empty($_POST["withtemplate"]))
		glpi_header($CFG_GLPI["root_doc"]."/front/setup.templates.php");
	else
		glpi_header($CFG_GLPI["root_doc"]."/front/software.php");
}
else if (isset($_POST["restore"]))
{
	$soft->check($_POST["id"],'w');

	$soft->restore($_POST);
	logEvent($_POST["id"], "software", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][23]);
	glpi_header($CFG_GLPI["root_doc"]."/front/software.php");
}
else if (isset($_POST["purge"]) || isset($_GET["purge"]))
{
	if (isset($_POST["purge"]))
		$input["id"]=$_POST["id"];
	else
		$input["id"] = $_GET["id"];

	$soft->check($input["id"],'w');

	$soft->delete($input,1);
	logEvent($input["id"], "software", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][24]);
	glpi_header($CFG_GLPI["root_doc"]."/front/software.php");
}
else if (isset($_POST["update"]))
{
	$soft->check($_POST["id"],'w');

	$soft->update($_POST);
	logEvent($_POST["id"], "software", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][21]);
	glpi_header($_SERVER['HTTP_REFERER']);
}
else if (isset($_POST["mergesoftware"]))
{
	$soft->check($_POST["id"],'w');

	popHeader($LANG['Menu'][4]);

	if (isset($_POST["id"]) && isset($_POST["item"]) && is_array($_POST["item"]) && count($_POST["item"])) {
		mergeSoftware($_POST["id"], $_POST["item"]);
	}
	glpi_header($_SERVER['HTTP_REFERER']);
}
else
{


	commonHeader($LANG['Menu'][4],$_SERVER['PHP_SELF'],"inventory","software");

	$soft->showForm($_SERVER['PHP_SELF'],$_GET["id"],$_GET["withtemplate"]);

	commonFooter();
}

?>
