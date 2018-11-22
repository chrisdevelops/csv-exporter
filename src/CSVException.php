<?php
/**
 * CSVExporter Exception Class.
 *
 * @author  Chris Rowles <cmrowles@pm.me>
 * @license GNU Lesser General Public License v3
 */
namespace Crowles\CSVExporter;

use Exception;

class CSVException extends Exception
{
    /**
     * CSVException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}