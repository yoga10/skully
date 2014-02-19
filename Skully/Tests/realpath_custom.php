<?php
/**
 * Created by Trio Design Team (jay@tgitriodesign.com).
 * Date: 1/6/14
 * Time: 5:42 PM
 */

/**
 * @param $path
 * @return mixed
 * Replacement of realpath method to work with vfsStream
 */
use \org\bovigo\vfs\vfsStream;
function realpath_custom($path) {
//    echo "realpath_custom $path";
    $originalPath = realpath_original($path);
    $basePath = realpath_original(__DIR__.'/../../../../../');
    $newPath = str_replace($basePath, vfsStream::url('root'), $originalPath);
    return $newPath;
}

function setRealpath()
{
//    echo "\nis realpath original empty?";
    if (!function_exists('realpath_original')) {
//        echo "\nyes so we rename. Is custom empty?";
        runkit_function_rename('realpath', 'realpath_original');
        if (function_exists('realpath_custom')) {
//            echo "\nyes so we rename custom to realpath";
            runkit_function_rename('realpath_custom', 'realpath');
        }
    }
}

function unsetRealpath()
{
//    echo "\nunsetrealpath, is custom empty?";
//    echo Skully\Logging\Logger::debugBacktrace(true);
    if (!function_exists('realpath_custom')) {
//        echo "\nyes so we move realpath to realpath custom";
        runkit_function_rename('realpath', 'realpath_custom');
    }
    if (!function_exists('realpath')) {
        runkit_function_rename('realpath_original', 'realpath');
    }
}