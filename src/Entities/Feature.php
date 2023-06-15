<?php

namespace Novadaemon\Larafeat\Entities;

class Feature extends Entity
{
    public function __construct($title, $file, $realPath, $relativePath, $content = '')
    {
        $className = str_replace(' ', '', $title).'Feature';

        $this->setAttributes([
            'title' => $title,
            'className' => $className,
            'file' => $file,
            'realPath' => $realPath,
            'relativePath' => $relativePath,
            'content' => $content,
        ]);
    }
}
