<?php

namespace GabrielAndy\Coindesk\Exceptions;

use Exception;

class ErrorsException extends Exception
{
	public static function connectionError(string $url)
	{
        return new static("Could not connect to `{$url}`");
	}

    public static function serviceError(string $message)
    {
        return new static("Conversion failed because `{$message}`");
    }

    public static function customError(string $message)
    {
    	return new static($message);
    }
}
