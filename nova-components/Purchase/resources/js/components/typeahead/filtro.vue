<template>
  <div
  :class="{ 'opacity-75': disabled }"
    v-on-clickaway="close"
  >
    <div class="relative">
        <div
        ref="input"
        @click="open"
        @focus="open"
        @keydown.down.prevent="open"
        @keydown.up.prevent="open"
        :class="{
          focus: show,
          'border-danger': error,
          'form-select': shouldShowDropdownArrow,
          disabled,
        }"
        class="flex items-center form-control form-input form-input-bordered pr-6"
        :tabindex="show ? -1 : 0"
        >
            <div
            v-if="shouldShowDropdownArrow && !disabled"
            class="search-input-trigger absolute pin select-box"
            />
            <div class="flex items-center">
                {{ selectedText }}
            </div>
        </div>
        <button
        type="button"
        @click.stop="clear"
        v-if="!shouldShowDropdownArrow && !disabled"
        tabindex="-1"
        class="absolute p-2 inline-block"
        style="right: 4px; top: 6px;"
        >
            <svg
            class="block fill-current icon h-2 w-2"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="278.046 126.846 235.908 235.908"
            >
            <path
                d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
            />
            </svg>
        </button>
    </div>
    <div
    v-if="show"
    ref="dropdown"
    class="form-input px-0 border border-60 absolute pin-t pin-l my-1 overflow-hidden"
    :style="{ width: inputWidth + 'px', zIndex: 2000 }"
    >
        <div class="p-2 bg-grey-300">
            <input
            :disabled="disabled"
            ref="search"
            @blur="onBlur"
            @input="onSearch"
            @keydown.esc="onEsc"
            @keydown.up="onUpKey"
            @keydown.down="onDownKey"
            @keydown.enter="onEnterKey"
            class="outline-none search-input-input w-full px-2 py-1.5 text-sm leading-normal bg-white rounded"
            tabindex="-1"
            type="text"
            :placeholder="__('Search')"
            spellcheck="false"
            />
        </div>
        <div
        ref="container"
        v-if="results.length"
        class="search-input-options relative overflow-y-scroll scrolling-touch text-sm"
        tabindex="-1"
        style="max-height: 155px;"
        >
            <div
            v-for="(option, index) in results"
            :key="option.id"
            class="px-4 py-2 cursor-pointer"
            :class="{
                [`search-input-item-${index}`]: true,
                'hover:bg-30': index !== selected,
                'bg-primary text-white': index === selected,
            }"
            >
                <div
                class="flex items-center text-sm font-semibold leading-5 text-90"
                :class="{ 'text-white': selected }"
                @mousedown.prevent="select(option)"
                @mouseover.prevent="onMouse(index)"
                >
                {{option.text}}
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
import Vue from 'vue'
import { mixin as clickaway } from 'vue-clickaway'
import { get } from '../../lib/api'
export default {
    mixins: [clickaway],
    props: {
        initialize: {
        default: null
        },
        url: {
            required: true
        },
        disabled: {default: false},
        error: {
            type: Boolean,
            default: false,
        },
        clearable: {
            type: Boolean,
            default: true,
        }
    },
    data: () => ({
        selectIndex: -1,
        show: false,
        inputWidth: null,
        search: '',
        selected: 0,
        results: []
    }),
    computed: {
        shouldShowDropdownArrow() {
            return this.value == '' || this.value == null || !this.clearable
        },
        selectedText() {
            return this.initialize && this.initialize.text
                ? this.initialize.text
                : 'Clic para seleccionar'
        }
    },
    methods:{
        fetchData(q) {
            get(this.url, {q: q})
                .then((res) => {
                Vue.set(this.$data, 'results', res.data.results)
            })
        },
        open() {
            if (!this.disabled) {
                this.show = true
                this.search = ''
            }
        },
        onSearch(e) {
            const q = e.target.value
            this.selectIndex = 0
            this.fetchData(q)
        },
        onBlur() {
            this.close()
        },
        onEsc() {
            this.close()
        },
        close() {
            this.show = false
            this.selectIndex = -1
            this.results = []
            this.search = ''
        },
        clear() {
            if (!this.disabled) {
                this.selected = null
                this.$emit('clear', null)
            }
        },
        onUpKey(e) {
            if(this.selectIndex > 0) {
                this.selectIndex--
            }
        },
        onDownKey(e) {
            if(this.results.length - 1 > this.selectIndex) {
                this.selectIndex++
            }
        },
        onEnterKey() {
            const found = this.results[this.selectIndex]
            if(found) {
                this.select(found)
            }
        },
        select(result) {
            this.$emit('input', {
                target: {
                    value: result
                }
            })
            this.close()
        },
        onMouse(index) {
            this.selectIndex = index
        }
    }
}
</script>

<style>

</style>