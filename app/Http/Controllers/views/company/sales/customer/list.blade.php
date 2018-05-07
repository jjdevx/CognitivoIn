
@extends('layouts.form')

@section('pageTitle')
    Hi
@endsection

@section('content')

    <div class="content content-boxed">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="{{ route('customers.create', request()->route('profile')) }}">
                    <div class="block-content block-content-full">
                        <div class="h1 font-w700 text-success"><i class="fa fa-plus"></i></div>
                    </div>
                    <div class="block-content block-content-full block-content-mini bg-gray-lighter text-success font-w600">Add New Customer</div>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        {{-- <div class="h1 font-w700 text-danger" data-toggle="countTo" data-to="15">15</div> --}}
                    </div>
                    {{-- <div class="block-content block-content-full block-content-mini bg-gray-lighter text-danger font-w600">Out of Stock</div> --}}
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        {{-- <div class="h1 font-w700" data-toggle="countTo" data-to="100">100</div> --}}
                    </div>
                    {{-- <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Top Sellers</div> --}}
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        {{-- <div class="h1 font-w700" data-toggle="countTo" data-to="8750">8750</div> --}}
                    </div>
                    {{-- <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">All Products</div> --}}
                </a>
            </div>
        </div>
        <div class="block">
            <div class="block-header bg-gray-lighter">
                <ul class="block-options">
                    <li class="dropdown">
                        <button type="button" data-toggle="dropdown">Filter <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a tabindex="-1" href="javascript:void(0)"><span class="badge pull-right">90</span>New</a>
                            </li>
                            <li>
                                <a tabindex="-1" href="javascript:void(0)"><span class="badge pull-right">15</span>Out of Stock</a>
                            </li>
                            <li>
                                <a tabindex="-1" href="javascript:void(0)"><span class="badge pull-right">8750</span>All</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <h3 class="block-title">All Products</h3>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">Gov Code</th>
                            <th class="visible-lg">Alias</th>
                            <th class="visible-lg">Telephone</th>
                            <th class="visible-lg">Email</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($customers as $customer)
                        <tr>
                            <td class="text-center">
                                <a class="font-" href="{{ route('customers.edit',[ request()->route('profile'),$customer]) }}">
                                    <strong>{{$customer->customer_taxid}}</strong>
                                </a>
                            </td>
                            <td class="visible-lg">
                                <a href="{{ route('customers.edit',[ request()->route('profile'),$customer]) }}">{{$customer->customer_name}}</a>
                            </td>
                            <td class="visible-lg">
                                <a class="font-" href="{{ route('customers.edit',[ request()->route('profile'),$customer]) }}">
                                    <strong>{{$customer->customer_alias}}</strong>
                                </a>
                            </td>
                            <td class="visible-lg">
                                <a class="font-" >
                                    <strong>{{$customer->customer_telephone}}</strong>
                                </a>
                            </td>
                            <td class="visible-lg">
                                <a class="font-">
                                    <strong>{{$customer->customer_email}}</strong>
                                </a>
                            </td>

                            <td class="text-center">
                                <div class="btn-group btn-group-xs">
                                    <a href="{{ route('customers.edit',[ request()->route('profile'),$customer]) }}" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="View"><i class="fa fa-eye"></i></a>
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Delete"><i class="fa fa-times text-danger"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                <nav class="text-right">
                    <ul class="pagination pagination-sm">
                        <li class="active">
                            <a href="javascript:void(0)">1</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">2</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">3</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">4</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">5</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

@endsection