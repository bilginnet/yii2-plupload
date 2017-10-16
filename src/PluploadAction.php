<?php

namespace bilginnet\plupload;


use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;
use yii\web\Response;

class PluploadAction extends Action
{
    public $targetDir = '@webroot/uploads';
    public $cleanupTargetDir = true; // Remove old files
    public $maxFileAge = 18000; // 5 * 3600 // Temp file age in seconds

    public $uploadComplete;

    public function init()
    {
        parent::init();

        Yii::$app->response->format = Response::FORMAT_JSON;

        $this->targetDir = Yii::getAlias($this->targetDir);
        if (!is_dir($this->targetDir)) {
            FileHelper::createDirectory($this->targetDir, 0775, true);
        }
    }

    public function run()
    {
        @set_time_limit(5 * 60);
        $params = Yii::$app->request->getBodyParams();

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }


        $targetDir = $this->targetDir;
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $isComplete = $this->chunk($filePath, $targetDir);

        if ($isComplete) {
            if ($this->uploadComplete) {
                return call_user_func($this->uploadComplete, $filePath, $params);
            } else {
                return [
                    'filePath' => $filePath,
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    protected function chunk($filePath, $targetDir)
    {
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


        // Remove old temp files
        if ($this->cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $this->maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
            return true;
        }

        // Return Success JSON-RPC response
        // die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
        return false;
    }
}
