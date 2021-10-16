<template>
    <div>
        <heading class="mb-6">Citas Médicas</heading>
        <div class="card py-6 px-6">
            <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        </div>
        <EventModal
            v-if="showModal"
            :currentEvent="currentEvent"
            :currentDate="currentDate"
            @refreshEvents="refreshEvents"
            @close="closeModal"
        />
       
    </div>
</template>

<script>
import '@fullcalendar/core/vdom' // solves problem with Vite
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction'
import allLocales from '@fullcalendar/core/locales-all'
import EventModal from '../../components/EventoModalCustomer.vue'

export default {
    components: {
        FullCalendar,
        EventModal
    },
    metaInfo() {
        return {
          title: 'Appointments',
        }
    },
    data() {
        return {
            calendarOptions: {
                events: '/nova-vendor/appointments/citas',
                plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
                initialView: 'dayGridMonth',
                locale: Nova.config.fullcalendar_locale || 'en',
                dateClick: this.handleDateClick,
                eventClick: this.handleEventClick,
                editable: true,
                headerToolbar: {
                    left: 'prev,next today myBoton',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText:{
                    today: 'Hoy dia',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Dia',
                },
                customButtons:{
                    myBoton:{
                        text: 'Reporte',
                        click: function() {
                            alert('Reporte');
                        }
                    }
                },
                eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    },
                timeFormat: 'H(:mm)'
            },
            currentEvent: null,
            currentDate: null,
            showModal: false
        }
    },
    mounted() {
        
    },
    methods: {
        handleDateClick(date) {
            this.showModal = true;
            this.currentDate = date;
        },
        handleEventClick(event) {
            this.showModal = true;
            this.currentEvent = event;
        },
        closeModal() {
            this.showModal = false;
            this.currentEvent = null;
            this.currentDate = null;
        },
        refreshEvents() {
            this.$refs.fullCalendar.getApi().refetchEvents();
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
