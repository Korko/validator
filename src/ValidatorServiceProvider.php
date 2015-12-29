<?php

namespace Korko\Validator;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('arrayunique', function ($attribute, $value, $parameters, $validator) {
            return is_array($value) && count($value) === count(array_unique($value));
        });

        Validator::extend('fieldin', function ($attribute, $value, $parameters, $validator) {
            return (count($parameters) === 1 &&
                isset($validator->getData()[$parameters[0]]) &&
                is_array($validator->getData()[$parameters[0]]) &&
                array_key_exists($value, $validator->getData()[$parameters[0]])
            );
        });
        Validator::replacer('fieldin', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':other', $parameters[0], $message);
        });

        Validator::extend('contains', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && array_reduce((array) $parameters, function ($return, $find) use ($value) {
                return $return && strpos($value, $find) !== false;
            }, true);
        });
        Validator::replacer('contains', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', implode(', ', $parameters), $message);
        });
    }

    public function register()
    {
        //
    }
}