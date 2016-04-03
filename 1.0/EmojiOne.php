<?php
if (!defined('SMF')) 
	die('Hacking attempt...');

function phase_emojione_data(&$data) {
	$data = mb_encode_numericentity ($data, array (0x1F000, 0x1F9FF, 0, 0x1F9FF,0x02600, 0x027FF, 0, 0x027FF), 'UTF-8');
	preg_match_all('/&#([0-9]{4,6});/', $data, $matches, PREG_PATTERN_ORDER);
	if (!empty($matches[0])) {
		$replaces = array();
		foreach ($matches[0] as $match => $dec) {
			$int = substr($dec,2,-1);
			if ($int >= 126976 && $int <= 129535 || $int >= 9728 && $int <= 10239) { // Emoji ranges
				$hex = dechex($int);
				if (isset($adec) && isset($replaces[$adec['dec']]) && ($int >= 127995 && $int <= 127999 || $int >= 127462 && $int <= 127487 && $adec['int'] >= 127462 && $adec['int'] <= 127487)) { // Modifiers Color || Flags
					unset($replaces[$adec['dec']]);
					$replaces[$adec['dec'].$dec] = '<img src="http://cdnjs.cloudflare.com/ajax/libs/emojione/2.1.3/assets/svg/'.$adec['hex'].'-'.$hex.'.svg" alt="'.$adec['dec'].$dec.'" class="SM_EmojiOne" />';
				} else {
					$replaces[$dec] = '<img src="http://cdnjs.cloudflare.com/ajax/libs/emojione/2.1.3/assets/svg/'.$hex.'.svg" alt="'.$dec.'" class="SM_EmojiOne" />';
					$adec = array('dec' => $dec, 'int' => $int, 'hex' => $hex);
				}
			}
		}
		$data = strtr($data, $replaces);
	}
}
