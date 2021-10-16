<template>
    <div class="flex flex-nowrap justify-center py-4 space-x-4">
        <button 
            :disabled="pagination.current_page <= 1"
            class="btn btn-sm btn-outline inline-flex items-center focus:outline-none focus:shadow-outline active:outline-none active:shadow-outline" 
            @click.prevent="changePage(1)"
            >
            Primer pagina
        </button>
        <button 
            :disabled="pagination.current_page <= 1"
            class="btn btn-sm btn-outline inline-flex items-center focus:outline-none focus:shadow-outline active:outline-none active:shadow-outline" 
            @click.prevent="changePage(pagination.current_page - 1)"
            >
            Anterior
        </button>
        <div v-for="page in pages"  :key="page" :class="isCurrentPage(page) ? 'active' : ''">
            <button 
            class="btn btn-sm btn-outline inline-flex items-center focus:outline-none focus:shadow-outline active:outline-none active:shadow-outline" 
            @click.prevent="changePage(page)"
            >
            {{ page }}
                <span v-if="isCurrentPage(page)" class="text-purple-600">(Actual)</span>
            </button>
        </div>
        <button 
            :disabled="pagination.current_page >= pagination.last_page"
            class="btn btn-sm btn-outline inline-flex items-center focus:outline-none focus:shadow-outline active:outline-none active:shadow-outline" 
            @click.prevent="changePage(pagination.current_page + 1)"
            >
            Siguiente
        </button>
        <button 
            :disabled="pagination.current_page >= pagination.last_page"
            class="btn btn-sm btn-outline inline-flex items-center focus:outline-none focus:shadow-outline active:outline-none active:shadow-outline" 
            @click.prevent="changePage(pagination.last_page)"
            >
            Ultima pagina
        </button>
    </div>
</template>

<script>
    export default {
        props:['pagination', 'offset'],
        methods: {
            isCurrentPage(page){
                return this.pagination.current_page === page
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = []

                let from = this.pagination.current_page - Math.floor(this.offset / 2)

                if (from < 1) {
                    from = 1
                }

                let to = from + this.offset -1

                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page
                }

                while (from <= to) {
                    pages.push(from)
                    from++
                }

                return pages
            }
        }
    }
</script>