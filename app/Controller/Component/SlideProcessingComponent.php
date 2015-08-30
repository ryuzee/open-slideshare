<?php

use Aws\S3\Exception\S3Exception;
use Monolog\Logger;

class SlideProcessingComponent extends Component
{
    /**
     * Slide.
     *
     * @var mixed
     */
    private $Slide;

    /**
     * log.
     *
     * @var mixed
     */
    private $log;

    /**
     * s3.
     *
     * @var mixed
     */
    private $S3;

    /**
     * FileConverter
     *
     * @var mixed
     */
    private $FileConverter;

    /**
     * __construct.
     */
    public function __construct(ComponentCollection $collection, $settings = array())
    {
        parent::__construct($collection, $settings);
        $this->Slide = ClassRegistry::init('Slide');

        // create S3 library instance
        App::uses('S3Component', 'Controller/Component');
        $this->S3 = new S3Component($collection, $settings);

        // create FileConverter library instance
        App::uses('FileConverterComponent', 'Controller/Component');
        $this->FileConverter = new FileConverterComponent($collection, $settings);

        // create a log channel
        $this->log = new Logger('name');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler(LOGS . DS . 'batch.log', Logger::INFO));
        $this->log->pushHandler(new \Monolog\Handler\ErrorLogHandler());
    }

    /**
     * Delete slide from S3.
     *
     * @param object $s3 S3 instance
     * @param string $key key to remove
     */
    public function delete_slide_from_s3($s3, $key)
    {
        $this->delete_master_slide($s3, $key);
        $this->delete_generated_files($s3, $key);
    }

    /**
     * Delete all generated files in Amazon S3.
     *
     * @param object $s3 S3 instance
     * @param string $key
     */
    public function delete_generated_files($s3, $key)
    {
        // List files and delete them.
        $res = $s3->listObjects(array('Bucket' => Configure::read('image_bucket_name'), 'MaxKeys' => 1000, 'Prefix' => $key . '/'));
        $keys = $res->getPath('Contents');
        $delete_files = array();
        if (is_array($keys)) {
            foreach ($keys as $kk) {
                $delete_files[] = array('Key' => $kk['Key']);
            }
        }
        if (count($delete_files) > 0) {
            $res = $s3->deleteObjects(array(
                'Bucket' => Configure::read('image_bucket_name'),
                'Objects' => $delete_files,
            ));
        }
    }

    /**
     * create_directory
     *
     * @param string $directory
     */
    private function create_directory($directory)
    {
        set_error_handler(function($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        if (file_exists($directory)) {
            return true;
        }

        try {
            mkdir($directory);
        } catch (Exception $e) {
            $this->log->addInfo(sprintf("Failed to create directory %s... ", $directory) . $e->getMessage());
        }
        restore_error_handler();
    }

    /**
     * Extract images from uploaded file.
     *
     * @param object $s3 S3 instance
     * @param array $data that is retrieved from SQS
     */
    public function extract_images($s3, $data)
    {
        // S3 object key
        $key = $data['key'];

        // filename to use for original one from S3
        $save_dir = TMP . basename($key);
        $this->create_directory($save_dir);
        $file_path = tempnam($save_dir, 'original');

        // retrieve original file from S3
        $this->log->addInfo('Start retrieving file from S3');
        $object = $s3->getObject(array(
            'Bucket' => Configure::read('bucket_name'),
            'Key' => $key,
            'SaveAs' => $file_path,
        ));

        $mime_type = $this->FileConverter->get_mime_type($file_path);
        $this->log->addInfo('File Type is ' . $mime_type);

        if (!$this->FileConverter->isConvertable($mime_type)) {
            $this->Slide->update_status($key, ERROR_NO_CONVERT_SOURCE);
            $this->log->addWarning('No Convertable File');
            return;
        }

        try {
            if ($this->FileConverter->isPPT($mime_type) || $this->FileConverter->isPPTX($mime_type)) {
                if ($this->FileConverter->isPPT($mime_type)) {
                    $extension = '.ppt';
                } else {
                    $extension = '.pptx';
                }
                $status = $this->FileConverter->convert_ppt_to_pdf($file_path);
                if (!$status) {
                    $this->Slide->update_status($key, ERROR_CONVERT_PPT_TO_PDF);
                    return false;
                }
            }
            if ($this->FileConverter->isPDF($mime_type)) {
                $extension = '.pdf';
                $this->log->addInfo('Renaming file...');
                rename($file_path, $file_path . '.pdf');
            }
            $this->Slide->update_extension($key, $extension);

            // Convert PDF to ppm
            $status = $this->FileConverter->convert_pdf_to_ppm($save_dir, $file_path);
            if (!$status) {
                $this->Slide->update_status($key, ERROR_CONVERT_PDF_TO_PPM);
                return false;
            }

            // Convert ppm to jpg
            $status = $this->FileConverter->convert_ppm_to_jpg($save_dir);
            if (!$status) {
                $this->Slide->update_status($key, ERROR_CONVERT_PPM_TO_JPG);
                return false;
            }

            // Extract Transcript
            if ($this->FileConverter->extract_transcript($save_dir, $file_path)) {
                $this->upload_transcript($s3, $key);
            }

            // Upload images to Amazon S3
            $files = $this->list_local_images($save_dir);
            $first_page = false;
            $this->upload_extract_images($s3, $key, $save_dir, $files, $first_page);

            // create thumbnail images
            if ($first_page) {
                $this->create_thumbnail($s3, $key, $first_page);
            }
            $this->log->addInfo('Converting file successfully completed!!');
            // update the db record
            $this->Slide->update_status($key, SUCCESS_CONVERT_COMPLETED);

        } catch (Exception $e) {
            $this->Slide->update_status($key, -99);
        }
        $this->log->addInfo('Cleaning up working directory ' . $save_dir . '...');
        $this->cleanup_working_dir($save_dir);
        $this->log->addInfo('Completed to run the process...');

        return true;
    }

    /**
     * get_slide_pages_list.
     *
     * @param mixed $slide_key
     */
    public function get_slide_pages_list($slide_key)
    {
        App::uses('CommonHelper', 'View/Helper');
        $helper = new CommonHelper(new View());
        $url = $helper->json_url($slide_key);

        $contents = $this->get_contents($url);
        $file_list = json_decode($contents);
        return $file_list;
    }

    /**
     * get_transcript
     *
     * @param mixed $slide_key
     */
    public function get_transcript($slide_key)
    {
        App::uses('CommonHelper', 'View/Helper');
        $helper = new CommonHelper(new View());
        $url = $helper->transcript_url($slide_key);

        $contents = $this->get_contents($url);
        $transcripts = unserialize($contents);

        return $transcripts;
    }

    /**
     * get_contents
     *
     * @param mixed $path
     */
    private function get_contents($path)
    {
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        set_error_handler(function($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        $result = "";
        try {
            $contents = file_get_contents($path, false, $context);
            if (strpos($http_response_header[0], '200')) {
                $result = $contents;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        restore_error_handler();
        return $result;
    }

    /**
     * Download original file from bucket.
     *
     * @param object $s3 S3 instance
     * @param string $key filename in S3
     */
    public function get_original_file_download_path($s3, $key, $extension = null)
    {
        $filename = $key . $extension;
        $opt = array('ResponseContentDisposition' => 'attachment; filename="' . $filename . '"');
        $url = $s3->getObjectUrl(Configure::read('bucket_name'), $key, '+15 minutes', $opt);

        return $url;
    }

    ################## Private ################

    /**
     * Upload all generated files to Amazon S3.
     *
     * @param object $s3 S3 instance
     * @param string $key
     * @param string $save_dir
     * @param array  $files
     */
    private function upload_extract_images($s3, $key, $save_dir, $files, &$first_page)
    {
        $file_array = array();
        $this->log->addInfo('Total number of files is ' . count($files));

        $bucket = Configure::read('image_bucket_name');
        foreach ($files as $file_path => $file_info) {
            $file_key = str_replace(TMP, '', $file_path);
            $file_array[] = $file_key;
            // store image to S3
            $this->log->addInfo("Start uploading image to S3($bucket). " . $file_key);
            try {
                $s3->putObject(array(
                    'Bucket' => $bucket,
                    'Key' => $file_key,
                    'SourceFile' => $file_path,
                    'ContentType' => 'image/jpg',
                    'ACL' => 'public-read',
                    'StorageClass' => 'REDUCED_REDUNDANCY',
                ));
            } catch (S3Exception $e) {
                $this->log->addError("The file was not uploaded.\n" . $e->getMessage());
            }
        }

        sort($file_array);
        $json_contents = json_encode($file_array, JSON_UNESCAPED_SLASHES);
        file_put_contents($save_dir . '/list.json', $json_contents);

        // store list.json to S3
        $this->log->addInfo('Start uploading list.json to S3');
        $s3->putObject(array(
            'Bucket' => Configure::read('image_bucket_name'),
            'Key' => $key . '/list.json',
            'SourceFile' => $save_dir . '/list.json',
            'ContentType' => 'text/plain',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        ));

        if (count($file_array) > 0) {
            $first_page = $file_array[0];
        } else {
            $first_page = false;
        }
    }

    /**
     * upload_transcript
     *
     * @param mixed $s3
     * @param mixed $key
     */
    private function upload_transcript($s3, $key)
    {
        $this->log->addInfo("Start uploading transcript ($key)");
        $s3->putObject(array(
            'Bucket' => Configure::read('image_bucket_name'),
            'Key' => $key . '/transcript.txt',
            'SourceFile' => TMP . $key . '/transcript.txt',
            'ContentType' => 'text/plain',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        ));
    }

    /**
     * Delete master slide from Amazon S3.
     *
     * @param object $s3 S3 instance
     * @param string $key
     */
    private function delete_master_slide($s3, $key)
    {
        $res = $s3->deleteObject(array(
            'Bucket' => Configure::read('bucket_name'),
            'Key' => $key,
        ));
    }

    /**
     * Clean up working directory.
     *
     * @param string $dir
     */
    private function cleanup_working_dir($dir)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $command = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $command($fileinfo->getRealPath());
        }
        rmdir($dir);
    }

    /**
     * Create thumbnail from specified original image file.
     *
     * @param object $s3 S3 instance
     * @param string $key
     * @param string $filename
     */
    private function create_thumbnail($s3, $key, $filename)
    {
        // Create Same Size Thumbnail
        $f = TMP . $filename;
        $src_image = imagecreatefromjpeg($f);

        // get size
        $width = ImageSx($src_image);
        $height = ImageSy($src_image);

        // Tatenaga...
        if ($height > $width * 0.75) {
            $src_y = (int) ($height - ($width * 0.75));
            $src_h = $height - $src_y;
            $src_x = 0;
            $src_w = $width;
        } else {
            // Yokonaga
            $src_y = 0;
            $src_h = $height;
            $src_x = 0;
            $src_w = $height / 0.75;
        }

        // get resized size
        $dst_w = 320;
        $dst_h = 240;

        // generate file
        $dst_image = ImageCreateTrueColor($dst_w, $dst_h);
        ImageCopyResampled($dst_image, $src_image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        imagejpeg($dst_image, TMP . $key . '/thumbnail.jpg');

        // store thumbnail to S3
        $s3->putObject(array(
            'Bucket' => Configure::read('image_bucket_name'),
            'Key' => $key . '/thumbnail.jpg',
            'SourceFile' => TMP . $key . '/thumbnail.jpg',
            'ContentType' => 'image/jpeg',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        ));
    }

    /**
     * List all files in specified directory.
     *
     * @param string $dir
     *
     * @return array
     */
    private function list_local_images($dir)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir,
                FilesystemIterator::CURRENT_AS_FILEINFO |
                FilesystemIterator::KEY_AS_PATHNAME |
                FilesystemIterator::SKIP_DOTS
            )
        );

        $files = new RegexIterator($files, '/^.+\.jpg$/i', RecursiveRegexIterator::MATCH);

        return $files;
    }
}
