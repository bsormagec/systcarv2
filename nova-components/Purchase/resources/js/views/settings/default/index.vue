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
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Ventas a Credito
                        </label>
                        <select class="w-full form-control form-select" v-model="form['default.type_payment_tocreditsales']">
                            <option v-for="item in form.typepayments" :value="item.id" :key="item.id">
                                {{item.description}}
                            </option>
                        </select>
                        <p class="text-80 text-sm font-semibold italic mb-3">
                            Tipo de pago que utilizara por defecto para las ventas a creditos.
                        </p>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-2 px-2">
                        <label class="inline-block text-80 pt-2 leading-tight">
                            Ventas al Contado
                        </label>
                        <select class="w-full form-control form-select" v-model="form['default.type_payment_to_chash']">
                            <option v-for="item in form.typepayments" :value="item.id" :key="item.id">
                                {{item.description}}
                            </option>
                        </select>
                         <p class="text-80 text-sm font-semibold italic mb-3">
                            Tipo de pago que utilizara por defecto para las ventas al contado.
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
        'create': `/nova-vendor/purchase/settings/default`
    }
    return (urls['create'])
}
export default {
    data() {
        return {
            resourceName: 'Default',
            cargando: true,
            form:{},
            errors: {},
            store: '/nova-vendor/purchase/settings/updatedefault',
            method: 'POST',
        }
    },
    beforeRouteEnter(to, from, next) {
        get(initialize(to))
            .then((res) => {
                next(vm => vm.setData(res))
        })
    },
    methods: {
        setData(res) {
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