@extends('frontend.layouts.master')
@section('title', 'Cart Page')
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ 'home' }}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="">Cart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Shopping Cart -->
    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Shopping Summery -->
                    <table class="table shopping-summery">
                        <thead>
                            <tr class="main-hading">
                                <th>PRODUCT</th>
                                <th>NAME</th>
                                <th class="text-center">UNIT PRICE</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody id="cart_item_list">
                            <form action="{{ route('cart.update') }}" method="POST">
                                @csrf
                                @if (Helper::getAllProductFromCart())
                                    @foreach (Helper::getAllProductFromCart() as $key => $cart)
                                        @php
                                            $photo = explode(',', $cart->product['photo']);
                                        @endphp
                                        <tr>
                                            <td class="image" data-title="No">
                                                <img src="{{ $photo[0] }}" alt="{{ $photo[0] }}">
                                            </td>
                                            <td class="product-des" data-title="Description">
                                                <p class="product-name">
                                                    <a href="{{ route('product-detail', $cart->product['slug']) }}" target="_blank">
                                                        {{ $cart->product['title'] }}
                                                    </a>
                                                </p>
                                                <p class="product-des">{!! $cart['summary'] !!}</p>
                                            </td>
                                            <td class="price" data-title="Price">
                                                <span class="product-price">${{ number_format($cart['price'], 2) }}</span>
                                            </td>
                                            <td class="qty" data-title="Qty">
                                                <div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number"
                                                                data-type="minus" data-field="quant[{{ $key }}]"
                                                                @if($cart->quantity <= 1) disabled @endif>
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="quant[{{ $key }}]" class="input-number"
                                                           data-min="1" data-max="100" value="{{ $cart->quantity }}">
                                                    <input type="hidden" name="qty_id[]" value="{{ $cart->id }}">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number"
                                                                data-type="plus" data-field="quant[{{ $key }}]"
                                                                @if($cart->quantity >= 100) disabled @endif>
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="total-amount" data-title="Total">
                                                <span class="money">${{ number_format($cart['amount'], 2) }}</span>
                                            </td>
                                            <td class="action" data-title="Remove">
                                                <a href="{{ route('cart-delete', $cart->id) }}">
                                                    <i class="ti-trash remove-icon"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <a class="btn text-white" href="{{route('product-grids')}}" >Update Cart</a>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            There are no items in your cart. <a href="{{ route('product-grids') }}" style="color:blue;">Continue shopping</a>
                                        </td>
                                    </tr>
                                @endif
                            </form>
                        </tbody>
                    </table>
                    <!--/ End Shopping Summery -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Total Amount -->
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-5 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <!-- Coupon code field if needed -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-12">
                                <div class="right">
                                    <ul>
                                        <li class="order_subtotal" data-price="{{ Helper::totalCartPrice() }}">
                                            Cart Subtotal<span>${{ number_format(Helper::totalCartPrice(), 2) }}</span>
                                        </li>
                                        <li class="last" id="order_total_price">
                                            You Pay<span>${{ number_format(Helper::totalCartPrice(), 2) }}</span>
                                        </li>
                                    </ul>
                                    <div class="button5">
                                        <a href="{{ route('checkout') }}" class="btn">Checkout</a>
                                        <a href="{{ route('product-grids') }}" class="btn">Continue shopping</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ End Total Amount -->
                </div>
            </div>
        </div>
    </div>
    <!--/ End Shopping Cart -->

    <!-- Start Shop Services Area -->
    <section class="shop-services section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-rocket"></i>
                        <h4>Free shipping</h4>
                        <p>Orders over $100</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>Free Return</h4>
                        <p>Within 30 days returns</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-lock"></i>
                        <h4>Secure Payment</h4>
                        <p>100% secure payment</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service">
                        <i class="ti-tag"></i>
                        <h4>Best Price</h4>
                        <p>Guaranteed price</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Newsletter -->

    @include('frontend.layouts.newsletter')
@endsection

@push('styles')
    <style>
        li.shipping {
            display: inline-flex;
            width: 100%;
            font-size: 14px;
        }
        .input-group-icon .icon {
            position: absolute;
            left: 20px;
            top: 0;
            line-height: 40px;
            z-index: 3;
        }
        .form-select {
            height: 30px;
            width: 100%;
        }
        .list li:hover {
            background: #F7941D !important;
            color: white !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Quantity buttons
        $('.btn-number').click(function(e) {
            e.preventDefault();

            let fieldName = $(this).attr('data-field');
            let type = $(this).attr('data-type');
            let input = $("input[name='" + fieldName + "']");
            let currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {
                    if (currentVal > input.attr('data-min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('data-min')) {
                        $(this).attr('disabled', true);
                    }
                    // Enable plus button if it was disabled
                    $(this).closest('.input-group').find('.plus').removeAttr('disabled');
                } else if (type == 'plus') {
                    if (currentVal < input.attr('data-max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('data-max')) {
                        $(this).attr('disabled', true);
                    }
                    // Enable minus button if it was disabled
                    $(this).closest('.input-group').find('.minus').removeAttr('disabled');
                }
            } else {
                input.val(1);
            }
        });

        // Handle quantity changes
        $('.input-number').change(function() {
            let minValue = parseInt($(this).attr('data-min'));
            let maxValue = parseInt($(this).attr('data-max'));
            let quantity = parseInt($(this).val());

            if (isNaN(quantity) || quantity < minValue) {
                quantity = minValue;
                $(this).val(minValue);
            } else if (quantity > maxValue) {
                quantity = maxValue;
                $(this).val(maxValue);
            }

            // Enable/disable buttons based on new value
            if (quantity == minValue) {
                $(this).closest('.input-group').find('.minus').attr('disabled', true);
            } else {
                $(this).closest('.input-group').find('.minus').removeAttr('disabled');
            }

            if (quantity == maxValue) {
                $(this).closest('.input-group').find('.plus').attr('disabled', true);
            } else {
                $(this).closest('.input-group').find('.plus').removeAttr('disabled');
            }

            let cartId = $(this).siblings('input[name^="qty_id"]').val();
            let price = parseFloat($(this).closest('tr').find('.product-price').text().replace('$', ''));

            updateCartItem(cartId, quantity, price);
        });

        // AJAX function to update cart
        function updateCartItem(cartId, quantity, price) {
            $.ajax({
                url: "{{ route('cart.update.ajax') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: cartId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.status) {
                        // Update item total
                        $('input[name="qty_id[]"][value="'+cartId+'"]').closest('tr')
                            .find('.money').text('$'+response.item_total);

                        // Update subtotal and total
                        $('.order_subtotal span').text('$'+response.subtotal);
                        $('#order_total_price span').text('$'+response.grand_total);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Error updating cart. Please try again.');
                }
            });
        }
    });
    </script>
@endpush
