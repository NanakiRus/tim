<?php

namespace app;

/**
 * @param string $path <p>
 * Путь до папки с изображениями.
 * </p>
 * @param string $thumbPath <p>
 * Пусть до папки для превью.
 * </p>
 * @param string $thumbName <p>
 * Префикс для вашего изображения.<br />
 * Пример:
 * $thumbName = 'thumb-';<br />
 * Тогда имя файла будет 'thumb-file'
 * </p>
 */
class ImageThumb
{

    protected $data = [];
    protected $images = [];
    protected $path;
    protected $thumbPath;
    protected $thumbName;
    protected $types = [
        'image/gif',
        'image/jpeg',
        'image/png',
    ];

    public function __construct($path, $thumbPath, $thumbName = null)
    {
        $this->path = $path;

        $this->data = array_diff(scandir($this->path), ['.', '..',]);

        $this->thumbPath = $thumbPath;

        $this->thumbName = $thumbName;
    }

    /**
     * @param int $width <p>
     * Ширина, по умолчанию 400.
     * </p>
     * @param int $height <p>
     * Высота, по умолчанию 400.
     * </p>
     * @return object
     */
    public function createFiles($width = 400, $height = 400)
    {
        foreach ($this->data as $key => $value) {
            if (is_dir($this->path . '/' . $value)) {
                continue;
            }

            $this->images[$key] = new \Imagick($this->path . '/' . $value);

            foreach ($this->types as $type) {
                if ($type != $this->images[$key]->getImageMimeType()) {
                    continue;
                }

                $this->images[$key]->cropThumbnailImage($width, $height);

                $value = str_replace(' ', '_', $value);

                $imageName = $this->thumbPath . '/' . $this->thumbName . $value;
                $imageName = str_replace('\\', '/', $imageName);

                if (!file_exists($imageName)) {
                    file_put_contents($imageName, $this->images[$key]);
                }
            }

        }

        return $this;
    }

    /**
     * @return array
     */
    public function getThumb()
    {
        $thumbImages = array_diff(scandir($this->thumbPath), ['.', '..',]);
        return $thumbImages;
    }
}