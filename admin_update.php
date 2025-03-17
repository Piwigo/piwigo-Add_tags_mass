<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based picture gallery                                  |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+
if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

include_once(PHPWG_ROOT_PATH.'admin/include/functions.php');
include_once(PHPWG_ROOT_PATH.'admin/include/functions_upload.inc.php');

$admin_base_url = get_root_url().'admin.php?page=plugin-Add_tags_mass-update';

// +-----------------------------------------------------------------------+
// | Checks                                                                |
// +-----------------------------------------------------------------------+

check_status(ACCESS_ADMINISTRATOR);


// +-----------------------------------------------------------------------+
// | Actions                                                               |
// +-----------------------------------------------------------------------+
if (isset($_POST['submit'])){
	
if (isset($_POST['tagname']) and $_POST['tagname'] != "")
{
		$starttime = get_moment();

			$raw = stripslashes($_POST['tagname']);
			$raw_lines = explode("\n", $raw); 
			array_walk($raw_lines, 'trim_value');
			$raw_lines = array_filter($raw_lines);
			$raw_lines = array_values($raw_lines);
		
				$query = 'SELECT name FROM '.TAGS_TABLE.';';
				$existing_tags = array_from_query($query, 'name');
				array_walk($existing_tags, 'trim_value');
		
					$update_files = array();
					$missing_files = array();
				  
			foreach ($raw_lines as $tag_name)
			{
				
				if (!in_array($tag_name, $existing_tags))
				{
					$update_files[$tag_name] = true;
					mass_inserts(
						TAGS_TABLE,
							array('name', 'url_name'),
							array(
							array(
								'name' => addslashes($tag_name),
								'url_name' => trigger_change('render_tag_url', $tag_name),
							)
						)
					);
				}
				else
				{
					$missing_files[$tag_name] = true;
				}
			}
			  
      $endtime = get_moment();
	  
      $elapsed = ($endtime - $starttime);

      		array_push($page['infos'],stripslashes(sprintf(l10n('%d Add tags: %s'),count($update_files),implode(', ', array_keys($update_files)))));
      
			if (count($missing_files) > 0)
			{
				array_push($page['errors'],stripslashes(sprintf(l10n('%d Not add tags: %s'),count($missing_files),implode(', ', array_keys($missing_files)))));
			}
    
	}
	else
	{
	$page['errors'][] = l10n('No tags');
	}
}

// +-----------------------------------------------------------------------+
// | form options                                                          |
// +-----------------------------------------------------------------------+

// image level options
$selected_level = isset($_POST['level']) ? $_POST['level'] : 0;
$template->assign(
    array(
      'level_options'=> get_privacy_level_options(),
      'level_options_selected' => array($selected_level)
    )
  );
  
// +-----------------------------------------------------------------------+
// | add function                                                    |
// +-----------------------------------------------------------------------+
function trim_value(&$value)
{
    $value = trim($value);
}

function encodeToUtf8($string) 
{
	return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-15, ISO-8859-1", true));
}
?>
