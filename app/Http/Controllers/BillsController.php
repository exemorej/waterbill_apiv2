<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bill\CreateBillRequest;
use App\Http\Requests\Bill\UpdateBillRequest;
use App\Http\Resources\BillResource;
use App\Http\Resources\ConsumerBillResource;
use App\Models\Bill;
use App\Models\Consumer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($consumerId)
    {
        if (is_null(Consumer::find($consumerId))) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        } else {
            $response['status'] = 'success';
            $response['data'] = BillResource::collection(Consumer::find($consumerId)->bills->sortBy('period_start')->sortBy('status'));

            return response()->json($response, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBillRequest $request, $consumerID)
    {
        $consumer = Consumer::find($consumerID);

        if (is_null($consumer)) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        } else {
            // if ($request->has('image') && !(is_null($request->image))) {
            //     $path = $request->file('image')->store('bills', 'public');
            // }

            $bill = Bill::create([
                'consumer_id' => $consumer->id,
                'amount' => $request->amount,
                'paid' => $request->paid,
                'status' => $request->status,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
                'due_date' => $request->due_date,
                // 'image' => isset($path) ? asset('/storage/' . $path) : $request->image,
                'reader_id' => Auth::id(),
                'reader_name' => Auth::user()->name,
                'reading_date' => now(),
            ]);

            $response['status'] = 'success';
            $response['data'] = $bill;
        }

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($consumerID, $billID)
    {
        $consumer = Consumer::find($consumerID);

        if (is_null($consumer)) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        } else {
            $bill = Consumer::find($consumerID)->bills->where('id', $billID)->first();

            if (is_null($bill)) {
                $response['status'] = 'fail';
                $response['message'] = 'This consumer has no bill with this ID!';
            } else {
                $response['status'] = 'success';
                $response['data'] = new BillResource($bill);
            }
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
    public function update(UpdateBillRequest $request, $consumerID, $billID)
    {
        $consumer = Consumer::find($consumerID);
        if (is_null($consumer)) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        } else {
            $bill = Consumer::find($consumerID)->bills->where('id', $billID)->first();

            if (is_null($bill)) {
                $response['status'] = 'fail';
                $response['message'] = 'This consumer has no bill with this ID!';
            } else {
                // if ($request->has('image') && !(is_null($request->image))) {
                //     $path = $request->file('image')->store('bills', 'public');
                // }
                $bill->update([
                    'amount' => $request->amount,
                    'paid' => $request->paid,
                    'status' => $request->status,
                    'period_start' => $request->period_start,
                    'period_end' => $request->period_end,
                    'due_date' => $request->due_date,
                    // 'image' => isset($path) ? asset('/storage/' . $path) : $request->image
                ]);

                $response['status'] = 'success';
                $response['data'] = new BillResource($bill);
            }
        }

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($consumerID, $billID)
    {
        $consumer = Consumer::find($consumerID);
        if (is_null($consumer)) {
            $response['status'] = 'fail';
            $response['message'] = 'Consumer not found!';
        } else {
            $bill = Consumer::find($consumerID)->bills->where('id', $billID)->first();

            if (is_null($bill)) {
                $response['status'] = 'fail';
                $response['message'] = 'This consumer has no bill with this ID!';
            } else {
                $bill->delete();

                $response['status'] = 'success';
                $response['data'] = null;
            }
        }
        return response()->json($response, 204);
    }

    public function dues()
    {
        $consumers = Consumer::whereHas('bills', function (Builder $query) {
            $query->where('status', 'unpaid')->where('period_end', '<=', now());
        })->orderBy('name', 'asc')->get();

        if ($consumers->isEmpty()) {
            $response['status'] = 'no records';
            $response['message'] = 'No for disconnection bills at the moment!';
        } else {
            $response['status'] = 'success';
            $response['data'] = ConsumerBillResource::collection($consumers);
        }

        return response()->json($response, 200);
    }
}
