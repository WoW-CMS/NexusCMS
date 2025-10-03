<?php

namespace App\Http\Controllers;

use App\Traits\ModelHandler;
use App\Traits\ResponseHandler;
use App\Traits\ValidationHandler;
use App\Traits\RouteViewHandler;
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
    use ModelHandler;
    use ResponseHandler;
    use ValidationHandler;
    use RouteViewHandler;

    /**
     * Model associated with the controller
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

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
     * Number of items per page
     *
     * @var int
     */
    protected $perPage = 5;

    /**
     * Get list of resources
     *
     * @param Request $request The request instance containing pagination parameters
     * @param string|null $view The view to render
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, ?string $view = null)
    {
        try {
            if (!$this->hasModel) {
                return $this->renderView($view ?? $this->getCurrentView());
            }

            $perPage = $request->get('per_page', $this->perPage);
            $data = $this->getModelData($perPage);

            if ($request->expectsJson()) {
                return $this->successResponse($data);
            }

            return $this->renderView($view ?? $this->getCurrentView(), ['data' => $data]);
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
    public function show(int|string $id, ?string $view = null)
    {
        try {
            $item = $this->findModel($id);

            if (request()->expectsJson()) {
                return $this->successResponse($item);
            }

            $this->setView('show');
            return $this->renderView($view ?? $this->getCurrentView(), ['item' => $item]);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse('Record not found', 404);
            }

            return $this->renderView('errors.404', ['message' => 'Record not found']);
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

            return redirect()
                ->route($redirectRoute ?? $this->guessRedirectRoute('index'))
                ->with('success', 'Record created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Error creating record: ' . $e->getMessage(), 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error creating record: ' . $e->getMessage()]);
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
            $item = $this->findModel($id);
            $item->update($validated);

            if ($request->expectsJson()) {
                return $this->successResponse($item, 'Record updated successfully');
            }

            return redirect()
                ->route($redirectRoute ?? $this->guessRedirectRoute('index'))
                ->with('success', 'Record updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Error updating record: ' . $e->getMessage(), 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error updating record: ' . $e->getMessage()]);
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
            $item = $this->findModel($id);
            $item->delete();

            if (request()->expectsJson()) {
                return $this->successResponse(null, 'Record deleted successfully');
            }

            return redirect()
                ->route($redirectRoute ?? $this->guessRedirectRoute('index'))
                ->with('success', 'Record deleted successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse('Error deleting record: ' . $e->getMessage(), 500);
            }

            return back()->withErrors(['error' => 'Error deleting record: ' . $e->getMessage()]);
        }
    }
}
