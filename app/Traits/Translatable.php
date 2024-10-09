<?php

namespace App\Traits;

use App\Models\User;
use Stichoza\GoogleTranslate\GoogleTranslate;

trait Translatable
{
    public static function translateAttributes(array &$input, array $attributes)
    {
        $languages = User::LANGUAGES;
        $langKeys = array_keys($languages);

        foreach ($langKeys as $langKey) {
            foreach ($attributes as $attribute) {
                if (app()->getLocale() == $langKey) {
                    $input[$attribute . '_' . $langKey] = $input[$attribute];
                } else {
                    $input[$attribute . '_' . $langKey] = GoogleTranslate::trans($input[$attribute], $langKey);
                }
            }
        }

        return $input;
    }

}

//                $input['name_'.$langs_key] = $input['name'];
//                $input['designation_'.$langs_key] = $input['designation'];
//                $input['short_description_'.$langs_key] = $input['short_description'];
//            }
//            else {
//                $input['name_'.$langs_key] = GoogleTranslate::trans($input['name'],$langs_key);
//                $input['designation_'.$langs_key]  =GoogleTranslate::trans($input['designation'],$langs_key); ;
//                $input['short_description_'.$langs_key] =GoogleTranslate::trans($input['short_description'],$langs_key);;
//
//            }
//        }
//    }
//
//}
