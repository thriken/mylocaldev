<?PHP
//dirLIST v0.3.0 functions file

function get_dir_content($path)
{
	global $ftp_stream, $url_folder, $this_file_size, $this_file_name, $case_sensative_ext, $show_folder_size_ftp, $view_mode, $exclude_ext, $exclude, $listing_mode;
	
	if($listing_mode == 0)
	{
		$dh  = opendir($path);
		
		while (false !== ($item = readdir($dh)))
			$content[] = $item;
		
		if(empty($content))
			return $content;
		
		$media_detected = '';
		$images_detected = '';
			
		foreach($content as $key => $val)
		{
			if(!in_array($val, $exclude))
			{
				$item_path = $path.$val;
				
				if(is_dir($item_path))
				{
					$folders['name'][] = $val;
					$folders['date'][] = date("d F Y", filectime($item_path));
					$folders['link'][] = (empty($url_folder)) ? $val : $url_folder."/".$val;
				}
				else
				{
					$file_size = filesize($item_path);

					if(!($val == $this_file_name && $this_file_size == $file_size))//Exclude the main index file specifically
					{
						$file_ext = strrchr($val, ".");
						
						if($case_sensative_ext == 0) $file_ext = strtolower($file_ext);
							
						if(!in_array($file_ext, $exclude_ext))
						{
							$files['name'][] = $val;
							$files['size'][] = $file_size;
							$files['link'][] = $path.rawurlencode($val);
							$files['date'][] = date("d F Y", filectime($item_path));
							if($images_detected == '')
								$images_detected = (in_array(strtolower($file_ext), array('.jpeg', '.jpg', '.png', '.gif'))) ? 1 : 0;
							if($media_detected == '')
								$media_detected = (strtolower($file_ext) == '.mp3') ? 1 : 0;
						}
					}					
					
				}
			}
		}
	}
	elseif($listing_mode == 1)
	{
		//The below two lines are a possible fix for ftp folders with spaces in thier name, however, they are buggy when used. Remember to change the dirctory back to '/' if they are employed
		//ftp_chdir($ftp_stream, $path);
		//$content = ftp_rawlist($ftp_stream, '.');
		$content = ftp_rawlist($ftp_stream, $path);
		
		if(empty($content))
			return $content;
			
		if(!in_array(substr($content[0], 0, 1), array('d','-'))) //check if the ftp server is an IIS FTP server
			$iis_ftp = TRUE;
		
		$item_name_index = ($iis_ftp) ? 3 : 8;
		$item_size_index = ($iis_ftp) ? 2 : 4;		
		
		foreach($content as $key => $val)
		{
			$item = ($iis_ftp) ? preg_split("/[\s]+/",$val,4) : preg_split("/[\s]+/",$val,9);
			
			if($item[2] == '<DIR>' || substr($item[0], 0, 1) == 'd')
			{
				if(!in_array($item[$item_name_index], $exclude))
				{
					$folders['date'][] = ($iis_ftp) ? $item[1].' '.$item[0] : $item[7].' '.$item[6].' '.$item[5];
					$folders['name'][] = $item[$item_name_index];
					$folders['link'][] = (empty($url_folder)) ? $item[$item_name_index] : $url_folder.'/'.$item[$item_name_index];
				}
			}
			else
			{
					
				if(!($item[$item_name_index] == $this_file_name && $this_file_size == $item[$item_size_index]))
				{
					$file_ext = strrchr($item[$item_name_index], ".");
					if($case_sensative_ext == 0)
						$file_ext = strtolower($file_ext);
						
					if(!in_array($file_ext, $exclude_ext))
					{
						$files['date'][] = ($iis_ftp) ? $item[1].' '.$item[0] : $item[7].' '.$item[6].' '.$item[5];
						$files['size'][] = $item[$item_size_index];
						$files['name'][] = $item[$item_name_index];
						$files['link'][] = $path.$item[$item_name_index];
					}
					
				}
			}
		}
	//ftp_chdir($ftp_stream, '/');
	}
	return @array('folders' => $folders, 'files' => $files, 'images_detected' => $images_detected, 'media_detected' => $media_detected);
}

function delete_directory($item_path, $mode)
{
	
	if($mode == 0)
	{
		$dh  = opendir($item_path);
		
		while (false !== ($item = readdir($dh)))
			$dir_content[] = $item;
			
		closedir($dh);
		
		array_shift($dir_content);array_shift($dir_content);
		
		foreach($dir_content as $val)
		{
			$sub_item_path = $item_path.$val;
			
			if(is_file($sub_item_path))
				unlink($sub_item_path);
				
			elseif(is_dir($sub_item_path))
				delete_directory($sub_item_path.'/', 0);
		}
		rmdir($item_path);
	}
	elseif($mode == 1)
	{
		global $ftp_stream;
		
		$content = ftp_rawlist($ftp_stream, $item_path);
			
		if(!in_array(substr($content[0], 0, 1), array('d','-'))) //check if the ftp server is an IIS FTP server
			$iis_ftp = TRUE;
		
		$item_name_index = ($iis_ftp) ? 3 : 8;
		$item_size_index = ($iis_ftp) ? 2 : 4;		
		
		foreach($content as $key => $val)
		{
			$item = ($iis_ftp) ? preg_split("/[\s]+/",$val,4) : preg_split("/[\s]+/",$val,9);
			
			if($item[2] == '<DIR>' || substr($item[0], 0, 1) == 'd'){
				if(!in_array($item[$item_name_index], array('.', '..')))
					$folders[] = $item[$item_name_index];}
			else
				$files[] = $item[$item_name_index];
		}
		
		foreach($files as $val)
			ftp_delete($ftp_stream, $item_path.$val);
			
		foreach($folders as $val)
			delete_directory($item_path.$val.'/', 1);

		ftp_rmdir($ftp_stream, $item_path);
	}
}

function folder_size($path)
{
	$dir_content = get_dir_content($path.'/');
	
	foreach($dir_content['files']['size'] as $val)
		$total_size += $val;
		
	foreach($dir_content['folders']['name'] as $val)
		$total_size += folder_size($path.'/'.$val);
		
	return $total_size;
}

function letter_size($byte_size)
{
	$file_size = $byte_size/1024;
	if($file_size >=  1048576)
	$file_size = sprintf("%01.2f", $file_size/1048576)." GB";
	elseif ($file_size >=  1024)
	$file_size = sprintf("%01.2f", $file_size/1024)." MB";
	else
	$file_size = sprintf("%01.1f", $file_size)." KB";
	return $file_size;
}

function display_error_message($message)
{
	return '
	<table width="725" cellpadding="5" cellspacing="0" class="error">
		<tr>
			<td width="55" height="55" align="center" valign="middle" bgcolor="#FFBBBD"><img src="dirLIST_files/icons_large/error.png" width="48" height="48" /></td>
			<td bgcolor="#FFBBBD" valign="middle">'.$message.'</td>
		</tr>
	</table>';
}

function remove_ext($file)
{
    $ext = strrchr($file, '.');
    if($ext !== false)
    {
        $file = substr($file, 0, -strlen($ext));
    }
    return $file;
}

function max_upload_size()
{
	$post = ini_get("post_max_size");
	$file = ini_get("upload_max_filesize");
	return ($post < $file) ? $post : $file;
}

function sort_by_date($array)
{
	$temp = array();
	foreach($array as $val)
		$temp[] = strtotime($val);
	asort($temp);
	foreach($temp as $key => $val)
	{
		
		$temp[$key] = date("d F Y", $val);
	}
	return $temp;
}

function icons($files, $view_mode)
{
	//The index 0 and 1 represent the view modes thumbnails and list respectively
	$specific_icons[0] = array('.asp', '.aspx', '.css', '.dll', '.doc', '.docx', '.exe', '.ini', '.js', '.log', '.pdf', '.php', '.ppt', '.pptx', '.psd', '.rar', '.txt', '.rtf', '.xls', '.xlsx', '.zip');	
	$specific_icons[1] = array('.php','.js','.zip','.dll','.pdf','.ppt','.psd', '.rar','.xls');
	$images =array('.jpg','.jpeg','.gif','.png','.tiff','.bmp');
	$videos = array('.avi','.mpg','.mpeg','.wmv','.asf','.divx','.3gp', '.ram', '.mkv');
	$videos_qt = array('.mov', '.qt', '.mp4');
	$audio = array('.mp3', '.wav','.wma','.aac','.aif', '.asx','.mid','.midi');
	$real = array('.ram', '.ra', '.rm');
	$web = array('.asp','.htm','.html','.xhtml', '.url');
	$exec = array('.bat','.com','.exe', '.msi');
	$compressed = array('.ace','.tar', '.gz');
	$text[1] = array('.doc','.docx','.wpd','.rtf');
	$text_plain[1] = array('.txt', '.log','.ini', '.css');
	
	foreach($files as $key => $val)
	{
		$file_ext = strtolower(strrchr($val, "."));
		$icon_ext = ($view_mode == 0) ? '.png' : '.gif';
		
		if(in_array($file_ext, $specific_icons[$view_mode]))
			$files_icons[$key] = substr($file_ext, 1).$icon_ext;
		elseif(in_array($file_ext, $images))
			$files_icons[$key] = 'image'.$icon_ext;
		elseif(in_array($file_ext, $videos))
			$files_icons[$key] = 'video'.$icon_ext;
		elseif(in_array($file_ext, $videos_qt))
			$files_icons[$key] = 'mov'.$icon_ext;
		elseif(in_array($file_ext, $audio))
			$files_icons[$key] = 'audio'.$icon_ext;
		elseif(in_array($file_ext, $real))
			$files_icons[$key] = 'rm'.$icon_ext;
		elseif(in_array($file_ext, $web))
			$files_icons[$key] = 'web'.$icon_ext;
		elseif(in_array($file_ext, $exec))
			$files_icons[$key] = 'bat'.$icon_ext;
		elseif(in_array($file_ext, $compressed))
			$files_icons[$key] = 'zip'.$icon_ext;
		elseif(in_array($file_ext, $text[1]))
			$files_icons[$key] = "text.gif";	
		elseif(in_array($file_ext, $text_plain[1]))
			$files_icons[$key] = "text_plain.gif";
		else
			$files_icons[$key] = 'unknown'.$icon_ext;
	}
	
	return $files_icons;
}

function color_scheme($code)
{
	$scheme = array();
	
	$link_content_link = array('#006699','#D20000','#006600','#D78100','#A46200','#000000');
	$link_content_visited = array('#006699','#D20000','#006600','#D78100','#A46200','#000000');
	$link_content_hover = array('#006699','#FF1515','#009D00','#FFAC2F','#DD8500','#333333');
	$link_content_active = array('#006699','#D20000','#009D00','#FFAC2F','#DD8500','#333333');
	$link_sort_link = array('#FFFFFF','#FFB70D','#FFFFFF','#666666','#FFFFFF','#FFFFFF');
	$link_sort_visited = array('#FFFFFF','#FFB70D','#FFFFFF','#666666','#FFFFFF','#FFFFFF');
	$link_sort_hover = array('#FFFFFF','#FFB70D','#FFFFFF','#666666','#FFFFFF','#FFFFFF');
	$link_sort_active = array('#FFFFFF','#FFB70D','#FFFFFF','#666666','#FFFFFF','#FFFFFF');
	$top_row_color = array('#FFFFFF','#FFB70D','#FFFFFF','#666666','#FFFFFF','#FFFFFF');
	$top_row_bg = array('#006699','#840000','#005300','#FFE500','#995100','#333333');
	$main_table_folder_bg = array('#CCCCCC','#FF6F6F','#5FC13E','#FFF364','#FFA540','#8A8A8A');
	$main_table_file_bg1 = array('#E8F8FF','#FFCCCC','#D9FFC1','#FFF3C6','#FFCC93','#CCCCCC');
	$main_table_file_bg2 = array('#B9E9FF','#FFAAAA','#AEFF7D','#FFE88A','#FFB96A','#A6A6A6');
	
	$scheme['link_content']['link'] = $link_content_link[$code];
	$scheme['link_content']['visited'] = $link_content_visited[$code];
	$scheme['link_content']['hover'] = $link_content_hover[$code];
	$scheme['link_content']['active'] = $link_content_active[$code];;
	$scheme['link_sort']['link'] = $link_sort_link[$code];
	$scheme['link_sort']['visited'] = $link_sort_visited[$code];;
	$scheme['link_sort']['hover'] = $link_sort_hover[$code];;
	$scheme['link_sort']['active'] = $link_sort_active[$code];;
	$scheme['top_row']['color'] = $top_row_color[$code];
	$scheme['top_row']['bg'] = $top_row_bg[$code];
	$scheme['main_table']['folder_bg'] = $main_table_folder_bg[$code];
	$scheme['main_table']['file_bg1'] = $main_table_file_bg1[$code];
	$scheme['main_table']['file_bg2'] = $main_table_file_bg2[$code];
	
	return $scheme;
}

function set_local_text($language_id)
{
	/*Index of languages
	0: English
	1: French
	2: German
	3: Chinese
	*/
	
	$text = array(
	'index_of' => array('Index of', 'Index de', 'Index der','浏览'),
	
	'key' => array('Key', 'Cl?','Schl?ssel','说明'),
	
	'folder' => array('Folder', 'Dossier','Ordner','文件夹'),
	
	'file' => array('File','Fichier','Datei','文件'),
	
	'switch_to_list' => array('Switch to list view', 'Passez ? vue de liste','Umschalten auf Listenansicht','切换列表模式'),
	
	'switch_to_thumbnail' => array('Switch to thumbnail view','Switch to vignette','Umschalten auf Symboldarstellung ','切换缩略图模式'),
	
	'launch_gallery' => array('Launch Gallery', 'Lancer la galerie','Start Galerie','打开相册'),
	
	'launch_media_player' => array('Launch Media Player', 'Lancement media player','Starten Sie Media Player ','打开媒体播放器'),
	
	'show_hide_stats' => array('Show/hide statistics','Afficher/cacher les statistiques','Zeigen/verstecken Statistiken','显示/隐藏 统计'),
	
	'name' => array('Name', 'Nom','Name','名称'),
	
	'size' => array('Size','Taille','Gr??e','大小'),
	
	'date_uploaded' => array('Date Uploaded','Date Uploaded','Date Uploaded','上传日期'),
	
	'filesize_limit' => array('Filesize limit','Taille limite','Dateigr??e begrenzen','文件大小限制'),
	
	'banned_files' => array('Banned files','Fichiers bannis','Banned Dateien','禁止文件'),
	
	'this_page_loaded_in' => array('This page loaded in','Cette page charg?e dans','Diese Seite wurde geladen in','页面加载时间'),
	
	'seconds' => array('seconds','secondes','sekunden','秒'),
	
	'upload' => array('Upload','Upload','Hochladen','上传'),
	
	'select_language' => array('Select Language','S?lection de la langue','Sprache ausw?hlen','选择语言'),
	
	'total_folders' => array('Total folders','Total dossiers','Insgesamt Ordner','文件夹'),
	
	'total_files' => array('Total files','Total fichiers','Gesamt-Dateien','文&nbsp;&nbsp;&nbsp;件 '),
	
	'total_files_and_folders' => array('Combined','Combin?','Kombinierte','合&nbsp;&nbsp;&nbsp;计'),
	
	'consuming' => array('consuming', 'consommation','konsum','占用磁盘'),
	
	'english' => array('English','Anglais','Englisch','英语'),
	
	'french' => array('French','Fran?ais','Franz?sisch','法语'),
	
	'german' => array('German','Allemand','Deutsch','德语'),
	
	'Chinese' => array('Chinese','Espagnol','Spanisch','中文'),
	
	'warning' => array('WARNING', 'AVERTISSEMENT', 'WARNUNG', '警告'),
	
	'no_go_back' => array('This action is irreversible', 'Cette action est irr?versible', 'Diese Aktion ist nicht mehr r?ckg?ngig zu', '警告 ！此操作是不可逆的 ！'),
	
	'sure_to_del' => array('Are you sure you want to delete', '?tes-vous s?r de vouloir supprimer','Sind Sie sicher, dass Sie l?schen m?chten','确定删除'),
	
	'check_for_update' => array('Check for update','V?rifier les mises ? jour','Siehe, wenn Update verf?gbar ist','检查更新'),
	
	'update_available' => array('Update Available','Mise ? jour disponible','Update verf?gbar','有可用更新'),
	
	'no_update_found' => array('No Update Found','Pas de mise ? jour introuvable','Keine Aktualisierung gefunden','无更新'),
	
	'current_name' => array('Current name','Nom actuel','Aktuellen namen','当前名称'),
	
	'new_name' => array('New name','Nouveau nom','Neuen namen','新名称'),
	
	'rename' => array('Rename','Renommer','Umbenennen','重命名'),
	);
	
	foreach($text as $key => $val)
		$local_text[$key] = $val[$language_id];
	
	return $local_text;
}
?>