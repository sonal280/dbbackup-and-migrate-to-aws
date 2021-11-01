<?php

namespace Drupal\Dbexport;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Ifsnop\Mysqldump as IMysqldump;

/**
 * Define SosManager class of Bi_sos custom module
 */
class DbexportManager implements DbexportManagerInterface{

    /**
     * Database Service Object.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;

    /**
     * Constructor.
     * @param \Drupal\Core\Database\Connection
     */
    public function __construct(Connection $connection){
       $this->Connection = $connection;
    }


    public function variable_get($fist_param, $sec_param = "")
    {
        $r = \Drupal::state()->get($fist_param);
        return ($r == null) ? $sec_param : $r;
    }

    public function DbExport()
    {
        $directory = \Drupal::service('file_system')->realpath("public://fileExport/");

        $dump = new IMysqldump\Mysqldump('mysql:host=localhost;dbname=newdrupal', 'root', '');
        
        $filename = time() . rand(0, 999) . '.sql';
        $uploaded_file = $this->variable_get("filename","");
        $dump->start($directory . '/' . $filename);

        \Drupal::logger('Dbexport')->info('Database Dump Started');

        $s3 = new \Aws\S3\S3Client([
            'region' => $this->variable_get("aws_region", ""),
            'version' => $this->variable_get("aws_version", ""),
            'credentials' => [
                'key' => $this->variable_get("aws_key", ""),
                'secret' => $this->variable_get("aws_secret", ""),
            ],
        ]);

        $aws_bucket_folder = $this->variable_get("aws_bucket_folder", "");
        // dd($uploaded_file);
        if ($uploaded_file) {
            $file_name = $aws_bucket_folder.'/'.$uploaded_file;
            $directory = \Drupal::service('file_system')->realpath("public://my_files/");
            $temp_file_location = $directory . '/' . $uploaded_file;
        }else{
            $file_name = $aws_bucket_folder.'/'.$filename;
            $temp_file_location = $directory . '/' . $filename;
        }
        // dd($temp_file_location);
        $result = $s3->putObject([
            'Bucket' => $this->variable_get("aws_bucket", ""),
            'Key' => $file_name,
            'SourceFile' => $temp_file_location,
        ]);
        if ($result) {
            \Drupal::logger('Dbexport')->info('Uploaded database in aws');
        }else{
            \Drupal::logger('Dbexport')->error('Something went wrong');
        }
        
        var_dump($result);

        die;

    }
}
