<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    public function cache()
    {

        $cachePath = storage_path('framework/cache/data');
        $files = File::allFiles($cachePath);

        $allCache = [];

        foreach ($files as $file) {
            $contents = file_get_contents($file->getRealPath());

            // Skip لو الملف فاضي أو مش كاش
            if (empty($contents)) continue;

            // أول 10 bytes هي expiration timestamp (i: للـ integer في serialize protocol)
            $expiration = unpack('J', substr($contents, 0, 10)) ? unpack('J', substr($contents, 0, 10))[1] : null;

            // الباقي هو serialized value
            $value = unserialize(substr($contents, 10));

            if ($value === false && $contents !== 'b:0;') {
                // لو unserialize فشل، skip
                continue;
            }

            // Check لو expired
            if ($expiration === null || $expiration > time()) {
                // نعكس الـ key من path الملف
                $relativePath = str_replace($cachePath . '/', '', $file->getPath());
                $filename = $file->getFilename();
                $key = $relativePath . '/' . $filename; // الـ path كامل hashed

                // أو لو عايز تحاول تعكس الـ original key (صعب 100% لأنه sha1)
                // بس غالباً الـ key الحقيقي مش محتاج، الـ hashed كافي للتعرف

                $allCache[$key] = $value;
            }
        }

        dd($allCache);
    }
}
