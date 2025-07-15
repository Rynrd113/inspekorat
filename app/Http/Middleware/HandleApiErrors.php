<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Http\Resources\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class HandleApiErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return response()->json(
                ApiResponse::validationError($e->errors()), 
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (AuthenticationException $e) {
            return response()->json(
                ApiResponse::error('Authentication required', Response::HTTP_UNAUTHORIZED),
                Response::HTTP_UNAUTHORIZED
            );
        } catch (AuthorizationException $e) {
            return response()->json(
                ApiResponse::error('Access forbidden', Response::HTTP_FORBIDDEN),
                Response::HTTP_FORBIDDEN
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(
                ApiResponse::error('Resource not found', Response::HTTP_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('API Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);

            return response()->json(
                ApiResponse::error('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
