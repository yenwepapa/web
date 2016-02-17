<?php
//
// File generated by ... on the 2016-02-16T17:47:18+0100
// Please do not edit manually
//

/**
 * Classes and menus for itop-attachments (version 2.2.0)
 *
 * @author      iTop compiler
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Module attachments
 * 
 * A quick and easy way to upload and attach files to *any* (see Configuration below) object in the CMBD in one click
 *
 * Configuration: the list of classes for which the "Attachments" tab is visible is defined via the module's 'allowed_classes'
 * configuration parameter. By default the tab is active for all kind of Tickets.
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class Attachment extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'addon,bizmodel',
			'key_type' => 'autoincrement',
			'name_attcode' => array('item_class', 'temp_id'),
			'state_attcode' => '',
			'reconc_keys' => array(''),
			'db_table' => 'attachment',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
			'indexes' => array (
  1 => 
  array (
    0 => 'temp_id',
  ),
  2 => 
  array (
    0 => 'item_class',
    1 => 'item_id',
  ),
  3 => 
  array (
    0 => 'item_org_id',
  ),
),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime("expire", array("allowed_values"=>null, "sql"=>'expire', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("temp_id", array("allowed_values"=>null, "sql"=>'temp_id', "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("item_class", array("allowed_values"=>null, "sql"=>'item_class', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeObjectKey("item_id", array("class_attcode"=>'item_class', "allowed_values"=>null, "sql"=>'item_id', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeInteger("item_org_id", array("allowed_values"=>null, "sql"=>'item_org_id', "default_value"=>'0', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeBlob("contents", array("is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));



		MetaModel::Init_SetZListItems('details', array (
  0 => 'temp_id',
  1 => 'item_class',
  2 => 'item_id',
  3 => 'item_org_id',
));
		MetaModel::Init_SetZListItems('standard_search', array (
  0 => 'temp_id',
  1 => 'item_class',
  2 => 'item_id',
));
		MetaModel::Init_SetZListItems('list', array (
  0 => 'temp_id',
  1 => 'item_class',
  2 => 'item_id',
));

	}


	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, e.g. 'org_id'
	 * @return string Filter code, e.g. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'org_id')
		{
			return 'item_org_id';
		}
		else
		{
			return null;
		}
	}

	/**
	 * Set/Update all of the '_item' fields
	 * @param object $oItem Container item
	 * @return void
	 */
	public function SetItem($oItem, $bUpdateOnChange = false)
	{
		$sClass = get_class($oItem);
		$iItemId = $oItem->GetKey();

 		$this->Set('item_class', $sClass);
 		$this->Set('item_id', $iItemId);

		$aCallSpec = array($sClass, 'MapContextParam');
		if (is_callable($aCallSpec))
		{
			$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter					
			if (MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				$iOrgId = $oItem->Get($sAttCode);
				if ($iOrgId > 0)
				{
					if ($iOrgId != $this->Get('item_org_id'))
					{
						$this->Set('item_org_id', $iOrgId);
						if ($bUpdateOnChange)
						{
							$this->DBUpdate();
						}
					}
				}
			}
		}
	}

	/**
	 * Give a default value for item_org_id (if relevant...)
	 * @return void
	 */
	public function SetDefaultOrgId()
	{
		// First check that the organization CAN be fetched from the target class
		//
		$sClass = $this->Get('item_class');
		$aCallSpec = array($sClass, 'MapContextParam');
		if (is_callable($aCallSpec))
		{
			$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter					
			if (MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				// Second: check that the organization CAN be fetched from the current user
				//
				if (MetaModel::IsValidClass('Person'))
				{
					$aCallSpec = array($sClass, 'MapContextParam');
					if (is_callable($aCallSpec))
					{
						$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter					
						if (MetaModel::IsValidAttCode($sClass, $sAttCode))
						{
							// OK - try it
							//
							$oCurrentPerson = MetaModel::GetObject('Person', UserRights::GetContactId(), false);
							if ($oCurrentPerson)
							{
						 		$this->Set('item_org_id', $oCurrentPerson->Get($sAttCode));
						 	}
						}
					}
				}
			}
		}
	}

}