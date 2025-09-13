<?php

namespace App\Http\Traits;

trait HandlesValidation
{
    /**
     * Handle validation errors with consistent response format
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|array
     */
    protected function validateRequest($request, array $rules, array $messages = [])
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ];
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        return $validator->validated();
    }

    /**
     * Handle success response with consistent format
     *
     * @param  string  $message
     * @param  string  $route
     * @param  array  $data
     * @return \Illuminate\Http\RedirectResponse|array
     */
    protected function successResponse($message, $route = null, $data = [])
    {
        if (request()->expectsJson()) {
            return array_merge([
                'success' => true,
                'message' => $message,
            ], $data);
        }

        $redirect = $route ? redirect()->route($route) : redirect()->back();

        return $redirect->with('success', $message);
    }

    /**
     * Handle error response with consistent format
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  array  $errors
     * @return \Illuminate\Http\RedirectResponse|array
     */
    protected function errorResponse($message, $statusCode = 400, $errors = [])
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $statusCode);
        }

        return redirect()->back()
            ->with('error', $message)
            ->withInput();
    }

    /**
     * Handle database operation with try-catch
     *
     * @param  callable  $operation
     * @param  string  $successMessage
     * @param  string  $errorMessage
     * @param  string  $successRoute
     * @return \Illuminate\Http\RedirectResponse|array
     */
    protected function handleDatabaseOperation($operation, $successMessage, $errorMessage = 'Terjadi kesalahan saat memproses data', $successRoute = null)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $result = $operation();

            \Illuminate\Support\Facades\DB::commit();

            return $this->successResponse($successMessage, $successRoute, ['data' => $result]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            \Illuminate\Support\Facades\Log::error('Database operation failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse($errorMessage.': '.$e->getMessage());
        }
    }
}
