<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\InstanceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    private InstanceInterface $categoryRepository;

    public function __construct(InstanceInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories per page
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'page' => 'nullable|integer|min:1',
                'perPage' => 'nullable|integer|min:1'
            ], $request->only('page', 'perPage'));
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $page = isset($validated['page']) ? $validated['page'] : 1;
        $perPage = isset($validated['perPage']) ? $validated['perPage'] : 10;

        try {
            return \response()->json([
                'data' => $this->categoryRepository->getAll($page, $perPage)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get category
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse {
        try {
            return response()->json([
                'data' => $this->categoryRepository->getById($id)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new category
     *
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function store(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:categories',
            ], $request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            return response()->json([
                'data' => $this->categoryRepository->create($validated)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update category
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'name' => 'string|required'
            ], $request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            if (!$this->categoryRepository->getById($id)) {
                return response()->json([
                    'error' => 'Category not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => !!$this->categoryRepository->update($id, $validated)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete category
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse {
        try {
            if (!$this->categoryRepository->getById($id)) {
                return response()->json([
                    'error' => 'Category not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => !!$this->categoryRepository->delete($id)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
