<?php

namespace App\Http\Controllers;

use App\Http\Requests\Consumer\CreateConsumerRequest;
use App\Http\Requests\Consumer\UpdateConsumerRequest;
use App\Http\Resources\ConsumerResource;
use App\Models\Consumer;
use Illuminate\Http\Request;

class ConsumersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Consumer::all()->count() == 0) {
            $response['status'] = 'no records';
            $response['message'] = 'No active Consumer at the moment';
        } else {
            $response['status'] = 'success';
            $response['data'] = ConsumerResource::collection(Consumer::all()->sortBy('name'));
        }

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateConsumerRequest $request)
    {
        $consumer = Consumer::create($request->all());

        $response['status'] = 'success';
        $response['data'] = $consumer;

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (is_null(Consumer::find($id))) {
            $response['status'] = 'fail';
            $response['message'] = 'There is no Consumer in our record associated with this id!';
        } else {
            $response['status'] = 'success';
            $response['data'] = Consumer::find($id);
        }

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConsumerRequest $request, $id)
    {
        if (is_null(Consumer::find($id))) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        } else {
            Consumer::find($id)->update($request->all());

            $response['status'] = 'success';
            $response['data'] = Consumer::find($id);
        }

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null(Consumer::find($id))) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        }

        $count = Consumer::find($id)->bills->count();
        if ($count == 0) {
            Consumer::find($id)->delete();
            $response['status'] = 'success';
            $response['data'] = null;
        } else if ($count >= 1) {
            $response['status'] = 'fail';
            $response['message'] = 'This consumer is associated to an existing bills. If you truly want to delete this consumer, settle and delete all the bills under this consumer.';
        }

        return response()->json($response, 200);
    }
}
