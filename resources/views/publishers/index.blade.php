@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row ">
        <div class="card">
            <div class="card-header">
                الناشرون
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <form action="{{route('gallery.publishers.search')}}" method="GET">
                        <div class="row d-flex justify-content-center">
                            <input type="text" class="col-3 mx-sm-3 mb-2" name="term" placeholder="ابحث عن ناشر">
                            <button type="submit" class="col-1 btn btn-secondary bg-secondary mb-2">ابحث</button>
                        </div>
                    </form>
                </div>
                <hr>
                <br>
                <h3 class="mb-4">{{$title}}</h3>
                @if($publishers->count())
                <ul class="list-group">
                    @foreach($publishers as $publisher)
                    <a href="{{route('gallery.publishers.show',$publisher)}}" style="color:grey">
                        <li class="list0group-item">
                            {{$publisher->name}} ({{$publisher->books->count()}})
                        </li>
                    </a>
                    @endforeach
                </ul>
                @else
                <div class="alert alert-info mt-4 mx-auto text-center" role="alert">
                    لاتوجد نتائج
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection