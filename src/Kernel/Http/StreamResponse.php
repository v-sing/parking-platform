<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) v-sing <email1946367301@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace VSing\ParkingPlatform\Kernel\Http;

use VSing\ParkingPlatform\Kernel\Exceptions\InvalidArgumentException;
use VSing\ParkingPlatform\Kernel\Exceptions\RuntimeException;
use VSing\ParkingPlatform\Kernel\Support\File;

/**
 * Class StreamResponse.
 *
 * @author v-sing <email1946367301@163.com>
 */
class StreamResponse extends Response
{
    /**
     * @param string $directory
     * @param string $filename
     * @param bool $appendSuffix
     *
     * @return bool|int
     *
     * @throws  InvalidArgumentException
     * @throws  RuntimeException
     */
    public function save(string $directory, string $filename = '', bool $appendSuffix = true)
    {
        $this->getBody()->rewind();

        $directory = rtrim($directory, '/');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // @codeCoverageIgnore
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf("'%s' is not writable.", $directory));
        }

        $contents = $this->getBody()->getContents();

        if (empty($contents) || '{' === $contents[0]) {
            throw new RuntimeException('Invalid media response content.');
        }

        if (empty($filename)) {
            if (preg_match('/filename="(?<filename>.*?)"/', $this->getHeaderLine('Content-Disposition'), $match)) {
                $filename = $match['filename'];
            } else {
                $filename = md5($contents);
            }
        }

        if ($appendSuffix && empty(pathinfo($filename, PATHINFO_EXTENSION))) {
            $filename .= File::getStreamExt($contents);
        }

        file_put_contents($directory . '/' . $filename, $contents);

        return $filename;
    }


    /**
     * @param string $directory
     * @param string $filename
     * @param bool $appendSuffix
     * @return bool|int|string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function saveAs(string $directory, string $filename, bool $appendSuffix = true)
    {
        return $this->save($directory, $filename, $appendSuffix);
    }
}
