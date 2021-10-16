<template>
    <auth-layout>
        <template #toolbar>
            <section>
                <h3 class="font-bold text-2xl">Bienvenid@</h3>
                <p class="text-gray-600 pt-2">Crea tu cuenta</p>
            </section>
        </template>

        <form
            @submit.prevent="register"
            class="flex flex-col"
        >
            <div class="mb-6 pt-3 rounded bg-gray-200">
                <label
                    class="block text-gray-700 text-sm font-bold mb-2 ml-3"
                    for="name"
                >
                    Nombre
                </label>
                <input
                    v-model="form.name"
                    id="name"
                    class="bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3"
                />
                <div v-if="errors.name" class="text-red-500">{{ errors.name }}</div>
            </div>

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
                    Contraseña
                </label>
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    class="bg-gray-200 rounded w-full text-gray-700 focus:outline-none border-b-4 border-gray-300 focus:border-purple-600 transition duration-500 px-3 pb-3"
                />
                <div v-if="errors.password" class="text-red-500">{{ errors.password }}</div>
            </div>

            <div class="mb-6 pt-3 rounded bg-gray-200">
                <label
                    class="block text-gray-700 text-sm font-bold mb-2 ml-3"
                    for="password_confirmation"
                >
                    Confirma la contraseña
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
                Crear mi cuenta
            </button>
        </form>

        <template #footer>
            <p class="text-white">
                ¿Ya tienes una cuenta? <inertia-link :href="route('login')" class="font-bold hover:underline">¡Accede!</inertia-link>
            </p>
        </template>
    </auth-layout>
</template>
<script>
    import AuthLayout from "../../Layouts/AuthLayout";
    export default {
        components: {AuthLayout},
        props: {
            errors: Object,
        },
        data() {
            return {
                processing: false,
                form: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                }
            }
        },
        methods: {
            register() {
                this.processing = true;
                this.$inertia.post(this.route("register"), this.form).then(() => {
                    this.processing = false;
                });
            }
        }
    }
</script>
