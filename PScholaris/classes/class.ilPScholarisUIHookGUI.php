<?php
/* Copyright (c) 1998-2012 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once("./Services/UIComponent/classes/class.ilUIHookPluginGUI.php");
include_once('class.ilPScholarisConfigGUI.php');

/**
 * ilPScholarisUIHookGUI class for Scholaris menu
 * @author            Marcin Czachurski
 * @version           $Id$
 */
class ilPScholarisUIHookGUI extends ilUIHookPluginGUI {
	/**
	 * Get html for a user interface area
	 *
	 * @param
	 * @return
	 */
	function getHTML($a_comp, $a_part, $a_par = array()) 
	{
		global $rbacsystem;
	
		if(!$rbacsystem->checkAccess("visible", SYSTEM_FOLDER_ID))
		{
			return;
		}
	
	   // add something to the main menu entries
		if ($a_comp == "Services/MainMenu" && $a_part == "main_menu_list_entries")
		{
				// $a_par["main_menu_gui"] is ilMainMenu object
				
				return array("mode" => ilUIHookPluginGUI::APPEND, "html" =>
					  '<li class="ilMainMenu" id="scholarismenu"><a id="mm_schol_tr" class="MMInactive" href="#" target="_top">'
					. 'Scholaris'
					. '<img border="0" alt="" src="./templates/default/images/mm_down_arrow.png"></a>'
					. '<div id="mm_schol_ov" class="ilOverlay ilNoDisplay yui-module yui-overlay" style="z-index: 2; visibility: visible; display: block; left: 20px; top: 84px; overflow: auto;">'
					. '<a class="ilGroupedListLE " target="_top" id="mm_pd_sel_items" href="ilias.php?baseClass=ilScholarisServiceGUI&amp;cmd=resources">Zasoby</a>'
					//. '<a class="ilGroupedListLE " target="_top" id="mm_pd_crs_grp" href="ilias.php?baseClass=ilScholarisServiceGUI&amp;cmd=export">Eksport wyników</a>'
					. '</div>'
					. '</li>'
					. '<script type="text/javascript">'
					. 'il.Util.addOnLoad(function() { il.Overlay.add("mm_schol_ov", {"yuicfg":{"visible":false,"fixedcenter":false,"context":["mm_schol_tr","tl","bl",["beforeShow","windowResize"]]},"trigger":"mm_schol_tr","trigger_event":"click","anchor_id":null,"auto_hide":false,"close_el":null});});'
					. 'var destination = $("ul").filter(function() { return $(this).attr("class") == "ilMainMenu"; });'
					. '$("#scholarismenu").appendTo(destination);'
					. '</script>'
				);
		}
	}

	/**
	 * @return string
	 */
	function getBlockHTML() {
		return "";
	}
}

?>
