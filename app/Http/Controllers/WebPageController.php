<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class WebPageController extends Controller
{
    public function index()
    {

    }
    public function showFiles(string $path = "")
    {
        $store = Storage::drive("files");
        if(is_file($store->path($path))) {
            return Storage::response("files".$path);
        }
        dd($store->path($path));
        return Inertia::render("index",
            [
                "files" => []
            ]
        );
    }
}
