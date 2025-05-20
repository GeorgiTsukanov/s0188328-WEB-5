<?php
    function validateInput($data) {
        $data = trim($data); //убирает лишние пробелы в конце и в начале
        $data = stripslashes($data); //убирает экранирующие слэши
        $data = htmlspecialchars($data); //преобразует специальные символы HTML в их HTML-сущности
        return $data;
    }
    
    function validateLanguages(array $languages): bool
    {
        if (!is_array($languages)){
            return false;
        }
        if (empty($languages)) {
            return false;
        }
        foreach ($languages as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }
        return true;
    }

    function countWord($string){
        $words = explode(" ", $string);
        $countWords = count(array_filter($words, function($words) {
            return $words !== ''; // игнорируем пустые элементы (если несколько пробелов подряд)
        }));
        return $countWords;
    }
    
    function isValidatePost($fio, $email, $phone, $birthday, $gender, $languages) {  
        $error = array();

        $wordCount = countWord($fio);
        if (!preg_match('/^[а-яёА-ЯЁ\s\-]+$/u', $fio)) {
            $error["fio"] = "ФИО может содержать только русские буквы, пробелы и дефисы";
        } elseif ($wordCount < 2 || $wordCount > 3) {
            $error["fio"] = "ФИО должно содержать 2 или 3 слова";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error["email"] = "Неверный формат email";
        }
        if (!preg_match('/^8\d{10}$/', $phone)){
            $error["phone"] = "Неверный формат номера телефона";
        }
        if (!preg_match('/^(?:(?:19|20)\d\d)-(?:0[1-9]|1[012])-(?:0[1-9]|[12][0-9]|3[01])$/', $birthday)){
            $error["date"] = "Неверный формат даты";
        }
        if (!($gender == "female" or $gender == "male")){
            $error["gender"] = "Неверный формат пола";
        }
        if (!validateLanguages($languages)){
            $error["languages"] = "Неверный формат массива яп";
        }

        return $error;
    }
    
    function makeAssociativeArray($fio){
        $string = explode(" ", $fio);
        $parts = array_filter($string, function($string) {
            return $string !== ''; // игнорируем пустые элементы (если несколько пробелов подряд)
        });

        return [
            'LastName' => $parts[0] ?? '',
            'FirstName' => $parts[1] ?? '',
            'Patronymic' => $parts[2] ?? ''
        ];
    }

?>