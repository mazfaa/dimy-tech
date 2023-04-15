<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => ['required', 'exists:transactions,id'],
            'product_id' => ['required', 'exists:products,id'],
            'payment_id' => ['required', 'exists:payments,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $transactions = Transaction::find($request->transaction_id);
        $product = Product::find($request->product_id);
        $payment = Payment::find($request->payment_id);

        $transaction = new TransactionDetail;
        $transaction->transaction_id = $transactions->id;
        $transaction->product_id = $product->id;
        $transaction->payment_id = $payment->id;
        $transaction->qty = $request->qty;
        $transaction->price_total = 
        $product->product_price * $transaction->qty;
        $transaction->save();

        $transaction = TransactionDetail::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('customers', 'customers.id', '=', 'transaction_details.transaction_id')
        ->join('products', 'products.id', '=', 'transaction_details.product_id')
        ->join('payments', 'payments.id', '=', 'transaction_details.payment_id')
        ->where('transaction_details.id', $transaction->id)
        ->select(
            'transactions.id AS transaction_id', 'transactions.transaction_date', 'customers.customer_name', 
            'products.product_name', 'products.product_price', 'transaction_details.qty', 
            'payments.payment_name', 'transaction_details.price_total'
        )
        ->groupBy(
            'transaction_details.id', 'transactions.transaction_date', 'customers.customer_name', 
            'products.product_name', 'products.product_price', 'transaction_details.qty', 'payments.payment_name',
            'transactions.id', 'transaction_details.price_total'
        )
        ->first();

        return response()->json([
            'message' => 'Transaction Created Successfully!',
            'data' => $transaction
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }
}
