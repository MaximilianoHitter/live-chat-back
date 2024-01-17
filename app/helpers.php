<?php

if (!function_exists('respuesta')) {
    function respuesta($data, $error = null, $status = 200)
    {
        return response()->json([
            'data' => $data,
            'error' => $error
        ], $status);
    }
}

if (!function_exists('setLog')) {
    function setLog($message, $observation = null, $stack = null): bool
    {
        try {
            $data = [
                'user_id' => auth()->user() ? auth()->user()->id : null,
                'message' => $message,
                'observation' => $observation,
                'stack' => $stack ? json_encode($stack) : null
            ];

            $log = new \App\Models\Log();
            $log->fill($data);
            $log->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

if (!function_exists('is_email')) {
    function is_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}