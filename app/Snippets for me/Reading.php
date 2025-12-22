<?php
class Config
{
    private static $data = [];  // هنا هنخزن كل الـ arrays بعد قراءتها

    public static function get(string $key, $default = null)
    {
        // dot notation: app.name → ['app']['name']
        $keys = explode('.', $key);
        $array = self::$data;

        foreach ($keys as $segment) {
            if (isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function load(string $filePath)
    {
        $name = basename($filePath, '.php');  // اسم الملف بدون امتداد

        if (!isset(self::$data[$name])) {
            if (file_exists($filePath)) {
                self::$data[$name] = require $filePath;  // هنا بس بنعمل require مرة واحدة
            } else {
                self::$data[$name] = [];
            }
        }

        return self::$data[$name];
    }
}

// مثال للاستخدام

/* 
ملف: config/app.php
<?php

return [
    'database' => [
        'host' => 'localhost',
        'name' => 'mydb',
    ],
    'app_name' => 'My App',
];
*/


// في أي ملف تاني (بدون include مباشر)

// أولًا: حمل الملفات اللي عايزها
Config::load('config/app.php');
Config::load('config/database.php');

// بعد كده: اقرأ البيانات بسهولة
$appName = Config::get('app.name', 'Default App');
$dbHost  = Config::get('database.host');

// لو عايز ملف كامل
$appConfig = Config::get('app');
