<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\InstanceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LotController extends Controller
{
    private InstanceInterface $lotRepository;

    public function __construct(InstanceInterface $lotRepository)
    {
        $this->lotRepository = $lotRepository;
    }

    /**
     * Get lots per page filtered by categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'integer|min:1',
                'page' => 'nullable|integer|min:1',
                'perPage' => 'nullable|integer|min:1'
            ], $request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $page = isset($validated['page']) ? $validated['page'] : 1;
        $perPage = isset($validated['perPage']) ? $validated['perPage'] : 10;

        try {
            if (isset($validated['category_ids']) && count($validated['category_ids']) > 0) {
                return response()->json([
                    'data' => $this->lotRepository->getAllFiltered($validated['category_ids'], $page, $perPage)
                ], Response::HTTP_OK);
            }

            return response()->json([
                'data' => $this->lotRepository->getAll($page, $perPage)
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get lot
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse {
        try {
            return response()->json([
                'data' => $this->lotRepository->getById($id)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new lot
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'category_ids' => 'required|array',
                'category_ids.*' => 'integer|min:1'
            ], $request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $lot = $this->lotRepository->create($validated);

            if (!$lot) {
                return response()->json([
                    'error' => 'Wrong data provided'
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'data' => $lot
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update lot
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'integer|min:1'
            ], $request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            if (!$this->lotRepository->getById($id)) {
                return response()->json([
                    'error' => 'Category not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => !!$this->lotRepository->update($id, $validated)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete lot
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse {
        try {
            if (!$this->lotRepository->getById($id)) {
                return response()->json([
                    'error' => 'Category not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => !!$this->lotRepository->delete($id)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
