<?php
/**
 * A simple CSV Exporter Class.
 *
 * @author  Chris Rowles <cmrowles@pm.me>
 * @license GNU Lesser General Public License v3
 */
namespace Crowles\CSVExporter;

use ZipArchive;

class CSVExporter
{
    /**
     * @var string $filepath the path to the file
     */
    protected $filepath;

    /**
     * @var string $filename the name of the file
     */
    protected $filename;

    /**
     * @var array $headers the column headers
     */
    protected $headers = array();

    /**
     * @var array $data the date to export
     */
    protected $data = array();

    /**
     * @var bool $exceptions enable or disable exceptions
     */
    protected $exceptions = false;

    /**
     * CSVExport constructor
     *
     * @param string $filepath
     * @param string $filename
     * @param null $exceptions
     */
    public function __construct($filepath, $filename, $exceptions = null)
    {
        if (!is_null($exceptions)) {
            $this->exceptions = (bool) $exceptions;
        }

        $this->filepath = $filepath;
        $this->filename = $filename;
        if (!strpos($this->filename, ".csv")) {
            $this->filename .= ".csv";
        }
    }

    /**
     * Sets CSV headers
     *
     * @param  array $headers
     * @return bool|$this
     * @throws CSVException
     */
    public function setHeaders($headers = array())
    {
        if(!$this->headers = $headers) {
            if($this->exceptions) {
                throw new CSVException('Could not set headers');
            }

            return false;
        }

        return $this;
    }

    /**
     * Sets CSV data
     *
     * @param array $data
     * @return $this|bool
     * @throws CSVException
     */
    public function setData($data = array())
    {
        if(!$this->data = $data) {
            if($this->exceptions) {
                throw new CSVException("Could not set data");
            }

            return false;
        }

        return $this;
    }

    /**
     * Generates CSV file
     *
     * @return bool|object
     * @throws CSVException
     */
    public function generate()
    {

        $handle = fopen($this->filepath . $this->filename, 'w');

        if(!$handle) {
            if($this->exceptions) {
                throw new CSVException(sprintf('Open file failed: %s', $this->filename));
            }

            return false;
        }

        if (!empty($this->headers)) {
            fputcsv($handle, $this->headers);
        }

        foreach ($this->data as $row) {
            fputcsv($handle, $row);
        }

        if(!fclose($handle)) {
            if ($this->exceptions) {
                throw new CSVException(sprintf('Could not generate: %s', $this->filename));
            }

            return false;
        }

        return $this;
    }

    /**
     * Compresses and encrypts file
     *
     * @param  string $password
     * @return bool|$this
     * @throws CSVException
     */
    public function zip($filename, $password)
    {
        $zip = new \ZipArchive();

        if (!strpos($filename, ".zip")) {
            $filename .= ".zip";
        }

        $zipfile = $this->filepath . $filename;
        $status = $zip->open($zipfile, \ZipArchive::CREATE);

        if (!$status) {
            if($this->exceptions) {
                throw new CSVException(sprintf('Failed to create zip archive. (Status code: %s)', $status));
            }

            return false;
        }

        if (!$zip->setPassword($password)) {
            if($this->exceptions) {
                throw new CSVException('set password failed');
            }

            return false;
        }

        $base = basename($this->filepath . $this->filename);
        if (!$zip->addFile($this->filepath . $this->filename, $base)) {
            if($this->exceptions) {
                throw new CSVException(sprintf('Add file failed: %s', $this->filename));
            }

            return false;
        }

        if (!$zip->setEncryptionName($base, ZipArchive::EM_AES_256)) {
            if($this->exceptions) {
                throw new CSVException(sprintf('Set encryption failed: %s', $base));
            }

            return false;
        }

        $zip->close();

        return $this;
    }
}