<?php

namespace vova07\imperavi\helpers;

use vova07\imperavi\actions\GetAction;
use yii\base\InvalidParamException;
use yii\helpers\BaseFileHelper;
use yii\helpers\StringHelper;

/**
 * File system helper
 */
class FileHelper extends BaseFileHelper
{
    /**
     * @inheritdoc
     */
    public static function findFiles($dir, $options = [], $type = GetAction::TYPE_IMAGES)
    {
        if (!is_dir($dir)) {
            throw new InvalidParamException('The dir argument must be a directory.');
        }
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        if (isset($options['url'])) {
            $options['url'] = rtrim($options['url'], '/');
        }
        if (!isset($options['basePath'])) {
            $options['basePath'] = realpath($dir);
            // this should also be done only once
            if (isset($options['except'])) {
                foreach ($options['except'] as $key => $value) {
                    if (is_string($value)) {
                        $options['except'][$key] = self::parseExcludePattern($value);
                    }
                }
            }
            if (isset($options['only'])) {
                foreach ($options['only'] as $key => $value) {
                    if (is_string($value)) {
                        $options['only'][$key] = self::parseExcludePattern($value);
                    }
                }
            }
        }
        $list = [];
        $handle = opendir($dir);
        if ($handle === false) {
            throw new InvalidParamException('Unable to open directory: ' . $dir);
        }
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (static::filterPath($path, $options)) {
                if (is_file($path)) {
                    if (isset($options['url'])) {
                        if ($type === GetAction::TYPE_IMAGES) {
                            $url = str_replace([$options['basePath'], '\\'], [$options['url'], '/'], $path);
                            $list[] = [
                                'thumb' => $url,
                                'image' => $url,
                                'title' => $file,
                            ];
                        } elseif ($type === GetAction::TYPE_FILES) {
                            $link = str_replace([$options['basePath'], '\\'], [$options['url'], '/'], $path);
                            $size = filesize($path);
                            $list[] = [
                                'title' => $file,
                                'name' => $file,
                                'link' => $link,
                                'size' => $size,
                            ];
                        } else {
                            $list[] = $path;
                        }
                    } else {
                        $list[] = $path;
                    }
                } elseif (!isset($options['recursive']) || $options['recursive']) {
                    $list = array_merge($list, static::findFiles($path, $options));
                }
            }
        }
        closedir($handle);

        return $list;
    }

    /**
     * @inheritdoc
     */
    private static function parseExcludePattern($pattern)
    {
        if (!is_string($pattern)) {
            throw new InvalidParamException('Exclude/include pattern must be a string.');
        }
        $result = [
            'pattern' => $pattern,
            'flags' => 0,
            'firstWildcard' => false,
        ];
        if (!isset($pattern[0])) {
            return $result;
        }

        if ($pattern[0] == '!') {
            $result['flags'] |= self::PATTERN_NEGATIVE;
            $pattern = StringHelper::byteSubstr($pattern, 1, StringHelper::byteLength($pattern));
        }
        $len = StringHelper::byteLength($pattern);
        if ($len && StringHelper::byteSubstr($pattern, -1, 1) == '/') {
            $pattern = StringHelper::byteSubstr($pattern, 0, -1);
            $len--;
            $result['flags'] |= self::PATTERN_MUSTBEDIR;
        }
        if (strpos($pattern, '/') === false) {
            $result['flags'] |= self::PATTERN_NODIR;
        }
        $result['firstWildcard'] = self::firstWildcardInPattern($pattern);
        if ($pattern[0] == '*' && self::firstWildcardInPattern(StringHelper::byteSubstr($pattern, 1, StringHelper::byteLength($pattern))) === false) {
            $result['flags'] |= self::PATTERN_ENDSWITH;
        }
        $result['pattern'] = $pattern;

        return $result;
    }

    /**
     * @inheritdoc
     */
    private static function firstWildcardInPattern($pattern)
    {
        $wildcards = ['*', '?', '[', '\\'];
        $wildcardSearch = function ($r, $c) use ($pattern) {
            $p = strpos($pattern, $c);

            return $r===false ? $p : ($p===false ? $r : min($r, $p));
        };

        return array_reduce($wildcards, $wildcardSearch, false);
    }
}
