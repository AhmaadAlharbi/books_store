@extends('theme.default')
@section('head')
<!-- Custom styles for this page -->
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('header')
جميع المشتريات
@endsection
@section('content')

<hr>
<div class="row">
    <div class="col-md-12">
        <table id="books-table" class="table table-striped table-bordered text-right" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>المشتري</th>
                    <th>الكتاب</th>
                    <th>السعر</th>
                    <th>عدد النسخ</th>
                    <th>السعر الاجمالي</th>
                    <th>تاريخ الشراء</th>

                </tr>
            </thead>
            <tbody>
                @foreach($allBooks as $product)
                <tr>
                    <td>{{$product->user::find($product->user_id)->name}}</td>
                    <td><a
                            href="{{route('book.details',$product->id)}}">{{$product->book::find($product->book_id)->title}}</a>
                    </td>
                    <td>{{$product->price}}</td>
                    <td>
                        {{$product->number_of_copies}}
                    </td>
                    <td>{{$product->price * $product->number_of_copies}}$</td>
                    <td>{{$product->created_at->locale('ar')->diffForHumans()}}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
<!-- Page level plugins -->
<script src="{{asset('theme/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('theme/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#books-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/ar.json"
            }
        });
    });
</script>
@endsection