<?php

namespace SON\Http\Controllers\Api;

use SON\Http\Controllers\Controller;
use SON\Http\Requests\CategoryRequest;
use SON\Repositories\CategoryRepository;


class CategoriesController extends Controller
{

    /**
     * @var CategoryRepository
     */
    protected $repository;


    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
        $this->repository->applyMultitenancy();
    }


    /**
     * Display a listing of the resource.
     *
     * @SWG\GET(
     *     path="/api/categories",
     *     description="Listar categorias",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Response(response="200", description="Coleção de categorias")
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @SWG\POST(
     *     path="/api/categories",
     *     description="Criar categoria",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="body", in="body", required=true,
     *       @SWG\Schema(
     *          @SWG\Property(
     *              property="name",
     *              type="string"
     *          ),
     *       )
     *          ),
     *     @SWG\Response(response="201", description="Categoria criada")
     * )
     * @param  CategoryRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->repository->create($request->all());
        return response()->json($category, 201);
    }


    /**
     * Display the specified resource.
     *
     * @SWG\GET(
     *     path="/api/categories/{id}",
     *     description="Listar uma categoria",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="id", in="path", required=true, type="integer"
     *          ),
     *     @SWG\Response(response="200", description="Categoria encontrada")
     * )
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @SWG\PUT(
     *     path="/api/categories/{id}",
     *     description="Atualizar categoria",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="id", in="path", required=true, type="integer"
     *     ),
     *     @SWG\Parameter(
     *          name="body", in="body", required=true,
     *       @SWG\Schema(
     *          @SWG\Property(
     *              property="name",
     *              type="string"
     *          ),
     *       )
     *          ),
     *     @SWG\Response(response="201", description="Categoria atualizada")
     * )
     * @param CategoryRequest $request
     * @param  string $id
     * @return Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = $this->repository->update($request->all(),$id);
        return response()->json($category, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @SWG\DELETE(
     *     path="/api/categories/{id}",
     *     description="Excluir categoria",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="id", in="path", required=true, type="integer"
     *     ),
     *     @SWG\Response(response="204", description="No content")
     * )
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if($deleted){
            return response()->json([], 204);
        }else{
            return response()->json([
                'error' => 'Resource can not be deleted'
            ], 500);
        }
    }
}
