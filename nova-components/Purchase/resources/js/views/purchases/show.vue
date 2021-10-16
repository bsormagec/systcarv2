<template>
    <loading-view :loading="initialLoading">
    <div v-if="show">
        <div class="flex items-center mb-3">
            <heading :level="1" class="flex-no-shrink">Compra</heading>
            <div class="ml-3 w-full flex items-center">
                <router-link 
                    to="/purchase" 
                    class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
                    >{{ __('Back') }}
                </router-link>
                <!-- <router-link :to="`/purchase/${model.id}/edit`"
                  class="btn btn-default btn-icon bg-primary"
                  :title="__('Edit')">
                  <icon
                    type="edit"
                    class="text-white"
                    style="margin-top: -2px; margin-left: 3px;"
                  />
                </router-link> -->
                <button
                  data-testid="open-delete-modal"
                  dusk="open-delete-modal-button"
                  @click="openDeleteModal" 
                  class="btn btn-default btn-icon btn-white mr-3" 
                  :title="__('Anular Compra')"
                >
                 <icon type="delete" class="text-80" />
                </button>
                <portal
                  to="modals"
                  v-if="deleteModalOpen"
                >
                <delete-resource-modal
                    v-if="deleteModalOpen"
                    @confirm="deleteItem"
                    @close="closeDeleteModal"
                    mode="delete"
                />
                </portal>
            </div>
        </div>
        <card class="py-3">
        <div class="flex border-b border-40">
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mt-2 px-2">
                <strong>Proveedor:</strong>
                <span>{{model.provider.business_name}}</span>
                <pre>Direccion: {{model.provider.address}}</pre>
            </div>
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mt-2 bg-gray-500 px-2">
               <table>
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <strong>Datos de Factura</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Numero:</td>
                            <td>{{model.invoice_number}}</td>
                        </tr>
                        <tr>
                            <td>Fecha:</td>
                            <td>{{model.date}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container mt-4">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in model.items" :key="item.id">
                        <td class="text-center">{{item.product.id}}</td>
                        <td class="text-center">
                            <pre>{{item.product.nombre}}</pre>
                        </td>
                        <td class="text-center">{{item.purchase_price | formatMoney}}</td>
                        <td class="text-center">{{item.quantity}}{{item.unit.abreviacion}}</td>
                        <td class="text-center">{{item.quantity * item.purchase_price | formatMoney}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total</td>
                        <td class="text-center">{{model.sub_total | formatMoney}}</td>
                    </tr>
                </tfoot>
            </table>
            
        </div>
        <div class="mx-4 my-4">
            <strong>Observacion</strong>
            <pre>{{model.observation}}</pre>
        </div>
        </card>
    </div>
    </loading-view>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import {get, byMethod} from '../../lib/api'
    export default {
        data () {
            return {
                initialLoading: true,
                show: false,
                deleteModalOpen: false,
                model: {
                    items: [],
                    customer: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            Nova.request().get(`/nova-vendor/purchase/purchases/${to.params.id}`)
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        beforeRouteUpdate(to, from, next) {
            this.show = false
            Nova.request().get(`/nova-vendor/purchase/purchases/${to.params.id}`)
                .then((res) => {
                    this.setData(res)
                    next()
                })
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.model)
                this.show = true
                this.initialLoading = false
            },
            async deleteItem() {
                byMethod('delete', `/nova-vendor/purchase/purchases/${this.model.id}`)
                    .then((res) => {
                        if(res.data.deleted) { 
                            this.$router.push('/purchase')
                             Nova.success(
                                this.__('Compra Anulada correctamente!')
                                )
                                this.closeDeleteModal()
                        }
                    })
            },
            /**
              * Open the delete modal
            */
            openDeleteModal() {
              this.deleteModalOpen = true
            },

            /**
              * Close the delete modal
            */
            closeDeleteModal() {
              this.deleteModalOpen = false
            }
        },
        filters: {
            formatMoney: function (value) {
                return Number(value)
                    .toFixed(2)
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            }
        }
    }
</script>