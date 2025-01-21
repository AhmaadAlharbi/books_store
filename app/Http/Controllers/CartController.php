<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addToCart(Request $request)
    {
        $book = Book::findOrFail($request->id);

        if (auth()->user()->booksInCart->contains($book)) {
            $newQuantity = $request->quantity + auth()->user()->booksInCart()
                ->where('book_id', $book->id)
                ->first()
                ->pivot->number_of_copies;

            if ($newQuantity > $book->number_of_copies) {
                session()->flash(
                    'warning_message',
                    'لم تتم اضافة الكتاب، اقصى عدد يمكن الحصول عليه هو ' .
                        ($book->number_of_copies - auth()->user()->booksInCart()
                            ->where('book_id', $book->id)
                            ->first()
                            ->pivot->number_of_copies)
                );
                return redirect()->back();
            } else {
                // Update the quantity in the cart
                auth()->user()->booksInCart()
                    ->updateExistingPivot($book->id, ['number_of_copies' => $newQuantity]);

                session()->flash('success_message', 'تم تحديث كمية الكتاب بنجاح.');
            }
        } else {
            // Add the book to the cart if it's not already present
            auth()->user()->booksInCart()->attach($book->id, ['number_of_copies' => $request->quantity]);

            session()->flash('success_message', 'تمت إضافة الكتاب إلى السلة بنجاح.');
        }
        $num_of_product = auth()->user()->booksInCart()->count();
        return response()->json(['num_of_product' => $num_of_product]);
    }
    public function viewCart()
    {
        $items = auth()->user()->booksInCart;
        return view('cart', compact('items'));
    }
    public function removeOne(Book $book)
    {
        $oldQuantity = auth()->user()->booksInCart()->where('book_id', $book->id)->first()->pivot->number_of_copies;
        if ($oldQuantity > 1) {
            auth()->user()->booksInCart()->updateExistingPivot($book->id, ['number_of_copies' => --$oldQuantity]);
        } else {
            auth()->user()->booksInCart()->detach($book->id);
        }
        return redirect()->back();
    }
    public function removeAll(Book $book)
    {
        auth()->user()->booksInCart()->detach($book->id);
        return redirect()->back();
    }
}
