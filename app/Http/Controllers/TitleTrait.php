<?php

namespace App\Http\Controllers;


trait TitleTrait
{
    public function getTitleRoute($routeName): string
    {
        $arrRouteName = explode('.', $routeName);
        $result = "";
        if (count($arrRouteName) == 2 || end($arrRouteName) == 'index') {
            $result = ucfirst($arrRouteName[1]);
        } else {
            // remove first element
            for ($i = 1; $i < count($arrRouteName) - 1; $i++) {
                $result .= ucfirst($arrRouteName[$i]) . ' - ';
            }
            $result .= ucfirst(end($arrRouteName));
        }

        return $result;
    }
}
