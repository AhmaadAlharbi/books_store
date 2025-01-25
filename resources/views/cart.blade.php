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

                <div class="d-inline-block" id="paypal-button-container"></div>
                <p id="result-message"></p>


                <!-- Initialize the JS-SDK -->
                <script
                    src="https://www.paypal.com/sdk/js?client-id=AQZTHHfd6Tdw0Go88_kAJREyGXvHy-j_8-R2bG5s4oNtpBEQJHDph5CuSXsTICqqyqynca2ao-E1-N8N&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"
                    data-sdk-integration-source="developer-studio"></script>
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
@section('script')
<script>
    paypal.Buttons({
        style: {
            shape: 'rect',
            layout: 'vertical',
            color: 'gold',
            label: 'paypal'
        },
        
        // Creates the order directly with PayPal
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '20.00' // Replace with your amount
                    }
                }]
            });
        },

        // Handle approved payment
        onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
        // Show a success message to the user
        alert('Transaction completed successfully by ' + details.payer.name.given_name + '!');

        // Update the result message on the page
        document.getElementById('result-message').innerHTML =
            'Transaction completed by ' + details.payer.name.given_name;

        // Log the transaction details in the console
        console.log('Transaction details:', details);
    }).catch(function(error) {
        // Handle errors
        console.error('Error capturing transaction:', error);
        alert('An error occurred during the transaction.');
    });
},


        // Handle errors
        onError: function(err) {
            document.getElementById('result-message').innerHTML = 
                'An error occurred: ' + err;
            console.error('PayPal Error:', err);
        }
    }).render('#paypal-button-container');
</script>
@endsection