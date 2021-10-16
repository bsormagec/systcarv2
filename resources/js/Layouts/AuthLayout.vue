<template>
    <div>
        <header class="max-w-lg mx-auto">
            <a href="#">
                <h1 class="text-4xl font-bold text-white text-center mt-5">SYSTCARV</h1>
            </a>
        </header>

        <main class="bg-white max-w-lg mx-auto p-8 md:p-12 my-4 rounded-lg shadow-2xl">

            <flash-messages />

            <slot name="toolbar" />

            <section class="mt-4">
                <slot />
            </section>
        </main>

        <div class="max-w-lg mx-auto text-center mt-12 mb-6">
            <slot name="footer" />
        </div>
    </div>
</template>

<script>
    import FlashMessages from "../Components/UI/FlashMessages";
    export default {
        name: "AuthLayout",
        components: {FlashMessages},
    }
</script>
