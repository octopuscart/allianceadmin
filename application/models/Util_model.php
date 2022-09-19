<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Util_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function createZipFolder($zipfoldername, $zipfilenames) {
        $zip = new ZipArchive();
        $filename = APPPATH . "../assets/zipfiles/$zipfoldername";

        if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
            exit("cannot open <$filename>\n");
        }
        print_r($zipfilenames);
        foreach ($zipfilenames as $key => $zfile) {
            $zip->addFile(APPPATH . "../assets/qrcodes/$zfile.png", "$zfile.png");
        }
        echo "numfiles: " . $zip->numFiles . "\n";
        echo "status:" . $zip->status . "\n";
        $zip->close();
    }

    function createQrImages($stock_id) {
        $this->load->library('phpqr');
        $filename = "assets/qrcodes/$stock_id.png";
        $filetemplate = "assets/img/template.png";
        $this->phpqr->createcode($stock_id, $filename);
        $font_path1 = APPPATH . "../assets/fonts/ABeeZee-Regular.otf";
        $dest = imagecreatefrompng($filetemplate);
        $src = imagecreatefrompng($filename);
        $white = imagecolorallocate($dest, 0, 0, 0);
// Copy and merge

        imagecopymerge($dest, $src, 400, 273, 0, 0, 500, 500, 75);
        imagettftext($dest, 25, 0, 450, 770, $white, $font_path1, "$stock_id");

// Output and free from memory
        header('Content-Type: image/png');
        imagepng($dest, $filename);
        imagedestroy($dest);
        imagedestroy($src);
    }

}
