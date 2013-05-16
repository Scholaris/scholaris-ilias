<?php

include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");

/**
 * ilPScholarisPlugin class for Scholaris plugin
 * @author            Marcin Czachurski
 * @version           $Id$
 */
class ilPScholarisPlugin extends ilUserInterfaceHookPlugin
{
	function getPluginName()
	{
		return "PScholaris";
	}

}