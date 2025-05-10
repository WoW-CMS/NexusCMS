<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * Base Controller class that provides common functionality for all controllers
 *
 * This controller implements common CRUD operations and utility methods that can be
 * inherited by other controllers. It includes methods for handling both API and web
 * responses, validation, view rendering, and standard CRUD operations.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   NexusCMS <noreply@wow-cms.com>
 * @license  GNU General Public License (GPL)
 * @version  1.0.0
 * @link     wow-cms.com
 */
class BaseController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Model associated with the controller
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

    /**
     * Default view for the controller
     *
     * @var string
     */
    protected $view;

    /**
     * Validation rules for the model
     *
     * @var array
     */
    protected $validationRules = [];

    /**
     * Custom validation messages
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Determines if the controller has a model associated with it
     *
     * @var bool
     */
    protected $hasModel = true;

    /**
     * Determines if it's paginated or not
     *
     * @var bool
     */
    protected $isPaginated = false;

    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(
        $data = null,
        string $message = 'Operation successful',
        int $code = 200
    ): JsonResponse {
        return response()->json(
            [
            'success' => true,
            'message' => $message,
            'data' => $data
            ],
            $code
        );
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = 'An error has occurred',
        int $code = 400,
        $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Render view with data
     *
     * @param string|null $view
     * @param array $data
     * @return View
     */
    protected function renderView(?string $view = null, array $data = []): View
    {
        $viewToRender = $view ?? $this->view ?? $this->guessViewName();
        return view($viewToRender, $data);
    }

    /**
     * Guess the view name based on controller and action
     *
     * @return string
     */
    protected function guessViewName(): string
    {
        $controllerName = class_basename($this);
        $controllerName = str_replace('Controller', '', $controllerName);
        $action = debug_backtrace()[2]['function'] ?? 'index';
        return strtolower($controllerName) . '.' . $action;
    }

    /**
     * Validate request
     *
     * @param Request $request
     * @param array|null $rules
     * @param array|null $messages
     * @return array|JsonResponse
     */
    protected function validateRequest(
        Request $request,
        ?array $rules = null,
        ?array $messages = null
    ) {
        $rules = $rules ?? $this->validationRules;
        $messages = $messages ?? $this->validationMessages;

        try {
            $validated = $request->validate($rules, $messages);
            return $validated;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', 422, $e->errors());
        }
    }

    /**
     * Get list of resources
     *
     * @param Request $request The request instance containing pagination parameters
     * @param  string|null  $view  The view to render
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, ?string $view = null)
    {
        try {
            if (!$this->hasModel) {
                return $this->renderView($view);
            }

            $perPage = $request->get('per_page', 15);
            $data = $this->isPaginated
                ? $this->model->paginate($perPage)
                : $this->model->all();

            if ($request->expectsJson()) {
                return $this->successResponse($data);
            }

            return $this->renderView($view, ['data' => $data]);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Error retrieving records: ' . $e->getMessage(), 500);
            }

            return $this->renderView('errors.500', [
                'message' => 'Error retrieving records: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show specific resource
     *
     * @param int $id
     * @param string|null $view
     * @return JsonResponse|View
     */
    public function show(int $id, ?string $view = null)
    {
        try {
            $item = $this->model->findOrFail($id);

            if (request()->expectsJson()) {
                return $this->successResponse($item);
            }

            return $this->renderView($view, ['item' => $item]);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse('Record not found', 404);
            }

            return $this->renderView(
                'errors.404',
                [
                    'message' => 'Record not found'
                ]
            );
        }
    }

    /**
     * Store a new resource
     *
     * @param Request $request
     * @param string|null $redirectRoute
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, ?string $redirectRoute = null)
    {
        $validated = $this->validateRequest($request);

        if ($validated instanceof JsonResponse) {
            return $validated;
        }

        try {
            $item = $this->model->create($validated);

            if ($request->expectsJson()) {
                return $this->successResponse($item, 'Record created successfully', 201);
            }

            return redirect()->route(
                $redirectRoute ??
                $this->guessRedirectRoute('index')
            )->with('success', 'Record created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return $this->errorResponse(
                    'Error creating record: '
                    . $e->getMessage(),
                    500
                );
            }

            return back()->withInput()->withErrors(
                ['error' => 'Error creating record: ' . $e->getMessage()]
            );
        }
    }

    /**
     * Update existing resource
     *
     * @param Request $request
     * @param int $id
     * @param string|null $redirectRoute
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id, ?string $redirectRoute = null)
    {
        $validated = $this->validateRequest($request);

        if ($validated instanceof JsonResponse) {
            return $validated;
        }

        try {
            $item = $this->model->findOrFail($id);
            $item->update($validated);

            if ($request->expectsJson()) {
                return $this->successResponse($item, 'Record updated successfully');
            }

            return redirect()->route(
                $redirectRoute ??
                $this->guessRedirectRoute('index')
            )->with('success', 'Record updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return $this->errorResponse(
                    'Error updating record: '
                    . $e->getMessage(),
                    500
                );
            }

            return back()->withInput()->withErrors(
                ['error' => 'Error updating record: ' . $e->getMessage()]
            );
        }
    }

    /**
     * Delete a resource
     *
     * @param int $id
     * @param string|null $redirectRoute
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id, ?string $redirectRoute = null)
    {
        try {
            $item = $this->model->findOrFail($id);
            $item->delete();

            if (request()->expectsJson()) {
                return $this->successResponse(null, 'Record deleted successfully');
            }

            return redirect()->route(
                $redirectRoute ??
                $this->guessRedirectRoute('index')
            )->with('success', 'Record deleted successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse(
                    'Error deleting record: '
                    . $e->getMessage(),
                    500
                );
            }

            return back()->withErrors(
                ['error' => 'Error deleting record: ' . $e->getMessage()]
            );
        }
    }

    /**
     * Guess the redirect route based on controller and action
     *
     * @param string $action
     * @return string
     */
    protected function guessRedirectRoute(string $action): string
    {
        $controllerName = class_basename($this);
        $controllerName = str_replace('Controller', '', $controllerName);

        return strtolower($controllerName) . '.' . $action;
    }
}
