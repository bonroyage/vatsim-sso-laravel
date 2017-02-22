<?php
/*
 * DO NOT PUBLISH THE KEY, SECRET AND CERT TO CODE REPOSITORIES
 * FOR SECURITY. PLEASE USE LARAVEL'S .env.php FILES TO PROTECT
 * SENSITIVE DATA.
 * hhttps://laravel.com/docs/4.2/configuration#protecting-sensitive-configuration
 */

return [

	/*
	 * The location of the VATSIM OAuth interface
	 */
	'base'   => '',

	/*
	 * The consumer key for your organisation (provided by VATSIM)
	 */
	'key'    => '',

	/*
	* The secret key for your organisation (provided by VATSIM)
	* Do not give this to anyone else or display it to your users. It must be kept server-side
	*/
	'secret' => '',

	/*
	 * The URL users will be redirected to after they log in, this should
	 * be on the same server as the request
	 */
	'return' => '',

	/*
	 * The signing method you are using to encrypt your request signature.
	 * Different options must be enabled on your account at VATSIM.
	 * Options: RSA / HMAC
	 */
	'method' => 'HMAC',

	/*
	 * Your RSA **PRIVATE** key
	 * If you are not using RSA, this value can be anything (or not set)
	 */
	'cert'   => '',

	/*
	 * Set to true to allow suspended users to sign in
	 */
	'allow_suspended' => false,

	/*
	 * Set to true to allow inactive users to sign in
	 */
	'allow_inactive'  => false,

];
