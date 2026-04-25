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
    <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
        <div v-if="show" class="fixed left-1/2 top-1/2 z-50 w-[min(90vw,650px)] -translate-x-1/2 -translate-y-1/2 rounded-2xl border border-green-300 bg-green-500 px-8 py-6 text-center text-xl font-bold text-white shadow-2xl">
            {{ message }}
        </div>
    </transition>

</template>