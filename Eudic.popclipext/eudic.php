<?php

// applescript to create the note in evernote
$applescript = <<<END
--tell application "System Events" to tell process "SystemUIServer"
--	tell (menu bar item 1 of menu bar 1 where description is "text input")
--		click
--		click menu item "美国" of menu 1
--	end tell
--end tell

tell application "Eudb_en" to activate
tell application "System Events"
	tell process "Eudb_en"
		delay 0.2
		set theText to "{popclip text}"

		keystroke "a" using command down
		keystroke theText
		keystroke return
		delay 0.5
		--keystroke "a" using {command down, shift down}
		tell menu bar item "学习" of menu bar 1
			click
			click UI element "从生词本中删除当前单词" of UI element "学习"
			click UI element "加入生词本" of UI element "学习"
		end tell
	end tell
	delay 1
	keystroke tab using {command down}
end tell


END;
							
// escape backslashes and double quotes
function applescript_safe($string) {
	$string=str_replace("\\", "\\\\", $string);
	$string=str_replace("\"", "\\\"", $string);
	return $string;
}



function force_string($str) {
	return is_string($str)?$str:'';
}

// get the required fields
$popclip_text=force_string(getenv('POPCLIP_TEXT'));
$popclip_html=force_string(getenv('POPCLIP_HTML'));
$popclip_browser_url=force_string(getenv('POPCLIP_BROWSER_URL'));
$popclip_browser_title=force_string(getenv('POPCLIP_BROWSER_TITLE'));
							
// Fill in applescript template fields.
$applescript=str_replace("{popclip text}", applescript_safe($popclip_text), $applescript);
$applescript=str_replace("{popclip html}", applescript_safe($popclip_html), $applescript);
$applescript=str_replace("{popclip browser url}", applescript_safe($popclip_browser_url), $applescript);
$applescript=str_replace("{popclip browser title}", applescript_safe($popclip_browser_title), $applescript);

// Call script
$escapedscript=escapeshellarg($applescript);
$result=`echo $escapedscript | osascript -`;

?>