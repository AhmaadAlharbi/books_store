@extends('theme.default')
@section('head')
<!-- Custom styles for this page -->
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('header')
عرض الكتب
@endsection
@section('content')

<a class="btn btn-primary" href="{{ route('books.create') }}">
    <i class="fas fa-plus"></i> أضف كتابًا جديدًا</a>

<hr>
<div class="row">
    <div class="col-md-12">
        <table id="books-table" class="table table-striped table-bordered text-right" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الرقم التسلسلي</th>
                    <th>التصنيف</th>
                    <th>المؤلفون</th>
                    <th>الناشر</th>
                    <th>السعر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr>
                    <td><a href="{{route('books.show',$book)}}">{{$book->title}}</a></td>
                    <td>{{$book->isbn}}</td>
                    <td>{{$book->category != null ? $book->category->name : ''}}</td>
                    <td>
                        @if($book->authors()->count())
                        @foreach($book->authors as $author)
                        {{$loop->first ? '' : 'و' }}
                        {{$author->name}}
                        @endforeach
                        @endif
                    </td>
                    <td>{{$book->publisher != null ? $book->publisher->name : ''}}</td>
                    <td>${{$book->price}}</td>
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