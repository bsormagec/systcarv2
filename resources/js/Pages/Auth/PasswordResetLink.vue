<template>
    <auth-layout>
        <template #toolbar>
            <section>
                <h3 class="font-bold text-2xl">Bienvenid@</h3>
                <p class="text-gray-600 pt-2">Utiliza el formulario para recuperar tu contraseña</p>
            </section>
        </template>

        <form
            @submit.prevent="requestPasswordLink"
            class="flex flex-col"
        >
            <div class="mb-6 pt-3 rounded bg-gray-200">
                <label
                    class="block text-gray-700 text-sm font-bold mb-2 ml-3"
                    for="email"
                >
                    Introduce tu correo electrónico
                </label>
                <input
                    v-model="form.email"
                    id="email"
                    class="bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3"
                />
                <div v-if="errors.email" class="text-red-500">{{ errors.email }}</div>
            </div>
            <button
                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded shadow-lg hover:shadow-xl transition duration-200"
                type="submit"
            >
                Recuperar mi contraseña
            </button>
        </form>

        <template #footer>
            <p class="text-white">¿Has recordado tu contraseña? <inertia-link :href="route('login')" class="font-bold hover:underline">¡Accede a tu cuenta!</inertia-link></p>
        </template>
    </auth-layout>
</template>

<script>
    import AuthLayout from "../../Layouts/AuthLayout";
    export default {
        name: "PasswordResetLink",
        components: {AuthLayout},
        props: {
            errors: Object,
        },
        data() {
            return {
                form: {
                    email: null
                }
            }
        },
        methods: {
            requestPasswordLink() {
                this.$inertia.post(this.route("password.email"), this.form).then(() => {

                })
            }
        }
    }
</script>
