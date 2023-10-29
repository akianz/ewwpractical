@extends('layouts.app')
<style>
 .product-container {
    /* display: flex; */
    align-items: center;
    padding: 10px;
}
.pro_main_divs{
    border: 1px solid black;

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
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
            <div class="card">
                <div class="card-header">Cart @if(count($cart_list) > 0)<a href="{{ route("checkout")}}" class="btn btn-success " style="float: right;">Checkout All</a>@endif</div>
                <div class="card-header">Total Amount : {{$cart_list_amount ?? 0}} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="product-container">
                        @if(!empty($cart_list))
                        @foreach($cart_list as $cValue)
                        <div class="pro_main_divs">
                            <div class="product-image">
                                <img src="{{asset('product_images/'.$cValue->product_details->image)}}" alt="Product Image" width="20%" height="30%"> 
                                <button class="btn btn-danger remove_product_cart" title="Remove product" data-url="{{route('cart.destroy',$cValue->id)}}"><i class="fa fa-close"></i></button>
                                
                            </div>
                            <div class="product-properties">
                                <h1 class="product-title">{{$cValue->product_details->name ?? ''}}</h1>
                                <p class="product-description">{{$cValue->product_details->description ?? ''}}</p>
                                <p><strong>Price:</strong> {{$cValue->product_details->price ?? ''}}</p>
                            </div>
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
    $(document).on("click",'.remove_product_cart',function(){
        var Url = $(this).attr('data-url');
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                url: Url,
                type: "DELETE",
                dataType: 'json',
                success: function(data) {
                    if(data.success){
                          Toast.fire({icon: 'success',title: `${data.message}`})
                          window.location.reload();
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
