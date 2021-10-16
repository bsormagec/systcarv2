<template>
    <auth-layout>
        <template #toolbar>
            <section>
                <h3 class="font-bold text-2xl">¡Hola!</h3>
                <p class="text-green-600 pt-2">
                    ¡Gracias por registrarte! Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar? Si no recibió el correo electrónico, con gusto le enviaremos otro.
                </p>
            </section>
        </template>
        <form
            @submit.prevent="sendNewVerificationEmail"
            class="flex flex-col"
        >
            <button
                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded shadow-lg hover:shadow-xl transition duration-200"
                type="submit"
            >
                Reenviar correo electrónico de verificación
            </button>
        </form>
    </auth-layout>
</template>
<script>
    import AuthLayout from "../../Layouts/AuthLayout";
    export default {
        components: {AuthLayout},
        methods: {
            sendNewVerificationEmail() {
                this.$inertia.post(this.route('verification.send')).then(() => {

                })
            }
        }
    }
</script>
