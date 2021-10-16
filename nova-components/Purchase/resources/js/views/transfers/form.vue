<template>
<div>
    <div class="p-4">
        <custom-create-header class="mb-3" :resource-name="resourceName" />
        <heading :level="1" class="pmb-3">{{resourceName}}</heading>
        <p class="text-80 text-sm font-semibold italic mb-3">Registro con Factura?
            <input class="checkbox" type="checkbox" v-model="form.with_invoice">
        </p>
    </div>
    <loading-view :loading="cargando">
        <form>
            <card>
                <div class="flex border-b border-40 mb-2 p-2">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Proveedor
                        </label>
                            <v-select
                                :filterable="false"
                                @search="selectProvider"
                                label="business_name"
                                :options="form.providers"
                                placeholder="nombre....."
                                @input="onProvider"                              
                            />
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Nit
                        </label>
                        <input v-model="form.nit" class="w-full form-control form-input form-input-bordered">
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2" v-show="form.with_invoice">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Nº Factura
                        </label>
                        <input v-model="form.invoice_number" class="w-full form-control form-input form-input-bordered">
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight" v-text="form.with_invoice? 'Fecha Factura' : 'Fecha Compra' ">
                        </label>
                        <input type="date" v-model="form.date" class="w-full form-control form-input form-input-bordered">
                    </div>
                </div>
                <div class="flex content-start bg-gray" v-show="form.with_invoice">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                        Tipo de Compra
                        </label>
                        <select class="w-full form-control form-select" v-model="form.type_purchase">
                            <option value="1" selected>Interno/Actividades agravadas (Estandar)</option>
                            <option value="2">Interno/Actividades no agravadas</option>
                            <option value="3">Sujetas a proporcinalidad</option>
                            <option value="4">Exportaciones</option>
                            <option value="5">Interno/Exportaciones</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                            Codigo de Control
                        </label>
                        <input v-model="form.codigo_control" class="w-full form-control form-input form-input-bordered">
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                            Nº Authorizacion
                        </label>
                        <input v-model="form.authorization_number" class="w-full form-control form-input form-input-bordered">
                    </div>
                </div>
                <hr>
                <div class="flex content-start bg-gray-50">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                            Almacen
                        </label>
                        <select class="w-full form-control form-select" v-model="form.warehouse">
                            <option v-for="item in form.warehouses" :value="item.id" :key="item.id">
                                {{item.nombre}}
                            </option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2" v-show="form.with_invoice">
                        <label class="text-80 pt-2 leading-tight">
                            Monto exento
                        </label>
                        <input v-model="form.exempt_amount" class="w-full form-control form-input form-input-bordered">
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2" v-show="form.with_invoice">
                        <label class="text-80 pt-2 leading-tight">
                            Descuento
                        </label>
                        <input v-model="form.discount" class="w-full form-control form-input form-input-bordered">
                    </div>
                </div>
                <hr>
                <div class="container mx-auto px-4">
                    <div class="overflow-hidden overflow-x-auto relative">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-left">
                                <th class=" text-gray-800">Descripcion</th>
                                <th>Unidad</th>
                                <th class="w-16 text-gray-800">Precio</th>
                                <th class="w-16 text-gray-800">Cantidad</th>
                                <th class="w-16 text-gray-800">Subtotal</th>
                                <th class="w-16 text-gray-800"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in form.items" :key="index">
                                <td>
                                  <v-select
                                        :filterable="false"
                                        @search="selectProduct"
                                        label="text"
                                        :options="products"
                                        placeholder="nombre....."
                                        @input="onProduct(index,$event)"                              
                                    >
                                        <template v-slot:option="option">
                                            {{ option.text }} . {{ option.almacen }}
                                        </template>
                                    </v-select>
                                </td>
                                <td>
                                    <v-select 
                                    label="abreviacion" 
                                    :options="item.units"
                                    @input="onUnit(index,$event)">
                                    </v-select>
                                    
                                </td>
                                <td>
                                <input type="number" class="w-16 form-control form-input form-input-bordered" v-model="item.purchase_price">
                                </td>
                                <td>
                                <input type="number" class="w-16 form-control form-input form-input-bordered" v-model="item.quantity">
                                </td>
                                <td>
                                    <span class="w-16">
                                        {{Number(item.purchase_price) * Number(item.quantity) | formatMoney }}
                                    </span>
                                </td>
                                <td class="td-fit text-right pr-6 align-middle">
                                    <div class="inline-flex items-center">
                                        <!-- Delete Resource Link -->
                                        <button
                                            class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                                            v-tooltip.click="__('Delete')"
                                            @click="removeItem(index)"
                                        >
                                            <icon />
                                        </button>
                                    </div>
                                </td>
                                </tr>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="3">
                                  <button class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6" type="button"
                                    @click="addNewLine">Nueva Linea</button>
                                </td>
                                <td class="form-summary">Total</td>
                                <td>{{subTotal | formatMoney }}</td>
                              </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>    
                <hr>
                <div class="container mx-auto px-4" v-show="form.with_invoice">
                    <div class="overflow-hidden overflow-x-auto relative">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-left">
                                <th class="px-4 py-2 text-gray-800">Subtotal</th>
                                <th class="px-4 py-2 text-gray-800">Importe base p/ credito fiscal</th>
                                <th class="px-4 py-2 text-gray-800">Credito Fiscal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>{{ SubTotal }}</td>
                                <td>{{ ImportBaseCredF }}</td>
                                <td>{{ CreditoFiscal }}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex flex-col bg-gray-200">
                    <div class="text-gray-700 text-rigth bg-gray-400 px-4 py-2 m-2">
                       <label>Observacion:</label>
                        <textarea 
                            class="w-full form-control form-input form-input-bordered py-3 h-auto"
                            v-model="form.observation">
                        </textarea>
                        <small class="error-control" v-if="errors.observation">
                            {{errors.observation[0]}}
                        </small>
                    </div>
                    <div class="text-gray-700 text-rigth bg-gray-400 px-4 m-2">
                        <p class="text-80 text-sm font-semibold italic mb-3">Crear registro de egreso en caja?
                            <input class="checkbox" type="checkbox" v-model="form.reg_compra_caja">
                        </p> 
                    </div>
                    <div class="text-gray-700 text-rigth bg-gray-400 px-4 py-2 m-2">
                       <button 
                           class="btn btn-default btn-primary flex-no-shrink ml-auto rounded-full" 
                           type="button"
                           :disabled="isProcessing" 
                           @click="onSave">
                                Guardar
                        </button>
                        <button class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6" 
                                type="button"
                                :disabled="isProcessing" 
                                @click="onCancel">Cancelar
                        </button>
                    </div>
                </div>
                
            </card>
        </form>
    </loading-view>
</div>
    
</template>
<script>
import Vue from 'vue'
import {get, byMethod } from '../../lib/api'
import { filtro } from '../../components/typeahead'
import "vue-select/dist/vue-select.css"
import vSelect from "vue-select"

function initialize(to) {
    let urls = {
        'create': `/nova-vendor/purchase/purchases/create`,
        'edit': `/nova-vendor/purchase/purchases/${to.params.id}/edit`
    }
    return (urls[to.meta.mode] || urls['create'])
}
export default {
    components: {
      filtro,
      vSelect
    },
    data() {
        return {
            form: {},
            errors: {},
            products:[],
            resourceName: 'Compras',
            resource: '/purchase',
            store: '/nova-vendor/purchase/purchases',
            method: 'POST',
            cargando: false,
            isProcessing: false,
            providerURL: '/nova-vendor/purchase/selectproviders',
            productURL: '/nova-vendor/purchase/selectproducts',
        }
    },
    beforeRouteEnter(to, from, next) {
        get(initialize(to))
            .then((res) => {
                next(vm => vm.setData(res))
        })
    },
    beforeRouteUpdate(to, from, next) {
        this.cargando = true
        get(initialize(to))
            .then((res) => {
                this.setData(res)
                next()
            })
    },   
    computed: {
        subTotal() {
            if(this.form.hasOwnProperty('items')){
                return this.form.items.reduce((carry, item) => {
                    return carry + (Number(item.purchase_price) * Number(item.quantity))
                }, 0)
            }
        },
        //subtotal con factura
        SubTotal(){
            let subtotal = this.form.with_invoice? (this.subTotal - this.form.exempt_amount) : this.subTotal;
            this.form.sub_total = subtotal;
            return subtotal;
        },
        ImportBaseCredF(){
            let bcr = this.form.with_invoice? (this.SubTotal - this.form.discount) : 0;
            this.form.amount_base_cf = bcr;
            return bcr;
        },
        CreditoFiscal(){
            let cr = this.form.with_invoice? ((this.SubTotal - this.form.discount) * 0.13) :0;
            this.form.credito_fiscal = cr;
            return cr;
        }
    },
    filters: {
        formatMoney: function (value) {
            return Number(value)
                .toFixed(2)
                .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        }
    }, 
    methods: {
        setData(res) {
            Vue.set(this.$data, 'form', res.data.form)
            if(this.$route.meta.mode === 'edit') {
                this.store = `/nova-vendor/purchase/purchases/${this.$route.params.id}`
                this.method = 'PUT'
                this.title = 'Edit'
            }
            this.cargando = false
        },
        selectProvider(search,loading){
            let me = this;
            loading(true)
            var url= '/nova-vendor/purchase/selectproviders?q='+search;
            axios.get(url).then(function (response) { 
                me.form.providers = response.data.results;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        onProvider(val) {
          Vue.set(this.$data.form, 'provider', val)
          Vue.set(this.$data.form, 'contact_id', val.id)
        },
        selectProduct(search,loading){
            let me = this;
            loading(true)
            var url= '/nova-vendor/purchase/selectproducts?q='+search;
            axios.get(url).then(function (response) { 
                me.products = response.data.results;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        onProduct(index,val) {
            Vue.set(this.form.items[index], 'product', val)
            Vue.set(this.form.items[index], 'product_id', val.COD)
            Vue.set(this.form.items[index], 'units', val.units)
        },
        removeItem(index){
            this.form.items.splice(index, 1)
        },
        addNewLine() {
            this.form.items.push({
                product_id: null,
                product: null,
                purchase_price: 0,
                quantity: 1,
                almacen: this.form.warehouse,
                quantity_unit:0,
                unit_id:1,
                units:[]
            })
        },
        onCancel() {
            if(this.$route.meta.mode === 'edit') {
                this.$router.push(`${this.resource}/${this.form.id}`)
            } else {
                this.$router.push({name:'purchases'})
            }
        },
        onSave() {
            this.errors = {}
            this.isProcessing = true
            byMethod(this.method, this.store, this.form)
                .then((res) => {
                    if (res.data.mensage) {
                        Nova.warning(res.data.mensage)
                        this.isProcessing = false
                    }
                    if(res.data && res.data.saved) {
                        this.success(res)
                    }
                })
                .catch((error) => {
                    if(error.response.status === 422) {
                        this.errors = error.response.data.errors
                    }
                    this.isProcessing = false
                })
        },
        success(res) {
            this.$router.push(`${this.resource}/${res.data.id}`)
        },
        onUnit(index, val) {
            let unidad = val.pivot
            Vue.set(this.form.items[index], 'quantity_unit',Number(unidad.cantidad_unidad))
            Vue.set(this.form.items[index], 'unit_id',Number(unidad.unit_id))    
        },
    }
}
</script>