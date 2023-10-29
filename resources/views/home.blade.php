@extends('layouts.app')
<style>
 .product-container {
    /* display: flex; */
    align-items: center;
}
.product-image {
    flex: 1;
    /* max-width: 20%; */
    /* width: 200px; */
    /* height: 100px; */
}

.product-properties {
    flex: 1;
    padding: 20px;
}

.product-title {
    font-size: 24px;
    margin-bottom: 10px;
}

.product-description {
    font-size: 16px;
}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <hr>
                    <div class="product-container">
                        @if(!empty($products))
                        @foreach($products as $pValue)
                        <div class="product-image">
                            <img src="{{asset('product_images/'.$pValue->image)}}" alt="Product Image" width="20%" height="30%"> 
                        </div>
                        <div class="product-properties">
                            <h1>{{$pValue->get_brand->name ?? ''}}</h1>
                            <h1 class="product-title">{{$pValue->name ?? ''}}</h1>
                            <p class="product-description">{{$pValue->description ?? ''}}</p>
                            <p><strong>Price:</strong> {{$pValue->price ?? ''}}</p>
                            <p><strong>stock:</strong>@if($pValue->stock > 0){{'Instock'}} @else {{"Out of stock"}} @endif</p>
                            <button class="btn btn-primary add_to_cart_btn" data-id="{{$pValue->id ?? ''}}" data-price="{{$pValue->price ?? ''}}">Add to cart</button>
                        </div>
                        @endforeach                          
                        @endif
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer" defer="defer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    $(document).on("click",".add_to_cart_btn",function(){
        var product_id = $(this).attr('data-id');
        var price = $(this).attr('data-price');
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                url: "{{ route('add_to_cart') }}",
                data : {product_id:product_id,price:price},
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if(data.success){
                          Toast.fire({icon: 'success',title: `${data.message}`})
                    }else{
                        Toast.fire({icon: 'danger',title: `${data.message}`})
                    }
                },
                error: function(xhr) {
                    alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText +' ' + xhr.responseText);
                },
            });
    });

</script>
