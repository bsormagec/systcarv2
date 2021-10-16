<template>
    <modal @modal-close="handleClose" classWhitelist="flatpickr-calendar">
        <form
            @submit.prevent="handleSave"
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            style="width: 460px"
        >
            <slot>
                <div class="p-8">
                    <heading v-if="!currentEvent" :level="2" class="mb-6">{{ __('Create Appointment') }}</heading>
                    <heading v-if="currentEvent" :level="2" class="mb-6">{{ __('Edit Appointment') }}</heading>
                    <div class="border-b border-40 pb-4">
                        <label for="title" class="mb-2 text-80 leading-tight">{{ __('Cliente')}}:</label>
                         <v-select
                                v-model="form.customer"
                                :filterable="false"
                                @search="selectCustomer"
                                label="fullName"
                                :options="customers"
                                placeholder="nombre....."
                                @input="onCustomer"                              
                            />
                        <small class="errors" v-if="errors.contact_id">
                            {{errors.contact_id[0]}}
                        </small>
                    </div>
                    <div class="border-b border-40 py-4">
                        <label for="title" class="mb-2 text-80 leading-tight">{{ __('Description')}}:</label>
                        <input v-model="form.title" name="title" class="w-full form-control form-input form-input-bordered" />
                    </div>
                    <div class="border-b border-40 py-4">
                        <label for="start" class="mb-2 text-80 leading-tight">{{ __('StartDate')}}:</label>
                        <date-time-picker @change="changeStart" v-model="form.start" name="start" class="w-full form-control form-input form-input-bordered" autocomplete="off" />
                    </div>
                    <div class="border-b border-40 py-4">
                        <label for="end" class="mb-2 text-80">{{ __('EndtDate')}}:</label>
                        <date-time-picker @change="changeEnd" v-model="form.end" name="end" class="w-full form-control form-input form-input-bordered" autocomplete="off" />
                    </div>
                    <div class="border-b border-40 py-4">
                        <label for="status" class="mb-2 text-80">{{ __('Status')}}:</label>
                        <select v-model="form.status" class="w-full form-control form-select">
                            <option value="R">Reservado</option>
                            <option value="C">Confirmado</option>
                            <option value="S">Servido</option>
                        </select>
                    </div>
                </div>
            </slot>

            <div class="btn-wrapper bg-30 px-6 py-3">
                <button v-if="currentEvent" @click.prevent="handleDelete"  type="button" class="btn btn-default btn-danger delete-event">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                </button>
                <button @click.prevent="handleClose" type="button" class="btn text-80 font-normal h-9 px-3 btn-link">{{__('Cancel')}}</button>
                <button @click.prevent="handleSave" ref="saveButton" type="submit" class="btn btn-default btn-primary ml-3">{{ __('Save') }}</button>
            </div>
        </form>
    </modal>
</template>

<script>
import Vue from 'vue'
import "vue-select/dist/vue-select.css"
import vSelect from "vue-select"

    export default {
        components: {vSelect},
        name: 'EventModal',
        props: ['currentEvent', 'currentDate'],
        data() {
            return {
                form:{
                    title: this.currentEvent !== null ? this.currentEvent.event.title : '',
                    start: moment(this.currentEvent !== null ? this.currentEvent.event.start : this.currentDate.date).format('YYYY-MM-DD HH:mm:ss'),
                    end: this.currentEvent !== null ? moment(this.currentEvent.event.end).format('YYYY-MM-DD HH:mm:ss') : moment(this.currentDate.date).add(1, 'hour').format('YYYY-MM-DD HH:mm:ss'),
                    customer: this.currentEvent !== null ? this.currentEvent.event.extendedProps.customer : null,
                    fecha: this.currentEvent !== null ? this.currentEvent.event.extendedProps.fecha : this.currentDate.dateStr,
                    status: this.currentEvent !== null ? this.currentEvent.event.extendedProps.status : 'R',
                    contact_id: null,
                    document: '',
                },
                customers: [],
                errors: {}
            } 
        },
        mounted() {
            
        },
        methods: {
            changeStart(value) {
                this.start = value;
            },
            changeEnd(value) {
                this.end = value;
            },
            handleClose() {
                this.$emit('close');
            },
            handleDelete() {
                Nova.request()
                    .delete('/nova-vendor/appointments/citas/'+this.currentEvent.event.id)
                    .then(response => {
                        if (response.data.success) {
                            this.$toasted.show('Cita eliminada correctamente', { type: 'success' });
                            this.$emit('close');
                            this.$emit('refreshEvents');
                        }
                    })
                    .catch(response => this.$toasted.show('Something went wrong', { type: 'error' }));
            },
            handleSave() {
                if (this.currentEvent === null) {
                    Nova.request()
                        .post('/nova-vendor/appointments/citas',this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$toasted.show('La cita a sido creada', { type: 'success' });
                                this.$emit('close');
                                this.$emit('refreshEvents');
                            } else if (response.data.error === true) {
                                this.$toasted.show(response.data.message, { type: 'error' });
                            }
                        })
                        .catch(response => this.$toasted.show('Algo salio mal', { type: 'error' }));
                } else if (this.currentEvent !== null) {
                    Nova.request()
                        .put('/nova-vendor/appointments/citas/'+this.currentEvent.event.id, this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$toasted.show('La cita a sido actualizada', { type: 'success' });
                                this.$emit('close');
                                this.$emit('refreshEvents');
                            } else if (response.data.error === true) {
                                this.$toasted.show(response.data.message, { type: 'error' });
                            }
                        })
                        .catch(response => this.$toasted.show('Algo salio mal', { type: 'error' }));
                }
            },
            selectCustomer(search,loading){
                let me = this;
                loading(true)
                var url= '/nova-vendor/purchase/selectcustomers?q='+search;
                axios.get(url).then(function (response) { 
                    me.customers = response.data.results;
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
            }
        },
    }
</script>

<style scoped>
    label {
        display: block;
    }

    .btn-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .btn.delete-event {
        margin-right: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>