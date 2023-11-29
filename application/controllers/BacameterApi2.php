<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
// require './vendor/autoload.php';

// use Aws\S3\S3Client;
// use namespace
use Restserver\Libraries\REST_Controller;

class BacameterApi2 extends REST_Controller
{

    function __construct()
    {
        //add api 2
        parent::__construct();
        $this->load->model('ApiModel2', 'api');
    }


    // public function uploadS3_post()
    // {

    //     $region = 'id-mtr-666';
    //     $version = 'latest';
    //     $acces_key_id = 'zanul';
    //     $secret_access_id = 'zanulAMGMid';
    //     $bucket = 'amgm-bin';
    //     $response = array();
    //     if (isset($_FILES["file"])) {
    //         $getName = $_FILES["file"]["name"];
    //         $ext = pathinfo($getName, PATHINFO_EXTENSION);
    //         $file_tmp_src = $_FILES["file"]["tmp_name"];
    //         if (is_uploaded_file($file_tmp_src)) {
    //             $s3 = new S3Client([
    //                 'region' => $region,
    //                 'version' => $version,
    //                 "endpoint" => "http://10.10.222.226:8814",
    //                 "use_path_style_endpoint" => true,
    //                 'credentials' => [
    //                     'key' => $acces_key_id,
    //                     'secret' => $secret_access_id
    //                 ]
    //             ]);
    //             try {
    //                 $result =  $s3->putObject([
    //                     'Bucket' => $bucket,
    //                     'Key' => 'bacameter/tes_aws/' . $this->post('nama') . '.' . $ext,
    //                     'SourceFile' => $file_tmp_src
    //                 ]);
    //                 $result_arr = $result->toArray();
    //                 if (!empty($result_arr['ObjectURL'])) {
    //                     $s3_file_link = $result_arr['ObjectURL'];
    //                 } else {
    //                     $api_error = 'Upload Failed! S3 link not found!';
    //                 }
    //             } catch (Aws\S3\Exception\S3Exception $e) {
    //                 $api_error = $e->getMessage();
    //             }
    //             if (empty($api_error)) {
    //                 $success = true;
    //                 $message = "Successfully Uploaded with " . $s3_file_link;
    //             } else {
    //                 $success = false;
    //                 $message = $api_error;
    //             }
    //         } else {
    //             $success = false;
    //             $message = "Error while uploading";
    //         }
    //     }
    //     $response["success"] = $success;
    //     $response["message"] = $message;
    //     // echo json_encode($response);
    //     return $this->response($response);
    // }
    public function index_get()
    {
        return $this->response(array('status' => true, 'message' => 'Api Bacameter versi 2.1.0 (connected with Aplikasi Pengaduan, select from vw_pelanggan)', 'cBlth' => date('m/Y'), 'host' => $this->db->hostname, 'db' => $this->db->database, "php" => phpversion()));
    }

    public function bacameter_get($cIdPembaca)
    {
        $data = $this->api->getDataBacaan($cIdPembaca);
        $this->response($data);
    }

    public function bacameterWithRekening_get($cIdPembaca)
    {
        $data = $this->api->getDataBacaanWithRekening($cIdPembaca);
        $this->response($data);
    }


    public function bacameterWithRekeningV2_get($cIdPembaca)
    {
        //ambil semua data bacaan yg sudah n belum terbaca
        $data = $this->api->getDataBacaanWithRekeningV2($cIdPembaca);
        $this->response($data);
    }


    public function getDatabyUser_post()
    {
        $data = $this->api->getData($this->post('bt'), $this->post('user'));
        $this->response($data);
    }

    function status_get()
    {
        $data = $this->api->getStatus();
        $this->response($data);
    }


    function jumbacaan_get($id)
    {
        $data = $this->api->getJumBacaan($id);
        $this->response($data);
    }

    function jumbelumbaca_get($id)
    {
        $data = $this->api->getJumBelumBacaan($id);
        $this->response($data);
    }

    function tarif_get()
    {
        $data = $this->api->getTarif();
        $this->response($data);
    }

    function tarif2_get()
    {
        $data = $this->api->getTarif2();
        $this->response($data);
    }

    function denda_get()
    {
        $data = $this->api->getTarifDenda();
        $this->response($data);
    }

    public function auth_get($id, $pass)
    {
        $data = $this->api->doLogin($id, $pass);
        $this->response($data);
    }

    public function progresbaca_get($id)
    {
        $bacaan = (int)$this->api->getJumBacaan($id);
        $belum_terbaca = (int)$this->api->getJumBelumBacaan($id);
        $this->response(array('bacaan' => $bacaan, 'belum_terbaca' => $belum_terbaca, 'sudah_terbaca' => $bacaan - $belum_terbaca));
    }

    public function version_get()
    {
        //tipe 0 = update langsung, 1 = unistall apk lama
        $this->response(array('version' => 'v2', 'tipe' => 1));
    }



    public function upload_post()
    {
        $data = json_decode($this->post('data'), true);
        $id = $this->post('id');
        $flag = $this->api->updateData($data, $id);
        if ($flag) {
            $this->response(array('status' => true, 'message' => 'data berhasil di upload guys',));
        } else {
            $this->response(array('status' => false, 'message' => 'data gagal di upload'));
        }
    }

    public function sinkron_post()
    {
        $data = json_decode($this->post('data'), true);
        $id = $this->post('id');
        $flag = $this->api->sinkronData($data, $id);
        if ($flag) {
            $this->response(array('status' => true, 'message' => 'data berhasil di sinkron guys',));
        } else {
            $this->response(array('status' => false, 'message' => 'data gagal di upload'));
        }
    }


    public function uploadSurvey_post()
    {
        $data = json_decode($this->post('data'), true);
        $id = $this->post('id');
        $flag = $this->api->updateDataSurvey($data, $id);
        if ($flag) {
            $this->response(array('status' => true, 'message' => 'data berhasil di upload guys',));
        } else {
            $this->response(array('status' => false, 'message' => 'data gagal di upload'));
        }
    }

    public function sinkronSurvey_post()
    {
        $data = json_decode($this->post('data'), true);
        $id = $this->post('id');
        $flag = $this->api->sinkronDataSurvey($data, $id);
        if ($flag) {
            $this->response(array('status' => true, 'message' => 'data berhasil di sinkron guys',));
        } else {
            $this->response(array('status' => false, 'message' => 'data gagal di upload'));
        }
    }

    public function uploadV3_post()
    {
        $data = json_decode($this->post('data'), true);
        $id = $this->post('id');
        $flag = $this->api->updateDataV3($data, $id);
        if ($flag) {
            $this->response(array('status' => true, 'message' => 'data berhasil di upload guys',));
        } else {
            $this->response(array('status' => false, 'message' => 'data gagal di upload'));
        }
    }

    public function sinkronV3_post()
    {
        $data = json_decode($this->post('data'), true);
        $id = $this->post('id');
        $flag = $this->api->sinkronDataV3($data, $id);
        if ($flag) {
            $this->response(array('status' => true, 'message' => 'data berhasil di sinkron guys',));
        } else {
            $this->response(array('status' => false, 'message' => 'data gagal di upload'));
        }
    }



    public function uploadImage_post()
    {
        $nama = $this->post('nama');
        $cIdPembaca = $this->post('cIdPembaca');
        $cBlth = date('m_Y/');
        $target_dir = "././images/";
        if (is_dir($target_dir . $cBlth)) {
            //ada folder cBlth
            if (!is_dir($target_dir . $cBlth . '/' . $cIdPembaca)) {
                $oldmask = umask(0);
                mkdir($target_dir . $cBlth . '/' . $cIdPembaca, 0777, true);
                umask($oldmask);
            }
        } else {
            $oldmask = umask(0);
            mkdir($target_dir . $cBlth . '/' . $cIdPembaca, 0777, true);
            umask($oldmask);
        }
        $getName = $_FILES["file"]["name"];
        $ext = pathinfo($getName, PATHINFO_EXTENSION);
        $target_file_name = $target_dir . $cBlth . '/' . $cIdPembaca . '/' . $nama . '.' . $ext;
        $response = array();
        // Check if image file is a actual image or fake image
        if (isset($_FILES["file"])) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_name)) {
                $success = true;
                $message = "Successfully Uploaded";
            } else {
                $success = false;
                $message = "Error while uploading";
            }
        } else {
            $success = false;
            $message = "Required Field Missing";
        }
        $response["success"] = $success;
        $response["message"] = $message;
        // echo json_encode($response);
        return $this->response($response);
    }

    public function uploadFoto_post()
    {
        $target_dir = "././images/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["foto"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["foto"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["foto"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
