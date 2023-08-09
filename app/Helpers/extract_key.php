<?php

/**
 * Checks for $array[$key] and returns its value if it exists, else
 * returns $default.
 *
 * Shorthand for $value = (isset($array['key'])) ? $array['key'] : 'default';
 *
 * @param string $key     Key to check in the source array
 * @param array  $array   Source array
 * @param mixed  $default Value to return if key is not found
 * @param bool   $strict  Return array key if it's set, even if empty. If false,
 *                        return $default if the array key is unset or empty.
 *
 * @return mixed
 * @since 1.8.0
 */
function extract_key($key, $array, $default = null, $strict = true) {
	if (!is_array($array)) {
		return $default;
	}

	if ($strict) {
		return (isset($array[$key])) ? $array[$key] : $default;
	} else {
		return (isset($array[$key]) && !empty($array[$key])) ? $array[$key] : $default;
	}
}