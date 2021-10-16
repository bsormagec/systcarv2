<template>
    <auth-layout>
        <template #toolbar>
            <section>
                <h3 class="font-bold text-2xl">Bienvenid@</h3>
                <p class="text-gray-600 pt-2">Crea una nueva contraseña</p>
            </section>
        </template>

        <form
            @submit.prevent="updatePassword"
            class="flex flex-col"
        >
            <div class="mb-6 pt-3 rounded bg-gray-200">
                <label
                    class="block text-gray-700 text-sm font-bold mb-2 ml-3"
                    for="email"
                >
                    Correo electrónico
                </label>
                <input
                    v-model="form.email"
                    id="email"
                    class="bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3"
                />
                <div v-if="errors.email" class="text-red-500">{{ errors.email }}</div>
            </div>
            <div class="mb-6 pt-3 rounded bg-gray-200">
                <label
                    class="block text-gray-700 text-sm font-bold mb-2 ml-3"
                    for="password"
                >
                    Tu nueva contraseña
                </label>
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    class="bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3"
                />
            </div>
            <div class="mb-6 pt-3 rounded bg-gray-200">
                <label
                    class="block text-gray-700 text-sm font-bold mb-2 ml-3"
                    for="password_confirmation"
                >
                    Confirma tu nueva contraseña
                </label>
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    id="password_confirmation"
                    class="bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3"
                />
            </div>
            <button
                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded shadow-lg hover:shadow-xl transition duration-200"
                type="submit"
            >
                Actualizar mi contraseña
            </button>
        </form>

        <template #footer>
            <p class="text-white">¿Ya tienes una cuenta? <inertia-link :href="route('login')" class="font-bold hover:underline">¡Accede!</inertia-link></p>
        </template>
    </auth-layout>
</template>

<script>
    import AuthLayout from "../../Layouts/AuthLayout";
    export default {
        name: "ResetPassword",
        components: {AuthLayout},
        props: {
            errors: Object,
        },
        data() {
            return {
                form: {
                    email: null,
                    password: null,
                    password_confirmation: null
                }
            }
        },
        methods: {
            updatePassword() {
                this.form.token = this.$page.request.token;
                this.$inertia.post(this.route("password.update"), this.form).then(() => {

                });
            }
        }
    }
</script>
