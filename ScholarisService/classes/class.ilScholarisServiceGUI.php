<?php
 
/* Copyright (c) 1998-2011 ILIAS open source, Extended GPL, see docs/LICENSE */
 
/**
 * Strona do Scholaris.
 *
 * @author Marcin Czachurski.
 * @version $Id$
 */
class ilScholarisServiceGUI
{
	/**
	* The reference to the Template class
	*
	* @var object
	*/
	var $tpl;

		/**
	* The reference to the ILIAS control class
	*
	* @var object
	*/
	var $ctrl;
	
	/**
	 * Constructor
	 *
	 * @param
	 * @return
	 */
	function ilScholarisServiceGUI()
	{
		global $tpl, $ilCtrl;
			
		$this->ctrl =& $ilCtrl;
		$this->tpl =& $tpl;
	
		// get the standard template        
		$this->tpl->getStandardTemplate();
	}
		
	/**
	 * Execute command
	 *
	 * @param
	 * @return
	 */
	function &executeCommand()
	{
		$cmd = $this->ctrl->getCmd();
		$next_class = $this->ctrl->getNextClass($this);

		$cmd = $this->getCommand($cmd);
		switch($next_class)
		{
			default:
				$ret =& $this->$cmd();
				break;
		}
		
		$this->tpl->show();
		//return $ret;
	}
		
	/**
	* Retrieves the ilCtrl command
	*/
	public function getCommand($cmd)
	{
		return $cmd;
	}

	/**
	* Deletes the certificate and all its data
	*/
	public function export()
	{			
		global $ilDB;
	
		// set title
		$this->tpl->setTitle("Eksport wyników");
		 
		// set title icon
		$this->tpl->setTitleIcon(ilUtil::getImagePath("icon_skmg_b.png"));
		 
		// set description
		$this->tpl->setDescription("Możliwość wyeksportowania wyników do systemu Scholaris");
	
		$texport = new ilTemplate("tpl.export.html", false, false, "Services/ScholarisService");
		
        $query = "SELECT DISTINCT obd.obj_id, obd.title " .
                "FROM object_data obd " .
                "WHERE obd.type = 'crs' " .
                "ORDER BY obd.title";

        $result = $ilDB->query($query);
		
        while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) 
		{
			$texport->setCurrentBlock("curses");
			$texport->setVariable("CourseId", $row['obj_id']);
			$texport->setVariable("CourseName", $row['title']);
			$texport->parseCurrentBlock();
        }
		
		$html = $texport->get();
		
		$this->tpl->setContent($html);
	}
	
	public function file()
	{				
		$this->tpl->setContent("ZAWARTOŚĆ PLIKU");
	}
	
	public function saveresource()
	{				
		global $ilDB;
	
		$result = $ilDB->query("SELECT * FROM pscholaris_data_types");
		
        while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) 
		{
			switch($row['ps_key'])
			{
				case "username":
					$username = $row["ps_value"];
				break;
				case "token":
					$token = $row["ps_value"];
				break;
			}
        }
		
		if(isset($username))
		{
			$statement = $ilDB->prepareManip("UPDATE pscholaris_data_types SET ps_value = ? WHERE ps_key = 'username'", array("text"));
			$data = array($_POST["username"]);
			$affectedRows = $ilDB->execute($statement, $data);
			$ilDB->free($statement);
		}
		else
		{
			$statement = $ilDB->prepareManip("INSERT INTO pscholaris_data_types (ps_key, ps_value) VALUES (?, ?)", array("text", "text"));
			$data = array("username", $_POST["username"]);
			$affectedRows = $ilDB->execute($statement, $data);
			$ilDB->free($statement);
		}
		
		if(isset($token))
		{
			$statement = $ilDB->prepareManip("UPDATE pscholaris_data_types SET ps_value = ? WHERE ps_key = 'token'", array("text"));
			$data = array($_POST["token"]);
			$affectedRows = $ilDB->execute($statement, $data);
			$ilDB->free($statement);
		}
		else
		{
			$statement = $ilDB->prepareManip("INSERT INTO pscholaris_data_types (ps_key, ps_value) VALUES (?, ?)", array("text", "text"));
			$data = array("token", $_POST["token"]);
			$affectedRows = $ilDB->execute($statement, $data);
			$ilDB->free($statement);
		}
		
		$this->ctrl->redirect($this, "resources");
	}
	
	public function resources()
	{				
		global $ilDB;
		
		$username = "";
		$token = "";
		$result = $ilDB->query("SELECT * FROM pscholaris_data_types");
		
        while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) 
		{
			switch($row['ps_key'])
			{
				case "username":
					$username = $row["ps_value"];
				break;
				case "token":
					$token = $row["ps_value"];
				break;
			}
        }

		// set title
		$this->tpl->setTitle("Zasoby");
		 
		// set title icon
		$this->tpl->setTitleIcon(ilUtil::getImagePath("icon_skmg_b.png"));
		 
		// set description
		$this->tpl->setDescription("Możliwość podglądu zasobów w systemie Scholaris");
	
		$texport = new ilTemplate("tpl.resource.html", false, false, "Services/ScholarisService");
		
		$texport->setVariable("UserName", $username);
		$texport->setVariable("Token", $token);
		
		$html = $texport->get();
		
		$this->tpl->setContent($html);
	}
}
 
?>