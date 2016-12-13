<?php

namespace SON\Http\Controllers\Api;

use SON\Http\Controllers\Controller;
use SON\Http\Requests\BillPayRequest;
use SON\Repositories\BillPayRepository;


class BillPaysController extends Controller
{

    /**
     * @var BillPayRepository
     */
    protected $repository;

    public function __construct(BillPayRepository $repository)
    {
        $this->repository = $repository;
        $this->repository->applyMultitenancy();
    }


    /**
     * Display a listing of the resource.
     *
     * @SWG\GET(
     *     path="/api/bill_pays",
     *     description="Listar contas a pagar",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Response(response="200", description="Coleção de contas a pagar")
     * )
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
     *     path="/api/bill_pays",
     *     description="Criar conta a pagar",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="body", in="body", required=true,
     *       @SWG\Schema(
     *          @SWG\Property( property="name", type="string" ),
     *          @SWG\Property( property="date_due", type="string", format="date"),
     *          @SWG\Property( property="value", type="number"),
     *          @SWG\Property( property="category_id", type="integer"),
     *       )
     *          ),
     *     @SWG\Response(response="201", description="Conta a pagar criada")
     * )
     * @param  BillPayRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BillPayRequest $request)
    {
        $data = $request->except('done');
        $billPay = $this->repository->create($data);
        return response()->json($billPay,201);
    }


    /**
     * Display the specified resource.
     *
     * @SWG\GET(
     *     path="/api/bill_pays/{id}",
     *     description="Listar uma conta a pagar",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="id", in="path", required=true, type="integer"
     *          ),
     *     @SWG\Response(response="200", description="Conta a pagar encontrada")
     * )
     *
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
     * * @SWG\PUT(
     *     path="/api/bill_pays/{id}",
     *     description="Atualizar conta a pagar",
     *     @SWG\Parameter(
     *          name="Authorization", in="header", type="string", description="Bearer __token__"
     *     ),
     *     @SWG\Parameter(
     *          name="id", in="path", required=true, type="integer"
     *          ),
     *     @SWG\Parameter(
     *          name="body", in="body", required=true,
     *       @SWG\Schema(
     *          @SWG\Property( property="name", type="string" ),
     *          @SWG\Property( property="date_due", type="string", format="date"),
     *          @SWG\Property( property="value", type="number"),
     *          @SWG\Property( property="category_id", type="integer"),
     *          @SWG\Property( property="done", type="boolean"),
     *       )
     *          ),
     *     @SWG\Response(response="201", description="Conta a pagar atualizada")
     * )
     * @param  BillPayRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(BillPayRequest $request, $id)
    {
        $billPay = $this->repository->update($request->all(),$id);
        return response()->json($billPay,200);
    }


    /**
     * Remove the specified resource from storage.
     *
     *  @SWG\DELETE(
     *     path="/api/bill_pays/{id}",
     *     description="Excluir conta a pagar",
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

    public function calculateTotal(){
        return $this->repository->calculateTotal();
    }
}
