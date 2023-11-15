<?php
namespace Worksection\SDK\Exception;

use Exception, Throwable;

class SdkException extends Exception
{
	/**
	 * @param string $errorMsg
	 * @param Throwable|null $previous
	 */
	public function __construct(string $errorMsg, Throwable $previous = null)
	{
		parent::__construct($errorMsg, 0, $previous);
	}
}