<?php
#############################################
# strings
#############################################

# money format
function get_money($value, $simple=false) {
	if($value > 0) {
		$class = 'money';
	} elseif($value < 0 ) {
		$class = 'money_neg';
	} else {
		$class = 'money_zero';
	}
	if($value >= 0) {
		if($simple) {
			return '<span class="'.$class.'">$<strong>'.number_format($value, 0,',','.').'</strong></span>';
		} else {
			return '<span class="'.$class.'">$<strong>'.number_format($value, 0,',','.').'</strong> <small>'.m('value').'</small></span>';
		}
	} else {
		if($simple) {
			return '<span class="'.$class.'">-$<strong>'.number_format((-1)*$value, 0,',','.').'</strong></span>';
		} else {
			return '<span class="'.$class.'">-$<strong>'.number_format((-1)*$value, 0,',','.').'</strong> <small>'.m('value').'</small></span>';
		}
	}
}

# language pack manager
function m($string) {
	global $m;	
	if($caption = $m[$string]) {
		return $caption;
	} else {
		return $string;
	}
}

# ASCII comparision
function ascii_comparision($a, $b) {
	$at = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
	$bt = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
	return strcmp($at, $bt);
}

# sanitize words
function sanitize_words($string,$limit=false) {
	preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]{1,}/u",$string,$matches,PREG_PATTERN_ORDER);
	$words = array_unique($matches[0]);
	if($limit && is_array($words)) $words = array_slice($words,0,$limit);
	return $words;
}

# sanitize sentence
function sanitize_sentence($string,$limit=false) {
	$pats = array(
	'/([.!?]\s{2}),/',		# Abc.  ,Def
	'/\.+(,)/',				# ......,
	'/(!)!+/',				# abc!!!!!!!!
	'/\s+(,)/',				# abc   , def
	'/([a-zA-Z])\1\1/');	# greeeeeeen
	$fixed = preg_replace($pats,'$1',$string);
	$fixed = preg_replace('/,(?!\s)/',', ',$fixed);
	if($limit) {
		return gen_string($fixed,$limit,true);
	} else {
		return $fixed;
	}
}

# truncate string
function gen_string($string,$max=20,$dry=false) {
	$array = explode("\n",wordwrap($string, $max, "\n", true));
	if(!$dry) if($max < strlen($string)) $array[0] .= '&hellip;';
	return $array[0];
}

# utf8 chars decoder
function replace_unicode_escape_sequence($match) {
	return mb_convert_encoding(pack('H*',$match[1]),'UTF-8','UCS-2BE');
}
function sanitize_unicode_chars($string) {
	return preg_replace_callback('/\\\\u([0-9a-f]{4})/i','replace_unicode_escape_sequence',$string);
}

# validate email sintaxis
function validate_email($email) {
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}

#############################################
# dates
#############################################

# date
function validate_date($date) {
	if($date) {
		$time = strtotime($date);
		if(checkdate(date('n', $time), date('j', $time), date('Y', $time))) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

# date with locale config
function gen_date($date, $time=false) {
	if($time) {
		return utf8_encode(strftime('%e %B %Y - %Hh%M', strtotime($date)));
	} else {
		return utf8_encode(strftime('%e %B %Y', strtotime($date)));
	}
}

# short date with locale config
function gen_date_short($date) {
	return utf8_encode(strftime('%e %b', strtotime($date)));
}

# short date for lists
function gen_date_list($date) {
	if(date('Ymd') == date('Ymd', strtotime($date))) {
		return m('today');
	} elseif(date('Ymd', strtotime('-1 day')) == date('Ymd', strtotime($date))) {
		return m('yesterday');
	} else {
		return utf8_encode(strftime('%e %b', strtotime($date)));
	}
}

# short date for lists
function gen_date_birthday($date) {
	return utf8_encode(strftime('%e %B', strtotime(date('Y').'-'.$date)));
}

# fix date for ranges
function format_month_range($date=false) {
	if($date) {
		return date('Y-m-01', strtotime($date));
	} else {
		return date('Y-m-01');
	}
}
