<?php
/**
 * Native .env loader for CodeIgniter 3.
 *
 * Reads the project root .env file and exposes variables to PHP without
 * overwriting values already provided by the operating system or container.
 */

// -----------------------------------------------------
// Resolve .env File Path
// -----------------------------------------------------
// application/config/env.php -> project root is two levels up.
$env_file = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env';

if ( ! is_readable($env_file))
{
	return;
}

// -----------------------------------------------------
// Parse .env File
// -----------------------------------------------------
$lines = file($env_file, FILE_IGNORE_NEW_LINES);

if ($lines === FALSE)
{
	return;
}

foreach ($lines as $line)
{
	$line = trim($line);

	// Skip empty lines and comments.
	if ($line === '' || $line[0] === '#')
	{
		continue;
	}

	// Skip lines without a key/value separator.
	if (strpos($line, '=') === FALSE)
	{
		continue;
	}

	list($name, $value) = explode('=', $line, 2);
	$name = trim($name);
	$value = trim($value);

	if ($name === '')
	{
		continue;
	}

	// Remove optional surrounding quotes.
	if (
		(strlen($value) >= 2)
		&& (
			($value[0] === '"' && substr($value, -1) === '"')
			|| ($value[0] === "'" && substr($value, -1) === "'")
		)
	)
	{
		$value = substr($value, 1, -1);
	}

	// Do not overwrite non-empty variables already supplied by the OS/container.
	// Empty container values (e.g. from an old .env) may be replaced by the file.
	$existing = getenv($name);
	if ($existing !== FALSE && $existing !== '')
	{
		continue;
	}

	putenv($name . '=' . $value);
	$_ENV[$name] = $value;
	$_SERVER[$name] = $value;
}