
<div>
    <div class="row">

        <div class="col-5">
            <p class="m--font-boldest m--font-transform-u">@lang('global.Code')</p>
        </div>
        <div class="col-5">
            <p class="m--font-boldest m--font-transform-u">@lang('global.Customer')</p>
        </div>
    </div>

    <div class="row m--margin-bottom-5" v-for="invoice in list">
        <div class="col-1">
            <p> @{{ invoice.tracking_code }} </p>
        </div>
        <div class="col-1">
            <p> @{{ invoice.relationship.customer_alias }} </p>
        </div>

        <div class="col-1">
            <div role="group" aria-label="...">
                <a @click="onEdit(invoice)" class="m-btn btn btn-secondary"><i class="la la-pencil m--font-brand"></i></a>
                <a @click="onDelete(invoice)" class="m-btn btn btn-secondary"><i class="la la-trash m--font-danger"></i></a>
                <a @click="onAnull(invoice)" class="m-btn btn btn-secondary"><i class="la la-close m--font-danger"></i></a>
            </div>
        </div>
    </div>
    @include('layouts/infinity-loading')
</div>
