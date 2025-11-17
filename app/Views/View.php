<?php

namespace App\Views;

class View{
    public static function render($view, $data = [], $useLayout = true){
        $viewFile = self::resolveViewPath($view);

        if (!file_exists($viewFile)){
            self::handleMissingView($viewFile);
            return;
        }

        if ($useLayout){
            Layout::header($data['title'] ?? 'Library');
        }

        extract($data);
        include $viewFile;

        if ($useLayout){
            Layout::footer();
        }
    }

    private static function resolveViewPath($view){
        return __DIR__ . DIRECTORY_SEPARATOR . "$view.php";
    }

    private static function handleMissingView($viewFile){
        Display::message("View '$viewFile' not found.", 'error');
    }
}