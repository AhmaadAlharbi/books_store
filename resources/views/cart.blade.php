@extends('layouts.main')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                عربة التسوق
            </div>
            <div class="card-body">
                @if($items->count())
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">العنوان</th>
                            <th scope="col">السعر</th>
                            <th scope="col">الكمية</th>
                            <th scope="col">السعر الكلي</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    @php($totalPrice = 0)
                    @foreach($items as $item)
                    @php($totalPrice += $item->price * $item->pivot->number_of_copies)
                    <tbody>
                        <tr>
                            <th scope="row">{{$item->title}}</th>
                            <td>{{$item->price}}</td>
                            <td>{{$item->pivot->number_of_copies}}</td>
                            <td>{{$item->price * $item->pivot->number_of_copies}} $</td>
                            <td>
                                <form style="float:left;,margin:auto 5px;"
                                    action="{{route('cart.remove_all',$item->id)}}" method="post">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm" type="submit">ازل الكل</button>
                                </form>
                                <form style="float:left;,margin:auto 5px;"
                                    action="{{route('cart.remove_one',$item->id)}}" method="post">
                                    @csrf
                                    <button class="btn btn-outline-warning btn-sm" type="submit">ازل واحدا</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    @endforeach

                </table>
                <h4 class="mb-5">المجموع النهائي :{{$totalPrice}}</h4>
                @else
                <div class="alert alert-info text-center">
                    لاتوجد كتب في العربة
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection