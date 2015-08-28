<?php

use Monolog\Logger;

/**
 * Class: FileConverterComponent
 *
 */
class FileConverterComponent extends Component
{
    /**
     * log.
     *
     * @var mixed
     */
    private $log;

    /**
     * __construct.
     */
    public function __construct(ComponentCollection $collection, $settings = array())
    {
        parent::__construct($collection, $settings);

        // create a log channel
        $this->log = new Logger('name');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler(LOGS . DS . 'convert.log', Logger::INFO));
        $this->log->pushHandler(new \Monolog\Handler\ErrorLogHandler());
    }

    /**
     * Get mime type from file.
     *
     * @param string $file_path path to file
     *
     * @return string mime_type
     */
    public function get_mime_type($file_path)
    {
        $mime = shell_exec('file -bi ' . escapeshellcmd($file_path));
        $mime = trim($mime);
        $parts = explode(';', $mime);
        $mime = preg_replace('/ [^ ]*/', '', trim($parts[0]));

        return $mime;
    }

    /**
     * Convert PPT file to PDF.
     *
     * @param string $file_path source file to convert
     */
    public function convert_ppt_to_pdf($file_path)
    {
        $status = '';
        $command_logs = array();

        $this->log->addInfo('Start converting PowerPoint to PDF');
        exec('unoconv -f pdf -o ' . $file_path . '.pdf ' . $file_path, $command_logs, $status);
        $this->log->addInfo(var_export($command_logs, true));
        if ($status === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert PDF file to PPM.
     *
     * @param string $save_dir path to store file
     * @param string $file_path source file to convert
     *
     */
    public function convert_pdf_to_ppm($save_dir, $file_path)
    {
        $status = '';
        $command_logs = array();

        $this->log->addInfo('Start converting PDF to ppm');
        exec('cd ' . $save_dir . '&& pdftoppm ' . $file_path . '.pdf slide', $command_logs, $status);
        $this->log->addInfo(var_export($command_logs, true));
        if ($status === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert PPM file to Jpeg.
     *
     * @param string $save_dir path to store file
     */
    public function convert_ppm_to_jpg($save_dir)
    {
        $status = '';
        $command_logs = array();

        $this->log->addInfo('Start converting ppm to jpg');
        exec('cd ' . $save_dir . '&& mogrify -format jpg slide*.ppm', $command_logs, $status);
        $this->log->addInfo(var_export($command_logs, true));
        if ($status != 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * isPPT
     *
     * @param mixed $mime
     */
    public function isPDF($mime) {
        return "application/pdf" === $mime;
    }

    /**
     * isPPT
     *
     * @param mixed $mime
     */
    public function isPPT($mime) {
        return 'application/vnd.ms-powerpoint' === $mime;
    }

    /**
     * isPPTX
     *
     * @param mixed $mime
     */
    public function isPPTX($mime) {
        return 'application/vnd.openxmlformats-officedocument.presentationml.presentation' === $mime;
    }

    /**
     * isConvertable
     *
     * @param mixed $mime_type
     */
    public function isConvertable($mime_type) {
        if ($this->isPDF($mime_type))  return true;
        if ($this->isPPTX($mime_type)) return true;
        if ($this->isPPT($mime_type))  return true;
        return false;
    }
}
