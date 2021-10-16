<template>
<div>
    <div class="p-4">
        <custom-create-header class="mb-3" :resource-name="resourceName" />
        <heading :level="1" class="pmb-3">{{resourceName}}</heading>
    </div>
    <loading-view :loading="cargando">
        <form>
            <card>
                <div class="flex border-b border-40 mb-2 p-2">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Cliente
                        </label>
                           <v-select
                                :filterable="false"
                                @search="selectCustomer"
                                label="fullName"
                                :options="form.customers"
                                placeholder="nombre....."
                                @input="onCustomer"                              
                            />
                        <small class="errors" v-if="errors.contact_id">
                            {{errors.contact_id[0]}}
                        </small>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            CI/NIT
                        </label>
                        <input v-model="form.document" class="w-full form-control form-input form-input-bordered">
                        <small class="errors" v-if="errors.document">
                            {{errors.document[0]}}
                        </small>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Tipo Documento
                        </label>
                        <select class="w-full form-control form-select" v-model="form.typedocument">
                            <option v-for="doc in form.typedocuments" :key="doc.id" 
                                :value="doc.id">{{doc.name}}
                            </option>
                        </select>
                        <small class="#DC2626" v-if="errors.typedocument">
                            {{errors.typedocument[0]}}
                        </small>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight" v-text="'Fecha'">
                        </label>
                        <input type="date" v-model="form.date" class="w-full form-control form-input form-input-bordered">
                        <small class="errors" v-if="errors.date">
                            {{errors.date[0]}}
                        </small>
                    </div>
                </div>
                <hr>
                <div class="flex content-start bg-gray-50">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                            Tipo de Pago
                        </label>
                        <select class="w-full form-control form-select" v-model="form.typepayment">
                            <option v-for="item in form.typepayments" :value="item.id" :key="item.id">
                                {{item.description}}
                            </option>
                        </select>
                        <small class="errors" v-if="errors.typepayment">
                            {{errors.typepayment[0]}}
                        </small>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 bg-gray-500 px-2" v-if="form.typepayment == form.defaultpaymet_for_creditsales">
                        <label class="inline-block text-80 pt-2 leading-tight" v-text="'Fecha Plazo'">
                        </label>
                        <input type="date" v-model="form.deadline" class="w-full form-control form-input form-input-bordered">
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                            Recibido
                        </label>
                        <input v-model="form.received" type="number" class="w-full form-control form-input form-input-bordered">
                        <small class="errors" v-if="errors.received">
                            {{errors.received[0]}}
                        </small>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
                        <label class="text-80 pt-2 leading-tight">
                            Vuelto
                        </label>
                        <br>
                        <strong>{{Vuelto}}</strong>
                    </div>
                </div>
                <hr>
                <div class="container mx-auto px-4">
                    <div class="overflow-hidden overflow-x-auto relative">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-center">
                                <th>Descripcion</th>
                                <th>Unidad</th>
                                <th class="w-16">Stock</th>
                                <th class="w-16">Precio</th>
                                <th class="w-16">Cantidad</th>
                                <th class="w-16">Subtotal</th>
                                <th class="w-16"></th>
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
                                    <small class="error-control" v-if="errors[`items.${index}.product_id`]">
                                        {{errors[`items.${index}.product_id`][0]}}
                                    </small>
                                </td>
                                <td>
                                    <v-select 
                                    label="abreviacion" 
                                    :options="item.units"
                                    @input="onUnit(index,$event)">
                                    </v-select>
                                    
                                </td>
                                <td>
                                    <span class="w-16">
                                        {{item.stock}}
                                    </span>
                                </td>
                                <td>
                                    <span class="w-16">
                                        {{item.sale_price}}
                                    </span>
                                </td>
                                <td>
                                    <input 
                                        type="number" 
                                        class="w-16 form-control form-input form-input-bordered" 
                                        v-model="item.quantity"
                                        @input="verify(index,item)">
                                     <small class="error-control" v-if="errors[`items.${index}.quantity`]">
                                        {{errors[`items.${index}.quantity`][0]}}
                                    </small>
                                </td>
                                <td>
                                    <span class="w-16">{{Number(item.sale_price) * Number(item.quantity) | formatMoney }}</span>
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
                                <td></td>
                              </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>    
                <hr>
                <div class="container mx-auto px-4" v-show="form.facturar">
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
                                <td>{{ ImportBaseDebitoF }}</td>
                                <td>{{ DebitoFiscal }}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex flex-col bg-gray-200">
                    <div class="text-gray-700 text-rigth bg-gray-400 px-4 py-2 m-2">
                       <label>Observacion:</label>
                        <textarea 
                            class="w-full form-control form-input form-input-bordered py-3 h-auto"
                            v-model="form.observations">
                        </textarea>
                        <small class="error-control" v-if="errors.observations">
                            {{errors.observations[0]}}
                        </small>
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
import "vue-select/dist/vue-select.css"
import vSelect from "vue-select"
import { filtro } from '../../components/typeahead'

function initialize(to) {
    let urls = {
        'create': `/nova-vendor/purchase/sales/create`,
        'edit': `/nova-vendor/purchase/sales/${to.params.id}/edit`
    }
    return (urls[to.meta.mode] || urls['create'])
}
export default {
    components: {
      vSelect,
      filtro
    },
    data() {
        return {
            form: {},
            errors: {},
            products:[],
            resourceName: 'Venta',
            resource: '/invoices',
            store: '/nova-vendor/purchase/sales',
            method: 'POST',
            cargando: false,
            isProcessing: false,
            typePaymentsURL: '/nova-vendor/purchase/selectypepayments',
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
                    return carry + (Number(item.sale_price) * Number(item.quantity))
                }, 0)
            }
        },
        //subtotal con factura
        SubTotal(){
            this.form.amount = this.subTotal;
            let subtotal = this.form.facturar ? (this.subTotal - this.form.exempt_amount) : this.subTotal;
            this.form.subtotal = subtotal;
            return subtotal;
        },
         Vuelto() {
            let cambio = (this.form.received > this.SubTotal) ? (this.form.received - this.subTotal) :0;
            this.form.turned = cambio;
            return cambio;
        },
        ImportBaseDebitoF(){
            let bcr = this.form.facturar ? (this.SubTotal - this.form.discount) : 0;
            this.form.amount_base = bcr;
            return bcr;
        },
        DebitoFiscal(){
            let cr = this.form.facturar ? ((this.SubTotal - this.form.discount) * 0.13) : 0;
            this.form.debito_fiscal = cr;
            return cr;
        },
        FechaLimite(){
            let fecha = (this.form.typepayment == this.form.defaultpaymet_for_creditsales) ? moment().format('YYYY-MM-DD'): null;
            this.form.deadline = fecha;
            return fecha;
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
                this.store = `/nova-vendor/purchase/sales/${this.$route.params.id}`
                this.method = 'PUT'
                this.title = 'Edit'
            }
            this.cargando = false
        },
        selectCustomer(search,loading){
            let me = this;
            loading(true)
            var url= '/nova-vendor/purchase/selectcustomers?q='+search;
            axios.get(url).then(function (response) { 
                me.form.customers = response.data.results;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        onCustomer(val) {
          Vue.set(this.$data.form, 'customer', val)
          Vue.set(this.$data.form, 'contact_id', val.id)
          Vue.set(this.$data.form, 'document', val.ci)
        },
        selectProduct(search,loading){
            let me = this;
            loading(true)
            var url= '/nova-vendor/purchase/selectproduct?q='+search;
            axios.get(url).then(function (response) { 
                me.products = response.data.results;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        onProduct(index, val) {
            Vue.set(this.form.items[index], 'product', val)
            Vue.set(this.form.items[index], 'product_id', val.COD)
            Vue.set(this.form.items[index], 'stock', val.stock)
            Vue.set(this.form.items[index], 'units', val.units)
            Vue.set(this.form.items[index], 'almacen', val.idalmacen)
        },
        onUnit(index, val) {
            let unidad = val.pivot
            Vue.set(this.form.items[index], 'sale_price',Number(unidad.precio))
            Vue.set(this.form.items[index], 'quantity_unit',Number(unidad.cantidad_unidad))
            Vue.set(this.form.items[index], 'unit_id',Number(unidad.unit_id))    
        },
        removeItem(index){
            this.form.items.splice(index, 1)
        },
        addNewLine() {
            this.form.items.push({
                product_id: null,
                product: null,
                units: [],
                sale_price: 0,
                stock: 0,
                almacen: null,
                quantity: 1,
                quantity_unit: 0,
                unit_id: null
            })
        },
        onCancel() {
            if(this.$route.meta.mode === 'edit') {
                this.$router.push(`${this.resource}/${this.form.id}`)
            } else {
                this.$router.push({name:'sales'})
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
                        this.$toasted.show('Venta Registrada con exito', { type: 'success' });
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
        verify(index,item){
         if (item.quantity > Number(item.stock)) {
              this.$toasted.show('la cantidad es mayor que el stock', { type: 'warning' });
         }
        }
    }
}
</script>