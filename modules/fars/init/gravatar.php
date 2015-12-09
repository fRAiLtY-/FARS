<?php 

class Gravitar
{
	const HTTP_URL = 'http://www.gravatar.com/avatar/';
	const HTTPS_URL = 'https://secure.gravatar.com/avatar/';
	
	const SIZE = 48;
	const DEFAULT_IMAGE = 'mm';
	const RATING = 'G';

	public static function hash($email)
	{
		return hash('md5', strtolower(trim($email)));
	}

	public static function url($email, $size = null)
	{
		$url = (Phpr::$request->protocol() == 'https') ? static::HTTPS_URL : static::HTTP_URL;

		$url .= self::hash($email);

		$params = array();
		$params['s'] = $size ?: static::SIZE;
		$params['r'] = static::RATING;
		$params['d'] = static::DEFAULT_IMAGE;
		$url .= '?'.http_build_query(array_filter($params));

		return $url;
	}
}