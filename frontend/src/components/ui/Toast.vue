<script setup>
defineProps({
    show: {
        type: Boolean,
        default : false
    },
    message:{
        type: String,
        default: ''
    }
})
</script>

<template>
    <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-2">
        <div v-if="show" class="fixed right-6 top-6 z-50 rounded-2xl border border-green-400 bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-2xl">
            {{ message }}
        </div>
    </transition>

</template>