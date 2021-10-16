<template>
    <div>
        <div class="p-4">
            <custom-create-header class="mb-3" :resource-name="resourceName" />
            <heading :level="1" class="pmb-3">{{resourceName}}</heading>
        </div>
        <loading-view :loading="cargando">
            <card>
                <div class="flex border-b border-40 mb-2 p-2">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 px-2">
                        <p class="text-80 text-sm font-semibold italic mb-3">Registro con Factura?
                            <input class="checkbox" type="checkbox" v-model="form['invoices.invoice_the_sale']">
                        </p>
                    </div>
                </div>
                <div class="text-gray-700 text-rigth bg-gray-400 px-4 py-2 m-2">
                    <button 
                        class="btn btn-default btn-primary flex-no-shrink ml-auto rounded-full" 
                        type="button"
                        :disabled="cargando" 
                        @click="onSave">
                            Guardar
                    </button>
                    <button class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6" 
                            type="button"
                            :disabled="cargando" 
                            @click="onCancel">Cancelar
                    </button>
                </div>
            </card>
        </loading-view>
    </div>
</template>
<script>
import Vue from 'vue'
import {get, byMethod } from '../../../lib/api'

function initialize(to) {
    let urls = {
        'create': `/nova-vendor/purchase/settings/invoices`
    }
    return (urls['create'])
}
export default {
    data() {
        return {
            resourceName: 'Invoice',
            cargando: true,
            form:{},
            errors: {},
            store: '/nova-vendor/purchase/settings/updateinvoices',
            method: 'POST',
        }
    },
    beforeRouteEnter(to, from, next) {
        get(initialize(to))
            .then((res) => {
                if (res.data.plan == 'oro') {
                  next(vm => vm.setData(res))  
                }else{
                    next({ name: 'indexsettings' })
                }
        })
    },
    methods: {
        setData(res) {
            console.log(res.data)
            Vue.set(this.$data, 'form', res.data.form)
            this.cargando = false
        },
        onCancel(){
            this.$router.push({name:'indexsettings'})
        },
        onSave() {
            this.errors = {}
            this.cargando = true
            byMethod(this.method, this.store, this.form)
                .then((res) => {
                    console.log(res)
                    if(res.data.saved) {
                         Nova.success("Datos actualizados correctamente..!")
                    }
                    this.cargando = false
                })
                .catch((error) => {
                    if(error.response.status === 422) {
                        this.errors = error.response.data.errors
                    }
                    this.cargando = false
                })
        }
    },
}
</script>