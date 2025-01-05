<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return response()->json([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ], 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function sendPaginatedResponse($result, $paginate, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'paginate' => [
                'total' => $paginate->total(),
                'per_page' => $paginate->perPage(),
                'last_page' => $paginate->lastPage(),
                'current_page' => $paginate->currentPage(),
            ],
        ];
        return response()->json($response, 200);
    }
}
