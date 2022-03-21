<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query();;

        $sorts = explode(',', $request->input('sort', ''));

        foreach ($sorts as $sortColumn) {
            if ($sortColumn !== '') {
                $sortDirection = str_starts_with($sortColumn, '-') ? 'desc' : 'asc';
                $sortColumn = ltrim($sortColumn, '-');

                $query->orderBy($sortColumn, $sortDirection);
            }
        }

        $filters = explode(',', $request->query('filter'));

        foreach ($filters as $filterColumn) {
            if ($filterColumn !== '') {
                [$field, $value] = explode(':', $filterColumn);

                $query->where($field, $value);
            }
        }

        $categories = $query->get();

        return response()->json([
            "meta" => [
                "total" => count($categories),
            ],
            "data" => $categories,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 400);
        }

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();

            return $this->sendCreatedResponse($category->id, $category);
        } catch (QueryException $e) {
            return $this->sendError(["query" => ["Error in Creating Category"]], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        try {
            $category = Category::findOrFail($id);

            $validator = Validator::make($request->all(), [
                "name" => "required"
            ]);
    
            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 400);
            }

            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();

            return $this->sendUpdatedResponse($category->id, $category);
        } catch (ModelNotFoundException $e) {
            return $this->sendError(["modelNotFound" => ["Model Not Found"]], 404);
        } catch (QueryException $e) {
            return $this->sendError(["query" => ["Error in Updating Category"]], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            $countDeleted = $category->delete();

            if ($countDeleted) {
                return response()->json([], 204);
            }
        } catch (ModelNotFoundException $e) {
            return $this->sendError(["modelNotFound" => ["Model Not Found"]], 404);
        } catch (QueryException $e) {
            return $this->sendError(["query" => ["Error in Deleting Category"]], 500);
        }
    }
}
