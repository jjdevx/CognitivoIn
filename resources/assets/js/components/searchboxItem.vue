<template>
    <div class="">


        <div class="input-group m-input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fa fa-spinner fa-spin" v-if="loading"></i>
                    <template v-else>
                        <i class="fa fa-search" v-show="isEmpty"></i>
                        <i class="fa fa-times" v-show="isDirty" @click="reset"></i>
                    </template>
                </span>
            </div>

            <input type="text"
            name ="contribuyente"
            class="form-control m-input"
            placeholder="Buscar"
            aria-describedby="basic-addon2"
            autocomplete="off"


            v-model="query"
            @keydown.down="down"
            @keydown.up="up"
            @keydown.enter="hit"
            @keydown.esc="reset"
            @blur="reset"
            @input="update"/>

            <div class="input-group-append">
                <span  id="basic-addon1">

                    <input type="hidden" name="item_id" v-model="id"/>
                    @{{ selectText }}
                </span>
            </div>
        </div>
        <span class="m-form__help">
            <ul v-show="hasItems">
                <li v-for="(item, $item) in items" :class="activeClass($item)" @mousedown="hit" @mousemove="setActive($item)">
                    <span class="name" v-text="item.name"></span>
                    <span class="screen-name" v-text="item.screen_name"></span>
                </li>
            </ul>
        </span>
    </div>
</template>

<script>
import VueTypeahead from 'vue-typeahead'
import Vue from 'vue'
import Axios from 'axios'
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
Vue.prototype.$http = Axios

export default {
    extends: VueTypeahead,
    props: ['current_company'],
    data () {
        return {
            src: '/api/' + this.current_company['slug'] + '/back-office/search/items/',
            limit: 5,
            minChars: 3,
            queryParamName: '',
            selectText:'Favor Elegir',
            id:'',
            detail:[]
        }
    },

    methods:
    {
        onHit (item)
        {
            var app = this  ;

            app.selectText = item.name + ' | ' + item.sku;
            app.id= item.id;

            // app.detail.push({ price:resp.data[i].unit_price,cost:resp.data[i].unit_cost,sku:resp.data[i].sku
            //     ,name:resp.data[i].name,quantity:quantity,item_id:resp.data[i].id});
                  this.$parent.addQuantity({ price:item.unit_price,cost:item.unit_cost,sku:item.sku
                      ,name:item.name,quantity:0,item_id:item.id});
            }

        }
    }
    </script>



    <style scoped>

    .fa-times
    {
        cursor: pointer;
    }

    i
    {
        float: right;
        position: relative;
        opacity: 0.4;
    }

    ul
    {
        position: absolute;
        padding: 0;
        min-width: 100%;
        background-color: #fff;
        list-style: none;
        border-radius: 4px;
        box-shadow: 0 0 10px rgba(0,0,0, 0.25);
        z-index: 1000;
    }

    li
    {
        padding: 5px;
        border-bottom: 1px solid #ccc;
        cursor: pointer;
    }

    li:first-child
    {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    li:last-child
    {
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-bottom: 0;
    }

    span
    {
        color: #2c3e50;
    }

    .active
    {
        background-color: #3aa373;
    }

    .active span
    {
        color: white;
    }

    .name
    {
        font-weight: 500;
        font-size: 14px;
    }

    .screen-name
    {
        font-style: italic;
    }
    </style>
