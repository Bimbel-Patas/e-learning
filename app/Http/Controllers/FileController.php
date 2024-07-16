<?php

namespace App\Http\Controllers;

class FileController extends Controller
{
    public function getFile($namaFile)
    {
        $file = storage_path('app/public/file/materi/' . $namaFile);

        return response()->download($file, $namaFile);
    }

    public function getFileUser($namaFile)
    {
        // $file = url('/file/tugas/user/' . $namaFile);
        $file = storage_path('app/public/file/tugas/user/' . $namaFile);

        return response()->download($file, $namaFile);
    }

    public function getFileTugas($namaFile)
    {
        // $file = url('/file/tugas/' . $namaFile);
        $file = storage_path('app/public/file/tugas/' . $namaFile);

        return response()->download($file, $namaFile);
    }
}
