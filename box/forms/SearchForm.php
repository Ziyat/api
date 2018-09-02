<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms;


use yii\base\Model;

class SearchForm extends Model
{
    public $text;
    public const LATIN = 0;
    public const CYRILLIC = 1;

    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
            [['text'], 'trim'],
            [['text'], 'filter', 'filter' => function ($value) {
                return str_replace(['[', ']'], ['', ''], $value);
            }],
            [['text'], 'default', 'value' => null],
        ];
    }

    public function getTypeOfText()
    {
        $cyr = $this->getAlphabet(self::CYRILLIC);
        $lat = $this->getAlphabet(self::LATIN);
        $length = count($cyr);
        for ($i = 0; $i < strlen($this->text); $i++) {
            $letter = mb_substr($this->text, $i, 1);
            for ($j = 0; $j < $length; $j++) {
                if ($letter == $cyr[$j]) {
                    return self::CYRILLIC;
                }
            }
            for ($j = 0; $j < $length; $j++) {
                if ($letter == $lat[$j]) {
                    return self::LATIN;
                }
            }
        }
        return false;
    }

    public function getAlternateText($type)
    {
        $var = '';
        $cyr = $this->getAlphabet(self::CYRILLIC);
        $lat = $this->getAlphabet(self::LATIN);
        if ($this->isCyrillic($type)) {
            $var = str_replace($cyr, $lat, $this->text);
        } elseif ($this->isLatin($type)) {
            $var = str_replace($lat, $cyr, $this->text);
        }
        return str_replace(['[', ']'], ['', ''], $var);
    }

    public function isCyrillic($type)
    {
        return $type === self::CYRILLIC;
    }

    public function isLatin($type)
    {
        return $type === self::LATIN;
    }

    private function getAlphabet($type)
    {
        $result = null;
        if ($this->isCyrillic($type)) {
            $result = [
                'ц', 'ч', 'ш', 'щ', 'ю', 'я', 'а', 'б', 'в', 'г',
                'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м',
                'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ',
                'ы', 'ь', 'э', 'Ц', 'Ч', 'Ш', 'Щ', 'Ю', 'Я', 'А',
                'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
                'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У',
                'Ф', 'Х', 'Ъ', 'Ы', 'Ь', 'Э'
            ];
        }
        if ($this->isLatin($type)) {
            $result = [
                'w', 'x', 'i', 'o', '.', 'z', 'f', ',', 'd', 'u',
                'l', 't', '`', ';', 'p', 'b', 'q', 'r', 'k', 'v',
                'y', 'j', 'g', 'h', 'c', 'n', 'e', 'a', '[', ']',
                's', 'm', '\'', 'W', 'X', 'I', 'O', '>', 'Z', 'F',
                '<', 'D', 'U', 'L', 'T', '~', ':', 'P', 'B', 'Q',
                'R', 'K', 'V', 'Y', 'J', 'G', 'H', 'C', 'N', 'E',
                'A', '{', '}', 'S', 'M', '"'
            ];
        }
        return $result;
    }
}