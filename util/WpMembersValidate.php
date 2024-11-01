<?php

class WpMembersValidate {

	public static function isNotNumeric($value) {
		if (preg_match("/[^１２３４５６７８９０0-9-]/", $value))
			return TRUE;
		return FALSE;
	}

	public static function isNotHankakuNumeric($value) {
		if (preg_match("/[^0-9-]/", $value))
			return TRUE;
		return FALSE;
	}

	public static function isNotAlphabet($value) {
		if (preg_match("/[^ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺa-zA-Z]/", $value))
			return TRUE;
		return FALSE;
	}

	public static function isNotHankaku($value) {
		if (preg_match("/[\x7F-\xFF]/", $value))
			return TRUE;
		return FALSE;
	}
    
	public static function isNotHankakuAlphabetNumeric($value) {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) return TRUE;
        return FALSE;
    }

	public static function isNotMailAddress($value) {
		if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value))
			return FALSE;
		return TRUE;
	}

	public static function isNotSet($value) {
		if (!isset($value))
			return TRUE;
		if ($value == "")
			return TRUE;
		return FALSE;
	}

	public static function isKishuIzonCharacter($value) {
		if (strlen($value) !== strlen(mb_convert_encoding(mb_convert_encoding($value, 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) return true;
		return false;
	}
    
    public static function isNotUrl($value) {
        if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $value)) {
            return false;
        }
        return true;
    }

}
?>