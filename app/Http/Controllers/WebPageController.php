<?php

namespace App\Http\Controllers;

use App\Data\FileData;
use App\Models\Files;
use http\Env\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
class WebPageController extends Controller
{
    public function index()
    {

    }

    function int2perm($perm)
    {
        $permString = "";
        foreach (str_split($perm) as $digit) {
            // Преобразуем цифру в двоичный формат (3 бита)
            $binary = str_pad(decbin((int)$digit), 3, "0", STR_PAD_LEFT);

            // Таблица прав доступа
            $mapping = ["r", "w", "x"];

            // Построение строки разрешений для одного уровня
            foreach (str_split($binary) as $index => $bit) {
                $permString .= $bit == "1" ? $mapping[$index] : "-";
            }
        }
        return $permString;
    }
    public function showFiles(string $path = "")
    {
        $store = Storage::drive("files");
        if(is_file($store->path($path))) {
            return Storage::response("files".$path);
        }
        $files = $store->files($path);
        $dir = $store->directories($path);
        $MassiveData = array_merge($dir,$files);
        $MassiveDataV2 = new Collection();
        $UserFiles = Files::whereIn("path", $MassiveData);
        foreach ($MassiveData as $data) {
            $stripos = stripos($data,"/");
            $name = $stripos ? substr($data,$stripos+1) : $data;
            $pathFile = $store->path($data);
            $perm = substr(sprintf('%o', fileperms($pathFile)), -3);

            $permS = $this->int2perm($perm);
            $MassiveDataV2->put($data,FileData::from([
                "name" => $name,
                "mimetype" => mime_content_type($pathFile),
                "size" => filesize($pathFile),
                "perm" => $perm,
                "permS" => $permS,
                "isFolder" => is_dir($pathFile),
                "created" => filectime($pathFile),
                "updated" => filemtime($pathFile)
            ]));
        }
        return Inertia::render("index",
            [
                "path" => $path,
                "files" => $MassiveDataV2->toArray()
            ]
        );
    }
    public function downloadFiles(string $path = "")
    {
        $store = Storage::drive("files");
        if (is_file($store->path($path))) {
            return response()->download($store->path($path));
        }
        if ($path != "/") {

        }
        $stripos = stripos($path,"/");
        $name = str($stripos) != "false" ? substr($path,$stripos+1) : $path;
        $name = empty($name) ? "root" : $name;
        $tempFile = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new \ZipArchive();
        if ($zip->open($tempFile, \ZipArchive::CREATE) === TRUE) {
            foreach ($store->allFiles($path) as $file) {
                $zip->addFile($store->path($file), $file);
            }
            $zip->close();
            return response()->download($tempFile, $name.'.zip')->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Не удалось создать архив'], 500);
        }
    }
}
