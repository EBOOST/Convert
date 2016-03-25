<?php

namespace App\Http\Controllers;

use Unoconv\Unoconv;
use Illuminate\Http\Request;

class ConvertersController extends Controller
{
    public function convert($format, Unoconv $unoconv, Request $request)
    {
        $file = $request->file('file');
        $pdf = stream_get_meta_data(tmpfile())['uri'];
        $pageRange = '1-1';

        if (!$file) {
            return response('You have to send a file to convert.', 422);
        }

        if ($file->getMimeType() != 'application/pdf') {
            if ($format = 'pdf') {
                $pageRange = null;
            }

            $unoconv->transcode((string)$file, Unoconv::FORMAT_PDF, $pdf, $pageRange);
        } else {
            copy($file, $pdf);
        }

        if ($format == 'pdf') {
            return response(file_get_contents($pdf), 200 , [
                'Content-Type' => 'application/pdf'
            ]);
        }

        if (!in_array(strtoupper($format), \Imagick::queryFormats())) {
            throw new \Exception('Format not supported');
        }

        $imagine = new \Imagick();
        $imagine->setResolution(300, 300);
        $imagine->readImage($pdf . '[0]');
        $imagine->setImageResolution(300, 300);
        $imagine->setCompressionQuality(100);

        $imagine->setFormat($format);

        return response($imagine, 200, [
            'Content-Type' => $imagine->getImageMimeType()
        ]);
    }

}
