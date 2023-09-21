<?php
require_once __DIR__ . '/Main.php';
class DataValidation extends Main
{
    protected static function validate(array $fieldList, object $source)
    {
        static::fieldExist($fieldList, $source);
        static::emptyFields($fieldList, $source);
        return true;
    }

    protected static function fieldExist(array $fieldList, object $source)
    {
        $notExist = [];
        foreach ($fieldList as $field) {
            if (!isset($source->{$field})) {
                array_push($notExist, $field);
            }
        }
        if (count($notExist) === 0) return true;
        static::sendJson(400, 'Please fill all the required fields', [
            'required_fields' => $notExist
        ]);
    }

    protected static function emptyFields(array $fieldList, object $source)
    {
        $emptyFields = [];
        $notString = [];

        foreach ($fieldList as $field) {
            if (!is_string($source->{$field})) {
                array_push($notString, $field);
            } elseif (empty(trim($source->{$field}))) {
                array_push($emptyFields, $field);
            }
        }

        if (count($notString)) {
            static::sendJson(422, 'Data type of all fields must be string.', [
                'invalid_data_type' => $notString
            ]);
        } elseif (count($emptyFields) === 0) return true;

        static::sendJson(422, 'Oops! empty field detected.', [
            'empty_fields' => $emptyFields
        ]);
    }

    protected static function isEmail(string $email)
    {
        return (!preg_match(
            '^[a-z0-9-]+(\.[a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            $email
        ))
            ? static::sendJson(422, 'Invalid email address.') : true;
    }

    protected static function minLength($data, $field, $len)
    {
        if (strlen($data) < $len) {
            static::sendJson(422, ucfirst($field) . " must be at least $len characters in length.", [
                'field' => strtolower($field)
            ]);
        }
    }
}
