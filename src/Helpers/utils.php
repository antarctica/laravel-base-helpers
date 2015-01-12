<?php

/**
 * Returns the current api version.
 * This value is set in app/config/app.php.
 *
 * @return int
 */
function getApiVersion()
{
    return Config::get('app.api_version');
}

/**
 * Converts a given datetime string into an array containing, the datetime string, unix_timestamp, timezone and timezone offset
 *
 * @param $attribute
 * @return array
 */
function formatDateTime($attribute)
{
    $attribute = Carbon::parse($attribute);

    return [
        'datetime' => (string) $attribute,
        'datetime_unix' => $attribute->timestamp,
        'timezone' => $attribute->timezoneName,
        'utc_offset_hours' => $attribute->offsetHours
    ];
}

/**
 * Acts as a wrapper around Input::get() but checks in headers as well for getting a single argument.
 *
 * If a given argument isn't found, or its value is NULL like (e.g. ' ') a default value can be returned,
 * if no default is given NULL is returned.
 *
 * If a given argument is found in both headers and input, the input value will clobber the header value.
 *
 * You can ignore either 'headers' or 'input' by adding pass either in an excludedSources array
 *
 * Note: All values are returned as strings (i.e. you will need to recast to int/bool as needed).
 *
 * @param string $argumentName
 * @param mixed|null $defaultArgumentValue
 * @param bool $prefixArgumentHeaderName
 * @param array $excludedSources
 * @return mixed|null
 */
function getArgument($argumentName, $defaultArgumentValue = null, $prefixArgumentHeaderName = true, array $excludedSources = [])
{
    $argumentValue = $defaultArgumentValue;

    if (in_array('headers', $excludedSources) === false) {
        // Look for argument in headers
        // Laravel auto-checks for blank or empty values and considers them equal to null
        $argumentHeaderName = $argumentName;
        if ($prefixArgumentHeaderName) {
            $argumentHeaderName = 'x-' . $argumentHeaderName;
        }
        $header = Request::header($argumentHeaderName);
        if ($header !== null) {
            $argumentValue = $header;
        }
    }

    if (in_array('input', $excludedSources) === false) {
        // Look for argument in form input (or JSON equivalent) or query string
        // Laravel auto-checks for blank or empty values and considers them equal to null
        if (Input::has($argumentName)) {
            $input = Input::get($argumentName);
            $argumentValue = $input;
        }
    }

    return $argumentValue;
}

/**
 * Acts as a wrapper around Input::only() but checks in headers as well for getting multiple arguments at once.
 *
 * If a given argument isn't found, or its value is NULL like (e.g. ' ') a default value (per argument) can be returned,
 * if no default is given NULL is returned.
 *
 * If an argument is found in both headers and input, the input value will clobber the header value.
 *
 * You can ignore either 'headers' or 'input' by adding pass either in an excludedSources array
 *
 * Note: All values are returned as strings (i.e. you will need to recast to int/bool as needed).
 *
 * @param array $argumentNames
 * @param array $defaultArgumentValues
 * @param bool $prefixArgumentHeaderNames
 * @param array $excludedSources
 * @return array
 */
function getArguments(array $argumentNames, array $defaultArgumentValues = [], $prefixArgumentHeaderNames = true, array $excludedSources = [])
{
    $arguments = [];

    foreach ($argumentNames as $key => $argumentName)
    {
        $arguments[$argumentName] = null;

        // Use default argument value if specified
        if (array_key_exists($key, $defaultArgumentValues) && $defaultArgumentValues[$key] !== null)
        {
            $arguments[$argumentName] = $defaultArgumentValues[$key];
        }
    }

    if (in_array('headers', $excludedSources) === false)
    {
        // Look for arguments in headers
        // Laravel auto-checks for blank or empty values and considers them equal to null
        foreach ($argumentNames as $argumentName)
        {
            $argumentHeaderName = $argumentName;
            if ($prefixArgumentHeaderNames)
            {
                $argumentHeaderName = 'x-' . $argumentHeaderName;
            }

            $header = Request::header($argumentHeaderName);
            if ($header !== null)
            {
                $arguments[$argumentName] = $header;
            }
        }
    }

    if (in_array('input', $excludedSources) === false) {
        // Look for arguments in form input (or JSON equivalent) or query string
        // Laravel auto-checks for blank or empty values and considers them equal to null
        $inputs = Input::only($argumentNames);
        foreach ($inputs as $argumentName => $input) {
            if ($input !== null) {
                $arguments[$argumentName] = $input;
            }
        }
    }

    return $arguments;
}
